#!/usr/bin/env python3
"""Fail if any saved SiteProfile JSON leaks identifiable data.

The persisted profile (mergedToProfile) is NOT redacted — only the model path is.
This gates the scrape: for Ares especially, a captured label/href can carry a
student name or parent email. Run over the profile dir; exit 1 on any hard hit.

  python verify_no_pii.py <profiles_dir>     # gate a capture
  python verify_no_pii.py --self-test        # prove the patterns work
"""
import json
import re
import sys
from pathlib import Path

# Windows consoles default to cp1252 and choke on → / … / ⟦⟧. Force UTF-8.
try:
    sys.stdout.reconfigure(encoding="utf-8", errors="replace")
except Exception:
    pass

TOKEN = re.compile(r"⟦D\d+⟧")  # redacted slot — allowed (forward-compat)

# Hard fails: unambiguous identifiers.
# NOTE: a bare long-digit rule floods on course/assignment IDs in URLs (cid=, aid=,
# qid=) which are NOT PII. Only person-scoped identifier params are flagged.
HARD = {
    "email": re.compile(r"\b[\w.+-]+@[\w-]+\.[\w.-]+\b"),
    "phone": re.compile(r"\b(?:\+?1[-.\s]?)?\(?\d{3}\)?[-.\s]?\d{3}[-.\s]?\d{4}\b"),
    "ssn": re.compile(r"\b\d{3}-\d{2}-\d{4}\b"),
    "dob": re.compile(r"\b(0?[1-9]|1[0-2])/(0?[1-9]|[12]\d|3[01])/(19|20)\d\d\b"),
    "student_id": re.compile(r"\b(?:stu(?:dent)?id|userid|uid|sid|personid)=\d{2,}", re.I),
    # Roster names render as "Lastname, Firstname" on gradebook checkboxes/labels.
    "roster_name": re.compile(r"\b[A-Z][a-z]+,\s+[A-Z][a-z]+\b"),
}
# Soft: two Capitalized words = name-shaped. Heuristic -> warn, don't fail.
# ponytail: regex + chrome-word stoplist; swap for an NER pass only if false
# positives bite. The stoplist kills the obvious UI bigrams ("Save Question").
NAME = re.compile(r"\b([A-Z][a-z]+)\s+([A-Z][a-z]+)\b")
CHROME_WORDS = {
    "save", "add", "edit", "delete", "new", "my", "all", "view", "the", "question",
    "questions", "assignment", "assignments", "course", "courses", "home", "next",
    "back", "previous", "submit", "cancel", "close", "open", "menu", "settings",
    "log", "sign", "page", "test", "preview", "answer", "common", "control", "text",
    "search", "filter", "sort", "select", "create", "update", "remove", "download",
    "upload", "print", "help", "about", "contact", "email", "message", "send",
}


def name_hits(s):
    """Name-shaped bigrams where NEITHER word is a known chrome word."""
    out = []
    for m in NAME.finditer(s):
        a, b = m.group(1).lower(), m.group(2).lower()
        if a in CHROME_WORDS or b in CHROME_WORDS:
            continue
        out.append(m.group(0))
    return out


def strings(obj, path=""):
    """Yield (json_path, value) for every string leaf."""
    if isinstance(obj, str):
        yield path, obj
    elif isinstance(obj, dict):
        for k, v in obj.items():
            yield from strings(v, f"{path}.{k}")
    elif isinstance(obj, list):
        for i, v in enumerate(obj):
            yield from strings(v, f"{path}[{i}]")


def scan_text(s):
    """Return list of (kind, masked_match), skipping anything inside a redaction token."""
    clean = TOKEN.sub("", s)
    hits = []
    for kind, pat in HARD.items():
        for m in pat.finditer(clean):
            v = m.group(0)
            hits.append((kind, v[:2] + "…" + v[-1:]))
    return hits


def scan_file(p):
    data = json.loads(p.read_text(encoding="utf-8"))
    hard, soft = [], []
    for jp, s in strings(data):
        for kind, masked in scan_text(s):
            hard.append((jp, kind, masked))
        if name_hits(TOKEN.sub("", s)):
            soft.append((jp, s[:40]))
    return hard, soft


def main(argv):
    if argv and argv[0] == "--self-test":
        assert scan_text("reach me at a@b.com")[0][0] == "email"
        assert scan_text("call 555-123-4567")[0][0] == "phone"
        assert scan_text("ssn 123-45-6789")[0][0] == "ssn"
        assert scan_text("dob 03/14/2008")[0][0] == "dob"
        assert scan_text("moddataset.php?stuid=4821")[0][0] == "student_id"
        assert scan_text("course.php?cid=306621") == []  # course id -> not PII
        assert scan_text('checkbox[name="De Jesus, Angel"]')[0][0] == "roster_name"
        assert scan_text("⟦D1⟧") == []  # tokenized -> allowed
        assert scan_text("Save") == []  # chrome -> clean
        assert name_hits("Jane Doe") and not name_hits("Save Question")
        assert not name_hits("My Assignments") and not name_hits("Add Questions")
        print("self-test OK")
        return 0

    if not argv:
        print("usage: verify_no_pii.py <profiles_dir> | --self-test")
        return 2
    root = Path(argv[0])
    files = list(root.rglob("*.json"))
    if not files:
        print(f"no profile JSON under {root}")
        return 2

    failed = False
    for p in files:
        hard, soft = scan_file(p)
        if hard:
            failed = True
            print(f"\nFAIL {p}")
            for jp, kind, masked in hard:
                print(f"  [{kind}] {jp} → {masked}")
        if soft:
            print(f"\nwarn {p} (name-shaped, review):")
            for jp, sample in soft[:10]:
                print(f"  {jp} → {sample!r}")
    print("\nPII GATE:", "FAILED" if failed else "passed",
          f"({len(files)} profiles scanned)")
    return 1 if failed else 0


if __name__ == "__main__":
    sys.exit(main(sys.argv[1:]))
