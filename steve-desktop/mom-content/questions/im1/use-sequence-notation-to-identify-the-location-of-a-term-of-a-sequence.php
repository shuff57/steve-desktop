// === NAME - DESCRIPTION: Use sequence notation to identify the location of a term of a sequence given as a list. (local for Steven Huff) ===
// === SET QUESTION TYPE TO: choices ===

// === COMMON CONTROL ===
$index = rand(2,5)
$ans_loc = $index + 1
$val = rand(2,8) where($val != $ans_loc && $val!=$index)
$a = nonzerodiffrands(-10,20,7) where ($a[$index]==$val)

$choices = array("<span STYLE='font-size: 1.2em'>`a_$val`</span>","<span STYLE='font-size: 1.2em'>`a_$index`</span>","<span STYLE='font-size: 1.2em'>`a_$ans_loc`</span>","<span STYLE='font-size: 1.2em'>`a_1`</span>")

$answer = 2
$displayformat = "2column"

// === QUESTION TEXT ===
Given the sequence <span STYLE="font-size: 1.2em">`a_n`</span> below
<div STYLE="margin:1.2em;">`$a[0],$a[1],$a[2],$a[3],$a[4],$a[5],$a[6]`</div>
which of the following terms has a value of `$val`?
<div STYLE="margin-left:2em;margin-bottom:0.5em;">$answerbox $showanswerloc</div>

// === ANSWER ===
Each term has a unique position (first, second, third, etc.) we label each entry as <span STYLE="font-size: 1.2em">`a_1`, `a_2`, `a_3`</span> and so on.

$pic
