# Question Types Reference

All 28 IMathAS question type IDs — where to find them and what they do.

## Type ID Lookup

| Type ID | Multipart Name | Description | File |
|---------|---------------|-------------|------|
| `number` | `number` | Numeric answer (integer, decimal, scientific notation) | [numeric.md](numeric.md) |
| `calculated` | `calculated` | Number or calculation (2/3, 5^2, sin(2)) | [numeric.md](numeric.md) |
| `multiple_choice` | `choices` | Single-select from randomized options | [choice.md](choice.md) |
| `multiple_answer` | `multans` | Multi-select all correct choices | [choice.md](choice.md) |
| `matching` | `matching` | Match items on left to lettered answers on right | [choice.md](choice.md) |
| `function` | `numfunc` | Algebraic expression or equation (any equivalent form) | [formula.md](formula.md) |
| `string` | `string` | Word or text answer with configurable comparison flags | [formula.md](formula.md) |
| `interval` | `interval` | Interval notation, e.g. `(2,5]U(7,oo)` | [formula.md](formula.md) |
| `calcinterval` | `calcinterval` | Interval with calculated values, e.g. `[2/5,sqrt(8)]` | [formula.md](formula.md) |
| `essay` | `essay` | Free-response, NOT auto-graded | [essay-file.md](essay-file.md) |
| `draw` | `draw` | Student draws on coordinate plane | [essay-file.md](essay-file.md) |
| `fileupload` | `file` | Student uploads a file, NOT auto-graded | [essay-file.md](essay-file.md) |
| `numericalmatrix` | `matrix` | Matrix of numbers | [matrix.md](matrix.md) |
| `calculatedmatrix` | `calcmatrix` | Matrix of calculations (fractions, powers) | [matrix.md](matrix.md) |
| `complexmatrix` | `complexmatrix` | Matrix of complex numbers in a+bi form | [matrix.md](matrix.md) |
| `calccomplexmatrix` | `calccomplexmatrix` | Matrix of complex calculations | [matrix.md](matrix.md) |
| `algmatrix` | `algmatrix` | Matrix of algebraic expressions | [matrix.md](matrix.md) |
| `ntuple` | `ntuple` | N-tuple, e.g. `(1,2)` or `<3,4,5>` | [ntuple-complex.md](ntuple-complex.md) |
| `calcntuple` | `calcntuple` | N-tuple with expressions, e.g. `(5/3, 2/3)` | [ntuple-complex.md](ntuple-complex.md) |
| `complexntuple` | `complexntuple` | N-tuple of complex numbers | [ntuple-complex.md](ntuple-complex.md) |
| `calccomplexntuple` | `calccomplexntuple` | N-tuple of complex expressions | [ntuple-complex.md](ntuple-complex.md) |
| `algntuple` | `algntuple` | N-tuple of algebraic expressions, e.g. `(x, x^2)` | [ntuple-complex.md](ntuple-complex.md) |
| `complex` | `complex` | Single complex number in a+bi form | [ntuple-complex.md](ntuple-complex.md) |
| `calccomplex` | `calccomplex` | Complex number with expressions, e.g. `1/3+sqrt(2)i` | [ntuple-complex.md](ntuple-complex.md) |
| `multipart` | — | Container: multiple parts each with own type | [special.md](special.md) |
| `conditional` | — | Container: multiple inputs, single conditional score | [special.md](special.md) |
| `chemeqn` | `chemeqn` | Chemical formula or reaction equation | [special.md](special.md) |
| `molecule` | `molecule` | Molecule sketch via Kekule tool | [special.md](special.md) |

## Files

| File | Types Covered |
|------|--------------|
| [numeric.md](numeric.md) | `number`, `calculated` |
| [choice.md](choice.md) | `multiple_choice`, `multiple_answer`, `matching` |
| [formula.md](formula.md) | `function`/`numfunc`, `string`, `interval`, `calcinterval` |
| [essay-file.md](essay-file.md) | `essay`, `draw`, `fileupload` |
| [matrix.md](matrix.md) | `numericalmatrix`, `calculatedmatrix`, `complexmatrix`, `calccomplexmatrix`, `algmatrix` |
| [ntuple-complex.md](ntuple-complex.md) | `ntuple`, `calcntuple`, `complexntuple`, `calccomplexntuple`, `algntuple`, `complex`, `calccomplex` |
| [special.md](special.md) | `multipart`, `conditional`, `chemeqn`, `molecule` |

