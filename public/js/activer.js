let article_id = document.getElementById("flexSwitchCheckDefault").value;
window.onload = () => {

    let activer = document.querySelectorAll("[type=checkbox]")
    for( let bouton of activer){
        bouton.addEventListener("click" , function() {
            let xmlhttp = new XMLHttpRequest;
            console.log(article_id); 

            xmlhttp.open("get", `/admin/articles/activer/` + article_id)
            xmlhttp.send()
        })
    }
}