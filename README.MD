# Array wildcard explainer [![Build Status](https://travis-ci.com/XAKEPEHOK/array-wildcard-explainer.svg?branch=master)](https://travis-ci.com/XAKEPEHOK/Path)
This helper takes any complicated array and dot-styled wildcard path for passed array, and return new array with all keys,
that match passed wildcard path. See examples below.

### Installation:
```bash
composer require xakepehok/array-wildcard-explainer
```

## Usage
```php
<?php
use \XAKEPEHOK\ArrayWildcardExplainer\ArrayWildcardExplainer;

$array = [
    'direct' => 1,
    'nested' => [
        'value_1' => 11,
        'value_2' => 22,
        'value_3' => [
            ['value' => 111],
            ['value' => 222],
        ],
    ]
];

print_r(ArrayWildcardExplainer::explainOne($array, 'direct'))
/* will print
 *  [
 *    'direct'
 *  ]
 */
 

print_r(ArrayWildcardExplainer::explainOne($array, 'nested.*'))
/* will print
 *  [
 *    'nested.value_1',
 *    'nested.value_2',
 *    'nested.value_3',
 *  ]
 */
 

print_r(ArrayWildcardExplainer::explainOne($array, 'nested.value_3'))
/* will print
 *  [
 *    'nested.value_3'
 *  ]
 */
 

print_r(ArrayWildcardExplainer::explainOne($array, 'nested.*.*.value'))
/* will print
 *  [
 *    'nested.value_3.0.value',
 *    'nested.value_3.1.value'
 *  ]
 */
```