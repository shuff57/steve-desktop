# SPEC-2-2-PUSH

**Book:** introduction-to-stats-sh
**Skill:** mom-transfer

## Scope

Push this session's edits to `mom-content/books/introduction-to-stats-sh/hw/2-2-histograms-frequency-polygons-and-time-series-graphs.json` into the live master course, then verify by answering every part in Teacher Preview. The manifest is the source of truth for slot order and points — read it fresh before doing anything, do not trust the table below if the two disagree.

- **Course (question home / master):** cid `334437`
- **Assignment (already live, being updated in place):** aid `23262795`, currently still titled with the old name — rename it to **"2.2 Histograms and Frequency Tables"**.
- **copyfrom / dates:** already set from the original 2026-08-09 push. Do not touch assessment settings other than the name — do not re-copy a template, do not set dates (this course stays undated per `assessment-presets.json`).

## Do NOT touch these local files — claimed by the dispatching session

`mom-content/books/introduction-to-stats-sh/hw/2-2-histograms-frequency-polygons-and-time-series-graphs.json`, `mom-content/reference/question-library.json`, and the five `.php` files below are all claimed (`msg.mjs owners` will show `claude`). **Do not edit any of them.** Read them for source content only. Report every qid/instance-id/points value you produce back through the message center reply — the dispatching session applies the manifest and index write-back itself, after independently verifying your report against the live course.

## Target state (15 slots, 100 points total — verify this sums to 100 before you finish)

| Slot | File | qid (existing / null = file new) | Points | Action |
|---|---|---|---|---|
| 1 | questions/descriptive-stats/frequency-columns-what-they-sum-to.php | 1874131 | 5 | already attached — confirm still attached at slot 1, points 5 |
| 2 | questions/descriptive-stats/back-solve-frequency-from-cumulative.php | 1873372 | 5 | already attached — confirm |
| 3 | questions/descriptive-stats/diagnose-cumulative-vs-individual-percent-claim.php | 1873382 | 6 | already attached — confirm |
| 4 | questions/descriptive-stats/cumulative-relative-frequency-at-most.php | 1873377 | 6 | already attached — confirm |
| 5 | questions/descriptive-stats/percent-more-than-cutoff-via-complement.php | 1873380 | 6 | already attached — confirm |
| 6 | questions/descriptive-stats/complete-grouped-frequency-table-discrete.php | **null — NEW** | 6 | **file into library (moddataset.php, cid 334437), then attach at slot 6** |
| 7 | questions/descriptive-stats/grouped-frequency-table-and-graphs.php | 1873375 | 6 | already attached — confirm |
| 8 | questions/displays/q2-histogram-relative-frequency.php | 1874132 | 6 | already attached — confirm |
| 9 | questions/descriptive-stats/histogram-bin-width-comparison.php | 1874133 | 6 | already attached — confirm |
| 10 | questions/descriptive-stats/compare-two-relative-frequency-histograms.php | 1874134 | 6 | **UPDATE IN PLACE** — see below |
| 11 | questions/descriptive-stats/histogram-classes-and-shape-from-data.php | 1874135 | 6 | already attached — confirm |
| 12 | questions/descriptive-stats/build-the-grouped-frequency-table.php | 1874136 | 6 | already attached — confirm |
| 13 | questions/descriptive-stats/pre-frq-grade-a-display-choice.php | 1874245 | 12 (was 10) | **UPDATE IN PLACE** — see below, also repoint points 10→12 |
| 14 | questions/descriptive-stats/find-the-class-width.php | **null — NEW** | 8 | **file into library, then attach at slot 14** |
| 15 | questions/descriptive-stats/label-the-bins-from-a-class-width.php | **null — NEW** | 10 | **file into library, then attach at slot 15** |

Total: 100.

**Four questions that were at this assignment's old slots 1, 3, 4, 6 are no longer in the manifest** (they were: complete-simple-frequency-table.php qid 1873371, q18-relative-and-cumulative-frequency.php qid 1873378, classify-then-build-frequency-table.php qid 1873383, relative-frequency-from-hurricane-table.php qid 1873376). **Detach these four from aid 23262795** (detach = remove from this assessment only; do NOT delete the library questions, they're used elsewhere — e.g. 1.3). Reorder/renumber the remaining and newly-attached questions so the assessment's own question order matches the slot column above exactly, and set each instance's points via `modquestion2.php?id=<instanceid>&aid=23262795&cid=334437` to match the table.

## The two in-place updates (qid unchanged, source changed)

Both files are confirmed used ONLY by this one assignment (checked this session against `question-library.json`'s `used_by`) — re-confirm that yourself before editing, since an in-place edit changes the question for every assessment attached to that qsetid.

- **qid 1873377... no — qid 1874134**, `compare-two-relative-frequency-histograms.php`: y-axis gridline step changed from every-5%-to every-2%, plot height grew 268→400px so the extra gridlines don't crowd. Open `moddataset.php?id=1874134&cid=334437`, replace `control`/`qtext`/`solution` from the current file content (CodeMirror, not the raw textarea — see mom-transfer's trap #1), save, byte-exact read back.
- **qid 1874245**, `pre-frq-grade-a-display-choice.php`: box-plot scenario replaced with histogram-vs-bar-graph (rubric categories, points, and structure otherwise unchanged — still Histogram/Bar Graph/Recommendation, still 3-choices + multans + 3-choices shape). Same update-in-place procedure. Also update this instance's points 10→12 via `modquestion2.php`.

## Filing the three new questions

Standard flow from the skill: `moddataset.php?aid=23262795&cid=334437` with no `id` opens a new-question form. Fill the five fields from each `.php` file's five markers (`NAME - DESCRIPTION` → description, `SET QUESTION TYPE TO` → qtype via the `#qtypedd` picker — all three are `multipart`, `COMMON CONTROL`/`QUESTION TEXT`/`ANSWER` → control/qtext/solution via CodeMirror). Strip the marker lines themselves before filing — they are delimiters, not content. Submit, capture the new `qsetid` from the response, then attach it via `modquestion2.php?qsetid=<qid>&cid=334437&aid=23262795&from=addq&process=true&usedef=true`, then set its points via the instance's `modquestion2.php?id=<instanceid>...`.

## Verify contract

1. **Static audits first** (fast, catches real defects the read-back alone misses): `node mom-content/_push/qtext-audit.mjs`, `node mom-content/_push/anstypes-audit.mjs`, `node mom-content/_push/usecheck.mjs` against the three new/edited files. Report clean or not.
2. **Byte-exact read-back** on all five touched questions (em dash → `--`, trim leading newline) and **qtype audit** (form's `[name=qtype]` vs each file's `SET QUESTION TYPE TO:` marker) for all five.
3. **Teacher Preview**, one session, answer and submit **every part of all fifteen questions** (not just the five touched ones — confirm the four detaches didn't leave a gap and the reordering didn't break anything), polling each question's `.scoreresult` before moving to the next (submission is AJAX and races). Strip bidi isolates (`⁦-⁩`) before parsing the score. Select `choices`/`multans` options by rendered label text, never by source index — options are shuffled per seed.
4. Confirm the header reads **Score: 100/100 (or 102/100 with the early-finish bonus), Answered: 15/15** in the same session that answered (a reopened preview shows no prior score).
5. **Full-page screenshot** of the assignment in Teacher Preview (one-shot `Emulation.setDeviceMetricsOverride`, not scroll-and-stitch) for the two edited-in-place histogram/pre-FRQ questions at minimum, so a human can eyeball typesetting — this skill has caught real defects (concat leaks, wrong function names) that passed every text-based check.

## Report format

Reply via the message center (`--re last`), addressed to claude. For every one of the 15 slots: slot number, title, qid (existing or newly-filed), points, attached (y/n), graded correct (y/n) — mark **unverified** for anything you did not personally fill/submit/see graded, never claim success you didn't observe. State the final header score and Answered count verbatim. State the static-audit results. State the new qsetids for the three filed questions explicitly (slot 6 / 14 / 15) so the dispatching session can write them into the claimed manifest and `question-library.json` itself. State the assignment's new name as read back from the settings page. If anything failed twice on the same question, stop and report it rather than grinding further.

## Read this before starting

`mom-content/reference/transfer-rules.md` and `mom-content/reference/learned-rules.md` — both hold real failures from earlier pushes into this same course. The render sandbox (mom.huffpalmer.fyi) is unreachable this session (TLS interception, already documented, do not retry it) — go straight to the live course for everything.
