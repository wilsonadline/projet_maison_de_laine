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
            const div = document.querySelector(".row")
            const input = arrow.previousElementSibling.querySelector("input");
            const textarea = document.getElementsByTagName("textarea");
            const parent = arrow.parentElement;
            const nextForm = parent.nextElementSibling;
            
            if(input){
                if(input.type === "text" && input.id === "contact_nom" && validateUser(input)){
                    nextSlide(parent, nextForm);
                }else if(input.type === "text" && input.id === "contact_prenom" && validateUser(input) ){
                    nextSlide(parent, nextForm);
                } else if(input.type === "email" && input.id === "contact_email" && validateEmail(input)){
                    nextSlide(parent, nextForm);
                    console.log(validateEmail(input)    );
                }else if(input.type === "text"  &&  input.id === "contact_sujet" && validateUser(input) ){
                    nextSlide(parent, nextForm);
                }
            }else if(textarea){
                champs.forEach(unChamp => {
                    unChamp.classList.remove("positionAbsolue");
                    unChamp.classList.add("positionRelative");
                    unChamp.classList.add("changeTranslation");
                    unChamp.classList.remove("innactive");
                    unChamp.classList.remove("active");
                });
                
                div.classList.add("center");
                
                btn();
                fa();
            }else{
                parent.style.animation = "shake 0.5s ease"
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

function validatePrenom(prenom){
    if(prenom.value.length < 3){
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

function error(color) {
    document.body.style.backgroundColor = color;
}

function changeDisplayBody(body) {
    document.body.style.position = body;
}

function changeDisplayInput(input) {
    document.body.style.position = input;
}

animationForm();