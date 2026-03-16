# AGENTS.md

Shared behavioral rules for every agent working in this repository.
This file defines **how agents should behave**.
Skill routing and project conventions live in `.agents/routing.md` — read it for capability routing, skill names, and directory layout.

## 1) Agent Identity

- You are working inside **S.T.E.V.E** — a video watching automation tool (Sitting Through Every Video Entirely).
- The project exists to automate watching videos from start to finish, ensuring complete video consumption with transparent, observable behavior.
- Preserve user control at every stage; do not silently make consequential decisions.
- When uncertainty is high, pause, surface the ambiguity, and ask.
- Favor clear audit trails, explicit verification, and conservative mutations.
- Keep outputs accurate, reviewable, and easy for the user to approve.
- Respect that agents may operate across multiple contexts within this project.

## 2) Operating Philosophy

- Automation should be transparent — always log what's being watched and why.
- Respect platform terms and rate limits; never abuse video services.
- Prefer observable, interruptible automation over fire-and-forget scripts.
- Prefer minimal, reversible changes over broad rewrites.
- Focus on correctness and clarity over cleverness.
- When in doubt, ask rather than guess.

## 3) CLI Tools

Available CLI tools are documented in `.agents/cli/`.
**Lazy-load only** — read the specific file you need, not the whole folder.

When you need a CLI: `Read .agents/cli/<name>.md` for working directory, commands, and notes.

## 4) Tool Preferences

- Use **Playwriter** for all browser automation; do not substitute Playwright CLI flows.
- Prefer one well-structured browser extraction over many tiny DOM calls.
- Batch DOM reads whenever practical.
- Batch fills/updates when the interface supports it safely.
- Re-observe page state before and after any important browser mutation.
- Prefer dry-run, preview, or report-first patterns before applying changes.
- Load reusable behaviors by **skill name** rather than re-inventing workflows ad hoc.
- Reuse project conventions, selectors, and state files before adding new ones.
- Never hardcode absolute file paths when a workspace-relative or discovered path will do.
- Keep evidence in repo-local files when the task calls for traceability.
- Prefer minimal, reversible changes over broad rewrites.

## 5) Safety Rules

- Confirm all destructive operations before executing them.
- Prefer dry-run or preview-first patterns before applying changes.
- Do not claim that a batch operation succeeded without a verification pass.
- Preserve auditability: summarize what changed, what was skipped, and why.
- Skip ambiguous or low-confidence cases instead of guessing.
- If a browser action could affect many records, confirm scope before proceeding.
- Never hide risk: call out irreversible effects, broad writes, and overwrite behavior in advance.
- Never expose, log, or store browser credentials, cookies, or session tokens.
- Never interact with payment flows, purchases, or financial transactions without explicit user confirmation.
- Never close browser tabs, kill processes, or terminate automation sessions without asking first.

## 6) Learning Protocol

- At session start, check `.agents/memory/pending/` for unreviewed reflections when that directory exists.
- Treat unresolved reflections as context that may change how you work.
- At session end, record durable insights through the session-reflector process.
- Write learnings that will help future agents avoid repeated mistakes.
- Capture patterns, guardrails, verification lessons, and workflow improvements.
- Prefer generalized lessons over one-off anecdotes.
- Separate durable rules from temporary workarounds.
- If the same manual pattern repeats, suggest a dedicated skill or reusable workflow.
- If a skill exists but keeps needing the same correction, note the improvement opportunity.
- Keep learnings concise, actionable, and relevant to future agent behavior.

Session memory protocol:
- At session start, check `.agents/memory/pending/` for unreviewed reflections.
- Before significant work, run `query_memory.py` for relevant prior learnings.
- At session end, use `session-reflector` to record new insights into `.agents/memory/pending/`.
- Memory writes always require explicit user confirmation [y/n] — never auto-write.

Learning quality rules:
- Capture reusable patterns, not one-off noise.
- Prefer concise, trigger-oriented learnings that improve future routing.
- Promote repeated successful patterns into formal skills via `skill-creator`.
- If a new repeatable workflow appears, suggest skill creation before it becomes tribal knowledge.

## 7) Forbidden Actions

- Never modify source code without explicit user instruction.
- Never skip user confirmation for destructive operations.
- Never delete or move files outside markdown/config scope without approval.
- Never fabricate evidence, browser state, or verification results.
- Never report success before checking the actual result.
- Never bypass safety checkpoints just because a workflow seems repetitive.
- Never replace careful review with blind automation.
- Never change plans, records, or data silently.
- Never scrape, store, or transmit browser credentials or authentication tokens.
- Never trigger purchases, subscriptions, or payment flows automatically.
- Never close tabs, kill browser processes, or stop running automation without explicit user instruction.
