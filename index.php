<?php

$error = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $lastname = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_SPECIAL_CHARS);
    if (empty($lastname)) {
        $error['lastname'] = 'Votre nom n\'est pas renseigné';
    } else {
        $isValid = filter_var($lastname, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[A-Za-z '-]+$/")));
        if (!$isValid) {
            $error['lastname'] = 'Votre nom doit contenir que des caractères majuscules et/ou minuscules';
        }
    }

    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    if (empty($email)) {
        $error['email'] = 'Votre email n\'est pas renseigné';
    } else {
        $isValid = filter_var($email, FILTER_VALIDATE_EMAIL);
        if (!$isValid) {
            $error['email'] = 'Votre email n\'est pas valide';
        }
    }

    if (!empty($_POST['gender'])) {
        $gender = $_POST['gender'];
    } else {
        $error['gender'] = 'Votre civilité n\'est pas renseigné';
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
                    <form class="row g-2" method="POST">
                        <div class="col-lg-4">
                            <label for="email" class="form-label">Email</label>
                            <input type="text" class="form-control mb-lg-2" id="email" name="email" placeholder="nom@exemple.fr" value="<?= $email ?? '' ?>">
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
                        <div class="col-md-4">
                            <label for="gender" class="form-label">Civilité</label>
                            <select id="gender" name="gender" class="form-select mb-lg-2">
                                <option value="Mr" <?= (isset($gender) && $gender=='Mr') ? 'selected' : ''?>>Mr</option>
                                <option value="Mme" <?= (isset($gender) && $gender=='Mme') ? 'selected' : ''?>>Mme</option>
                            </select>
                        </div>
                        <div class="col-md-8">
                            <label for="lastname" class="form-label">Nom</label>
                            <input type="text" class="form-control mb-lg-2" id="lastname" name="lastname" placeholder="ex. : Dupont" pattern="^[A-Za-z '-]+$" value="<?= $lastname ?? '' ?>">
                            <div class="form-text text-error"><?= $error['lastname'] ?? '' ?></div>
                        </div>
                        <div class="col-md-4">
                            <label for="inputDateBirth" class="form-label">Date de naissance</label>
                            <input type="date" class="form-control mb-lg-2" id="inputDateBirth" name="inputDateBirth" min="1900-01-01" max="2030-12-31" pattern="(0[1-9]|[12][0-9]|3[01])[\/](0[1-9]|1[012])[\/](19|20)\d\d">
                        </div>
                        <div class="col-md-4">
                            <label for="inputNativeCountry" class="form-label">Pays de naissance</label>
                            <select id="inputNativeCountry" name="inputNativeCountry" class="form-select mb-lg-2">
                                <option selected>France</option>
                                <option>Belgique</option>
                                <option>Suisse</option>
                                <option>Luxembourg</option>
                                <option>Allemagne</option>
                                <option>Italie</option>
                                <option>Espagne</option>
                                <option>Portugal</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="inputPostalCode" class="form-label">Code postal</label>
                            <input type="text" class="form-control mb-lg-2" id="inputPostalCode" name="inputPostalCode" placeholder="ex. : 80000" pattern="([A-Z]+[A-Z]?\-)?[0-9]{1,2} ?[0-9]{3}">
                        </div>
                        <div class="col-md-6">
                            <label for="imgProfilFile" class="form-label">Photo de profil</label>
                            <input class="form-control mb-lg-2" type="file" id="imgProfilFile" name="imgProfilFile">
                        </div>
                        <div class="col-md-6">
                            <label for="inputLinkedin" class="form-label">URL profil Linkedin</label>
                            <input type="text" class="form-control mb-lg-2" id="inputLinkedin" name="inputLinkedin" placeholder="ex. : linkedin.com/in/..." pattern="https?://.+">
                        </div>
                        <div class="col-12">
                            <fieldset class="mb-lg-2">
                                <p class="mb-1">Quel langages web connaissez-vous?</p>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="checkboxHTML" name="checkboxHTML" value="">
                                    <label class="form-check-label" for="checkboxHTML">HTML/CSS</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="checkboxPHP" name="checkboxPHP" value="">
                                    <label class="form-check-label" for="checkboxPHP">PHP</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="checkboxJS" name="checkboxJS" value="">
                                    <label class="form-check-label" for="checkboxJS">Javascript</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="checkboxPython" name="checkboxPython" value="">
                                    <label class="form-check-label" for="checkboxPython">Python</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="checkboxOthers" name="checkboxOthers" value="">
                                    <label class="form-check-label" for="checkboxOthers">Autres</label>
                                </div>
                            </fieldset>
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
                    <h3 class="text-center mb-3 text-light fw-bold">Récapitulatif de mon compte</h3>
                    <h5 class="fw-light">Email : <span class="fw-bold"><?= $email ?></span></h5>
                    <h5 class="fw-light">Civilité : <span class="fw-bold"><?= $gender ?></span></h5>                    
                    <h5 class="fw-light">Nom : <span class="fw-bold"><?= $lastname ?></span></h5>                    
                <?php } ?>
            </div>
        </div>
    </div>

</body>

</html>