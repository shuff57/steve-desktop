import type { SnapshotOptions, SnapshotResult } from './dom-snapshot-types';
import { isValidSnapshotResult } from './dom-snapshot-types';

const DEFAULT_OPTIONS: Required<SnapshotOptions> = {
  maxNodes: 500,
  maxChars: 12_000,
  maxDepth: 8,
  captureBoundingBoxes: false,
  rootSelector: 'body',
};

function resolveOptions(options?: Partial<SnapshotOptions>): Required<SnapshotOptions> {
  return {
    maxNodes: options?.maxNodes ?? DEFAULT_OPTIONS.maxNodes,
    maxChars: options?.maxChars ?? DEFAULT_OPTIONS.maxChars,
    maxDepth: options?.maxDepth ?? DEFAULT_OPTIONS.maxDepth,
    captureBoundingBoxes: options?.captureBoundingBoxes ?? DEFAULT_OPTIONS.captureBoundingBoxes,
    rootSelector: options?.rootSelector ?? DEFAULT_OPTIONS.rootSelector,
  };
}

export function buildSmartWalkScript(options?: Partial<SnapshotOptions>): string {
  const resolved = resolveOptions(options);
  const optsJson = JSON.stringify(resolved);

  return `(function(){
var opts=${optsJson};
function captureAttrs(el){var out={};if(!el||!el.attributes)return out;for(var i=0;i<el.attributes.length;i++){var a=el.attributes[i];if(a.name.indexOf('data-')===0||a.name.indexOf('aria-')===0||a.name==='id'||a.name==='class'||a.name==='name'||a.name==='type'||a.name==='role'||a.name==='href'||a.name==='src'||a.name==='placeholder'||a.name==='contenteditable'){out[a.name]=a.value;}}return out;}
function classify(tag,attrs,hasText,childCount){if(['script','style','noscript','meta','link','head','svg','path','g','defs','symbol'].indexOf(tag)>=0)return'low';var role=(attrs.role||'').toLowerCase();var hasSignal=false;for(var key in attrs){if(key.indexOf('data-')===0||key.indexOf('aria-')===0||key==='role'){hasSignal=true;break;}}if(tag==='input'||tag==='textarea')return'critical';if(['button','checkbox','combobox','link','menuitem','option','radio','slider','spinbutton','switch','tab','textbox'].indexOf(role)>=0)return'critical';if(hasSignal&&(tag==='button'||tag==='a'||tag==='select'))return'critical';if(['form','table','tr','td','th','button','select','details','summary','label'].indexOf(tag)>=0)return'high';if(hasSignal||hasText||childCount>0)return'medium';return'low';}
function directText(el){var raw='';if(!el||!el.childNodes)return void 0;for(var i=0;i<el.childNodes.length;i++){var c=el.childNodes[i];if(c.nodeType===3){raw+=c.textContent||'';}}var text=raw.trim();return text||void 0;}
function selectorFor(el,parent){var tag=el.tagName.toLowerCase();var seg=el.id?tag+'#'+el.id:tag;return parent?parent+' > '+seg:seg;}
function bbox(el){var r=el.getBoundingClientRect();return{x:Math.round(r.x),y:Math.round(r.y),width:Math.round(r.width),height:Math.round(r.height),visible:r.width>0&&r.height>0};}
var root=document.querySelector(opts.rootSelector)||document.body;var nodes=[];var visited=0;
function walk(el,depth,parent){if(!el||depth>opts.maxDepth||nodes.length>=opts.maxNodes)return;visited++;var attrs=captureAttrs(el);var text=directText(el);var children=Array.prototype.slice.call(el.children||[]);var node={tag:el.tagName.toLowerCase(),depth:depth,priority:classify(el.tagName.toLowerCase(),attrs,text!==void 0,children.length),attrs:attrs};if(text)node.text=text;if(opts.captureBoundingBoxes)node.bbox=bbox(el);node.selector=selectorFor(el,parent);nodes.push(node);for(var i=0;i<children.length&&nodes.length<opts.maxNodes;i++){walk(children[i],depth+1,node.selector);}}
walk(root,0,'');var charCount=JSON.stringify(nodes).length;return{nodes:nodes,meta:{totalVisited:visited,nodesIncluded:nodes.length,nodesDropped:Math.max(visited-nodes.length,0),wasTruncated:charCount>opts.maxChars||visited>nodes.length,charCount:charCount,capturedAt:new Date().toISOString()}};
})()`;
}

export function parseSmartWalkResult(json: string): SnapshotResult {
  let parsed: unknown;

  try {
    parsed = JSON.parse(json);
  } catch (error) {
    const message = error instanceof Error ? error.message : 'unknown parse failure';
    throw new Error(`Invalid snapshot JSON: ${message}`);
  }

  if (!isValidSnapshotResult(parsed)) {
    throw new Error('Invalid snapshot result payload');
  }

  return parsed;
}

export function formatSnapshotForPrompt(result: SnapshotResult): string {
  const metaLine = [
    `totalVisited=${result.meta.totalVisited}`,
    `nodesIncluded=${result.meta.nodesIncluded}`,
    `nodesDropped=${result.meta.nodesDropped}`,
    `wasTruncated=${result.meta.wasTruncated}`,
    `charCount=${result.meta.charCount}`,
    `capturedAt=${result.meta.capturedAt}`,
  ].join(' ');

  const nodeLines = result.nodes.map((node) => {
    const parts = [`[${node.priority}] ${node.tag}`, `depth=${node.depth}`];

    if (node.text) parts.push(`text=${JSON.stringify(node.text)}`);
    const attrEntries = Object.entries(node.attrs);
    if (attrEntries.length > 0) {
      parts.push(`attrs=${attrEntries.map(([key, value]) => `${key}:${value}`).join(', ')}`);
    }
    if (node.selector) parts.push(`selector=${node.selector}`);
    if (node.bbox) {
      parts.push(
        `bbox=${node.bbox.x},${node.bbox.y} ${node.bbox.width}x${node.bbox.height} ${node.bbox.visible ? 'visible' : 'hidden'}`,
      );
    }

    return parts.join(' | ');
  });

  return ['DOM Snapshot', metaLine, ...nodeLines].join('\n');
}
