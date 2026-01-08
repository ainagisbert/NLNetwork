<!-- Definició classe Comentari -->
<?php

require_once(__DIR__ . '/../DL/Database.php');

class Comentari {

    private $id_comentari;
    private $contingut;
    private $data;
    private $likes;
    private $id_usuari;
    private $id_publicacio;

    public function __construct($id_comentari = null, $contingut = '', $data = null, $likes = 0, $id_usuari = null, $id_publicacio = null) {
        $this->id_comentari = $id_comentari;
        $this->contingut = $contingut;
        $this->data = $data;
        $this->likes = $likes;
        $this->id_usuari = $id_usuari;
        $this->id_publicacio = $id_publicacio;
    }

    public function getIdCom() { return $this->id_comentari; }
    public function getContingut() { return $this->contingut; }
    public function getData() { return $this->data; }
    public function getLikes() { return $this->likes; }
    public function getUser() { return $this->id_usuari; }

    public static function getAll() {
        $db = new Database();
        return $db->getAllComments();
    }
}

?>