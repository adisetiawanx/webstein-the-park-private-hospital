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

Results as of 24 August 2026 are recorded in `../LIGHTHOUSE.md`.

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
