<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Genshindle - Register </title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../css/register.css">
</head>

<body>
    <a href="Genshindle.php"><img id='home_img' src="../icones/home.png"></a>
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
            <img id='home_img' src="../icones/home.png">
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
