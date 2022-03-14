// window/onload nous sert a nous assurer que notre document est réellement chargé
window.onload = () => {
    // Variables
    // la public key de stripe
    let stripe = Stripe('pk_test_51JlA15DnhjURuLLqIC7kBQg2Cu3RYuEUUYmEUxtTiX4whUfNbHfazvmqbyoiBRHdb6xXDFvrWfXJD6nNHwL1FmdP009MV0I6wR');
    let elements = stripe.elements();

    // objet de la page
    let cardHolderName = document.getElementById("cardholder-name");
    let cardButton = document.getElementById("card-button");
    let clientSecret = cardButton.dataset.secret;
    
    // Creer les elements du formulaire de la carte bancaire 
    // CB date exp etc... 
    let card = elements.create("card");
    card.mount("#card-elements");
    
    // on gere la saisie
    card.addEventListener("change", (event) => {
        let displayError = document.getElementById("card-errors");
        if(event.error){
            displayError.textContent = event.error.message;
        }else{
            displayError.textContent = "";
        }
    })

    // On gere le paiement
    cardButton.addEventListener("click", () => {
         //  les variables
         let adresse_id = document.getElementById("adresse_id").value;
         let deliveryMode = $("input[name=drone]:checked").val();

        // si l'adresse id n'est pas defini, une alerte s'affichera,
        // et la page sera diriger vers le formulaire d'adresse
        if(!adresse_id){
            alert("Désolé, une erreur s'est produite. Vous allez être redirigé vers la page d'adresse.");
            location.href = "/adresse";
            return;
        }
        
        if(!deliveryMode){
            // si le mode de livraison n'est pas selectionné donc undefined
            // alors il affichera une alerte
            // et rechargera la page afin d'effacer les données de la CB
            alert("Veuillez selectionner un mode de livraison svp");
            return;
        }

        // gerer le paiement
        stripe.handleCardPayment(
            // numero secret de la transaction + la carte contenant les infos de la CB
            clientSecret, card, {
                // donnée de la method de paiement
                payment_method_data: {
                    // detail de facturation contenant le nom du client 
                    billing_details: {name: cardHolderName.value}
                }
            }
        // resultat du gestion de paiement avec then 
        ).then((result) => {
            // s'il y a une erreur 
            if(result.error){
                // je place le message d'erreur envoyer par stripe dans la div error
                document.getElementById("errors").innerText = result.error.message;
                return;
            }

            // j'apppelle la fonction validateOrder 
            // en passant en parametre le delivryMode et l'adresse id recup précedement
            // si le traitement est un succes 
            // j'appelle la fonction removePanier sera appliquée
            // et rediriger vers la page succes 
            // si la fonction validateOrder est un echec on tolbera dans le message d'erreur
            $.ajax({
                type: "GET",
                url: "/validateOrder/"+ adresse_id + "/" + deliveryMode,
                success: function(data){
                    $.ajax({
                        type: "GET",
                        url: "/removePanier/",
                        success: function(data){
                            window.location.href = "/succes/"
                        }
                    });
                },   
                error: function(request, status, error){
                    alert("Une erreur est survenue sur la validation de votre commande !")
                }
            });
        })
    })
}