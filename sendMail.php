<?php
$monsieur = 'Jallal';
$etudient ='ETUDIANT';
$capa = 31;
$body ='
</div style="color:black;" >Cher(e) <b>'.$monsieur.'</b>,</div>

<div style="color:black;" >Nous tenons à vous informer que la capacité d\'accueil sera augmentée à <b>'.$capa.'</b> en réponse à une forte demande. Cette modification vise à offrir à davantage d\'étudiants l\'opportunité de bénéficier de votre enseignement exceptionnel.</div>

<div style="color:black;" >Si vous avez des questions ou des objections à cette modification, n\'hésitez pas à contacter le directeur pour discuter de vos préoccupations.</div>

<div style="margin :25px; color:black;">Cordialement,</div>

<div style="margin :25px; color:black;">Secrétaire du centre scolaire</div>
';
  require_once 'phpmailer-master/mail.php'; 
  $mail->setFrom('res.gafp2011@gmail.com','Secretaire GAFP');
  $mail->isHTML(true);
  $mail->addAddress('ali.jallousy12@gmail.com'); 
  $mail->Subject = 'Augmentation de la capacité d\'accueil ';
  $mail->Body = $body;

  $mail->send();
?>