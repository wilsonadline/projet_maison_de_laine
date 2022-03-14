// --------------------------Start Animation Contact Form-------------------------- //
// --------------------------Start cibler btn envoyer-------------------------- //
function btn(){
    const btn = document.querySelectorAll("button");
    btn.forEach(btn => {
        btn.classList.remove("visibility");
    });
}
// --------------------------End cibler btn envoyer-------------------------- //


// --------------------------Start cibler les icon fleche-------------------------- //
function fa(){
    const fa = document.querySelectorAll(".fa-arrow-down");
    fa.forEach(fa =>{
        fa.classList.add("visibility");
    });
}
// --------------------------End cibler les icon fleche-------------------------- //

// --------------------------Start amination-------------------------- //
function animationForm(){
    // je cible toutes les fleches
    const arrows = document.querySelectorAll(".fa-arrow-down");
    // je crée une boucle et je mets chaque fleche sur écoute
    arrows.forEach(arrow =>{
        arrow.addEventListener("click", () =>{
            // je rassemble les éléments dont j'ai besoin
            const champs= document.querySelectorAll(".champs");
            const input= arrow.previousElementSibling.querySelector("input");
            const textarea= document.querySelector("textarea");
            const parent= arrow.parentElement;
            const nextForm= parent.nextElementSibling;

            // Condition input
            if(input){
                // s'il s'agit d'un input et s'il remplie les conditions demandées alors il peut passer au prochain input 
                // si non j'appelle mon animation shake qui se trouve dans le CSS
                if(input.type === "text" && input.id === "contact_nom" && validateUser(input)){
                    nextSlide(parent, nextForm);
                }else if(input.type === "text" && input.id === "contact_prenom" && validateUser(input)){
                    nextSlide(parent, nextForm);
                }else if(input.type === "email" && input.id === "contact_email" && validateEmail(input)){
                    nextSlide(parent, nextForm);
                }else if(input.type === "text" && input.id === "contact_sujet" && validateUser(input)){
                    nextSlide(parent, nextForm);
                }else{
                    parent.style.animation= "shake 0.5s ease"
                }
            }else{ // Condition textearea
                // s'il s'agit d'un textearea et si la longueur de sa valeur est > à 3 lettres
                if(textarea && textarea.value.length > 3){
                    // alors que la boucle ajoute ou retire les éléments demandés
                    champs.forEach(unChamp => {
                        unChamp.classList.remove("positionAbsolue");
                        unChamp.classList.add("positionRelative");
                        unChamp.classList.remove("innactive");
                        unChamp.classList.remove("active");
                    });
                    // Puis j'appelle mes fonctions bouton et fleche que j'ai créé en debut de script
                    btn();
                    fa();
                }else{
                    // si non j'appelle mon animation shake qui se trouve dans le CSS
                    parent.style.animation = "shake 0.5s ease";
                }
            }
            // réinitialisation mon animation pour qu'elle puisse s'animer à chaque fois
            parent.addEventListener("animationend", () =>{
                parent.style.animation = "";
            })
        });
    });
}

// je crée quatre fonctions et je leur initialise une longueur à leur valeur
function validateUser(user){
    if(user.value.length < 3){
    }else{
        return true;
    }
}

function validateSujet(sujet){
    if(sujet.value.length < 3){
    }else{
        return true;
    }
}

function validateMessage(message){
    if(message.value.length < 3){
    }else{
        return true;
    }
}

function validateEmail(email){
    const validation = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if(validation.test(email.value)){
        return true;
    }
}

// fonction permettant d'ajouter ou retirer les classes active et innactive
function nextSlide(parent, nextForm){
    parent.classList.add("innactive");
    parent.classList.remove("active");
    nextForm.classList.add("active");
}

// j'appelle ma fonction pour qu"elle puisse s'appliquer
animationForm();
// --------------------------End Animation Contact Form -------------------------- //
