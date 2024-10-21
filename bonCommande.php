<?php 
    session_start();
    $nomClient = $_SESSION['Nom'] ;
    $prenomClient = $_SESSION['Prenom']  ;
    $formation = $_SESSION['Formation'] ;
    $date = $_SESSION['Date'] ;
    $duree = $_SESSION['Duree'] ;
    $montant = $_SESSION['Montant'] ;
    $numero = $_SESSION['Numero'];
    $monsieur = isset($_GET['monsieur']) ? urldecode($_GET['monsieur']) : '';
    $emailEnvoyeAvecSucces = isset($_GET['emailEnvoyeAvecSucces']) ? $_GET['emailEnvoyeAvecSucces'] : false;
    $tva = 1.12;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styleBonCommande.css">
    <link rel="stylesheet" href="styleN1.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Bon de commande</title>

    <style>

        
.success-box {
   position: fixed;
   top:30%;
   right: 50%;
   transform: translate(49% ,49%);
   width: 17%;
  max-height: 150px;
   background-color: #00ff00F1; /* Fond semi-transparent */
   justify-content: center;
   align-items: center;
   text-align: center;
   font-size: 18px;
   font-weight: bold;
   padding: 10px;
   border-radius: 15px ;

   color: #fff; /* Couleur de la police verte, ajustez selon vos besoins */
   opacity: 0; /* Initialement transparent */
   transition: opacity 1.5s ease; /* Transition de 1.5 secondes */
   z-index: 1000;
}
.titreX{
    background: var(--primary-color) ;
    display: flex;
    justify-content: center ;
    align-items: center;
    height: 5.1em;
    width: 100em;
    position: relative;
    
    
}
.titreX .titreShadow{
    position: absolute;
    top :25%;
    font-size: 2.15rem;
    background-color: var(--primary-color) ;
    height: 2.3em;
    width: 18%;
    color: var(--body-color);
    border-radius: 29px;
    box-shadow: 0px 2px 10px black;
    z-index: -1;
}

.titreX h1{
    position: absolute;
    top :55%;
    font-size: 1.6rem;
    color: var(--body-color) ;
   
}

.line {
    width: 62%; /* Adjust to the desired width */
    height: 2px; /* Adjust to the desired height */
    background-color: black; /* Adjust the color */
}


.success-box-top{
   position: fixed;
   top:10%;
   min-height: 45px;
   width: 100%;
   background-color: rgba(255, 255, 255, 0.8); /* Fond semi-transparent */
   justify-content: center;
   align-items: center;
   text-align: center;
   font-size: 18px;
   font-weight: bold;
   padding-top: 10px;
   color: #006400; /* Couleur de la police verte, ajustez selon vos besoins */
   animation: fadeInOut 10s forwards; /* Transition de 1.5 secondes */
   z-index: 1000;
}

.close-btn {
    border: none;
    cursor: pointer;
    color: gray;
    background-color: transparent;
}

@keyframes fadeInOut {
    0% {
        opacity: 0;
    }
    5% {
        opacity: 1;
    }
    95% {
        opacity: 1;
    }
    100% {
        opacity: 0;
        display: none; 
    }
}

    </style>
</head>
<body>
    <nav>       
        <div class="titreX">
            <h1>Bon Commande</h1>
        <div class="titreShadow"></div>
        </div>
    </nav>
    <div style="margin-top : 2.3em;"><?php echo "<h1 class='titre'>La Commande N°$numero a été créée avec succès!</h1>"; ?>
</div>
<?php
    if ($emailEnvoyeAvecSucces) {
        echo "<div class='success-box-top' style='opacity: 1; color:red;'>Tous les enseignants de cette formation ont atteint leur capacité maximale ou il n'a aucun(e) enseignant! \n. Mais un e-mail a passer pour <b>M. ".$monsieur."</b>   <button onclick='closeSuccessBox(this.parentNode)' class='close-btn'><i class='bx bxs-x-circle' style = 'font-size: 17px;'></i></button>
        </div>  " ; 
    }
?>
    <section class="Imprimer">
    <div class="section">
        <div class="text"><strong>Bon de Commande</strong></div>
        <div class="line"></div>
        <h1 class="titreGAFP">GAFP</h1>
        <div class="text" >Reçu N° : <span><?php echo sprintf('%08d', $numero);  ?></span></div>
        <div class="text" >Payé par : <span><?php echo  $nomClient. " ".$prenomClient ;?></span></div>
        <div class="text" >Inscrit le : <span><?php echo $date ?></span></div>
        <div class="text" >Durée : <span><?php echo $duree." jours" ?></span></div>
        <div class="text" >Formation : <span><?php echo $formation ?></span></div>
        <div class="text">Montant net : <span><?php echo $montant." DH" ?></span> </div>
        <div class="text" style="display : flex; flex-direction : row; gap: 3em">
            <div >Montant brut : <span><?php echo $montant * $tva." DH"; ?></span></div>
            <div >TVA : <span><?php echo "12%" ?></span></div>
        </div>
        
        
        
        <div style="padding: 25px 0px 0px 40px; font-size: 18px; font-weight :600; ">Signature:</div>
    </div>
    </section>
    
    <div class="boutons">
        <button onclick="window.print()">Imprimer</button>
        <button onclick="Retour()">Retour</button>  
        
    </div>

    <script>
        function Retour(){
        window.location.href = "InscriptionCommande.php";
    }   
    </script>

    
<script>
    // Afficher le div
    var successBox = document.querySelector('.success-box-top');

    // Appeler la fonction après 3 secondes pour déclencher l'animation
    setTimeout(function() {
        successBox.style.animation = 'fadeInOut 4.5s forwards';
    }, 3000);
</script>

<script>
    function closeSuccessBox(element) {
        element.style.display = 'none';
    }
</script>

</body>
</html>