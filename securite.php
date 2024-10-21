<?php
// Déclaration
session_start();
$_SESSION['user_authenticated1'] = false;
$output = "s";

if (isset($_POST['submit'])) {
    $password = $_POST['password'];

    if ($password === "GAFP20112011") {
        $_SESSION['user_authenticated1'] = true;
        header('Location: Inscription.php');
        exit;
    } else {
        $output = "Mot de passe Incorrect! Veuillez réessayer.";
    }
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
    <title>Sécurité</title>

     <style>

        .mainT{
            display: flex;
            align-items: center;
            justify-content: center;
            
            
        }
        .containerT{
            margin-top: 9em;
            position: relative;
            max-width: 600px;
            background: var(--primary-color2);
            box-shadow: 0px 2px 10px black;
            border-radius: 10px;
        }
        .containerT .form{
            padding: 30px;
            min-width: 600px;
        }

        .submitJ{
            padding: 3px 22px;
            border: none;
            color : var(--primary-color);
            border-radius: 4px;
            height: 60%;
            width: 20%;
            font-size: 16px;
            margin-left: 8px;
            font-weight: 400;
            background-color: var(--primary-color-ligh);
            cursor: pointer;
            transition: all 0.4s ease;
        }

        .submitJ:hover{
            background-color: var(--primary-color);
            color: var(--primary-color-ligh);
        }


        .form input{
            height: 35px;
            width: 45%;
            position: relative;
            margin: 7px 33px 7px 0;
            
            font-size: 16px;
            padding: 0 18px;
            border: none;
            outline: none;
            border-bottom: 2px solid #ccc;
            border-top: 2px solid #ccc transparent;
    
            transition: all 0.4s ease;
            background: var(--primary-color2);
        }
        
        .form h2{
            font-size: 27px;
            font-weight: 600;
            color: var(--primary-color-ligh);
            margin-bottom: 30px;
        }

        .form h2::before{
            content: '';
            position: absolute;
            left: 5.4%;
            bottom: 69%;
            height: 4px;
            width: 100px;
            background: var(--primary-color3);
        }

        input:is(:focus, :valid){
            border-bottom-color: var(--primary-color);
        }

        i{
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            color: var(--toggle-color);
            font-size: 23px;
            transition: all 0.4s ease;
        }

        i.show-hide{
            right :52.5%;
            top: 54.2%;
            cursor: pointer;
            padding: 10px;
        }

        input:is(:focus, :valid) ~ i{
            color: var(--primary-color);
        }

        .form .gerant{
            margin-top: 30px;
            color: var(--primary-color-ligh);
        }

        .informer{
            opacity: 0;
            bottom: -45%;
            left: 16%;
            position: absolute;
            min-height: 60px;
            min-width: 400px;
            padding: 10px;
            border-radius: 6px;
            background-color: #5ca0fe;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>

</head>
<body>
    <div class="mainT">
        <div class="containerT">
            <form method="post" class="form">
                <h2>Enter le mot de passe de sécurité</h2>
                
                    <input type="password" class="password" id="passwordInput" name="password" placeholder="Enter le Password" required>
                    <i class='bx bx-hide show-hide'></i>
                    <button class="submitJ" name="submit" type="submit">Entrer</button>
                    <button class="submitJ" onclick="Deconx()">Retour</button>
                
                
                <p class="gerant">Si vous ne le connaissez pas, veuillez demander au gérant.</p>
                
            </form>
            <div class="informer" id="informer">  
                    <p style="color:red; align-text:centre;">
                        <?php 
                            echo $output; ?>
                        
                    </p>          
                </div>
        </div>
        
    </div>
    <script src="scriptN.js"></script>

    <script>
    <?php
    if (!empty($output) && isset($_POST['submit'])) {
        echo "fadeIn();";
    }
    ?>
    </script>

    <script>
        function Deconx(){
        window.location.href = "Identification.php";
        }


    const show_hide = document.querySelectorAll(".show-hide"),
    field = document.querySelectorAll(".password");

    show_hide.forEach(eyeIcon =>{
    eyeIcon.addEventListener("click",() =>{
        field.forEach(field => {
            if (field.type === "password") {
                field.type = "text";
                // Change the class of the icon
                eyeIcon.classList.replace("bx-hide", "bx-show");
            } else {
                field.type = "password";
                eyeIcon.classList.replace("bx-show", "bx-hide");
            }
        });
        })
    });
    </script>
    
</body>
</html>
