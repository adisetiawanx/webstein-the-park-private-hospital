# Lighthouse results

Recorded 24 August 2026 against the LocalWP build at `http://localhost:10010`,
Lighthouse 13.4.1, headless Chrome. Mobile runs use the standard Moto G Power
emulation with 4× CPU throttling and Slow 4G; desktop uses the standard desktop
preset.

Target agreed with the client: **90+ on all four categories, mobile and
desktop.**

## Every page

| Page | Device | Perf | A11y | Best practices | SEO |
|---|---|---:|---:|---:|---:|
| Home | mobile | 98 | 100 | 100 | 100 |
| Home | desktop | 100 | 100 | 100 | 100 |
| About — Our Hospital | mobile | 98 | 100 | 100 | 100 |
| About — Our Hospital | desktop | 100 | 100 | 100 | 100 |
| About — Our Team | mobile | 98 | 100 | 100 | 100 |
| About — Our Team | desktop | 100 | 100 | 100 | 100 |
| Visitors | mobile | 97 | 100 | 100 | 100 |
| Visitors | desktop | 100 | 100 | 100 | 100 |
| Preparing for your Admission | mobile | 98 | 100 | 100 | 100 |
| Preparing for your Admission | desktop | 100 | 100 | 100 | 100 |
| Post Operative Care | mobile | 98 | 100 | 100 | 100 |
| Post Operative Care | desktop | 100 | 100 | 100 | 100 |
| Patient Rights & Responsibilities | mobile | 98 | 100 | 100 | 100 |
| Patient Rights & Responsibilities | desktop | 100 | 100 | 100 | 100 |
| For Doctors — Credentialing | mobile | 99 | 100 | 100 | 100 |
| For Doctors — Credentialing | desktop | 100 | 100 | 100 | 100 |
| Safety and Quality | mobile | 99 | 100 | 100 | 100 |
| Safety and Quality | desktop | 100 | 100 | 100 | 100 |
| Careers | mobile | 98 | 100 | 100 | 100 |
| Careers | desktop | 100 | 100 | 100 | 100 |
| Contact Us | mobile | 98 | 100 | 100 | 100 |
| Contact Us | desktop | 100 | 100 | 100 | 100 |
| Make a Payment (stub) | mobile | 99 | 100 | 100 | **92** |
| Make a Payment (stub) | desktop | 100 | 100 | 100 | **92** |

**Lowest score anywhere: 92.** Every page clears the target on every category.

Reproduce with:

```bash
node tools/lighthouse.mjs
node tools/lighthouse.mjs /about/ /careers/    # specific pages
```

## What got us there

- **Fonts self-hosted and preloaded.** Two variable woff2 files, 72KB for both
  families, no third-party origin on the critical path.
- **Hero images eager with `fetchpriority="high"`.** The hero is the LCP element
  on every template; lazy-loading it costs several points on its own.
- **Google Maps behind a facade.** The styled map needs the Maps JavaScript API,
  which is 200KB+ of third-party script for a section below the fold on both
  pages that use it. It hydrates on IntersectionObserver instead.
- **Images resized to their display width and converted to WebP**, 16.4MB down
  to 4.2MB across 44 files, with `add_image_size()` values that match the layout
  breakpoints so `srcset` offers something usable.
- **Unused WordPress output removed**: emoji detection, oEmbed discovery, RSD
  and WLW head links, and the core block stylesheet on templates that render no
  blocks.
- **Own scripts deferred**, and the team, testimonial and map scripts only
  enqueued on pages that actually use them.

## Known, and deliberately left

**`Make a Payment` scores SEO 92** because it has no meta description. It is one
of the four pages the design links to but never designs, so there is no content
to describe. It will reach 100 as soon as the client supplies copy. The same
applies to Fees Charges & Insurance, Privacy Policy and Disclaimer.

**"Use efficient cache lifetimes"** is flagged on every page. This is the
LocalWP nginx default, not something the theme controls. Set far-future
`Cache-Control` on `/wp-content/uploads/`, `/wp-content/themes/` and the font
directory at the hosting layer — it does not affect the score, but it is real
for returning visitors.

**"Render-blocking requests", "Network dependency tree" and "Improve image
delivery"** appear as opportunities rather than failures, worth a fraction of a
second at 98/100. Inlining critical CSS would claw back part of it at the cost
of a build step and a cache-invalidation problem on every stylesheet change.
Not worth it at this score; revisit only if the number drops.

**Inline `mailto:` and `tel:` links inside running prose** measure about 20px
tall, under the 24px touch-target guidance. Padding them would break the line
rhythm of the paragraph they sit in for no practical gain, so they are left as
they are. Every standalone navigation link clears the threshold.

## Accessibility

Accessibility scores 100 on every page and device. Independently swept for:

- horizontal overflow at 390, 834 and 1440 — none on any page
- text under 12px — none
- images without an `alt` attribute — none
- heading order — no skipped levels
- exactly one `h1` per page

The brand palette clears WCAG AA on every pairing the design uses, so no colour
had to be changed to get there. See the contrast table in `README.md`.
