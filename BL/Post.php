<?php

require_once(__DIR__ . '/../DL/Database.php');

class Post {

    public $id_publicacio;
    public $contingut;
    public $data;
    public $url_imatge;
    public $likes;
    public $id_usuari;
    public $id_categoria;

    public function __construct($id_publicacio = null, $contingut = '', $data = null, $url_imatge = '', $likes = 0, $id_usuari = null, $id_categoria = null) {
        $this->id_publicacio = $id_publicacio;
        $this->contingut = $contingut;
        $this->data = $data ?? date('Y-m-d H:i:s');
        $this->url_imatge = $url_imatge;
        $this->likes = $likes;
        $this->id_usuari = $id_usuari;
        $this->id_categoria = $id_categoria;
    }
}

?>