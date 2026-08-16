// === NAME - DESCRIPTION: Law of Sines (local for Steven Huff) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
$anstypes="calculated,calculated,calculated"
$abstolerance=0.01
$ang1,$ang2=rands(20,70,2) where ($ang1+$ang2 > 90)
$c=rand(5,8)

$ang3=180-$ang1-$ang2

$answer[0]=$ang3

$sinc=sin($ang3*pi/180)
$sina=sin($ang1*pi/180)
$sinb=sin($ang2*pi/180)

$answer[1]=$c*$sinb/$sinc
$answer[2]=$c*$sina/$sinc

// === QUESTION TEXT ===
<table>
  <tr><td style="width:50%">In the triangle shown, 
    <ul>
      <li>`angle A = $ang1` degrees
      <li>`angle B = $ang2` degrees
      <li>length `AB = $c`
    </ul>
    </td><td rowspan=2 align=center>$triangle</td></tr>
  <tr><td>Find the measures of the other sides and angles</td></tr>
</table>

`angle C = `$answerbox[0] degrees

$previewloc[0]

length `AC approx`  $answerbox[1] (Round your answer to three decimal places)

$previewloc[1]

length `BC approx` = $answerbox[2] (Round your answer to three decimal places)

$previewloc[2]

// === ANSWER ===
Since we have two angles and a side, we can use the law of sines.

Since the only side we have is `AB`, we need to use the sine of the angle across from it.

Since the measure of the three angles in a triangle add to `180^(circ)`, we have:
<table>
  <tr><td align=right>`angle A + angle B + angle C`</td><td align=center>`=`</td><td align=left>`180^circ`</td></tr>
  <tr><td align=right>`$ang1^circ + $ang2^circ + angle C`</td><td align=center>`=`</td><td align=left>`180^circ`</td></tr>
  <tr><td align=right>`angle C`</td><td align=center>`=`</td><td align=left>`180^circ - $ang1^circ - $ang2^circ`</td></tr>
  <tr><td align=right>`angle C`</td><td align=center>`=`</td><td align=left>`$answer[0]^circ`</td></tr>
</table>
Now using the law of sines:
<table>
  <tr><td align=right>`(text(Sine of )C)/c`</td><td align=center>`=`</td><td align=left>`(text(Sine of )A)/a`</td><td></td></tr>
  <tr><td align=right>`(text(Sine of ) $answer[0]^circ)/$c`</td><td align=center>`=`</td><td align=left>`(text(Sine of )$ang1^circ)/a`</td></tr>
  <tr><td colspan=4>It's best to solve for `a` first, then substitute in the values of the sines:</td></tr>
  <tr><td align=right>`a (text(Sine of )$answer[0]^circ)`</td><td align=center>`=`</td><td align=left>`$c (text(Sine of )$ang1^circ)`</td></tr>
  <tr><td align=right>`a`</td><td align=center>`=`</td><td align=left>`($c (text(Sine of ) $ang1^circ))/ (text(Sine of )$answer[0]^circ)`</td></tr>
  <tr><td align=right>`a`</td><td align=center>`=`</td><td align=left>`($c ($sina))/ ($sinc)`</td></tr>
  <tr><td align=right>`a`</td><td align=center>`approx`</td><td align=left>`$answer[2]`</td></tr>
</table>

This will be the length of the side across from angle `A`:  this is side `BC`.

Similarly:

<table>
  <tr><td align=right>`(text(Sine of )C)/c`</td><td align=center>`=`</td><td align=left>`(text(Sine of )B)/b`</td><td></td></tr>
  <tr><td align=right>`(text(Sine of ) $answer[0]^circ)/$c`</td><td align=center>`=`</td><td align=left>`(text(Sine of )$ang2^circ)/b`</td></tr>
  <tr><td align=right>`b (text(Sine of )$answer[0]^circ)`</td><td align=center>`=`</td><td align=left>`$c (text(Sine of )$ang2^circ)`</td></tr>
  <tr><td align=right>`b`</td><td align=center>`=`</td><td align=left>`($c (text(Sine of ) $ang2^circ))/ (text(Sine of )$answer[0]^circ)`</td></tr>
  <tr><td align=right>`b`</td><td align=center>`=`</td><td align=left>`($c ($sinb))/ ($sinc)`</td></tr>
  <tr><td align=right>`a`</td><td align=center>`approx`</td><td align=left>`$answer[1]`</td></tr>
</table>

This will be the length of the side across from angle `B`:  this is side `AC`.
