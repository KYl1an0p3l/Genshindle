<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Genshindle - Register </title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
    main {
        display: flex;
        position: relative;
        justify-content: center;
        align-items: center;
        box-sizing: border-box;
        width: 100vw;
        height: 100vh;
    }

    @import url("https://fonts.googleapis.com/css2?family=Quicksand:wght@300&display=swap");

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: "Quicksand", sans-serif;
    }

    body {
        background: #111;
        width: 100%;
        overflow: hidden;
    }

    .conditions label {
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
        width: 100%;
        box-sizing: content-box;
    }

    .register-link {
        position: absolute;
        bottom: 25px;
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

    .login .inputB {
        position: relative;
        width: 100%;
    }

    .login .inputB input {
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

    .login .inputB input[type="submit"] {
        width: 100%;
        background: #0078ff;
        background: linear-gradient(45deg, #ff357a, #fff172);
        border: none;
        cursor: pointer;
    }

    .login .inputB input::placeholder {
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

    #home_img {
        color: blue;
        position: absolute;
        width: 40px;
        height: 40px;
        left: 20px;
        top: 20px;
    }

    #messages {
        position : absolute;
        bottom : 0px;
        color: white;
        background-color: red; /* Choisissez une couleur de fond appropriée */
        padding: 10px;
        margin-top: 10px; /* Ajustez la marge pour l'espacement par rapport au formulaire */
    }
    </style>
</head>

<body>
    <a href="Genshindle.php"><img id='home_img' src="home.png"></a>
    <main>
        <form class="ring" method="post" action="#">
            <i style="--clr:#00ff0a;"></i>
            <i style="--clr:#ff0057;"></i>
            <i style="--clr:#fffd44;"></i>
            <div class="login">
                <h2>register</h2>
                <div class="inputB">
                    <input type="text" name="email" placeholder="Email" required>
                </div>
                <div class="inputB">
                    <input type="text" name="username" placeholder="Username" required>
                </div>
                <div class="inputB">
                    <input type="password" name="passwd" placeholder="Password" required>
                </div>
                <div class="inputB">
                    <input type="password" name="confirmpassword" placeholder="Confirm password" required>
                </div>
                <div class="conditions">
                    <label><input type="checkbox" required> i agree to the terms & conditions</label>
                </div>
                <div class="inputB">
                    <input type="submit" name="send" value="Sign up">
                </div>

            </div>
        </form>
        <a href="Genshindle.php" id="home_link">
            <img id='home_img' src="home.png">
        </a>
        <div class="register-link">
            <p>You already have an account?
                <a href="connexion.php">
                    <span class="cadre">Login</span>
                </a>
            </p>
        </div>
    </main>
    <?php
    function nettoyer_donnees($donnees){
        $donnees =trim($donnees);
        $donnees =stripslashes($donnees);
        $donnees =htmlspecialchars($donnees);
        return $donnees;
    }
    session_start();
    $user = "root";
    $pass = 'root';
    $db = new PDO("mysql:host=localhost;dbname=genshindle", $user, $pass);

    if (isset($_POST['send'])) {
        $email = nettoyer_donnees($_POST['email']);
        $username = nettoyer_donnees($_POST['username']);
        $password = nettoyer_donnees($_POST['passwd']);
        $confirmPassword = nettoyer_donnees($_POST['confirmpassword']);

        $query_check_email = $db->prepare("SELECT * FROM score WHERE mail = :email");
        $query_check_email->bindParam(':email', $email);
        $query_check_email->execute();
        $existing_user = $query_check_email->fetch();

        if ($existing_user) {
            echo "<div id='messages'>";
            echo "<p>Email already exists, please choose another one.</p>";
            echo "</div>";
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<div id='messages'>";
            echo "<p>Invalid email address</p>";
            echo "</div>";
        } else if ($password !== $confirmPassword) {
            echo "<div id='messages'>";
            echo "<p>Passwords do not match</p>";
            echo "</div>";
        } else {
            $query = "INSERT INTO score (name, passwd, mail, score) VALUES (:username, :password, :email, 0)";
            $statement = $db->prepare($query);
            $statement->bindValue(':username', $username);
            $statement->bindValue(':email', $email);
            $statement->bindValue(':password', $password);
            $statement->execute();
            echo "<div id='messages'>";
            echo "<p>Registration successful</p>";
            echo "</div>";
        }
    }
    ?>


</body>

</html>
