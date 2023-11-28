<section class="my-5">
<div class="row justify-content-center">
        <div class="col-12 col-lg-9 col-xl-7">
            <div class="card shadow-lg rounded-3 px-5 py-4 bg-glass" data-bs-theme="dark">
                <?php if ($_SERVER['REQUEST_METHOD'] != 'POST' || !empty($error)) { ?>
                    <h1 class="text-center mb-3 h3 text-light fw-bold">Créer un compte</h1>
                    <!-- Formulaire -->
                    <form class="row g-2" method="POST" enctype='multipart/form-data' novalidate>
                        <div class="col-lg-4">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control mb-lg-2" id="email" name="email" placeholder="nom@exemple.fr" value="<?= $email ?? '' ?>">
                            <div class="form-text text-error"><?= $error['email'] ?? '' ?></div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <label for="password" class="form-label">Mot de passe</label>
                            <input type="password" class="form-control mb-lg-2" id="password" name="password" placeholder="Entrez votre mot de passe" pattern="<?= REGEX_PASSWORD ?>" required>
                            <div class="form-text">Doit contenir au moins 8 caractères.</div>
                            <div class="form-text text-error"><?= $error['password'] ?? '' ?></div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <label for="confirmPassword" class="form-label">Confirmation du mot de passe</label>
                            <input type="password" class="form-control mb-lg-2" id="confirmPassword" name="confirmPassword" placeholder="Confirmez votre mot de passe" pattern="<?= REGEX_PASSWORD ?>" required>
                            <div class="form-text text-error"><?= $error['confirmPassword'] ?? '' ?></div>
                        </div>
                        <div class="col-md-12">
                            
                            <div class="form-check-inline">
                            <p class="mb-0">Civilité</p>
                            </div>
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
                        <div class="col-md-6">
                            <label for="lastname" class="form-label">Nom</label>
                            <input type="text" class="form-control mb-lg-2" id="lastname" name="lastname" placeholder="ex. : Dupont" pattern="<?= REGEX_LASTNAME ?>" value="<?= $lastname ?? '' ?>">
                            <div class="form-text text-error"><?= $error['lastname'] ?? '' ?></div>
                        </div>
                        <div class="col-md-6">
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
                            <label for="file" class="form-label">Photo de profil</label>
                            <input class="form-control mb-lg-2" type="file" id="file" name="file" accept=".png, image/jpeg">
                            <div class="form-text text-error"><?= $error['fileName'] ?? '' ?></div>
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
                            <label for="textarea" class="form-label">Racontez une expérience avec la programmation et/ou l'informatique que vous auriez pu avoir :</label>
                            <textarea class="form-control mb-3" id="textarea" name="textarea" rows="3" maxlength="1000" placeholder="Entrez votre texte"><?= $textarea ?? '' ?></textarea>
                            <div class="form-text text-error"><?= $error['textarea'] ?? '' ?></div>
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
                    <h5 class="fw-light">Mot de passe : <span class="fw-bold"><?= $hashPassword ?></span></h5>
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
                    <h5 class="fw-light">Expérience : <span class="fw-bold"><?= $textarea ?? 'pas renseigné' ?></span></h5>
                    <h5 class="fw-light">Photo de profil : <img src="<?= $to ?>" class="img-fluid" alt=""></h5>

                <?php  } ?>
            </div>
        </div>
    </div>
</section>
