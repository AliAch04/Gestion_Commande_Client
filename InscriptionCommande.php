<?php
session_start();

if (!isset($_SESSION['user_authenticated']) || $_SESSION['user_authenticated'] !== true) {
    header("Location: Identification.php");
    exit();
}

$nomClient = $prenomClient = $email = $teleC = $formation = $date = $duree = $montant = '';
$erreur = array('nom' => '', 'prenom' => '', 'email' => '', 'tele' => '', 'formation' => '', 'date' => '', 'duree' => '', 'montant' => '');

function clear_data($var)
{
    $var = stripslashes($var);
    $var = trim($var);
    $var = strip_tags($var);
    return $var;
}

if (isset($_POST['submit'])) {
    $email = $_POST['email'] ?? '';
    $teleC = $_POST['teleC'] ?? '';
    $prenomClient = $_POST['prenomClient'] ?? '';
    $formation = $_POST['formation'] ?? '';
    $date = $_POST['dateInsc'] ?? '';
    $duree = $_POST['duree'] ?? '';
    $montant = $_POST['montant'] ?? '';

    $nomsec = $_SESSION['NomSec'] ;
    $prenomsec = $_SESSION['PrenomSec'];
    $passsec = $_SESSION['PassSec'] ;

    if (empty($email)) {
        $erreur['email'] = "Il faut Ajouter un email!";
    } elseif (!filter_var(clear_data($email), FILTER_VALIDATE_EMAIL)) {
        $erreur['email'] = "Veuillez entre un email valide!";
    }

    if (empty($teleC)) {
        $erreur['tele'] = "Il faut ajouter un numéro de téléphone!";
    } elseif (!preg_match('/^[0-9\+]{10,13}$/', clear_data($teleC))) {
        $erreur['tele'] = "Veuillez entre un numéro de téléphone valide!";
    }

    if (empty($prenomClient)) {
        $erreur['prenom'] = "Il faut remplir le prénom!";
    } elseif (!preg_match('/^[a-z\d_éç]{3,15}$/i', clear_data($prenomClient))) {
        $erreur['prenom'] = "Veuillez entre au moins 3 caractères à 15!";
    }

    if ($formation === " ") {
        $erreur['formation'] = "Il faut choisir une formation!";
    }

    if (empty($date)) {
        $erreur['date'] = "Il faut ajouter la date!";
    } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', clear_data($date))) {
        $erreur['date'] = "Veuillez entrer la date sous forme '2023-01-31'!";
    }

    if (empty($duree)) {
        $erreur['duree'] = "Il faut ajouter la durée de la formation";
    } elseif (!preg_match('/^[0-9]{2}$/', clear_data($duree))) {
        $erreur['duree'] = "Il faut ajouter les nombres des jours en max 99!";
    }

    if (empty($montant)) {
        $erreur['montant'] = "Il faut taper le montant!";
    } elseif (!preg_match('/[0-9(.{2})]/', clear_data($montant))) {
        $erreur['montant'] = "Il faut ajouter un nombre décimal!";
    }

    $nomClient = $_POST['nomClient'] ?? '';
    if (empty($nomClient)) {
        $erreur['nom'] = "Il faut remplir le nom!";
    } elseif (!preg_match('/^[a-z\d_éç]{3,15}$/i', clear_data($nomClient))) {
        $erreur['nom'] = "Veuillez entre au moins 3 caractères à 15!";
    }

    if (array_filter($erreur) == []) {
        include('connection.php');
        $requete = mysqli_query($conn, "SELECT Nom, Prenom FROM `client` WHERE Nom ='$nomClient' AND Prenom = '$prenomClient'");
        $num = mysqli_num_rows($requete);
        $sql = "SELECT ID FROM client WHERE Prenom = '$prenomClient' AND Nom = '$nomClient'";
        $requete = mysqli_query($conn, $sql);

    if ($requete) {
        $ligne = mysqli_fetch_assoc($requete);

        if ($ligne) {
            $IDClient = $ligne['ID'];
        } else {

        }
    } else {
    
    }
        if ($num > 0) {
            $_SESSION['IDClient'] = $IDClient;
            $_SESSION['nomC'] = $nomClient;
            $_SESSION['prenomC'] = $prenomClient;
            $_SESSION['nomsec'] = $nomsec;
            $_SESSION['prenomsec'] = $prenomsec;
            $_SESSION['passsec'] = $passsec;
            $_SESSION['formation'] = $formation;
            $_SESSION['date'] = $date;
            $_SESSION['duree'] = $duree;
            $_SESSION['montant'] = $montant;
            header('Location: autre1.php');
            exit;
        }

        if (array_filter($erreur) == []) {
            $sql = "INSERT INTO `client`(`Nom`, `Prenom`, `Gmail`, `Tele`) VALUES ('$nomClient', '$prenomClient', '$email', '$teleC')";
            mysqli_query($conn, $sql);
            echo $nomClient;
            include_once('InsererCommande.php');
            exit;
        }
    }
}

if (array_filter($erreur) != [] && isset($_POST['submit'])) {
    echo "<script>fadeIn();</script>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription Client</title>
    
    <link rel="stylesheet" href="styleN.css">
    <link rel="stylesheet" href="styleN1.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>   

    <style>

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
        .titreX .titreShadow{
            
            top :25%;
            font-size: 2.15rem;
            background-color: var(--primary-color) ;
            /* padding: 12px 23px 35px 23px; */
            height: 2.3em;
            width: 22.3%;
            color: var(--body-color);
            border-radius: 29px;
            box-shadow: 0px 2px 10px black;
            z-index: -1000;
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

    </style>
</head>

<body>
    <nav class="sidebar close">
    <header>
            <div class="image-text">
                <span class="image">
                    <img src="Images/GAFP.png" alt="logo">
                </span>

                <div class="text header-text">
                    <span class ="name">Group Academy</span>
                    <span class ="profession">Formation Pratique</span>

                </div>
            </div>
            <i class='bx bx-chevron-right toggle'></i>
        </header>

        <button id="scrollToBtn" class="scroll-to-btn" onclick="scrollToTarget()"><i class='bx bxs-down-arrow'></i></button>

        <div class="menu-bar">
            <div class="menu">
                <ul class="menu-links">
                    <li class="nav-link">
                        <a href="ajouterFormation.php">
                            <i class='bx bx-book icon' ></i>
                            <span class="text nav-text">Ajouter Formation</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="ajouterEnseignant.php">
                            <i class='bx bxs-user icon' ></i>
                            <span class="text nav-text">Ajouter Enseignant</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="ListEnseignant.php">
                            <i class='bx bxs-user-account icon' ></i>
                            <span class="text nav-text">Afficher les Enseignants</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="RCcommande.php">
                            <i class='bx bx-search-alt-2 icon' ></i>
                            <span class="text nav-text">R/C les Commandes</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="log-out.php">
                            <i class='bx bx-log-out icon' ></i>
                            <span class="text nav-text">Déconnexion</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <nav>       
        <div class="titreX">
        
        <h1>Ajouter commandes</h1>
        <div class="titreShadow"></div>
        </div>
    </nav>

    <section class="main">
        <form action="InscriptionCommande.php" method="POST">
        <div class="container">

        <div class="form">
    <div class="form-login">
        <span class="title">Inscription de Client</span>

        <div class="input-field">
            <input type="text" name="nomClient" tabindex="1" placeholder="Entre le Nom" value="<?php echo $nomClient; ?>">
            <i class='bx bx-user-circle icon'></i>
        </div>
        <div class="informerP">
            <p style="color:red; align-text:centre;">
                <?php echo "* " . $erreur['nom']; ?>
            </p>
        </div>

        <div class="input-field">
            <input type="text" name="prenomClient" tabindex="2" placeholder="Entre le Prénom" value="<?php echo $prenomClient; ?>">
            <i class='bx bx-user-circle icon'></i>
        </div>
        <div class="informerP">
            <p style="color:red; align-text:centre;">
                <?php echo "* " . $erreur['prenom']; ?>
            </p>
        </div>

        <div class="input-field">
            <input type="text" name="email" tabindex="3" placeholder="Email" value="<?php echo $email; ?>">
            <i class='bx bx-envelope icon'></i>
        </div>
        <div class="informerP">
            <p style="color:red; align-text:centre;">
                <?php echo "* " . $erreur['email']; ?>
            </p>
        </div>

        <div class="input-field">
            <input type="tel" name="teleC" tabindex="4" placeholder="Numéro de téléphone" value="<?php echo $teleC; ?>">
            <i class='bx bx-phone icon'></i>
        </div>
        <div class="informerP">
            <p style="color:red; align-text:centre;">
                <?php echo "* " . $erreur['tele']; ?>
            </p>
        </div>
    </div>
</div>

</div>

<div class="container">
    <div class="form">
        <div class="form-login">
            <span class="title">Détails de Commande</span>
            <div class="input-field">
            <select name="formation" tabindex="5">
                <?php
                include('connection.php');

                $formations = mysqli_query($conn, "SELECT Nom, Type, Prix FROM formation");

                $formations_par_type = array();

                echo '<option value=" ">Selecter la Formation</option>'; // Option par défaut

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
                <input type="text" name="dateInsc" tabindex="6" placeholder="Entre la date d'inscription" value="<?php echo $date; ?>">
                <i class='bx bx-calendar icon'></i>
            </div>
            <div class="informerP">
                <p style="color:red; align-text:centre;">
                    <?php echo "* " . $erreur['date']; ?>
                </p>
            </div>

            <div class="input-field">
                <input type="text" name="duree" tabindex="7" placeholder="Durée de la formation!" value="<?php echo $duree; ?>">
                <i class='bx bx-time-five icon'></i>
            </div>
            <div class="informerP">
                <p style="color:red; align-text:centre;">
                    <?php echo "* " . $erreur['duree']; ?>
                </p>
            </div>

            <div class="input-field">
                <input type="text" name="montant" tabindex="8" placeholder="Montant" value="<?php echo $montant; ?>">
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
</div>

        </form>
    </section>

    <script src="scriptN.js"></script>

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
</body>

</html>