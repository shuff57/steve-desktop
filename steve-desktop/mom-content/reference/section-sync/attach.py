import json, io, sys, time
PLAN = json.load(io.open(sys.argv[1] if len(sys.argv)>1 else 'secplan.json', encoding='utf-8'))
ONLY = sys.argv[2] if len(sys.argv)>2 else None

DATES = r"""(function(){var o={};
  ['sdate','stime','edate','etime','sdatetype','edatetype'].forEach(function(n){
    var e=document.getElementsByName(n)[0];
    o[n]= e? (e.type==='radio'? (document.querySelector('[name='+n+']:checked')||{}).value : e.value) : null;});
  var lnk=[...document.getElementsByName('extreflinks[]')].map(e=>e.value);
  o.booklinks=lnk; return JSON.stringify(o);})()"""

def settings(cid, aid):
    goto_url("https://www.myopenmath.com/course/addassessment2.php?id=%s&cid=%s"%(aid,cid)); wait_for_load()
    return json.loads(js(DATES))

for p in PLAN:
    tag = "%s-%s" % (p['sec'], p['kind'])
    if ONLY and ONLY != tag: continue
    cid, aid = p['cid'], p['aid']
    print("\n=== %s cid=%s aid=%s" % (tag, cid, aid))
    before = settings(cid, aid)
    print("  dates before:", json.dumps(before))

    goto_url("https://www.myopenmath.com/course/addquestions.php?cid=%s&aid=%s"%(cid,aid)); wait_for_load()
    empty = js("document.body.innerText.indexOf('No Questions currently in assessment') > -1")
    if not empty:
        n = js("(typeof itemarray!=='undefined')? itemarray.length : 'unknown'")
        print("  SKIP - not empty (%s questions). Refusing to attach on top." % n); continue

    for i, q in enumerate(p['q']):
        goto_url("https://www.myopenmath.com/course/modquestion2.php?qsetid=%s&cid=%s&aid=%s&from=addq&process=true&usedef=true"
                 % (q['qsetid'], cid, aid)); wait_for_load()
    print("  attached %d" % len(p['q']))

    goto_url("https://www.myopenmath.com/course/addquestions.php?cid=%s&aid=%s"%(cid,aid)); wait_for_load()
    spec = json.dumps({"want":[{"qsetid":q['qsetid'],"points":q['points'],"slot":i+1}
                               for i,q in enumerate(p['q'])]})
    res = js("""(function(){
      var spec = """ + spec + """;
      if(typeof itemarray==='undefined') return JSON.stringify({err:'no itemarray after attach'});
      if(beentaken) return JSON.stringify({err:'beentaken - points would not save'});
      var byq={}; spec.want.forEach(function(w){ byq[w.qsetid]=w; });
      var unmatched=[];
      itemarray.forEach(function(r){ var w=byq[String(r[1])];
        if(w) r[4]=w.points; else unmatched.push(r[1]); });
      var missing=spec.want.filter(function(w){
        return !itemarray.some(function(r){return String(r[1])===w.qsetid;});}).map(w=>w.qsetid);
      var ord={}; spec.want.forEach(function(w){ ord[w.qsetid]=w.slot; });
      itemarray.sort(function(a,b){ return ord[String(a[1])]-ord[String(b[1])]; });
      return JSON.stringify({n:itemarray.length,
        total:itemarray.reduce(function(s,r){return s+Number(r[4]);},0),
        unmatched:unmatched, missing:missing});})()""")
    d = json.loads(res)
    print("  pre-save:", res)
    if d.get('err') or d['unmatched'] or d['missing'] or d['n']!=15 or d['total']!=100:
        print("  ABORT - not saving"); continue
    js("submitChanges();"); time.sleep(2)

    goto_url("https://www.myopenmath.com/course/addquestions.php?cid=%s&aid=%s"%(cid,aid)); wait_for_load()
    chk = js("""JSON.stringify({n:itemarray.length,
      total:itemarray.reduce(function(s,r){return s+Number(r[4]);},0),
      rows:itemarray.map(function(r){return [String(r[1]),Number(r[4])];})})""")
    got = json.loads(chk)
    want = [[q['qsetid'], q['points']] for q in p['q']]
    print("  VERIFY: %d q, %d pts, order %s" % (got['n'], got['total'],
          "MATCH" if got['rows']==want else "MISMATCH"))
    after = settings(cid, aid)
    print("  dates after: ", json.dumps(after))
    print("  dates %s | booklink %s" % ("UNCHANGED" if after==before else "CHANGED!!",
          "unchanged" if after.get('booklinks')==before.get('booklinks') else "CHANGED!!"))
