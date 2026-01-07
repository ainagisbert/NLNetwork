<!-- Definició classe Database
Les comandes SQL només estan aquí. -->
<?php

require_once("config.php");

class Database {

    private $conn;

    public function __construct() {
        $this->conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DBNAME);
        if ($this->conn->connect_error) {
          die("Connexió fallida: " . $this->conn->connect_error);
      }
      $this->conn->set_charset("utf8");
    }

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

    public function addPost($id_usuari, $id_categoria, $contingut, $url_imatge = '') {
      $sql = "INSERT INTO publicacio (contingut, `data`, url_imatge, likes, id_usuari, id_categoria)
              VALUES ('$contingut', NOW(), '$url_imatge', 0, '$id_usuari', '$id_categoria')";
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
}
?>
