<!-- Definició classe Post -->
<?php

require_once(__DIR__ . '/../DL/Database.php');

class Post {

    private $id_publicacio;
    private $contingut;
    private $data;
    private $url_imatge;
    private $likes;
    private $comentaris;
    private $id_usuari;
    private $id_categoria;

    public function __construct($id_publicacio = null, $contingut = '', $data = null, $url_imatge = '', $likes = 0, $comentaris = 0, $id_usuari = null, $id_categoria = null) {
        $this->id_publicacio = $id_publicacio;
        $this->contingut = $contingut;
        $this->data = $data ?? date('Y-m-d H:i:s');
        $this->url_imatge = $url_imatge;
        $this->likes = $likes;
        $this->comentaris = $comentaris;
        $this->id_usuari = $id_usuari;
        $this->id_categoria = $id_categoria;
    }

    public function getIdPost() { return $this->id_publicacio; }
    public function getContingut() { return $this->contingut; }
    public function getData() { return $this->data; }
    public function getImatge() { return $this->url_imatge; }
    public function getLikes() { return $this->likes; }
    public function getNumComentaris() { return $this->comentaris; }
    public function getUser() { return $this->id_usuari; }
    public function getCategoria() { return $this->id_categoria; }

    public static function getAll() {
        $db = new Database();
        return $db->getAllPosts();
    }
}

?>