<?php

namespace XAKEPEHOK\ArrayWildcardExplainer;

use PHPUnit\Framework\TestCase;

class ArrayWildcardExplainerTest extends TestCase
{

    private array $data;

    protected function setUp(): void
    {
        parent::setUp();
        $this->data = [
            'direct' => 1,
            'nested' => [
                'value_1' => 11,
                'value_2' => 22,
                'value_3' => [
                    ['value' => 111],
                    ['value' => 222],
                ],
            ],
            'array' => [
                [
                    'value_1' => 1111,
                    'value_2' => 2222,
                ],
                [
                    'value_1' => 1111,
                    'value_2' => 2222,
                ],
                [
                    'value_1' => 1111,
                    'value_2' => 2222,
                    'value_3' => [
                        [
                            'value_1' => 11111,
                            'value_2' => 22222,
                        ],
                        [
                            'value_1' => 11111,
                            'value_2' => 22222,
                        ],
                    ],
                ],
                [
                    'value_1' => 11111,
                    'value_2' => 22222,
                    'value_3' => [
                        [
                            'value_1' => 111111,
                            'value_2' => 222222,
                        ],
                        [
                            'value_1' => 111111,
                            'value_2' => 222222,
                        ],
                    ],
                ],
                'value_6' => [
                    [
                        'filter' => [111],
                    ],
                ],
            ],
        ];
    }

    public function expandDataProvider(): array
    {
        $data = [
            [
                'direct',
                [
                    'direct',
                ]
            ],
            [
                'nested.*',
                [
                    'nested.value_1',
                    'nested.value_2',
                    'nested.value_3',
                ]
            ],
            [
                'nested.value_3',
                [
                    'nested.value_3',
                ]
            ],
            [
                'nested.value_3.*',
                [
                    'nested.value_3.0',
                    'nested.value_3.1',
                ]
            ],
            [
                'nested.*.*.value',
                [
                    'nested.value_3.0.value',
                    'nested.value_3.1.value',
                ]
            ],
            [
                'array.*.value_3',
                [
                    'array.2.value_3',
                    'array.3.value_3',
                ]
            ],
            [
                'array.*.value_3.*.value_1',
                [
                    'array.2.value_3.0.value_1',
                    'array.2.value_3.1.value_1',
                    'array.3.value_3.0.value_1',
                    'array.3.value_3.1.value_1',
                ]
            ],
            [
                'array.value_6.*.filter',
                [
                    'array.value_6.0.filter',
                ]
            ]
        ];

        return array_combine(array_column($data, 0), $data);
    }

    /**
     * @dataProvider expandDataProvider
     * @param $input
     * @param $expected
     */
    public function testExpandOne($input, $expected)
    {
        $this->assertEquals($expected, ArrayWildcardExplainer::explainOne($this->data, $input));
    }
}