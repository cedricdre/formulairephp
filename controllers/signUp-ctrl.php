<?php
require_once __DIR__. '/../config/regex.php';
require_once __DIR__. '/../config/constants.php';


$minYear = (date('Y') - 120)."-01-01";
$maxYear = date("Y-m-d");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $error = [];

    // INPUT "lastname"
    $lastname = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_SPECIAL_CHARS);
    if (empty($lastname)) {
        $error['lastname'] = 'Votre nom n\'est pas renseigné';
    } else {
        $isValid = filter_var($lastname, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => '/' . REGEX_LASTNAME . '/')));
        if (!$isValid) {
            $error['lastname'] = 'Votre nom doit contenir que des caractères majuscules et/ou minuscules';
        }
    }

    // INPUT "email"
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    if (empty($email)) {
        $error['email'] = 'Votre email n\'est pas renseigné';
    } else {
        $isValid = filter_var($email, FILTER_VALIDATE_EMAIL);
        if (!$isValid) {
            $error['email'] = 'Votre email n\'est pas valide';
        }
    }

    // INPUT "Code postal"
    if (!empty($_POST['postalCode'])) {
        $postalCode = filter_input(INPUT_POST, 'postalCode', FILTER_SANITIZE_NUMBER_INT);
        $isValid = filter_var($postalCode, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => '/' . REGEX_POSTAL_CODE . '/')));
        if (!$isValid) {
            $error['postalCode'] = 'Votre Code Postal n\'est pas valide';
        }
    }

    // INPUT "linkedin"
    if (!empty($_POST['linkedin'])) {
        $linkedin = filter_input(INPUT_POST, 'linkedin', FILTER_SANITIZE_URL);
        $isValid = filter_var($linkedin, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => '/' . REGEX_LINKEDIN . '/')));
        if (!$isValid) {
            $error['linkedin'] = 'Votre URL n\'est pas valide';
        }
    }

    // INPUT "Pays de naissance"
    if (!empty($_POST['nativeCountry'])) {
        $nativeCountry = filter_input(INPUT_POST, 'nativeCountry', FILTER_SANITIZE_SPECIAL_CHARS);
        if (!in_array($nativeCountry, NATIVE_COUNTRYS)) {
            $error['nativeCountry'] =  "La valeur sélectionnée n'est pas valide.";
        }
    }

    // INPUT checkbox "Quel langages web connaissez-vous ?"
    // Nettoyage de name = 'checkbox'
    $checkbox = filter_input(INPUT_POST, 'checkbox', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
    // Validation de name = 'checkbox'
    if (!empty($checkbox)) {
        foreach ($checkbox as $value) {
            // Vérification si la valeur de mon tableau 'checkbox' est dans mon tableau 'CHECKBOX_LANGUAGES'
            if (!in_array($value, CHECKBOX_LANGUAGES)) {
                $error['checkbox'] = "La valeur sélectionnée n'est pas valide.";
            }
        }
    }

    // INPUT "Pays de naissance"
    if (!empty($_POST['dateBirth'])) {
        $dateBirth = filter_input(INPUT_POST, 'dateBirth', FILTER_SANITIZE_NUMBER_INT);
        $isValid = filter_var($dateBirth, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => '/' . REGEX_DATEBIRTH . '/')));
        if (!$isValid || $dateBirth >= $maxYear || $dateBirth <= $minYear ) {
            $error['dateBirth'] = 'Votre date de naissance n\'est pas valide';
        }
    }

    // INPUT "Civilité"
    // Nettoyage de name = 'gender'  
    $gender = intval(filter_input(INPUT_POST, 'gender', FILTER_SANITIZE_NUMBER_INT));
    // Validation de name = 'gender'
    if (!empty($gender)) {
        $isValid = filter_var($gender, FILTER_VALIDATE_INT, array("options" => array("min_range" => 0, "max_range" => 1 )));
        if (!$isValid) {
            $error['gender'] = "La valeur sélectionnée n'est pas valide.";
        }
    }    

    // INPUT "password"
    $password = filter_input(INPUT_POST, 'password');
    $confirmPassword = filter_input(INPUT_POST, 'confirmPassword');

    if (empty($password)) {
        $error['password'] = 'Votre mot de passe n\'est pas renseigné';
    }
    if (empty($confirmPassword)) {
        $error['confirmPassword'] = 'Veuillez confirmer votre mot de passe';
    }

    if ($password !== $confirmPassword) {
        $error['password'] = 'Les mots de passe ne correspondent pas';
        $error['confirmPassword'] = 'Les mots de passe ne correspondent pas';
    }

    $isValidPassword = filter_var($password, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => '/' . REGEX_PASSWORD . '/')));
    if (!$isValidPassword) {
        $error['password'] = 'Votre mot de passe n\'est pas valide';
    } else {
        $hashPassword = password_hash($password, PASSWORD_DEFAULT);
    }

    // INPUT "textarea"
    $textarea = filter_input(INPUT_POST, 'textarea', FILTER_SANITIZE_SPECIAL_CHARS);
    if (!empty($textarea)) {
        if (strlen($textarea) > 1000) {
            $error['textarea'] = 'Votre texte doit contenir que des caractères majuscules et/ou minuscules';
        }
    } 


    try {
        if (empty($_FILES['file']['name'])) {
            throw new Exception("Ajoutez une photo");           
        }
        if (!empty($_FILES['file']['error'])) {
            throw new Exception("Erreur"); 
        }
        if (!in_array($_FILES['file']['type'], TYPES_MIME)) {
            throw new Exception("Le format de l'image n'est pas autorisée");           
        }
        if (!empty($_FILES['file']['size'] > MAX_SIZE)) {
            throw new Exception("Le fichier est trop lourd");           
        }
        $filename = uniqid("img_");
        $extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        $from = $_FILES['file']['tmp_name'];
        $to = './public/uploads/users/'. $filename. '.'. $extension;
        move_uploaded_file($from, $to);
    } catch (\Throwable $th) {
        $error['fileName'] = $th->getMessage();
    }

}
include __DIR__. '/../views/templates/header.php';
include __DIR__. '/../views/signUp.php';
include __DIR__. '/../views/templates/footer.php';