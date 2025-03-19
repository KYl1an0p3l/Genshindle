<!DOCTYPE html>
<html lang="fr">

<head>  
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="venti.jpg">
    <title>Genshindle - Login</title>
    <style>
        main {
            display: flex;
            justify-content: center;
            align-items: center;
            box-sizing: border-box;
            width: 100vw;
        }

        @import url("https://fonts.googleapis.com/css2?family=Quicksand:wght@300&display=swap");
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Quicksand", sans-serif;
        }
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: #111;
            width: 100%;
            overflow: hidden;
        }
        .remember-for label {
            color: white;
            width: 100%;
            box-sizing: content-box;
        }
        .register-link p {
            color: white;
        }
        .ring {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            width: 500px;
            height: 500px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .remember-for {
            font-weight: bold;
            width: 100%;
            box-sizing: content-box;
        }
        .register-link {
            font-weight: bold;
            box-sizing: content-box;
            display: flex;
            justify-content: space-between;
        }
        .ring i {
            position: absolute;
            inset: 0;
            border: 2px solid #fff;
            transition: 0.5s;
            border: 6px solid var(--clr);
            filter: drop-shadow(0 0 20px var(--clr));
        }
        .ring i:nth-child(1) {
            border-radius: 38% 62% 63% 37% / 41% 44% 56% 59%;
            animation: animate 6s linear infinite;
        }
        .ring i:nth-child(2) {
            border-radius: 41% 44% 56% 59%/38% 62% 63% 37%;
            animation: animate 4s linear infinite;
        }
        .ring i:nth-child(3) {
            border-radius: 41% 44% 56% 59%/38% 62% 63% 37%;
            animation: animate2 10s linear infinite;
        }
        @keyframes animate {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }
        @keyframes animate2 {
            0% {
                transform: rotate(360deg);
            }
            100% {
                transform: rotate(0deg);
            }
        }
        .login {
            position: absolute;
            width: 300px;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            gap: 20px;
        }
        .login h2 {
            font-size: 2em;
            color: #fff;
        }
        .login .inputBx {
            position: relative;
            width: 100%;
        }
        .login .inputBx input {
            position: relative;
            width: 100%;
            padding: 12px 20px;
            background: transparent;
            border: 2px solid #fff;
            border-radius: 40px;
            font-size: 1.2em;
            color: #fff;
            box-shadow: none;
            outline: none;
        }
        .login .inputBx input[type="submit"] {
            width: 100%;
            background: #0078ff;
            background: linear-gradient(45deg, #ff357a, #fff172);
            border: none;
            cursor: pointer;
        }
        .login .inputBx input::placeholder {
            color: rgba(255, 255, 255, 0.75);
        }
        .login .links {
            position: relative;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
        }
        .login .links a {
            color: #fff;
            text-decoration: none;
        } 
        #home_img{
            color: blue;
            position : absolute;
            width : 40px;
            height : 40px;
            left : 20px;
            top : 20px;
        }

    </style>
</head>

<body>
    <a href="Genshindle.php"><img id ='home_img' src="home.png"></a>
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