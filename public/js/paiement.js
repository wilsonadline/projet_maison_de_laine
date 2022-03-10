// cindow/onload nous sert a nous assurer que notre document est réellement chargé  
window.onload = () => {
    // Variables
    // // la public key de stripe
    let stripe = Stripe('pk_test_51JlA15DnhjURuLLqIC7kBQg2Cu3RYuEUUYmEUxtTiX4whUfNbHfazvmqbyoiBRHdb6xXDFvrWfXJD6nNHwL1FmdP009MV0I6wR');
    let elements = stripe.elements();

    //  objet de la page
    let cardHolderName = document.getElementById("cardholder-name");
    let cardButton = document.getElementById("card-button");
    let clientSecret = cardButton.dataset.secret;
    
    // Creer les elements du formulaire de la carte bancaire 
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
        stripe.handleCardPayment(
            clientSecret, card, {
                payment_method_data: {
                    billing_details: {name: cardHolderName.value}
                }
            }
        ).then((result) => {
            if(result.error){
                document.getElementById("errors").innerText = result.error.message;
            }else{
                //  les variables 
                let adresse_id = document.getElementById("adresse_id").value;
                let deliveryMode = $("input[name=drone]:checked").val();

                // si le mode de livraison n'est pas selectionné donc undefined
                // alors il affichera une alerte
                // et rechargera la page afin d'effacer les inputs de la carte bancaire
                if(deliveryMode == undefined){
                    alert("Veuillez selectionner un mode de livraison svp");
                    location.reload();
                }else{
                    // si non grâce à ajax je vérifie que l'url est bien valide 
                    // au success de cet URL, je redirige la page vers la succes
                    $.ajax({
                        type: "GET",
                        url: "/validateOrder/"+ adresse_id + "/" + deliveryMode,
                        success: function(data){
                            // window.location.href = "/succes/"
                            // $.ajax({
                            //     type: "GET",
                            //     url: "/succes/",
                            //     success: function(data){
                            //         url: "/endSession/"
                            //     }
                            // });
                            // document.location.href = "/dom/pdf/"+ data;
                            $.ajax({
                                type: "GET",
                                url: "/endSessoin/",
                                success: function(data){
                                    window.location.href = "/succes/"
                                    }
                                });
                        }
                    });
                }
            }
        })
    })
}