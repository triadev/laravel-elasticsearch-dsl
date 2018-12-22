<?php
namespace Tests\Unit\Dsl\Query;

use Tests\TestCase;
use Triadev\Es\Dsl\Dsl\Query\Geo;
use Triadev\Es\Dsl\Model\Location;

class GeoTest extends TestCase
{
    /**
     * @test
     */
    public function it_builds_a_geo_query()
    {
        $result = (new Geo())->filter()
            ->geoShape([])
            ->geoBoundingBox('FIELD', [
                new Location(1, 2),
                new Location(3, 4)
            ])
            ->geoDistance('FIELD', '10km', new Location(1, 2))
            ->geoPolygon('FIELD', [
                new Location(1, 2),
                new Location(3, 4)
            ])->toDsl();
    
        $this->assertEquals([
            'geo_shape' => []
        ], array_get($result, 'query.bool.filter.0'));
    
        $this->assertEquals([
            'geo_bounding_box' => [
                'FIELD' => [
                    'top_left' => [
                        'lat' => 1.0,
                        'lon' => 2.0
                    ],
                    'bottom_right' => [
                        'lat' => 3.0,
                        'lon' => 4.0
                    ]
                ]
            ]
        ], array_get($result, 'query.bool.filter.1'));
    
        $this->assertEquals([
            'geo_distance' => [
                'distance' => '10km',
                'FIELD' => [
                    'lat' => 1.0,
                    'lon' => 2.0
                ]
            ]
        ], array_get($result, 'query.bool.filter.2'));
    
        $this->assertEquals([
            'geo_polygon' => [
                'FIELD' => [
                    'points' => [
                        [
                            'lat' => 1.0,
                            'lon' => 2.0
                        ],
                        [
                            'lat' => 3.0,
                            'lon' => 4.0
                        ]
                    ]
                ]
            ]
        ], array_get($result, 'query.bool.filter.3'));
    }
}
