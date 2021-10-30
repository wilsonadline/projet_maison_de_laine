function copyToDelivry(){
    $('#copyToDelivry').on("click", function(){
        
        $("#livraison_nom").val($("#facturation_name").val())
        $("#livraison_prenom").val($("#facturation_fstname").val())
        $("#livraison_tel").val($("#facturation_tel").val())
        $("#livraison_adresse").val($("#facturation_ad1").val())
        $("#livraison_cp").val($("#facturation_cp").val())
        $("#livraison_ville").val($("#facturation_ville").val())
    })
}
copyToDelivry()