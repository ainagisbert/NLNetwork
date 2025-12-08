<!-- Definició classe Database
Les comandes SQL només estan aquí. -->
<?php

require_once("config.php");

class Database {
  
    // Fer la connexió amb la BD
    private $conn;

    public function __construct() {
        $this->conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DBNAME);
        if ($this->conn->connect_error) {
          die("Connexió fallida: " . $this->conn->connect_error);
      }
      $this->conn->set_charset("utf8");
    }

    // Funció per afegir un usuari a la BD
    public function addUser($nom, $alies, $email, $contrasenya, $url_imatge, $descripcio) {
        $sql = "INSERT INTO usuari (nom, alies, email, contrasenya, url_imatge, descripcio)
                VALUES ('$nom', '$alies', '$email', '$contrasenya', '$url_imatge', '$descripcio')";
        return $this->conn->query($sql);
    }

    // Funció per comprovar si existeix un email
    public function checkEmail($email) {
        $sql = "SELECT email FROM usuari WHERE email = '$email' LIMIT 1";
        $result = $this->conn->query($sql);
        return ($result && $result->num_rows > 0);
    }

    // Funció per comprovar si existeix un alias
    public function checkAlias($alias) {
        $sql = "SELECT alies FROM usuari WHERE alies = '$alias' LIMIT 1";
        $result = $this->conn->query($sql);
        return ($result && $result->num_rows > 0);
    }

    // Funció per eliminar un usuari de la BD
    public function deleteUser($email){
      $sql = "DELETE FROM usuari WHERE email = '$email'";
      return $this->conn->query($sql);
    }

    // Funció per actualitzar el perfil d'un usuari
    public function updateProfile($email, $nom, $alies, $url_imatge, $descripcio) {
      $sql = "UPDATE usuari SET nom = '$nom', alies = '$alies', url_imatge = '$url_imatge', descripcio = '$descripcio' WHERE email = '$email'";
      return $this->conn->query($sql);
    }

    // Funció per actualitzar la contrasenya d'un usuari
    public function updatePassword($email, $contrasenya) {
      $sql = "UPDATE usuari SET contrasenya = '$contrasenya' WHERE email = '$email'";
      return $this->conn->query($sql);
    }

    // Funció per obtenir la contrasenya d'un usuari
    public function getUserPassword($identificador) {
        $sql = "SELECT contrasenya FROM usuari WHERE alies = '$identificador' OR email = '$identificador' LIMIT 1";
        $result = $this->conn->query($sql);
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['contrasenya'];
        }
        return null;
    }

    // Funció per obtenir les dades d'un usuari, ho fem amb un OR perquè puc buscar per alias o per email
    public function getUserById($identificador) {
      $sql = "SELECT * FROM usuari WHERE alies = '$identificador' OR email = '$identificador' LIMIT 1";
      $result = $this->conn->query($sql);
      if ($result && $result->num_rows > 0) {
          return $result->fetch_assoc();
      }
      return null;
    }
}
?>
