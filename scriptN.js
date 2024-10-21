const body = document.querySelector("body"),
    sidebar = body.querySelector(".sidebar"),
    toggle = body.querySelector(".toggle");
    

toggle.addEventListener("click",() =>{
    sidebar.classList.toggle("close");
});



    var opacity = 0;
    var intervalID = 0;
    
function fadeIn() {
    intervalID = setInterval(show, 60);
}

function fadeOut() {
    intervalID = setInterval(hide, 60);
}

function show() {
    var info = document.getElementById("informer");
    opacity = Number(window.getComputedStyle(info).getPropertyValue("opacity"));

    if (opacity < 1) {
        opacity = opacity + 0.1;
        info.style.opacity = opacity;
    } else {
        
        clearInterval(intervalID);
        // If fadeIn is complete, start the fadeOut after a 3-second delay
        setTimeout(function () {
            fadeOut();
        }, 2500); 
    }
}

function hide() {
    var info = document.getElementById("informer");
    opacity = Number(window.getComputedStyle(info).getPropertyValue("opacity"));

    if (opacity > 0) {
        opacity = opacity - 0.1;
        info.style.opacity = opacity;
    } else {
        clearInterval(intervalID);
    }
}
        

