# Data Collection Lab — §1.5 (Intro Stats -SH)

Auto-graded lab questions for the 1.5 Data Collection Experiment: systematic sampling mechanics
(Try It 1.5.1), the two frequency tables of the class movie-count data (Try It 1.5.2/1.5.3), the
four at-most/more-than reading questions, grouping tradeoffs, and the Camila-vs-Rosa settlement.
Created 2026-08-26. The lab itself is the source: the 1.5 bookSHelf section is
chapter-1-sampling-and-data/1.5_data_collection_experiment.html.

## Files

| File | Topic | Type | qid |
|---|---|---|---|
| `systematic-sampling-positions.php` | Mark the 12 positions on an odd-N list, step 4, wrap at the bottom | ntuple set | 1890450 |
| `why-twelve-distinct-marks.php` | The 24-name counter-case: gcd(24,4)=4 makes only 6 distinct names | choices x2 | 1890458 |
| `systematic-versus-srs.php` | Exactly one random decision; why systematic is not SRS | choices x2 | 1890459 |
| `ungrouped-table-blanks.php` | Back-solve a blanked frequency and two relative frequencies | number + numfunc x2 | 1890460 |
| `cumulative-relative-frequency-named-row.php` | Read one cumulative cell off a named row | numfunc | 1890461 |
| `grouped-table-blanks.php` | Back-solve a blanked group frequency and two relative frequencies | number + numfunc x2 | 1890462 |
| `at-most-two-which-table.php` | Proportion + which table and why, cut inside the 2-3 row | numfunc + choices | 1890463 |
| `at-most-three-which-table.php` | Proportion + which table and why, cut on the boundary | numfunc + choices | 1890465 |
| `more-than-two-which-table.php` | Complement + which table and why, cut inside the 2-3 row | numfunc + choices | 1890464 |
| `more-than-three-which-table.php` | Complement + which table and why, cut on the boundary | numfunc + choices | 1890466 |
| `grouping-hides-the-question.php` | Why a grouped table cannot answer more than two | choices x2 | 1890467 |
| `bottom-row-cumulative-check.php` | The not-1 bottom rule: count the drop, name the row, fix the bottom | number + choices + numfunc | 1890468 |
| `camila-vs-rosa-settlement.php` | Same share from both tables; the boundary explanation | numfunc x2 + choices | 1890469 |
| `why-switch-tables.php` | Which cut forces the switch; when staying is fine | choices x2 | 1890470 |
| `grouping-the-data-differently.php` | A legal regrouping and what grouping can and cannot change | choices x2 | 1890471 |
| `pre-frq-grade-a-table-comparison.php` | Grade four answers to the discussion question against the rubric | choices + multans + choices | 1890472 |

## Conventions

- Every dataset question draws its own random 60-count dataset with the shared generator:
  frequencies are multiples of 3, so every relative frequency is a multiple of 1/20 and the
  bottom cumulative is exactly 1 (see SPEC-1-5.md for the generator and its feasibility proof).
- numfunc for every share answer (abstolerance 0.00011), number + integer format for counts.
- The at-most/more-than choice scaffolds are structurally identical; the correct index is fixed
  by the cut: at 2 = ungrouped only (index 0), at 3 = either table (index 2).
- Solution guides repackage the section's own worked steps and its lesson sentences.
- Multipart for everything, $noshuffle all on every choices/multans part, scoped CSS (.qscope16)
  on the pre-FRQ.