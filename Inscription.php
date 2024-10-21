<?php 
    session_start();

    // Check if the user is authenticated
    if (!isset($_SESSION['user_authenticated1']) || $_SESSION['user_authenticated1'] !== true) {
        // Redirect to the login page
        header("Location:identification.php");
        exit();
    }
    
?> 
<?php

function clear_data($var){
    $var = stripslashes($var);
    $var = trim($var);
    $var = strip_tags($var);

    return $var;
}
    // Déclaration de variable de message d'erreur
$output = "";

// Initialiser les données
$nom = $prenom = $pass = $repass = "";

if (isset($_POST['submit'])) {

    $errors = [];
    if (empty($_POST['nom']) && empty($_POST['prenom']) && empty($_POST['password']) && empty($_POST['re-password'])) {
        $output = "Veuillez-vous entre les données!";
    } else {

    if (empty($_POST['nom'])) {
        $errors[] = "Il faut Ajouter un Nom!";
    } else {
        // Nettoyage des données
        $nom = $_POST['nom'];
    }

    if (empty($_POST['prenom'])) {
        $errors[] = "Il faut Ajouter un Prenom!";
    } else {
        // Nettoyage des données
        $prenom = $_POST['prenom'];
    }

    if (empty($_POST['password'])) {
        $errors[] = "Il faut Ajouter un password!";
    } else {
        // Nettoyage des données
        $pass = $_POST['password'];
    }

    if (empty($_POST['re-password'])) {
        $errors[] = "Il faut retaper le password!";
    } else {
        // Nettoyage des données
        $repass = $_POST['re-password'];
        if ($repass != $pass) {
            $errors[] = "Il faut retaper le password correctement!";
        }
    }
}
    if (empty($errors)) {
        include('connection.php');

        // Vérifier si le compte est déjà existé

        $requet = mysqli_query($conn, "SELECT `Nom`, `Prenom` FROM `secretaire`");
        if ($requet) {
            // Récupérer les enregistrements
            while ($secretaire = mysqli_fetch_assoc($requet)) {

                // Vérifier si le nom ou le prénom entrée est identique
                if ($nom == $secretaire['Nom'] && $prenom == $secretaire['Prenom']) {
                    $output = "Ce compte est déjà existé!";
                }
            }
            if (empty($output)) {
                mysqli_query($conn, "INSERT INTO `secretaire`(`Nom`, `Prenom`, `Password`) VALUES ('$nom','$prenom','$pass')");
                $output = "Le compte créé avec succès!";
            }
        } else {
            $output = "Erreur lors de récupérer les données!";
        }
    } else {
        // Handle and display multiple errors with line breaks
        $output = implode("<br>", $errors);
    }
}

   if (!empty($output) && isset($_POST['submit'])) {
    
    
    
}else{

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styleN1.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>authentification</title>
    
    <style>
        .form .title::before{
            content: '';
            position: absolute;
            left: 7.5%;
            bottom: 87%;
            height: 3px;
            width: 69px;
            background: var(--primary-color3);
}

.informer1{
    z-index: 10;
    top: 15%;  /* Modifié pour être au milieu en bas */
    left: 50%;    /* Modifié pour être au milieu horizontalement */
    transform: translateX(-50%);  /* Ajustement pour centrer horizontalement */
    position: fixed;
    max-height: 100px;
    min-width: 400px;
    padding: 10px;
    border-radius: 6px;
    background-color: #5ca0fe;
    display: flex;
    justify-content: center;
    align-items: center;
}

.mainT{
    display: flex;
    align-items: center;
    justify-content: center;
    position: fixed;
    top: 18%;  /* Modifié pour être au milieu en bas */
    left: 50%;    /* Modifié pour être au milieu horizontalement */
    transform: translateX(-50%);  /* Ajustement pour centrer horizontalement */
    position: fixed;
    width: 50%;        
        }

    </style>
</head>

<body>

<nav>
        
    <div class="titreX">
        <div class="to-img">
            <img src="Images/GAFP.png" alt="">
        </div>
        <h1>Application de gestion des commandes</h1>
        <div class="titreShadow"></div>
    </div>
        
        
</nav>


    <div class = "informer1" id="informer">
        <p style="color: <?php echo ($output === 'Le compte créé avec succès!') ? 'green' : 'red'; ?>">
            <?php echo $output; ?>
        </p>
    </div>


<section class="mainT">
    <div class="container">
    <div class="form">
        <div class="form-login">
            <span class="title"> Inscription</span>

            <form method="POST">
                <div class="input-field">
                    <input type="text" name ="nom" value="<?php echo $nom; ?>" placeholder="Entre le Nom">
                    <i class='bx bx-user-circle icon'></i>
                </div>

                <div class="input-field">
                    <input type="text" name ="prenom" value="<?php echo $prenom; ?>" placeholder="Entre le Prenom">
                    <i class='bx bx-user-circle icon'></i>
                </div>

                <div class="input-field">
                    <input type="password" name = "password" placeholder="Tapez un mot de passe">
                    <i class='bx bx-lock-alt icon'></i>
                    
                </div>

                <div class="input-field">
                    <input type="password" name = "re-password" class="password" placeholder="Retapez le mot de passe">
                    <i class='bx bx-lock-alt icon'></i>
                    <i class='bx bx-hide show-hide' ></i>
                </div>

                <div class="input-field button">
                <button class="submit" type="submit" name="submit">Enregistrer</button>
                </div>
                
                <div class="login-signup">
                    <span>Déja avez un compte! : 
                    <button type="button" onclick="retour()" id="toggleButton" class="but1 text signup-text">Login</button>
                    </span>
                </div>
                
                    
            
            </form>
        </div>
         
    </div>
    
    </div>
</section>  
<script src="scriptN.js"></script>

<script>
    

    const show_hide = document.querySelectorAll(".show-hide"),
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
function getDown() {
    var myMain = document.querySelector('.mainT');

    // Démarre le mouvement vers 30%
    myMain.style.top = '29%';

    // Démarre le mouvement de 30% à 18% après 3 secondes
    setTimeout(function() {
        myMain.style.transition = 'top 3s';  // Définit la transition sur 3 secondes
        myMain.style.top = '18%';
    }, 1981);
}

function retour(){
    window.location.href ="Identification.php";
}

</script>

<script>
    <?php
    if (!empty($output) && isset($_POST['submit'])) {
        echo "fadeIn();";
        echo "getDown();";
    }
    ?>
</script>

</body>

</html>