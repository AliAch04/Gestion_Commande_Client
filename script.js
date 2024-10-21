// function toggleDropdown() {
//     var dropdown = document.getElementById("myDropdown");
//     if (dropdown.classList.contains("show")) {
//         dropdown.classList.remove("show");
//     } else {
//         dropdown.classList.add("show");
//     }
// }

function RC(){
    window.location.href = "RCcommande.php";
}

function Retour(){
    window.location.href = "InscriptionCommande.php";
}

function Deconx(){
    window.location.href = "Identification.php";
}

// Declaration pour obtenez l'élément de bouton et le mot de passe 
const submitButton = document.getElementById("Bouton");
const passwordInput = document.getElementById("passwordInput");

// Ajouter click event listener au bouton
submitButton.addEventListener("click", function() {
    const enteredPassword = passwordInput.value;

    // Vérifier si le mot de passe est correcte
    if (enteredPassword === "GAFP20112011") {
        // Passer au fichier d'inscription
        window.location.href = "Inscription.php"; 
    } else {
        // Le mot de passe est incorrecte
        alert("Mot de passe Incorrect! Veuillez réessayer.");
    }
});