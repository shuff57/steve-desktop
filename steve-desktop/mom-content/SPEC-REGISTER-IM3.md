# SPEC — Register Integrated Math 3 (MOM course 285887)

**Book:** integrated-math-3
**Skill:** mom-transfer

Read a live MyOpenMath course and write down what is in it.

**This is a READ-ONLY survey of MyOpenMath.** You create nothing in MOM, you edit nothing in MOM,
you click no Save button on any MOM page, you submit no form. Every MOM request you make is a GET.
The only writes you make are to files on disk, listed under "What you write".

If any path or URL in this spec does not resolve, **STOP and say so in your reply.** Do not guess a
path, a course id, or a repo name. A run that invents a path burns the session and produces nothing.

## Absolute paths

```
content root   C:/Users/shuff/Documents/GitHub/steve-desktop/steve-desktop/mom-content
book registry  C:/Users/shuff/Documents/GitHub/steve-desktop/steve-desktop/mom-content/books/_books.json
library map    C:/Users/shuff/Documents/GitHub/steve-desktop/steve-desktop/mom-content/reference/question-library.json
shape example  C:/Users/shuff/Documents/GitHub/steve-desktop/steve-desktop/mom-content/books/introduction-to-stats-sh/hw/1-1-definitions-of-statistics-probability-and-key-terms.json
your output    C:/Users/shuff/Documents/GitHub/steve-desktop/steve-desktop/mom-content/books/integrated-math-3/
```

## The work

For each of the **71 assessments** listed at the bottom of this spec:

1. GET `https://www.myopenmath.com/course/addquestions2.php?aid=<AID>&cid=285887`
2. Extract every question row from the "Questions in Assessment" table
3. Write one manifest JSON

The course crawl is **already done** — the aid list below is complete and verified. Do not crawl
`course.php` to rediscover it. Do not add an assessment that is not in the list, and do not skip one
that is.

## Extracting a question row

The page renders a table whose columns are `Order | Description | Features | ID | Preview | Type |
Avg Time | Points | Actions`. Per row:

| field | where it comes from |
|---|---|
| `slot` | the `Order` cell — `Q1`, `Q2`, … → integer `1`, `2`, … |
| `title` | the `Description` cell, trimmed |
| `qid` | the `ID` cell. Equivalently the `id=` on that row's `moddataset.php?id=<qsetid>&aid=…` link |
| `points` | the `Points` cell, as a number |

Ignore the `template=true&makelocal=…` variant link — each question has **two** `moddataset.php`
links and only the one without `template=true` carries the real qsetid. Taking both double-counts
every question.

Verified live 2026-08-16 on aid 22313212: 6 questions, ids 468549 / 468616 / 753577 / 766115 /
1178549 / 1178548, types multipart×4 + draw×2, 1 point each. **Use that assessment as your
smoke test before looping** — if your extractor does not return exactly those 6 rows with those
ids, fix the extractor before touching the other 70.

An assessment with zero questions is legitimate. Write the manifest with `"questions": []` and
report it in your reply. Do not skip it.

## Traps that have already bitten on this exact site

Read these. They are not hypothetical; each one cost a real run.

- **IMathAS emits unquoted HTML attributes** (`name=qtype`, `type=radio`). A regex expecting
  `name="qtype"` matches nothing and condemns every question. Prefer the DOM over raw-HTML regex.
  **When a check fails 100% of a population, suspect the check before the data.**
- **A long loop inside one `js()` call blows the CDP read timeout while the work keeps running.**
  One page per `js()` call. Never blind-retry a timeout — re-read state first.
- **`browser-harness`'s `js()` cannot carry a backslash escape or a regex literal.** No `\n`, no
  `/(\d+)/`. Build newlines with `String.fromCharCode(10)`, split on plain strings, and do all real
  pattern work in Python. The reported error points at the escape, not at anything wrong with the page.
- **A CSS selector containing `=` must be quoted**: `a[href*="aid="]`, never `a[href*=aid=]` — the
  latter throws `not a valid selector`.
- **The first DOM extraction after a load can race MathJax** and come back missing rows. Any
  single-shot read needs a second extraction, or a check that the row count is non-zero before
  accepting it.
- **An expired MOM session serves the login form AT the content URL.** A dead session therefore
  looks like a successful survey of an empty course. If a page has an `input[type=password]` or no
  "Questions in Assessment" heading, **STOP and report it** — do not write an empty manifest.
- Several of these assessments warn *"This assessment has already been taken."* That warning is
  expected and harmless on a GET. It is not an error and not a reason to stop. Just never act on it.

## Browser

Drive through **browser-harness**. `new_tab(url)` ONCE at the start, then `goto_url(url)` +
`wait_for_load()` for every navigation after. One tab, navigated — not a tab per page. 71 sequential
page loads through one tab.

## Track assignment — apply this rule mechanically, do not improvise

Match against the assessment name, case-insensitive, **first match wins, in this order**:

| # | name contains | track |
|---|---|---|
| 1 | `group` | `group` |
| 2 | `individual`, `final test`, `mock final` | `ind` |
| 3 | `practice`, `review`, `mock` | `practice` |
| 4 | anything else | `hw` |

Rule 1 beats rule 3, so "Unit 7 Group Quiz" is `group`, not `practice`. If a name genuinely fits
none of these you have made an error — the fallback is `hw`, and every fallback is fine. Report the
track counts; do not invent a fifth track.

## Matching qsetid back to a disk file

`reference/question-library.json` maps `questions/<family>/<file>.php` → `{ qsetid, cid, filed, qtype }`.
Build the reverse map (qsetid → path) **once, in Python, from that file**. Then per slot:

- qsetid present in the map → `"file_path": "questions/..."`
- qsetid absent → `"file_path": null` and `"unbanked": true`

**Never guess a file_path from a title match.** A wrong file_path silently points a future authoring
pass at the wrong source, which is worse than a null. Most IM3 questions are MyOpenMath library
questions (ids like `468549`, `1178549`) rather than entries from this bank (ids like `1867369`), so
**expect the large majority to come back unbanked.** That is the correct, expected result — it is
not a failure, and it is not a reason to loosen the match.

## Manifest shape

One file at `mom-content/books/integrated-math-3/<track>/<slug>.json`.

`<slug>` = name lowercased, every run of non-alphanumeric characters collapsed to a single `-`,
leading/trailing `-` stripped. So `7.2.1 & 7.2.2 Determining Missing Parts of Triangles` →
`7-2-1-7-2-2-determining-missing-parts-of-triangles`. If two assessments in the same track collapse
to the same slug (there are two `Practice Test` and two `Chapter 1 Group Test`-style names in this
course), append `-2`, `-3`, … in aid order, and **list every collision in your reply.**

```json
{
  "name": "Common Angles in the Unit Circle",
  "book": "integrated-math-3",
  "kind": "hw",
  "slug": "common-angles-in-the-unit-circle",
  "target": { "cid": "285887", "aid": "22313212" },
  "filing": { "cid": "285887", "aid": "22313212" },
  "created_at": "2026-08-16",
  "block": "0-4",
  "questions": [
    { "slot": 1, "file_path": null, "unbanked": true, "qid": "468549",
      "title": "Unit Circle Identifying angles", "points": 1 }
  ]
}
```

Read the `introduction-to-stats-sh` example named above first and match its key order and
indentation. Write **no `.md` sidecar** — those are hand-authored blueprints, not survey output.

## Register the book

Add to `books/_books.json`, preserving the existing three entries, the `_purpose` key, and the
file's formatting:

```json
{ "slug": "integrated-math-3", "title": "Integrated Math 3" }
```

## What you write

**Only** these:

```
mom-content/books/_books.json                  (append ONE entry)
mom-content/books/integrated-math-3/**         (new)
```

Do **not** touch `reference/question-index.json` — it is derived and gets rebuilt by
`bun mom-content/reference/sync-index.ts` by whoever reviews this. Do not touch
`reference/question-library.json` (nothing was filed, so nothing changed). Do not edit any question
`.php`. Do not run `git add` or `git commit`.

## Self-check before you reply — report every number

1. **Manifest count == 71.** If not, list which aids are missing. This is the gate; a run that
   writes 70 has failed even if all 70 are perfect.
2. Every written `.json` parses.
3. Per-folder counts match exactly:

   | block | expected |
   |---|---|
   | `0` | 6 |
   | `0-3` | 5 |
   | `0-4` | 8 |
   | `0-3-1` | 9 |
   | `0-3-2` | 7 |
   | `0-3-3` | 5 |
   | `0-4-1` | 13 |
   | `0-4-2` | 10 |
   | `0-4-3` | 8 |

4. Slots within each manifest are 1..n contiguous, no gaps, no duplicate `slot`.
5. No duplicate `qid` within a single manifest. Duplicates **across** manifests are legitimate —
   report them, never remove them.
6. Count of slots with `file_path: null`, and the count with a real path.
7. `_books.json` still contains its three original entries plus the one new one.
8. Total question count across all 71 manifests.

## Reply

Reply through the message center with `--re <id of the message that sent you here>`. Include:

- a table: track | #assignments | #questions | #unbanked
- the per-block counts from check 3, next to the expected column
- every slug collision you had to disambiguate
- every assessment that came back with zero questions
- **one unpinned design decision you had to make that this spec did not settle**
- **explicitly state which checks you could NOT perform, and why**

Do not claim you registered an assessment whose `addquestions2.php` page you did not actually load.

---

## The 71 assessments

| aid | block | name |
|---|---|---|
| 19998609 | 0 | Entering Answers - HW |
| 22428234 | 0 | Mid Unit Review |
| 22437996 | 0 | Mid Unit Quiz |
| 22454172 | 0 | Cosine Function Basic Transformations |
| 22463005 | 0 | More Sine Transformations |
| 22464945 | 0 | More Cosine Transformations |
| 21169941 | 0-3 | Review - Chapter 1 - Functions and Algebra |
| 21191473 | 0-3 | Review - Chapter 2 - Parent Functions and Solving Rational Functions |
| 21223233 | 0-3 | Review - Chapter 3 - System of Equations and Inequalities |
| 21223334 | 0-3 | Mock Final Test - Semester 1 |
| 21278729 | 0-3 | Final Test - Semester 1 |
| 22313212 | 0-4 | Common Angles in the Unit Circle |
| 22313213 | 0-4 | Radians in the Unit Circle |
| 22341359 | 0-4 | Finding Terminal Points |
| 22352637 | 0-4 | Review on all unit Circle |
| 22395197 | 0-4 | Graphing Sine Function |
| 22437997 | 0-4 | Sine Function Basic Transformations |
| 22408255 | 0-4 | Graphing Cosine Functions |
| 22417024 | 0-4 | Graphing Tangent Function |
| 19998939 | 0-3-1 | 1.1.1 Functions |
| 19999337 | 0-3-1 | 1.1.2 Using Graphing to Explore a Function - Domain and Range |
| 19999388 | 0-3-1 | 1.1.3 Function Investigation |
| 19999389 | 0-3-1 | 1.1.4 Combining Linear Functions |
| 20699845 | 0-3-1 | Exponents and Quadratics Review |
| 20732758 | 0-3-1 | 1.2.1 Representing Points of Intersection |
| 20764950 | 0-3-1 | Chapter 1 Practice Test |
| 19999830 | 0-3-1 | Chapter 1 Group Test |
| 20000049 | 0-3-1 | Chapter 1 Individual Test |
| 20819157 | 0-3-2 | Parabola Graphing with Graphing Form |
| 20819158 | 0-3-2 | Cubic/Absolute Value/ Square Root |
| 20819159 | 0-3-2 | 2.1.1 Standard Form to Graphing Form |
| 20869294 | 0-3-2 | Complete the Square |
| 20894250 | 0-3-2 | Exponential and Hyperbola Graphing Form |
| 21064022 | 0-3-2 | Rational Functions Unit - Practice Test |
| 21076884 | 0-3-2 | Rational Functions Unit - Ind Test |
| 21085996 | 0-3-3 | 3.1.1 Strategies for Solving Equations |
| 21098728 | 0-3-3 | 3.1.3 Solving Equations Graphically |
| 21107841 | 0-3-3 | Systems of Linear Inequalities |
| 21098729 | 0-3-3 | System of Non-Linear Inequalities |
| 21098730 | 0-3-3 | Piecewise Functions |
| 21478256 | 0-4-1 | Undoing a function |
| 21574537 | 0-4-1 | Composition of functions |
| 21613848 | 0-4-1 | Composition and Inverses |
| 21673286 | 0-4-1 | Restricting Domain and Range |
| 21673287 | 0-4-1 | Undoing Exponentials - Intro to Logs |
| 21673322 | 0-4-1 | Graphing Logs using Exponentials |
| 21951528 | 0-4-1 | Power and Product Property |
| 21951529 | 0-4-1 | Quotient Property and Applications of Logs |
| 21673437 | 0-4-1 | Practice Test Chapter 5 |
| 22027209 | 0-4-1 | Group Test Chapter 5 - scantron |
| 22073898 | 0-4-1 | Individual Test Chapter 5 - A |
| 22074532 | 0-4-1 | Individual Test Chapter 5 - B |
| 21673501 | 0-4-1 | Individual Test Chapter 5 |
| 22100093 | 0-4-2 | 7.2.1 & 7.2.2 Determining Missing Parts of Triangles |
| 22100094 | 0-4-2 | Law Of Sines |
| 22100095 | 0-4-2 | Law of Cosine |
| 22100098 | 0-4-2 | Area of a Triangle |
| 22100096 | 0-4-2 | Trig and Triangles Quiz |
| 22100097 | 0-4-2 | Mixed Triangle Review |
| 22100099 | 0-4-2 | Practice Test |
| 22100100 | 0-4-2 | Unit 7 Group Quiz |
| 22100101 | 0-4-2 | Unit 7 Group Quiz (V2) |
| 22100102 | 0-4-2 | Unit 7 Individual Exam |
| 22184231 | 0-4-3 | 8.1.2 Predicting and Creating graphs |
| 22197263 | 0-4-3 | 8.1.3 Writing Exact Equations |
| 22197264 | 0-4-3 | 8.2.1 Writing Equations Using Complex Roots |
| 22197265 | 0-4-3 | 8.2.2 More Real and Complex Roots |
| 22252163 | 0-4-3 | 8.3.1 Polynomial Division |
| 22252164 | 0-4-3 | 8.3.2 Polynomial Division |
| 22252134 | 0-4-3 | Chapter 7 Practice Test |
| 22273439 | 0-4-3 | Chapter 7 Group Test |

Block `0-5` (`TEMPLATES`, hidden — 3 assessments) is **deliberately excluded**. Do not register it.
