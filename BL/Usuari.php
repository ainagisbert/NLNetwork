<!-- Definició classe Usuari
Aquí les dades arriben ja validades i sanejades, així que no cal preocpuar-se per això.
Els mètodes de la classe es van utilitzant en altres fitxers per realitzar operacions de l'usuari, com registrar-lo, comprobar les dades guardades, etc -->
<?php

require_once(__DIR__ . '/../DL/Database.php');

class Usuari {

    // Funció per afegir un usuari a la BD
    // (!) Important: nosaltres posem una descripció i una imatge de perfil per defecte, però l'usuari les podrà actualitzar més tard 
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

    // Funció per comprovar si existeix un email
    public function existsEmail($email) {
        $db = new Database();
        return $db->checkEmail($email);
    }

    // Funció per comprovar si existeix un alias
    public function existsAlias($alias) {
        $db = new Database();
        return $db->checkAlias($alias);
    }

    // Funció per comprovar si la contrasenya és valida
    public function isValidPassword($identificador, $contrasenya) {
        $db = new Database();
        $hash = $db->getUserPassword($identificador);

        if (!$hash) {
            return false;
        }

        return password_verify($contrasenya, $hash);
    }

    // Funció per eliminar un usuari de la BD
    public function deleteUser($email) {
        $db = new Database();
        return $db->deleteUser($email);
    }

    // Funció per actualitzar el perfil d'un usuari menys la contrasenya
    public function updateProfile($email, $nom, $alies, $url_imatge, $descripcio) {
        $db = new Database();
        return $db->updateProfile($email, $nom, $alies, $url_imatge, $descripcio);
    }

    // Funció per actualitzar la contrasenya d'un usuari, ja que s'ha de fer un hash abans de guardar-la
    public function updatePassword($email, $contrasenya) {
        $passwordHash = password_hash($contrasenya, PASSWORD_DEFAULT);
        $db = new Database();
        return $db->updatePassword($email, $passwordHash);
    }
    
    // Funció per obtenir totes les dades d'un usuari en un array associatiu
    // Això vol dir que guardarem les dades a l'array amb el nom del camp, per exemple: $usuari['id'] = 1;
    public function getUserById($identificador) {
        $db = new Database();
        return $db->getUserById($identificador);
    }
}

?>