# Future plans — steve-desktop

Newest first. What was decided, and what is still open.

## 2026-09-01 — VPS for browser automation: parked, not rejected

**Decided:** keep the browser local for now. The VPS is real and already owned, but it
is deliberately not in play until the harness is proven end to end on this box.

**Why parked rather than dropped.** A VPS answers "can this run unattended at 7am with
the laptop shut." It does not answer "why does the run get stuck" — that was snapshot
volume (33670 B per MOM course page), which follows the process to any machine. Moving
first would have shipped the same defect over SSH.

**What makes it cheap to revisit.** The swap to `agent-browser` turned the VPS from a
rewrite into a flag: `-p browserbase | kernel | browserless | agentcore | browseruse`.
Every command written against the local session runs unchanged against a cloud runtime.
So there is no migration to design later — only a decision to make.

**Still open:**
- Prove the harness end to end locally first (read-only MOM walk, steps + bytes + stalls).
- Whether MOM/Aeries credentials and a live session holding student records belong on a
  rented box at all. Today that exposure stops at the desktop. Not a blocker, but it is
  the cost the $5/mo does not include, and it should be an explicit call rather than a
  side effect of wanting cron.
- Scheduled runs (the actual reason to go remote) are explicitly deferred — revisit when
  attendance should run itself every morning.
