<?php
    $server = 'localhost';
    $username = 'root';
    $password = '';
    $conn = mysqli_connect($server,$username,$password);

    if(!$conn){
        die("Erreur lors de la connection : ". mysqli_connect_errno());
    }

    $sql = 'Create database if not exists commandesysteme';

    $resultat = mysqli_query($conn,$sql);

    if(!$resultat){
        echo "Erreur lors de creation de DB".mysqli_error($conn);
        exit();
    }

    mysqli_select_db($conn,'commandesysteme');

    $sql = 'CREATE TABLE if not exists client (
        ID int auto_increment Primary key,
        Nom varchar(25) not null,
        Prenom varchar(25) not null,
        Gmail varchar(100) not null,
        Tele varchar(20) not null
        );';
    mysqli_query($conn,$sql);

    $sql='CREATE TABLE if not exists Formation (
        Nom VARCHAR(25) Primary key,
        Type VARCHAR(25) not null,
        Prix float
    );' ;
    mysqli_query($conn,$sql);


    $sql='CREATE TABLE if not exists Enseignant (
        Matricule int auto_increment primary key,
        PhotoRef VARCHAR(25) unique,
        NomComplet VARCHAR(25) unique,
        Email VARCHAR(25) unique,
        Formation varchar(25),
        CapaciteMax int,
        CapaciteCurr int,
        FOREIGN KEY (Formation) REFERENCES formation(Nom)
    );' ;
    mysqli_query($conn,$sql);
    
    $sql = 'CREATE TABLE if not exists Secretaire (
        Matricule int auto_increment primary key,
        Nom VARCHAR(25),
        Prenom VARCHAR(25),
        Password VARCHAR(15)
    );';
    mysqli_query($conn,$sql);   

    $sql ='CREATE TABLE if not exists Commande (
        Numero int auto_increment PRIMARY KEY,
        Date date not null,
        Duree int not null,
        Montant float not null,
        EstTerminee boolean default 0,
        Formation varchar(25),
        Client int,
        MatEns int,
        MatSecretaire int,
        FOREIGN KEY (Formation) REFERENCES formation(Nom),
        FOREIGN KEY (Client) REFERENCES Client(ID),
        FOREIGN KEY (MatEns) REFERENCES Enseignant(Matricule),
        FOREIGN KEY (MatSecretaire) REFERENCES Secretaire(Matricule)
    );';
    mysqli_query($conn,$sql);
    /*
    $sql = "INSERT INTO secretaire (Nom, Prenom, Password)
    VALUES ('Admin', 'admin', 'GAFP20112011')
    ON DUPLICATE KEY UPDATE Nom = 'Admin', Prenom = 'admin', Password='GAFP20112011'";

    mysqli_query($conn,$sql);
    /*
        $sql ='INSERT IGNORE INTO Formation (Nom, Type, Prix) VALUES ("Français","Langages",200);';
    mysqli_query($conn,$sql);
    
    $sql ='INSERT IGNORE INTO Formation (Nom, Type, Prix) VALUES ("Anglais","Langages",200);';
    mysqli_query($conn,$sql);

    $sql ='INSERT IGNORE INTO Formation (Nom, Type, Prix) VALUES ("Espagnole","Langages",300);';
    mysqli_query($conn,$sql);
    */
    
    
?>