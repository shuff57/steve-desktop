// === NAME - DESCRIPTION: Mutually Exclusive, Independent or Neither - Use P(A), P(B) and P(A and B) to classify three pairs of events ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Built for 3.2. Three pairs of events, each given as P(A), P(B) and P(A and B). One pair is
// mutually exclusive (P(A and B) = 0), one is independent (P(A and B) = P(A) xx P(B)), and one is
// neither (an overlap that is not the product). The three outcomes land on parts a, b and c in a
// random order, so the answer is not always the same letter. The story for each outcome is chosen to
// fit it: the mutually exclusive pair really cannot happen together.
$anstypes = array("choices", "choices", "choices")

$p = rand(0, 5)
$o0 = array(0, 0, 1, 1, 2, 2)
$o1 = array(1, 2, 0, 2, 0, 1)
$o2 = array(2, 1, 2, 0, 1, 0)
$t0 = $o0[$p]
$t1 = $o1[$p]
$t2 = $o2[$p]

$j = rand(0, 2)
$meA = array("The student is a freshman", "The voter votes yes", "The card is a heart")
$meB = array("the student is a senior", "the voter votes no", "the card is a spade")
$k = rand(0, 2)
$inA = array("The student wears glasses", "The person is left-handed", "The household owns a dog")
$inB = array("the student was born in June", "the person was born on a Monday", "the household has a garden")
$m = rand(0, 2)
$neA = array("The student plays a sport", "The driver is under 25", "The customer buys a drink")
$neB = array("the student has a part-time job", "the driver has had an accident", "the customer buys a snack")

$eA = array($meA[$j], $inA[$k], $neA[$m])
$eB = array($meB[$j], $inB[$k], $neB[$m])

$meP = rand(2, 4) / 10
$meQ = rand(2, 4) / 10
$inP = rand(2, 8) / 10
$inQ = rand(2, 8) / 10
$neP = rand(4, 8) / 10
$neQ = rand(4, 8) / 10
$pa = array($meP, $inP, $neP)
$pb = array($meQ, $inQ, $neQ)
$pab = array(0, round($inP * $inQ, 4), round($neP * $neQ + 0.05, 4))
$prod = array(round($meP * $meQ, 4), round($inP * $inQ, 4), round($neP * $neQ, 4))

$eA0 = $eA[$t0]
$eB0 = $eB[$t0]
$pA0 = $pa[$t0]
$pB0 = $pb[$t0]
$pAB0 = $pab[$t0]
$eA1 = $eA[$t1]
$eB1 = $eB[$t1]
$pA1 = $pa[$t1]
$pB1 = $pb[$t1]
$pAB1 = $pab[$t1]
$eA2 = $eA[$t2]
$eB2 = $eB[$t2]
$pA2 = $pa[$t2]
$pB2 = $pb[$t2]
$pAB2 = $pab[$t2]
$prod0 = $prod[$t0]
$prod1 = $prod[$t1]
$prod2 = $prod[$t2]

$choices[0] = array("Mutually exclusive", "Independent", "Neither")
$choices[1] = array("Mutually exclusive", "Independent", "Neither")
$choices[2] = array("Mutually exclusive", "Independent", "Neither")
$answer[0] = $t0
$answer[1] = $t1
$answer[2] = $t2
$displayformat[0] = "select"
$displayformat[1] = "select"
$displayformat[2] = "select"

$verdict = array("mutually exclusive", "independent", "neither")
$why = array("P(A and B) = 0, so they cannot happen together.", "P(A and B) equals P(A) xx P(B).", "P(A and B) is not 0, and it is not P(A) xx P(B).")
$v0 = $verdict[$t0]
$v1 = $verdict[$t1]
$v2 = $verdict[$t2]
$w0 = $why[$t0]
$w1 = $why[$t1]
$w2 = $why[$t2]

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
      <p><span class="term-label">Two tests.</span> Mutually exclusive: `P(A and B) = 0`. Independent: `P(A and B) = P(A) xx P(B)`. Check the first, then work out the product for the second.</p>
      <p><span class="term-label">(a)</span> The product is `' . $pA0 . ' xx ' . $pB0 . ' = ' . $prod0 . '` and `P(A and B) = ' . $pAB0 . '`. The pair is ' . $v0 . ': ' . $w0 . '</p>
      <p><span class="term-label">(b)</span> The product is `' . $pA1 . ' xx ' . $pB1 . ' = ' . $prod1 . '` and `P(A and B) = ' . $pAB1 . '`. The pair is ' . $v1 . ': ' . $w1 . '</p>
      <p><span class="term-label">(c)</span> The product is `' . $pA2 . ' xx ' . $pB2 . ' = ' . $prod2 . '` and `P(A and B) = ' . $pAB2 . '`. The pair is ' . $v2 . ': ' . $w2 . '</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Each part gives two events A and B and their probabilities. Decide whether the events are mutually exclusive, independent, or neither.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> A: $eA0. B: $eB0.<br>P(A) = $pA0, P(B) = $pB0, P(A and B) = $pAB0.<br><br>$answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> A: $eA1. B: $eB1.<br>P(A) = $pA1, P(B) = $pB1, P(A and B) = $pAB1.<br><br>$answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> A: $eA2. B: $eB2.<br>P(A) = $pA2, P(B) = $pB2, P(A and B) = $pAB2.<br><br>$answerbox[2]
  </div>
</div>

// === ANSWER ===

$sol
