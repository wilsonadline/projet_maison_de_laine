function btn(){
    const btn = document.querySelectorAll("button");
    btn.forEach(btn => {
        btn.classList.remove("visibility");
    });
}

function fa(){
    const fa = document.querySelectorAll(".fa-arrow-down");
    fa.forEach(fa =>{
        fa.classList.add("visibility");
    });
}

function animationForm(){
    const arrows = document.querySelectorAll(".fa-arrow-down");
    arrows.forEach(arrow =>{
        arrow.addEventListener("click", () =>{
            const champs = document.querySelectorAll(".champs");
            const input = arrow.previousElementSibling.querySelector("input");
            const textarea = document.querySelector("textarea");
            const parent = arrow.parentElement;
            const nextForm = parent.nextElementSibling;
            
            if(input){
                if(input.type === "text" && input.id === "contact_nom" && validateUser(input)){
                    nextSlide(parent, nextForm);
                }else if(input.type === "text" && input.id === "contact_prenom" && validateUser(input) ){
                    nextSlide(parent, nextForm);
                } else if(input.type === "email" && input.id === "contact_email" && validateEmail(input)){
                    nextSlide(parent, nextForm);
                }else if(input.type === "text"  &&  input.id === "contact_sujet" && validateUser(input) ){
                    nextSlide(parent, nextForm);
                }else{
                    parent.style.animation = "shake 0.5s ease"
                }
            }
            else
            {
                if(textarea && textarea.value.length > 3 ){
                    champs.forEach(unChamp => {
                        unChamp.classList.remove("positionAbsolue");
                        unChamp.classList.add("positionRelative");
                        // unChamp.classList.add("changeTranslation");
                        unChamp.classList.remove("innactive");
                        unChamp.classList.remove("active");
                    });
                
                    btn();
                    fa();
                }else{
                    parent.style.animation = "shake 0.5s ease"
                }
            }
            parent.addEventListener("animationend", () =>{
                parent.style.animation = "";
            })
        });
    });
}

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

function validateEmail(email) {
    const validation = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if(validation.test(email.value)){
        return true;
    }
}

function nextSlide(parent, nextForm) {
    parent.classList.add("innactive");
    parent.classList.remove("active");
    nextForm.classList.add("active");
}

animationForm();