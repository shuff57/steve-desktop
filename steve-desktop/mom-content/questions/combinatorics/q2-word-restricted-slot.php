// === NAME - DESCRIPTION: Word Permutations With a Slot Restriction - Vowels/consonants in fixed positions ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number")

// Four word/restriction scenarios with fully precomputed answers.
$words        = array("PROBLEM", "SECURITY", "PRODUCT", "PLANET")
$restrictions = array("end in a vowel",
                      "end in a consonant",
                      "have consonants in the second and third positions",
                      "have a vowel in the first position")
$ans          = array(1440, 25200, 2400, 240)
$expls        = array("PROBLEM has 7 distinct letters with 2 vowels (O, E). Place 1 of 2 vowels in slot 7, then arrange the remaining 6 letters: `2 \cdot 6! = 1440`.",
                      "SECURITY has 8 distinct letters with 5 consonants (S, C, R, T, Y). Place 1 of 5 consonants in slot 8, then arrange the remaining 7 letters: `5 \cdot 7! = 25200`.",
                      "PRODUCT has 7 distinct letters with 5 consonants (P, R, D, C, T). Fill slot 2 (5 choices), then slot 3 (4 remaining), then arrange the other 5 letters: `5 \cdot 4 \cdot 5! = 2400`.",
                      "PLANET has 6 distinct letters with 2 vowels (A, E). Place 1 of 2 vowels in slot 1, then arrange the remaining 5 letters: `2 \cdot 5! = 240`.")
$picked = jointrandfrom($words, $restrictions, $ans, $expls)
$word = $picked[0]
$restriction = $picked[1]
$answer[0] = $picked[2]
$expl = $picked[3]

$solutionguide = '
<style>
  .sol-wrap details { width:100%; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; background:#fff; }
  .sol-wrap summary { cursor:pointer; display:block; width:100%; background:#f0f4ff; color:#21242c; padding:0.5em 0.75em; font-weight:700; font-size:15px; border-bottom:1px solid #e5e7eb; list-style:none; }
  .sol-wrap summary::-webkit-details-marker { display:none; }
  .sol-arrow-open { display:none; }
  .sol-wrap details[open] .sol-arrow-closed { display:none; }
  .sol-wrap details[open] .sol-arrow-open { display:inline; }
  .sol-body { padding:0.75em; background:#fafafa; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p><b>Handle the restricted slot first, then fill the remaining slots with the multiplication axiom.</b></p>
      <p>'.$expl.'</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        Answer: '.$answer[0].' permutations.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">How many permutations of the letters of the word <b>$word</b> $restriction?</p>
    <div style="margin-top:12px;text-align:center;">$answerbox[0]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
