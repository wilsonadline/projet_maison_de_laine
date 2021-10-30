// cindow/onload nous sert a nous assurer que notre document est réellement chargé  
window.onload = () => {
    // Variables
    let stripe = Stripe('pk_test_51JlA15DnhjURuLLqIC7kBQg2Cu3RYuEUUYmEUxtTiX4whUfNbHfazvmqbyoiBRHdb6xXDFvrWfXJD6nNHwL1FmdP009MV0I6wR')
    let elements = stripe.elements()
    // let redirect = "www.google.com"

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
        console.log("test"); 
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
                document.location.href = "http://localhost:8000/"
            }
        })
    })

}

// // cindow/onload nous sert a nous assurer que notre document est réellement chargé  
// window.onload = () => {
//     // Variables
//     let stripe = Stripe('pk_test_51JlA15DnhjURuLLqIC7kBQg2Cu3RYuEUUYmEUxtTiX4whUfNbHfazvmqbyoiBRHdb6xXDFvrWfXJD6nNHwL1FmdP009MV0I6wR')
//     let elements = stripe.elements()
//     // let redirect = "www.google.com"

//     //  objet de la page
//     let cardHolderName = document.getElementsById("paiement_Titulaire")
//     let cardButton = document.getElementsById("paiement_boutton")
//     let clientSecret = cardButton.dataset.secret; 
    
//     // Creer les elements du formulaire de carte bancaire 
//     let card = elements.create("card")
//     card.mount("#paiement_Carte")
    
//     // on gere la saisie
//     card.addEventListener("change", (event) => {
//         let displayError = document.getElementsById("paiement_card_errors")
//         if(event.error){
//             displayError.textContent = event.error.message;
//         }else{
//             displayError.textContent = "";
//         } 
//     })

//     // On gere le paiement
//     cardButton.addEventListener("click", () => {
//         stripe.handleCardPayment(
//             clientSecret, card, {
//                 payment_method_data: {
//                     billing_details: {name: cardHolderName.value}
//                 }
//             }
//         ).then((result) => {
//             if(result.error){
//                 document.getElementsById("paiement_errors").innerText = result.error.message
//             }else{
//                 document.location.href = "http://localhost:8000/"
//             }
//         })
//     })

// }