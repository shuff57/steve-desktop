// === NAME - DESCRIPTION: Identify the Population from a Study Description - separate the whole group a study is about from the subset actually interviewed and from the variable being measured ===
// === SET QUESTION TYPE TO: choices ===

// === COMMON CONTROL ===

$ci = rand(0, 3)
$cities = array("San Antonio, Texas", "Boise, Idaho", "Akron, Ohio", "Modesto, California")
$places = array("park", "library branch", "community pool", "walking trail")
$city = $cities[$ci]
$place = $places[$ci]

$step = rand(5, 12)
$first = rand(1, 4)

$questions = array(
  "all the residents in the neighborhood around the " . $place . " in " . $city,
  "the residents of every " . $step . "th house who were actually interviewed",
  "the age, number of visits per week, and duration of each visit",
  "everyone who lives in " . $city
)
$answer = 0

$solutionguide = '
<div style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <p><b>Step 1 &mdash; Ask who the study wanted to learn about.</b> The study is about the people who live around the ' . $place . ' and use it &mdash; not just the ones who happened to be interviewed.</p>
  <p><b>Step 2 &mdash; Separate population from sample.</b> The residents of every ' . $step . 'th house are the <b>sample</b>. The whole group they were drawn from is the <b>population</b>.</p>
  <p><b>Why the others are wrong.</b> The interviewed residents are the sample, not the population. The age, visits and duration are the <i>variables</i> being measured &mdash; what was recorded, not who was studied. And everyone in ' . $city . ' is far too wide: the study never intended to describe residents on the other side of the city who never go near the ' . $place . '.</p>
  <p><b>Answer:</b> all the residents in the neighborhood around the ' . $place . ' in ' . $city . '.</p>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <p style="margin:0;">A study was done to determine the age, number of times per week, and the duration (amount of time) of residents using the $place in $city. The first house in the neighborhood around the $place was selected randomly, and then the resident of every <b>$step</b>th house in the neighborhood was interviewed.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    The <b>population</b> is _______________.
  </div>
</div>

// === ANSWER ===

$solutionguide
