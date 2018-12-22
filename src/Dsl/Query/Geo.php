<?php
namespace Triadev\Es\Dsl\Dsl\Query;

use ONGR\ElasticsearchDSL\Query\Geo\GeoBoundingBoxQuery;
use ONGR\ElasticsearchDSL\Query\Geo\GeoDistanceQuery;
use ONGR\ElasticsearchDSL\Query\Geo\GeoPolygonQuery;
use ONGR\ElasticsearchDSL\Query\Geo\GeoShapeQuery;
use Triadev\Es\Dsl\Dsl\AbstractDsl;
use Triadev\Es\Dsl\Model\Location;

class Geo extends AbstractDsl
{
    /**
     * Geo bounding box
     *
     * @param string $field
     * @param Location[] $locations
     * @param array $params
     * @return Geo
     */
    public function geoBoundingBox(string $field, array $locations, array $params = []): Geo
    {
        $l = [];
        
        foreach ($locations as $location) {
            if ($location instanceof Location) {
                $l[] = [
                    'lat' => $location->getLatitude(),
                    'lon' => $location->getLongitude()
                ];
            }
        }
        
        return $this->append(new GeoBoundingBoxQuery($field, $l, $params));
    }
    
    /**
     * Geo distance
     *
     * @param string $field
     * @param string $distance
     * @param Location $location
     * @return Geo
     */
    public function geoDistance(string $field, string $distance, Location $location): Geo
    {
        return $this->append(new GeoDistanceQuery(
            $field,
            $distance,
            [
                'lat' => $location->getLatitude(),
                'lon' => $location->getLongitude()
            ]
        ));
    }
    
    /**
     * Geo polygon
     *
     * @param string $field
     * @param Location[] $points
     * @return Geo
     */
    public function geoPolygon(string $field, array $points): Geo
    {
        $p = [];
        
        foreach ($points as $point) {
            if ($point instanceof Location) {
                $p[] = [
                    'lat' => $point->getLatitude(),
                    'lon' => $point->getLongitude()
                ];
            }
        }
        
        return $this->append(new GeoPolygonQuery($field, $p));
    }
    
    /**
     * Geo shape
     *
     * @param array $params
     * @return Geo
     */
    public function geoShape(array $params = []): Geo
    {
        return $this->append(new GeoShapeQuery($params));
    }
}
