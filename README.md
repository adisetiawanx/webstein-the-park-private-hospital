# The Park Private Hospital — WordPress theme

Custom theme for [tpph.com.au](https://tpph.com.au), built from the Adobe XD design supplied by Edge Creative.

ClickUp: [Edge Creative (The Park Private Hospital): Website Build](https://app.clickup.com/t/6950141/869eknq9d)

---

## Stack

| | |
|---|---|
| WordPress | 7.1 |
| PHP | 8.2 (LocalWP), minimum 7.4 |
| Theme type | Classic (PHP templates), **not** a block theme |
| Fields | [Secure Custom Fields](https://wordpress.org/plugins/secure-custom-fields/) 6.9.5 — free, no ACF Pro licence needed |
| SEO | Yoast SEO (metadata only — schema is written in this theme) |
| CSS | Sass, compiled to `style.css` |
| JS | Vanilla. No jQuery, no framework, no bundler |
| Page builder | None |

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

The PNG exports were rendered on a machine that did not have the brand fonts installed. As a result they show:

- a light humanist **sans** where the design specifies **Noto Serif**, and
- **italic** on the navigation, body copy, eyebrows and buttons, where the design specifies upright.

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

### Artboard naming

Two artboards are misnamed in the XD. `6. Careers – 1` and `6. Careers – 5` are **not** Careers pages — they are the two states of **About → Our Team**.

---

## Design tokens

Read out of the XD fill values, defined in `src/scss/abstracts/_tokens.scss` and mirrored into `theme.json`.

| Token | Value | Use |
|---|---|---|
| `--c-green` | `#386c5f` | Headings, cards, buttons, map roads |
| `--c-brown` | `#483932` | Body copy |
| `--c-olive` | `#4d5b31` | Accents, eyebrows, icon strokes |
| `--c-cream` | `#f7fbea` | Alternating section background |
| `--c-white` | `#ffffff` | |

Every pairing the design uses clears **WCAG AA**:

| Pairing | Ratio | AA normal |
|---|---|---|
| green on white | 6.04 | pass |
| green on cream | 5.73 | pass |
| brown on white | 11.01 | pass |
| brown on cream | 10.46 | pass |
| olive on white | 7.35 | pass |
| white on green | 6.04 | pass |

No colour changes were needed for accessibility.

### Type scale

The XD canvas is 1920 wide with a 45px H1 — small for its canvas. We build to a **1440** container and hold the absolute sizes rather than scaling them down by 25%, which preserves the proportion the designer intended instead of leaving laptops with tiny type in a wide, empty page. Sizes are fluid via `clamp()`.

### Breakpoints

`480 / 768 / 1024 / 1440`. There are **no mobile or tablet artboards** in the design, so all responsive behaviour is a development decision. See `src/scss/abstracts/_breakpoints.scss`.

---

## Fonts

Noto Serif and Noto Sans are **self-hosted** from `assets/fonts/`, not loaded from `fonts.googleapis.com`. Three reasons:

1. It removes two third-party origins from the critical path, which Lighthouse flags as render-blocking.
2. It lets us `preload` both faces, which are needed above the fold on every template.
3. It avoids serving visitor IPs to a US endpoint, which is the Google Fonts GDPR problem.

Google ships both families as **variable** fonts, so one file per family covers the entire weight axis — **72KB for the pair**, latin subset, instead of five static faces. Both are SIL Open Font License 1.1, so bundling is permitted.

Noto Sans has no italic axis in the variable file. The design's two italic runs are synthesised by the browser; a third request for one 17px line is not worth it.

---

## Conventions

- Text domain `tpph`, prefix `tpph_` on every global function.
- Templates never call `get_field()` directly — they go through `tpph_field()`, so deactivating SCF degrades to empty sections instead of fatal errors sitewide.
- Icons are inlined from `assets/theme/icons/` via `tpph_icon()` so they inherit `currentColor` and cost no requests. They are design-system assets, not content, which is why they are not in the Media Library.
- Content images live in the **Media Library**. Logo, service icons and decorative ornaments live in the **theme**.

## Repository

The Adobe XD file (27MB) and the PNG exports (200MB) are gitignored. They are design source, not build input.

Media Library content and SCF field *values* live in the database and are **not** versioned — migrate them with All-in-One WP Migration. SCF field *definitions* are versioned as local JSON in `acf-json/`.

---

## Status

Work is tracked in [GitHub Issues](https://github.com/adisetiawanx/webstein-the-park-private-hospital/issues) across four milestones: Foundation, Pages, Responsive, and SEO / Performance / Handover.

Items waiting on the client are labelled `blocked:client-content`.
