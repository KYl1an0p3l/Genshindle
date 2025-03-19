<?php
session_start();
if ($_SESSION['score'] == 0 || !isset($_COOKIE['serie'])) {
    setcookie('serie', 0, time() + (1800), "/");
}
else{
    setcookie('serie', $_COOKIE['serie'] + 1, time() + (1800), "/");
}

if(isset($_SESSION['score'])){ 
    if(isset($_SESSION['name'])){ //On gère différement le comptage et l'allocation à la variable de session si on est connecté
        $name = $_SESSION['name'];
        $score = $_SESSION['score'];

        $user = "root";
        $pass = 'root';
        $pdo = new PDO("mysql:host=localhost;dbname=genshindle", $user, $pass);

        $stmt = $pdo->prepare("UPDATE score SET score = score + :score WHERE name = :name");
        $stmt->execute(array(':score' => $score, ':name' => $name)); //On ajoute le score à la valeur associé au profil contenue dans la bdd 

        //Ici on récupère juste la valeur contenu dans la bdd pour l'affecter à la variable de session ça permet de garder la même ligne pour l'affichage du score tout en étant compatible avec les 2 gestions de score différentes
        $stmt = $pdo->prepare("SELECT SUM(score) FROM score WHERE name = :name"); 
        $stmt->execute(array(':name' => $name)); 
        $user_score_total = $stmt->fetchColumn();
        $_SESSION['score_total'] = $user_score_total;

        $_SESSION['score'] = 11;
    }
    else{ //De si on ne l'est pas
        $_SESSION['score_total'] += $_SESSION['score'];
        $_SESSION['score'] = 11;
    }
}
?>
