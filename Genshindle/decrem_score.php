<?php
session_start();//Ici on gère simplement la décrémentation du score, toujours avec la méthode AJAX pour éviter toute erreur possible
if (isset($_SESSION['score'])) {
    $_SESSION['score'] -= 1;
    if ($_SESSION['score'] < 0) { // Si le score est déjà à 0 ça va essayer de le mettre à -1 donc on le remet à 0
        $_SESSION['score'] = 0;
    }
}
?>
