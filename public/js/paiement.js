// cindow/onload nous sert a nous assurer que notre document est réellement chargé  
window.onload = () => {
    // Variables
    let stripe = Stripe('pk_test_51JlA15DnhjURuLLqIC7kBQg2Cu3RYuEUUYmEUxtTiX4whUfNbHfazvmqbyoiBRHdb6xXDFvrWfXJD6nNHwL1FmdP009MV0I6wR')
    let elements = stripe.elements()

    //  objet de la page
    let cardHolderName = document.getElementById("cardholder-name")
    let cardButton = document.getElementById("card-button")
    let clientSecret = cardButton.dataset.secret; 
    
    // Creer les elements du formulaire de carte bancaire 
    let card = elements.create("card")
    card.mount("#card-elements")
    
    // on gere la saisie
    card.addEventListener("change", (event) => {
        let displayError = document.getElementById("card-errors")
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
                document.getElementById("errors").innerText = result.error.message
            }else{
                let adresse_id = document.getElementById("adresse_id").value;

                let deliveryMode = $("input[name=drone]:checked").val();
                console.log(deliveryMode);

                // var removing = 
                if(deliveryMode == undefined){
                    console.log('red'); 
                    alert("Veuillez selectionner un mode de livraison svp")
                    location.reload();
                }else{
                    $.ajax({
                        type: "GET",
                        url: "/validateOrder/"+ adresse_id + "/" + deliveryMode,
                        success: function(data){
                            $.ajax({
                                type: "GET",
                                url: "/endSessoin/",
                                success: function(data){
                                    window.location.href = "/dom/pdf/"+ data;
                                }
                            });
                        }
                    });
                }
            }
        })
    })

}