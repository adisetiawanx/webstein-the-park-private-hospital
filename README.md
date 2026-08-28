# The Park Private Hospital — WordPress theme

Custom theme for [tpph.com.au](https://tpph.com.au), built from the Adobe XD design supplied by Edge Creative.

ClickUp: [Edge Creative (The Park Private Hospital): Website Build](https://app.clickup.com/t/6950141/869eknq9d)
Lighthouse results: [LIGHTHOUSE.md](LIGHTHOUSE.md)
Outstanding client content: [HANDOVER.md](HANDOVER.md)

---

## Stack

| | |
|---|---|
| WordPress | 7.1 |
| PHP | 8.2 (LocalWP), minimum 7.4 |
| Theme type | Classic (PHP templates), **not** a block theme |
| Fields | [Secure Custom Fields](https://wordpress.org/plugins/secure-custom-fields/) 6.9.5 — free, no ACF Pro licence needed |
| SEO | Yoast SEO — titles, meta and Open Graph only. Schema is written in this theme |
| CSS | Sass, compiled to `style.css` |
| JS | Vanilla. No jQuery, no framework, no bundler |
| Page builder | None |
| Forms | None. The design does not have one anywhere |

## Build

```bash
npm install
npm run watch    # develop
npm run build    # compressed, before committing
```

`style.css` is the **compiled output** and is committed, so the theme works on a server that has never run node. The WordPress theme header lives at the top of `src/scss/main.scss` as a preserved (`/*!`) comment — it has to survive compilation or WordPress stops recognising the theme. Never edit `style.css` by hand.

---

## The design source, and why the PNGs lie

The theme is built from `assets/The Park Private Hospital Website.xd`, not from `assets/exported design png/`.

The PNG exports were rendered on a machine that did not have the brand fonts installed. As a result they show a light humanist **sans** where the design specifies **Noto Serif**, and **italic** on the navigation, body copy, eyebrows and buttons, where the design specifies upright.

Parsing the text runs out of the XD settles it. Across all thirteen artboards:

| Font | Size | Runs | Used for |
|---|---|---|---|
| Noto Sans Regular | 16 | 253 | Body copy |
| Noto Serif Regular | 45 | 42 | Every H1 and H2 |
| Noto Serif Bold | 25 | 35 | Card titles / H3 |
| Noto Sans Bold | 17 | 29 | Sub-labels, e.g. DAY PATIENT VISITORS |
| Noto Sans Bold | 16 | 27 | Inline emphasis |
| Noto Sans Regular | 15 | 7 | Footer and fine print |
| Noto Sans **Italic** | 17 | **2** | Both are `- Patient 2025` on the homepage testimonial |

Italic appears **twice in the entire design**. Use the XD for type, colour and spacing. Use the PNGs for layout only.

**Two artboards are misnamed.** `6. Careers – 1` and `6. Careers – 5` are **not** Careers pages — they are the two states of **About → Our Team**.

---

## Design tokens

Read out of the XD fill values, defined in `src/scss/abstracts/_tokens.scss` and mirrored into `theme.json`.

| Token | Value | Use |
|---|---|---|
| `--c-green` | `#386c5f` | Headings **and body copy**, cards, buttons, map roads |
| `--c-brown` | `#483932` | Hairline rules only. The XD sets no text in this |
| `--c-olive` | `#4d5b31` | Footer copy, icon strokes |
| `--c-lime` | `#8aba31` | The `ABOUT US` eyebrow on Our Hospital. Used once |
| `--c-lime-pale` | `#b4e6a4` | Hairlines inside the navigation dropdowns |
| `--c-cream` | `#f7fbea` | Alternating section background, **and text on green** |
| `--c-mint` | `#eafbed` | Footer band |
| `--c-ornament` | `#e3e7d9` | Tree watermark — olive `#3b451d` at 10%, flattened onto the band it sits on |

Body copy is **green, not brown**. Counting the text runs settles it: `#386c5f`
appears 135 times as a text fill across the thirteen artboards and `#483932`
never does. The same count puts cream `#f7fbea` on the green cards and panels
rather than white; white is reserved for the homepage testimonial band and for
the italic qualifications on the Our Team cards.

Every pairing clears **WCAG AA** with one exception, called out below:

| Pairing | Ratio | AA normal |
|---|---|---|
| green on white | 6.04 | pass |
| green on cream | 5.73 | pass |
| olive on mint | 6.62 | pass |
| cream on green | 5.61 | pass |
| white on green | 6.04 | pass |
| **lime on cream** | **2.0** | **fail** |

The lime eyebrow is the design's own colour for the two words `ABOUT US` on Our
Hospital, and it is the only pairing in the design that does not clear AA.
Setting it in olive `#4d5b31` instead keeps the page at 100 and is a one-token
change; it is set to the design's lime, so this is a live decision, not an
oversight.

### Type scale

The XD canvas is 1920 wide with a 45px H1 — small for its canvas. Type sizes are held at their absolute values rather than scaled down, which preserves the proportion the designer intended. Headings are fluid via `clamp()`; body copy is a flat 16px, which is what the design sets at every size it is used and what gives every inline link a 24px target without padding it out of the line rhythm.

Two measurements out of the XD's own line boxes drive the vertical rhythm, and getting them wrong was worth hundreds of pixels a page:

- **45px type sets on 61px** — `--lh-heading: 1.36`, not 1.22.
- **16px type sets on 24px** — `--lh-body: 1.5`, not 1.65. Every block of copy in the design sits on that one unbroken 24px grid: paragraphs run on from one another with nothing between them, and where the design wants air it leaves a single empty row. `tpph_lines_to_html()` keeps blank lines for exactly that reason and marks the block after one `.is-spaced`.

The container is **1610px of content**, measured rather than estimated: the Home promo cards run `x=154..1765` on the 1920 artboard, the header logo starts at 154 and the Make a Payment button ends at 1766. The gutter is added *outside* that width rather than eaten out of it — subtracting it instead inset every page by 63px a side.

### Image crops

Every image fill in the XD carries its own crop: `scaleBehavior: cover`, plus an
`offsetX`/`offsetY` expressed as a fraction of the *scaled* image. That converts
to CSS as

```
s        = max(frameW / imgW, frameH / imgH) * zoom
overflow = imgH * s - frameH
y%       = (overflow / 2 - offsetY * imgH * s) / overflow
```

which was checked against the export by matching the Home About photograph
back to its source: the formula says 58.1%, the pixels say 57.9%.

Most of them come out dead centre, which is the CSS default. The ones that do
not are the wide strips, where only a fifth of the photograph survives the crop
and the difference between 50% and 77% is a roofline or a garden bed:

| Band | Frame | `object-position` |
|---|---|---|
| Home, About photograph | 1612x322 | `center 58%` |
| Our Hospital, ward strip | 1920x200 | `center 60%` |
| Visitors, closing | 1945x357 | `center 72%` |
| Patient Rights, closing | 1933x533 | `center 77%` |
| Careers, closing | 1933x334 | `center 63%` |
| Preparing, going home | 776x339 | `center 79%` |

Full-bleed bands read theirs from `--band-focus`, which the **Focal point**
field on the section sets, so the client can re-frame a band after swapping the
photograph without touching CSS.

The **page banners are the exception**: the XD crops those from a wide
pre-cropped file (`1-894x329.jpg`, 2.72:1) that is not in the handover, so its
64% does not carry over to the supplied 3:2 photographs. Rendering the
candidates against the artboard settles it — a centre crop of the supplied
photograph gives the facade the artboard shows; weighting it up puts the roof
in the band, and down puts the signage in it.

### Breakpoints

`480 / 768 / 1024 / 1440`. There are **no mobile or tablet artboards** in the design, so all responsive behaviour is a development decision. The notable one: the overlapping Vision/Mission/Values cards unwind to a plain stack below 768, because the offset is decorative and only produces collisions on small screens.

### The tree watermark

Supplied as `assets/images/Mask Group 32.png`: olive `rgb(59, 69, 29)` at 10%
alpha. Alpha on a leaf silhouette does not compress — the transparent file came
out at 66KB even at quality 55 — and the design only ever sets it on cream or on
white, so it is flattened onto each. Two files, 23KB apiece, in
`assets/theme/img/`.

Regenerate both from the source with the snippet in `tools/README.md` if the
band colours ever change.

### Fonts

Noto Serif and Noto Sans are **self-hosted** from `assets/fonts/`. Three reasons: it removes two third-party origins from the critical path, it lets us `preload` both faces, and it avoids serving visitor IPs to a US endpoint.

Google ships both as **variable** fonts, so one file per family covers the whole weight axis — 72KB for the pair, latin subset. Both are SIL OFL 1.1, so bundling is permitted.

---

## Templates

| Template | Pages using it |
|---|---|
| `front-page.php` | Home |
| `page-templates/about.php` | About — Our Hospital |
| `page-templates/our-team.php` | About — Our Team |
| `page-templates/sections.php` | Visitors, Preparing for your Admission, Post Operative Care, Patient Rights & Responsibilities, Credentialing, Safety and Quality |
| `page-templates/careers.php` | Careers |
| `page-templates/contact.php` | Contact Us |
| `page.php` | The four stub pages, and any future plain-text page |
| `404.php` | Not found |

`page-templates/sections.php` is the workhorse. Six artboards are the same three shapes in a different order — heading and text with images alongside, a row of columns, or a full-width photograph — so they share one template instead of getting six near-identical ones.

### URL structure

The section landing page **is** the first content page: `/for-patients-visitors/` is the Visitors page and `/for-doctors/` is Credentialing. Each artboard puts the section name in the banner and the page name in the H2, which is exactly that. Inventing empty parent pages would mean shipping pages nobody designed.

---

## Content model

Field groups are stored as local JSON in `acf-json/`, so the content model is part of the repository: diffable in review, surviving a database reset, and a fresh clone comes up with the same edit screens.

Field groups locate by **page template**, never by page ID — a page ID from this database means nothing on staging.

| Group | Attached to |
|---|---|
| Site Settings | Options page (header CTA, default banner, map position) |
| Page Header | Every page |
| Home Page | Front page |
| About — Our Hospital | `about.php` template |
| About — Our Team | `our-team.php` template |
| Page Sections | `sections.php` template |
| Careers | `careers.php` template |
| Contact Us | `contact.php` template |
| Doctor Details | `doctor` post type |
| Vacancy Details | `vacancy` post type |

`doctor` and `vacancy` post types and the `specialty` taxonomy are registered **in the theme**, not a plugin, so deactivating something cannot empty the Our Team and Careers pages.

Neither post type is publicly queryable. The design has no single-doctor or single-vacancy page — biographies open inline and Apply Now is a mailto — so individual URLs would only publish thin, near-duplicate pages for Google to index. One line in `inc/post-types.php` reverses that if detail pages are ever wanted.

### Text formatting in fields

Several fields take a plain textarea that carries structure through two prefixes, handled by `tpph_lines_to_html()`:

```
Ordinary line          -> <p>
- line starting a dash -> <li>, consecutive dashes group into one <ul>
## line                -> <h3 class="sub-label">
```

This is why the facility panels and the Visitors columns can mix prose, sub-headings and bullets in one field, without a rich-text editor that would let the styling be broken after handover.

---

## Conventions

- Text domain `tpph`, prefix `tpph_` on every global function.
- Templates never call `get_field()` directly — they go through `tpph_field()`, so deactivating SCF degrades to empty sections instead of fatal errors sitewide.
- Icons are inlined from `assets/theme/icons/` via `tpph_icon()` so they inherit `currentColor` and cost no requests. They are design-system assets, not content.
- Content images live in the **Media Library**. Logo, service icons and the tree ornament live in the **theme**.
- Hero images go through `tpph_image( $id, $size, [ 'eager' => true ] )`, which sets `fetchpriority="high"`. Everything else is lazy.

### The Our Team biography panel

Hovering a person card slides the biography up over the portrait from the bottom
edge, per Edge Creative's note. Opening is pure CSS — `:hover` for pointers,
`:focus-within` for keyboard — so it works with JavaScript off.

Two things are worth knowing before touching it:

- The panel has **no `hidden` attribute**. An element that is `display: none`
  cannot be transitioned from, so visibility is driven by CSS instead, which
  also keeps the panel out of the tab order while it is closed.
- Closing on touch or by keyboard returns focus to the toggle, which lives
  *inside* the card — so `:focus-within` matched again and the panel sprang
  straight back open, making the close button appear to do nothing. The
  `is-dismissed` class holds it shut, and is cleared as soon as focus or the
  pointer genuinely leaves.

### Two reset rules worth knowing about

Both of these cost real debugging time and are commented in place:

- `[hidden]` is declared `display: none !important`. A class-level `display` beats the user-agent rule, which silently breaks every JS toggle relying on the attribute.
- The list reset is wrapped in `:where()`. As `ul[class]` it scored (0,1,1) and quietly beat every component class trying to set a margin on a list.

---

## Structured data

The JSON-LD graph is written in `inc/schema.php` so it survives Yoast being deactivated. The corollary is that Yoast's own graph is switched off — two Organization entities that disagree with each other is worse than having none. The filter that disables it is inert when Yoast is absent, so nothing breaks if the plugin is removed.

`Hospital`/`MedicalOrganization` sitewide, `WebSite`, `WebPage`, `BreadcrumbList`, `Physician` per doctor on Our Team, and `JobPosting` per vacancy on Careers. The last one is worth having: Google surfaces those in its Jobs experience.

---

## Google Maps

The map in the design is custom styled — cream base, green roads — which only the Maps JavaScript API can produce. That is 200KB+ of third-party script for a section below the fold on both pages that use it, so it loads behind a facade and hydrates on IntersectionObserver.

The key never goes in this repository. It can live in either of two places, and
`wp-config.php` wins:

```php
define( 'TPPH_GOOGLE_MAPS_KEY', '...' );
```

or **Site Settings → Map → Google Maps API key**, which exists so a staging or
development site can be set up without shell access. `tpph_maps_key()` in
`inc/fields.php` is the single place that resolves the two, and everything else
— the enqueue, the band's decision to hydrate, and SCF's own map field — reads
it from there.

Until one of them is set the site renders a styled static facade in the brand colours. The artboard draws the map bare — no button and no address panel over it — so there is nothing else in that band; the address stays in the markup for screen readers and for Google. Restrict the key to the site's domains before it goes live.

---

## Repository

The Adobe XD file (27MB), the PNG exports (200MB) and the raw source images are gitignored. They are design source, not build input.

Media Library files and SCF field *values* live in the database and the uploads folder, neither of which is versioned — migrate them with All-in-One WP Migration. SCF field *definitions* are versioned as local JSON in `acf-json/`.

`tools/` holds the scripts that rebuild the content from the design source, plus both audit harnesses. See [tools/README.md](tools/README.md).

---

## Deployment checklist

1. **Install and activate Secure Custom Fields.** The theme has no content without it — every field falls back to empty and the pages render as bare banners. It warns on the plugins screen if it is missing.
2. Migrate **database and uploads** with All-in-One WP Migration — but not the theme. It copies `wp-content` verbatim, so `src/`, `tools/` and `package.json` ride along and end up readable over HTTP. Ship the theme with the rsync line in [tools/README.md](tools/README.md), which honours `.distignore`.
3. Set the Maps key — `define( 'TPPH_GOOGLE_MAPS_KEY', ... )` in `wp-config.php`, or Site Settings → Map — and **restrict it to the live domain**. A Maps key is readable in the page source of every site that uses one; referrer restriction is the only thing that stops it being spent elsewhere.
4. Set far-future `Cache-Control` on `/wp-content/uploads/`, `/wp-content/themes/` and the font directory. Lighthouse flags this on the LocalWP build; it is a hosting-layer setting, not a theme one.
5. Confirm permalinks are `/%postname%/`.
6. Re-run `node tools/lighthouse.mjs` against the live URL.
6. Check the four stub pages against [HANDOVER.md](HANDOVER.md) — some may have real content by then.
