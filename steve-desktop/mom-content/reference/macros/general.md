# General Macros

### `ifthen(condition,trueval,falseval)`
Returns trueval if condition is true, falseval otherwise.
- **Returns**: mixed

### `cases(testvalue,comparearray,outputarray,[defaultoutput,tolerance])`
Compares testvalue to comparearray entries; returns corresponding outputarray entry. Tolerance defaults to 0.0015 relative. Prefix `'|'` for absolute.
- **Returns**: mixed
- **Example**: `cases(3,[1,2,3,4],["a","b","c","d"])` returns `"c"`

### `formhoverover(label,tip)`
Creates a hover-over definition or tip.
- **Returns**: string

### `formpopup(label,content,[width,height,style,scrollbars])`
Creates link/button to popup window. Content: URL or HTML. Style: `'link'` or `'button'`.
- **Returns**: string

### `forminlinebutton(label,content,[style,outputstyle])`
Creates link/button that reveals hidden content. outputstyle: `'inline'` or `'block'`.
- **Returns**: string

### `makenumberrequiretimes(array/list)`
Generates `$requiretimes` string from array of numbers. Auto-detects number overlaps.
- **Returns**: string

### `ABarray(start,num)`
Produces array of `[AB#]` strings for answerbox placeholders.
- **Returns**: array
- **Example**: `ABarray(5,2)` → `array("[AB5]","[AB6]")`

### `getntupleparts(string,[expected_components,checknumeric])`
Extracts components of n-tuple from `$stuanswers`/`$stuanswersval`. Returns false if invalid.
- **Returns**: array or false

### `scoremultiorder(stua,answer,swap,type,[weights,options])`
Allows multipart questions to be scored in any order. Works for `number`, `string`, `calculated`, `numfunc`, `complex`, `calccomplex`, `ntuple`, `calcntuple`.
- **Returns**: modified `$answer` (and optionally `$answeights`)
- **Params**:
  - `stua`: `$stuanswers[$thisq]` for numeric, `$stuanswersval[$thisq]` for calculated
  - `swap`: String or array showing swappable indices, e.g. `'0;1'` or `['0;1','2;3']` or `'0,1,2;3,4,5'`
  - `type`: Question part short name like `'number'` or `'calccomplex'`

### `scoreperiodic(answer,stuanswer,variable,[tolerance])`
Helps score periodic answers like `'pi/6+2pik'`. Redefines `$answer` if student's equivalent answer has different base value.
- **Returns**: modified `$answer`
