<?php
// Démarrez la session
session_start();

if (!isset($_SESSION['user_authenticated']) || $_SESSION['user_authenticated'] !== true) {
    header("Location: Identification.php");
    exit();
}

// Inclure le fichier de connexion à la base de données
include('connection.php');

// Traitement de la mise à jour de l'enseignant
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['matEns'])) {
    // Récupération des valeurs du formulaire
    $newMatEns = $_POST['matEns'];
    $numCommande = $_POST['numero'];

    // Récupérez le matricule de l'enseignant actuel de la commande
    $queryCommande = "SELECT * FROM Commande WHERE Numero = $numCommande";
    $resultCommande = mysqli_query($conn, $queryCommande);
    $rowCommande = mysqli_fetch_assoc($resultCommande);
    $oldMatEns = $rowCommande['MatEns'];
    $IDClien = $rowCommande['Client'];

    // Récupérez le NOM COMPLET de client de la commande
    $sql = "SELECT * FROM Client WHERE ID = $IDClien";
    $resultClient = mysqli_query($conn, $sql);
    $rowClient = mysqli_fetch_assoc($resultClient);
    $nomccc = $rowClient['Nom'];
    $prenomccc = $rowClient['Prenom'];
    $nomClient = $nomccc . " " . $prenomccc;

    // Récupération des informations de l'enseignant
    $query = "SELECT Matricule, NomComplet, Email, CapaciteCurr FROM Enseignant WHERE Matricule = '$newMatEns'";
    $result = mysqli_query($conn, $query);
    $rowE = mysqli_fetch_assoc($result);
    $currentCapaciteCurr = $rowE['CapaciteCurr'];

    // Récupération des enseignants dont la capacité actuelle est égale à la capacité maximale
    $queryEnseignantsPleins = "SELECT NomComplet, Email FROM Enseignant WHERE CapaciteCurr = CapaciteMax";
    $resultEnseignantsPleins = mysqli_query($conn, $queryEnseignantsPleins);

    // Vérifiez si l'enseignant a changé
    if ($newMatEns != $oldMatEns) {
        // Mise à jour de la capacité actuelle de l'ancien enseignant
        $decrementOldEnsSQL = "UPDATE Enseignant SET CapaciteCurr = CapaciteCurr - 1 WHERE Matricule = '$oldMatEns'";
        if (mysqli_query($conn, $decrementOldEnsSQL)) {
            // Mise à jour de la capacité actuelle du nouvel enseignant
            $incrementNewEnsSQL = "UPDATE Enseignant SET CapaciteCurr = CapaciteCurr + 1 WHERE Matricule = '$newMatEns'";
            if (mysqli_query($conn, $incrementNewEnsSQL)) {
                // Mise à jour du matricule de l'enseignant dans la commande
                $updateCommandeSQL = "UPDATE Commande SET MatEns = '$newMatEns' WHERE Numero = $numCommande";
                if (mysqli_query($conn, $updateCommandeSQL)) {
                    // Succès de la mise à jour de la commande

                    // Votre code pour envoyer un email ici
                    if ($resultEnseignantsPleins) {
                        // Boucle à travers les résultats
                        while ($rowEnseignantPlein = mysqli_fetch_assoc($resultEnseignantsPleins)) {
                            $NomEnss = $rowEnseignantPlein['NomComplet'];
                            $emailEns = $rowEnseignantPlein['Email'];

                            $nouvelleCapaciteCurr = $currentCapaciteCurr + 1;
                            $updateCapaciteCurrSQL = "UPDATE Enseignant SET CapaciteCurr = '$nouvelleCapaciteCurr', CapaciteMax = '$nouvelleCapaciteCurr' WHERE Matricule = '$newMatEns'";
                            mysqli_query($conn, $updateCapaciteCurrSQL);

                            echo "<div class='success-box-top' style='opacity: 1; color:red;'>Tous les enseignants de cette formation ont atteint leur capacité maximale ou il n'a aucun(e) enseignant! \n. Mais un e-mail a passer pour <b>".$NomEnss."</b>   <button onclick='closeSuccessBox(this.parentNode)'><i class='bx bxs-x-circle' style = 'font-size: 17px;'></i></button>
                            </div>";
                            $monsieur = $NomEnss;
                            $etudiant = $nomClient;

                            $body = '
                                <div style="color:black;" >Cher(e) <b>' . $monsieur . '</b>,</div>

                                <div style="color:black;" >Nous tenons à vous informer que la capacité d\'accueil sera augmentée à <b>' . $nouvelleCapaciteCurr . '</b> en réponse à une forte demande de client <b>' . $etudiant . '</b>. Cette modification vise à offrir à davantage d\'étudiants l\'opportunité de bénéficier de votre enseignement exceptionnel.</div>

                                <div style="color:black;" >Si vous avez des questions ou des objections à cette modification, n\'hésitez pas à contacter le directeur pour discuter de vos préoccupations.</div>

                                <div style="margin :25px; color:black;">Cordialement,</div>

                                <div style="margin :25px; color:black;">Secrétaire du centre scolaire</div>
                            ';
                            require_once 'phpmailer-master/mail.php';
                            $mail->setFrom('res.gafp2011@gmail.com', 'Secretaire GAFP');
                            $mail->isHTML(true);
                            $mail->addAddress($emailEns);
                            $mail->Subject = 'Augmentation de la capacité d\'accueil ';
                            $mail->Body = $body;

                            $mail->send();
                            // Fin de l'envoi de l'email
                        }
                    }

                } else {
                    echo "<div>Erreur lors de la mise à jour de la commande : " . mysqli_error($conn)."</div>";
                }
            } else {
                echo "<div>Erreur lors de l'incrémentation de la capacité du nouvel enseignant : " . mysqli_error($conn)."</div>";
            }
        } else {
            echo "<div>Erreur lors de la décrémentation de la capacité de l'ancien enseignant : " . mysqli_error($conn)."</div>";
        }
    }
    // Succès de la mise à jour de la commande
    $_SESSION['changementReussi'] = true;
} else {
    
}

// Vérifier si la date actuelle est après la date de fin de chaque commande
$queryCommandes = "SELECT Numero, Date, Duree, MatEns, EstTerminee FROM Commande";
$resultCommandes = mysqli_query($conn, $queryCommandes);
$dateActuelle = date('Y-m-d');

// Stocker les informations des commandes terminées dans un tableau
$commandesTerminees = array();

while ($rowCommande = mysqli_fetch_assoc($resultCommandes)) {
    $numeroCommande = $rowCommande['Numero'];
    $dateCommande = $rowCommande['Date'];
    $dureeCommande = $rowCommande['Duree'];
    $matriculeEnseignant = $rowCommande['MatEns'];
    $estTerminee = $rowCommande['EstTerminee'];

    if (!$estTerminee) {
        // Calculer la date de fin de la commande en ajoutant la durée à la date de début
        $dateFinCommande = date('Y-m-d', strtotime($dateCommande . ' + ' . $dureeCommande . ' days'));

        // Vérifier si la date actuelle est après la date de fin de la commande
        if ($dateActuelle > $dateFinCommande) {
            // Marquer la commande comme terminée
            $marquerTermineeSQL = "UPDATE Commande SET EstTerminee = 1 WHERE Numero = $numeroCommande";
            mysqli_query($conn, $marquerTermineeSQL);

            // Décrémenter la capacité actuelle de l'enseignant associé à la commande
            $decrementCapaciteSQL = "UPDATE Enseignant SET CapaciteCurr = CapaciteCurr - 1 WHERE Matricule = '$matriculeEnseignant'";
            mysqli_query($conn, $decrementCapaciteSQL);

            // Stocker les informations dans le tableau
            $commandesTerminees[] = array(
                'numero' => $numeroCommande,
                'matriculeEnseignant' => $matriculeEnseignant
            );
        }
    }
}

// Afficher les divs à partir des informations stockées dans le tableau
foreach ($commandesTerminees as $commandeTerminee) {
    echo "<div class='success-box-top' style='opacity: 1; color:red; '>La commande {$commandeTerminee['numero']} est terminée. La capacité de l'enseignant de matricule : {$commandeTerminee['matriculeEnseignant']} a été décrémentée. <button class='close-btn' onclick='closeSuccessBox(this.parentNode)'><i class='bx bxs-x-circle ttb'></i></button></div>";
}

// Affichez le message de succès si la variable de session est définie
if (isset($_SESSION['changementReussi'])) {
    echo '<div class="success-box" style="opacity: 1;">Changement réussi !</div>';

    // Une fois affiché, détruisez la variable de session pour ne pas répéter le message après chaque rechargement de la page
    unset($_SESSION['changementReussi']);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commandes</title>
    <link rel="stylesheet" href="styleN.css">
    <link rel="stylesheet" href="styleN1.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>   
    
    <style>
       

       .titreX .titreShadow{
   position: absolute;
   top :25%;
   font-size: 2.15rem;
   background-color: var(--primary-color) ;
   height: 2.3em;
   width: 27%;
   color: var(--body-color);
   border-radius: 29px;
   box-shadow: 0px 2px 10px black;
   z-index: -1;
}

::-webkit-calendar-picker-indicator{
   background-color: #ddd;
   padding: 5px;
   cursor: pointer;
   border-radius: 3px;
}

form{
   margin-top: 2em;
   height: 5em;
   width: 100%;
   display: flex;
   flex-direction: row;
   justify-content: space-evenly;
   align-items: center;
}

.green {
   color: green;
}

.yellow {
   color: yellow;
}

.orange {
   color: orange;
}
.red{
   color: red;
}

input[type="date"]{
   font-size: 15px;
   padding: 5px 35px;
   border-radius: 3px;
   border: none;
   outline: none;
   border-bottom: 2px solid #ccc;
   border-top: 2px solid #ccc transparent;
   transition: all 0.4s ease;
   background: var(--primary-color-ligh);
   color: var(--primary-color);
}
.submitxn{
   padding: 5px 35px;
   border: none;
   color : var(--primary-color);
   letter-spacing: 1px;
   border-radius: 6px;
   font-size: 15px;
   font-weight: 500;
   background-color: var(--primary-color-ligh);
   cursor: pointer;
   transition: all 0.4s ease;
}
.submitxn:hover{
           background-color: var(--primary-color);
           color: var(--primary-color-ligh);
       }

.dis label{
   margin-right:10px;
   color: var(--primary-color);
   font-weight: 600;
}

.table {
           width: 90%;
           margin: 2em auto;
           box-shadow: 0px 1px 10px grey;
           border: 3px solid white;
           border-radius: 15px;
           
       }

       .tr {
           width: 100%;
           display: flex;
           justify-content: center;
           flex-direction: row;
           text-align: center;
           border-bottom: 3px solid white;
           border-bottom-left-radius: 15px;
           border-top-right-radius: 15px;
           background-color: rgb(127, 178, 255);
           transition: 0.4s ease;
       }

       .trT {
           width: 100%;
           display: flex;
           justify-content: start;
           flex-direction: row;
           text-align: left;
           border-bottom: 3px solid white;
           border-radius: 15px;
           background-color: #695CFE;
           transition: 0.4s ease;
       }

       .bold {
           padding: 20px;
           height: 50%;
           border-radius: 10px;
           background-color:#695CFE;
           transition: 0.4s ease;
       }
       .bold1 {
           padding: 15px 15px;
           width: 50%;
           background-color: rgb(127, 178, 255);
           transition: 0.4s ease;
           border-bottom-left-radius:  15px ;
           border-bottom-right-radius:  15px ;
       }

       .trT:hover{
           background-color: #857aff;
       }
       .trT:hover .bold{
           background-color: #857aff;
           
       }

       .tr .boldtx{
           height: 100px;
       }

       
       .sel{
   font-size: 16px;
   padding: 0 35px;
   border: none;
   outline: none;
   border-bottom: 2px solid #ccc;
   border-top: 2px solid #ccc transparent;
   margin-bottom: 100px;
   transition: all 0.4s ease;
   background: var(--primary-color2);
   color: var(--toggle-color);
}

.sel:is(:focus){
   color: var(--primary-color);
   
}

.sel:is(:focus) option:checked {
   background-color: var(--primary-color-light);
   transition: background-color 0.5s;
}

.sel:is(:focus) option:not(:checked) {
   background-color: white;
   transition: background-color 0.5s;
}

.sel:is(:focus) ~ i{
   color: var(--primary-color);
}

optgroup{
   background-color: var(--toggle-color);
}

.input-field i{
   position: absolute;
   top: 50%;
   transform: translateY(-50%);
   color: var(--toggle-color);
   font-size: 23px;
   transition: all 0.4s ease;
}


i.icon{
   left :0;
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

.success-box {
   position: fixed;
   margin-top: 170px;
   height: 45px;
   width: 100%;
   background-color: rgba(255, 255, 255, 0.8); /* Fond semi-transparent */
   justify-content: center;
   align-items: center;
   text-align: center;
   font-size: 18px;
   font-weight: bold;
   padding-top: 10px;
   color: #006400; /* Couleur de la police verte, ajustez selon vos besoins */
   opacity: 0; /* Initialement transparent */
   transition: opacity 1.5s ease; /* Transition de 1.5 secondes */
   z-index: 1000;
}

.success-box-top{
   position: static;
   height: 50px;
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

.scroll-to-btn {
    position: fixed;
    bottom: 20px;
    right: 20px;
    padding: 10px;
    background-color: #007bff;
    color: #fff;
    border: none;
    height: 40px;
    width: 40px;
    border-radius: 50%;
    cursor: pointer;
        }

.close-btn {
    border: none;
    cursor: pointer;
    color: gray;
    background-color: transparent;
}

.ttb{
    height: 170px;
    width: 170px;
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
        <h1>RC Commandes</h1>
        <div class="titreShadow"></div>
        <div class="cont">
                <button class ="retour" onclick="Retour()"  type="submit"><i class='bx bx-log-out-circle icon' ></i></button>   
        </div>
        </div>
    </nav>

    <button id="scrollToBtn" class="scroll-to-btn" onclick="scrollToTarget()"><i class='bx bxs-down-arrow'></i></button>
    

    <!-- Formulaire pour sélectionner l'intervalle de temps -->
    <form method="POST">

        <div class="dis">
            <label for="date_debut">Date de début :</label>
            <input type="date" id="date_debut" name="date_debut" >
        </div>

        <div class="dis">
            <label for="date_fin">Date de fin :</label>
            <input type="date" id="date_fin" name="date_fin">
        </div>

        <input type="hidden" name="oldMatEns" value="<?php echo $MatEns1; ?>">

        <div>
            <button class="submitxn" type="submit" name ="submit" value="Afficher les commandes">Afficher</button>
        </div>
    </form>

    <!-- Champ hidden pour l'ancien matricule de l'enseignant -->
    <input type="hidden" name="oldMatEns" value="<?php echo $MatEns1; ?>">

    <!-- Champ pour le numéro de commande -->
    <input type="hidden" name="numero" value="<?php echo $num; ?>">

    <?php

    if(isset($_POST['submitP'])){
        header('Location: InscriptionCommande.php');
    }

    // Inclure le fichier de connexion à la base de données
    include('connection.php');
    
    // Vérifier si le formulaire a été soumis
    if (isset($_POST['submit'])) {
        $date_debut = $_POST['date_debut'];
        $date_fin = $_POST['date_fin'];

        /// Requête SQL pour récupérer les commandes dans l'intervalle de temps choisi
        $sql = "SELECT *, IFNULL(EstTerminee, 0) AS EstTerminee FROM Commande WHERE Date BETWEEN '$date_debut' AND '$date_fin'";
        $result = mysqli_query($conn, $sql);
        
        if (!$result) {
            echo "Erreur lors de la requête : " . mysqli_error($conn);
        } else {
            $totalMontant = 0;  
            $numOptions =0; 

    // Afficher les commandes dans un tableau
    echo "<div class='table'>
            <div class='trT' >
                <div class='bold' style='width: 50%'>Numéro de Commande</div>
                <div class='bold'   style='width: 28%' >Date</div>
                <div class='bold'  style='width: 28%'  >Durée</div>
                <div class='bold' style='width: 28%'  >Montant</div>
                <div class='bold' style='width: 45%'  >Formation</div>
                <div class='bold' style='width: 40%'  >Client</div>
                <div class='bold' style='width: 60%'  >Mat Sec</div>
                <div class='bold' style='width: 33%'  >Enseignant</div>
                <div class='bold' style='width: 30%'  >BonCommande</div>

            </div>";

    while ($row = mysqli_fetch_assoc($result)) {
        $totalMontant += $row['Montant'];
        $num = $row['Numero'];

        // Récupérer le nom et le prénom du client en fonction de son ID
        $clientId = $row['Client'];
        $query = "SELECT Nom, Prenom FROM client WHERE ID = $clientId";
        $clientInfo = mysqli_query($conn, $query);
        $clientData = mysqli_fetch_assoc($clientInfo);
        $clientNom = $clientData['Nom'];
        $clientPrenom = $clientData['Prenom'];
        $Num = $row['Numero'];

        $MatEns1 = $row['MatEns'];
        $query = "SELECT * FROM Enseignant WHERE Matricule = $MatEns1";
$EnsInfo = mysqli_query($conn, $query);
$EnsData = mysqli_fetch_assoc($EnsInfo);

$NomEns1 = $EnsData['NomComplet'];
$capacity = $EnsData['CapaciteMax'];
$capacityCurr = $EnsData['CapaciteCurr'];
$oneThird = $capacity / 3;
$twoThirds = 2 * $oneThird;


// Determine the color class for the select element
if ($capacityCurr < $oneThird) {
    $selectColorClass = 'green';
} elseif ($capacityCurr < $twoThirds) {
    $selectColorClass = 'yellow';
} elseif ($capacityCurr < $capacity) {
    $selectColorClass = 'orange';
} else {
    $selectColorClass = 'red';
}


// Generate the dynamic options for the select element
$options = '';
$formation = $row['Formation'];

$queryEns = "SELECT * FROM Enseignant WHERE Formation = '$formation' AND Matricule != '$MatEns1'";
$resultEns = mysqli_query($conn, $queryEns);



while ($rowEns = mysqli_fetch_assoc($resultEns)) {
    // Determine the color class for each dynamically generated option
    $ensCapacity = $rowEns['CapaciteCurr'];
    $ensOneThird = $rowEns['CapaciteMax'] / 3;

    if ($ensCapacity < $ensOneThird) {
        $optionColorClass = 'green';
    } elseif ($ensCapacity < 2 * $ensOneThird) {
        $optionColorClass = 'yellow';
    } elseif ($ensCapacity < $rowEns['CapaciteMax']) {
        $optionColorClass = 'orange';
    } else {
        $optionColorClass = 'red';
    }

    $options = "<option value='" . $rowEns['Matricule'] . "' style='color: $optionColorClass;'>" . $rowEns['NomComplet'] . "</option>";
}

// Comptez le nombre d'options affichées

$numOptions = mysqli_num_rows($result);


// Vérifier si la commande est terminée
$estTerminee = $row['EstTerminee'];
$backgroundStyle = $estTerminee ? "background-color: #658CBB;" : "";

echo "<div class='tr' style='$backgroundStyle'>
    <div class='bold1' style='width:40%; $backgroundStyle'>" . $row['Numero'] . "</div>
    <div class='bold1' style='$backgroundStyle' >" . $row['Date'] . "</div>
    <div class='bold1' style='width:20%; $backgroundStyle'>" . $row['Duree'] . "</div>
    <div class='bold1' style='width:40%; $backgroundStyle'>" . $row['Montant'] . "</div>
    <div class='bold1' style='$backgroundStyle'> " . $row['Formation'] . "</div>
    <div class='bold1' style='$backgroundStyle'> " . $row['Client'] . "</div>
    <div class='bold1' style='$backgroundStyle'> " . $row['MatSecretaire'] . "</div>

    <div class='boldtx' style='width:11%;'>
        <form method='post' action='RCcommande.php'>
            <input type='hidden' name='numero' value='$num'>";

            // Vérifier si la commande est terminée pour désactiver le select
    $selectDisabled = $estTerminee ? "disabled" : "";
            
    echo "<select class='sel' style='color: $selectColorClass;' name='matEns' $selectDisabled>
                <option value='$MatEns1'>$NomEns1</option>
                $options
            </select>
        </form>
    </div>

    <div class='bold1' style='$backgroundStyle'> <a href='boncomrout.php?numero=" . $row['Numero'] . "&date=" . $row['Date'] . "&duree=" . $row['Duree'] . "&montant=" . $row['Montant'] . "&formation=" . $row['Formation'] . "&clientNom=" . $clientNom . "&clientPrenom=" . $clientPrenom . "'>Voir</a> </div>

</div>";
                // Établir la session pour les données de la commande
                $_SESSION['Numero'] = $row['Numero'];
                $_SESSION['Date'] = $row['Date'];
                $_SESSION['Duree'] = $row['Duree'];
                $_SESSION['Montant'] = $row['Montant'];
                $_SESSION['Formation'] = $row['Formation'];
                $_SESSION['Nom'] = $clientNom;
                $_SESSION['Prenom'] = $clientPrenom;
    }
            
// Afficher le total du montant de toutes les commandes en tant que dernière ligne
echo "<div class='bold1' style='display: flex; justify-content : space-around; padding : 40px; width:100%;'><div>Le nombre des Commandes : $numOptions </div> <div>Total du montant : $totalMontant</div> </div> 

            </div>";
            
        }    
    }
       
    ?>

<script>
  // Recherchez l'élément avec la classe "success-box"
  const successBox = document.querySelector('.success-box');

  // Si l'élément existe
  if (successBox) {
    // Attendez 3 secondes (3000 millisecondes) avant d'ajuster l'opacité
    setTimeout(() => {
      // Réglez l'opacité sur 0
      successBox.style.opacity = 0;
    }, 3000);
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

<script>
        function scrollToTarget() {
            // Utilisez window.scrollTo pour faire défiler vers le bas ou vers le haut de la page
            const targetPosition = window.scrollY === 0 ? document.body.scrollHeight : 0;

            window.scrollTo({
                top: targetPosition,
                behavior: 'smooth'
            });

            // Mettez à jour le contenu HTML du bouton en conséquence
            const btnIcon = targetPosition === 0 ? "<i class='bx bxs-down-arrow'></i>" : "<i class='bx bxs-up-arrow'></i>";
            document.getElementById('scrollToBtn').innerHTML = btnIcon;
        }

        // Ajoutez un gestionnaire d'événements pour détecter le défilement de la page
        window.addEventListener('scroll', function () {
            // Mettez à jour le contenu HTML du bouton en conséquence lorsque vous atteignez la fin de la page
            const btnIcon = window.scrollY === 0 ? "<i class='bx bxs-down-arrow'></i>" : "<i class='bx bxs-up-arrow'></i>";
            document.getElementById('scrollToBtn').innerHTML = btnIcon;
        });
    </script>

<script>

    
    const matEnsSelects = document.querySelectorAll('select[name="matEns"]');
    matEnsSelects.forEach(select => {
        select.addEventListener('change', function () {
            this.form.submit();
        });
    });

    
    function Retour(){
    window.location.href = "InscriptionCommande.php";
    }
    
</script>

</body>
</html>
