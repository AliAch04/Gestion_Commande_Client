<?php

session_start();

if (!isset($_SESSION['user_authenticated']) || $_SESSION['user_authenticated'] !== true) {
    header("Location: Identification.php");
    exit();
}
    function clear_data($var){
        $var = stripslashes($var);
        $var = trim($var);
        $var = strip_tags($var);

        return $var;
    }
    $output = "";
    $nom = $type = "";
    $prix = "";
    $errors = [];
    
    if(isset($_POST['submitP'])){
        header('Location: InscriptionCommande.php');
    }

    if (empty($_POST['nom']) && empty($_POST['type']) && empty($_POST['prix'])) {
        $output = "Veuillez-vous entre les données!";
    } else {

    if (empty($_POST['nom'])) {
        $errors[] = "Il faut ajouter le Nom de la Formation!!";
    } else {
        // Nettoyage des données
        $nom = clear_data($_POST['nom']);
        // Vérifier la validité des données
        if(!preg_match('/^[a-zA-Z\d_çé+]{1,15}$/i', $nom)){
            $errors[] = "Veuillez entrer au moins 3 caractères et au maximum 15!";
        }
    }

    if (empty($_POST['type'])) {
        $errors[] = "Il faut ajouter le type de la formation!";
    } else {
        // Nettoyage des données
        $type = clear_data($_POST['type']);
        // Vérifier la validité des données
        if(!preg_match('/^[a-zA-Z\d_çé+]{3,15}$/i', $type)){
            $error[] = "Veuillez entrer au moins 3 caractères et au maximum 15!";
        }
    }

    if (empty($_POST['prix'])) {
        $errors[] = "Il faut choisir une prix référentielle!";
    } else {
        // Nettoyage des données
        $prix = clear_data($_POST['prix']);
        // Vérifier la validité des données
        if(!preg_match('/^[0-9]{2,6}$/', $prix)){
            $errors[] = "Veuillez entrer un nombre valide!";
        }
    }
    }

    

            if(empty($errors) ){
                include('connection.php');
                // Utilisez une requête préparée pour sécuriser la requête SQL
                $sql = "SELECT `Nom`, `Type` FROM `Formation` ";
                $requet = mysqli_query($conn, "SELECT `Nom`, `Type` FROM `Formation`");
                if($requet){
                    while ($secretaire = mysqli_fetch_assoc($requet)) {

                        // Vérifier si le nom ou le prénom entrée est identique
                        if ($nom == $secretaire['Nom'] && $type == $secretaire['Type']) {
                            $output = "* Cette formation est déjà existé!";
                        }
                    }
                    
                }else{
                    $output = "Erreur lors de récupérer les données!";
                }
                if (empty($output)) {
                    $sql ="INSERT INTO Formation (Nom, Type, Prix) VALUES ('$nom', '$type', '$prix')";
                    mysqli_query($conn, $sql);
                    $output = "Formation ajouté avec succès.";
                }

                
                mysqli_close($conn);
            }else {
                // Handle and display multiple errors with line breaks
                $output = implode("<br>", $errors);
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
    <title>Ajouter une Formation</title>
    
    <style>
        .titreX .titreShadow{
    position: absolute;
    top :25%;
    font-size: 2.15rem;
    background-color: var(--primary-color) ;
    height: 2.3em;
    width: 21%;
    color: var(--body-color);
    border-radius: 29px;
    box-shadow: 0px 2px 10px black;
    z-index: -1;
}
    .titreX h1{
    position: absolute;
    top :49%;
    font-size: 1.6rem;
    color: var(--body-color) ;
   
}

.button{
    display: flex;
    justify-content: center;
    align-items: center;
    gap : 20px;
}

button{
    padding: 0 10px;
    margin-bottom: 25px;
    border: none;
    color : var(--primary-color);
    border-radius: 6px;
    height: 100%;
    width: 100%;
    font-size: 15px;
    font-weight: 500;
    background-color: var(--primary-color-ligh);
    cursor: pointer;
    transition: all 0.4s ease;
}
    .informer{
    opacity: 0;
    bottom: -30%;
    left: 11%;
    position: absolute;
    min-height: 60px;
    min-width: 350px;
    padding: 10px;
    border-radius: 6px;
    background-color: #5ca0fe;
    display: flex;
    justify-content: center;
    align-items: center;
    box-shadow: 0px 1px 10px grey;
}

.cont{
    position: relative;
    right: -45%;
    bottom: 40%;
    height: 40px;
}

.cont .retour{
    padding: 0 35px;
    
    margin-top: 35px;
    border: none;
    color : #695CFE;
    letter-spacing: 1px;
    border-radius: 6px;
    height: 100%;
    width: 100%;
    font-size: 17px;
    font-weight: 500;
    background-color: #f6f5ff;
    cursor: pointer;
    transition: all 0.4s ease;
    z-index: 147;
}
    </style>
</head>
<body>
    <nav>       
        <div class="titreX">
        
        <h1>Ajouter Formation</h1>
        <div class="titreShadow"></div>
            <div class="cont">
                <button class ="retour" onclick="Retour()"  type="submit"><i class='bx bx-log-out-circle icon' ></i></button>   
            </div>
        </div>
    </nav>

    

<section class="main">
    <div class="container">
    <div class="form">
        <div class="form-login">
            <span class="title">Détails de la Formation</span>
            <form method="POST" action="ajouterFormation.php">
                <div class="input-field">
                    <input type="text" name="nom" value="<?php echo $nom; ?>" placeholder="Entrez le Nom de formation">
                    <i class='bx bx-user-circle icon'></i>
                </div>

                <div class="input-field">
                <input type="text" name="type" value="<?php echo $type; ?>" placeholder="Entrez le Type de formation">
                    <i class='bx bx-book icon'></i>
                    
                </div>

                <div class="input-field">
                <input type="text" name="prix" value="<?php echo $prix; ?>" placeholder="Entrez le Prix référentielle ">
                    <i class='bx bx-purchase-tag icon'></i>
                    
                </div>

                <div class="input-field button">
                    <button class="submit" type="submit" name="submit" value="Login">Ajouter</button>
                    
                </div>
                
                <div class="informer" id="informer">  
                    <p style="color: <?php echo ($output === 'Formation ajouté avec succès.') ? 'green' : 'red'; ?>">
                        <?php 
                            echo $output; ?>
                        
                    </p>          
                </div>
             </form>
        </div>   
    </div>
    </div>
</section>

<script src="scriptN.js"></script>

<script>
    <?php
    if (!empty($output) && isset($_POST['submit'])) {
        echo "fadeIn();";
    }
    ?>

function Retour(){
    window.location.href = "InscriptionCommande.php";
    }
</script>
    
    
    
</body>
</html>

