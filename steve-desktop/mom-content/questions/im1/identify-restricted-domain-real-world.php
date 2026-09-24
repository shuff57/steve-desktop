// === NAME - DESCRIPTION: Identify the correct domain for a real world ticket sales scenario ===
// === SET QUESTION TYPE TO: choices ===

// === COMMON CONTROL ===
$N = randfrom("40,50,60,75,80,100,120")
$N2 = $N + 10
$correct = "t is a whole number with 0 <= t <= $N"
$d1 = "t is any integer, so t can be negative"
$d2 = "t is any real number with 0 <= t <= $N"
$d3 = "t is a whole number with 0 <= t <= $N2"
$questions = array($correct, $d1, $d2, $d3)
$answer = 0
$solutionguide = '
<div style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <p><b>Step 1: decide whether t can be negative.</b> Tickets sold cannot be negative, so t is at least 0. Any description that allows negative values is wrong.</p>
  <p><b>Step 2: decide whether t can be a fraction.</b> You cannot sell part of a ticket, so t must be a whole number. Any description that allows every real number is wrong.</p>
  <p><b>Step 3: find the largest possible value.</b> The auditorium holds at most ' . $N . ' people, so t is at most ' . $N . '. Any description with a larger upper bound is wrong.</p>
  <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
    The correct description is: t is a whole number with 0 &le; t &le; ' . $N . '.
  </div>
</div>'

// === QUESTION TEXT ===
<p>A school is selling tickets to a play. The number of tickets sold is `t`. The auditorium holds at most $N people, so no more than $N tickets can be sold.</p>
<p>Which description gives all the possible values of `t`?</p>
$answerbox

// === ANSWER ===
$solutionguide
