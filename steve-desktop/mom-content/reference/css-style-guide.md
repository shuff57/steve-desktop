# CSS Style Guide

## Overview

All questions in this project share a common CSS + JS block (`$css_block`) that provides consistent styling for collapsible sections, rubric tables, solution guides, and answer key formatting. This block must be copied verbatim from `free-response-template.php` — do not simplify or modify the JS code.

## Usage

Define `$css_block` in Common Control, then reference it in question text or rubric variables:

```
$css_block = '<style>...</style><script>...</script>'

// For FRQ questions:
$rubricbutton = $css_block . '<div class="rubric-container">...</div>'

// For non-FRQ questions (solution guides):
$solutionguide = $css_block . '<div class="rubric-container">...</div>'
```

## CSS Classes

### Layout & Container

- class: `rubric-container`
  - purpose: Outer wrapper for any collapsible section
  - styles: `width:100%; font-family:Arial; font-size:medium; margin:1em 0`
  - usage: Wraps a `<details>` element

- class: `rubric-content`
  - purpose: Inner content wrapper with open/close animation
  - styles: Hidden by default (`max-height:0; opacity:0`), expands on open (`max-height:2000px; opacity:1`)
  - usage: Must be direct child of `<details>`, wraps all collapsible content

### Summary / Toggle

- element: `summary` (inside `.rubric-container details`)
  - styles: `cursor:pointer; background:#f8f8f8; color:#333; padding:0.35em 0.6em; font-weight:bold`
  - note: Default marker hidden via `::-webkit-details-marker { display:none }`

- class: `arrow-closed`
  - purpose: Right-pointing arrow shown when collapsed
  - content: `▸`

- class: `arrow-open`
  - purpose: Down-pointing arrow shown when expanded
  - content: `▾`
  - note: Hidden by default, shown only when `details[open]`

### Table Styling

- class: `rubric-table`
  - purpose: Styled table for rubric criteria or solution steps
  - styles: `border-collapse:separate; border-spacing:0; width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; font-size:small`
  - header: `th` gets `background:#f2f2f2; padding:8px; text-align:center; border:1px solid #ccc`
  - cells: `td` gets `padding:10px; border:1px solid #ccc; vertical-align:top; user-select:text`

### Theme Colors

- class: `row-colored`
  - purpose: Alternating row highlight
  - color: `background:#fff9ea` (warm cream)

- class: `col-header`
  - purpose: First column header cell
  - styles: `width:25%; border-top-left-radius:8px`

- class: `col-check`
  - purpose: Second column header cell
  - styles: `border-top-right-radius:8px`

- class: `col-cat-bot`
  - purpose: Last row first column (bottom-left radius)
  - styles: `border-bottom-left-radius:8px`

- class: `col-check-bot`
  - purpose: Last row second column (bottom-right radius)
  - styles: `border-bottom-right-radius:8px`

### Answer Key (Instructor Only)

- class: `ideal-ans`
  - purpose: Highlights ideal answer targets (visible only after `///` separator)
  - styles: `display:block; background-color:#e8f5e9; font-style:italic; font-weight:bold; font-size:0.95em; border-left:3px solid #4CAF50; padding-left:8px`
  - color: Green left border on light green background

- class: `full-response-box`
  - purpose: Green bordered box for complete model narrative
  - styles: `border:2px solid #4CAF50; background-color:#e8f5e9; padding:15px; border-radius:5px`

## JavaScript

The `$css_block` includes a `DOMContentLoaded` script that:
1. Finds all `.rubric-container details` elements
2. Adds smooth open/close animation via `maxHeight` and `opacity` transitions
3. Cleans up `maxHeight` on `transitionend` when closing

Do NOT simplify or remove this script — it handles edge cases with the CSS transition.

## Collapsible Section Template

### Student-Facing (Hints / Checklist)

```html
<div class="rubric-container">
  <details>
    <summary>
      <span class="arrow-closed">▸</span><span class="arrow-open">▾</span>
      Click to View Hints
    </summary>
    <div class="rubric-content">
      <p>Content here...</p>
    </div>
  </details>
</div>
```

### Worked Solution Guide (Non-FRQ Questions)

Used for trig, matrix, and other autograded questions. Uses `Step / Work` columns instead of `Category / Checklist`. The final answer gets a green highlight inside the last table cell.

**PHP pattern:**
```php
$solutionguide = $css_block . '
<div class="rubric-container">
  <details>
    <summary>
      <span class="arrow-closed">▸</span><span class="arrow-open">▾</span>
      Step-by-Step Solution
    </summary>
    <div class="rubric-content">
      <table class="rubric-table">
        <tbody>
          <tr>
            <th class="col-header">Step</th>
            <th class="col-check">Work</th>
          </tr>

          <tr class="row-colored">
            <td style="text-align:center;"><b>Given</b></td>
            <td>State the known values and what is being asked.</td>
          </tr>

          <tr class="row-colored">
            <td style="text-align:center;"><b>Step 1</b></td>
            <td>Description and computation for this step.</td>
          </tr>

          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Step 2</b></td>
            <td class="col-check-bot">Final computation.
              <div style="margin-top:8px;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">
                <b>Answer:</b> final value
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </details>
</div>'
```

**Key differences from FRQ rubric:**
- Headers: `Step / Work` (not `Category / Checklist`)
- No checkboxes or `<label>` elements
- No `.ideal-ans` spans — use inline green highlight `<div>` for the final answer
- First row is typically `Given` (states knowns), not a scoring category
- Last row must use `.col-cat-bot` / `.col-check-bot` for border radius
- Embedded matrix displays (`matrixdisplaytable()`) nest inside `<td>` cells

**Migration from inline styles:**

| Old (inline) | New (class-based) |
|---|---|
| `<div style="font-family:Arial;font-size:medium;margin:1em 0;border:2px solid #ccc;...">` | `<div class="rubric-container">` |
| `<summary style="cursor:pointer;padding:...">&#9658; Step-by-Step Solution</summary>` | `<summary><span class="arrow-closed">▸</span><span class="arrow-open">▾</span> Step-by-Step Solution</summary>` |
| `<div style="padding:1em 1.4em;line-height:1.8;">` | `<div class="rubric-content">` |
| Sequential `<p style="margin:...">` tags | `<table class="rubric-table">` with Step/Work rows |
| Standalone green `<div>` at bottom | Green `<div>` inside last `<td class="col-check-bot">` |

**Note:** Question text wrappers (`<div style="font-family:Arial; font-size:medium; line-height:1.6;">`) remain inline-styled. The `.rubric-container` class is semantically reserved for collapsible `<details>` sections.

### Instructor Rubric (FRQ — After `///` Separator)

```html
<div class="rubric-container">
  <details>
    <summary>
      <span class="arrow-closed">▸</span><span class="arrow-open">▾</span>
      Rubric & Model Response
    </summary>
    <div class="rubric-content">
      <table class="rubric-table">
        <tbody>
          <tr>
            <th class="col-header">Category</th>
            <th class="col-check">Checklist & Ideal Targets</th>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Category Name<br>(N pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Checklist item.
                  <span class="ideal-ans">Target: "model answer"</span></li>
              </ul>
            </td>
          </tr>
        </tbody>
      </table>
      <div class="full-response-box">
        <span style="color:#2E7D32; font-weight:bold;">Model Narrative Response:</span><br><br>
        $sample_narrative
      </div>
    </div>
  </details>
</div>
```

## Card-Based Question Layout (Linear Programming Style)

Used for autograded multipart questions where the problem setup and answer parts are visually distinct. The setup lives in a card with a border, and each answer part gets its own card with a blue letter label.

**Question setup card:**
```html
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.08),0 2px 4px -2px rgba(0,0,0,0.05);">
  <p>Problem description...</p>
  <table>...</table>
  <p><b>Instructions...</b></p>
</div>
```

**Answer part card (with inline answerbox):**
```html
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
  <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Label text: $answerbox[0]
</div>
```

**Resource/constraint table (inside setup card):**
```html
<table style="border-collapse:collapse; width:100%; font-family:inherit; font-size:inherit; margin:8px 0;">
  <tr>
    <th style="background:#f7f9fa; font-weight:600; color:#21242c; border:1px solid #dee1e3; padding:10px 18px; text-align:left;">Header</th>
    ...
  </tr>
  <tr>
    <td style="padding:10px 18px; border:1px solid #dee1e3;">Cell</td>
    ...
  </tr>
</table>
```

**Font stack:** `-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif` with `font-size:16px; line-height:1.6; color:#21242c; max-width:688px`.

**Key tokens:**

| Token | Value | Usage |
|-------|-------|-------|
| Card border | `#e5e7eb` | Setup and answer card borders |
| Card shadow | `0 4px 6px -1px rgba(0,0,0,0.08)` | Subtle lift effect |
| Label bg | `#e8f0fe` | Blue pill behind `a.`/`b.`/`c.` |
| Label text | `#1865f2` | Blue letter color |
| Table header bg | `#f7f9fa` | Light gray column headers |
| Table border | `#dee1e3` | Cell borders in resource table |
| Text color | `#21242c` | Primary dark text |

**Note:** The `$answerbox[N]` references must be OUTSIDE any `$variable = '...'` assignment in Common Control. Place them directly in the Question Text field so MOM renders them as input elements.

## Color Palette

| Token | Hex | Usage |
|-------|-----|-------|
| Row highlight | `#fff9ea` | `.row-colored` background |
| Table border | `#ccc` | Outer borders, summary bottom |
| Header bg | `#f2f2f2` | `th` background |
| Content bg | `#fafafa` | `.rubric-content` background |
| Cell border | `#eee` | `td` bottom border |
| Green accent | `#4CAF50` | `.ideal-ans` border, `.full-response-box` border |
| Green bg | `#e8f5e9` | `.ideal-ans` and `.full-response-box` background |
| Green text | `#2E7D32` | Model narrative label |
| Text | `#333` | Summary text |
