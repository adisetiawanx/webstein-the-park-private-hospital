# Outstanding items — The Park Private Hospital

Everything the design specifies is built. This is what the **client** still owes
before the site is ready to go live, plus the one item we need internally.

Nothing here is a blocker for review; the site is complete and navigable as it
stands. Each gap renders as either the design's own placeholder or a neutral
one, never as a broken page.

---

## 0. Corrected since the first review

Three lines were transcribed from the PNG exports rather than the XD text
layers, and the exports were clipping or altering them:

| Where | Was | Now |
|---|---|---|
| **Home → Become an Accredited Practitioner** | ended at "designed to complement" | "…designed to complement your clinical practice." |
| **About → Our Hospital** intro | "centred around individual needs" | "centred around individual well being" |
| **Preparing for your Admission** | "arrive at the scheduled admission time" | "arrive by the scheduled admission time" |
| **For Patients & Visitors** | "nearby cafes, all within" | "nearby cafés – all within" |

The first was spotted by Mika: the artboard's text frame is a fixed height, so
the last line of that card is clipped in the export while the XD text layer
holds the full sentence. The other three came out of re-checking every block of
copy on the site against the XD's own text, which is the only reliable source
for wording.

## 1. Copy that is lorem ipsum in the design

The artboards themselves carry placeholder text in these five places. We have
reproduced it exactly rather than writing our own, because inventing plausible
copy about a hospital's accreditation or credentialing would be worse than
leaving it obviously unfinished.

| Where | What is needed |
|---|---|
| **For Doctors → Credentialing** | The whole page. Two body sections plus the "Evaluation of credentialing applications" sub-section |
| **Safety and Quality → Accreditation and Licensing** | The whole page. Two body sections plus one sub-section |
| **Careers → Join our team** | Two intro paragraphs |
| **Careers → Current Vacancies** | Three real vacancies. Currently "Job One / Job Two / Job Three" with "Short job description" and four dummy lines, exactly as drawn |
| **About → Our Team → Executive Team** | Four biographies — A/Prof Dieter Gebauer, Brendon Garton, Helen Robinson, Jan Morskate. Shown when a card is hovered |

## 2. Missing photographs

The artboard shows a magenta placeholder where these should be. The site shows a
neutral cream placeholder instead.

- **A/Prof Dieter Gebauer** — Medical Director
- **Jan Morskate** — Director of Nursing
- **The Home page hero.** The artboard uses a daytime photograph of the
  building, filed in the XD as `TPPH New.jpg`, which is not in the handover
  folder. The site uses the supplied dusk photograph instead. Ask Edge Creative
  for the original if the daytime shot is wanted.
- **The inner page banners.** Every inner artboard shows the same daytime
  photograph, filed in the XD as `1-894x329.jpg`, which is also not in the
  handover folder. Each page instead uses the header photograph supplied for its
  own section, which is what the folders imply was intended.
- **A banner for Safety and Quality.** The handover has a `Header.jpg` for every
  top-level section except this one. It currently borrows the For Patients &
  Visitors photograph.

Portrait, roughly 3:4, on a plain background to match the other eight.

## 3. Missing qualifications

The artboard shows magenta placeholder text where the post-nominals should be
for four doctors. These are left blank rather than guessed — inventing a
surgeon's qualifications is not something to do quietly.

- **Dr Eric Tai** — Specialist Anaesthetist
- **Dr Nathan Vujcich** — Oral and Maxillofacial Surgeon
- **Dr Rob Choa** — Specialist Plastic Surgeon
- **Dr Joseph Luo** — Specialist Plastic Surgeon

## 4. Pages the design links to but never designs

The XD draws links to four pages it provides no layout or copy for. All four are
built on the standard page template and currently show a short "content is being
finalised" placeholder, so no link in the site is dead.

| Page | Linked from |
|---|---|
| **Fees, Charges & Insurance** | For Patients & Visitors dropdown, and referenced in the Preparing for your Admission copy |
| **Privacy Policy** | Footer, all thirteen artboards |
| **Disclaimer** | Footer, all thirteen artboards |
| **Make a Payment** | The header button, all thirteen artboards |

For Make a Payment there is a second option: the existing site has a page at
`tpph.com.au/patients/make-a-payment-2/`. If the client would rather keep using
it, the header button can point there instead — that is a one-field change on
the Site Settings screen.

## 5. Errors and oddities in the design, reproduced as drawn

We have not silently corrected these. Please confirm the intent before we change
anything.

- **About → Vision, Mission and Values → Principles**, third bullet reads
  "A commitment to being a financially **health** business." Presumably
  "healthy".
- **Post Operative Care** opens "Please follow any specific instructions
  **provide** to you by your surgeon". The artboard reads that way; we have set
  it as "provided", which is the one place we corrected the design's grammar
  rather than reproducing it. Say the word if you would rather it matched.
- **About → Our Hospital**, the `ABOUT US` eyebrow is drawn in a lime
  `#8aba31` that appears nowhere else in the design and measures 2.0:1 against
  the cream behind it — well under the 4.5:1 accessibility minimum. It is built
  as drawn; say the word and it goes olive, which is what every other small
  label on the site uses.
- **Visitors**, two of the four column headings are typed in capitals in the
  artboard (`DAY PATIENT VISITORS`, `INPATIENT / OVERNIGHT PATIENT VISITORS`)
  and two are not. Reproduced as drawn.
- **About → Our Team**, the banner reads "About", not "Our Team" — the artboard
  puts the parent section's name in the banner on every child page. Same as the
  three For Patients & Visitors children.

## 6. Needed from Webstein, not the client

- **Google Maps API key — supplied and installed** on the local build as
  `TPPH_GOOGLE_MAPS_KEY` in `wp-config.php`. The map now renders live on Home
  and Contact Us in the brand palette.

  The key can now be set in either place: `TPPH_GOOGLE_MAPS_KEY` in
  `wp-config.php`, which wins, or **Site Settings → Map** for an environment
  where editing files is awkward. That is what the dev site needed — an import
  brings the database across but never `wp-config.php`, so the map fell back to
  its facade there.

  Still to do before launch: **restrict the key** to the site's domains in the
  Google Cloud console. A Maps key is readable in the page source of every site
  that uses one, so referrer restriction is the only thing standing between an
  unrestricted key and someone else's bill.

## 7. Worth flagging, low priority

- **Source images top out at 1600px wide.** Fine at 1x, slightly soft on a 2x
  desktop display for the full-bleed page banners. Worth asking Edge Creative
  for larger originals if the client notices.
- **Alt text** has been written for every image from what it shows, so the site
  ships accessible rather than waiting on a content pass. All of it is editable
  in the Media Library if the client wants different wording.
- **The Our Team card interaction** is now built to Edge Creative's note:
  hovering a card slides the biography up over the portrait from the bottom
  edge. Because hover does not exist on touch, tapping a card does the same
  thing on a phone or tablet, with a close button that only appears in that
  case. Keyboard users get it on focus, and Escape closes.

---

## What is already done

For completeness: all eleven designed pages are built and populated, the
navigation and both dropdowns work including the two anchor links into Our Team,
the doctor filter and biography cards work, the site is responsive from 390px
up, and Lighthouse scores 97–100 on Performance, 100 on Accessibility, 100 on
Best Practices and 100 on SEO on every page. See [LIGHTHOUSE.md](LIGHTHOUSE.md).
