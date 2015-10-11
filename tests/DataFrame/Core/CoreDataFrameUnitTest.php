<?php namespace Archon\Tests\DataFrame\Core;

use Archon\DataFrame;

class CoreDataFrameUnitTest extends \PHPUnit_Framework_TestCase {

    public function setUp() {
        $this->input = [
            ['a' => 1, 'b' => 2, 'c' => 3],
            ['a' => 4, 'b' => 5, 'c' => 6],
            ['a' => 7, 'b' => 8, 'c' => 9],
        ];

        $this->df = DataFrame::fromArray($this->input);
    }

    public function testFromArray() {
        $this->assertEquals($this->input, $this->df->toArray());
    }

    public function testColumns() {
        $this->assertEquals(['a', 'b', 'c'], $this->df->columns());
    }

    public function testForEach() {
        foreach($this->df as $i => $row) {
            $this->assertEquals($row, $this->input[$i]);
        }
    }

    public function testOffsetGet() {
        $a = $this->df['a'];
        $b = $this->df['b'];

        $this->assertEquals([['a' => 1], ['a' => 4], ['a' => 7]], $a->toArray());
        $this->assertEquals([['b' => 2], ['b' => 5], ['b' => 8]], $b->toArray());
    }

    public function testOffsetSet_value() {
        $df = $this->df;
        $df['a'] = 321;

        $expected = [
            ['a' => 321, 'b' => 2, 'c' => 3],
            ['a' => 321, 'b' => 5, 'c' => 6],
            ['a' => 321, 'b' => 8, 'c' => 9],
        ];

        $this->assertEquals($expected, $df->toArray());
    }

    public function testOffsetSet_closure() {
        $df = $this->df;

        $add = function($x) {
            return function($y) use ($x) {
                return $x + $y;
            };
        };

        $df['a'] = $add(10);
        $df['b'] = $add(20);
        $df['c'] = $add(30);

        $expected = [
            ['a' => 11, 'b' => 22, 'c' => 33],
            ['a' => 14, 'b' => 25, 'c' => 36],
            ['a' => 17, 'b' => 28, 'c' => 39],
        ];

        $this->assertEquals($expected, $df->toArray());
    }

    public function testOffsetSet_dataframe() {
        $df = $this->df;

        $df['a'] = $df['b'];

        $expected = [
            ['a' => 2, 'b' => 2, 'c' => 3],
            ['a' => 5, 'b' => 5, 'c' => 6],
            ['a' => 8, 'b' => 8, 'c' => 9],
        ];

        $this->assertEquals($expected, $df->toArray());
    }

}
