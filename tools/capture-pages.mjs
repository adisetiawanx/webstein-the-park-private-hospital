// Capture every page at the design's own canvas width so the two can be read
// side by side.
//
// Screenshots are stitched from viewport-sized slices rather than taken with
// fullPage. Chrome's fullPage capture does not reliably resolve lazy-loaded
// images in headless — it silently dropped the About photograph on one run and
// the testimonial photograph on another, which would have read as a missing
// image in the design comparison. Scrolling and shooting one viewport at a time
// only ever captures what is genuinely painted.
//
//   node compare.mjs
import fs from 'fs';
import puppeteer from 'puppeteer-core';

const CHROME = 'C:/Program Files/Google/Chrome/Application/chrome.exe';
const BASE = 'http://localhost:10010';
const OUT = 'shots/build';
const WIDTH = 1920;
const VH = 1000;

const PAGES = [
  ['home', '/'],
  ['about', '/about/'],
  ['our-team', '/about/our-team/'],
  ['visitors', '/for-patients-visitors/'],
  ['preparing', '/for-patients-visitors/preparing-for-your-admission/'],
  ['postop', '/for-patients-visitors/post-operative-care/'],
  ['rights', '/for-patients-visitors/patient-rights-responsibilities/'],
  ['doctors', '/for-doctors/'],
  ['safety', '/safety-and-quality/'],
  ['careers', '/careers/'],
  ['contact', '/contact-us/'],
];

const browser = await puppeteer.launch({
  executablePath: CHROME,
  headless: 'new',
  args: ['--no-sandbox', '--disable-gpu', '--hide-scrollbars'],
});

const page = await browser.newPage();
await page.setViewport({ width: WIDTH, height: VH, deviceScaleFactor: 1 });

/**
 * Scroll through the page one viewport at a time, shooting each, and report the
 * slice files plus the total document height.
 */
async function captureSlices( name ) {
  // A sticky header would repeat in every slice. Pinning it makes it appear
  // once, at the top, exactly as the artboard has it.
  await page.addStyleTag( {
    content: '.site-header{position:static !important}',
  } );

  const height = await page.evaluate( () => document.body.scrollHeight );
  const slices = [];

  for ( let y = 0, i = 0; y < height; y += VH, i++ ) {
    await page.evaluate( ( top ) => window.scrollTo( 0, top ), y );
    // Give lazy images in the new viewport time to fetch and decode.
    await page.evaluate( async () => {
      await Promise.all(
        Array.from( document.images )
          .filter( ( img ) => {
            const r = img.getBoundingClientRect();
            return r.bottom > -200 && r.top < window.innerHeight + 200;
          } )
          .map( ( img ) =>
            img.complete && img.naturalWidth
              ? Promise.resolve()
              : new Promise( ( res ) => {
                  img.addEventListener( 'load', res, { once: true } );
                  img.addEventListener( 'error', res, { once: true } );
                  setTimeout( res, 5000 );
                } )
          )
      );
    } );
    await new Promise( ( r ) => setTimeout( r, 320 ) );

    const file = `${ OUT }/_slice-${ name }-${ i }.png`;
    await page.screenshot( { path: file } );
    slices.push( file );
  }

  return { slices, height };
}

const manifest = {};

for ( const [ name, path ] of PAGES ) {
  await page.goto( BASE + path, { waitUntil: 'networkidle0', timeout: 60000 } );
  const { slices, height } = await captureSlices( name );
  manifest[ name ] = { slices, height, vh: VH, width: WIDTH };
  console.log( `${ name }: ${ slices.length } slices, ${ height }px` );
}

// The dropdown preview artboard: homepage with For Patients & Visitors open.
await page.goto( BASE + '/', { waitUntil: 'networkidle0' } );
await new Promise( ( r ) => setTimeout( r, 600 ) );
const parents = await page.$$( '.nav__item--has-children' );
await parents[ 1 ].hover();
await new Promise( ( r ) => setTimeout( r, 700 ) );
await page.screenshot( {
  path: `${ OUT }/dropdown.png`,
  clip: { x: 0, y: 0, width: WIDTH, height: 700 },
} );
console.log( 'dropdown captured' );

fs.writeFileSync( `${ OUT }/manifest.json`, JSON.stringify( manifest, null, 1 ) );

await browser.close();
