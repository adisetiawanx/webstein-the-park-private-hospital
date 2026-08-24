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
| `--c-green` | `#386c5f` | Headings, cards, buttons, map roads |
| `--c-brown` | `#483932` | Body copy |
| `--c-olive` | `#4d5b31` | Accents, eyebrows, icon strokes |
| `--c-cream` | `#f7fbea` | Alternating section background |
| `--c-mint` | `#eafbed` | Footer band |
| `--c-ornament` | `#e3e7d9` | Tree watermark over the cream bands |

Every pairing the design uses clears **WCAG AA**, so no colour had to be changed for accessibility:

| Pairing | Ratio | AA normal |
|---|---|---|
| green on white | 6.04 | pass |
| green on cream | 5.73 | pass |
| brown on white | 11.01 | pass |
| brown on cream | 10.46 | pass |
| olive on white | 7.35 | pass |
| white on green | 6.04 | pass |

### Type scale

The XD canvas is 1920 wide with a 45px H1 — small for its canvas. We build to a **1440** container and hold the absolute sizes rather than scaling them down by 25%, which preserves the proportion the designer intended instead of leaving laptops with tiny type in a wide, empty page. Sizes are fluid via `clamp()`.

### Breakpoints

`480 / 768 / 1024 / 1440`. There are **no mobile or tablet artboards** in the design, so all responsive behaviour is a development decision. The notable one: the overlapping Vision/Mission/Values cards unwind to a plain stack below 768, because the offset is decorative and only produces collisions on small screens.

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

The key goes in `wp-config.php` and never in this repository:

```php
define( 'TPPH_GOOGLE_MAPS_KEY', '...' );
```

Until it is defined the site renders a styled static facade with a working Get Directions link. Restrict the key to the site's domains before it goes live.

---

## Repository

The Adobe XD file (27MB), the PNG exports (200MB) and the raw source images are gitignored. They are design source, not build input.

Media Library files and SCF field *values* live in the database and the uploads folder, neither of which is versioned — migrate them with All-in-One WP Migration. SCF field *definitions* are versioned as local JSON in `acf-json/`.

`tools/` holds the scripts that rebuild the content from the design source, plus both audit harnesses. See [tools/README.md](tools/README.md).

---

## Deployment checklist

1. Migrate database and uploads with All-in-One WP Migration.
2. `define( 'TPPH_GOOGLE_MAPS_KEY', ... )` in `wp-config.php`, restricted to the live domain.
3. Set far-future `Cache-Control` on `/wp-content/uploads/`, `/wp-content/themes/` and the font directory. Lighthouse flags this on the LocalWP build; it is a hosting-layer setting, not a theme one.
4. Confirm permalinks are `/%postname%/`.
5. Re-run `node tools/lighthouse.mjs` against the live URL.
6. Check the four stub pages against [HANDOVER.md](HANDOVER.md) — some may have real content by then.
