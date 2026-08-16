// === NAME - DESCRIPTION: Equation of line between two points (point-slope form) (local for Steven Huff) ===
// === SET QUESTION TYPE TO: numfunc ===

// === COMMON CONTROL ===
$variables="x,y"
$answerformat="equation"

$x1,$x2=diffrands(-10,10,2) where ($x1 > 0)
$y1,$y2=diffrands(-10,10,2) where ($y1 < 0)

$dy=$y2-$y1
$dx=$x2-$x1

$slope=makereducedfraction($dy,$dx)

//y = slope (x - x1) +  y1

$ans=makepretty("y -$y1 = $slope (x - $x1)")

$answer=$ans

// === QUESTION TEXT ===
Find the equation of the line between the points `($x1,$y1)` and `($x2, $y2)`.

The equation is $answerbox (Be sure to enter your answer as an equation)

$previewloc

// === ANSWER ===
The equation of the line with slope `m` going through the point `(x_0, y_0)` can be expressed as

`y - y_0 = m(x - x_0)`

To write the equation, we need the slope.

The slope of the line between the points `(x_0, y_0)` and `(x_1,y_1)` is

`m = (y_1 - y_0)/(x_1 - x_0)`

The points are the points `($x1,$y1)` and `($x2, $y2)`, so the slope will be

`($y2 - ($y1))/($x2 - ($x1)) =  $slope`

We can use either point.  If we use `($x1, $y1)`, the equation of the line will be

`$answer`
