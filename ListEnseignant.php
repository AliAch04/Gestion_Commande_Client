<?php
session_start();

if (!isset($_SESSION['user_authenticated']) || $_SESSION['user_authenticated'] !== true) {
    header("Location: Identification.php");
    exit();
}

require 'connection.php';

// Fetch distinct Formation values from the database
$formationQuery = "SELECT DISTINCT Formation FROM Enseignant";
$formationResult = mysqli_query($conn, $formationQuery);

// Create an array to store sections
$sections = array();

while ($formationRow = mysqli_fetch_assoc($formationResult)) {
    $formationName = $formationRow['Formation'];
    // Query to fetch Enseignants with the current Formation
    $enseignantsQuery = "SELECT * FROM Enseignant WHERE Formation = '" . $formationName . "'";
    $enseignantsResult = mysqli_query($conn, $enseignantsQuery);
    
    $sections[$formationName] = array();

    while ($row = mysqli_fetch_assoc($enseignantsResult)) {
        $sections[$formationName][] = $row;
    }
}

// Check if the search button is clicked
if (isset($_POST['searchButton'])) {
    // Get the search term entered by the user
    $searchTerm = $_POST['searchInput'];

    // Filter sections based on the search term
    $filteredSections = array();

    foreach ($sections as $formationName => $enseignants) {
        $filteredEnseignants = array();

        foreach ($enseignants as $enseignant) {
            // Check if the search term is present in the NomComplet, Email, or Formation of the enseignant
            if (stripos($enseignant['NomComplet'], $searchTerm) !== false ||
                stripos($enseignant['Email'], $searchTerm) !== false ||
                stripos($enseignant['Formation'], $searchTerm) !== false) {
                $filteredEnseignants[] = $enseignant;
            }
        }

        // Add the formation only if there are matching enseignants
        if (!empty($filteredEnseignants)) {
            $filteredSections[$formationName] = $filteredEnseignants;
        }
    }
} else {
    // If the search button is not clicked, use all sections as before
    $filteredSections = $sections;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>list des Enseignants</title>
    
    <link rel="stylesheet" href="styleN.css">
    
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <link rel="stylesheet" href="CSS/swiper-bundle.min.css">
<style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap');

*{
    font-family: 'Poppins', sans-serif;
    padding: 0;
    margin: 0;
    box-sizing: border-box;
}
        
.titreX{
    background: var(--primary-color) ;
    display: flex;
    justify-content: center ;
    align-items: center;
    height: 5em;
    width: 100%;
    position: relative;
    
    
}
.titreX .titreShadow{
    position: absolute;
    top :25%;
    font-size: 2.15rem;
    background-color: var(--primary-color) ;
    height: 2.3em;
    width: 24.8%;
    color: var(--body-color);
    border-radius: 29px;
    box-shadow: 0px 2px 10px black;
    z-index: -1;
}

.titreX h1{
    position: absolute;
    top :35%;
    font-size: 1.6rem;
    color: var(--body-color) ;
   
}

.titreX .to-img{
    position: absolute;
    top :4%;
    left: 0%;
    width: 5.5em;
   height: 9em;
   width: 9em;
   background-color: #695CFE;
   border-radius: 50%;
   z-index: 10;
   
}

.titreX img{
    height: 8em;
   width: 8em;
   
}
body{
    height: 100vh;
}
.main{
    min-height: 75vh;
    display: flex;
    align-items: center;
    justify-content: center;
    
}

.slide-container{
    max-width: 1120px;
    width: 100%;
    border-radius: 25px;
    background-color: #9CA5FF;
    padding: 20px  ;
    margin-top: 50px;
}

.name{
    font-size: 18px;
    font-weight: 500;
    color: #333; 
}

.desc{
    font-size:14px ;
    color: #707070;
    text-align: center;
}

.slide-content{
    padding: 45px 25px;
    margin: 0 40px;
    overflow: hidden;
    border-radius: 25px;
}

.image-content{
    position: relative;
    row-gap: 5px;
    padding: 25px 0;
}

.card{
    
    border-radius: 25px;
    background-color: #fff;
}

.image-content, 
.card-content{
    display: flex;
    flex-direction: column;
    align-items: center; 
    padding: 10px 14px;
    
}
.card-content{
    margin: 10px;
}


.card-image{
    position: relative;
    height: 150px;
    width: 150px;
    border-radius: 50%;
    background-color: #fff;
    padding: 3px;
}

.card-image .card-img{
    height: 100%;
    width: 100%;
    object-fit: cover;
    border-radius: 50%;
    border: 4px solid #4070f4;
}


.overlay{
     position: absolute;
     left: 0;
     top: 0;
     height: 100%;
     width: 100%;
     background-color: #4070f4 ;
    border-radius: 25px 25px 0 25px;

}

.overlay::before,
.overlay::after{
    content: '';
    position: absolute;
    right: 0;
    bottom: -40px;
    height: 40px;
    width: 40px;
    background-color: #4070f4;
}

.overlay::after{
    border-radius: 0 25px 0 0;
    background-color: #fff;
}


.button{
    border: none;
    font-size: 16px;
    color: #fff;
    padding: 8px 16px;
    background-color: #4070f4;
    border-radius: 6px;
    margin: 14px;
    transition: all 0.4s ease;
    cursor: pointer;
}

.button:hover{
    background-color: #265df2;
}

.swiper-navBtn{
    color:#6e93f7;
    transition: color 0.4s ease;
}

.swiper-navBtn{
    color:#4070f4;
}

.swiper-navBtn::before,
.swiper-navBtn::after
{
    font-size: 40px;
}

.swiper-button-next{
    right: 0;
}

.swiper-button-prev{
    left: 0;
}

.swiper-pagination-bullet{
    background-color :#695CFE;
    opacity: 1;
}

.swiper-pagination-bullet-active{
    background-color :#4070f4;
    
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

        .centry{
            height: 100px;
            width: 400px;
            margin: 20px  auto;
            display: flex;
            align-items: center;
        }
        .centry .input-field{
    height: 50px;
    width: 100%;
    position: relative;
    margin: auto;
}

.input-field input{
    position: absolute;
    font-size: 16px;
    padding: 0 35px;
    border: none;
    outline: none;
    border-radius: 10px;
    border-bottom: 2px solid #ccc;
    border-top: 2px solid #ccc transparent;
    height: 100%;
    width: 100%;
    transition: all 0.4s ease;
    background: #5ca0fe;
    color: #ddd;
}

::placeholder{
    color: #ddd;
}

.input-field input:is(:focus){
    border-bottom-color: #695CFE;
}

.input-field input:is(:focus) ~ i{
    color: #695CFE;
}

.cont{
    position: relative;
    right: -45%;
    bottom: 45%;
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

.input-field i{
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    color: #ddd;
    font-size: 23px;
    transition: all 0.4s ease;
}

.input-field i.icon{
    left :1.5%;
}

.input-field i.show-hide{
    right :0;
    cursor: pointer;
    padding: 10px;
}

.conty{
    
    

}

.conty .retour{
    

}

.enseignant-count{
    margin-left: 0px;
    color: #ddd;
    font-weight: bold;
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

        
@media screen and (max-width: 768px){
    .swiper-navBtn{
        display: none;
    }
    .slide-content{
        margin: 0 10px; 
    }
}

    </style>
</head>
<body>
    <nav>
        <div class="titreX">
            <h1>Liste des Enseignants</h1>
            <div class="titreShadow"></div>
            <div class="cont">
                <button class ="retour" onclick="Retour()"  type="submit"><i class='bx bx-log-out-circle icon' ></i></button>   
            </div>
        </div>
        
    </nav>

    <button id="scrollToBtn" class="scroll-to-btn" onclick="scrollToTarget()"><i class='bx bxs-down-arrow'></i></button>

    <div class="centry">
    <div class="input-field">
    <form method="post" action="">
        <input type="text" id="searchInput" name="searchInput" placeholder="Rechercher un Enseignant">
        <i class='bx bx-search-alt-2 icon'></i>
        <button id="searchButton" class="submit" style="margin-left : 15px;"name="searchButton" type="submit">Rechercher</button>
        
    </form>
    </div>
    </div>
    
    

    <?php foreach ($filteredSections as $formationName => $enseignants) : ?>
        <div class="slide-container swiper">
            <div class="conty">
                <h2 style="font-size : 27px; color: #ddd"><?= $formationName ?></h2>
                <p class="enseignant-count"><?= count($enseignants) ?> Enseignants</p> <!-- Add this line -->
            
            </div>
            <div class="slide-content">
                <div class="card-wrapper swiper-wrapper">
                    <?php foreach ($enseignants as $row) : ?>
                        <?php
                        $capacity = $row['CapaciteMax'];
                        $capacityCurr = $row['CapaciteCurr'];
                        $oneThird = $capacity / 3;
                        $twoThirds = 2 * $oneThird;

                        if ($capacityCurr < $oneThird) {
                            $colorClass = 'green';
                        } elseif ($capacityCurr < $twoThirds) {
                            $colorClass = 'yellow';
                        } elseif ($capacityCurr < $capacity) {
                            $colorClass = 'orange';
                        } else {
                            $colorClass = 'red';
                        }
                        ?>
                        <div class="card swiper-slide">
                            <div class="image-content">
                                <span class="overlay"></span>
                                <div class="card-image">
                                    <img src="imagesEns/<?= $row['PhotoRef'] ?>" alt="" class="card-img">
                                </div>
                            </div>
                            <div class="card-content">
                                <h2 class="name" style="margin-bottom: 15px;"><?= $row['NomComplet'] ?></h2>
                                <p class="desc" style="margin-bottom: 15px;"><?= $row['Email'] ?></p>
                                <p style="margin-bottom: 5px;" class="desc <?= $colorClass ?>"><strong>C. Courante:</strong> <?= $row['CapaciteCurr'] ?></p>
                                <p class="desc"><strong>C. Maximale:</strong> <?= $row['CapaciteMax'] ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <script src="JS/swiper-bundle.min.js"></script>
<script>
    var swiper = new Swiper(".slide-content", {
      slidesPerView: 3,
      spaceBetween: 25,
      slidesPerGroup: 3,
      loopFillGroupWithBlank: true,
      loop: true,
      
      fade: 'true',
      grabCursor: 'true',
      pagination: {
        el: ".swiper-pagination",
        clickable: true,
        dynamicBullets: true,
      },
      navigation: {
        nextEl:".swiper-button-next",
        prevEl:".swiper-button-prev",
      },
      breakpoints: {
        0:{
            slidesPerView: 1,
        },
        520:{
            slidesPerView: 2,
        },
        950:{
            slidesPerView: 3,
        },
      }
    });


    function Retour(){
    window.location.href = "InscriptionCommande.php";
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
</body>
</html>
