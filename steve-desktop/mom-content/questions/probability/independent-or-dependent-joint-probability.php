// === NAME - DESCRIPTION: Independent or Dependent Joint Probability - Multiply two plain probabilities when the events are independent, use the conditional when they are dependent, then decide mutual exclusivity from the product ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Built for 3.2 and 3.3. Two separate scenarios, each labelled in the prompt with the plain words
// "The events are independent." or "The events are dependent." In the independent one the two plain
// probabilities multiply. In the dependent one the second rate is a conditional ("of those who ..."), and
// that is the number that multiplies P(A). Part d then reads mutual exclusivity off the dependent product.
$anstypes = array("numfunc", "numfunc", "choices", "choices")

$i = rand(0, 3)
$setups1 = array("Two machines in a factory run separately.", "A fair coin is flipped and a spinner is spun.", "Two players each take one free throw.", "Two smoke alarms are in different rooms.")
$evA1 = array("Machine 1 jams", "the coin lands heads", "Player 1 scores", "Alarm 1 works")
$evB1 = array("Machine 2 jams", "the spinner lands on red", "Player 2 scores", "Alarm 2 works")
$joint1 = array("both machines jam", "the coin lands heads and the spinner lands on red", "both players score", "both alarms work")
$setup1 = $setups1[$i]
$eA1 = $evA1[$i]
$eB1 = $evB1[$i]
$j1 = $joint1[$i]
$pA1 = rand(2, 8) / 10
if ($i == 1) {
  $pA1 = 0.5
}
$pB1 = rand(2, 8) / 10
$prod1 = $pA1 * $pB1

$k = rand(0, 3)
$s2 = array("At a school, X% of the students walk to school. Of those who walk, Y% arrive early.", "At a clinic, X% of the patients have a cold. Of those who have a cold, Y% miss work.", "In a choir, X% of the members play piano. Of those who play piano, Y% also play guitar.", "On a highway, X% of the drivers speed. Of those who speed, Y% get a ticket.")
$joint2 = array("a student walks and arrives early", "a patient has a cold and misses work", "a member plays piano and guitar", "a driver speeds and gets a ticket")
$pa2 = 10 * rand(2, 8)
$pb2 = 10 * rand(2, 8)
$setup2 = str_replace("Y", $pb2, str_replace("X", $pa2, $s2[$k]))
$j2 = $joint2[$k]
$dec2 = $pa2 / 100
$decB2 = $pb2 / 100
$prod2 = $dec2 * $decB2

$questions[2] = array(
  "The conditional rate, because the events are dependent",
  "The conditional rate, because the events are independent",
  "P(A) again, because the events are dependent",
  "One minus the conditional rate, because the events are dependent"
)
$questions[3] = array(
  "No, because P(A and B) is not zero",
  "Yes, because P(A and B) is not zero",
  "Yes, because the events are dependent",
  "No, because the events are independent"
)
$answer[0] = $prod1
$answer[1] = $prod2
$answer[2] = 0
$answer[3] = 0

$sol = '
<style>
  .sol-wrap details { width:100%; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; background:#fff; }
  .sol-wrap summary { cursor:pointer; display:block; width:100%; background:#f0f4ff; color:#21242c; padding:0.5em 0.75em; font-weight:700; font-size:15px; border-bottom:1px solid #e5e7eb; list-style:none; }
  .sol-wrap summary::-webkit-details-marker { display:none; }
  .sol-arrow-open { display:none; }
  .sol-wrap details[open] .sol-arrow-closed { display:none; }
  .sol-wrap details[open] .sol-arrow-open { display:inline; }
  .sol-body { padding:0.75em; background:#fafafa; }
  .term-label { font-weight:700; color:#1865f2; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary><span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span> Step-by-Step Solution</summary>
    <div class="sol-body">
      <p><span class="term-label">When to multiply the plain probabilities.</span> If the events are independent, one happening does not change the chance of the other. Then `P(A and B) = P(A) xx P(B)`.</p>
      <p><span class="term-label">(a) Independent.</span> `' . $pA1 . ' xx ' . $pB1 . ' = ' . $prod1 . '`.</p>
      <p><span class="term-label">When to use the conditional.</span> If the events are dependent, the chance of B changes once A has happened. The words "of those who" give that changed chance, which is the conditional probability. Then `P(A and B) = P(A) xx P(B given A)`.</p>
      <p><span class="term-label">(b) Dependent.</span> `P(A) = ' . $dec2 . '` and `P(B given A) = ' . $decB2 . '`, so `' . $dec2 . ' xx ' . $decB2 . ' = ' . $prod2 . '`.</p>
      <p><span class="term-label">(c) Which number.</span> The conditional rate ' . $decB2 . ' multiplies P(A), because the events are dependent.</p>
      <p><span class="term-label">(d) Mutually exclusive.</span> Two events are mutually exclusive when they cannot happen together, which means `P(A and B) = 0`. Here `P(A and B) = ' . $prod2 . '`, which is not zero, so the events are not mutually exclusive.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 6px 0;"><b>First scenario.</b> $setup1 <b>The events are independent.</b> P($eA1) = $pA1 and P($eB1) = $pB1.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Find P($j1). Enter a decimal. $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;"><b>Second scenario.</b> $setup2 <b>The events are dependent.</b> Call the first event A and the second event B.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Find P($j2). Enter a decimal. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Which number do you multiply by P(A) in part b, and why? $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> Using your answer to part b, are the two events mutually exclusive? $answerbox[3]
  </div>
</div>

// === ANSWER ===

$sol
