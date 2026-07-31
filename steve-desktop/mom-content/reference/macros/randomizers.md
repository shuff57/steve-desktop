# Randomizers

> **Note:** All bounds (min,max) are inclusive. Arguments in [square brackets] are optional.

## Single-Value Randomizers

### `rand(min,max)`
Returns an integer between min and max.
- **Returns**: integer

### `rrand(min,max,p)`
Returns a number between min and max in steps of p.
- **Returns**: number
- **Examples**:
  - `rrand(2,5,.1)` might return 3.4
  - `rrand(2,5,.01)` might return 3.27
  - `rrand(2,8,2)` might return 6

### `nonzerorand(min,max)`
Returns a nonzero integer between min and max.
- **Returns**: integer

### `nonzerorrand(min,max,p)`
Returns a nonzero real number between min and max in steps of p.
- **Returns**: number

### `randfrom(list or array)`
Return an element of the list/array.
- **Returns**: element
- **Examples**:
  - `randfrom("2,4,6,8")`
  - `randfrom("red,green,blue")`

### `randname()`
Returns a random first name.
- **Returns**: string

### `randmalename()`
Returns a random male first name.
- **Returns**: string

### `randfemalename()`
Returns a random female first name.
- **Returns**: string

### `randnamewpronouns([option])`
Returns a random first name with pronouns in order: subjective, objective, possessive (singular), possessive (plural), reflexive. Set option to 'neutral' for they/them pronouns.
- **Returns**: array
- **Example**: `$name,$heshe,$himher,$hisher,$hishers,$himherself = randnamewpronouns()`

### `uniqid()`
Generates a random unique string, useful for giving a unique id to elements.
- **Returns**: string

## Array Randomizers

### `rands(min,max,n,[order])`
Returns n integers between min and max. order: 'inc' or 'dec' to sort. May have duplicates.
- **Returns**: array

### `rrands(min,max,p,n,[order])`
Returns n real numbers between min and max in steps of p. order: 'inc' or 'dec'.
- **Returns**: array

### `nonzerorands(min,max,n,[order])`
Returns n nonzero integers between min and max.
- **Returns**: array

### `nonzerorrands(min,max,p,n,[order])`
Returns n nonzero real numbers between min and max in steps of p.
- **Returns**: array

### `randsfrom(list/array,n,[order])`
Return n elements of the list/array.
- **Returns**: array

### `jointrandfrom(list/array,list/array,[list/array,...])`
Returns one element from each list, where the location used in each list is the same.
- **Returns**: array

### `diffrands(min,max,n,[order])`
Returns n different integers between min and max.
- **Returns**: array

### `diffrrands(min,max,p,n,[order])`
Returns n different real numbers between min and max in steps of p.
- **Returns**: array

### `diffrandsfrom(list/array,n,[order])`
Return n different elements of the list/array.
- **Returns**: array

### `nonzerodiffrands(min,max,n,[order])`
Returns n different nonzero integers between min and max.
- **Returns**: array

### `nonzerodiffrrands(min,max,p,n,[order])`
Returns n different nonzero real numbers between min and max in steps of p.
- **Returns**: array

### `jointshuffle(list/array1,list/array2,[n1,n2])`
Shuffles two lists/arrays retaining respective order. If n1 provided, returns n1 elements from each. If n2 also provided, n1 from array1 and n2 from array2.
- **Returns**: array

### `singleshuffle(list/array,[n])`
Returns a shuffled version of a list/array. If n provided, behaves like diffrandsfrom.
- **Returns**: array

### `randnames(n)`
Returns n random first names.
- **Returns**: array

### `randmalenames(n)`
Returns n random male first names.
- **Returns**: array

### `randfemalenames(n)`
Returns n random female first names.
- **Returns**: array

### `randcity([country])`
Returns a random US or Canadian city name. country: 'USA' (default) or 'Canada'.
- **Returns**: string

### `randcities(n,[country])`
Returns n random city names.
- **Returns**: array

### `randstate([country])`
Returns a random US state or Canadian province name. country: 'USA' (default) or 'Canada'.
- **Returns**: string

### `randstates(n,[country])`
Returns n random state/province names.
- **Returns**: array

### `randcountry()`
Returns a random country name.
- **Returns**: string

### `randcountries(n)`
Returns n random country names.
- **Returns**: array

### `randpythag([min,max])`
Return a Pythagorean triple. min/max default to 1 to 100.
- **Returns**: array
