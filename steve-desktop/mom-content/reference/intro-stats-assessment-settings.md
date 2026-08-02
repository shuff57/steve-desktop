# Intro to Stats — MyOpenMath assessment settings

Course **334243** ("8 Intro to Stats"), **period 4** / even-day track. Written 2026-08-02.

These values were **read back out of MyOpenMath**, not copied from intent — this is what the
course actually has. Regenerate by reading one assessment of each kind off
`addassessment2.php?id=<aid>&cid=334243`.

All five kinds are copied from **1.1 Definitions of Statistics** (aid `23108651`), which is the
homework template, and then adjusted per the table.

## Settings by kind

| Setting | Homework | Practice | Lab | Group Test | Individual Test |
|---|---|---|---|---|---|
| Attempts per question | 3 | 3 | 3 | 2 | 2 |
| Versions per question | 20 | 20 | 20 | 1 | 1 |
| Penalty per version | 0 | 0 | 0 | (absent) | (absent) |
| Penalty per attempt | 0 | 0 | 0 | 0 | 50 |
| …starting after attempt | 1 | 1 | 1 | 1 | 1 |
| Early-finish bonus % | 2 | 2 | 0 | 0 | 0 |
| …hours before due | 24 | 24 | 1 | 1 | 1 |
| Gradebook category | 792509 (HW) | 792509 (HW) | 792510 (GROUP) | 792510 (GROUP) | 792511 (IND) |
| Late passes | 2 (Up to 1) | 2 (Up to 1) | 2 (Up to 1) | 0 (None) | 0 (None) |
| Late pass after due | yes | yes | yes | no | no |
| Open for practice after due | yes | yes | yes | yes | yes |
| Time limit (min) | — | — | — | 89 | 81 |
| May work past limit | (absent) | (absent) | (absent) | yes | yes |
| Scores shown | during (On each question immediately) | during (On each question immediately) | during (On each question immediately) | during (On each question immediately) | during (On each question immediately) |
| Answers shown | after_lastattempt (After the last try on a question) | after_lastattempt (After the last try on a question) | after_lastattempt (After the last try on a question) | after_lastattempt (After the last try on a question) | after_lastattempt (After the last try on a question) |
| Display | full (All questions at once, or in pages) | full (All questions at once, or in pages) | full (All questions at once, or in pages) | full (All questions at once, or in pages) | full (All questions at once, or in pages) |

## Dates and times

Times come from the 2026-27 bell schedule, period 3-or-4 block:

| Day type | Period runs |
|---|---|
| Regular (M, T, Th, F) | 10:11 am – 11:40 am |
| Wednesday | 10:33 am – 11:54 am |
| Minimum day (Oct 27, Jan 26, Mar 2) | 9:37 am – 10:37 am |

| Kind | Opens | Due |
|---|---|---|
| Homework | its class day, at the period start | the NEXT class day, at the period start |
| Lab | same as homework | same as homework |
| Practice test | the chapter's last lab day, period start | the day the chapter test opens, period start |
| Group test | its class day, period start | **same day**, period end **+7 min** |
| Individual test | its class day, period start | **same day**, period end **+7 min** |

A test's time limit is the period length exactly (89 min regular, 81 min Wednesday). Students may
work past the timer (`allowovertime`), and the window stays open 7 minutes past the bell so that
overtime is actually usable — the close time and the timer have to agree or the window shuts first.

## Other per-kind facts

- **Tests carry an access passcode**; homework, labs and practice tests do not. Codes are listed in
  `~/Documents/mom-test-passcodes-2026-27.md`.
- **Tests allow no late passes.** Homework, labs and practice tests allow up to 1, usable after the
  due date.
- **The 2% early-finish bonus is on for homework and practice tests only** — off for labs and both
  test kinds.
- Every assessment carries three resource links: **Book** (its own bookSHelf section, or the book
  index where that section is not published yet), **rāSHio**, and **Desmos Scientific Calc**.
- Student instructions (`intro`) are generated from these settings, so the text and the form agree.

## Counts

| Kind | Count | Category |
|---|---|---|
| Homework | 42 | HW |
| Lab | 13 | GROUP |
| Practice test | 12 | HW |
| Group test | 12 | GROUP |
| Individual test | 12 | IND |
| **Total** | **91** | |

## Course front matter

The two intro blocks were repointed from the OpenIntro/AHSS textbooks to bookSHelf and rāSHio on
2026-08-02. Items were retitled and repointed, never deleted, so the block structure survives.
Originals, if any of it needs reverting:

| Item id | Was | Now |
|---|---|---|
| 10929653 | "OpenIntro Statistics & Advanced High School Statistics" welcome text | "bookSHelf and rāSHio" |
| 10189792 | `openintro.org/go/?id=ahss3_learning_objectives` | book index |
| 10189793 | "Advanced High School Statistics 3rd ed" → `openintro.org/redirect.php?go=ahss` | "bookSHelf — Introduction to Statistics" |
| 10189794 | "OpenIntro Statistics 4th ed" | "rāSHio — statistics tool" → `rashio.app` |
| 10189795 | "Section Summaries and Chapter Highlights (AHSS)" | "Section summaries and chapter highlights" → book index |

## Known gaps

- 17 assignments (chapters 10–13 and 9.6) point **Book** at the book index because their sections
  are not published yet. Re-run the link pass when they are.
- `earlybonushrs` reads 1 rather than 24 on labs and tests. It is inert there because
  `earlybonus` is 0, but it is not a value anyone chose.
- Periods 3 and 7 meet on the ODD-day rotation and period 7 meets 2:03–3:32, so they need their own
  courses; a MyOpenMath assessment carries one date window and cannot serve all three.

