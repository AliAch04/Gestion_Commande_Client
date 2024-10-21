<?php
ob_start();
session_start();
$monsieur="";
$env=false;
$IDClient = $_SESSION['IDClient'] ;
$nomClient = $prenomClient = $nomsec = $prenomsec = $passsec = '';
$formation = $date = $duree = $montant = '';
$erreur = array('formation' => '', 'date' => '', 'duree' => '', 'montant' => '');

function clear_data($var){
    $var = stripslashes($var);
    $var = trim($var);
    $var = strip_tags($var);
    
    return $var;
}

include('connection.php');

    $nomClient = $_SESSION['nomC'];
    $prenomClient = $_SESSION['prenomC'];
    $formation = $_SESSION['formation'];
    $date = $_SESSION['date'];
    $duree = $_SESSION['duree'];
    $montant = $_SESSION['montant'];
    $nomsec = $_SESSION['nomsec'];
    $prenomsec = $_SESSION['prenomsec'];
    $passsec = $_SESSION['passsec'];


if (isset($_POST['submitP'])) {
    header('Location: InscriptionCommande.php');
}

if (isset($_POST['submit'])) {
    if($_POST['formation'] == " "){
        $erreur['formation'] = "Il faut choisir une formation! ";

    }else{
        $formation = $_POST['formation']; 
    }

    if(empty($_POST['dateInsc'])){
        $erreur['date'] = "Il faut ajouter la date! ";
    }else{
        $date = clear_data($_POST['dateInsc']);
        if( preg_match('/^\d{4}-\d{2}-\d{2}$/',$date) == false){
            $erreur['date'] = "Veuillez entre la date sous forme '2023-01-31'!";
        }
    }

    if(empty($_POST['duree'])){
        $erreur['duree'] = "Il faut ajouter la durée de la formation ";
    }else{
       $duree = clear_data($_POST['duree']);
       if( preg_match('/^[0-9]{2}$/',$duree) == false){
        $erreur['duree'] = "Il faut ajouter les nombres des jours en max 99! ";
        }
    }

    if(empty($_POST['montant'])){
        $erreur['montant'] = "Il faut taper le montant! ";
    }else{
        $montant = clear_data($_POST['montant']);
        if( preg_match('/[0-9(.{1})]/',$montant) == false){
            $erreur['montant'] = "Il faut ajouter un nombre décimale ! ";
        }
        
    }
    // Handle form data validation and processing here
    // Add a query to retrieve the IDClient from the database

    if (array_filter($erreur) == []) {
        // Process the form data and insert it into the database
        // You may also need to handle the $IDClient variable here
        // Set the $_SESSION['IDClient'] variable
        $_SESSION['IDClient'] = $IDClient;
        $_SESSION['nomsec'] = $nomsec;
        $_SESSION['prenomsec']= $prenomsec;
        $_SESSION['passsec'] =$passsec;
        
        // Redirect to a new page after processing the form
        include('InsererAutreCommande.php');
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Autre Commande</title>   
    <link rel="stylesheet" href="styleN1.css">
    <style>
.loading {
    position: fixed;
    margin-top: 170px;
    height: 50px;
    width: 100%;
    background-color: rgba(255, 255, 255, 0.8);
    justify-content: center;
    align-items: center;
    text-align: center;
    font-size: 18px;
    font-weight: bold;
    padding-top: 10px;
    color: #006400;
    z-index: 102;
    display: none;
}
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
            padding: 20px 15px;
            height: 100%;
            width: 25%;
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


        .container{
            min-width: 600px;
        }

        .container .form{
            padding: 30px;
            margin-bottom: 6.5em;
            
        }

        .button{
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .form .title::before{
    content: '';
    position: absolute;
    left: 5.1%;
    bottom: 86%;
    height: 3.5px;
    width: 70px;
    background: var(--primary-color3);
}

        .submit{
            padding: 10px 35px;
            margin-bottom: 35px;
            border: none;
            color : var(--primary-color);
            letter-spacing: 1px;
            border-radius: 6px;
            height: 100%;
            width: 40%;
            font-size: 17px;
            font-weight: 500;
            background-color: var(--primary-color-ligh);
            cursor: pointer;
            transition: all 0.4s ease;
        }

        select{
    
    font-size: 16px;
    padding: 0 35px;
    border: none;
    outline: none;
    border-bottom: 2px solid #ccc;
    border-top: 2px solid #ccc transparent;
    height: 100%;
    width: 100%;
    transition: all 0.4s ease;
    background: var(--primary-color2);
    color: var(--toggle-color);
}

select:is(:focus){
    color: var(--primary-color);
    
}

select:is(:focus) option:checked {
    background-color: var(--primary-color-light);
    transition: background-color 0.5s;
}

select:is(:focus) option:not(:checked) {
    background-color: white;
    transition: background-color 0.5s;
}

select:is(:focus) ~ i{
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

i.icon{
    left :0;
}

        .submit:hover{
            background-color: var(--primary-color);
            color: var(--primary-color-ligh);
        }
        
        .informer1{
    opacity: 1;
    bottom: 65%;
    left: 108%;
    position: absolute;
    min-height: 60px;
    min-width: 360px;
    padding: 10px;
    border-radius: 6px;
    background-color: #5ca0fe;
    display: flex;
    justify-content: center;
    align-items: center;
    box-shadow: 0px 1px 10px grey;
}

.titreX .titreShadow{
    position: absolute;
    top :25%;
    font-size: 2.15rem;
    background-color: var(--primary-color) ;
    height: 2.3em;
    width: 28%;
    color: var(--body-color);
    border-radius: 29px;
    box-shadow: 0px 2px 10px black;
    z-index: -1;
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
    </style>
    
</head>
<body>

    <nav>       
        <div class="titreX">
            <h1>Inserer autre Commande</h1>
            <div class="titreShadow"></div>
        </div>
    </nav>


    <button id="scrollToBtn" class="scroll-to-btn" onclick="scrollToTarget()"><i class='bx bxs-down-arrow'></i></button>

    <section class="centerTo1">
        <div class="centerTo">             
            <div>
                <?php echo "Le Client "?> <strong><?php echo $nomClient." ".$prenomClient ?></strong> <?php echo " est déjà existé!";
                ?>
            </div>
        </div>
    </section>

    <div style = "margin:4em "></div>
    <?php echo "
        <div class='table'>
            <div class='trT' >
                <div class='bold'>Date d'Insription</div>
                <div class='bold'>Formation</div>
                <div class='bold'>Statut</div>
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
        echo "</div>";
    ?>

<section class="main">
        <form action="autre1.php" method="POST">
        
<div class="container">
    <div class="form">
        <div class="form-login">
            <span class="title">Détails de Commande</span>
            <div class="input-field">
                <select name="formation">
        <option value=" ">Selecter la Formation</option>
        <?php
        include('connection.php');

        $formations = mysqli_query($conn, "SELECT Nom, Type, Prix FROM formation");

        $formations_par_type = array();

        while ($f = mysqli_fetch_array($formations)) {
            $type = $f['Type'];
            $formations_par_type[$type][] = $f;
        }

        foreach ($formations_par_type as $type => $formations) {
            echo '<optgroup label="' . $type . '">';
            foreach ($formations as $f) {
                $selected = ($f['Nom'] == $formation) ? 'selected' : '';
                echo '<option value="' . $f['Nom'] . '" ' . $selected . '>' . $f['Nom'] . str_repeat('&nbsp;', 10) . $f['Prix'] . 'DH</option>';
            }
            echo '</optgroup>';
        }
        ?>
    </select>
                <i class='bx bxs-graduation icon'></i>
            </div>
            <div class="informerP">
                <p style="color:red; align-text:centre;">
                    <?php echo "* " . $erreur['formation']; ?>
                </p>
            </div>

            <div class="input-field">
                <input type="text" name="dateInsc" placeholder="Entre la date d'inscription" >
                <i class='bx bx-calendar icon'></i>
            </div>
            <div class="informerP">
                <p style="color:red; align-text:centre;">
                    <?php echo "* " . $erreur['date']; ?>
                </p>
            </div>

            <div class="input-field">
                <input type="text" name="duree" placeholder="Durée de la formation!" value="<?php echo $duree; ?>">
                <i class='bx bx-time-five icon'></i>
            </div>
            <div class="informerP">
                <p style="color:red; align-text:centre;">
                    <?php echo "* " . $erreur['duree']; ?>
                </p>
            </div>

            <div class="input-field">
                <input type="text" name="montant" placeholder="Montant" value="<?php echo $montant; ?>">
                <i class='bx bx-dollar-circle icon'></i>
            </div>
            <div class="informerP">
                <p style="color:red; align-text:centre;">
                    <?php echo "* " . $erreur['montant']; ?>
                </p>
            </div>
        </div>
    </div>
</div>

<div class="input-field button">
    <button class="submit" type="submit" name="submit">Enregistre</button>
    <button type="submit" class = "submit" name="submitP" value="Retour">Retour</button>
</div>

        </form>
    </section>

<script src="scriptN.js"></script>

<script>
    <?php
    if ($capaciteCurr >= $capaciteMax) {
        echo "setTimeout(function () { showLoading(); }, 3000);";
    }
    ?>

    function showLoading() {
        var loadingDiv = document.getElementById('loadingDiv');
        loadingDiv.innerHTML = "Un Mail a été Envoyé au Enseignant(e) : <?php echo $NomEnss; ?> avec Succès !";
        loadingDiv.style.display = 'block';

        setTimeout(function () {
            loadingDiv.style.display = 'none';
            window.location.href = 'bonCommande.php';
        }, 3000);
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




</body>
</html>

