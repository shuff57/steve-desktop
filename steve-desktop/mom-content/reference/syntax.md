# IMathAS Syntax Reference

## Variables

- prefix: `$`
- assignment_forms:
  - `$var = number (e.g. $a = 3)`
  - `$var = calculation (e.g. $a = 3*$b*$c)`
  - `$var = function (e.g. $a = showplot("sin(x)"))`
  - `$var = randomizer (e.g. $a = rand(-5,5))`

## Types

- number: `$a = 15 or $a = 3^2`
- array: `$a = array(6,8,10) — zero-indexed, $a[0] = 6. Shorthand: $a = [2,3,4,5]`
- string: `$a = "hi there" — double quotes interpolate variables, single quotes do not`
- boolean: `$a = true`

## String Interpolation

- desc: `Double-quoted strings interpolate variables. Single-quoted strings do not.`
- example: `$a = 3; $b = 5; $str = "What is $a/$b" results in "What is 3/5"`
- warning: `Strings are interpolated literally: $a = -4; $b = "$a^2" gives "-4^2". Use explicit parentheses.`

## String Concatenation

- operator: `.`
- example: `$both = $a . $b`

## Line Continuation

- ampersand: `Put & at end of line to continue on next line`
- double_ampersand: `Put && at end of line to continue and insert <br/>`

## Comments

- `Start with // — best on own line, can also be placed after code`

## Array Assignment

- multi_var: `$a,$b = diffrands(-5,5,2) — each takes a single value`
- single_array: `$ar = diffrands(-5,5,2) — elements via $ar[0], $ar[1]`
- literal: `$ar = array(2,3,4,5) or $ar = array("red","green","blue")`
- shorthand: `$ar = [2,3,4,5]`
- notes: `Use parentheses in calculations: $new = ($ar[0])^2. Use curly brackets in strings: "there were {$ar[0]} people"`

## Conditionals

### where

- desc: `Repeats assignment if condition not met. Almost exclusively used with array randomizers.`
- example: `$a,$b = diffrands(-5,5,2) where ($a+$b!=0)`
- limit: `System gives up after 200 tries. Condition should have at least 10% probability.`
- else: `$a = rand(1,100) where (gcd($a,$b)==1) else ($a = 7)`

### if

- desc: `Makes an assignment conditional`
- example: `$b = "sin(x)" if ($a==0)`
- block_form: `if ($a==0) { $b = 1; $c = 2 }`
- postfix_block: `{ $b = 1; $c = 2 } if ($a==0)`
- elseif_else: `if ($a==0) { ... } elseif ($a==2) { ... } else { ... }`

### comparison_operators

- `== Equal to`
- `!= Not equal to`
- `> Greater than`
- `< Less than`
- `>= Greater than or equal to`
- `<= Less than or equal to`
- `isset($v) Whether $v is defined`

### compound

- `|| for "or", && for "and"`

### in_question_text

- desc: `Wrap text in [if variable==value][/if]. Variable name without $ prefix. Value without quotes.`
- example: `[if graphdispmode==0]text here[/if]`

## Loops

### for

- sig: `for ($i=a..b) { action }`
- desc: `a and b are whole numbers, variables, or simple expressions`
- example: `for ($i=1..5) { $f = $f + $i }`

### foreach

- sig: `foreach ($arr as $k=>$v) { action }`
- desc: `For associative arrays with non-numeric or non-consecutive keys`
- example: `$arr = ['red' => 3, 'green' => 5]; for ($arr as $color=>$num) { ... }`

### control_flow

- break: `Breaks out of the loop`
- continue: `Moves to next iteration without executing further code in the block`

### notes

- `Conditions after statement work INSIDE for loop but not outside without explicit blocking`
- `for ($i=1..5) {$a = $a+$i if ($i>2)} — WORKS`
- `for ($i=1..5) {$a = $a+$i} if ($a>2) — DOES NOT WORK`
- `{for ($i=1..5) {$a = $a+$i}} if ($a>2) — WORKS`
