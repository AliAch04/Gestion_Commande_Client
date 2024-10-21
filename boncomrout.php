<?php 
    $numero = $_GET['numero'];
    $date = $_GET['date'];
    $duree = $_GET['duree'];
    $montant = $_GET['montant'];
    $formation = $_GET['formation'];
    $nomClient = $_GET['clientNom'];
    $prenomClient = $_GET['clientPrenom'];
    $tva = 1.2;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styleBonCommande.css">
    <link rel="stylesheet" href="styleN1.css">
    <title>Bon de commande</title>

    <style>
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
            <div >TVA : <span><?php echo "20%" ?></span></div>
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
    
</body>
</html>
    
    
    
    