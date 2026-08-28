# tools/

One-off scripts that rebuild the site's content from the design source.

Media Library files and field *values* live in the database and the uploads
folder, neither of which is in this repository. These scripts exist so that a
fresh install can be brought back to a known state without hand-entering
fourteen pages of copy — and so the image processing decisions are recorded
rather than lost in someone's terminal history.

They are **not** loaded by the theme. Nothing in `functions.php` references
them.

## Rebuilding the content from scratch

```bash
# 1. Resize and convert the design images. Writes .upload-staging/
python tools/prepare-images.py

# 2. Import them into the Media Library with alt text
wp eval-file tools/import-images.php

# 3. Create the page tree and set the front page
wp eval-file tools/create-pages.php

# 4. Build the menus and assign them to their theme locations
wp eval-file tools/create-menus.php

# 5. Populate each page
wp eval-file tools/seed-home.php
wp eval-file tools/seed-about.php
wp eval-file tools/seed-team.php             # 4 executives, 8 doctors
wp eval-file tools/seed-sections.php         # the six section-template pages
wp eval-file tools/seed-careers-contact.php  # + 3 placeholder vacancies
wp eval-file tools/seed-meta.php             # Yoast meta descriptions
```

Every one is idempotent. Re-running skips or updates rather than duplicating.

## Auditing

Both harnesses run against whatever is at `http://localhost:10010`. They need
`npm install lighthouse puppeteer-core` in whatever directory you run them from.

```bash
node tools/lighthouse.mjs                 # four categories, mobile + desktop
node tools/lighthouse.mjs /about/ /careers/
node tools/lighthouse.mjs --verbose       # + LCP, TBT, CLS per page

node tools/audit-layout.mjs               # every page at 390 / 834 / 1440:
                                          # overflow, tiny text, touch targets,
                                          # missing alt, heading order
```

Results as of 26 August 2026 are recorded in `../LIGHTHOUSE.md`.

The layout audit deliberately ignores anything inside `.map__canvas`. Google's
map widget renders 10px attribution text, sub-24px zoom buttons and tiles that
overflow their container by design; flagging all of it every run buries our own
findings.

## Comparing against the design

```bash
node tools/capture-pages.mjs        # every page at the artboards' own 1920 canvas
python tools/stitch-pages.py        # join the slices into one image per page
python tools/compare-to-design.py   # design left, build right, one sheet per page
```

`capture-pages.mjs` imports `puppeteer-core`, which resolves against the
script's own directory — run `npm install puppeteer-core` here, or copy the
script to wherever the install is.

Captures are stitched from viewport-sized slices rather than taken with
`fullPage`. Chrome's full-page capture does not reliably resolve lazy-loaded
images in headless — it silently dropped the About photograph on one run and the
testimonial on another, which reads as a missing image when you are checking a
design match. Scrolling and shooting one viewport at a time only ever captures
what is genuinely painted.

This is how the 1610 container and the 6:1 band ratio were measured; both had
been estimated wrongly by eye.

The capture waits for the Google map to finish hydrating before shooting the
slice it sits in. Without that the map is caught mid-load and reads as broken
when it is fine on the page.

## Regenerating the tree watermark

The supplied `assets/images/Mask Group 32.png` is olive at 10% alpha. Alpha on a
leaf silhouette will not compress, and the design only sets it on cream or on
white, so it is flattened onto each:

```python
from PIL import Image
m = Image.open('assets/images/Mask Group 32.png').convert('RGBA')
m = m.crop(m.getbbox())
m = m.resize((820, round(m.size[1] * 820 / m.size[0])), Image.LANCZOS)
for name, bg in (('tree-ornament.webp', (247, 251, 234)),
                 ('tree-ornament-white.webp', (255, 255, 255))):
    base = Image.new('RGBA', m.size, bg + (255,))
    Image.alpha_composite(base, m).convert('RGB').save(
        'assets/theme/img/' + name, format='WEBP', quality=86, method=6)
```

## Deploying

**Prerequisite: Secure Custom Fields must be installed and active.** Every
piece of copy on this site is an SCF field value, and `tpph_field()` returns an
empty fallback when the plugin is absent — so the site renders without erroring,
as bare banners with no content, which reads as a broken deploy rather than a
missing plugin. The theme prints a notice on the plugins screen to say so.

The Maps key is the other environment-level setting: `TPPH_GOOGLE_MAPS_KEY` in
`wp-config.php`, or Site Settings → Map. A database import carries neither.

### All-in-One WP Migration is not a release tool

It copies `wp-content` verbatim, which is the right behaviour for moving a site
and the wrong one for shipping a theme: everything in `.distignore` rides along,
so `src/`, `package.json` and `tools/` land on the server and are reachable over
HTTP. That has already happened once on the dev site.

So use each for what it is good at:

- **Database and uploads** — All-in-One WP Migration.
- **The theme** — the rsync line below, or a zip built the same way, so the
  development files stay behind.

Every script in this directory now begins with `defined( 'ABSPATH' ) || exit;`
so that a copy which does end up on a server is inert rather than a public
endpoint that rewrites page content. That is a backstop, not a reason to leave
them there.

`.distignore` in the theme root lists everything that is development-only. None
of it is loaded at runtime — `functions.php` references neither `tools/` nor
`src/`, and the compiled `style.css` is committed — so a live server needs only
what is left.

```bash
rsync -avz --delete --exclude-from=.distignore   ./ user@host:/path/to/wp-content/themes/the-park-private-hospital/
```

`--delete` is what removes files already on the server that should not be there.
Run it once with `--dry-run` first and read the list: on a first deploy against
a directory that has had development files copied into it, that list is the
answer to "what should not be here".

Two things this does **not** move, because neither lives in the theme:

- **Uploads.** `wp-content/uploads/` is its own tree. The Media Library rows in
  the database point at it, so the two travel together or not at all.
- **Content.** Every page's copy is ACF field values in the database. The
  scripts here rebuild it from the design source on a fresh install; they are
  not a substitute for a database export, and running them against a live
  database would overwrite whatever is in it.

## Notes

**`prepare-images.py`** needs Pillow (`pip install Pillow`). It reads
`assets/images/`, which is gitignored — the design source is not in the
repository, so this step only works on a machine that has the Dropbox handover
folder in place.

Sizes are picked from where each image is actually used: 2560 for full-bleed
banners, 1600 for wide content bands, 1200 for half-column images, 800 for
headshots. Quality 82, which is conservative enough that the output is visually
indistinguishable from the source at these sizes.

> The supplied source images top out at **1600px wide**. That is fine at 1x but
> slightly soft on a 2x desktop display for the full-bleed page banners. Worth
> asking Edge Creative for larger originals if the client notices.

**`import-images.php`** stores a slug to attachment-ID map in the
`tpph_media_map` option, which is how `seed-home.php` finds the right image
without hard-coding IDs.

**`seed-home.php`** transcribes the copy from artboard `1. Home` verbatim,
including the promo card that ends mid-sentence at "designed to complement".
That truncation is in the design and is flagged for the client rather than
invented around.

## wp-cli on LocalWP

The bundled CLI does not load the MySQL extension or know which port Local
assigned to MariaDB, so plain `wp` will not connect. Either use Local's own
"Open site shell", or run it with the extensions loaded and the port supplied:

```bash
TPPH_DB_HOST=127.0.0.1:<mysql-port> \
"<local>/lightning-services/php-8.2.29+0/bin/win64/php.exe" -n \
  -d extension_dir="<local>/lightning-services/php-8.2.29+0/bin/win64/ext" \
  -d extension=php_mysqli.dll -d extension=php_mbstring.dll \
  -d extension=php_gd.dll -d extension=php_exif.dll \
  wp-cli.phar eval-file tools/import-images.php
```

`wp-config.php` reads `DB_HOST` from a `TPPH_DB_HOST` environment variable and
falls back to `localhost`, so nginx and PHP-FPM are unaffected.
