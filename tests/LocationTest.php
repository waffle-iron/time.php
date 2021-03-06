<?php
/**
 * This file is part of the "litgroup/datetime" package.
 *
 * (c) Roman Shamritskiy <roman@litgroup.ru>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types = 1);

namespace Test\LitGroup\Time;

use LitGroup\Equatable\Equatable;
use LitGroup\Time\Date;
use LitGroup\Time\Location;
use LitGroup\Time\LocationId;
use LitGroup\Time\Month;
use LitGroup\Time\Time;
use LitGroup\Time\Year;
use LitGroup\Time\Zone;
use LitGroup\Time\ZonedDateTime;

class LocationTest extends \PHPUnit_Framework_TestCase
{
    const ID = 'Europe/Moscow';
    const ANOTHER_ID = 'UTC';

    const ZONE_OFFSET = 10800;
    const ZONE_ABBR = 'MSK';
    const ZONE_DST = false;

    const TIMESTAMP = 1470269107;

    /**
     * @var Location
     */
    private $location;

    /**
     * @var LocationId
     */
    private $locationId;

    protected function setUp()
    {
        $this->locationId = new LocationId(self::ID);
        $this->location = Location::ofId($this->locationId);
    }

    /**
     * @test
     */
    public function itHasAnId()
    {
        $this->assertSame($this->getLocationId(), $this->getLocation()->getId());
    }

    /**
     * @test
     */
    public function itCanBeConvertedToString()
    {
        $this->assertSame(self::ID, (string) $this->getLocation());
    }

    /**
     * @test
     * @dataProvider getEqualityExamples
     */
    public function itIsEqualToAnotherOne(bool $equal, Equatable $another)
    {
        $location = $this->getLocation();
        $this->assertInstanceOf(Equatable::class, $location);
        $this->assertSame($equal, $location->equals($another));
    }

    public function getEqualityExamples(): array
    {
        return [
            [true, Location::ofId(new LocationId(self::ID))],
            [false, Location::ofId(new LocationId(self::ANOTHER_ID))],
            [false, $this->createMock(Equatable::class)],
        ];
    }

    /**
     * @test
     */
    public function itProvidesATimeZone()
    {
        $location = $this->getLocation();
        $expectedZone = new Zone(self::ZONE_ABBR, self::ZONE_OFFSET, self::ZONE_DST);

        $this->assertTrue($expectedZone->equals($location->getZone(self::TIMESTAMP)));
    }

    /**
     * @test
     */
    public function itHasAFactoryMethodWithInitializationByRawId()
    {
        $this->assertTrue(
            $this->getLocation()->equals(Location::of(self::ID))
        );
    }

    /**
     * @test
     */
    public function itHasAFactoryMethodForInstantiationOfUtcLocation()
    {
        $location = Location::utc();

        $this->assertInstanceOf(Location::class, $location);
        $this->assertTrue(Location::of('UTC')->equals($location));
    }

    private function getLocation(): Location
    {
        return $this->location;
    }

    private function getLocationId(): LocationId
    {
        return $this->locationId;
    }
}
