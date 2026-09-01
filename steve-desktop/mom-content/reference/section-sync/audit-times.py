import json, io, glob, datetime, sys
T = {"34":{"regular":{"start":"10:11 am","end":"11:40 am","end7":"11:47 am"},
           "wed":    {"start":"10:33 am","end":"11:54 am","end7":"12:01 pm"},
           "min":    {"start":"9:37 am", "end":"10:37 am","end7":"10:44 am"}},
     "78":{"regular":{"start":"2:03 pm","end":"3:32 pm","end7":"3:39 pm"},
           "wed":    {"start":"2:09 pm","end":"3:30 pm","end7":"3:37 pm"},
           "min":    {"start":"11:56 am","end":"12:56 pm","end7":"1:03 pm"}}}
MINDAYS = {"10/27/2026","01/26/2027","03/02/2027"}
DOW = ["Mon","Tue","Wed","Thu","Fri","Sat","Sun"]
def norm(t): return " ".join((t or "").lower().split())
def daytype(d):
    if d in MINDAYS: return "min"
    try: dt = datetime.datetime.strptime(d, "%m/%d/%Y")
    except Exception: return None
    return "weekend" if dt.weekday()>=5 else ("wed" if dt.weekday()==2 else "regular")
def dow(d):
    try: return DOW[datetime.datetime.strptime(d,"%m/%d/%Y").weekday()]
    except Exception: return "?"
def which(block,t):
    t=norm(t); return [(dt_,slot) for dt_,slots in T[block].items()
                       for slot,v in slots.items() if norm(v)==t]
rows=[]
for f in sorted(glob.glob(sys.argv[1]+"/times-*.jsonl")):
    for line in io.open(f,encoding='utf-8'):
        line=line.strip()
        if line: rows.append(json.loads(line))
print("read %d assessments\n" % len(rows))
bad=[]
for r in rows:
    for lbl,dk,tk,tk_on in (("START",'sdate','stime','sdatetype'),("DUE",'edate','etime','edatetype')):
        on = r.get(tk_on) == ('sdate' if lbl=="START" else 'edate')
        if not on: continue
        d,t = r[dk], r[tk]
        dty = daytype(d)
        if dty is None: continue
        if dty=="weekend":
            bad.append((r,lbl,d,t,"date falls on %s (weekend)"%dow(d))); continue
        hits = which(r['block'], t)
        if not hits:
            bad.append((r,lbl,d,t,"time matches no bell slot for block %s"%r['block']))
        elif not any(h[0]==dty for h in hits):
            gotty,slot = hits[0]
            bad.append((r,lbl,d,t,"%s (%s) but time is the %s-day %s -> should be %s"
                        % (dow(d),dty,gotty,slot,T[r['block']][dty][slot])))
print("================ %d MISMATCHES ================" % len(bad))
for r,lbl,d,t,msg in bad:
    print("%-3s %-38s %-5s %s %-9s %s" % (r['sec'], (r.get('name') or '')[:38], lbl, d, t, msg))
