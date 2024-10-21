<?php
include('connection.php');

// Récupérer l'ID du client pour ajouter à la commande
$sql = "SELECT ID FROM client WHERE Nom = '$nomClient' AND Prenom = '$prenomClient'";
$result = mysqli_query($conn, $sql);
$IDclient = mysqli_fetch_assoc($result)['ID'];

// Récupérer le matricule du secrétaire pour ajouter à la commande
$sql = "SELECT * FROM secretaire WHERE Nom = '$nomsec' AND Prenom = '$prenomsec' AND Password ='$passsec'";
$result = mysqli_query($conn, $sql);
$MatSec = mysqli_fetch_assoc($result)['Matricule'];

// Récupérer l'enseignant avec la plus faible capacité et la même formation que la commande
$selectEnseignantSQL = "SELECT * FROM Enseignant WHERE Formation = '$formation' ORDER BY CapaciteCurr ASC LIMIT 1";
$result = mysqli_query($conn, $selectEnseignantSQL);
$emailEnvoyeAvecSucces=false;
if ($result) {
    // Vérifier s'il y a un enseignant correspondant
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $matriculeEnseignant = $row['Matricule'];
        $NomEnss = $row['NomComplet'];
        $emailEns = $row['Email'];
        $capaciteMax = $row['CapaciteMax'];
        $capaciteCurr = $row['CapaciteCurr'];

        if ($capaciteCurr < $capaciteMax) {
            // Incrémenter la capacité actuelle de l'enseignant
            $nouvelleCapaciteCurr = $capaciteCurr + 1;

            // Mettre à jour la capacité actuelle de l'enseignant
            $updateCapaciteCurrSQL = "UPDATE Enseignant SET CapaciteCurr = '$nouvelleCapaciteCurr' WHERE Matricule = '$matriculeEnseignant'";
            mysqli_query($conn, $updateCapaciteCurrSQL);

            // Insérer la commande
            $sql = "INSERT INTO `commande`(`Date`, `Duree`, `Montant`, `EstTerminee`, `Formation`, `Client`, `MatEns`, `MatSecretaire`) 
                    VALUES ('$date', '$duree', '$montant','0', '$formation', '$IDclient', '$matriculeEnseignant', '$MatSec')";
            mysqli_query($conn, $sql);
        } else {
            $nouvelleCapaciteCurr = $capaciteCurr + 1;
            $updateCapaciteCurrSQL = "UPDATE Enseignant SET CapaciteCurr = '$nouvelleCapaciteCurr', CapaciteMax = '$nouvelleCapaciteCurr' WHERE Matricule = '$matriculeEnseignant'";
            mysqli_query($conn, $updateCapaciteCurrSQL);

            // Insérer la commande avec une capacité dépassée
            $sql = "INSERT INTO `commande`(`Date`, `Duree`, `Montant`, `EstTerminee`, `Formation`, `Client`, `MatEns`, `MatSecretaire`) 
                    VALUES ('$date', '$duree', '$montant', '0', '$formation', '$IDclient', '$matriculeEnseignant', '$MatSec')";
            mysqli_query($conn, $sql);

            // Envoyer un e-mail
            $body = "
                <div style='color:black;'>Cher(e) <b>$NomEnss</b>,</div>
                <div style='color:black;'>Nous tenons à vous informer que la capacité d'accueil sera augmentée à <b>$nouvelleCapaciteCurr</b> en réponse à une forte demande de client <b>$nomClient $prenomClient</b>. Cette modification vise à offrir à davantage d'étudiants l'opportunité de bénéficier de votre enseignement exceptionnel.</div>
                <div style='color:black;'>Si vous avez des questions ou des objections à cette modification, n'hésitez pas à contacter le directeur pour discuter de vos préoccupations.</div>
                <div style='margin :25px; color:black;'>Cordialement,</div>
                <div style='margin :25px; color:black;'>Secrétaire du centre scolaire</div>
            ";

            require_once 'phpmailer-master/mail.php'; 
            $mail->setFrom('res.gafp2011@gmail.com', 'Secretaire GAFP');
            $mail->isHTML(true);
            $mail->addAddress($emailEns); 
            $mail->Subject = "Augmentation de la capacité d'accueil";
            $mail->Body = $body;
            $mail->send();
            $emailEnvoyeAvecSucces = true;
        }
    } else {
        // Aucun enseignant correspondant trouvé, vous pouvez gérer cette situation selon vos besoins.
    }
} else {
    // Erreurs SQL
}
echo $duree." ". $montant." ".$formation." ".$IDclient;

// Récupérer le Numéro de commande avant de transférer
$sql = "SELECT Numero FROM commande 
        WHERE Date='$date' AND Duree='$duree' AND Montant ='$montant' AND Formation='$formation' 
        AND Client = '$IDclient'";
$ligne = mysqli_fetch_assoc(mysqli_query($conn, $sql));

// Stocker les données en session
$_SESSION['Nom'] = $nomClient;
$_SESSION['Prenom'] = $prenomClient;
$_SESSION['Formation'] = $formation;
$_SESSION['Date'] = $date;
$_SESSION['Duree'] = $duree;
$_SESSION['Montant'] = $montant;
$_SESSION['Numero'] = $ligne['Numero'];
echo $_SESSION['Numero'];
$monsieur = $NomEnss;
// Rediriger vers bonCommande.php
header('Location: bonCommande.php?emailEnvoyeAvecSucces=' . $emailEnvoyeAvecSucces . '&monsieur=' . urlencode($monsieur));
?>
