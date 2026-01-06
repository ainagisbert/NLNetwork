<!-- Definició classe Usuari
Control de dades -->
<?php

require_once(__DIR__ . '/../DL/Database.php');

class Usuari {

    public function addUser($nom, $alies, $email, $contrasenya) {
        $passwordHash = password_hash($contrasenya, PASSWORD_DEFAULT);
        $url_imatge = 'images/profile_placeholder.jpg';
        $descripcio = 'Hola! Soc nou a NLNetwork';

        $db = new Database();
        $saved = $db->addUser($nom, $alies, $email, $passwordHash, $url_imatge, $descripcio);
        
        if (!$saved) {
            return false;
        } else {
            return true;
        }
    }

    public function existsEmail($email) {
        $db = new Database();
        return $db->checkEmail($email);
    }

    public function existsAlias($alias) {
        $db = new Database();
        return $db->checkAlias($alias);
    }

    public function isValidPassword($identificador, $contrasenya) {
        $db = new Database();
        $hash = $db->getUserPassword($identificador);

        if (!$hash) {
            return false;
        }

        return password_verify($contrasenya, $hash);
    }

    public function deleteUser($email) {
        $db = new Database();
        return $db->deleteUser($email);
    }

    public function updateProfile($email, $nom, $alies, $url_imatge, $descripcio) {
        $db = new Database();
        return $db->updateProfile($email, $nom, $alies, $url_imatge, $descripcio);
    }

    public function updatePassword($email, $contrasenya) {
        $passwordHash = password_hash($contrasenya, PASSWORD_DEFAULT);
        $db = new Database();
        return $db->updatePassword($email, $passwordHash);
    }
    
    public function getUserById($identificador) {
        $db = new Database();
        return $db->getUserById($identificador);
    }
}

?>