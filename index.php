<?php

define('REGEX_POSTAL_CODE', '^[0-9]{5}$');
define('REGEX_LASTNAME', '^[A-Za-z \'-]+$');
define('REGEX_LINKEDIN', '^(http(s)?:\/\/)?([\w]+\.)?linkedin\.com\/(pub|in|profile)');
define('NATIVE_COUNTRYS', ['Allemagne', 'Belgique', 'Espagne', 'France', 'Italie', 'Luxembourg', 'Portugal', 'Suisse']);
define('CHECKBOX_LANGUAGES', ['HTML/CSS', 'PHP', 'Javascript', 'Python', 'Autres']);
define('REGEX_DATEBIRTH', '^(19|20)\d\d-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])$');


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

    // // INPUT "Civilité"
    // if (!empty($_POST['gender'])) {
    //     $gender = filter_input(INPUT_POST, 'gender', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
    //     if (!in_array($gender, RADIO_GENDERS)) {
    //         $error['gender'] =  "La valeur sélectionnée n'est pas valide.";
    //     }
    // }

    // INPUT "Civilité"
    // Nettoyage de name = 'gender'
    
    $gender = intval(filter_input(INPUT_POST, 'gender', FILTER_SANITIZE_NUMBER_INT));
    var_dump($gender);
    // Validation de name = 'gender'
    if (!empty($gender)) {
        $isValid = filter_var($gender, FILTER_VALIDATE_INT, array("options" => array("min_range" => 0, "max_range" => 1 )));
        if (!$isValid) {
            $error['gender'] = "La valeur sélectionnée n'est pas valide.";
        }
    }    
    

}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@200;400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="./public/assets/css/style.css">
    <title>TP formulaire PHP</title>
</head>

<body>
    <div class="row justify-content-center align-items-center vh-100">
        <div class="col-12 col-lg-9 col-xl-7">
            <div class="card shadow-lg rounded-3 px-5 py-4 bg-glass" data-bs-theme="dark">
                <?php if ($_SERVER['REQUEST_METHOD'] != 'POST' || !empty($error)) { ?>
                    <h1 class="text-center mb-3 h3 text-light fw-bold">Créer un compte</h1>
                    <!-- Formulaire -->
                    <form class="row g-2" method="POST" novalidate>
                        <div class="col-lg-4">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control mb-lg-2" id="email" name="email" placeholder="nom@exemple.fr" value="<?= $email ?? '' ?>">
                            <div class="form-text text-error"><?= $error['email'] ?? '' ?></div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <label for="password" class="form-label">Mot de passe</label>
                            <input type="password" class="form-control mb-lg-2" id="password" name="password" placeholder="Entrez votre mot de passe" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}">
                            <div class="form-text">Doit contenir au moins 8 caractères.</div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <label for="ConfirmPassword" class="form-label">Confirmation du mot de passe</label>
                            <input type="password" class="form-control mb-lg-2" id="ConfirmPassword" name="ConfirmPassword" placeholder="Confirmez votre mot de passe" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}">
                        </div>
                        <div class="col-md-3">
                            <p class="mb-2">Civilité</p>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="0" name="gender" value="0" <?= (isset($gender) && $gender == 0) ? 'checked' : '' ?> >
                                <label class="form-check-label" for="0">Mr</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="1" name="gender" value="1" <?= (isset($gender) && $gender == 1) ? 'checked' : '' ?> >
                                <label class="form-check-label" for="1">Mme</label>
                            </div>
                            <div class="form-text text-error"><?= $error['gender'] ?? '' ?></div>
                        </div>
                        <div class="col-md-5">
                            <label for="lastname" class="form-label">Nom</label>
                            <input type="text" class="form-control mb-lg-2" id="lastname" name="lastname" placeholder="ex. : Dupont" pattern="<?= REGEX_LASTNAME ?>" value="<?= $lastname ?? '' ?>">
                            <div class="form-text text-error"><?= $error['lastname'] ?? '' ?></div>
                        </div>
                        <div class="col-md-4">
                            <label for="dateBirth" class="form-label">Date de naissance</label>
                            <input type="date" inputmode="numeric" class="form-control mb-lg-2" id="dateBirth" name="dateBirth" min="<?= (date('Y') - 120)."-01-01" ?>" max="<?= $currentYear ?>" pattern="<?= REGEX_DATEBIRTH ?>" value="<?= $dateBirth ?? '' ?>">
                            <div class="form-text text-error"><?= $error['dateBirth'] ?? '' ?></div>
                        </div>
                        <div class="col-md-6">
                            <label for="nativeCountry" class="form-label">Pays de naissance</label>
                            <select id="nativeCountry" name="nativeCountry" class="form-select mb-lg-2">
                                <option value="">Sélectionnez votre pays</option>
                                <?php
                                foreach (NATIVE_COUNTRYS as $country) { ?>
                                    <option value="<?= $country ?>" <?= (isset($nativeCountry) && $nativeCountry == $country) ? 'selected' : '' ?>><?= $country ?></option>
                                <?php }
                                ?>
                            </select>
                            <div class="form-text text-error"><?= $error['nativeCountry'] ?? '' ?></div>
                        </div>
                        <div class="col-md-6">
                            <label for="postalCode" class="form-label">Code postal</label>
                            <input type="text" inputmode="numeric" class="form-control mb-lg-2" id="postalCode" name="postalCode" placeholder="ex. : 80000" pattern="<?= REGEX_POSTAL_CODE ?>" value="<?= $postalCode ?? '' ?>" autocomplete="postal-code">
                            <div class="form-text text-error"><?= $error['postalCode'] ?? '' ?></div>
                        </div>
                        <div class="col-md-6">
                            <label for="imgProfilFile" class="form-label">Photo de profil</label>
                            <input class="form-control mb-lg-2" type="file" id="imgProfilFile" name="imgProfilFile">
                        </div>
                        <div class="col-md-6">
                            <label for="linkedin" class="form-label">URL profil Linkedin</label>
                            <input type="url" class="form-control mb-lg-2" id="linkedin" name="linkedin" placeholder="ex. : linkedin.com/in/..." pattern="<?= REGEX_LINKEDIN ?>" value="<?= $linkedin ?? '' ?>">
                            <div class="form-text text-error"><?= $error['linkedin'] ?? '' ?></div>
                        </div>
                        <div class="col-12">
                            <fieldset class="mb-lg-2">
                                <p class="mb-1">Quel langages web connaissez-vous ?</p>
                                <?php
                                foreach (CHECKBOX_LANGUAGES as $language) { ?>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="<?= $language ?>" name="checkbox[]" value="<?= $language ?>" <?= (isset($checkbox) && in_array($language, $checkbox)) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="<?= $language ?>"><?= $language ?></label>
                                    </div>
                                <?php }
                                ?>
                            </fieldset>
                            <div class="form-text text-error"><?= $error['checkbox'] ?? '' ?></div>
                        </div>
                        <div class="col-12">
                            <label for="inputTextarea" class="form-label">Racontez une expérience avec la programmation et/ou l'informatique que vous auriez pu avoir</label>
                            <textarea class="form-control mb-3" id="inputTextarea" name="inputTextarea" rows="3" max="500"></textarea>
                        </div>

                        <!-- Bouton de validation -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-outline-light mb-2">Je m'inscris !</button>
                        </div>
                    </form>
                <?php } else { ?>
                    <!-- /// Affichage de mes valeurs -->
                    <h3 class="text-center mb-3 text-light fw-bold">Récapitulatif de mon compte</h3>
                    <!-- Affichage Email -->
                    <h5 class="fw-light">Email : <span class="fw-bold"><?= $email ?></span></h5>
                    <!-- Affichage MDP -->
                    <h5 class="fw-light">Mot de passe : <span class="fw-bold"></span></h5>
                    <!-- Affichage Civilité -->
                    <h5 class="fw-light">Civilité :
                        <span class="fw-bold">
                        <?php
                        if ($gender == 0 ) {
                            echo "Mr";
                        } elseif ($gender == 1) {
                            echo "Mme";
                        } else {
                            echo "pas renseigné";
                        }
                        ?>
                        </span>
                    </h5>
                    <!-- Affichage Nom -->
                    <h5 class="fw-light">Nom : <span class="fw-bold"><?= $lastname ?></span></h5>
                    <!-- Affichage Date de naissance -->
                    <h5 class="fw-light">Date de naissance : <span class="fw-bold"><?= $dateBirth ?? 'pas renseigné' ?></span></h5>
                    <!-- Affichage Pays de naissance -->
                    <h5 class="fw-light">Pays de naissance : <span class="fw-bold"><?= $nativeCountry ?? 'pas renseigné' ?></span></h5>
                    <!-- Affichage CP -->
                    <h5 class="fw-light">CP : <span class="fw-bold"><?= $postalCode ?? 'pas renseigné' ?></span></h5>
                    <!-- Affichage Linkedin -->
                    <h5 class="fw-light">URL profil Linkedin : <span class="fw-bold"><?= $linkedin ?? 'pas renseigné' ?></span></h5>
                    <!-- Affichage Langages web -->
                    <h5 class="fw-light">Langages web :
                        <span class="fw-bold">
                        <?php
                        if (!empty($checkbox)) {
                            foreach ($checkbox as $value) {
                                echo htmlspecialchars($value) . ", ";
                            }
                        } else {
                            echo "pas renseigné";
                        }
                        ?>
                        </span>
                    </h5>
                <?php  } ?>
            </div>
        </div>
    </div>

</body>

</html>