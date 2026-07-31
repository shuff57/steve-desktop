# Display

## Math Entry

- desc: `MyOpenMath uses ASCIIMath for math entry.`
- rendering: `Wrap expressions in backtick/grave symbol: `x^2``
- calculation_symbols:
  - `* / + -`: `Multiply, divide, add, subtract`
  - `^`: `Powers. 2^3 = 8`
  - `e, pi`: `Standard constants`
  - `%`: `Modulus of integers. 5%2 = 1`
  - `mod(p,n)`: `Modulus with positive results for negative numbers`
  - `fmod(p,n)`: `Modulus of decimal values`
  - `!`: `Factorial`
  - `sqrt`: `Square root`
  - `sin,cos,tan,cot,sinh,cosh`: `Trig functions. Use sin(2) not sin 2`
  - `arcsin,arccos,arctan,arcsinh,arccosh`: `Inverse trig`
  - `sin^-1,cos^-1,tan^-1`: `Alternative inverse trig. sin^-1(0.5)`
  - `ln`: `Natural logarithm base e`
  - `log`: `Common logarithm base 10`
  - `abs`: `Absolute value. abs() for calculations, | for display`
  - `round(n,d)`: `Round n to d decimal places`
  - `roundsigfig(n,s)`: `Round n to s significant digits`
  - `floor,ceil`: `Floor/ceiling: integer below/above`
  - `min,max`: `min/max of passed values or array`
- full_display: `Full ASCIIMath language available for display (includes limited LaTeX subset). See asciimath.org/#syntax.`

## Solver

- desc: `Tool for solving formulas for variables while writing questions.`
- operations: `Solve`, `Differentiate`, `Integrate`, `Plot`
- input_methods:
  - `Select code from Common Control, drag-drop or copy-paste`
  - `Highlight expression and press Ctrl-M`
  - `Highlight expression before clicking Solver`
- sage_syntax:
  - examples:
    - `x,y,a,b,c,d = var('x,y,a,b,c,d'); solve(y==(a*x+b)/(c*x-d), x)`
    - `x = var('x'); diff(3*x^4, x)`
    - `x = var('x'); plot(-x^2+4, (x,-10,10))`
    - `x = var('x'); simplify(5*x+7*(-3*x-4))`
  - tips:
    - `Declare variables using var(). x is default.`
    - `Use == for math equations.`
    - `solve() diff() integral() need independent variable after comma.`
    - `plot() requires range like (x,-10,10).`
- output: `Drag result or click 'Insert in Common Control' / 'Insert as $answer'.`

## Accessible Alternative

- desc: `Specify alternative question ID for students with accessibility settings.`
- warning: `Most likely should NOT be used. Only for rare cases where question cannot be made accessible (jsxgraph, Geogebra). NOT needed for static images, showplot graphs, or standard Drawing type.`
- auto_features:
  - `System auto-generates textual alternatives for showplot, histogram, etc.`
  - `Use replacealttext for custom alt text.`
  - `System provides keyboard alternative for drawing questions.`
- options:
  - visual_alt: `When student has Graph Display set to text alternatives.`
  - mouse_alt: `When student has Drawing Entry set to keyboard/text-based.`
  - visual_or_mouse_alt: `When question has both visual elements and mouse-required elements.`
- accessibility_tips:
  - hide_from_screenreader: `<div aria-hidden=true>...</div>`
  - screenreader_only: `<div class="sr-only">textual description</div>`
