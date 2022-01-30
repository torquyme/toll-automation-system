<?php
/**
 * RouteTest.php
 */

namespace App\Models;

class RouteTest extends \TestCase
{

    public function testIsValidThreeStations()
    {
        $station = new Station();

        $route = new Route();
        $route->addStation($station)->addStation($station)->addStation($station);
        $this->assertTrue($route->isValid());
        $this->assertCount(3, $route->getStations());
    }

    public function testIsValidNoStations()
    {
        $station = new Station();

        $route = new Route();
        $route->addStation($station);
        $this->assertFalse($route->isValid());
        $this->assertCount(1, $route->getStations());
    }
}
