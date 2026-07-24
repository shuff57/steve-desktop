// === NAME - DESCRIPTION: Identify Conditioning Event - Pick the conditioning event from a worded conditional probability statement ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices")

// Three scenarios. In each, the conditioning event is the "given" clause (the population the
// statement restricts to). Distractors are the event of interest, the negation, and the percentage.
$prompts = array(
  "A study reports that of the students who study at least 5 hours per week, 80% pass the final exam.",
  "A medical screening study reports that of the patients who tested positive for the disease, 95% had a fever.",
  "A retail study reports that of the customers who used a coupon, 60% also made an additional purchase."
)
$option0 = array("Passing the final exam",                    "Having a fever",                      "Making an additional purchase")
$option1 = array("Studying at least 5 hours per week",        "Testing positive for the disease",    "Using a coupon")
$option2 = array("Failing the final exam",                    "Not having a fever",                  "Not using a coupon")
$option3 = array("The 80% figure itself",                     "The 95% figure itself",               "The 60% figure itself")

$picked = jointrandfrom($prompts, $option0, $option1, $option2, $option3)
$prompt = $picked[0]
$choices[0] = array($picked[1], $picked[2], $picked[3], $picked[4])
$answer[0] = 1   // The "given" clause is always at index 1

$correct_event = $picked[2]

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
      <p>In a conditional probability P(A|B), the <b>conditioning event B</b> is the population we restrict attention to &mdash; the "given that" clause that comes <i>after</i> the word <i>of</i>.</p>
      <p>The statement says "<i>of the people who [B], a percentage [A]</i>." The conditioning event is the clause attached to "of."</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        Conditioning event: <b>'.$correct_event.'</b>
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$prompt</p>
    <p style="margin:0.5em 0 0 0;">If we want to write this percentage as a conditional probability, <b>which event is the conditioning event</b> (the event we are conditioning on)?</p>
    <div style="margin-top:12px;text-align:center;">$answerbox[0]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
