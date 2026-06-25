---
name: Email this parent
description: Copy a student's parent email from Aeries into a new Outlook message — FERPA-safe, the address never reaches the model — then stop for your approval before sending.
author: steve-desktop
tags:
  - aeries
  - outlook
  - email
  - ferpa
urlPatterns:
  - https://*.aeries.net/*
  - https://outlook.office.com/*
  - https://outlook.cloud.microsoft/*
---

# Email this parent

Run this in the **Agent** panel in **Review mode** so you approve each step.
It moves the parent email **by reference** using `read`/`paste`: the address is
read into an on-device slot and written into Outlook locally — it is **never**
put in a `fill`, never shown to the model, never sent to any provider. That is
what keeps it FERPA-safe regardless of which model (including hosted/Ollama
Cloud) is selected.

This skill is **autonomous**: it opens the pages it needs, logs itself in with
saved credentials (filled on-device via `login`), and navigates by the known
selectors below — no manual setup. The only thing it will not do without you is
click **Send**.

> **Dry run first.** Prefix your goal with **"dry run"** (e.g. "dry run: email
> this parent") to rehearse safely — the agent opens pages, verifies a login
> credential matches (without submitting), and drafts the message, but the final
> **Send** is only *verified* (located, not clicked). Drop the prefix to run for real.

## Steps for the agent
1. Go to the Aeries student's contacts:
   - `navigate` to `https://chicousd.aeries.net/teacher/EmergencyContacts.aspx`.
   - If a login form appears, use `login` (it fills saved credentials on-device),
     then `navigate` to `EmergencyContacts.aspx` again.
   - The student in context is the one to email. If you need a different student,
     ask the user to select them in Aeries first.
2. Copy the **parent** email into slot `p1`:
   - `read` selector `#ctl00_MainContent_subStuTopEmail_lblPEM` into `p1`.
   - (Student email, if ever needed, is `#ctl00_MainContent_subStuTopEmail_lblSEM`.)
   - The result reports only a length — that is expected. Do not try to view the value.
   - If the length is 0, there is no parent email on file — stop and tell the user.
3. `navigate` to `https://outlook.office.com/mail/`.
   - If a Microsoft login form appears, use `login`, then continue.
4. `click` the compose button: selector `role=button[name="New mail"]`.
5. Put the address into the recipient field, then commit it as a recipient:
   - `paste` selector `[aria-label="To"]` from `p1`.
   - `keyboard` key `Enter`  (Outlook's To is a people-picker; Enter commits the pill).
6. Fill the message the user asked for:
   - `fill` selector `input[aria-label="Subject"]` with the subject the user gave you.
   - `fill` selector `[aria-label="Message body"]` with the body the user gave you.
7. **STOP. Do not send.** Respond asking the user to review the drafted message
   and confirm. Only after the user explicitly says to send:
   - `click` selector `role=button[name="Send"]`.

## Hard rules
- **Never** `fill` the recipient with a literal email address — always `paste`
  from the slot. The address must never appear in your reasoning or messages.
- **Never** click **Send** without explicit user approval in the same session.
- If the parent-email `read` returns length 0, the student has no parent email on
  file — stop and tell the user; do not guess or use the student's own email.
- If you cannot find the To field or the Send button, stop and report — do not
  improvise a different send path.
