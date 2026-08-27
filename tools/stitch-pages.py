"""Join the viewport slices from capture-pages.mjs into one image per page.

    node tools/capture-pages.mjs      # writes shots/build/_slice-*.png
    python tools/stitch-pages.py      # writes shots/build/<page>.png
    python tools/compare-to-design.py # design left, build right

The last slice of a page is shot with the document scrolled to its end rather
than to a multiple of the viewport height, so it is pasted flush with the bottom
rather than at slice_index * viewport_height.
"""
import json
import os

from PIL import Image

BUILD = os.path.join("shots", "build")

manifest = json.load(open(os.path.join(BUILD, "manifest.json")))

for name, info in manifest.items():
    height, vh, width = info["height"], info["vh"], info["width"]
    page = Image.new("RGB", (width, height), (255, 255, 255))

    for i, slice_path in enumerate(info["slices"]):
        top = min(i * vh, max(0, height - vh))
        page.paste(Image.open(slice_path).convert("RGB"), (0, top))

    page.save(os.path.join(BUILD, name + ".png"))
    print("%-12s %dx%d" % (name, page.size[0], page.size[1]))
