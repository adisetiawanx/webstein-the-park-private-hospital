import io
import os

from PIL import Image, ImageDraw

DESIGN = r"D:\Development\LocalWordpress\the-park-private-hospital\app\public\wp-content\themes\the-park-private-hospital\assets\exported design png"
BUILD = "shots/build"
OUT = "shots/cmp"

PAIRS = [
    ("home", "1. Home.png"),
    ("about", "2. About.png"),
    ("our-team", "6. Careers – 1.png"),
    ("visitors", "3. For Patients & Visitors.png"),
    ("preparing", "Preparing for admission.png"),
    ("postop", "Post Operative Care.png"),
    ("rights", "Patient Rights & Responsibilities.png"),
    ("doctors", "4. For Doctors.png"),
    ("safety", "5. Safety and Quality.png"),
    ("careers", "6. Careers.png"),
    ("contact", "7. Contact Us.png"),
    ("dropdown", "Dropdown Menu Preview.png"),
]

COL = 620          # width of each column in the sheet
GAP = 16
LABEL = 26
SLICE = 2400       # split anything taller than this

os.makedirs(OUT, exist_ok=True)


def fit(path, width):
    im = Image.open(path).convert("RGB")
    h = int(round(im.size[1] * width / float(im.size[0])))
    return im.resize((width, h), Image.LANCZOS)


for name, design_file in PAIRS:
    dpath = os.path.join(DESIGN, design_file)
    bpath = os.path.join(BUILD, name + ".png")

    if not os.path.exists(dpath) or not os.path.exists(bpath):
        print("skip " + name)
        continue

    d = fit(dpath, COL)
    b = fit(bpath, COL)

    # The dropdown artboard is a full page; crop it to the same band the build
    # capture covers so the two are comparable.
    if name == "dropdown":
        d = d.crop((0, 0, COL, min(d.size[1], int(COL * 700 / 1920.0))))

    height = max(d.size[1], b.size[1])
    sheet = Image.new("RGB", (COL * 2 + GAP, height + LABEL), (245, 245, 245))
    draw = ImageDraw.Draw(sheet)
    draw.text((6, 7), "DESIGN (XD export)", fill=(20, 20, 20))
    draw.text((COL + GAP + 6, 7), "BUILD (localhost:10010)", fill=(20, 20, 20))
    sheet.paste(d, (0, LABEL))
    sheet.paste(b, (COL + GAP, LABEL))
    draw.line([(COL + GAP // 2, 0), (COL + GAP // 2, height + LABEL)], fill=(180, 180, 180), width=1)

    total = sheet.size[1]
    if total <= SLICE:
        sheet.save(os.path.join(OUT, name + ".png"))
        print("%-12s 1 sheet  %dx%d" % (name, sheet.size[0], total))
    else:
        parts = (total + SLICE - 1) // SLICE
        for i in range(parts):
            top = i * SLICE
            bottom = min(total, top + SLICE)
            sheet.crop((0, top, sheet.size[0], bottom)).save(
                os.path.join(OUT, "%s-%d.png" % (name, i + 1))
            )
        print("%-12s %d sheets %dx%d" % (name, parts, sheet.size[0], total))
