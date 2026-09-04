// === NAME - DESCRIPTION: Identify Sampling Method - Match a study description to the correct sampling method (simple random, stratified, cluster, systematic, convenience) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices")

// Scenario 0: SRS: names drawn from a hat
// Scenario 1: Stratified: divide students by grade level then random sample from each
// Scenario 2: Cluster: randomly pick 4 entire classrooms; survey everyone in those rooms
// Scenario 3: Systematic: every 10th customer who walks in
// Scenario 4: Convenience: first 50 friends contacted
// Scenario 5: Stratified: split adults by region then random sample within each region
// Scenario 6: Cluster: random pick of 6 voting precincts; survey every voter in those precincts
// Scenario 7: Systematic: every 25th name on the alphabetized list

$ctxs = array(
  "A school office puts every student's name in a bowl and randomly draws 75 names. The 75 students drawn are the sample.",
  "A researcher splits a school's students into four groups by grade level (freshman, sophomore, junior, senior) and then takes a simple random sample of 25 students from each grade.",
  "A national survey randomly selects 4 entire high schools from a list, then attempts to survey every single student in each of the 4 chosen schools.",
  "A store manager surveys every 10th customer who walks through the front door, starting with a randomly chosen customer between 1 and 10.",
  "A student researcher posts a poll link in three group chats with close friends and uses the first 50 responses.",
  "A polling firm divides adults into 5 regions of the country and then takes a simple random sample of 200 adults from each region.",
  "A polling agency randomly picks 6 voting precincts from a city and surveys every registered voter living in those 6 precincts.",
  "A list of 5,000 alumni is alphabetized. Beginning with a randomly chosen name between 1 and 25, every 25th name is selected."
)
$correct = array(0, 1, 2, 3, 4, 1, 2, 3) // index into choices

$why = array(
  "Every member of the population has the same chance of being chosen and the selection is purely random: this is a <b>simple random sample</b>.",
  "The population is split into <b>strata</b> (groups that share a characteristic, like grade level or region) and a random sample is drawn from each: this is <b>stratified sampling</b>.",
  "Whole, pre-existing groups (clusters) are randomly selected, and every member of the chosen clusters is surveyed: this is <b>cluster sampling</b>.",
  "A random start is chosen and then every k-th element is selected at a fixed interval: this is <b>systematic sampling</b>.",
  "Subjects are chosen because they are easy to reach (friends, walk-by, sign-ups): this is <b>convenience sampling</b>."
)

$picked = jointrandfrom($ctxs, $correct)
$ctx       = $picked[0]
$answer[0] = $picked[1]
$wkey = $picked[1]
$wexplain = $why[$wkey]

$choices[0] = array(
  "Simple random sample",
  "Stratified sample",
  "Cluster sample",
  "Systematic sample",
  "Convenience sample"
)
$noshuffle[0] = "all"

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
      <p>' . $wexplain . '</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p>Which sampling method is described?</p>
    $answerbox[0]
  </div>
</div>


// === ANSWER ===

$solutionguide
