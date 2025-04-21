<?php
    
    namespace FlowFinder\Models;
    use FlowFinder\Models\Model;

    class Declencheur extends Model
    {

        public function __construct()
        {
        }

        public function AjouteDeclencheur(int $id_collection, int $type_declencheur, int $type_page, int $id_page_configuration, string $url_regexp, int $seconds_delay, string $inject_into_elem_id)
        {
            $this->requete_insert("INSERT INTO declencheurs(id_collection, type_declencheur, type_page, id_page_configuration, url_regexp, seconds_delay, inject_into_elem_id) VALUES(?, ?, ?, ?, ?, ?, ?)", [$id_collection, $type_declencheur, $type_page, $id_page_configuration,  $url_regexp, $seconds_delay, $inject_into_elem_id]);
            return $this->getLastInsertedId();
        }

        public function SupprimeDeclencheur(int $id_collection, int $id_declencheur)
        {
            $this->requete("DELETE FROM declencheurs WHERE id_declencheur = ? AND id_collection = ?;", [$id_declencheur, $id_collection]);
        }

        public function UpdateDeclencheur(int $id_collection, int $id_declencheur, int $type_page, int $id_page_configuration, string $url_regexp, int $seconds_delay, string $inject_into_elem_id)
        {
            $this->requete("UPDATE declencheurs SET type_page = ?, id_page_configuration = ?, url_regexp = ?, seconds_delay = ?, inject_into_elem_id = ? WHERE id_declencheur = ? AND id_collection = ?;", [$type_page, $id_page_configuration, $url_regexp, $seconds_delay, $inject_into_elem_id, $id_declencheur, $id_collection]);
        }

        public function SelectAllDeclencheursPourCollectionParTypeDeclencheur(int $id_collection, int $type_declencheur)
        {
           return  $this->requete("SELECT * FROM declencheurs WHERE id_collection = ? AND type_declencheur = ?;", [$id_collection, $type_declencheur])->fetchAll();
        }
        
        public function SelectDeclencheurParIdEtIdCollection(int $id_declencheur, int $id_collection)
        {
           return  $this->requete("SELECT * FROM declencheurs WHERE id_collection = ? AND id_declencheur = ?;", [$id_collection, $id_declencheur])->fetch();
        }
    }
?>