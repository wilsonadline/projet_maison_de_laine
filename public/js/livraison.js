function copyToDelivry(){
    $('#copyToDelivry').on("click", function(){
        $("#livraison_name").val($("#facturation_name").val())
        $("#livraison_fstname").val($("#facturation_fstname").val())
        $("#livraison_ad1").val($("#facturation_ad1").val())
        $("#livraison_ad2").val($("#facturation_ad2").val())
        $("#livraison_ad3").val($("#facturation_ad3").val())
        $("#livraison_cp").val($("#facturation_cp").val())
        $("#livraison_ville").val($("#facturation_ville").val())
    })
}
copyToDelivry()