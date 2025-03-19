<!DOCTYPE html>
<html lang="fr">

<head>  
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../images/persos/venti.jpg">
    <link rel="stylesheet" href="../css/connexion.css">
    <title>Genshindle - Login</title>
</head>

<body>
    <a href="Genshindle.php"><img id ='home_img' src="../icones/home.png"></a>
    <main>
    <form class="ring" method="post" action="#">
     <i style="--clr:#00ff0a;"></i>
     <i style="--clr:#ff0057;"></i>
     <i style="--clr:#fffd44;"></i>
        <div class="login">
         <h2>Login</h2>
         <div class="inputBx">
            <input type="text" name="pseudo" placeholder="Username">
         </div>
         <div class="inputBx">
            <input type="password" name="password" placeholder="Password">
         </div>
         <div class="remember-for">
            <label ><input type="checkbox"> Remember me</label>
            <a href="forgot.php">Forgot password?</a>
          </div>
         <div class="inputBx">
            <input type="submit" value="Sign in">
        </div>
        <div class="register-link">
    <p>Don't have an account? 
        <a href="register.php">
            <span class="cadre">Register</span>
        </a>
    </p>
</div>
  </div>
</form>
    </main>
    <?php
    session_start();
    $user = "root";
    $pass = 'root';
    $db = new PDO("mysql:host=localhost;dbname=genshindle", $user, $pass);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST["pseudo"];
        $password = $_POST["password"];

        $query = $db->prepare("SELECT * FROM score WHERE name = :name");
        $query->bindParam(':name', $name);
        $query->execute();

        $user = $query->fetch();

        if ($user) { //On vérifie le mot de passe entré
            if ($password == $user["passwd"]) { //Si l'utilisateur s'est bien identifié, on va le "connecter" c'est à dire lui affecter les variable de session qui lui sont propre
                $_SESSION["name"] = $user["name"];
                $queryScore = $db->prepare("SELECT score FROM score WHERE name = :name");
                $queryScore->bindParam(':name', $_SESSION["name"]);
                $queryScore->execute();
                $userScore = $queryScore->fetchColumn();
                $_SESSION['score_total'] = $userScore;
                echo '<h1 style="color: white;"><strong>Connected as ' . $_SESSION['name'] . '</strong></h1>';
            } else { //Sinon, on explique ce qui ne va pas
                echo '<h1 style="color: white;"><strong>Invalid Password</strong></h1>';
            }
        } else {
            echo '<h1 style="color: white;"><strong>Invalid Pseudo</strong></h1>';
        }
    }
    ?>
</body>

</html>