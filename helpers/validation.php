<?php

require_once('vendor/autoload.php');

use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\DNSCheckValidation;
use Egulias\EmailValidator\Validation\MultipleValidationWithAnd;
use Egulias\EmailValidator\Validation\NoRFCWarningsValidation;
use Egulias\EmailValidator\Validation\Extra\SpoofCheckValidation;

// === Funcions de sanejament ===

// Sanejar strings per treure els espais abans d'abans i després i fer alguns caràcters segurs i evitar injeccions de codi
function sanitizeString($value) {
    return htmlspecialchars(trim((string) $value), ENT_QUOTES, 'UTF-8');
}

// Treiem els espais d'abans i després dels emails
function sanitizeEmail($email) {
    return trim((string) $email, FILTER_SANITIZE_EMAIL);
}

// === Funcions de validació ===

function validateName($name)
{
    $length = strlen($name);
    return $length >= 2 && $length <= 50;
}

function validateAlias($alias) {
    return (bool) preg_match('/^[A-Za-z0-9_.]{3,30}$/', $alias);
}

// Validació correu utilitzant el paquet EmailValidator, mirem els estàndards de correu i que el domini existeixi
function validateEmailAddress($email)
{
    $validator = new EmailValidator();

    $multipleValidations = new MultipleValidationWithAnd([
        new NoRFCWarningsValidation(),
        new DNSCheckValidation(),
        new SpoofCheckValidation()
    ]);

    return $validator->isValid($email, $multipleValidations);
}

function validatePassword($password)
{
    $length = strlen($password);
    $hasLetters = preg_match('/[A-Za-z]/', $password);
    $hasDigits = preg_match('/\d/', $password);
    $hasSpecialChars = preg_match('/[^A-Za-z0-9]/', $password);
    return $length >= 8 && $hasLetters && $hasDigits && $hasSpecialChars;
}

// Aquesta funció valida tots els atributs d'un usuari
function validateUserFields($nom, $alies, $email, $contrasenya = null, $isUpdate = false)
{
    $errors = [];

    if (!validateName($nom)) {
      $errors[] = 'El nom ha de tenir entre 2 i 50 caràcters.';
    }

    if (!validateAlias($alies)) {
      $errors[] = 'L\'àlies només pot tenir lletres, números, un punt o un guió baix (3-30).';
    }

    if (!validateEmailAddress($email)) {
      $errors[] = 'El correu no és vàlid.';
    }

    if (!$isUpdate && !validatePassword((string) $contrasenya)) {
      $errors[] = 'La contrasenya ha de tenir almenys 8 caràcters, incloure un número i un caràcter especial.';
    }

    return $errors;
}

function validateImage(?array $file, string $currentImageUrl, string $uploadDir = '../images/'): array {
    if (!$file || !isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => true, 'url' => $currentImageUrl];
    }

    $fileTmpPath = $file['tmp_name'];
    $fileName = $file['name'];
    $fileSize = $file['size'];
    $fileType = $file['type'];

    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));

    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

    if (!in_array($fileExtension, $allowedExtensions)) {
        return [
            'success' => false,
            'url' => $currentImageUrl,
            'error' => 'Tipus d\'arxiu no permès. Només es permeten: JPG, JPEG, PNG, GIF.'
        ];
    }

    $maxFileSize = 2 * 1024 * 1024; // Imatges de 2MB màxim
    if ($fileSize > $maxFileSize) {
        return [
            'success' => false,
            'url' => $currentImageUrl,
            'error' => 'La imatge és massa gran. La mida màxima és 2 MB.'
        ];
    }

    $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
    $destPath = $uploadDir . $newFileName;

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    if (move_uploaded_file($fileTmpPath, $destPath)) {
        $url = 'images/' . $newFileName;
        return ['success' => true, 'url' => $url];
    } else {
        return [
            'success' => false,
            'url' => $currentImageUrl,
            'error' => 'Error en pujar la imatge al servidor.'
        ];
    }
}

function validateImageForPost(?array $file, string $uploadDir = '../images/') {
    if (!$file || !isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => true, 'url' => null];
    }

    $fileTmpPath = $file['tmp_name'];
    $fileName = $file['name'];
    $fileSize = $file['size'];
    $fileType = $file['type'];

    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));

    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

    if (!in_array($fileExtension, $allowedExtensions)) {
        return [
            'success' => false,
            'url' => null,
            'error' => 'Tipus d\'arxiu no permès. Només es permeten: JPG, JPEG, PNG, GIF.'
        ];
    }

    $maxFileSize = 2 * 1024 * 1024; // 2MB
    if ($fileSize > $maxFileSize) {
        return [
            'success' => false,
            'url' => null,
            'error' => 'La imatge és massa gran. La mida màxima és 2 MB.'
        ];
    }

    $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
    $destPath = $uploadDir . $newFileName;

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    if (move_uploaded_file($fileTmpPath, $destPath)) {
        $url = 'images/' . $newFileName;
        return ['success' => true, 'url' => $url];
    } else {
        return [
            'success' => false,
            'url' => null,
            'error' => 'Error en pujar la imatge al servidor.'
        ];
    }
}

?>