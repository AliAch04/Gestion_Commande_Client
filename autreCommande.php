<?php
ob_start();
session_start();

// Check if session variables exist and assign them to local variables
    $IDClient =0;
    $nomClient = $_SESSION['nomC'];
    $prenomClient = $_SESSION['prenomC'];
    $nomsec = $_SESSION['nomsec'];
    $prenomsec = $_SESSION['prenomsec'];
    $passsec = $_SESSION['passsec'];
    
    //Récupérer le ID de client à partir de $nomClient
    include('connection.php');
    $sql = "SELECT ID FROM client WHERE Prenom = '$prenomClient' AND Nom = '$nomClient'";
    $requete = mysqli_query($conn, $sql);
    if ($requete) {
        $ligne = mysqli_fetch_assoc($requete);
        if ($ligne) {
            $IDClient = $ligne['ID'];
        } else {
            echo "Aucune ID trouvée!";
        }
    } else {
        echo "Aucune valeur trouvée";
    }
    //echo $IDClient;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styleN1.css">
    <title>Autre Commande</title>
    <style>

        .table {
            width: 70%;
            margin: 20px auto;
            box-shadow: 0px 1px 10px grey;
            border: 3px solid white;
            border-radius: 15px;
        }

        .tr {
            width: 100%;
            display: flex;
            justify-content: center;
            flex-direction: row;
            text-align: left;
            border-bottom: 3px solid white;
            border-bottom-left-radius: 15px;
            border-top-right-radius: 15px;
            background-color: rgb(127, 178, 255);
            transition: 0.4s ease;
        }

        .trT {
            width: 100%;
            display: flex;
            justify-content: center;
            flex-direction: row;
            text-align: left;
            border-bottom: 3px solid white;
            border-radius: 15px;
            background-color: #695CFE;
            transition: 0.4s ease;
        }

        .bold {
            padding: 20px;
            height: 100%;
            width: 30%;
            background-color:#695CFE;
            transition: 0.4s ease;
        }
        .bold1 {
            padding: 20px;
            height: 100%;
            width: 30%;
            background-color: rgb(127, 178, 255);
            transition: 0.4s ease;
        }

        .trT:hover{
            background-color: #857aff;
        }
        .trT:hover .bold{
            background-color: #857aff;
            
        }

        .tr:hover{
            background-color: #d0efff;
        }
        .tr:hover .bold1{
            background-color: #d0efff;
            
        }
        .color{
            padding: 10px;
            background-color: #d0efff;
        }

        .informer{
    opacity: 1;
    position: absolute;
    min-height: 60px;
    min-width: 350px;
    padding: 10px;
    border-radius: 6px;
    background-color: #5ca0fe;
    display: flex;
    justify-content: center;
    align-items: center;
    color: black;
    box-shadow: 0px 1px 10px grey;
}

.centerTo1{
    display: flex;
    justify-content: center;
    align-items: center;
}

.centerTo{
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 2.9em;
    font-size: 25px;
    background-color: #d0efff;
    height: 90px;
    border-radius: 6px;
    letter-spacing: 1px;
    padding: 10px;
    box-shadow: 0px 1px 10px grey;
    
}
    </style>
</head>
<body>

<nav>       
        <div class="titreX">
        
        <h1>Inserer autre Commande</h1>
        <div class="titreShadow"></div>
        </div>
    </nav>

    <section class="centerTo1">
    <div class="centerTo">
                
                <div ><?php echo "Le Client "?> <strong><?php echo $nomClient." ".$prenomClient ?></strong> <?php echo " est déja existé!";?>
                </div>
            </div>
    </section>

    <div style = "margin:4em "></div>
    <?php echo "
    <div class='table'>
            <div class='trT' >
                <div class='bold'>Date d'Insription</div>
                <div class='bold'>Formation</div>
                <div class='bold'>Durée</div>
            </div>";

        //Les jointures  table client avec table commande
        include('connection.php');
        $sql = "SELECT commande.Formation, commande.Date, commande.Duree 
                FROM client INNER JOIN commande ON client.ID = commande.Client 
                Where client.ID = '$IDClient' ";

        $requet = mysqli_query($conn,$sql);

        $dateActuelle = strtotime(date("Y-m-d"));
        if($requet){
            //Récuperer les enregitrements 
            while ($ligne = mysqli_fetch_assoc($requet)){
                $dateFormation = strtotime($ligne['Date']);
                $date = new DateTime($ligne['Date']);
                $date->add(new DateInterval("P{$ligne['Duree']}D")); 
                $resultDate = $date->format('Y-m-d');
                
                //Calculer le reste des jours
                $dateRest = $ligne['Duree'] - floor( ($dateActuelle - $dateFormation) / (60*60*24) ) ;
                ?>                    
                        <?php echo "<div class='tr'>
                            <div class='bold1'>".$ligne['Date']."</div>
                            <div class='bold1 '>".$ligne['Formation']."</div>
                            <div class='bold1 '>";if( $dateRest > 0 ){
                                    echo "lui reste ".$dateRest." jours pour terminer sa formation."; 
                                    }else{ 
                                    echo " déja terminer la formation!, le : "."<strong>".$resultDate."</strong>"; } ?> <?php echo "</div>
                        </div>";?>
                    
                <?php
            }
        }else{
            echo "Probleme lors de la recupiration des donnes!";
        }
        ?>

        <?php echo "</div>";?>
        
        <?php 
            //Ajouter les détails pour une autre commande 
            include_once('detailCommandeSUBMIT.php');
        ?>
               
</body>
</html>

