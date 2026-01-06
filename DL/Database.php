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

    public function deleteUser($email){
      $sql = "DELETE FROM usuari WHERE email = '$email'";
      return $this->conn->query($sql);
    }

    public function updateProfile($email, $nom, $alies, $url_imatge, $descripcio) {
      $sql = "UPDATE usuari SET nom = '$nom', alies = '$alies', url_imatge = '$url_imatge', descripcio = '$descripcio' WHERE email = '$email'";
      return $this->conn->query($sql);
    }

    public function updatePassword($email, $contrasenya) {
      $sql = "UPDATE usuari SET contrasenya = '$contrasenya' WHERE email = '$email'";
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
      $sql = "SELECT * FROM usuari WHERE alies = '$identificador' OR email = '$identificador' LIMIT 1";
      $result = $this->conn->query($sql);
      if ($result && $result->num_rows > 0) {
          return $result->fetch_assoc();
      }
      return null;
    }

    // Afegir un nou post
    public function addPost($id_usuari, $id_categoria, $contingut, $url_imatge = '') {
      $sql = "INSERT INTO publicacio (contingut, data, url_imatge, likes, id_usuari, id_categoria)
              VALUES ('$contingut', CURDATE(), '$url_imatge', 0, '$id_usuari', '$id_categoria')";
      return $this->conn->query($sql);
    }

    // Obtenir tots els posts d'un usuari
    public function getPostsByIdUsuari($id_usuari) {
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

    // Esborrar un post
    public function deletePost($id_publicacio, $id_usuari) {
      $sql = "DELETE FROM publicacio WHERE id_publicacio = '$id_publicacio' AND id_usuari = '$id_usuari'";
      return $this->conn->query($sql);
    }
}
?>
