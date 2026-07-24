// === NAME - DESCRIPTION: Modeling with Linear Functions - Real-world word problem: slope, y-intercept, and prediction ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("stats")

$anstypes = array("number", "number", "number")

/* ---------- 1. Context selection ---------- */
// 3 contexts: 0 = gym membership, 1 = taxi fare, 2 = water tank
$ctx = rand(0, 2)

// --- Gym membership: total cost = sign_up + monthly * months ---
// rate in $/month, initial = sign-up fee, input = # months to predict
$gym_rates   = array(25, 30, 35, 40)
$gym_inits   = array(50, 60, 75, 80)
$gym_inputs  = array(6,  8,  10, 12)

$gym_pick = jointrandfrom($gym_rates, $gym_inits, $gym_inputs)
$gym_rate  = $gym_pick[0]
$gym_init  = $gym_pick[1]
$gym_input = $gym_pick[2]

// --- Taxi fare: total = base + rate_per_mile * miles ---
$taxi_rates  = array(2, 3, 4)
$taxi_inits  = array(3, 4, 5)
$taxi_inputs = array(7, 8, 10)

$taxi_pick  = jointrandfrom($taxi_rates, $taxi_inits, $taxi_inputs)
$taxi_rate  = $taxi_pick[0]
$taxi_init  = $taxi_pick[1]
$taxi_input = $taxi_pick[2]

// --- Water tank: level = start - drain_rate * hours ---
// drain_rate is positive; y decreases over time
$tank_rates  = array(50, 60, 75, 80)
$tank_inits  = array(800, 900, 1000, 1200)
$tank_inputs = array(4, 5, 6, 8)

$tank_pick  = jointrandfrom($tank_rates, $tank_inits, $tank_inputs)
$tank_rate  = $tank_pick[0]
$tank_init  = $tank_pick[1]
$tank_input = $tank_pick[2]

/* ---------- 2. Active scenario variables ---------- */
if ($ctx == 0) {
  $rate      = $gym_rate
  $init_val  = $gym_init
  $input_val = $gym_input
  $scenario  = "A gym charges a one-time sign-up fee of $" . $gym_init . " and then $" . $gym_rate . " per month."
  $x_label   = "months"
  $y_label   = "total cost (dollars)"
  $rate_label = "monthly fee (dollars per month)"
  $init_label = "sign-up fee (dollars)"
  $pred_label = $gym_input . " months"
  $rate_sign  = "positive"
}
if ($ctx == 1) {
  $rate      = $taxi_rate
  $init_val  = $taxi_init
  $input_val = $taxi_input
  $scenario  = "A taxi charges a flat base fare of $" . $taxi_init . " plus $" . $taxi_rate . " per mile."
  $x_label   = "miles"
  $y_label   = "total fare (dollars)"
  $rate_label = "cost per mile (dollars per mile)"
  $init_label = "base fare (dollars)"
  $pred_label = $taxi_input . " miles"
  $rate_sign  = "positive"
}
if ($ctx == 2) {
  $rate      = -$tank_rate
  $init_val  = $tank_init
  $input_val = $tank_input
  $scenario  = "A water tank holds " . $tank_init . " gallons at the start. Water drains at a steady rate of " . $tank_rate . " gallons per hour."
  $x_label   = "hours"
  $y_label   = "water level (gallons)"
  $rate_label = "drain rate (gallons per hour, as a negative number)"
  $init_label = "starting water level (gallons)"
  $pred_label = $tank_input . " hours"
  $rate_sign  = "negative"
}

$answer[0] = $rate
$answer[1] = $init_val
$answer[2] = $init_val + $rate * $input_val

$abstolerance[0] = 0.01
$abstolerance[1] = 0.01
$abstolerance[2] = 0.01

/* ---------- 3. Solution guide ---------- */
$ans2_show = $init_val + $rate * $input_val

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
      <p>The linear model is `y = ' . $init_val . ' + (' . $rate . ')x`, where `x` is ' . $x_label . ' and `y` is ' . $y_label . '.</p>
      <p><b>Part a (slope/rate):</b> The rate of change is <b>' . $rate . '</b>. This is the ' . $rate_label . '.</p>
      <p><b>Part b (y-intercept):</b> When `x = 0`, `y = ' . $init_val . '`. This is the ' . $init_label . '.</p>
      <p><b>Part c (prediction):</b> Substitute `x = ' . $input_val . '` into the model:</p>
      <p style="margin-left:1.5em;">`y = ' . $init_val . ' + (' . $rate . ')(' . $input_val . ') = ' . $init_val . ' + (' . ($rate * $input_val) . ') = ' . $ans2_show . '`</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        Rate: <b>' . $rate . '</b> &nbsp;&bull;&nbsp; Initial value: <b>' . $init_val . '</b> &nbsp;&bull;&nbsp; Prediction at ' . $pred_label . ': <b>' . $ans2_show . '</b>
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:16px 20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<p style="margin:0;">$scenario</p>
<p style="margin:6px 0 0 0;">Let `x` = $x_label and `y` = $y_label.</p>
</div>
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:16px 20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the <b>slope</b> (rate of change) of this linear model? <span style="margin-left:8px;">$answerbox[0]</span>
</div>
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:16px 20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What is the <b>y-intercept</b> (initial value when `x = 0`)? <span style="margin-left:8px;">$answerbox[1]</span>
</div>
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:16px 20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Use your model to predict `y` when `x = $input_val` ($pred_label). <span style="margin-left:8px;">$answerbox[2]</span>
</div>
</div>


// === ANSWER ===

$solutionguide
