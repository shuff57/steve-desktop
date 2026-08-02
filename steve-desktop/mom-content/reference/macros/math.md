# Math Macros

## Trigonometry

### `sin(t)`
Sine function.
- **Returns**: number

### `cos(t)`
Cosine function.
- **Returns**: number

### `tan(t)`
Tangent function.
- **Returns**: number

### `sec(t)`
Secant function.
- **Returns**: number

### `csc(t)`
Cosecant function.
- **Returns**: number

### `cot(t)`
Cotangent function.
- **Returns**: number

### `arcsin(v)`
Inverse sine.
- **Returns**: number

### `arccos(v)`
Inverse cosine.
- **Returns**: number

### `arctan(v)`
Inverse tangent.
- **Returns**: number

### `atan2(y,x)`
Two-argument inverse tangent.
- **Returns**: number

## Basic Math

### `abs(v)`
Absolute value.
- **Returns**: number

### `sqrt(t)`
Square root.
- **Returns**: number

### `root(n)(t)`
Nth root.
- **Returns**: number

### `gcd(a,b,[c,d,...])`
Greatest common divisor.
- **Returns**: integer

### `lcm(a,b,[c,d,...])`
Least common multiple.
- **Returns**: integer

### `sign(a,[option])`
Returns sign as 1 or -1. option=true for `'+'`/`'-'` string. option=`'onlyneg'` for `'-'` or `''`.
- **Returns**: number or string

### `sgn(a)`
Returns sign as -1, 0, or 1.
- **Returns**: integer

## Base Conversion

### `hexdec(a)`
Converts hexadecimal string to decimal number.
- **Returns**: number

### `dechex(a)`
Converts decimal number to hexadecimal string.
- **Returns**: string

## Special

### `v!`
Factorial. Usage: `$a = $b!`
- **Returns**: integer

### `evalfunc(func,vars,val1,val2,...,[falseonerror])`
Evaluates function with given variable values.
- **Returns**: number or false
- **Example**: `evalfunc("x^2*y","x,y",2,3)`

### `evalnumstr(expr,[complex])`
Evaluates string representation of numerical expression to decimal. Set complex=true for complex number expressions.
- **Returns**: number

## Rounding

### `round(n,d)`
Round number n to d decimal places.
- **Returns**: number

### `roundsigfig(n,s)`
Round number n to s significant digits. For calculations; use `prettysigfig` for display.
- **Returns**: number

### `floor(n)`
Integer below given number.
- **Returns**: integer

### `ceil(n)`
Integer above given number.
- **Returns**: integer

## Min / Max / Modulus

### `min(a,b,c,...) or min(array)`
Minimum of passed values or array.
- **Returns**: number

### `max(a,b,c,...) or max(array)`
Maximum of passed values or array.
- **Returns**: number

### `mod(p,n)`
Modulus of integers. Gives positive results for negative numbers.
- **Returns**: integer

### `fmod(p,n)`
Modulus of decimal values. May give negative results from negative inputs.
- **Returns**: number

### `a % b`
Modulus of integers (remainder). `5%2 = 1`.
- **Returns**: integer
