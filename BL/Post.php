<!-- Definició classe Post -->
<?php

require_once(__DIR__ . '/../DL/Database.php');
require_once('Comentari.php');

class Post {

    private $id_publicacio;
    private $contingut;
    private $data;
    private $url_imatge;
    private $likes;
    private $numComentaris;
    private $id_usuari;
    private $id_categoria;
    private $comentaris = [];

    public function __construct($id_publicacio = null, $contingut = '', $data = null, $url_imatge = '', $numComentaris = 0, $id_usuari = null, $id_categoria = null) {
        $this->id_publicacio = $id_publicacio;
        $this->contingut = $contingut;
        $this->data = $data;
        $this->url_imatge = $url_imatge;
        $this->numComentaris = $numComentaris;
        $this->id_usuari = $id_usuari;
        $this->id_categoria = $id_categoria;
    }

    public function getIdPost() { return $this->id_publicacio; }
    public function getContingut() { return $this->contingut; }
    public function getData() { return $this->data; }
    public function getImatge() { return $this->url_imatge; }
    public function getLikes() { 
        $db = new Database();
        return $db->countLikesPost($this->id_publicacio);
    }
    public function getNumComentaris() { return $this->numComentaris; }
    public function getUser() { return $this->id_usuari; }
    public function getCategoria() { return $this->id_categoria; }
    public function getComentaris() { return $this->comentaris; }

    public function loadComments() {
        if (!isset($this->id_publicacio)) {
            throw new Exception("No s'ha carregat cap publicació.");
        }
        $db = new Database();
        $commentsData = $db->getCommentsById($this->id_publicacio);
        $this->comentaris = [];

        foreach ($commentsData as $row) {
            $this->comentaris[] = new Comentari(
                $row['id_comentari'],
                $row['contingut'],
                $row['data'],
                (int)$row['id_usuari'],
                (int)$row['id_publicacio'],
            );
        }
    }

    public static function getAll() {
        $db = new Database();
        $postsData = $db->getAllPosts();
        $posts = [];

        foreach ($postsData as $row) {
            $numComentaris = $db->countComments($row['id_publicacio']);
            
            $posts[] = new Post(
                $row['id_publicacio'],
                $row['contingut'],
                $row['data'],
                $row['url_imatge'],
                $numComentaris,
                (int)$row['id_usuari'],
                (int)$row['id_categoria']
            );
        }
        return $posts;
    }
}

?>