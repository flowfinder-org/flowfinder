<?php
/**
 * Objet Utilisateur FlowFinder de base
 */

namespace FlowFinder\Models;

use FlowFinder\Models\Model;

class Utilisateur extends Model
{
	
	public function __construct()
	{
	}

	public function chercheUtilisateurParId(int $user_id)
	{
		return $this->getDummyUser();
	}

    public function chercheUtilisateurParUsername(string $username)
    {
        return $this->getDummyUser();
    }

	private function getDummyUser()
	{
		return (object)[
			"password"=> password_hash("admin", PASSWORD_ARGON2I),
			"id_utilisateur" => 1,
			"prenom" => "admin",
			"nom" => "admin",
 			"email" => "admin@dummy",
			"date_paiement" => new \DateTime('2025-04-21'),
			"statut_paiement" => 1,
			"photo_disponible" => 0
		];
	}
}
