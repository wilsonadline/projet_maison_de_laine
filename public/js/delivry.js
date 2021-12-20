let selectedId = $("input[name=drone]:checked").val();

function calculateTotalTTC(){
    // debugger;
    let selectedId = $("input[name=drone]:checked").val();
    let ttcToShow = parseFloat($('#totalTtc')[0].attributes.value.value);
    let price = document.getElementById(selectedId + "-price").value;
    ttcToShow += parseFloat(price);

    if(selectedId && ttcToShow>0){
        $('#totalTtc')[0].innerText = ttcToShow + '€';
    }
    // if(!selectedId){
        // console.log("not selected");
    // }
}

// if ( selectedId == 0){
// // function myFunction() {
//         alert("I am an alert box!");
//       }
// // }
function onChangeDeliveryOption(){

    $("input[name=drone]").on('change', function(){
        calculateTotalTTC();
    });
}

$(document).ready(function(){
    onChangeDeliveryOption();
});
