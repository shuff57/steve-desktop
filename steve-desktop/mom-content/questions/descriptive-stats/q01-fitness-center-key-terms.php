// === NAME - DESCRIPTION: Fitness Center Key Terms - Identify population, sample, parameter, statistic, variable, and data in a study about mean client exercise time ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("string", "string", "string", "string", "string", "string")

// Randomize the business type and the time unit.
$businesses = array("fitness center", "gym", "health club", "recreation center", "wellness studio")
$units = array("weekly", "daily", "monthly")

$business = $businesses[rand(0, count($businesses)-1)]
$unit = $units[rand(0, count($units)-1)]

// Ask for short phrases identifying each key term.
// Correct answers use the `all_words` flag and accept a few equivalent phrasings.
$answer[0] = "all clients,all of the clients,clients of the {$business},every client"
$answer[1] = "the clients surveyed,the clients in the sample,the sampled clients,a group of clients"
$answer[2] = "mean amount of time all clients exercise,the mean time for all clients,the population mean"
$answer[3] = "mean amount of time the sample exercises,the mean time for the sample,the sample mean"
$answer[4] = "amount of time one client exercises,time one client exercises,time a client exercises"
$answer[5] = "recorded times,actual exercise times,values of the variable,individual times"

// Common flags for all string parts: case-insensitive, allow extra words containing the key phrase.
for ($i=0..5) {
  $strflags[$i]['ignore_case'] = 1
  $strflags[$i]['trim_whitespace'] = 1
  $strflags[$i]['all_words'] = 1
  $ansprompt[$i] = ""
}

$answeights = array(.1667, .1667, .1667, .1667, .1667, .1667)

$solutionguide = '
<style>
  .sol-wrap details { width:100%; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; background:#fff; }
  .sol-wrap summary { cursor:pointer; display:block; width:100%; background:#f0f4ff; color:#21242c; padding:0.5em 0.75em; font-weight:700; font-size:15px; border-bottom:1px solid #e5e7eb; list-style:none; }
  .sol-wrap summary::-webkit-details-marker { display:none; }
  .sol-arrow-open { display:none; }
  .sol-wrap details[open] .sol-arrow-closed { display:none; }
  .sol-wrap details[open] .sol-arrow-open { display:inline; }
  .sol-body { padding:0.75em; background:#fafafa; }
  .term-row { margin:0.6em 0; }
  .term-label { font-weight:700; color:#1865f2; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p>The study wants the <b>mean</b> amount of time clients of a ' . $business . ' exercise ' . $unit . '.</p>
      <div class="term-row"><span class="term-label">Population:</span> all clients of the ' . $business . '</div>
      <div class="term-row"><span class="term-label">Sample:</span> the clients whose exercise time is actually recorded</div>
      <div class="term-row"><span class="term-label">Parameter:</span> the mean ' . $unit . ' exercise time for <em>all</em> clients</div>
      <div class="term-row"><span class="term-label">Statistic:</span> the mean ' . $unit . ' exercise time for the clients in the sample</div>
      <div class="term-row"><span class="term-label">Variable:</span> the amount of time one client exercises ' . $unit . '</div>
      <div class="term-row"><span class="term-label">Data:</span> the recorded time values, such as 2 hours, 5 hours, 0 hours</div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A <b>$business</b> is interested in the <b>mean amount of time</b> a client exercises in the center <b>$unit</b>. The manager records the exercise time of a group of clients.</p>
    <p style="margin:12px 0 0 0;">For this study, identify each key term with a short phrase.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p><span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> <b>Population:</b> $answerbox[0]</p>
    <p><span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> <b>Sample:</b> $answerbox[1]</p>
    <p><span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> <b>Parameter:</b> $answerbox[2]</p>
    <p><span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> <b>Statistic:</b> $answerbox[3]</p>
    <p><span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">e.</span> <b>Variable:</b> $answerbox[4]</p>
    <p><span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">f.</span> <b>Data:</b> $answerbox[5]</p>
  </div>
</div>


// === ANSWER ===

$solutionguide
