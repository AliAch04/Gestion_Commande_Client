
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styleN1.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Document</title>

    <style>
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
    </style>
</head>
<body>

<section class="main">
        <form action="detailCommandeSUBMIT.php" method="POST">

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
                            echo '<option value="' . $f['Nom'] . '">' . $f['Nom'] . str_repeat('&nbsp;', 10) . $f['Prix'] . 'DH</option>';
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
                <input type="text" name="dateInsc" placeholder="Entre la date d'inscription" value="<?php echo $date; ?>">
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
    
</body>
</html>
