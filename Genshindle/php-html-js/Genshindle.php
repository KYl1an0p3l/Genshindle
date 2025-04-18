<!DOCTYPE html>
<?php 
session_start(); //Ici on va gérer l'initialisation de quelques valeurs ainsi que la connexion à la base de donnée, on va donc surtout gérer les 1ère connexion des utilisateurs ou les connexion après une longue période.
if (!isset($_SESSION['score'])) {
    $_SESSION['score'] = 11;
    $_SESSION['score_total'] = 0;
}
try {
    $conn = new PDO("mysql:host=localhost;dbname=genshindle", "root", "root");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt_top_scores = $conn->prepare("SELECT name, score FROM score ORDER BY score DESC LIMIT 5");//Je récupère les 5 premiers au classement pour pour faire le classement
    $stmt_top_scores->execute();
    $top_scores = $stmt_top_scores->fetchAll(PDO::FETCH_ASSOC);

    if(isset($_SESSION['name'])) { //Puis je viens récupérer le classement du joueur
        $name = $_SESSION['name'];
        $stmt_user_score = $conn->prepare("SELECT score FROM score WHERE name = :name");
        $stmt_user_score->execute(array(':name' => $name));
        $user_score = $stmt_user_score->fetchColumn();
    }
} catch(PDOException $e) {
    echo "La connexion à la base de données a échoué : " . $e->getMessage();
}

if (!isset($_COOKIE['serie'])){
    setcookie('serie', 0, time() + (1800), "/"); // On créer le cookie de la série de victoire, valide pendant 3 minutes
}
?>

<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../images/persos/venti.jpg">
    <link rel="stylesheet" href="../css/Genshindle.css">
    <title>Genshindle</title>
</head>
<body>
    <div id="header">
        <div id="entete">
            <h1>Genshindle</h1>
            <a href="connexion.php"><img id ='con_img' src="../icones/connexion.jpg"></a>
            <img id ='ser_img' src="../icones/flamme.png" title="Série de victoire, le cooldown se réinitialise à chaque réussite, vous avez 3 minutes pour deviner sans briser la chaîne">
            <p id ='ser_txt'>
                <?php if(isset($_COOKIE['serie'])) {echo $_COOKIE['serie'];} else{echo 0;} ?></p>
        </div>
        <input type="text" id="chartxt" placeholder="Nom d'un personnage">
        <ul id="nameSuggestions"></ul>
        <button id="charbut">Guess</button>
    </div>

    <table class="guess-header">
        <tbody>
            <tr class="guess-item-header row">
                <th class="name-header">Name</th>
                <th class="wb-header">Weekly Boss</th>
                <th class="region-header">Region</th>
                <th class="elem-header">Element</th>
                <th class="arme-header">Arme</th>
                <th class="v-header">Version</th>
            </tr>

            <tr class="guess-result">
                <td colspan="7" id="resultText"></td>
            </tr>
        </tbody>
    </table>

    <div id="score-box">
        <div id="score-content">
            <h2>Score :</h2>
            <h3><p id="score-value"><?php echo $_SESSION['score_total']; ?></p></h3> <!--Affichage du score-->
            <p>Le score qui est affiché est votre score total sur le jeu. Le score gagné à chaque round est de 11 - le nombre de tentatives effectuées. Donc si vous le devinez dès la 1ère tentative, vous aurez +10 au score, puis +9,+8,+7... jusqu'à 0.</p>
        </div>
        <div id="score-content">
            <h4>Un Coup de mou ? Un trou de mémoire ?</h4>
            <p>Utilisez la soluce !</p>
            <button id="sol_button">Soluce</button>
            <p id="soluce"></p>
        </div>
        <div class="top-scores">
            <h4>Top 5 Scores</h4>
            <ul>
                <?php foreach ($top_scores as $score) : ?> <!--Affichage du classement-->
                    <li><?php echo $score['name']; ?> - <?php echo $score['score']; ?> points</li>
                <?php endforeach; ?>
            </ul>
            <?php if(isset($_SESSION['name'])) : ?>
                <div class="user-score">
                    <h4>Votre Score</h4>
                    <p><?php echo $_SESSION['name']; ?> - <?php echo $user_score; ?> points</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div id="rules-box">
        <div id="rules-content">
            <h2>Règles</h2>
            <p>Bienvenue dans Genshindle !</p>
            <p>Votre but est de deviner le personnage (choisis aléatoirement) 
            grâce aux divers indices donnés par le site, si la case est verte, cette information est la même 
            que celle du personnage que vous devez deviner, si la case est rouge, ce n'est pas bon.<br>
            <br>
            Cela fonctionne indépendemment pour chaque indices ratachés au personnage que vous devez deviner<br>
            <br>
            De ce qui est de la version il n'y a pas de couleur mais des flèches vous indiquant si la version est 
            plus haute ou plus basse que celle du personnage que vous devez trouver, plus il y a de flèches, 
            plus la version est loin de celle que vous recherchez.<br><br>
        
            Pour mieux comprendre, commencez par tapper le nom d'un personnage, le site vous aidera en vous proposant des noms qui se rapproche de ce que vous avez mis.</p>
        </div>
    </div>

    <script>
        //Dictionnaire des personnages à deviner avec leurs attributs
        const characters = [
    { name: "Diluc", boss: "Stormterror", region: "Mondstadt", arme: "Claymore", element: "Pyro", version: "1.0" },
    { name: "Venti", boss: "Stormterror", region: "Mondstadt", arme: "Bow", element: "Anemo", version: "1.0" },
    { name: "Keqing", boss: "Loup_du_Nord", region: "Liyue", arme: "Sword", element: "Electro", version: "1.0" },
    { name: "Voyageur", boss: "Aucun", region: "Aucune", arme: "Sword", element: "Tout", version: "1.0" },
    { name: "Xiao", boss: "Tartaglia", region: "Liyue", arme: "Spear", element: "Anemo", version: "1.3" },
    { name: "Sucrose", boss: "Loup_du_Nord", region: "Mondstadt", arme: "Catalyseur", element: "Anemo", version: "1.0" },
    { name: "Jean", boss: "Stormterror", region: "Mondstadt", arme: "Sword", element: "Anemo", version: "1.0" },
    { name: "Kazuha", boss: "Azhdaha", region: "Inazuma", arme: "Sword", element: "Anemo", version: "1.6" },
    { name: "Sayu", boss: "Azhdaha", region: "Inazuma", arme: "Claymore", element: "Anemo", version: "2.0" },
    { name: "Heizou", boss: "Raiden", region: "Inazuma", arme: "Catalyseur", element: "Anemo", version: "2.8" },
    { name: "Nomade", boss: "Wanderer", region: "Sumeru", arme: "Catalyseur", element: "Anemo", version: "3.3" },
    { name: "Faruzan", boss: "Wanderer", region: "Sumeru", arme: "Bow", element: "Anemo", version: "3.3" },
    { name: "Lynette", boss: "Garde_d'Apep", region: "Fontaine", arme: "Sword", element: "Anemo", version: "4.0" },
    { name: "Xiangling", boss: "Stormterror", region: "Liyue", arme: "Spear", element: "Pyro", version: "1.0" },
    { name: "Klee", boss: "Loup_du_Nord", region: "Mondstadt", arme: "Catalyseur", element: "Pyro", version: "1.0" },
    { name: "Bennett", boss: "Stormterror", region: "Mondstadt", arme: "Sword", element: "Pyro", version: "1.0" },
    { name: "Amber", boss: "Stormterror", region: "Mondstadt", arme: "Bow", element: "Pyro", version: "1.0" },
    { name: "Xinyan", boss: "Tartaglia", region: "Liyue", arme: "Claymore", element: "Pyro", version: "1.1" },
    { name: "HuTao", boss: "Tartaglia", region: "Liyue", arme: "Spear", element: "Pyro", version: "1.3" },
    { name: "Yanfei", boss: "Azhdaha", region: "Liyue", arme: "Catalyseur", element: "Pyro", version: "1.5" },
    { name: "Yoimiya", boss: "Azhdaha", region: "Inazuma", arme: "Bow", element: "Pyro", version: "2.0" },
    { name: "Thomas", boss: "Signora", region: "Inazuma", arme: "Spear", element: "Pyro", version: "2.2" },
    { name: "Dehya", boss: "Wanderer", region: "Sumeru", arme: "Claymore", element: "Pyro", version: "3.5" },
    { name: "Lyney", boss: "Garde_d'Apep", region: "Fontaine", arme: "Bow", element: "Pyro", version: "4.0" },
    { name: "Chevreuse", boss: "Narval_Cosmique", region: "Fontaine", arme: "Spear", element: "Pyro", version: "4.3" },
    { name: "Razor", boss: "Stormterror", region: "Mondstadt", arme: "Claymore", element: "Electro", version: "1.0" },
    { name: "Fischl", boss: "Loup_du_Nord", region: "Mondstadt", arme: "Bow", element: "Electro", version: "1.0" },
    { name: "Beidou", boss: "Stormterror", region: "Liyue", arme: "Claymore", element: "Electro", version: "1.0" },
    { name: "Lisa", boss: "Stormterror", region: "Mondstadt", arme: "Catalyseur", element: "Electro", version: "1.0" },
    { name: "Raiden", boss: "Signora", region: "Inazuma", arme: "Spear", element: "Electro", version: "2.1" },
    { name: "Sara", boss: "Signora", region: "Inazuma", arme: "Bow", element: "Electro", version: "2.1" },
    { name: "YaeMiko", boss: "Raiden", region: "Inazuma", arme: "Catalyseur", element: "Electro", version: "2.5" },
    { name: "Shinobu", boss: "Raiden", region: "Inazuma", arme: "Sword", element: "Electro", version: "2.7" },
    { name: "Dori", boss: "Azhdaha", region: "Sumeru", arme: "Claymore", element: "Electro", version: "3.0" },
    { name: "Cyno", boss: "Raiden", region: "Sumeru", arme: "Spear", element: "Electro", version: "3.1" },
    { name: "Chongyun", boss: "Stormterror", region: "Liyue", arme: "Claymore", element: "Cryo", version: "1.0" },
    { name: "Kaeya", boss: "Loup_du_Nord", region: "Mondstadt", arme: "Sword", element: "Cryo", version: "1.0" },
    { name: "Qiqi", boss: "Loup_du_Nord", region: "Liyue", arme: "Sword", element: "Cryo", version: "1.0" },
    { name: "Diona", boss: "Tartaglia", region: "Mondstadt", arme: "Bow", element: "Cryo", version: "1.1" },
    { name: "Ganyu", boss: "Tartaglia", region: "Liyue", arme: "Bow", element: "Cryo", version: "1.2" },
    { name: "Rosaria", boss: "Tartaglia", region: "Mondstadt", arme: "Spear", element: "Cryo", version: "1.4" },
    { name: "Eula", boss: "Azhdaha", region: "Mondstadt", arme: "Claymore", element: "Cryo", version: "1.5" },
    { name: "Ayaka", boss: "Azhdaha", region: "Inazuma", arme: "Sword", element: "Cryo", version: "2.0" },
    { name: "Aloy", boss: "Signora", region: "Aucune", arme: "Bow", element: "Cryo", version: "2.1" },
    { name: "Shenhe", boss: "Signora", region: "Liyue", arme: "Spear", element: "Cryo", version: "2.4" },
    { name: "Layla", boss: "Wanderer", region: "Sumeru", arme: "Sword", element: "Cryo", version: "3.2" },
    { name: "Mika", boss: "Wanderer", region: "Mondstadt", arme: "Spear", element: "Cryo", version: "3.5" },
    { name: "Freminet", boss: "Garde_d'Apep", region: "Fontaine", arme: "Claymore", element: "Cryo", version: "4.0" },
    { name: "Wriothesley", boss: "Garde_d'Apep", region: "Fontaine", arme: "Catalyseur", element: "Cryo", version: "4.1" },
    { name: "Charlotte", boss: "Narval_Cosmique", region: "Fontaine", arme: "Catalyseur", element: "Cryo", version: "4.2" },
    { name: "Noelle", boss: "Stormterror", region: "Mondstadt", arme: "Claymore", element: "Geo", version: "1.0" },
    { name: "Ningguang", boss: "Loup_du_Nord", region: "Liyue", arme: "Catalyseur", element: "Geo", version: "1.0" },
    { name: "Zhongli", boss: "Tartaglia", region: "Liyue", arme: "Spear", element: "Geo", version: "1.1" },
    { name: "Albedo", boss: "Tartaglia", region: "Mondstadt", arme: "Sword", element: "Geo", version: "1.2" },
    { name: "Gorou", boss: "Signora", region: "Inazuma", arme: "Bow", element: "Geo", version: "2.3" },
    { name: "Itto", boss: "Signora", region: "Inazuma", arme: "Claymore", element: "Geo", version: "2.3" },
    { name: "YunJin", boss: "Signora", region: "Liyue", arme: "Spear", element: "Geo", version: "2.4" },
    { name: "Navia", boss: "Narval_Cosmique", region: "Fontaine", arme: "Claymore", element: "Geo", version: "4.3" },
    { name: "Xingqiu", boss: "Loup_du_Nord", region: "Liyue", arme: "Sword", element: "Hydro", version: "1.0" },
    { name: "Barbara", boss: "Loup_du_Nord", region: "Mondstadt", arme: "Catalyseur", element: "Hydro", version: "1.0" },
    { name: "Mona", boss: "Loup_du_Nord", region: "Mondstadt", arme: "Catalyseur", element: "Hydro", version: "1.0" },
    { name: "Tartaglia", boss: "Tartaglia", region: "Snezhnaya", arme: "Bow", element: "Hydro", version: "1.1" },
    { name: "Kokomi", boss: "Signora", region: "Inazuma", arme: "Catalyseur", element: "Hydro", version: "2.1" },
    { name: "Ayato", boss: "Raiden", region: "Inazuma", arme: "Sword", element: "Hydro", version: "2.6" },
    { name: "Yelan", boss: "Azhdaha", region: "Liyue", arme: "Bow", element: "Hydro", version: "2.7" },
    { name: "Candace", boss: "Raiden", region: "Sumeru", arme: "Spear", element: "Hydro", version: "3.1" },
    { name: "Nilou", boss: "Raiden", region: "Sumeru", arme: "Sword", element: "Hydro", version: "3.1" },
    { name: "Neuvillette", boss: "Garde_d'Apep", region: "Fontaine", arme: "Catalyseur", element: "Hydro", version: "4.1" },
    { name: "Furina", boss: "Narval_Cosmique", region: "Fontaine", arme: "Sword", element: "Hydro", version: "4.2" },
    { name: "Collei", boss: "Raiden", region: "Sumeru", arme: "Bow", element: "Dendro", version: "3.0" },
    { name: "Tighnari", boss: "Raiden", region: "Sumeru", arme: "Bow", element: "Dendro", version: "3.0" },
    { name: "Nahida", boss: "Wanderer", region: "Sumeru", arme: "Catalyseur", element: "Dendro", version: "3.2" },
    { name: "Alhaitham", boss: "Wanderer", region: "Sumeru", arme: "Sword", element: "Dendro", version: "3.4" },
    { name: "Yaoyao", boss: "Wanderer", region: "Liyue", arme: "Spear", element: "Dendro", version: "3.4" },
    { name: "Baizhu", boss: "Garde_d'Apep", region: "Liyue", arme: "Catalyseur", element: "Dendro", version: "3.6" },
    { name: "Kaveh", boss: "Garde_d'Apep", region: "Sumeru", arme: "Claymore", element: "Dendro", version: "3.6" },
    { name: "Kirara", boss: "Garde_d'Apep", region: "Inazuma", arme: "Sword", element: "Dendro", version: "3.7" },
];
        const randomCharacter = characters[Math.floor(Math.random() * characters.length)];//On choisis un personnage au hasard
        const randomCharacterName = randomCharacter.name;
        document.getElementById("sol_button").addEventListener("click", function(){ //Ici on va directement affecter le personnage choisi au bouton pour la soluce
            var soluce = document.getElementById("soluce");
            soluce.textContent = "Soluce : " + randomCharacterName;
        });

        const chartxtInput = document.getElementById("chartxt");
        const nameSuggestions = document.getElementById("nameSuggestions");
        const charbutButton = document.getElementById("charbut");

        chartxtInput.addEventListener("input", function () { //Ici on va filtrer la liste de tous les personnages pour qu'il n'en reste que les personnage contenant ce que l'utilisateur a déjà écris dans la barre de recherche
            const inputValue = chartxtInput.value.toLowerCase();
            const filteredNames = characters //Donc on copie le dictionnaire
                .map(character => character.name) //On récupère les noms
                .filter(name => name.toLowerCase().includes(inputValue)); //Puis on ne garde que ceux contenant ce que l'utilisateur a déjà écris

            updateSuggestions(filteredNames);
        });

        function updateSuggestions(suggestions) { //Ici on gère tous ce qui est recommendation type "barre de recherche" lorsque l'on tappe le nom d'un personnage
            if (suggestions.length > 0) {
                nameSuggestions.innerHTML = "";
                suggestions.slice(0, 2).forEach(name => { //Je limite la liste à 2 sinon elle prend beaucoup trop de place sur l'écran
                    const li = document.createElement("li");
                    li.textContent = name;
                    li.addEventListener("click", function () {
                        chartxtInput.value = name;
                        nameSuggestions.style.display = "none";
                    });
                    nameSuggestions.appendChild(li);
                });
                nameSuggestions.style.display = "block";
            } else {
                nameSuggestions.style.display = "none";
            }
        }

        charbutButton.addEventListener("click", function () {
            handleGuess();
        });

        chartxtInput.addEventListener("keyup", function (event) { //Ici c'est juste pour faire en sorte que l'entré d'un peronnage fonctionne aussi avec la touche Entrée, pour que ce soit plus pratique que de cliquer tout le temps sur un bouton
            if (event.key === "Enter") {
                handleGuess();
            }
        });

        function handleGuess() { //Ici on aura la réponse du progamme quand un utilisateur entre le nom d'un personnage et comment il le gère
            const char_guess = chartxtInput.value.toLowerCase();
            fetch('decrem_score.php', {//Je fais du AJAX pour éviter les erreurs type PHP dans JS, je le refait juste après dans cette fonction
                method: 'POST', //Ce AJAX là sert juste à décrémenter de score actuel de la partie
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    decrement: true
                }),
            })
            .then(response => response.text())
            .then(data => {
                console.log(data);

            characters.forEach(character => { //Ici on va créer la nouvelle ligne qui aceuillera le nouveau personnage deviné, puis on ajoutera et remplira les cases
                if (char_guess === character.name.toLowerCase()) {
                    const newRow = document.createElement("tr");

                    const attributes = ["name", "boss", "region", "element", "arme", "version"];

                    attributes.forEach(attribute => {
                        const cell = document.createElement("td");

                if (attribute === "name") { //On gère l'affichage pour chaque catégorie
                    const img = document.createElement("img");
                    img.src = `../images/persos/${character.name.toLowerCase()}.jpg`;
                    img.alt = character.name;
                    img.style.maxWidth = "80px";
                    img.style.maxHeight = "80px";
                    img.style.margin = "5px";
                    img.classList.add("rotate-once");
                    cell.appendChild(img);
                }
                else if (attribute === "arme") {
                    const img = document.createElement("img");
                    img.src = `../images/armes/${character.arme.toLowerCase()}.jpg`;
                    img.alt = character.arme;
                    img.style.maxWidth = "80px";
                    img.style.maxHeight = "80px";
                    img.style.margin = "5px";
                    img.classList.add("rotate-once");
                    cell.appendChild(img);
                }
                else if (attribute === "element") {
                    const img = document.createElement("img");
                    img.src = `../images/elements/${character.element.toLowerCase()}.jpg`;
                    img.alt = character.element;
                    img.style.maxWidth = "80px";
                    img.style.maxHeight = "80px";
                    img.style.margin = "5px";
                    img.classList.add("rotate-once");
                    cell.appendChild(img);
                }
                else if (attribute === "region") {
                    const img = document.createElement("img");
                    img.src = `../images/regions/${character.region.toLowerCase()}.jpg`;
                    img.alt = character.region;
                    img.style.maxWidth = "80px";
                    img.style.maxHeight = "80px";
                    img.style.margin = "5px";
                    img.classList.add("rotate-once");
                    cell.appendChild(img);
                }
                else if (attribute === "boss") {
                    const img = document.createElement("img");
                    if (character.boss === "Tartaglia"){
                        img.src = `../images/boss/childe.jpg`;
                    }
                    else if (character.boss === "Raiden"){
                        img.src = `../images/boss/shogun.jpg`;
                    }
                    else{
                        img.src = `../images/boss/${character.boss.toLowerCase()}.jpg`;
                    }
                    img.alt = character.boss;
                    img.style.maxWidth = "80px";
                    img.style.maxHeight = "80px";
                    img.style.margin = "5px";
                    img.classList.add("rotate-once");
                    cell.appendChild(img);
                }
                else {
                    cell.textContent = character[attribute];
                }

                if (attribute === "version") {
                    const versionComparison = parseFloat(character.version) - parseFloat(randomCharacter.version);
                    let arrowHtml = (versionComparison > 0) ? "&#9660;" : (versionComparison < 0 ? "&#9650;" : "");
                    if (Math.abs(versionComparison) > 1.0) {
                        arrowHtml += (versionComparison > 0) ? "&#9660;" : (versionComparison < 0 ? "&#9650;" : "");
                    }
                    cell.innerHTML = `${character.version} ${arrowHtml}`;
                } 
                else {
                    cell.style.backgroundColor = (character[attribute] === randomCharacter[attribute]) ? "#8bc34a" : "#f44336";
                }

                newRow.appendChild(cell);
            });

            const tableBody = document.querySelector(".guess-header tbody");
            const firstRow = tableBody.querySelector(".guess-item-header.row");

            tableBody.insertBefore(newRow, firstRow.nextSibling);
            nameSuggestions.style.display = "none";
            chartxtInput.value = "";
            if (char_guess === randomCharacter.name.toLowerCase()) { //Si l'utilisateur trouve le bon personnage
                setTimeout(function(){
                    fetch('update_score.php', { //Ce AJAX là va permettre d'ajuster le score total (score affiché) du joueur
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            score: <?php echo $_SESSION['score']; ?>
                        }),
                    })
                    .then(response => response.text())
                    .then(data => {
                        console.log(data);
                        location.reload(); //On recharge la page automatiquement après 10sec si l'utilisateur ne le refait pas lui-même
                    })
                    .catch(error => console.error('Erreur lors de la mise à jour du score :', error));
                }, 10000)
            }
        }
    });
})
.catch(error => console.error('Erreur lors de la mise à jour du score :', error));
}

    </script>
</body>
</html>
