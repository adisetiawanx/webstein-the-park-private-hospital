// Run Lighthouse against a set of URLs, mobile and desktop, and print the
// four category scores plus any audit that did not pass.
//   node lh.js [--verbose]
import lighthouse from 'lighthouse';
import * as chromeLauncher from 'chrome-launcher';

const BASE = 'http://localhost:10010';
const VERBOSE = process.argv.includes('--verbose');

const URLS = process.argv.filter((a) => a.startsWith('/')).length
  ? process.argv.filter((a) => a.startsWith('/'))
  : ['/', '/about/our-team/', '/for-patients-visitors/preparing-for-your-admission/', '/careers/'];

const CATEGORIES = ['performance', 'accessibility', 'best-practices', 'seo'];

const PRESETS = {
  mobile: {
    formFactor: 'mobile',
    screenEmulation: { mobile: true, width: 412, height: 823, deviceScaleFactor: 1.75, disabled: false },
    throttling: { rttMs: 150, throughputKbps: 1638.4, cpuSlowdownMultiplier: 4, requestLatencyMs: 562.5, downloadThroughputKbps: 1474.56, uploadThroughputKbps: 675 },
  },
  desktop: {
    formFactor: 'desktop',
    screenEmulation: { mobile: false, width: 1350, height: 940, deviceScaleFactor: 1, disabled: false },
    throttling: { rttMs: 40, throughputKbps: 10240, cpuSlowdownMultiplier: 1, requestLatencyMs: 150, downloadThroughputKbps: 9216, uploadThroughputKbps: 9216 },
  },
};

const chrome = await chromeLauncher.launch({
  chromePath: 'C:/Program Files/Google/Chrome/Application/chrome.exe',
  chromeFlags: ['--headless=new', '--no-sandbox', '--disable-gpu'],
});

const rows = [];
const failures = new Map();

for (const url of URLS) {
  for (const [name, preset] of Object.entries(PRESETS)) {
    const result = await lighthouse(
      BASE + url,
      { port: chrome.port, output: 'json', logLevel: 'error' },
      { extends: 'lighthouse:default', settings: { onlyCategories: CATEGORIES, ...preset } }
    );

    const lhr = result.lhr;
    const scores = CATEGORIES.map((c) => Math.round((lhr.categories[c].score || 0) * 100));

    rows.push({ url, device: name, scores, lhr });

    for (const c of CATEGORIES) {
      for (const ref of lhr.categories[c].auditRefs) {
        const a = lhr.audits[ref.id];
        if (!a || a.scoreDisplayMode === 'notApplicable' || a.scoreDisplayMode === 'manual' || a.scoreDisplayMode === 'informative') continue;
        if (a.score === null || a.score >= 0.9) continue;
        const key = `${c} :: ${a.title}`;
        if (!failures.has(key)) failures.set(key, new Set());
        failures.get(key).add(`${name} ${url}`);
      }
    }
  }
}

// Chrome sometimes cannot remove its own temp profile on Windows. Not worth
// losing the whole report over.
try { await chrome.kill(); } catch (e) { /* ignore */ }

const pad = (s, n) => String(s).padEnd(n);
console.log(pad('URL', 52) + pad('device', 9) + 'perf  a11y  best   seo');
console.log('-'.repeat(52 + 9 + 24));
for (const r of rows) {
  console.log(
    pad(r.url, 52) + pad(r.device, 9) +
    r.scores.map((s) => String(s).padStart(4)).join('  ')
  );
}

const all = rows.flatMap((r) => r.scores);
console.log('\nlowest score across every page and device: ' + Math.min(...all));

if (failures.size) {
  console.log('\naudits below 90:');
  for (const [k, v] of failures) {
    console.log('  ' + k + '\n      ' + [...v].join(', '));
  }
} else {
  console.log('\nno audit scored below 90 anywhere');
}

if (VERBOSE) {
  for (const r of rows) {
    if (r.device !== 'mobile') continue;
    const m = r.lhr.audits;
    console.log(`\n${r.url} mobile metrics:`);
    for (const id of ['first-contentful-paint', 'largest-contentful-paint', 'total-blocking-time', 'cumulative-layout-shift', 'speed-index']) {
      console.log('   ' + pad(id, 28) + m[id].displayValue);
    }
  }
}
