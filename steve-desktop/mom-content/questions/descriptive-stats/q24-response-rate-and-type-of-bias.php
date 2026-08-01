// === NAME - DESCRIPTION: Response Rate and Type of Bias - Compute a study's response rate as a percent from the number contacted and the number who replied, then classify the study's main problem as nonresponse bias, voluntary response bias, or neither ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "choices")

// Six study designs, two per verdict, so which of the three answers is correct rotates:
// 0,1 -> nonresponse bias (sampling was random, the replies are not)
// 2,3 -> voluntary response bias (nobody was sampled; respondents chose themselves)
// 4,5 -> neither / very little (random sample, callbacks, very high response rate)
$ci = rand(0, 5)

$biasIdxAll = array(0, 0, 1, 1, 2, 2)
$biasIdx = $biasIdxAll[$ci]

// Sizes and target response rates that fit each design.
$contactLo = array(400, 1500, 8000, 5000, 180, 300)
$contactHi = array(900, 4000, 20000, 12000, 260, 500)
$rateLo = array(14, 6, 2, 1, 93, 92)
$rateHi = array(26, 13, 6, 3, 97, 96)

$contacted = rand($contactLo[$ci], $contactHi[$ci])
$targetRate = rand($rateLo[$ci], $rateHi[$ci])
$responded = round($contacted * $targetRate / 100) + rand(-3, 3)
$missing = $contacted - $responded

// (a) is a calculation, so it is typed in &mdash; offering percents to choose between would
// turn the arithmetic into elimination.
$rate = 100 * $responded / $contacted
$answer[0] = $rate
$abstolerance[0] = 0.051
$showanswer[0] = round($rate, 1)
$rateR = round($rate, 1)

// Wording that goes with each design.
$groupAll = array("alumni", "students", "viewers", "listeners", "households", "registered voters")
$contactVerbAll = array(
  "were mailed the survey",
  "were sent the survey link",
  "were shown the pop-up",
  "heard the invitation",
  "were selected for the sample",
  "were selected for the sample"
)
$respondVerbAll = array(
  "returned it",
  "completed it",
  "submitted a rating",
  "called in",
  "were interviewed",
  "were interviewed"
)
$populationAll = array(
  "all alumni of the department",
  "all students enrolled at the college",
  "everyone who watches the show",
  "all residents of the listening area",
  "all households in the county",
  "all registered voters in the county"
)
$introAll = array(
  "The alumni office at Marlowe College draws a <b>simple random sample of " . $contacted . " alumni</b> from its complete graduate list and mails each one a printed survey about their careers, with a reply envelope. <b>" . $responded . "</b> surveys come back.",
  "A registrar draws a <b>simple random sample of " . $contacted . " enrolled students</b> from the college roster and emails each one a link to a survey about study habits. <b>" . $responded . "</b> students complete it.",
  "A streaming service displays a &ldquo;Rate this episode!&rdquo; pop-up at the end of every episode. It appears to <b>" . $contacted . " viewers</b> who finished the latest episode, and <b>" . $responded . "</b> of them submit a rating. The service reports the average rating as the show&rsquo;s score.",
  "A radio host asks listeners to call the station with their opinion on a proposed county tax. About <b>" . $contacted . " listeners</b> hear the invitation and <b>" . $responded . "</b> of them call in. The host reports the percentage of callers who oppose the tax.",
  "A researcher draws a <b>simple random sample of " . $contacted . " households</b> from county property records and visits each one in person, returning up to <b>three</b> more times to reach anyone who is not home. <b>" . $responded . "</b> households are interviewed.",
  "A county health department draws a <b>simple random sample of " . $contacted . " registered voters</b> and sends interviewers to each address in person, making up to <b>three</b> return visits to reach anyone not found the first time. <b>" . $responded . "</b> voters are interviewed."
)

$groupWord = $groupAll[$ci]
$contactVerb = $contactVerbAll[$ci]
$respondVerb = $respondVerbAll[$ci]
$populationText = $populationAll[$ci]
$introText = $introAll[$ci]

// (b) is a recognition task, so it is multiple choice. The three options are the three
// verdicts the section teaches; the wrong two are the live confusions, not filler.
$choices[1] = array(
  "<b>Nonresponse bias.</b> The sample was selected properly, but the people who answered differ in a meaningful way from the people who did not, so the replies no longer represent the sample that was drawn.",
  "<b>Voluntary response bias.</b> Nobody was selected at all &mdash; the respondents put themselves into the sample by choosing to respond, so the result over-represents whoever felt strongly enough to participate.",
  "<b>Neither, or very little.</b> The sample was selected at random and nearly everyone in it responded, so the responses that came back still represent the sample that was drawn."
)
$answer[1] = $biasIdx

// Design-specific feedback: who ends up over- or under-represented.
$whoAll = array(
  "The <i>sampling</i> was flawless &mdash; " . $contacted . " alumni drawn at random from the full graduate list. What went wrong happened afterwards. Returning a paper survey costs a stamp and an evening, so the " . $responded . " who wrote back skew toward alumni with a strong attachment to the department and time on their hands. Recent graduates, the very busy, and anyone indifferent about the department are <b>under-represented</b>; the loyal and the leisured are <b>over-represented</b>. The " . $missing . " who never replied are not a random slice of the " . $contacted . " &mdash; they are the ones for whom replying was not worth it.",
  "The <i>sampling</i> was flawless &mdash; " . $contacted . " students drawn at random from the roster. But the " . $responded . " who filled the form in are no longer a random sample of students; they are a sample of <i>students who answer college email</i>, which is a different population with different habits. Organized, engaged students who read their mail are <b>over-represented</b>; students who ignore college email &mdash; often the ones whose study habits the survey most wanted to measure &mdash; are <b>under-represented</b> or missing entirely.",
  "Nobody was sampled here. The pop-up appears and each viewer decides whether to tap it, so the " . $responded . " ratings come from whoever felt like rating. Worse, the pop-up only appears to viewers who <i>finished</i> the episode &mdash; anyone who disliked the show enough to stop watching never saw it. Enthusiasts are <b>over-represented</b>, people who quit partway are <b>missing entirely</b>, and the large middle group who thought it was fine simply watched and moved on. The average describes finishers who chose to rate, not viewers.",
  "Nobody was selected here. The " . $responded . " callers put themselves into the sample, and people who are angry about a tax pick up the phone at far higher rates than people who mildly favour it or do not care. Listeners with intense opinions &mdash; and opponents in particular &mdash; are <b>over-represented</b>; the indifferent majority is <b>under-represented</b>. The percentage measures intensity of feeling among listeners motivated to call, not opinion among listeners, and certainly not opinion among residents.",
  "This is what the defence against nonresponse actually looks like: a random sample, then <i>persistence</i>. Going back up to three times reaches the people who happened to be out the first time &mdash; exactly the households a single visit would have quietly dropped. Some nonresponse remains in principle, since the " . $missing . " never reached are probably the rarely-home, but with " . $responded . " of " . $contacted . " there is not enough missing for it to move the result much.",
  "This is what the defence against nonresponse actually looks like: a random sample, then <i>persistence</i>. Return visits reach the voters who were out the first time &mdash; exactly the people a single attempt would have quietly dropped. Some nonresponse remains in principle, since the " . $missing . " never reached are probably the hardest to find at home, but with " . $responded . " of " . $contacted . " there is not enough missing for it to move the result much."
)
$whoText = $whoAll[$ci]

// Why the two rejected options are wrong, for this design.
$whyNotAll = array(
  "It is not <b>voluntary response bias</b>, because the alumni did not put themselves forward &mdash; they were chosen at random and then invited. Self-selection entered only at the reply stage, which is what makes this the milder-sounding but still fatal cousin. And it is not <b>neither</b>: at " . $rateR . "%, more than three quarters of the drawn sample is missing.",
  "It is not <b>voluntary response bias</b>, because the students did not put themselves forward &mdash; they were drawn at random and then contacted. Self-selection entered only at the reply stage. And it is not <b>neither</b>: at " . $rateR . "%, the overwhelming majority of the sample that was drawn never answered.",
  "It is not <b>nonresponse bias</b> in the strict sense, because there was never a sample to fail to respond to &mdash; no one was selected. Voluntary response is nonresponse bias with the dial turned all the way up: the entire sample is self-chosen. And it is certainly not <b>neither</b>: a " . $rateR . "% response rate on a self-selected pool is the weakest evidence on this list.",
  "It is not <b>nonresponse bias</b> in the strict sense, because no sample was ever drawn &mdash; there was nobody selected who could fail to answer. Voluntary response is nonresponse bias with the dial turned all the way up: the whole sample chose itself. And it is not <b>neither</b>: " . $rateR . "% of listeners calling in is not a sample of listeners.",
  "It is not <b>voluntary response bias</b>: the households were chosen by the researcher, not by themselves. And it is not really <b>nonresponse bias</b> either &mdash; a " . $rateR . "% response rate leaves only " . $missing . " of " . $contacted . " households unaccounted for, far too few to swing the result.",
  "It is not <b>voluntary response bias</b>: the voters were chosen by the health department, not by themselves. And it is not really <b>nonresponse bias</b> either &mdash; a " . $rateR . "% response rate leaves only " . $missing . " of " . $contacted . " voters unaccounted for, far too few to swing the result."
)
$whyNotText = $whyNotAll[$ci]

// The wrong-way-round arithmetic students actually produce.
$flipped = round(100 * $missing / $contacted, 1)

$solutionguide = '
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
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p><span class="term-label">a. The response rate:</span> a response rate is the number who answered divided by the number who were approached, times 100. Here that is ' . $responded . ' &divide; ' . $contacted . ' &times; 100 &approx; <b>' . $rateR . '%</b>. The ' . $missing . ' who did not answer are the rest of the ' . $contacted . ' &mdash; dividing those by ' . $contacted . ' instead gives ' . $flipped . '%, which answers the opposite question.</p>
      <p><b>Why the rate is worth reporting at all:</b> a study that says it approached ' . $contacted . ' ' . $groupWord . ' is telling you about its intentions. A study that says ' . $responded . ' of ' . $contacted . ' answered, a rate of ' . $rateR . '%, is telling you about its evidence. A sample is not the list of people you selected &mdash; it is the list of people who actually answered.</p>
      <p><span class="term-label">b. What is wrong with the result:</span> ' . $whoText . '</p>
      <p><b>Why not the other two:</b> ' . $whyNotText . '</p>
      <p><b>The point that catches people out:</b> a bigger self-selected sample is a <i>worse</i> one, not a better one. With ordinary sampling error, more data helps &mdash; the estimate settles down around the truth. With self-selection, more data settles the estimate down around the <i>wrong</i> value, and does it far more convincingly. Ten thousand voluntary responses buy you a very precise measurement of what strongly-motivated people think. That precision is real, and it is not an answer to your question. Sample size fixes sampling error; it does not fix bias.</p>
      <p><b>The fix, when there is one:</b> the cure for a low response rate is <i>persistence</i> &mdash; callbacks, follow-ups, going back to the people already drawn &mdash; not a larger initial mailing. Mailing twice as many surveys and getting the same low rate just doubles the size of a sample of people who like answering surveys.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$introText</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Of the $contacted $groupWord who $contactVerb, $responded $respondVerb. What is the <b>response rate</b>, as a percent? Enter the percent rounded to <b>one decimal place</b>, without the % sign. $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> The result is going to be used to describe <b>$populationText</b>. What is the <b>main problem</b> with doing that? $answerbox[1]
  </div>
</div>

// === ANSWER ===

$solutionguide
