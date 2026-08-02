# Graph & Table Macros

## Table Display

### `showarrays(string,array,[string,array]...,[options])`
Creates tabular display of data in arrays as columns with strings as headers. Alt form: `showarrays(array of headers, array of data arrays, [options])`.
- **Returns**: string
- **Options**: string `'c'`, `'l'`, `'r'` for alignment, or array with `$opts['align']`, `$opts['tablealign']='center'`, `$opts['caption']`. Per-column alignment: `'rcc'`.

### `showdataarray(array,[columns,options])`
Creates tabular display of data with no headers. One column unless columns specified.
- **Returns**: string

### `horizshowarrays(string,array,[string,array]...,[options])`
Creates tabular display of data as rows with strings as row labels. Does not text wrap.
- **Returns**: string

### `showrecttable(data_array_of_arrays,columnlabels,rowlabels,[options])`
Creates tabular display of a 2x2 data table. `$data[0]` is the first row.
- **Returns**: string

## Plot Creation

### `showplot(funcstrings,[xmin,xmax,ymin,ymax,labels,grid,width,height])`
Creates a graph plot.
- **Returns**: plot

**Parameters:**

| Parameter | Description |
|-----------|-------------|
| `funcstrings` | Single string or array of strings: `'function,color,min,max,startmarker,endmarker,width,dash'` |
| `function` | Function of x like `cos(x)`, parametric like `[sin(t),cos(t)]`, or vertical line `x=1` |
| `color` | `black`, `red`, `orange`, `yellow`, `green`, `blue`, `purple` |
| `min_max` | Input variable range. Exclude points: `-5,5!0!2` |
| `startmarker` / `endmarker` | `'open'`, `'closed'` (dots), `'arrow'`, anything else for none |
| `width` | Pixel width for the line |
| `dash` | `'dash'` for dashed line |
| `dots_format` | `'dot,x,y,style,color,label,labelloc'` — style: `open`/`closed` |
| `text_format` | `'text,x,y,label,color,location,angle,graphassoc'` |
| `xmin_xmax_ymin_ymax` | Graphing window. Defaults -5 to 5. Use `'0:-n'` for first quadrant. |
| `labels` | Axes label spacing. Default 1. `'off'`/0 for none. `'xlbl:ylbl'` or `'xlbl:ylbl:xname:yname'`. |
| `grid` | Grid line spacing. Default 1. `'off'`/0 for none. `'xgrid:ygrid'`. |
| `width_height` | Pixel dimensions. Default 200x200. |

### `mergeplots($plot1,$plot2,[$plot3,...])`
Merges multiple plots into one. Grid/border from first graph used.
- **Returns**: plot

### `invertplot($plot)`
Inverts drawing on plot, swapping x and y coordinates. Use `replacealttext` since auto-generated alt text won't be accurate.
- **Returns**: plot

### `addfractionaxislabels(plot,step,[axis])`
Adds fraction labels along axis in steps. Step is fraction like `'1/4'` or pi multiple like `'pi/4'`. Set axis to `'y'` for y-axis.
- **Returns**: plot

### `addlabel(plot,x,y,label,[color,loc,angle,size])`
Adds a label to a plot. loc: `'left'`, `'right'`, `'below'`, `'above'`. MathML not supported in labels.
- **Returns**: plot

### `addlabelabs(plot,x,y,label,[color,loc,angle])`
Like `addlabel` but x,y are pixel values on the picture.
- **Returns**: plot

### `addplotborder(plot,left,[bottom,right,top])`
Changes pixel width of border (default 5) around a plot.
- **Returns**: plot

### `adddrawcommand(plot,commands)`
Inserts arbitrary asciisvg drawing commands into an existing plot.
- **Returns**: plot

### `showasciisvg(string,[width,height,alttext])`
Sets up an svg with string as the script. Include alttext for accessibility.
- **Returns**: string

### `replacealttext(image_or_graph,alttext)`
Replaces the alt text in the image or graph with specified text.
- **Returns**: void

### `changeimagesize(image_or_graph,width,[height])`
Changes display width for an image. Doesn't resize the file itself.
- **Returns**: void

### `textonimage(img,text,left,top,[text,left,top,...])`
Overlays text over an image. Image can be URL or uploaded image variable. Not screenreader-friendly.
- **Returns**: string

### `addimageborder(image,[border_width,margin])`
Adds a border to an uploaded image. Border width default 1, margin default 0.
- **Returns**: string

## Array-to-Plot Converters

### `arraystodoteqns(xarray,yarray,[color])`
Converts x/y arrays into form usable in `showplot`.
- **Returns**: string

### `connectthedots(xarray,yarray,[color,thickness,startmarker,endmarker])`
Converts x/y arrays into showplot format for line segments connecting (x,y) pairs.
- **Returns**: string

### `arraystodots(xarray,yarray)`
Converts x/y arrays into form for Drawing answer type.
- **Returns**: string

## Drawing Answer Extraction

### `gettwopointlinedata(stuans,[xmin,xmax,ymin,ymax,width,height]) or (stuans,[grid,snaptogrid])`
Extracts two-point line data from `$stuanswers` of a drawing question.
- **Returns**: array of array(x1,y1,x2,y2)

### `gettwopointdata(stuans,type,[xmin,xmax,ymin,ymax,width,height]) or (stuans,type,[grid,snaptogrid])`
Extracts two-point data for given curve type from drawing `$stuanswers`.
- **Returns**: array of array(x1,y1,x2,y2)
- **type values**: `line`, `lineseg`, `ray`, `parab`, `halfparab`, `horizparab`, `sqrt`, `cubic`, `cuberoot`, `rational`, `exp`, `genexp`, `log`, `genlog`, `sin`, `cos`, `abs`, `vector`, `circle`, `ellipse`. `'circlerad'` returns (x-center,y-center,radius). `'ellipserad'` returns (x-center,y-center,x-radius,y-radius).

### `gettwopointformulas(stuans,type,[xmin,xmax,ymin,ymax,width,height]) or (stuans,type,[grid,snaptogrid])`
Returns formulas for curves drawn by student. Optional arg `'showequation,[xvar,yvar]'` for implicit equations.
- **Returns**: array of expressions

### `getdotsdata(stuans,[xmin,xmax,ymin,ymax,width,height]) or (stuans,[grid,snaptogrid])`
Extracts closed dots data from drawing `$stuanswers`.
- **Returns**: array of array(x,y)

### `getopendotsdata(stuans,[xmin,xmax,ymin,ymax,width,height]) or (stuans,[grid,snaptogrid])`
Extracts open dots data from drawing `$stuanswers`.
- **Returns**: array of array(x,y)

### `getlinesdata(stuans,[xmin,xmax,ymin,ymax,width,height]) or (stuans,[grid,snaptogrid])`
Extracts lines data from older non-twopoint line tool or polygon tool.
- **Returns**: array of arrays of (x,y) points

### `getineqdata(stuans,[type,xmin,xmax,ymin,ymax,width,height]) or (stuans,[grid,snaptogrid])`
Extracts inequalities data. Type: `'linear'` or `'quadratic'`.
- **Returns**: array of array(style,x1,y1,x2,y2,x3,y3)

### `getsnapwidthheight(xmin,xmax,ymin,ymax,width,height,snaptogrid)`
Returns array(width,height) for pixel-accurate snaptogrid values.
- **Returns**: array(width,height)
