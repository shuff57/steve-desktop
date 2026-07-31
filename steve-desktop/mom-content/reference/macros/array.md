# Array Macros

## Conversion

### `listtoarray(list,[tonum])`
Converts list like `'1,2,3,4'` to array. Set tonum=true for numeric treatment.
- **Returns**: array

### `calclisttoarray(list)`
Converts list of calculations like `'2^2,3^5,7/2'` to array, evaluating along the way.
- **Returns**: array

### `explode(symbol,string)`
Converts string to array, breaking on given symbol.
- **Returns**: array

### `arraytolist(array,[space])`
Converts array to list. Set space=true for spaces after commas.
- **Returns**: string

### `joinarray(array,[symbol,ksort])`
Converts array to string joined by symbol (default comma). ksort=true to sort by key.
- **Returns**: string

### `stringtoarray(string,[tonum])`
Converts text string to array of characters. tonum=true for numeric treatment.
- **Returns**: array

## Creation

### `fillarray(value,num,[start])`
Creates array with num entries of same value. Start index default 0. num can be array of indices.
- **Returns**: array

### `consecutive(min,max,[step])`
Creates array of consecutive numbers. Step defaults to 1.
- **Returns**: array

### `arraysetvalues(array,keyarray,value/valuearray)`
Sets values on existing array at specified keys. Returns modified array.
- **Returns**: array

## Sorting

### `sortarray(list/array,[type,maxkey])`
Sorts lowest to highest. type: `'rev'` for reverse, `'key'` by key, `'keyfill'` by key filling gaps.
- **Returns**: array

### `jointsort(array,array,[array,...])`
Jointly reorders arrays based on sort of first array.
- **Returns**: arrays

## Calculation on Arrays

### `calconarray(array,calculation)`
Performs calculation on each element using `'x'` as variable.
- **Returns**: array
- **Example**: `$b = calconarray($a,"x^2")`

### `multicalconarray(calculation,varslist,var1array,var2array,...)`
Multivariable `calconarray`.
- **Returns**: array
- **Example**: `$z = multicalconarray("x*y^2","x,y",$xs,$ys)`

### `calconarrayif(array,calculation,ifcondition)`
Like `calconarray` with condition per element.
- **Returns**: array
- **Example**: `$b = calconarrayif($a,"x+.1","floor(x)==x")`

### `keepif(array,condition)`
Filters array keeping only values meeting condition.
- **Returns**: array
- **Example**: `keepif($a,"x%3==0")`

## Slicing & Merging

### `subarray(array,params)`
Creates subset of array. Forms: `subarray($a,2,4,6)`, `subarray($a,"1:3","6:8")`, `subarray($a,$b)`.
- **Returns**: array

### `splicearray(array,offset,length,[replacement])`
Removes elements at offset/length and replaces with replacement array.
- **Returns**: array

### `mergearrays(array,array,[array,...])`
Combines two or more arrays into one.
- **Returns**: array

### `unionarrays(array,array)`
Unions two arrays preventing duplicates.
- **Returns**: array

### `intersectarrays(array,array)`
Finds intersection of two arrays.
- **Returns**: array

### `diffarrays(array1,array2)`
Returns elements in array1 not in array2.
- **Returns**: array

### `array_unique(array)`
Strips duplicate values. Does not re-index; use `array_values` after if needed.
- **Returns**: array

### `array_values(array)`
Returns values with consecutive numeric indices.
- **Returns**: array

### `array_keys(array)`
Returns keys of associative array.
- **Returns**: array

## Inspection & Search

### `count(array)`
Counts number of entries in array.
- **Returns**: integer

### `is_array(variable)`
Determines if variable is an array.
- **Returns**: boolean

### `sumarray(array)`
Adds entries in an array.
- **Returns**: number

### `in_array(needle,haystack)`
Checks if value is in array.
- **Returns**: boolean

### `arrayfindindex(needle,haystack)`
Returns index of value in array. First match only.
- **Returns**: integer

### `arrayfindindices(needle,haystack)`
Returns array of all indices matching value.
- **Returns**: array

### `array_flip(array)`
Reverses one-to-one array: indexes become values and vice versa.
- **Returns**: array

### `print_r(array,[return])`
Outputs array for debugging. Set return=true to return as string.
- **Returns**: string/void
