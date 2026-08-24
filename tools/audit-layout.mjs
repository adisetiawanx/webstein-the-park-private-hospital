// Sweep every page at three viewports and report layout faults.
//   node audit.js
const puppeteer = require('puppeteer-core');

const CHROME = 'C:/Program Files/Google/Chrome/Application/chrome.exe';
const BASE = 'http://localhost:10010';

const PAGES = [
  '/',
  '/about/',
  '/about/our-team/',
  '/for-patients-visitors/',
  '/for-patients-visitors/preparing-for-your-admission/',
  '/for-patients-visitors/post-operative-care/',
  '/for-patients-visitors/patient-rights-responsibilities/',
  '/for-doctors/',
  '/safety-and-quality/',
  '/careers/',
  '/contact-us/',
  '/make-a-payment/',
  '/no-such-page/',
];

const VIEWPORTS = [
  { name: 'mobile', width: 390, height: 844 },
  { name: 'tablet', width: 834, height: 1112 },
  { name: 'desktop', width: 1440, height: 900 },
];

(async () => {
  const browser = await puppeteer.launch({
    executablePath: CHROME,
    headless: 'new',
    args: ['--no-sandbox', '--disable-gpu', '--hide-scrollbars'],
  });

  const problems = [];

  for (const vp of VIEWPORTS) {
    const page = await browser.newPage();
    await page.setViewport({
      width: vp.width,
      height: vp.height,
      isMobile: vp.width < 768,
      hasTouch: vp.width < 768,
    });

    for (const path of PAGES) {
      const consoleErrors = [];
      page.removeAllListeners('console');
      page.on('console', (m) => {
        if (m.type() === 'error') consoleErrors.push(m.text().slice(0, 120));
      });

      await page.goto(BASE + path, { waitUntil: 'networkidle0', timeout: 60000 });

      const r = await page.evaluate(() => {
        const de = document.documentElement;
        const vw = de.clientWidth;

        // Anything sticking out horizontally, ignoring the off-canvas drawer.
        const overflow = [];
        document.querySelectorAll('body *').forEach((el) => {
          if (el.closest('#primary-navigation') && window.innerWidth < 1024) return;
          const b = el.getBoundingClientRect();
          if (b.width > 0 && (b.right > vw + 2 || b.left < -2)) {
            overflow.push(
              el.tagName.toLowerCase() +
                '.' +
                (el.className || '').toString().split(' ')[0] +
                ' right=' +
                Math.round(b.right)
            );
          }
        });

        // Text small enough to fail Lighthouse's legible-font-size audit.
        const tiny = [];
        document.querySelectorAll('p, li, span, a, td').forEach((el) => {
          if (!el.textContent.trim()) return;
          const fs = parseFloat(getComputedStyle(el).fontSize);
          if (fs && fs < 12) tiny.push(el.tagName.toLowerCase() + ' ' + fs + 'px');
        });

        // Interactive targets smaller than 24px in either axis.
        const small = [];
        document.querySelectorAll('a, button').forEach((el) => {
          const b = el.getBoundingClientRect();
          if (b.width === 0 || b.height === 0) return;
          if (b.width < 24 || b.height < 24) {
            small.push(
              el.tagName.toLowerCase() +
                '.' +
                (el.className || '').toString().split(' ')[0] +
                ' ' +
                Math.round(b.width) +
                'x' +
                Math.round(b.height)
            );
          }
        });

        // Images with no alt attribute at all.
        const noAlt = Array.from(document.images)
          .filter((i) => !i.hasAttribute('alt'))
          .map((i) => i.currentSrc.split('/').pop());

        // Heading order.
        const levels = Array.from(document.querySelectorAll('h1,h2,h3,h4,h5,h6')).map((h) =>
          parseInt(h.tagName[1], 10)
        );
        const jumps = [];
        for (let i = 1; i < levels.length; i++) {
          if (levels[i] - levels[i - 1] > 1) jumps.push(levels[i - 1] + '->' + levels[i]);
        }
        const h1s = document.querySelectorAll('h1').length;

        return {
          scrollWidth: de.scrollWidth,
          viewport: vw,
          overflow: [...new Set(overflow)].slice(0, 6),
          tiny: [...new Set(tiny)].slice(0, 4),
          small: [...new Set(small)].slice(0, 4),
          noAlt: noAlt.slice(0, 4),
          jumps: [...new Set(jumps)],
          h1s,
        };
      });

      const issues = [];
      if (r.scrollWidth > r.viewport + 1) issues.push('h-scroll ' + r.scrollWidth + '>' + r.viewport);
      if (r.overflow.length) issues.push('overflow: ' + r.overflow.join(', '));
      if (r.tiny.length) issues.push('tiny text: ' + r.tiny.join(', '));
      if (r.small.length) issues.push('small targets: ' + r.small.join(', '));
      if (r.noAlt.length) issues.push('no alt: ' + r.noAlt.join(', '));
      if (r.jumps.length) issues.push('heading jumps: ' + r.jumps.join(', '));
      if (r.h1s !== 1) issues.push('h1 count = ' + r.h1s);
      if (consoleErrors.length) issues.push('console: ' + consoleErrors.join(' | '));

      if (issues.length) {
        problems.push(vp.name + '  ' + path + '\n    - ' + issues.join('\n    - '));
      }
    }

    await page.close();
  }

  if (!problems.length) {
    console.log('clean: no layout, contrast-size, alt or heading faults found');
  } else {
    console.log(problems.join('\n'));
  }

  await browser.close();
})();
