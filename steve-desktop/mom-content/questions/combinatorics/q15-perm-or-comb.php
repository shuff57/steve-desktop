// === NAME - DESCRIPTION: Permutation or Combination - Identify whether order matters in four scenarios ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices", "choices", "choices", "choices")

// Each part has its own pool. 0 = Permutation, 1 = Combination. Each pool is order-balanced.
$qa_prompts = array("Choosing the gold, silver, and bronze medalists from a race of 12 runners",
                    "Setting a 4-digit PIN where digit order matters and digits may repeat",
                    "Awarding 1st, 2nd, and 3rd place ribbons in a science fair with 20 entries",
                    "Selecting a 5-person committee from a club of 15 members",
                    "Picking 6 numbers for a lottery ticket where order does not matter",
                    "Choosing a 4-card poker hand from a 52-card deck <span style=\"font-size:20px; letter-spacing:3px; vertical-align:middle;\">&#9824;&#9829;&#9830;&#9827;</span>")
$qa_correct = array(0, 0, 0, 1, 1, 1)

$qb_prompts = array("Seating 5 friends in a row for a photo",
                    "Picking 3 toppings for a pizza from a list of 8 options",
                    "Arranging 6 books on a shelf in some left-to-right order",
                    "Selecting 2 students from a class to attend a conference",
                    "Forming a 'word' (any letter order counts as different) from the letters of CHAIR",
                    "Choosing which 4 of 10 charities to donate to")
$qb_correct = array(0, 1, 0, 1, 0, 1)

$qc_prompts = array("Electing a president, vice-president, and treasurer from 9 candidates",
                    "Choosing 3 books to read from a list of 12",
                    "Arranging 5 paintings in a row on a wall",
                    "Selecting 7 players for a starting roster from a team of 14",
                    "Generating a 5-letter password from the alphabet (no repeats)",
                    "Picking 2 desserts from a menu of 9 options")
$qc_correct = array(0, 1, 0, 1, 0, 1)

$qd_prompts = array("Listing the top 4 finishers (in order) from a 10-runner race",
                    "Choosing 5 students at random for a focus group from 20 volunteers",
                    "Forming a 3-letter sequence (with no repeats) from the letters of NUMBER",
                    "Picking 3 of 8 colors to use in a logo, with no order",
                    "Assigning 3 distinct prizes (laptop, tablet, phone) to 3 of 12 contestants",
                    "Choosing 4 toppings for a frozen yogurt from 9 options")
$qd_correct = array(0, 1, 0, 1, 0, 1)

$picked_a = jointrandfrom($qa_prompts, $qa_correct)
$picked_b = jointrandfrom($qb_prompts, $qb_correct)
$picked_c = jointrandfrom($qc_prompts, $qc_correct)
$picked_d = jointrandfrom($qd_prompts, $qd_correct)
$prompt_a = $picked_a[0]; $answer[0] = $picked_a[1]
$prompt_b = $picked_b[0]; $answer[1] = $picked_b[1]
$prompt_c = $picked_c[0]; $answer[2] = $picked_c[1]
$prompt_d = $picked_d[0]; $answer[3] = $picked_d[1]

$questions[0] = array("Permutation", "Combination")
$questions[1] = array("Permutation", "Combination")
$questions[2] = array("Permutation", "Combination")
$questions[3] = array("Permutation", "Combination")
$displayformat[0] = "select"
$displayformat[1] = "select"
$displayformat[2] = "select"
$displayformat[3] = "select"
$noshuffle[0] = "all"
$noshuffle[1] = "all"
$noshuffle[2] = "all"
$noshuffle[3] = "all"

// Pre-render the answer labels (0 -> "Permutation", 1 -> "Combination") for the solution guide.
$labels = array("Permutation", "Combination")
$label_a = $labels[$answer[0]]
$label_b = $labels[$answer[1]]
$label_c = $labels[$answer[2]]
$label_d = $labels[$answer[3]]

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
      <p>Ask <i>"if I swap two of the chosen items, do I have the same outcome or a different one?"</i></p>
      <p style="margin:6px 0;"><b>Same</b> &rarr; combination &nbsp;|&nbsp; <b>different</b> &rarr; permutation.</p>
      <p><b>(a)</b> '.$label_a.'</p>
      <p><b>(b)</b> '.$label_b.'</p>
      <p><b>(c)</b> '.$label_c.'</p>
      <p><b>(d)</b> '.$label_d.'</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">For each scenario, decide whether the count is a <b>permutation</b> (order matters) or a <b>combination</b> (order does not matter).</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> $prompt_a
    <div style="margin-top:12px;text-align:center;">$answerbox[0]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> $prompt_b
    <div style="margin-top:12px;text-align:center;">$answerbox[1]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> $prompt_c
    <div style="margin-top:12px;text-align:center;">$answerbox[2]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> $prompt_d
    <div style="margin-top:12px;text-align:center;">$answerbox[3]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
