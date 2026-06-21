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

function uploadToCloudinary(string $fileTmpPath): ?string {
    $cloudName = getenv('CLOUDINARY_CLOUD_NAME');
    $apiKey    = getenv('CLOUDINARY_API_KEY');
    $apiSecret = getenv('CLOUDINARY_API_SECRET');

    $timestamp = time();
    $signature = sha1("timestamp={$timestamp}{$apiSecret}");

    $ch = curl_init("https://api.cloudinary.com/v1_1/{$cloudName}/image/upload");
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => [
            'file'      => new CURLFile($fileTmpPath),
            'api_key'   => $apiKey,
            'timestamp' => $timestamp,
            'signature' => $signature,
        ],
    ]);
    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);
    return $data['secure_url'] ?? null;
}

function validateImage(?array $file, string $currentImageUrl): array {
    if (!$file || !isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => true, 'url' => $currentImageUrl];
    }

    $fileNameCmps  = explode(".", $file['name']);
    $fileExtension = strtolower(end($fileNameCmps));

    if (!in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])) {
        return ['success' => false, 'url' => $currentImageUrl, 'error' => 'Tipus d\'arxiu no permès. Només es permeten: JPG, JPEG, PNG, GIF.'];
    }

    if ($file['size'] > 2 * 1024 * 1024) {
        return ['success' => false, 'url' => $currentImageUrl, 'error' => 'La imatge és massa gran. La mida màxima és 2 MB.'];
    }

    $url = uploadToCloudinary($file['tmp_name']);
    if (!$url) {
        return ['success' => false, 'url' => $currentImageUrl, 'error' => 'Error en pujar la imatge al servidor.'];
    }

    return ['success' => true, 'url' => $url];
}

function validateImageForPost(?array $file): array {
    if (!$file || !isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => true, 'url' => null];
    }

    $fileNameCmps  = explode(".", $file['name']);
    $fileExtension = strtolower(end($fileNameCmps));

    if (!in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])) {
        return ['success' => false, 'url' => null, 'error' => 'Tipus d\'arxiu no permès. Només es permeten: JPG, JPEG, PNG, GIF.'];
    }

    if ($file['size'] > 2 * 1024 * 1024) {
        return ['success' => false, 'url' => null, 'error' => 'La imatge és massa gran. La mida màxima és 2 MB.'];
    }

    $url = uploadToCloudinary($file['tmp_name']);
    if (!$url) {
        return ['success' => false, 'url' => null, 'error' => 'Error en pujar la imatge al servidor.'];
    }

    return ['success' => true, 'url' => $url];
}

?>