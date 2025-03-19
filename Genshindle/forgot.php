<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css"
        integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA=="
        crossorigin="anonymous" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>


    <title>Forgot Password </title>

    <style>
    * {
        margin: 0;
        padding: 0;
        font-family: 'Poppins', sans-serif;
    }

    body {
        background-color: #ff99f5;
        background-image:
            radial-gradient(at 61% 4%, hsla(303, 91%, 61%, 1) 0px, transparent 50%),
            radial-gradient(at 75% 66%, hsla(196, 91%, 79%, 1) 0px, transparent 50%),
            radial-gradient(at 98% 88%, hsla(76, 87%, 78%, 1) 0px, transparent 50%),
            radial-gradient(at 23% 16%, hsla(238, 96%, 77%, 1) 0px, transparent 50%),
            radial-gradient(at 95% 65%, hsla(13, 91%, 75%, 1) 0px, transparent 50%),
            radial-gradient(at 10% 79%, hsla(228, 96%, 69%, 1) 0px, transparent 50%),
            radial-gradient(at 85% 58%, hsla(328, 81%, 68%, 1) 0px, transparent 50%);
        background-repeat: no-repeat;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 15rem 0;
    }

    .card {
        backdrop-filter: blur(16px) saturate(180%);
        -webkit-backdrop-filter: blur(16px) saturate(180%);
        background-color: rgba(0, 0, 0, 0.75);
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.125);
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 30px 40px;
    }

    .lock-icon {
        font-size: 3rem;
    }

    h2 {
        font-size: 1.5rem;
        margin-top: 10px;
        text-transform: uppercase;
    }

    p {
        font-size: 12px;
    }

    .card {
        gap: 20px;
    }

    .passInput {
        margin-top: 15px;
        width: 80%;
        background: transparent;
        border: none;
        border-bottom: 2px solid deepskyblue;
        font-size: 15px;
        color: white;
        outline: none;
    }

    .card h2 {
        font-size: 2em;
        color: #fff;
    }

    .card .button {
        position: relative;
        width: 100%;
    }

    .card .button input {
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

    .card .button input[type="submit"] {
        width: 100%;
        background: #0078ff;
        background: linear-gradient(45deg, #ff357a, #fff172);
        border: none;
        cursor: pointer;
    }

    .card .button input::placeholder {
        color: rgba(255, 255, 255, 0.75);
    }

    .card .links {
        position: relative;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 20px;
    }

    .card .links a {
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
    </style>
</head>

<body>
    <a href="Genshindle.php"><img id='home_img' src="home.png"></a>
    <main>
        <div class="card">
            <p class="lock-icon"><i class='bx bxs-lock-alt'></i></p>
            <h2>Forgot Password?</h2>
            <p>You can reset your Password here</p>
            <input type="text" class="passInput" placeholder="Email address" required>
            <div class="button">
                <input type="submit" value="Send my Password to My Mail" required>
            </div>
        </div>
    </main>
</body>

</html>