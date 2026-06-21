<?php

require_once("config.php");

class Database {

    private $conn;

    public function __construct() {
        $this->conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DBNAME, PORT);
        if ($this->conn->connect_error) {
          die("Connexió fallida: " . $this->conn->connect_error);
      }
      $this->conn->set_charset("utf8");
    }

    // COMANDES PER GESTIONAR L'USUARI: donar d'alta, esborrar, actualitzar, fer comprovacions i obtenir totes les seves dades
    
    public function addUser($nom, $alies, $email, $contrasenya, $url_imatge, $descripcio) {
        $sql = "INSERT INTO usuari (nom, alies, email, contrasenya, url_imatge, descripcio)
                VALUES ('$nom', '$alies', '$email', '$contrasenya', '$url_imatge', '$descripcio')";
        return $this->conn->query($sql);
    }

    public function checkEmail($email) {
        $sql = "SELECT email FROM usuari WHERE email = '$email' LIMIT 1";
        $result = $this->conn->query($sql);
        return ($result && $result->num_rows > 0);
    }

    public function checkAlias($alias) {
        $sql = "SELECT alies FROM usuari WHERE alies = '$alias' LIMIT 1";
        $result = $this->conn->query($sql);
        return ($result && $result->num_rows > 0);
    }

    public function deleteUser($id){
      $sql = "DELETE FROM usuari WHERE id_usuari = '$id'";
      return $this->conn->query($sql);
    }

    public function updateProfile($id, $nom, $alies, $url_imatge, $descripcio) {
      $sql = "UPDATE usuari SET nom = '$nom', alies = '$alies', url_imatge = '$url_imatge', descripcio = '$descripcio' WHERE id_usuari = '$id'";
      return $this->conn->query($sql);
    }

    public function updatePassword($id, $contrasenya) {
      $sql = "UPDATE usuari SET contrasenya = '$contrasenya' WHERE id_usuari = '$id'";
      return $this->conn->query($sql);
    }

    public function getUserPassword($identificador) {
        $sql = "SELECT contrasenya FROM usuari WHERE alies = '$identificador' OR email = '$identificador' LIMIT 1";
        $result = $this->conn->query($sql);
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['contrasenya'];
        }
        return null;
    }

    public function getUserById($identificador) {
      $sql = "SELECT * FROM usuari WHERE alies = '$identificador' OR email = '$identificador' OR id_usuari = '$identificador' LIMIT 1";
      $result = $this->conn->query($sql);
      if ($result && $result->num_rows > 0) {
          return $result->fetch_assoc();
      }
      return null;
    }

    public function countLikesRebutsPosts($id_usuari) {
      $sql = "SELECT COUNT(*) AS total 
              FROM likes_post lp
              JOIN publicacio p ON lp.id_publicacio = p.id_publicacio
              WHERE p.id_usuari = '$id_usuari'";
      $result = $this->conn->query($sql);
      return ($result && $row = $result->fetch_assoc()) ? (int)$row['total'] : 0;
    }

    // COMANDES PER GESTIONAR POSTS: publicar un nou, esborrar-lo, obtenir la seva informació i obtenir tots els de la BD

    public function addPost($id_usuari, $id_categoria, $contingut, $url_imatge = '') {
      $sql = "INSERT INTO publicacio (contingut, `data`, url_imatge, id_usuari, id_categoria)
              VALUES ('$contingut', NOW(), '$url_imatge', '$id_usuari', '$id_categoria')";
      return $this->conn->query($sql);
    }

    public function getPostsById($id_usuari) {
      $sql = "SELECT * FROM publicacio WHERE id_usuari = '$id_usuari' ORDER BY data DESC";
      $result = $this->conn->query($sql);
      
      $posts = [];
      if ($result && $result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
              $posts[] = $row;
          }
      }
      return $posts;
    }

    public function deletePost($id_publicacio, $id_usuari) {
      $sql = "DELETE FROM publicacio WHERE id_publicacio = '$id_publicacio' AND id_usuari = '$id_usuari'";
      return $this->conn->query($sql);
    }

    public function countLikesPost($id_publicacio) {
      $sql = "SELECT COUNT(*) AS total FROM likes_post WHERE id_publicacio = '$id_publicacio'";
      $result = $this->conn->query($sql);
      return ($result && $row = $result->fetch_assoc()) ? (int)$row['total'] : 0;
    }

    public function countComments($id_publicacio) {
      $sql = "SELECT COUNT(*) AS total FROM comentari WHERE id_publicacio = '$id_publicacio'";
      $result = $this->conn->query($sql);
      if ($result && $row = $result->fetch_assoc()) {
          return (int)$row['total'];
      }
      return 0;
    }

    public function getAllPosts() {
      $sql = "SELECT * FROM publicacio ORDER BY data DESC";
      $result = $this->conn->query($sql);
      $posts = [];
      if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          $row['comentaris'] = $this->countComments($row['id_publicacio']);
          $posts[] = $row;
        }
      }
      return $posts;
    }

    // COMANDES PER GESTIONAR COMENTARIS: publicar un nou, esborrar-lo, obtenir la seva informació i retornar tots els comentaris d'un post

    public function addComment($id_usuari, $id_publicacio, $contingut) {
        $sql = "INSERT INTO comentari (contingut, data, id_usuari, id_publicacio)
                VALUES ('$contingut', NOW(), '$id_usuari', '$id_publicacio')";
        return $this->conn->query($sql);
    }

    public function getCommentsById($id_publicacio) {
        $sql = "SELECT c.*, u.alies, u.url_imatge 
                FROM comentari c
                JOIN usuari u ON c.id_usuari = u.id_usuari
                WHERE c.id_publicacio = '$id_publicacio'
                ORDER BY c.data ASC";
        $result = $this->conn->query($sql);
        
        $comments = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $comments[] = $row;
            }
        }
        return $comments;
    }

    public function deleteComment($id_comentari, $id_usuari) {
        $sql = "DELETE FROM comentari WHERE id_comentari = '$id_comentari' AND id_usuari = '$id_usuari'";
        return $this->conn->query($sql);
    }
    public function countLikesComentari($id_comentari) {
      $sql = "SELECT COUNT(*) AS total FROM likes_comentari WHERE id_comentari = '$id_comentari'";
      $result = $this->conn->query($sql);
      return ($result && $row = $result->fetch_assoc()) ? (int)$row['total'] : 0;
    }

    // Funcions gestionar els likes, si l'usuari té like, el treu, si no en té, l'afegeix

    public function toggleLikePost($id_publicacio, $id_usuari) {
      $check = "SELECT 1 FROM likes_post WHERE id_publicacio = '$id_publicacio' AND id_usuari = '$id_usuari'";
      $result = $this->conn->query($check);
      
      if ($result && $result->num_rows > 0) {
          $sql = "DELETE FROM likes_post WHERE id_publicacio = '$id_publicacio' AND id_usuari = '$id_usuari'";
      } else {
          $sql = "INSERT INTO likes_post (id_publicacio, id_usuari) VALUES ('$id_publicacio', '$id_usuari')";
      }
      return $this->conn->query($sql);
    }

    public function toggleLikeComentari($id_comentari, $id_usuari) {
      $check = "SELECT 1 FROM likes_comentari WHERE id_comentari = '$id_comentari' AND id_usuari = '$id_usuari'";
      $result = $this->conn->query($check);
      
      if ($result && $result->num_rows > 0) {
          $sql = "DELETE FROM likes_comentari WHERE id_comentari = '$id_comentari' AND id_usuari = '$id_usuari'";
      } else {
          $sql = "INSERT INTO likes_comentari (id_comentari, id_usuari) VALUES ('$id_comentari', '$id_usuari')";
      }
      return $this->conn->query($sql);
    }

    // Comprovacions si un usuari ha fet like a una publicació o comentari

    public function userHasLikedPost($id_publicacio, $id_usuari) {
      $sql = "SELECT 1 FROM likes_post WHERE id_publicacio = '$id_publicacio' AND id_usuari = '$id_usuari'";
      $result = $this->conn->query($sql);
      return $result && $result->num_rows > 0;
  }

  public function userHasLikedComentari($id_comentari, $id_usuari) {
    $sql = "SELECT 1 FROM likes_comentari WHERE id_comentari = '$id_comentari' AND id_usuari = '$id_usuari'";
    $result = $this->conn->query($sql);
    return $result && $result->num_rows > 0;
  }
}
?>
