<?php

namespace FlowFinder\Helper;

use FlowFinder\Models\GeoIpCache;
use FlowFinder\Models\VisiteurSession;

class GeoIpLocator
{
    public $ip = null;
    public $geo_country = null;
    public $geo_region = null;
    public $geo_city = null;
    public $geo_latitude = null;
    public $geo_longitude = null;
    public $geo_timezone = null;

    public function __construct($ip) {
        $this->ip = $ip;
    }

    public function load()
    {
        // Utilisation d'un parser d'user agent comme UAParser
        if(!$this->getByDBCache())
        {
            if($this->getByMaxmind())
            {
                $this->addToDBCache();
                return true;
            }
            else
            {
                return false;
            }
        }
        return true;
    }

    public static function CalculGeoIpManquantes()
    {
        $modelVisiteurSession = new VisiteurSession();
        $sessions = $modelVisiteurSession->getAllVisiteurSessionsSansGeoIp();
        foreach($sessions as $session)
        {
            if($session->user_ip != null && $session->user_ip != "")
            {
                $geoIpLocator = new GeoIpLocator($session->user_ip);
                $geoIpLocator->load();
                $modelVisiteurSession->updateGeoIp($session->id_visiteur_session, $geoIpLocator->geo_country, $geoIpLocator->geo_region, $geoIpLocator->geo_city, $geoIpLocator->geo_latitude, $geoIpLocator->geo_longitude, $geoIpLocator->geo_timezone);
            }
        }
    }

    private function getByDBCache() 
    {
        // Vérifier si l'IP est locale ou privée
        if ($this->isLocalOrPrivateIp($this->ip)) {
            // Si l'IP est locale ou privée, attribuer des valeurs par défaut
            $this->geo_country = "local";
            $this->geo_region = "local";
            $this->geo_city = "local";
            $this->geo_latitude = 0.0;
            $this->geo_longitude = 0.0;
            $this->geo_timezone = "Europe/Paris";
            return true;
        }

        $modelGeoIpCache = new GeoIpCache();
        $ip_info = $modelGeoIpCache->getGeoIp($this->ip);
        if($ip_info != null)
        {
            $this->geo_country = $ip_info->geo_country;
            $this->geo_region = $ip_info->geo_region;
            $this->geo_city = $ip_info->geo_city;
            $this->geo_latitude = $ip_info->geo_latitude;
            $this->geo_longitude = $ip_info->geo_longitude;
            $this->geo_timezone = $ip_info->geo_timezone;
            return true;
        }
        return false;
    }

    private function addToDBCache()
    {
        $modelGeoIpCache = new GeoIpCache();
        $modelGeoIpCache->AjouteGeoIp($this->ip, $this->geo_country, $this->geo_region, $this->geo_city, $this->geo_latitude, $this->geo_longitude, $this->geo_timezone);
    }

    private function getByMaxmind()
    {
        if(!defined(MAXMIND_GEOIP_ACCOUND_ID) || MAXMIND_GEOIP_ACCOUND_ID == "")
        {
            return;
        }

        $url = "https://geoip.maxmind.com/geoip/v2.1/city/{$this->ip}?pretty";

        // Initialise cURL
        $ch = curl_init();

        // Défini les options cURL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, MAXMIND_GEOIP_ACCOUND_ID . ":" . MAXMIND_GEOIP_LICENSE_KEY);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        // Exécute la requête
        $response = curl_exec($ch);

        // Vérifie si la requête a échoué
        if ($response === false) {
            error_log("Erreur cURL lors de l'appel au service web de maxmind geoip: " . curl_error($ch));

            // Ferme la session cURL
            curl_close($ch);

            return false;
        } else {
            // Parse la réponse JSON et affiche les données
            $data = json_decode($response, true);

            if(isset($data['code']) && ($data['code'] == "IP_ADDRESS_NOT_FOUND" || $data['code'] == "IP_ADDRESS_RESERVED"))
            {
                $this->geo_country = "?";
                $this->geo_region = "?";
                $this->geo_city = "?";
                $this->geo_latitude = 0.0;
                $this->geo_longitude = 0.0;
                $this->geo_timezone = "Europe/Paris";
            }
            else if(!isset($data['country']) && isset($data['traits']['isp']))
            {
                // une ip qui appartien à une organisation que n'a pas déclaré sa géolocalisation , ex; certaines ip cloudflare
                $this->geo_country = "?";
                $this->geo_region = "?";
                $this->geo_city = "?";
                $this->geo_latitude = 0.0;
                $this->geo_longitude = 0.0;
                $this->geo_timezone = "Europe/Paris";
            }
            else
            {

                $this->geo_country = $data['country']['names']['en'];
                if(isset($data['subdivisions']))
                {
                    $this->geo_region = $data['subdivisions'][0]['names']['en'];
                }
                if($this->geo_region == null)
                {
                    $this->geo_region = "";
                }
                if(isset($data['city']))
                {
                    $this->geo_city = $data['city']['names']['en'];
                }
                if($this->geo_city == null)
                {
                    $this->geo_city = "";
                }
                $this->geo_latitude = $data['location']['latitude'];
                $this->geo_longitude = $data['location']['longitude'];
                $this->geo_timezone = $data['location']['time_zone'];
            }
            // Ferme la session cURL
            curl_close($ch);
        
            return true;
        }

    }

    private function isLocalOrPrivateIp($ip) {
        // Vérifie si l'IP est valide (IPv4 ou IPv6)
        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            return false;
        }

        // Plages d'IP privées IPv4
        $privateRanges = [
            ['0.0.0.0', '0.255.255.255'], // Plage non utilisable 0.0.0.0 à 0.255.255.255
            ['10.0.0.0', '10.255.255.255'],     // Plage privée 10.0.0.0 à 10.255.255.255
            ['172.16.0.0', '172.31.255.255'],   // Plage privée 172.16.0.0 à 172.31.255.255
            ['192.168.0.0', '192.168.255.255'], // Plage privée 192.168.0.0 à 192.168.255.255
            ['127.0.0.1', '127.255.255.255'],         // Loopback
            ['169.254.0.0', '169.254.255.255'], // APIPA
            ['224.0.0.0' , '239.255.255.255'], // Adresses multicast
            ['240.0.0.0', '255.255.255.255'] // réservé pour la documentation et autres usages
        ];

        // Vérifier si l'IP est dans l'une des plages privées IPv4
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $ipLong = ip2long($ip);
            foreach ($privateRanges as $range) {
                $start = ip2long($range[0]);
                $end = ip2long($range[1]);
                if ($ipLong >= $start && $ipLong <= $end) {
                    return true;
                }
            }
        }

        // Plages d'IP privées IPv6
        // Vérifie ici si l'IP est dans les plages privées IPv6 comme fc00::/7 ou fe80::/10
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            // Exemple simplifié, à étendre pour une plus grande précision
            $ipv6Parts = explode(":", $ip);
            if ($ipv6Parts[0] == 'fc00' || $ipv6Parts[0] == 'fe80') {
                return true;
            }
        }

        // Si aucune des conditions n'est remplie, l'IP n'est pas locale ou privée
        return false;
    }
}

?>