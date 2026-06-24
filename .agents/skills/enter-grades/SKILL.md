---
name: Enter Grades
description: enter grades for a student
urlPatterns:
  - sis.example.edu
---

# Enter Grades

Trigger: enter grades for a student

Recorded steps (replayed deterministically):

```json
{
  "name": "Enter Grades",
  "trigger": "enter grades for a student",
  "steps": [
    {
      "action": "fill",
      "selector": "#studentName",
      "value": "Jane Doe",
      "description": "student name field"
    },
    {
      "action": "fill",
      "selector": "#grade",
      "value": "A",
      "description": "grade field"
    },
    {
      "action": "click",
      "selector": "#submit",
      "description": "submit button"
    }
  ]
}
```
