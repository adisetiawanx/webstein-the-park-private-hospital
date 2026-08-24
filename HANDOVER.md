# Outstanding items — The Park Private Hospital

Everything the design specifies is built. This is what the **client** still owes
before the site is ready to go live, plus the one item we need internally.

Nothing here is a blocker for review; the site is complete and navigable as it
stands. Each gap renders as either the design's own placeholder or a neutral
one, never as a broken page.

---

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

## 5. Two errors in the design, reproduced as drawn

We have not silently corrected these. Please confirm the intent before we change
anything.

- **About → Vision, Mission and Values → Principles**, third bullet reads
  "A commitment to being a financially **health** business." Presumably
  "healthy".
- **Home → Become an Accredited Practitioner** card ends mid-sentence:
  "…a supportive, well coordinated environment designed to complement". The
  artboard stops there.

## 6. Needed from Webstein, not the client

- **Google Maps API key.** The design's map is custom styled — cream base, green
  roads — which needs the Maps JavaScript API. Until the key is set the site
  renders a styled static map with a working Get Directions link, so nothing is
  broken. Add it to `wp-config.php` as `TPPH_GOOGLE_MAPS_KEY` and restrict it to
  the live domain.

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
