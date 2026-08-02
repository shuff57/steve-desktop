# Testing & Feedback Macros

## Conditional Test Macros

### `getstuans($stuanswers,$thisq,[part_number])`
Retrieves student answer value. Recommended over direct array access as it handles undefined values.
- **Returns**: mixed

### `stuansready($stuanswers,$thisq,array_of_parts,[anstypes,answerformat])`
Checks if student has answered specified parts. Use null for single-part. Prefix part with tilde to allow blank. `'checknumeric'` for answerformat checks numeric types.
- **Returns**: boolean

### `comparenumbers(a,b,[tol])`
Compares numbers/expressions for equivalence. tol defaults to .001 relative. Prefix `'|'` for absolute.
- **Returns**: boolean

### `comparenumberswithunits(a,b,[tol])`
Compares numbers-with-units. Format: `[number]*[unit]^[power]...` `'per'` for division, `'squared'`/`'cubed'` supported.
- **Returns**: boolean

### `comparentuples(a,b,[tol],[option])`
Compares ntuples/calcntuples. tol can be array for per-element. option `'ignoreparens'` to ignore bracket type.
- **Returns**: boolean

### `comparefunctions(a,b,[vars,tol,domain])`
Compares functions for algebraic equivalence. vars defaults to `'x'`. domain: `'xmin,xmax'` or `'xmin,xmax,integers'`.
- **Returns**: boolean

### `comparesameform(a,b,[vars])`
Compares expressions for same form. Allows commutation, mult by 1, implicit mult, extra parens. vars defaults to `'x'`.
- **Returns**: boolean

### `comparelogic(a,b,vars)`
Compares logical statements. Uses `^^` for and, `vv` for or, `~` for not, `->` or `=>` for conditional, `<->` or `<=>` for biconditional.
- **Returns**: boolean

### `isset($var)`
Checks if variable is defined.
- **Returns**: boolean

### `is_numeric(str)`
Checks if string represents integer or decimal number.
- **Returns**: boolean

### `are_numeric(v1,v2,...) or are_numeric(array)`
Checks if all arguments are numeric.
- **Returns**: boolean

### `is_nan(val)`
Checks if value is NAN. Only works on calculated values, not string expressions.
- **Returns**: boolean

### `scorestring($answer,$showanswer,words,$stuanswers,$thisq,[partn,highlight])`
Checks if words appear in student answer. Redefines `$answer` if correct. Set highlight=false to disable.
- **Returns**: array($answer,$showanswer)
- **Example**: `$answer,$showanswer = scorestring($answer,"",$words,$stuanswers,$thisq)`

### `checkanswerformat(string,answerformat)`
Checks if numerical expression string meets the answerformat.
- **Returns**: boolean

### `getsigfigs(value,[expected_sigfigs])`
Returns number of sigfigs. Include expected to resolve ambiguity on numbers like 1200.
- **Returns**: integer

## Feedback Macros

### `getfeedbackbasic(correct_msg,incorrect_msg,$thisq,[partnum])`
Feedback based on correct/incorrect. Give array of part-numbers to require all correct for correct message.
- **Returns**: string

### `getfeedbacktxt(stuans,feedbacktxt,ans)`
Feedback on multiple choice. feedbacktxt array corresponds to `$questions` order.
- **Returns**: string

### `getfeedbacktxtessay(stuans,feedbacktxt)`
Feedback on essay. Returns feedbacktxt once student has entered any response.
- **Returns**: string

### `getfeedbacktxtnumber(stuans,partialcredit,feedbacktxt,defaultfeedback,[tol])`
Feedback on number questions. partialcredit: `array(number,score,...)`. feedbacktxt array corresponds.
- **Returns**: string

### `getfeedbacktxtcalculated(stuans,stuansval,partialcredit,feedbacktxt,defaultfeedback,[answerformat,requiretimes,tol])`
Feedback on calculated questions. answerformat/requiretimes can be arrays for per-expression checking.
- **Returns**: string

### `getfeedbacktxtnumfunc(stuans,partialcredit,feedbacktxt,defaultfeedback,[vars,requiretimes,tol,domain])`
Feedback on algebraic expression questions. vars defaults to `'x'`. domain defaults to `'-10,10'`.
- **Returns**: string
