// === NAME - DESCRIPTION: Does a Reward Announced in Advance Bias the Data - decide whether a topic-related incentive known before responding changes who answers and how ===
// === SET QUESTION TYPE TO: choices ===

// === COMMON CONTROL ===

$ci = rand(0, 2)
$stores = array("a video game store", "a coffee shop chain", "a sporting goods store")
$habits = array("hours per week they play video games", "cups of coffee they drink per week", "hours per week they play sports")
$store = $stores[$ci]
$habit = $habits[$ci]
$amt = rand(2, 5) * 5

$questions = array(
  "Yes. A reward tied to the very behaviour being studied can draw in students who do it most, and can push answers upward.",
  "No. A gift card does not change how many hours a student actually spent, so the data are unaffected.",
  "No. Paying respondents always improves data quality by raising the response rate.",
  "Yes, but only because any payment to respondents is a form of coercion."
)
$answer = 0

$solutionguide = '<div style="font-family:-apple-system,BlinkMacSystemFont,Segoe UI,Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <p><b>The problem is the pairing, not the payment.</b> The reward is a gift card to ' . $store . ' &mdash; and the survey asks about ' . $habit . '.</p>
  <p><b>Two distinct effects, both biasing.</b></p>
  <ul>
    <li><b>Who responds.</b> A student who cares about ' . $store . ' has more reason to take part than one who does not. The respondents tilt toward the group whose behaviour is being measured, which is a selection effect.</li>
    <li><b>What they say.</b> Knowing in advance what the reward is signals what the researchers are interested in, which nudges answers in that direction.</li>
  </ul>
  <p><b>"Knew about the award before the study" is the operative phrase.</b> The same $' . $amt . ' card handed out afterwards, unannounced, biases neither who responds nor what they say &mdash; nobody could have adjusted to something they did not know was coming.</p>
  <p><b>Answer:</b> yes, it would affect the data.</p>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    As a way to reward students for participating in the survey about $habit, the researchers gave each student a <b>$$amt gift card to $store</b>. Would this affect the data if students knew about the award <b>before</b> the study?
  </div>
</div>

// === ANSWER ===

$solutionguide
