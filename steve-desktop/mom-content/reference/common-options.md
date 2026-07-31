# Common Options

## Hints

- desc: `Per-attempt hints displayed in question.`
- single_part:
  - define: `$hints[attempt_number] = "hint text"`
  - display: `Place $hintloc in Question Text`
- multipart:
  - single_strand: `Same as single part for one hint strand.`
  - per_part: `$hints[part_number][attempt_number] = "hint text". Display: $hintloc[part_number].`
  - conditional: `$hints[part][attempt] = array("hint text", [part_num1, part_num2]) — shows after listed parts attempted/correct.`
- $hintlabel: `Override default 'Hint:' text.`

## Help References

- $helptext: `Links to videos, book references, etc. Displays at bottom of question. Controlled by 'show hints?' setting.`

## Student Answers

- $stuanswers:
  - desc: `Reference students' previous answers for consistency grading or lab-type problems.`
  - access:
    - `$stuanswers[N] — answer on question N (1-indexed)`
    - `$stuanswers[N][P] — answer on question N, part P`
    - `$stuanswers[$thisq][P] — current question, part P`
    - `$stuanswers[$thisq-1] — previous question`
  - $stuanswersval: `$stuanswersval[$thisq] or $stuanswersval[$thisq][P] for numerical value of Calculated types.`
  - $stulastentry: `Student's entered answer including autosaved (not just submitted).`
- notes:
  - `Unanswered: $stuanswers[N] === null (except drop-down where == 'NA'). In equations takes value 0.`
  - `Guard null: $a = $stuanswers[$thisq][0]; $a = rand(1,100) if ($a===null)`
  - `Custom $showanswer recommended when using $stuanswers in $answer.`
  - `In strings/Question Text, use curly brackets: {$stuanswers[0][0]}. In calculations, use parentheses.`
  - `$stuanswers[$thisq] defined for scoring but may not be for display.`
  - `Use getstuans($stuanswers,N,P) for safer retrieval.`
  - `Use stuansready($stuanswers,$thisq,[p1,p2,...]) to check answers exist.`

## Reference Variables

- $scorenonzero: `Display only. -1 unanswered, 0 zero score, 1 nonzero score. Use [$thisq] or [$thisq][partindex].`
- $scoreiscorrect: `Display only. -1 unanswered, 1 perfect (pre-penalty), 0 otherwise.`
- $attemptn: `Current attempt number (0-indexed).`
- $partattemptn: `Per-part attempt numbers. $partattemptn[part_number].`
- $requestclearla: `Set true to clear student's last answer on display.`
- $graphdispmode: `0 for text-based alternatives, 1/2 for image-based.`
- $drawentrymode: `0 for keyboard-based, 1 for mouse-based.`

## Reusing Code

### includecodefrom

- sig: `includecodefrom(questionid)`
- desc: `Imports Common Control code from another question. Sets $included = true in source question.`

### includeqtextfrom

- sig: `includeqtextfrom(questionid)`
- desc: `Imports Question Text from another question.`

## Teacher Notes

- desc: `Notes only viewable by teacher while grading.`
- syntax: `[teachernote]This is the note[/teachernote]`

## Hiding Content

- hidden: `<div class="hidden">...</div> — content still in page (students can inspect).`
- toggler: `<div data-toggler="Button Title">...</div> — adds toggle button. Optional data-toggler-hide attribute for different hide label.`
