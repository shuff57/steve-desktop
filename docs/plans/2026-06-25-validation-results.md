# Live Validation Results — 2026-06-25

Engine: browser-harness (logged-in Chrome) as the explore pass. App-validation
(real SiteMapper capture) still to run per the chosen login path.

## Site identities (resolved)

| Plan name | Real system | URL | Login state |
|-----------|-------------|-----|-------------|
| Keenan SafeSchool | Keenan SafeColleges | `butte-keenan.safecolleges.com` | authenticated (Steven Huff) |
| MyOpenMath | MyOpenMath (IMathAS) | `www.myopenmath.com` | authenticated (Steven Huff, instructor) |
| Ares LMS | **Aeries SIS** | `chicousd.aeries.net/teacher` | authenticated |
| Outlook (PVHS) | Outlook / O365 | `outlook.office.com` → `outlook.cloud.microsoft` | authenticated (Steven Huff) |

## MyOpenMath — `generalize` ✅ confirmed

Course URL pattern: `course/course.php?folder=0&cid={N}`. Courses on this account
include cid 306621 (Math 12), 316341 (Intro to Stats), 263001, 316900, 314128, …

Diffed two courses' interactive surface (cid-normalized hrefs):
- **Shared, class-agnostic nav** (only `cid` differs): `course.php`, `gradebook.php`,
  `listusers.php`, `showcalendar.php`. This is the "global way to interact with a
  class" — map once, substitute `cid`.
- 129/133 non-shared links were per-content-item (different content per class) —
  the noise the "don't map every class" rule deliberately excludes.

Authoring side already exists in `../mom`: `reference/*.json` docs map +
`/mom-section-to-questions` skill (draft → upload → `testquestion2.php` preview →
fix-loop → `addquestions2.php`). steve-desktop integrates by pointer, not copy.

## Aeries ("Ares") — `site` + **PII gate** ✅ PII-safe scrape proven

Teacher nav pages located (structure only): `StudentProfile.aspx`,
`EmergencyContacts.aspx`, `Students.aspx` (Demographics), `StudentGrades.aspx`,
`Classes.aspx`, `StudentSearch.aspx`, report runners, etc.

**Parent-email target** is on `EmergencyContacts.aspx` at STABLE ASP.NET ids
(do not change per student — only the value does):
- `#ctl00_MainContent_subStuTopEmail_lblPEM` — **P**arent **EM**ail
- `#ctl00_MainContent_subStuTopEmail_lblSEM` — **S**tudent **EM**ail
- `#ctl00_MainContent_subStuTopEmail_divPEM` / `divSEM` — containers
- `#ctl00_MainContent_subStuTopEmail_hfParent` — hidden parent flag

**PII guarantee demonstrated:** the scrape returned only `{tag,type,id,name,
valLen}` — value text was never extracted, so **no email/name entered the agent
context**, only field lengths. At runtime the skill reads `lblPEM` on-device,
fills Outlook To, never logs it. This is the structural-selector-not-value
pattern the requirement demands.

## Outlook (PVHS) — `page` ✅ compose mapped

Entry: `role=button[name='New mail']`. Compose pane (chrome only, no inbox):
- `role=div[name='To']`, `role=div[name='Cc']`
- `role=input[name='Subject']`
- `role=textbox[name='Message body']`
- `role=button[name='Send']`, `role=button[name='Attach file']`

Note: inbox sender labels leaked into a broad query — confirms the Outlook PII
rule "map compose chrome only, never inbox."

## Keenan SafeColleges — `site` ✅ validated

Post-SSO lands on a "confirm it's you" gate (`WELCOME, STEVEN HUFF!` +
**`LOG ME IN!`** button at `/login`). Clicking it reaches the dashboard.

Stable training nav (`/training/home` = "My Assignments"):
- `/training/home` — My Assignments
- `/training/hist` — Training History
- `/training/extra` — Extra Training
- **`Continue`** button → launches the course player.

Matches existing maps (`butte-keenan-safecolleges-com/`: course-launch,
training-home, video-player). The `steve` skill owns the in-player course-taking
(video, knowledge checks, final assessment).

## PII gate — ran on EXISTING profiles, caught real leaks

`scripts/verify_no_pii.py .agents/site-profiles` hard-failed 3 MyOpenMath
gradebook profiles carrying real rosters on disk (`De Jesus, Angel`,
`Gonzalez Quintana, Enid`, `Hernandez Ruiz, Giovanni`). Root cause:
`mergedToProfile` does not redact. **Action: implement redact-before-save** for
PII domains (Aeries especially) before any new app capture.

## Next

1. Land redact-before-save (close the on-disk leak) — Aeries first.
2. App-validation pass: run the real SiteMapper per site, re-run the PII gate on
   the freshly saved profiles.
3. Build the "email this parent" skill chaining Aeries `lblPEM` → Outlook compose.
