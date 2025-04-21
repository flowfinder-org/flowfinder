<?php

namespace FlowFinder\Models;

use FlowFinder\Models\Model;

class VisiteurSession extends Model
{
	/*
	id_visiteur_session INT AUTO_INCREMENT PRIMARY KEY,
    id_visiteur INT NOT NULL,
    visiteurr_session_uuid VARCHAR(64) NOT NULL UNIQUE,
    date_creation DATETIME
	*/

	public function __construct()
	{
	}

    public function getVisiteurSessionParId(int $id_visiteur_session)
    {
        return $this->requete("SELECT * FROM visiteur_sessions WHERE id_visiteur_session = ? ", [$id_visiteur_session])->fetch();
    }

    public function getVisiteurSessionMaxSequenceIdx(int $id_visiteur, string $visiteur_session_uuid)
    {
        return $this->requete("SELECT Max(sequence_index) as max_sequence_index FROM visiteur_sessions WHERE id_visiteur = ? AND visiteur_session_uuid = ? ", [$id_visiteur, $visiteur_session_uuid])->fetch();
    }

    public function getVisiteurSessionsParSessionUUID(string $visiteur_session_uuid)
    {
        return $this->requete("SELECT * FROM visiteur_sessions WHERE visiteur_session_uuid = ? ORDER BY date_creation ASC ", [$visiteur_session_uuid])->fetchAll();
    }

    public function getVisiteurSessionParUUIDEtSequenceIndex(string $visiteur_session_uuid, int $sequence_idx)
    {
        return $this->requete("SELECT * FROM visiteur_sessions WHERE visiteur_session_uuid = ? AND sequence_index = ?", [$visiteur_session_uuid, $sequence_idx])->fetch();
    }
    
    public function addVisiteurSession(int $id_visiteur, string $visiteur_session_uuid, int $visiteur_session_seq_idx, string $url, 
    string $user_agent, $os_family, $os_version, $device_type, $browser_family, $browser_version, $device_brand, $device_model,
    string $user_ip, 
    $utm_source, $utm_medium, $utm_campaign, $utm_term, $utm_content, $fbclid, $referer,
    $screen_width, $screen_height, $window_width, $window_height, $pixel_ratio, $orientation)
    {
        $this->requete("INSERT INTO visiteur_sessions(
            date_creation, id_visiteur, visiteur_session_uuid, sequence_index, url, 
            user_agent, os_family, os_version, device_type, browser_family, browser_version, device_brand, device_model,
            user_ip, 
            utm_source, utm_medium, utm_campaign, utm_term, utm_content, fbclid, referer, 
            screen_width, screen_height, window_width, window_height, pixel_ratio, orientation,
            seconds_active) VALUES(
                UTC_TIMESTAMP(), ?, ?, ?, ?,
                ?, ?, ?, ?, ?, ?, ?, ?,
                ?,
                ?, ?, ?, ?, ?, ?, ?,
                ?, ?, ?, ?, ?, ?,
                0)", [$id_visiteur, $visiteur_session_uuid, $visiteur_session_seq_idx, $url,
                $user_agent, $os_family, $os_version, $device_type, $browser_family, $browser_version, $device_brand, $device_model,
                $user_ip, 
                $utm_source, $utm_medium, $utm_campaign, $utm_term, $utm_content, $fbclid, $referer,
                $screen_width, $screen_height, $window_width, $window_height, $pixel_ratio, $orientation]);
        return $this->getLastInsertedId();
    }

	public function markAlive(int $id_visiteur_session)
	{
		$this->requete("UPDATE visiteur_sessions SET seconds_active = (UTC_TIMESTAMP()-date_creation) WHERE id_visiteur_session = ?;", [$id_visiteur_session]);
	}

    public function getVisiteursSessionsPourUtilisateur($id_utilisateur, $start_index, $limit)
    {
        return $this->requete("SELECT * 
            FROM collections_utilisateurs cu
            LEFT JOIN visiteurs v ON cu.id_collection = v.id_collection
            LEFT JOIN visiteur_sessions vs ON v.id_visiteur = vs.id_visiteur
            WHERE cu.id_utilisateur = ?
            ORDER BY vs.date_creation DESC
            LIMIT " . $start_index . ", " . $limit  . ";
            ", [$id_utilisateur,])->fetchAll();
    }

    public function getVisiteursSessionsPourUtilisateurFiltre($id_utilisateur, $filtre_utm_campaign, $date_start, $date_end)
    {
        if($filtre_utm_campaign != "")
        {
            // on élimine les bots et l'équipe d'admin
            return $this->requete("SELECT * 
            FROM collections_utilisateurs cu
            LEFT JOIN visiteurs v ON cu.id_collection = v.id_collection
            LEFT JOIN visiteur_sessions vs ON v.id_visiteur = vs.id_visiteur
            WHERE cu.id_utilisateur = ? AND vs.is_bot = 0 AND is_team = 0
            AND vs.utm_campaign = ?
            AND vs.date_creation BETWEEN ? AND ?
            ORDER BY vs.date_creation DESC;
            ", [$id_utilisateur, $filtre_utm_campaign, $date_start, $date_end])->fetchAll();
        }
        else
        {
            return $this->requete("SELECT * 
            FROM collections_utilisateurs cu
            LEFT JOIN visiteurs v ON cu.id_collection = v.id_collection
            LEFT JOIN visiteur_sessions vs ON v.id_visiteur = vs.id_visiteur
            WHERE cu.id_utilisateur = ?  AND vs.is_bot = 0 AND is_team = 0
            AND vs.date_creation BETWEEN ? AND ?
            ORDER BY vs.date_creation DESC;
            ", [$id_utilisateur, $date_start, $date_end])->fetchAll();
        }
        
    }

    public function getAllVisiteurSessionsSansOSFamily()
    {
        return $this->requete("SELECT * 
            FROM visiteur_sessions
            WHERE os_family IS NULL;
            ", [])->fetchAll();
    }

    public function getAllVisiteurSessionsSansGeoIp()
    {
        return $this->requete("SELECT * 
            FROM visiteur_sessions
            WHERE geo_country IS NULL;
            ", [])->fetchAll();
    }

    public function updateUserAgentDetection($id_visiteur_session, $os_family, $os_version, $device_type, $browser_family, $browser_version, $device_brand, $device_model)
    {
        $this->requete("UPDATE visiteur_sessions SET os_family = ?, os_version = ?, device_type = ?, browser_family = ?, browser_version = ?, device_brand = ?, device_model = ? WHERE id_visiteur_session = ?;", [$os_family, $os_version, $device_type, $browser_family, $browser_version, $device_brand, $device_model, $id_visiteur_session]);
    }

    public function updateGeoIp($id_visiteur_session, $geo_country, $geo_region, $geo_city, $geo_latitude, $geo_longitude, $geo_timezone)
    {
        $this->requete("UPDATE visiteur_sessions SET geo_country = ?, geo_region = ?, geo_city = ?, geo_latitude = ?, geo_longitude = ?, geo_timezone = ? WHERE id_visiteur_session = ?;", [$geo_country, $geo_region, $geo_city, $geo_latitude, $geo_longitude, $geo_timezone, $id_visiteur_session]);
    }

    public function DetecteBotEtTeam()
    {
        $this->requete("UPDATE visiteur_sessions SET is_bot=1 WHERE id_visiteur_session > 0 AND
            (  
            user_ip LIKE '173.252.127.%'
            OR user_ip LIKE '31.13.127.%'
            OR user_ip LIKE '31.13.115.%'
            OR user_ip LIKE '173.252.87.%'
            OR user_ip LIKE '69.171.251.%'
            OR user_ip LIKE '173.252.95.%'
            OR user_ip LIKE '173.252.107.%'
            OR user_ip LIKE '66.220.149.%'
            OR user_ip LIKE '69.63.184.%'
            OR user_ip LIKE '52.168.53.%'
            OR user_ip LIKE '44.245.199.%'
            OR user_ip LIKE '54.170.122.%'
            OR user_ip LIKE '205.169.39.%'
            OR user_ip LIKE '104.197.69.%'
            OR user_ip LIKE '34.71.214.%'
            OR user_ip LIKE '54.70.53.%'
            OR user_ip LIKE '66.249.89.%'
            OR user_ip LIKE '66.249.64.%'
            OR user_ip LIKE '66.249.76.%'
            OR user_ip LIKE '66.249.75.%'
            OR user_ip LIKE '202.8.42.%'
            OR user_ip LIKE '5.255.231.%'
            OR user_ip LIKE '66.249.66.%'
            OR user_ip LIKE '66.249.68.%'
            OR user_ip LIKE '202.8.43.%'
            OR user_ip LIKE '40.77.189.%'
            OR user_ip LIKE '42.236.101.%'
            OR user_ip LIKE '42.236.17.%'
            OR user_ip LIKE '42.236.12.%'
            OR user_ip LIKE '17.246.15.%'
            OR user_ip LIKE '40.77.188.%'
            OR user_ip LIKE '66.249.92.%'
            OR user_ip LIKE '74.125.150.%'
            OR user_ip LIKE '66.249.79.%'
            OR user_ip LIKE '72.14.199.%'
            OR user_ip LIKE '87.250.224.%'
            OR user_ip LIKE '17.246.19.%'
            OR user_ip LIKE '66.249.65.%'
            OR user_ip LIKE '66.249.72.%'
            OR user_ip LIKE '74.125.216.%'
            OR user_ip LIKE '40.77.167.%'
            OR user_ip LIKE '17.241.219.%'
            OR user_ip LIKE '66.249.78.%'
            OR user_ip LIKE '17.22.253.%'
            OR user_ip LIKE '17.241.75.%'
            OR user_ip LIKE '66.249.74.%'
            OR user_ip LIKE '40.77.202.%'
            OR user_ip LIKE '17.241.227.%'
            OR user_ip LIKE '69.171.231.%'
            OR user_ip LIKE '40.77.190.%'
            OR user_ip LIKE '202.8.40.%'
            OR user_ip LIKE '17.22.245.%'
            OR user_ip LIKE '17.246.23.%'
            OR user_ip LIKE '17.22.237.%'
            OR user_ip LIKE '3.142.50.%'
            OR user_ip LIKE '202.8.41.%'
            OR user_ip LIKE '34.94.135.%'
            OR user_ip LIKE '3.145.77.%'
            OR user_ip LIKE '209.85.238.%'
            OR user_ip LIKE '52.167.144.%'
            OR user_ip LIKE '3.142.240.%'
            OR user_ip LIKE '3.138.183.%'
            OR user_ip LIKE '34.45.139.%'
            OR user_ip LIKE '3.19.67.%'
            OR user_ip LIKE '18.117.85.%'
            OR user_ip LIKE '3.16.109.%'
            OR user_ip LIKE '3.149.29.%'
            OR user_ip LIKE '3.135.185.%'
            OR user_ip LIKE '3.145.196.%'
            OR user_ip LIKE '18.189.28.%'
            OR user_ip LIKE '13.59.85.%'
            OR user_ip LIKE '18.118.171.%'
            OR user_ip LIKE '64.71.131.%'
            OR user_ip LIKE '18.191.4.%'
            OR user_ip LIKE '18.224.64.%'
            OR user_ip LIKE '18.191.166.%'
            OR user_ip LIKE '3.141.104.%'
            OR user_ip LIKE '3.138.86.%'
            OR user_ip LIKE '18.222.254.%'
            OR user_ip LIKE '18.191.122.%'
            OR user_ip LIKE '3.16.214.%'
            OR user_ip LIKE '3.143.244.%'
            OR user_ip LIKE '34.16.67.%'
            OR user_ip LIKE '3.144.8.%'
            OR user_ip LIKE '3.142.220.%'
            OR user_ip LIKE '34.169.33.%'
            OR user_ip LIKE '135.181.138.%'
            OR user_ip LIKE '3.141.14.%'
            OR user_ip LIKE '157.55.39.%'
            OR user_ip LIKE '3.145.177.%'
            OR user_ip LIKE '185.117.225.%'
            OR user_ip LIKE '34.21.9.%'
            );");

        $this->requete("
            UPDATE visiteur_sessions SET is_team=1 WHERE id_visiteur_session > 0 AND
            (
            user_ip LIKE '127.0.0.1'
            );");
    }

}