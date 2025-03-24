<?php

namespace XAKEPEHOK\ArrayWildcardExplainer;

use PHPUnit\Framework\TestCase;

class ArrayWildcardExplainerTest extends TestCase
{

    private array $data;
    private array $dataSimple;

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

        $this->dataSimple = [
            'direct' => 1,
            'nested' => [
                'value_1' => 11,
                'value_2' => [
                    ['value' => 111],
                    ['value' => 222],
                ],
            ],
        ];
    }

    public function explainDataProvider(): array
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
     * @dataProvider explainDataProvider
     * @param $input
     * @param $expected
     */
    public function testExplainOne($input, $expected)
    {
        $this->assertEquals($expected, ArrayWildcardExplainer::explainOne($this->data, $input));
    }

    public function explainInverseOneDataProvider(): array
    {
        $data = [
            [
                'direct',
                [
                    'nested.value_1',
                    'nested.value_2.0.value',
                    'nested.value_2.1.value',
                ]
            ],
            [
                'nested.*',
                [
                    'direct',
                    'nested.value_2.0.value',
                    'nested.value_2.1.value',
                ]
            ],
            [
                'nested.value_2',
                [
                    'direct',
                    'nested.value_1',
                    'nested.value_2.0.value',
                    'nested.value_2.1.value',
                ]
            ],
            [
                'nested.*.*.value',
                [
                    'direct',
                    'nested.value_1',
                ]
            ],
        ];

        return array_combine(array_column($data, 0), $data);
    }

    /**
     * @dataProvider explainInverseOneDataProvider
     * @param $input
     * @param $expected
     */
    public function testExplainInverseOne($input, $expected)
    {
        $this->assertEquals($expected, ArrayWildcardExplainer::explainOne($this->dataSimple, $input, true));
    }

    public function explainInverseManyDataProvider(): array
    {
        $data = [
            [
                ['direct', 'nested.*'],
                [
                    'nested.value_2.0.value',
                    'nested.value_2.1.value'
                ]
            ],
            [
                ['nested.*', 'nested.value_2'],
                [
                    'direct',
                    'nested.value_2.0.value',
                    'nested.value_2.1.value',
                ]
            ],
            [
                ['direct', 'nested.value_2'],
                [
                    'nested.value_1',
                    'nested.value_2.0.value',
                    'nested.value_2.1.value',
                ]
            ],
            [
                ['nested.value_2', 'nested.*.*.value'],
                [
                    'direct',
                    'nested.value_1',
                ]
            ],
        ];

        return array_combine(array_map('json_encode', array_column($data, 0)), $data);
    }

    /**
     * @dataProvider explainInverseManyDataProvider
     * @param $input
     * @param $expected
     */
    public function testExplainInverseMany($input, $expected)
    {
        $this->assertEquals($expected, ArrayWildcardExplainer::explainMany($this->dataSimple, $input, true));
    }
}