# String Macros

### `stringappend(value,string)`
Appends string to value. If value is array, appends to each element.
- **Returns**: string/array

### `stringprepend(value,string)`
Prepends string to value. If value is array, prepends to each element.
- **Returns**: string/array

### `today([string])`
Returns today's date like `'July 3, 2008'`. Change format using PHP date format string.
- **Returns**: string

### `stringpos(needle,haystack)`
Finds position of needle in haystack. Returns -1 if not found.
- **Returns**: integer

### `stringlen(string)`
Returns character count in the string.
- **Returns**: integer

### `stringclean(string,[mode])`
mode 0 (default): trims whitespace. mode 1: removes all whitespace. mode 2: removes all non-alphanumeric.
- **Returns**: string

### `substr(string,start,[length])`
Grabs part of string from start index. Negative start counts from end. Negative length omits from end.
- **Returns**: string

### `strtoupper(string)`
Makes all characters upper case.
- **Returns**: string

### `ucfirst(string)`
Makes first character upper case.
- **Returns**: string

### `strtolower(string)`
Makes all characters lower case.
- **Returns**: string

### `lcfirst(string)`
Makes first character lower case.
- **Returns**: string

### `substr_count(haystack,needle)`
Find number of occurrences of needle in haystack.
- **Returns**: integer

### `str_replace(search,replace,string)`
Replace all occurrences of search with replace. Search and replace can be arrays.
- **Returns**: string

### `preg_match(pattern,subject,[matches])`
Regex search. Returns true/false, stores match in matches.
- **Returns**: boolean

### `preg_match_all(pattern,subject,[matches,flags,offset])`
Regex search for multiple matches. Returns number of matches.
- **Returns**: integer

### `preg_replace(pattern,replacement,subject,[limit])`
Regex search and replace.
- **Returns**: string
