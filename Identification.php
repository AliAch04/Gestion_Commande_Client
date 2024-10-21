<?php
session_start();
$output = "";
$nom = $pass = "";
$_SESSION['user_authenticated'] = false;

if (!empty($output) && isset($_POST['submitJ'])) {
    echo "<script>fadeIn();</script>";
}

if (isset($_POST['submit'])) {
    if (empty($_POST['nom']) && empty($_POST['password'])) {
        $output = "Veuillez entrer les données!";
    } else {
        $nom = isset($_POST['nom']) ? $_POST['nom'] : '';
        $pass = isset($_POST['password']) ? $_POST['password'] : '';

        if (empty($nom) ) {
            $output = "Veuillez entrer le Nom ou le Prenom!";
        } else if(empty($pass)){
            $output = "Veuillez entrer le mot de passe!";
        }
        
        else {
            include('connection.php');
            $sql = "SELECT Nom, Prenom, Password FROM secretaire";
            $result = mysqli_query($conn, $sql);

            if ($result) {
                while ($secretaire = mysqli_fetch_assoc($result)) {
                    if (($nom === $secretaire['Nom'] || $nom === $secretaire['Prenom']) && $pass === $secretaire['Password']) {
                        $_SESSION['NomSec'] = $secretaire['Nom'];
                        $_SESSION['PrenomSec'] = $secretaire['Prenom'];
                        $_SESSION['PassSec'] = $secretaire['Password'];
                        $_SESSION['user_authenticated'] = true;
                        header('Location: InscriptionCommande.php');
                        exit;
                    } else {
                        $output = "* Le Login ou Password est incorrect! Veuillez réessayer!";
                    }
                }
            } else {
                $output = "Erreur lors de la récupération des données!";
            }
        }
    }
}

if (!empty($output) && isset($_POST['submit'])) {
    echo "<script>fadeIn();</script>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styleN1.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <title>Identification</title>
   
    <style>

.mainT{
    display: flex;
    align-items: center;
    justify-content: center;
    position: fixed;
    right: 49%;
    left: 30.5%;
    top: 0;
    display: none;

    width: 50%;
    
}
.containerT{
    margin-top: 9em;
    position: relative;
    max-width: 600px;
    background: var(--primary-color2);
    box-shadow: 0px 2px 10px black;
    border-radius: 10px;
}
.containerT .formt{
    padding: 30px;
    min-width: 600px;
}

.submitJ{
    padding: 3px 22px;
    border: none;
    color : var(--primary-color);
    border-radius: 4px;
    height: 60%;
    width: 20%;
    font-size: 16px;
    margin-left: 8px;
    font-weight: 400;
    background-color: var(--primary-color-ligh);
    cursor: pointer;
    transition: all 0.4s ease;
}

.submitJ:hover{
    background-color: var(--primary-color);
    color: var(--primary-color-ligh);
}


.formt input{
    height: 35px;
    width: 45%;
    position: relative;
    margin: 7px 33px 7px 0;
    
    font-size: 16px;
    padding: 0 18px;
    border: none;
    outline: none;
    border-bottom: 2px solid #ccc;
    border-top: 2px solid #ccc transparent;

    transition: all 0.4s ease;
    background: var(--primary-color2);
}

.formt h2{
    font-size: 27px;
    font-weight: 600;
    color: var(--primary-color-ligh);
    margin-bottom: 30px;
}

.formt h2::before{
    content: '';
    position: absolute;
    left: 5.4%;
    bottom: 69%;
    height: 4px;
    width: 100px;
    background: var(--primary-color3);
}


i{
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    color: var(--toggle-color);
    font-size: 23px;
    transition: all 0.4s ease;
}

i.show-hide{
    right :52.5%;
    top: 54.2%;
    cursor: pointer;
    padding: 10px;
    
}


.formt .gerant{
    margin-top: 30px;
    color: var(--primary-color-ligh);
}

.informer {
opacity: 0;
bottom: 19%;  /* Modifié pour être au milieu en bas */
left: 50%;    /* Modifié pour être au milieu horizontalement */
transform: translateX(-50%);  /* Ajustement pour centrer horizontalement */
position: fixed;
max-height: 60px;
min-width: 400px;
padding: 10px;
border-radius: 6px;
background-color: #5ca0fe;
display: flex;
justify-content: center;
align-items: center;
}



.input-field input:is(:focus){
border-bottom-color: var(--primary-color);
}

.input-field input:is(:focus) ~ i{
color: var(--primary-color);
}


.block{
    pointer-events : none;
    user-select: none;
}
</style>

</head>

<body>
    <div class="toBlur">

<nav>       
    <div class="titreX">
        <div class="to-img">
            <img src="Images/GAFP.png" alt="">
        </div>
        <h1>Application de gestion des commandes</h1>
        <div class="titreShadow"></div>
    </div>
</nav>

<section class="main">
    <div class="container">
    <div class="form">
        <div class="form-login">
            <span class="title">Identifcation</span>
            <form method="POST" action="Identification.php" id="myForm">
                <div class="input-field">
                    <input type="text" name ="nom" placeholder="Entre le Nom ou Prenom" class="myInput" >
                    <i class='bx bx-user-circle icon'></i>
                </div>

                <div class="input-field">
                    <input type="password" name = "password" class="password" placeholder="Entre le mot de passe" class="myInput" >
                    <i class='bx bx-lock-alt icon'></i>
                    <i class='bx bx-hide show-hide' ></i>
                </div>

                <div class="input-field button">
                    <button class="submit" onclick="validateForm()" type="submit" name="submit" value="Login">Login</button>
                </div>
                
                <div class="login-signup">
                    <span>Nouveau ici! : 
                        
                    <button type="button" onclick="blured()" id="toggleButton" class="but1 text signup-text">Créer un compte</button>
                  </span>
                </div>
                
             </form>
        </div>   
    </div>
    </div>
</section>

</div>


<div class="informer" id="informer">  
                    <p style="color:red; align-text:centre;">
                        <?php 
                            echo $output; ?>
                        
                    </p>          
                </div>

    <div class="mainT" style="display: none;">
        <div class="containerT">
            <form method="post" class="formt">
                <h2>Enter le mot de passe de sécurité</h2>
                <input type="password" class="password" id="passwordInput" name="password" placeholder="Enter le Password">
                <i class='bx bx-hide show-hide'></i>
                <button class="submitJ submit" name="submit" id="entre" type="submit">Entrer</button>
                <button class="submitJ" id="retour" onclick="deconx()">Retour</button>
                <p class="gerant">Si vous ne le connaissez pas, veuillez demander au gérant.</p>
            </form>
            <div class="informer" id="informer">
                <p style="color:red; align-text:centre;">
                    <?php echo $output; ?>
                </p>
            </div>
        </div>
    </div>


    <script src="scriptN.js"></script>

    <script>
        function blured() {
            var body = document.querySelector('.toBlur');
            var myMain = document.querySelector('.mainT');
            var currBlur = 0;
            var tarBlur = 7;

            var interval1 = setInterval(function () {
                currBlur++;
                body.style.filter = 'blur(' + currBlur + 'px)';

                if (currBlur >= tarBlur) {
                    clearInterval(interval1);
                    body.classList.add('block');
                    myMain.style.display = 'block';
                }
            }, 50);
        }

        function deconx() {
            var body = document.querySelector('.toBlur');
            var myMain = document.querySelector('.mainT');
            var currBlur = 7;
            var tarBlur = 0;

            body.classList.remove('block');
            myMain.style.display = 'none';

            var interval1 = setInterval(function () {
                currBlur--;

                body.style.filter = 'blur(' + currBlur + 'px)';

                if (currBlur <= tarBlur) {
                    clearInterval(interval1);
                }
            }, 50);
        }
    </script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ...

        // Écouteur pour le bouton "Entrer"
        document.getElementById('entre').addEventListener('click', function(event) {
            event.preventDefault();
            validatePassword();
        });

        // Écouteur pour le bouton "Retour"
        document.getElementById('retour').addEventListener('click', function(event) {
            event.preventDefault();
            deconx();
        });
    });

    function validatePassword() {
        var password = document.getElementById('passwordInput').value;

        fetch('validatePassword.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'password=' + encodeURIComponent(password),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                localStorage.setItem('user_authenticated1', 'true');
                window.location.href = 'Inscription.php';
            } else {
                var outputElement = document.getElementById('informer');
                outputElement.innerHTML = "<p style='color:red; text-align:center;'>Mot de passe incorrect! Veuillez réessayer.</p>";
                fadeIn();
            }
        })
        .catch(error => console.error('Erreur lors de la validation du mot de passe:', error));
    }
</script>

<script>
    const show_hide = document.querySelectorAll(".show-hide");
    field = document.querySelectorAll(".password");

    show_hide.forEach(eyeIcon =>{
    eyeIcon.addEventListener("click",() =>{
        field.forEach(field => {
            if (field.type === "password") {
                field.type = "text";
                // Change the class of the icon
                eyeIcon.classList.replace("bx-hide", "bx-show");
            } else {
                field.type = "password";
                eyeIcon.classList.replace("bx-show", "bx-hide");
            }
        });
        })
    });
</script>

<script>
    <?php
    if (!empty($output) && isset($_POST['submit'])) {
        echo "fadeIn();";
    }
    ?>
</script>
</body>

</html>
