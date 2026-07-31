# Essay, Draw, and File Upload Question Types

Types: `essay`, `draw`, `fileupload`

---

## `essay` — Free Response / Essay

Free-response answer. **NOT automatically graded.** Multipart name: `essay`.

### Required Variables

None required. The question type itself is the configuration.

### Options / Settings

- `$answerboxsize` — `'rows'` or `'rows,columns'`. Default 5 rows, 50 columns.
- `$displayformat` — `'editor'` for rich text, **`'editornopaste'` for rich text without paste** (recommended for assessments — prevents copy-paste cheating), `'pre'` for preformatted.
- `$scoremethod` — `'takeanything'`, `'takeanythingorblank'`, `'nomanual'` (no credit, no manual grading flag).
- `$readerlabel` — Screenreader label.
- `$answerbox` — In Question Text.
- `$showanswer` — Answer to show.
- `$hidetips` — `true` to hide tips.

### Example

```php
$anstypes = array("essay");
$displayformat[0] = 'editornopaste';
$answerboxsize[0] = "8,60";
```

### Notes

- **Always use `'editornopaste'`** in assessments. `'editor'` allows paste and is a cheating risk.
- Essay questions require manual grading unless `$scoremethod = 'takeanything'`.
- See [`../macros/format.md`](../macros/format.md) for `$displayformat` details.
- For rubric-based grading patterns used in this repo, see the FRQ questions in `questions/frq/`.

---

## `draw` — Drawing / Graphing

Student draws lines, curves, or dots on a coordinate plane. Dots graded right/wrong. Lines graded by deviation. Multipart name: `draw`.

### Required Variables

- `$answers` — String or array of strings describing points/curves:
  - Curves: `'f(x)'` (e.g., `'x^2-3'`)
  - Segments: `'f(x),xmin,xmax'`
  - Dots: `'x,y'` or `'x,y,open'`
  - Prefix `'optional'` for non-required elements.

### Options / Settings

- `$answerformat` — Drawing tool selection:
  - **`twopoint`** (recommended) — Two control points per shape. Default tools: `line`, `parabola`, `absolute value`, `circle`, `dot`. Available tools: `line`, `dashedline`, `lineseg`, `ray`, `parab`, `horizparab`, `halfparab`, `3pointparab`, `cubic`, `abs`, `circle`, `dot`, `opendot`, `sqrt`, `cuberoot`, `trig`, `tan`, `vector`, `exp`, `genexp`, `log`, `genlog`, `rational`, `ellipse`, `hyperbola`.
  - `polygon` — Single polygon. `$answer` as array of points joined with edges.
  - `closedpolygon` — Closed polygon with shaded area. First and last `$answers` entry identical.
  - `inequality` — Linear inequalities. Answers: `'>=3x+4'`, `'<5x+4'`, `'x<=3'`. Add `'parab'` or `'abs'` for other types.
  - `numberline` — Inequalities/points on number line. Use `intervalstodraw` from interval library.
  - `freehand` — Freehand drawing. **NOT auto-graded.**
- `$snaptogrid` — Snap spacing. `1` for integers, `0.5` for half, `'2:4'` for different x/y.
- `$grid` — `'xmin,xmax,ymin,ymax,xscl,yscl,width,height'`. Default `'-5,5,-5,5,1,1,300,300'`. For numberline, set ymin/ymax to 0.
- `$background` — Equation or array for background graph. `'none'` removes axes/grid. Alt-text: add `'alt:description'` element.
- `$partweights` — Array/list of weights per answer element. Default equal.
- `$reltolerance` — Scales grading tolerance. Default `1`. `2` = twice tolerant, `0.5` = half.
- `$abstolerance` — All-or-nothing cutoff. Score < value = 0, otherwise full credit.
- `$scoremethod` — `'takeanything'`, `'direction'` (vectors), `'relativelength'` (vectors), `'ignoreoverlap'` (lines), `'ignoreextradots'` (polygon).
- `$readerlabel` — Screenreader label.
- `$answerbox` — In Question Text.
- `$showanswer` — Defaults to correct answer text.
- `$hidetips` — `true` to hide tips.

#### Twopoint Answer Formats

| Shape | Format |
|-------|--------|
| Function | `'2x+3'` |
| Line segment | `'2x+4,-oo,3'` or `'x=4,-2,3'` for vertical |
| Dashed line | `'dashedline,2/3x+4'` |
| Dot | `'x,y'` or `'x,y,open'` |
| Circle | `'circle,x,y,radius'` |
| Ellipse | `'ellipse,x,y,x_radius,y_radius'` |
| Horizontal hyperbola | `'horizhyperbola,h,k,a,b'` |
| Vertical hyperbola | `'verthyperbola,h,k,a,b'` |
| Horizontal parabola | `'x=equation'` (e.g., `'x=2(y-1)^2-3'`) |
| Half parabola | `'equation,>xvertex'` (e.g., `'(x-1)^2-3,>1'`) |
| Vector | `'vector,x_start,y_start,x_end,y_end'` or `'vector,dx,dy'` |

### Example

```php
$anstypes = array("draw");
$answers = array("2x+1", "3,2", "3,2,open");
$answerformat = "twopoint";
$grid = "-5,5,-5,5,1,1,300,300";
```

### Notes

- See `questions/draw/graph-linear-function.php` for a complete working example.
- Use `gettwopointlinedata()`, `gettwopointdata()`, `gettwopointformulas()` macros to extract drawn data for scoring in `conditional` type.

---

## `fileupload` — File Upload

Student uploads a file. **NOT automatically graded.** Multipart name: `file`.

### Required Variables

None required.

### Allowed Extensions

`.jpg`, `.jpeg`, `.png`, `.gif`, `.webp`, `.heic`, `.tiff`, `.bmp`, `.svg`, `.doc`, `.xls`, `.ppt`, `.docx`, `.xlsx`, `.pptx`, `.rtf`, `.csv`, `.pdf`, `.txt`, `.odt`, `.ods`, `.odp`, `.key`, `.md`, `.epub`, `.tex`, `.zip`, `.rar`, `.7z`, `.gz`, `.mp4`, `.mov`, `.mkv`, `.mp3`, `.m4a`, `.vtt`, `.srt`, `.nb`, `.nbp`, `.mw`, `.mws`, `.m`, `.mat`, `.mlx`, `.omv`, `.sas`, `.sav`, `.r`, `.rda`, `.rds`, `.dta`, `.rdata`, `.rmd`, `.sps`, `.qmd`, `.mpx`, `.mpj`, `.mwx`, `.mtw`, `.jmp`, `.jrn`, `.jrp`, `.dat`, `.json`, `.imas`, `.imscc`, `.ggb`, `.ipynb`, `.pages`, `.numbers`, `.htm`, `.html`, `.js`, `.xml`, `.xhtml`

### Options / Settings

- `$scoremethod` — `'takeanything'` or `'takeanythingorblank'`.
- `$answerformat` — `'images'`, `'canpreview'`, or comma-separated extensions with leading periods (e.g., `'images,.pdf'`).
- `$readerlabel` — Screenreader label.
- `$answerbox` — In Question Text.
- `$showanswer` — Answer to show.
- `$hidetips` — `true` to hide tips.

### Example

```php
$anstypes = array("number", "file");
$answer[0] = 42;
$scoremethod[1] = "takeanything";
$answerformat[1] = "images,.pdf";
```

### Notes

- Typically paired with a graded answer type (e.g., `number` or `essay`) in `multipart` — the file part collects student work.
- `$answerformat[1] = 'images,.pdf'` is the standard pattern used in this repo's matrix questions.
