<?php
include('connection.php');
// Get necessary session variables
$nomsec = $_SESSION['nomsec'];
$prenomsec = $_SESSION['prenomsec'];
$passsec = $_SESSION['passsec'];
$IDClient = $_SESSION['IDClient'];

$sql = "SELECT * FROM secretaire WHERE Nom = '$nomsec' AND Prenom = '$prenomsec' AND Password = '$passsec'";
$requete = mysqli_query($conn, $sql);
$ligne = mysqli_fetch_assoc($requete);
$MatSec = $ligne['Matricule'];

// Retrieve the Client Name
$sql = "SELECT * FROM client WHERE ID = '$IDClient'";
$requete = mysqli_query($conn, $sql);
$ligne = mysqli_fetch_assoc($requete);
$nomCli = $ligne['Nom'];
$prenomCli = $ligne['Prenom'];
$emailEnvoyeAvecSucces = false;

// Retrieve the teacher with the lowest capacity and the same training as the order
$selectEnseignantSQL = "SELECT * FROM Enseignant WHERE Formation = '$formation' ORDER BY CapaciteCurr ASC LIMIT 1";
$result = mysqli_query($conn, $selectEnseignantSQL);

if ($result) {
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $matriculeEnseignant = $row['Matricule'];
        $NomEnss = $row['NomComplet'];
        $emailEns = $row['Email'];
        //echo $emailEns;
        $capaciteMax = $row['CapaciteMax'];
        $capaciteCurr = $row['CapaciteCurr'];
        

        if ($capaciteCurr < $capaciteMax) {
            // Incrémente la capacité actuelle de l'enseignant
            $nouvelleCapaciteCurr = $capaciteCurr + 1;

            // Mettez à jour la capacité actuelle de l'enseignant
            $updateCapaciteCurrSQL = "UPDATE Enseignant SET CapaciteCurr = '$nouvelleCapaciteCurr' WHERE Matricule = '$matriculeEnseignant'";
            mysqli_query($conn, $updateCapaciteCurrSQL);
            $sql = "INSERT INTO `commande`(`Date`, `Duree`, `Montant`, `EstTerminee`, `Formation`, `Client`, `MatEns`, `MatSecretaire`) 
                    VALUES ('$date', '$duree', '$montant','0', '$formation', '$IDClient', '$matriculeEnseignant', '$MatSec')";
            mysqli_query($conn, $sql);

            $emailEnvoyeAvecSucces = false;

        } else {
            echo "<script>showLoading();</script>";

            $nouvelleCapaciteCurr = $capaciteCurr + 1;
            $updateCapaciteCurrSQL = "UPDATE Enseignant SET CapaciteCurr = '$nouvelleCapaciteCurr' , CapaciteMax = '$nouvelleCapaciteCurr' WHERE Matricule = '$matriculeEnseignant'";
            mysqli_query($conn, $updateCapaciteCurrSQL);
            $sql = "INSERT INTO `commande`(`Date`, `Duree`, `Montant`, `Formation`, `Client`, `MatEns`, `MatSecretaire`) 
                    VALUES ('$date', '$duree', '$montant', '$formation', '$IDClient', '$matriculeEnseignant', '$MatSec')";
            mysqli_query($conn, $sql);

            $monsieur = $NomEnss;
            $etudient = $nomCli." ".$prenomCli;
            
            $body ='
            </div style="color:black;" >Cher(e) <b>'.$monsieur.'</b>,</div>

            <div style="color:black;" >Nous tenons à vous informer que la capacité d\'accueil sera augmentée à <b>'.$nouvelleCapaciteCurr.'</b> en réponse à une forte demande de client <b>'.$etudient.'</b>. Cette modification vise à offrir à davantage d\'étudiants l\'opportunité de bénéficier de votre enseignement exceptionnel.</div>

            <div style="color:black;" >Si vous avez des questions ou des objections à cette modification, n\'hésitez pas à contacter le directeur pour discuter de vos préoccupations.</div>

            <div style="margin :25px; color:black;">Cordialement,</div>

            <div style="margin :25px; color:black;">Secrétaire du centre scolaire</div>
            ';
              require_once 'phpmailer-master/mail.php'; 
              $mail->setFrom('res.gafp2011@gmail.com','Secretaire GAFP');
              $mail->isHTML(true);
              $mail->addAddress($emailEns); 
              $mail->Subject = 'Augmentation de la capacité d\'accueil ';
              $mail->Body = $body;

              $mail->send();
              $emailEnvoyeAvecSucces = true;
              
        }
        
    } else {
        // Handle the situation when no matching teacher is found
    }
} else {
    // Handle SQL errors
}
// Redirect to the 'recu.php' file to view and print the order
$_SESSION['Nom'] = $nomClient;
$_SESSION['Prenom'] = $prenomClient;
$_SESSION['Formation'] = $formation;
$_SESSION['Date'] = $date;
$_SESSION['Duree'] = $duree;
$_SESSION['Montant'] =$montant ;
//echo "/////////////////".$date." ".$duree." ". $montant." ".$formation." ".$IDClient;
// Retrieve the order number before redirection
$sql = "SELECT * FROM commande 
        WHERE Date = '$date' AND Duree = '$duree' AND Montant = '$montant' AND Formation = '$formation' 
        AND Client = '$IDClient'";
$result = mysqli_query($conn, $sql);
$ligne = mysqli_fetch_assoc($result);
//echo "****************************************".$ligne['Numero'];
$_SESSION['Numero'] = $ligne['Numero'];

mysqli_close($conn);
// Uncomment the line below when you want to perform the redirection
header('Location: bonCommande.php?emailEnvoyeAvecSucces=' . $emailEnvoyeAvecSucces . '&monsieur=' . urlencode($monsieur));
exit();

