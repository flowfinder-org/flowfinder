<?php
    
    namespace FlowFinder\Models;
    use FlowFinder\Models\Model;

    class GeoIpCache extends Model
    {

        public function __construct()
        {
        }

        public function getGeoIp(string $ip)
        {
            $geoip = $this->requete("SELECT * FROM geo_ip_cache WHERE ip = ?;", [$ip])->fetch();   
            if($geoip === false)
            {
                return null;
            }
            return $geoip;
        }

        public function AjouteGeoIp(string $ip, string $geo_country, string $geo_region, string $geo_city, $geo_latitude, $geo_longitude, $geo_timezone)
        {
            $this->requete("INSERT INTO geo_ip_cache(ip, date_updated, geo_country, geo_region, geo_city, geo_latitude, geo_longitude, geo_timezone)
                VALUES(?, UTC_TIMESTAMP(), ?, ?, ?, ?, ?, ?)", [$ip, $geo_country, $geo_region, $geo_city, $geo_latitude, $geo_longitude, $geo_timezone]);
        }

    }
?>