"""Rebuild reference/question-index.json -- what is already in the bank, and who uses it.

Run from mom-content/:   python reference/build-question-index.py

Exists so that authoring a section starts by asking "what do we already have?" rather than
writing fresh. The bank is 500+ questions across a dozen families, and a section's problems
routinely repeat topics an earlier section already covers; a question filed once can be attached
to any number of assignments, so a reused question costs a single GET.

One entry per .php in questions/, carrying:
  path      relative to mom-content/
  desc      the NAME - DESCRIPTION marker, which is what makes the file searchable
  qtype     the SET QUESTION TYPE TO marker
  used_by   slugs of the assignments whose manifests reference it -- empty means unused
  qsetid    its MyOpenMath library id if it has been filed, from question-library.json

A manifest names its question file under EITHER "file_path" or "file". Both are in use
(roughly 300 and 200 entries), and reading only one of them reports ~200 live questions as
unused -- which invites repurposing a question that is already in front of students.
"""
import collections
import io
import json
import os
import re

QUESTIONS = "questions"
BOOKS = "books"
LIBRARY = "reference/question-library.json"
OUT = "reference/question-index.json"

rows = []
for root, _, files in os.walk(QUESTIONS):
    for fn in sorted(files):
        if not fn.endswith(".php"):
            continue
        path = os.path.join(root, fn).replace("\\", "/")
        text = io.open(path, encoding="utf-8", errors="replace").read()
        desc = re.search(r"NAME - DESCRIPTION:\s*(.*?)\s*===", text, re.S)
        qtype = re.search(r"SET QUESTION TYPE TO:\s*(\S+?)\s*===", text)
        rows.append({
            "path": path,
            "desc": desc.group(1).replace("\n", " ") if desc else "",
            "qtype": qtype.group(1) if qtype else "?",
        })

used = collections.defaultdict(set)
for root, _, files in os.walk(BOOKS):
    for fn in files:
        if not fn.endswith(".json"):
            continue
        path = os.path.join(root, fn)
        try:
            manifest = json.loads(io.open(path, encoding="utf-8").read())
        except ValueError:
            print("  skipped unparseable manifest: %s" % path)
            continue
        slug = manifest.get("slug") or fn[:-5]
        for q in manifest.get("questions", []):
            fp = q.get("file_path") or q.get("file")
            if fp:
                used[fp].add(slug)

library = {}
if os.path.exists(LIBRARY):
    library = json.loads(io.open(LIBRARY, encoding="utf-8").read())

for r in rows:
    r["used_by"] = sorted(used[r["path"]])
    r["qsetid"] = library.get(r["path"], {}).get("qsetid")

io.open(OUT, "w", encoding="utf-8", newline="\n").write(json.dumps(rows, indent=1) + "\n")

live = sum(1 for r in rows if r["used_by"])
filed = sum(1 for r in rows if r["qsetid"])
print("%s: %d questions | %d used by an assignment | %d unused | %d filed in MOM"
      % (OUT, len(rows), live, len(rows) - live, filed))

missing = sorted(fp for fp in used if not os.path.exists(fp))
if missing:
    print("\nmanifests point at %d file(s) that do not exist:" % len(missing))
    for fp in missing:
        print("  %s  <- %s" % (fp, ", ".join(sorted(used[fp]))))
