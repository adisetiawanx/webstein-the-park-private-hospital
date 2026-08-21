# -*- coding: utf-8 -*-
"""Resize and convert the design source images to WebP for the Media Library.

The sources are print-resolution: page headers run to several thousand pixels
wide and the whole folder is 17MB. Serving them as supplied would fail
"properly size images" on every page.

Sizes are chosen from where each image is actually used:
  header   2560  full-bleed page banners
  wide     1600  full-width content bands
  content  1200  half-column content images
  portrait  800  doctor and executive headshots

Quality 82 with method 6. Deliberately conservative: the brief asks not to
compress so hard that anything looks soft, and at 82 WebP is visually
indistinguishable from the source at these sizes.
"""
import io
import json
import os
import re

from PIL import Image, ImageOps

SRC = r"D:\Development\LocalWordpress\the-park-private-hospital\app\public\wp-content\themes\the-park-private-hospital\assets\images"
OUT = r"D:\Development\LocalWordpress\the-park-private-hospital\app\public\wp-content\themes\the-park-private-hospital\.upload-staging"

QUALITY = 82

# Alt text. Written from what each image shows, so the site ships accessible
# rather than waiting on a content pass that may never come. The client can
# refine any of it in the Media Library.
ALT = {
    "1-home-header": "The Park Private Hospital entrance and driveway at dusk",
    "1-home-about-us": "Stained glass windows on the heritage red brick facade of the hospital",
    "1-home-testimonials": "The hospital buildings and gardens at twilight",
    "1-home-map-tpph": "Map showing the hospital location in Mount Lawley",
    "2-about-our-hospital": "Two members of the executive team standing outside the hospital",
    "2-about-our-facilities": "A hospital ward with beds separated by curtains",
    "2-about-our-services": "Reception staff at the hospital front desk",
    "2-about-vision": "Nursing staff at the hospital reception counter",
    "3-for-patients-visitors-header": "The heritage frontage of The Park Private Hospital",
    "3-for-patients-visitors-visitors-visitors": "The hospital verandah and entrance in the evening",
    "3-for-patients-visitors-visitors-day-patient": "The red brick heritage building at the hospital entrance",
    "3-for-patients-visitors-visitors-inpatient": "The hospital front entrance at sunset",
    "3-for-patients-visitors-visitors-map-tpph": "Map showing the hospital location in Mount Lawley",
    "4-for-doctors-header": "The Park Private Hospital seen from the street",
    "5-careers-header": "The Park Private Hospital buildings and gardens",
    "6-about-us-header": "The Park Private Hospital exterior",
    "7-contact-us-header": "The Park Private Hospital exterior at sunset",
    "7-contact-us-map-tpph": "Map showing the hospital location in Mount Lawley",
}


def slugify(text):
    s = text.lower()
    s = re.sub(r"\.[a-z0-9]+$", "", s)
    s = re.sub(r"[^a-z0-9]+", "-", s)
    return s.strip("-")


def classify(rel):
    name = os.path.basename(rel).lower()
    parts = rel.lower().replace("\\", "/").split("/")

    if "header" in name:
        return "header", 2560
    if "doctors" in parts or "exec team" in parts:
        return "portrait", 800
    if name.startswith("image-") or "map" in name:
        return "content", 1200
    return "wide", 1600


def alt_for(slug, rel):
    if slug in ALT:
        return ALT[slug]
    # Fall back to a readable description built from the path, flagged for the
    # content pass rather than left empty.
    words = re.sub(r"[-/\\]+", " ", os.path.splitext(rel)[0])
    words = re.sub(r"\b\d+\.?\s*", "", words).strip()
    return "The Park Private Hospital, " + words.lower()


manifest = []

if os.path.isdir(OUT):
    for f in os.listdir(OUT):
        os.remove(os.path.join(OUT, f))
else:
    os.makedirs(OUT)

for root, _dirs, files in os.walk(SRC):
    for f in sorted(files):
        if not f.lower().endswith((".jpg", ".jpeg", ".png")):
            continue

        full = os.path.join(root, f)
        rel = os.path.relpath(full, SRC)
        kind, max_w = classify(rel)
        slug = slugify(rel.replace("\\", "-").replace("/", "-"))

        im = Image.open(full)
        im = ImageOps.exif_transpose(im)
        src_size = im.size

        if im.mode in ("RGBA", "LA", "P"):
            # Portraits are cut out on a flat backdrop; flatten to white so the
            # WebP has no alpha channel to carry.
            im = im.convert("RGBA")
            bg = Image.new("RGB", im.size, (255, 255, 255))
            bg.paste(im, (0, 0), im.split()[3])
            im = bg
        else:
            im = im.convert("RGB")

        if im.size[0] > max_w:
            ratio = max_w / float(im.size[0])
            im = im.resize((max_w, int(round(im.size[1] * ratio))), Image.LANCZOS)

        dest = os.path.join(OUT, slug + ".webp")
        im.save(dest, format="WEBP", quality=QUALITY, method=6)

        manifest.append({
            "slug": slug,
            "file": dest,
            "kind": kind,
            "alt": alt_for(slug, rel),
            "title": re.sub(r"[-]+", " ", slug).title(),
            "source": rel,
            "src_px": "%dx%d" % src_size,
            "out_px": "%dx%d" % im.size,
            "bytes": os.path.getsize(dest),
            "src_bytes": os.path.getsize(full),
        })

io.open(os.path.join(OUT, "manifest.json"), "w", encoding="utf-8").write(
    json.dumps(manifest, indent=1)
)

src_total = sum(m["src_bytes"] for m in manifest)
out_total = sum(m["bytes"] for m in manifest)

for m in manifest:
    print("%-52s %-8s %-11s -> %-11s %6.0fKB -> %5.0fKB" % (
        m["slug"][:52], m["kind"], m["src_px"], m["out_px"],
        m["src_bytes"] / 1024.0, m["bytes"] / 1024.0))

print("\n%d images   %.1fMB -> %.1fMB  (%.0f%% smaller)" % (
    len(manifest), src_total / 1048576.0, out_total / 1048576.0,
    100 - (out_total * 100.0 / src_total)))
