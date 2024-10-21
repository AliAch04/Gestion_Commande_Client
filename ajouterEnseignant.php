<?php
session_start();

if (!isset($_SESSION['user_authenticated']) || $_SESSION['user_authenticated'] !== true) {
    header("Location: Identification.php");
    exit();
}


$nom = $prenom = $capaciteCurr = $email = $formation = $capaciteMax = $output = "";
$errors = [];

function clear_data($var) {
    return stripslashes(trim(strip_tags($var)));
}

function validate_data($data, $pattern, $error_message) {
    global $errors, ${$data};
    if (empty($_POST[$data])) {
        $errors[] = "Veuillez ajouter un $data.";
    } else {
        ${$data} = clear_data($_POST[$data]);
        if (!preg_match($pattern, ${$data})) {
            $errors[] = $error_message;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Vérification pour tous les champs vides
    if (empty($_POST['prenom']) && empty($_POST['email']) && empty($_POST['capaciteMax']) && empty($_POST['formation'])) {
        $errors[] = "Veuillez ajouter les données!";
    } else {
        validate_data('prenom', '/^[a-zA-Z\d_çé+\s]{3,15}$/i', "Veuillez entrer au moins 3 caractères et au maximum 15!");
        validate_data('email', '/^[a-zA-Z\d_çé+.]+@[a-zA-Z\d_çé+.]+\.[a-zA-Z\d_çé+]{2,}$/i', "Veuillez entrer une adresse e-mail valide.");
        validate_data('capaciteMax', '/^[0-9]{1,6}$/', "Veuillez entrer un nombre valide au moins superieur à 10!");

        if (empty($_POST['formation'])) {
            $errors[] = "Veuillez ajouter une Formation.";
        } else {
            $formation = $_POST['formation'];
        }

        if (empty($_FILES['image']['name'])) {
            $errors[] = "Veuillez sélectionner une photo.";
        }
    }

    if (empty($errors)) {
        include('connection.php');

        $requete = mysqli_query($conn, "SELECT * FROM `Enseignant`");

        if ($requete) {
            while ($secretaire = mysqli_fetch_assoc($requete)) {
                if (strcasecmp($prenom, $secretaire['NomComplet']) == 0) {
                    $output = "* Ce/Cette Enseignant(e) est déjà existé!";
                    break;
                }

                if (strcasecmp($email, $secretaire['Email']) == 0) {
                    $output = "* Cet e-mail est déjà utilisé par un autre enseignant!";
                    break;
                }
            }
        } else {
            $output = "Erreur lors de récupérer les données!";
        }

        if (empty($output)) {
            $name = $prenom;

            if ($_FILES["image"]["error"] === 4) {
                echo "<script>alert('L'images nexiste pas!')</script>";
            } else {
                $fileName = $_FILES["image"]["name"];
                $fileSize = $_FILES["image"]["size"];
                $tmpName = $_FILES["image"]["tmp_name"];

                $validExt = ['jpg', 'jpeg', 'png'];
                $imageExt = explode('.', $fileName);
                $imageExt = strtolower(end($imageExt));

                if (!in_array($imageExt, $validExt)) {
                    $output = 'Invalide Extension!';
                } else {
                    $newName = uniqid();
                    $newName = $name . "." . $imageExt;
                    move_uploaded_file($tmpName, 'imagesEns/' . $newName);

                    $query = "INSERT INTO `Enseignant`(`PhotoRef`, `NomComplet`, `Email`,`Formation`, `CapaciteMax`, `CapaciteCurr`) VALUES ('$newName','$prenom','$email','$formation','$capaciteMax', '$capaciteCurr')";
                    mysqli_query($conn, $query);
                }
                $output = "Enseignant(e) ajouté(e) avec succès.";
            }
        }

        mysqli_close($conn);
    } else {
        $output = implode("<br>", $errors);
    }
}

if (isset($_POST['submitP'])) {
    header('Location: InscriptionCommande.php');
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
    <title>Ajouter un Enseignant</title>
    
<style>

.titreX .titreShadow{
    position: absolute;
    top :25%;
    font-size: 2.15rem;
    background-color: var(--primary-color) ;
    height: 2.3em;
    width: 23%;
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

.form .title::before{
    content: '';
    position: absolute;
    left: 7.5%;
    bottom: 89%;
    height: 3.5px;
    width: 80px;
    background: var(--primary-color3);
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

.centri{
    padding-top: 20px;
}

.card-image{
    position: relative;
    height: 150px;
    width: 150px;
    border-radius: 50%;
    background-color: #fff;
    padding: 3px;
    margin: auto;
}

.card-image .card-img{
    height: 100%;
    width: 100%;
    object-fit: cover;
    border-radius: 50%;
    border: 4px solid #4070f4;
}

.card-image .round{
    position:absolute;
    bottom: 0;
    right: 0;
    background-color: #4070f4;
    width: 36px;
    height: 36px;
    line-height: 40px;
    color: #ddd;
    text-align: center;
    border-radius:50% ;
    overflow: hidden;
}

.card-image .round input[type='file']{
    position: absolute;
    transform: scale(2);
    opacity: 0;
}

input[type=file]::-webkit-file-upload-button{
    cursor: pointer;
}

.icone{
    font-size: 18px;
}


.cont{
    position: relative;
    right: -45%;
    bottom: 0%;
    height: 40px;
}

.cont .retour{
    padding: 0 35px;
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

.informer1{
    z-index: 100;
    display: none;
    top: 15%;  /* Modifié pour être au milieu en bas */
    left: 50%;    /* Modifié pour être au milieu horizontalement */
    transform: translateX(-50%);  /* Ajustement pour centrer horizontalement */
    position: fixed;
    max-height: 100px;
    min-width: 400px;
    padding: 10px;
    border-radius: 6px;
    background-color: #5ca0fe;
    display: flex;
    justify-content: center;
    align-items: center;
}


.mainXn{
    display: flex;
    align-items: center;
    justify-content: center;
    position: fixed;
    top: 17%;  /* Modifié pour être au milieu en bas */
    left: 50%;    /* Modifié pour être au milieu horizontalement */
    transform: translateX(-50%);  /* Ajustement pour centrer horizontalement */
    position: fixed;
    width: 50%;
}

    </style>
</head>
<body>



    <nav>       
        <div class="titreX">
        
        <h1>Ajouter un Enseignant</h1>
        <div class="titreShadow"></div>
            <div class="cont">
                <button class ="retour" onclick="Retour()"  type="submit"><i class='bx bx-log-out-circle icon' ></i></button>   
            </div>
        </div>
    </nav>

    
    <div class="informer1" id="informer">  
        <p style="color: <?php echo ($output === 'Enseignant(e) ajouté(e) avec succès.') ? 'green' : 'red'; ?>">
            <?php echo $output; ?>
        </p>          
    </div>
    
    <section class="mainXn">
    <div class="container">
    
    <div class="form">
        <div class="form-login">
            <span class="title">Détails de l'Enseignant</span>
            <form method="POST" action="ajouterEnseignant.php" autocomplete="off" enctype="multipart/form-data">

            <div class="centri">
                <div class="card-image">
                    <img src="Images/icon.jfif" id="image-preview" width=100 height=100 alt="" class="card-img">
                    <div class="round">
                        <input type="file" id="image" class="upload-box" name="image" id="image" accept=".jpg, .jpeg, .png" onchange="previewImage(event)">
                        <i class='bx bx-camera icone'></i>
                    </div>
                </div>
            </div>

                <div class="input-field">
                <input type="text" name="prenom" value="<?php echo $prenom; ?>" placeholder="Entrez le Nom Complet">
                    <i class='bx bx-user-circle icon'></i>
                    
                </div>

                <div class="input-field">
                <input type="text" name="email" value="<?php echo $email; ?>" placeholder="Entrez le Email">
                    <i class='bx bx-envelope icon'></i>
                    
                </div>

                <div class="input-field">
                <input class="input" type="text" name="capaciteMax" value="<?php echo $capaciteMax; ?>" placeholder="Entrez la capacité d'enseignant">
                    <i class='bx bx-doughnut-chart icon'></i>
                    
                </div>

                <div class="input-field">
                <select name="formation">
                    <option value="">Selecter la Formation</option>
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

                <div class="input-field button">
                    <button class="submit" type="submit" name="submit" >Ajouter</button>
                    
                </div>
                
                
             </form>
        </div>   
    </div>
    </div>
    
</section>
       


    
<script src="scriptN.js"></script>

<script>

function Retour(){
    window.location.href = "InscriptionCommande.php";
}

function getDown() {
    var myMain = document.querySelector('.mainXn');

    // Démarre le mouvement vers 30%
    myMain.style.top = '29%';

    // Démarre le mouvement de 30% à 18% après 3 secondes
    setTimeout(function() {
        myMain.style.transition = 'top 3s';  // Définit la transition sur 3 secondes
        myMain.style.top = '17%';
    }, 1981);
}

    function previewImage(event) {
        const imagePreview = document.getElementById("image-preview");
        const fileInput = event.target;
        const file = fileInput.files[0];

        if (file) {
            const reader = new FileReader();

            reader.onload = function(e) {
                imagePreview.src = e.target.result;
            };

            reader.readAsDataURL(file);
        } else {
            imagePreview.src = "Images/icon.jfif"; // Afficher une image par défaut si aucun fichier n'est sélectionné
        }
    }
</script>      



<script>
    <?php
    if (!empty($output) && isset($_POST['submit'])) {
        echo "fadeIn();";
        echo "getDown();";
    }
    ?>

</script>
    
</body>
</html>


