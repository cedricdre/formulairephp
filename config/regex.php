<?php
// Regex formulaire d'inscription
define('REGEX_POSTAL_CODE', '^[0-9]{5}$');
define('REGEX_LASTNAME', '^[A-Za-z \'-]+$');
define('REGEX_LINKEDIN', '^(http(s)?:\/\/)?([\w]+\.)?linkedin\.com\/(pub|in|profile)');
define('REGEX_DATEBIRTH', '^(19|20)\d\d-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])$');
define('REGEX_PASSWORD', '(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}');
define('REGEX_TEXT', '[^A-Za-z0-9]');
?>