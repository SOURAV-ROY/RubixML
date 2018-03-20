<?php

use Rubix\Graph\DistanceFunctions\Diagonal;
use Rubix\Graph\DistanceFunctions\DistanceFunction;
use Rubix\Graph\GraphNode;
use PHPUnit\Framework\TestCase;

class DiagonalTest extends TestCase
{
    protected $distanceFunction;

    public function setUp()
    {
        $this->distanceFunction = new Diagonal();
    }

    public function test_build_distance_function()
    {
        $this->assertTrue($this->distanceFunction instanceof Diagonal);
        $this->assertTrue($this->distanceFunction instanceof DistanceFunction);
    }

    public function test_compute_distance()
    {
        $start = new GraphNode(1, ['x' => 2, 'y' => 1]);

        $end = new GraphNode(2, ['x' => 7, 'y' => 9]);

        $this->assertEquals(8.0, round($this->distanceFunction->compute($start, $end, ['x', 'y']), 2));
    }
}