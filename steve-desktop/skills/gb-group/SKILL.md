---
name: gb-group
description: Use when generating skill-based student pair groupings from a MyOpenMath gradebook tab — sorts students by Weighted Total %, fold-pairs the top half with the bottom half, and writes a clean two-column markdown table of names. Trigger whenever the user asks to "make groups", "pair students", "group by skill", or names a chapter/period file like "Ch 8 Groups p8" while a MyOpenMath gradebook tab is open.
---

# gb-group

> Build skill-balanced pair groups from the currently-open MyOpenMath (MOM) gradebook tab. Sort by Weighted Total %, fold-pair top half with bottom half, title-case names, and write a markdown table to the gradebook desktop folder.

## When to use
- User asks for groups, pairs, or partners "for Ch X period Y" with a MOM gradebook tab open.
- User says "skill-based pairs", "balanced groups", "fold pairs", or names a target file like `Ch 8 Groups p8`.

## When NOT to use
- Random groupings or alphabetical groupings — this skill is specifically the *fold pairing by Weighted Total %* pattern.
- Groups of 3+ as the default — pairs are the default; an odd class count produces exactly one trio (the last group). If the user wants all trios or quads, surface that and ask whether to extend the skill.
- No MOM gradebook tab open in playwriter — ask the user to open it instead of guessing.

## Prerequisites
- Playwriter MCP enabled and a session connected to the user's Chrome.
- An open browser tab on a MyOpenMath gradebook page (URL contains `myopenmath.com/course/gradebook.php`).

## Inputs
- **filename** (required): the file's display name, e.g. `Ch 8 Groups p8`. Used as both the title heading and the filename (`.md` appended).
- **output dir** (optional, defaults to `C:\Users\shuff57\Desktop\gradebook\`).

## Output
A markdown file at `<output-dir>\<filename>.md`. Two-column table, no header labels — column 1 is the group number (1, 2, 3, …), column 2 holds the group's members stacked vertically with `<br>` line breaks. The group number is just a label ("group 5"), not the student's skill rank.

```markdown
# <filename>

| | |
|---|---|
| 1 | Last, First<br>Last, First |
| 2 | Last, First<br>Last, First |
…
```

No grades, no extra commentary. The user prints/copies this directly.

## Procedure

### 1. Locate the gradebook tab
Find or create a playwriter session, then locate the MOM gradebook page among open tabs. Don't navigate — work with what's open.

```js
// In a playwriter session:
const pages = context.pages();
const gb = pages.find(p => p.url().includes('myopenmath.com/course/gradebook.php'));
if (!gb) throw new Error('No MyOpenMath gradebook tab open');
state.page = gb;
```

If multiple gradebook tabs are open, ask the user which course (the URL has `?cid=…`).

### 2. Extract students
Each student row uses a `<th>` for the name and `<td>` cells for grades. The final row is `Averages` and must be filtered out.

**Find the Weighted Total column by its header text, never by a fixed index.** Column count varies between courses and with the `Pictures` toggle. Measured 2026-09-01 on `cid=339304` (3 Intro to Stats): the layout was `Name | Pictures | Weighted Total % | GROUP 10% | HW 15%`, so the old hardcoded `tds[2]` read the GROUP column instead — where 20 of 31 students were a flat `100%`, which would have made the fold-pairing near-random. It fails silently: you still get percentages, just the wrong ones.

**Index against `row.cells`, not `querySelectorAll('td')`.** The header row is built entirely from `<th>`; a data row is one `<th>` (the name) plus `<td>` grades. `cells` counts both, so a single index is valid in both rows. A first attempt at this fix located the header at cell 2 and then tried to convert it to a `<td>` index by subtracting the `<th>` count *in the header row* (2), yielding `tds[0]` — the empty Pictures cell. Every student came back with a blank grade.

```js
const students = await state.page.evaluate(() => {
  const rows = [...document.querySelectorAll('table tr')];

  // Find the Weighted Total column by header text. Use HTMLTableRowElement.cells,
  // which counts <th> and <td> together, so the same index works on the all-<th>
  // header row and on a data row whose first cell is the <th> student name.
  let colIndex = -1;
  for (const row of rows) {
    const i = [...row.cells].findIndex(c => /weighted\s*total/i.test(c.innerText));
    if (i !== -1) { colIndex = i; break; }
  }
  if (colIndex < 0) throw new Error('No "Weighted Total" column header found');

  const out = [];
  for (const row of rows) {
    const nameTh = row.querySelector('th');
    const cells = [...row.cells];
    if (!nameTh || cells.length <= colIndex) continue;
    const name = nameTh.innerText.trim();
    if (!name || name.startsWith('Name') || name === 'Averages') continue;
    out.push({ name, weighted: cells[colIndex].innerText.trim() });
  }
  return out;
});
```

### 3. Title-case names
MOM lets students self-enter their names, so capitalization is inconsistent. Two real failure modes:
- All-lowercase: `piper, malakai` → should be `Piper, Malakai`.
- All-uppercase: `MARTIN, MAKENZIE` → should be `Martin, Makenzie`.

But mixed-case names are usually correct as entered and **must be preserved**: `D'Amato`, `DeMontigny`, `McKay`. A naive regex that lowercases everything-after-the-first-letter destroys those.

```js
function titleCase(name) {
  return name.replace(/[A-Za-z][A-Za-z']*/g, w => {
    // Short all-upper words (≤2 letters) are likely initials — preserve.
    if (w.length <= 2 && /^[A-Z]+$/.test(w)) return w;
    // Otherwise re-case only if uniformly upper or lower; preserve mixed-case.
    if (/^[a-z']+$/.test(w) || /^[A-Z']+$/.test(w)) {
      return w[0].toUpperCase() + w.slice(1).toLowerCase();
    }
    return w;
  });
}
```

Test cases this must handle correctly:
- `melton, myla` → `Melton, Myla`
- `MARTIN, MAKENZIE` → `Martin, Makenzie`
- `Boeldt, CJ` → `Boeldt, CJ` (initials preserved, NOT `Cj`)
- `D'Amato, Shelby` → `D'Amato, Shelby` (unchanged)
- `DeMontigny, Ronen` → `DeMontigny, Ronen` (unchanged)
- `MacMillan, Mary` / `McKay, Owen` → unchanged (mixed-case preserved)
- `steele, Richard` → `Steele, Richard` (per-word: `steele` is all-lower → fix; `Richard` is mixed → preserve)

### 4. Sort and fold-pair
Sort by Weighted Total % descending (parse `'53.8%'` → `53.8`). Then fold:

- `half = ceil(n / 2)`
- For `i` in `0..half-1`: pair `sorted[i]` with `sorted[i + half]`.
- If `n` is odd, the last top-half student (`sorted[half-1]`) has no fold partner. Don't emit a separate row for them — instead, **append them to the last completed pair as a trio** by stacking a third `<br>` name inside that group's cell. Example: `| 18 | Price, Lynn<br>Tirado, Isaias<br>Davis, Cecilia |`. The output has `floor(n/2)` rows total.

This puts the strongest student with someone in the middle of the class (not the very weakest), which is the standard "fold" pattern. Adding the leftover student to the last group keeps everyone partnered without leaving a `(unpaired)` placeholder.

### 5. Write the file
Compose markdown with the filename as the H1 heading, then a two-column table with an empty header row. Column 1 is the group number (1, 2, 3, …); column 2 holds both pair names stacked vertically with `<br>` (the trio row stacks three). Save to `<output-dir>\<filename>.md`.

```markdown
# <filename>

| | |
|---|---|
| 1 | Last, First<br>Last, First |
| 2 | Last, First<br>Last, First |
| 3 | Last, First<br>Last, First<br>Last, First |
```

### 6. Confirm to the user
Print the absolute path of the file written and the student count (e.g. `Wrote 32 students (16 pairs) to C:\Users\shuff57\Desktop\gradebook\Ch 8 Groups p8.md`).

## Rules
- **Read-only on MOM.** Never click anything, never navigate. Just extract.
- **Drop the `Averages` row.** It's a footer, not a student.
- **Find Weighted Total by header text, never a fixed `<td>` index.** A hidden or added column shifts it and the failure is silent — plausible-looking percentages from the wrong column.
- **Title-case every name.** Don't ship a file with `piper, malakai` in it.
- **Pairs by default; one trio when `n` is odd.** Never silently make all-trios or all-quads — if the user asks for that, stop and confirm.
- **No grades in the output file.** The teacher hands these to students; the grades stay private.

## Why this design
- *Fold pairing* (top with mid, not top with bottom) avoids the disengagement that happens when the gap between partners is too large. The strongest student helps someone who is "almost there", not someone who is lost.
- *Weighted Total %* is the column the teacher already trusts as a skill proxy — using it keeps the groupings explainable.
- *Markdown table, no grades* means the file is print-ready and shareable in class without exposing private data.
