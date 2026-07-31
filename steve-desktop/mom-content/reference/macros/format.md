# Format Macros

## String Cleanup

### `makepretty(string or array)`
Changes double add/subtract signs to a single sign.
- **Returns**: string/array

### `makeprettydisp(string or array)`
Does `makepretty` and backquotes the string for math display.
- **Returns**: string/array

### `polymakepretty(string or array)`
Like `makepretty` but for polynomials. Cleans up `0*`, `1*`, `^1`, `^0`. Can do weird things for non-polynomials.
- **Returns**: string/array

### `polymakeprettydisp(string or array)`
`polymakepretty` + backquotes for math display.
- **Returns**: string/array

### `makexpretty(string)`
(deprecated - use makexxpretty) Like `makepretty`, X-tra version — cleans up `1*`, `0*`, etc.
- **Returns**: string

### `makexprettydisp(string)`
(deprecated) `makexpretty` + backquotes.
- **Returns**: string

### `makexxpretty(string)`
X-tra `makepretty` — cleans up `1*`, `0*`, `x^1`, `x^0`, etc. Cannot handle `|x|` notation; use `abs(x)`.
- **Returns**: string

### `makexxprettydisp(string)`
`makexxpretty` + backquotes for math display.
- **Returns**: string

### `makepretynegative(string)`
Puts parens around negatives. `3--4` to `3-(-4)`, `3+-4` to `3+(-4)`, but `3-+4` to `3-4`.
- **Returns**: string

## Number-to-Words

### `numtowords(number,[addth,numwithTh,addcommas])`
Creates string of number in words.
- **Returns**: string
- **Examples**:
  - `numtowords(1203)` → `"one thousand two hundred three"`
  - `numtowords(1203,true)` → `"one thousand two hundred third"`
  - `numtowords(1203,false,true)` → `"1023rd"`
  - `numtowords(1203,false,false,true)` → `"one thousand, two hundred three"`

### `fractowords(numerator,denominator,[options])`
Creates string of fraction in words. Options: `'mixed'`, `'literal'`, `'over'`, `'by'`, `'hyphen'`. Fractions reduced by default.
- **Returns**: string
- **Examples**:
  - `fractowords(5,-2)` → `"negative five halves"`
  - `fractowords(6,3)` → `"two"`
  - `fractowords(2,3,'hyphen')` → `"two-thirds"`
  - `fractowords(-7,4,'mixed')` → `"negative one and three fourths"`

### `numtoroman(number,[uppercase])`
Converts number 0.5-3999.5 to roman numeral. Defaults uppercase; set false for lowercase.
- **Returns**: string

## Number Formatting

### `prettyint(number)`
Adds commas in thousands places. `prettyint(1234)` → `"1,234"`. Result is string, not for calculations.
- **Returns**: string

### `prettyreal(number,decimals,[comma])`
Adds commas and fixed decimals. `prettyreal(1234.567,2)` → `"1,234.57"`. Set comma to `""` to omit.
- **Returns**: string

### `prettyreal_instring(string,decimals,[comma])`
Formats any number in the string using `prettyreal`.
- **Returns**: string

### `round_instring(string,[decimals])`
Rounds any number in the string to specified decimal places.
- **Returns**: string

### `prettysmallnumber(number,[space])`
Prevents very small numbers from showing as scientific notation. space=true for grouped display.
- **Returns**: string

### `prettysigfig(number,sigfigs,[comma,choptrailingzeros,scinot,sigfigbar])`
Rounds to sigfigs significant figures. For display only; use `roundsigfig` for calculations.
- **Returns**: string

### `prettysigfig_instring(string,sigfigs,[comma,choptrailingzeros,scinot,sigfigbar])`
Rounds any number in string to sigfigs significant figures.
- **Returns**: string

### `makescinot(number,[decimals,format])`
Converts number to scientific notation. format: `'*'` or `'E'` as alternative to default cross.
- **Returns**: string

### `prettytime(value,informat,outformat)`
Creates nice time representation. informat: `'h'`, `'m'`, `'s'`. outformat: combo of those, `'clock'` (3:42pm), or `'sclock'` (3:42:15pm).
- **Returns**: string

## Fraction Display

### `dispreducedfraction(numerator,denominator,[doubleslash,variable])`
Returns display form of fraction reduced to lowest terms. Set doubleslash for `'3//4'` form.
- **Returns**: string

### `makereducedfraction(numerator,denominator,[doubleslash,variable])`
Same as `dispreducedfraction` but not in display form. Alt: `makereducedfraction(num,den,'parts')` returns array of reduced num/den.
- **Returns**: string or array

### `decimaltofraction(decimal,[format,maxden])`
Converts decimal to fraction. format=`'mixednumber'` for mixed number. maxden default 5000.
- **Returns**: string

## Miscellaneous

### `htmldisp(string,[variables])`
Uses HTML instead of math typesetter for simple exponents/subscripts and italicized variables.
- **Returns**: string

### `formatcomplex(real,imag)`
Creates a string like `'2+i'` from real and imaginary parts.
- **Returns**: string

### `rawurlencode(string)`
Encodes a string for use in a URL query string parameter.
- **Returns**: string
