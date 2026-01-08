<!-- Definició classe Usuari i tots els mètodes necessaris per gestionar-lo -->
<?php

require_once(__DIR__ . '/../DL/Database.php');
require_once(__DIR__ . '/Post.php');

class Usuari {

    private $id;
    private $nom;
    private $alies;
    private $email;
    private $url_imatge;
    private $descripcio;
    private $posts = [];

    // Fem un constructor intel·ligent, que pot instanciar una classe usuari fins i tot si no li donem un identificador
    public function __construct($identificador = null) {
        if ($identificador !== null) {
            $this->loadById($identificador);
        }
    }

    // Carrega les dades de l'usuari, funciona com un setter
    public function loadById($identificador) {
        $db = new Database();
        $data = $db->getUserById($identificador);
        if ($data) {
            $this->id = $data['id_usuari'];
            $this->nom = $data['nom'];
            $this->alies = $data['alies'];
            $this->email = $data['email'];
            $this->url_imatge = $data['url_imatge'];
            $this->descripcio = $data['descripcio'];
            return true;
        }
        return false;
    }

    // ========== MÈTODES D'INSTÀNCIA (fan servir les dades carregades) ==========

    // Getters dels atributs
    public function getId() { return $this->id; }
    public function getNom() { return $this->nom; }
    public function getAlies() { return $this->alies; }
    public function getEmail() { return $this->email; }
    public function getAvatar() { return $this->url_imatge; }
    public function getDescripcio() { return $this->descripcio; }
    public function getLikes() {
        $db = new Database();
        return $db->countLikesRebutsPosts($this->id);
    }
    public function getPosts() { return $this->posts; }

    // Esborra l'usuari
    public function deleteUser() {
        if (!isset($this->id)) {
            throw new Exception("No s'ha carregat cap usuari.");
        }
        $db = new Database();
        return $db->deleteUser($this->id);
    }

    // Actualitza les dades de l'usuari
    public function updateProfile($nom, $alies, $url_imatge, $descripcio) {
        if (!isset($this->id)) {
            throw new Exception("No s'ha carregat cap usuari.");
        }
        $db = new Database();
        if ($db->updateProfile($this->id, $nom, $alies, $url_imatge, $descripcio)) {
            $this->nom = $nom;
            $this->alies = $alies;
            $this->url_imatge = $url_imatge;
            $this->descripcio = $descripcio;
            return true;
        }
        return false;
    }

    public function updatePassword($contrasenya) {
        $passwordHash = password_hash($contrasenya, PASSWORD_DEFAULT);
        $db = new Database();
        return $db->updatePassword($this->id, $passwordHash);
    }

    public function loadPosts() {
        if (!isset($this->id)) {
            throw new Exception("No s'ha carregat cap usuari.");
        }
        $db = new Database();
        $postsData = $db->getPostsById($this->id);
        $this->posts = [];

        foreach ($postsData as $row) {
            $numComentaris = $db->countComments($row['id_publicacio']);

            $this->posts[] = new Post(
                $row['id_publicacio'],
                $row['contingut'],
                $row['data'],
                $row['url_imatge'],
                $numComentaris,
                (int)$row['id_usuari'],
                (int)$row['id_categoria'],
            );
        }
    }

    // Afegeix nou post per aquest usuari
    public function addPost($id_categoria, $contingut, $url_imatge = null) {
        if (!isset($this->id)) {
            throw new Exception("No s'ha carregat cap usuari.");
        }
        $db = new Database();
        return $db->addPost($this->id, $id_categoria, $contingut, $url_imatge);
    }

    // Esborra un post només si pertany a aquest usuari
    public function deletePost($id_publicacio) {
        if (!isset($this->id)) {
            throw new Exception("No s'ha carregat cap usuari.");
        }
        $db = new Database();
        return $db->deletePost($id_publicacio, $this->id);
    }

    public function toggleLikePost($id_publicacio) {
        if (!isset($this->id)) {
            throw new Exception("No s'ha carregat cap usuari.");
        }
        $db = new Database();
        return $db->toggleLikePost($id_publicacio, $this->id);
    }

    public function toggleLikeComentari($id_comentari) {
        if (!isset($this->id)) {
            throw new Exception("No s'ha carregat cap usuari.");
        }
        $db = new Database();
        return $db->toggleLikeComentari($id_comentari, $this->id);
    }

    public function hasLikedPost($id_publicacio) {
        if (!isset($this->id)) {
            return false;
        }
        $db = new Database();
        return $db->userHasLikedPost($id_publicacio, $this->id);
    }

    public function hasLikedComentari($id_comentari) {
        if (!isset($this->id)) return false;
        $db = new Database();
        return $db->userHasLikedComentari($id_comentari, $this->id);
    }


    public function addComment($id_publicacio, $contingut) {
        if (!isset($this->id)) {
            throw new Exception("No s'ha carregat cap usuari.");
        }
        $db = new Database();
        return $db->addComment($this->id, $id_publicacio, $contingut);
    }

    public function deleteComment($id_comentari) {
        if (!isset($this->id)) {
            throw new Exception("No s'ha carregat cap usuari.");
        }
        $db = new Database();
        return $db->deleteComment($id_comentari, $this->id);
    }

    // ========== MÈTODES ESTÀTICS / DE CLASSE ==========

    // Registra nou usuari a la base de dades
    public static function addUser($nom, $alies, $email, $contrasenya) {
        $passwordHash = password_hash($contrasenya, PASSWORD_DEFAULT);
        $url_imatge = 'images/profile_placeholder.jpg';
        $descripcio = 'Hola! Soc nou a NLNetwork';

        $db = new Database();
        return $db->addUser($nom, $alies, $email, $passwordHash, $url_imatge, $descripcio);
    }

    // Comprovem si l'email està a la BD, ja que no es poden repetir
    public static function existsEmail($email) {
        $db = new Database();
        return $db->checkEmail($email);
    }

    // Comprovem si l'alies està a la BD, ja que no es poden repetir
    public static function existsAlias($alias) {
        $db = new Database();
        return $db->checkAlias($alias);
    }

    // Comprovem que sigui la seva contrasenya
    public static function isValidPassword($identificador, $contrasenya) {
        $db = new Database();
        $hash = $db->getUserPassword($identificador);
        return ($hash && password_verify($contrasenya, $hash));
    }
    
    /* public static function getUserById($identificador) {
        $db = new Database();
        return $db->getUserById($identificador);
    } */
}

?>