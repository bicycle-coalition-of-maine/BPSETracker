/* jQuery for invoice-sub/detail view */

function isNumeric(keyCode) {
    //alert(keyCode);
    return (   keyCode == 8   // backspace
            || keyCode == 9   // tab
            || keyCode == 37  // left arrow
            || keyCode == 39  // right arrow
            || keyCode == 46  // del
            || (keyCode >= 48 && keyCode <= 57 )    // top row digits
            || (keyCode >= 96 && keyCode <= 105 )   // numeric keypad digits
            || keyCode == 110 // numeric keypad decimal point
            || keyCode == 190 // standard decimal point (a.k.a. period)
           );    
}

function formatDollars(n) {
    var retVal = String(n.toFixed(2));
    if(retVal.indexOf('.') == -1)
        retVal += '.';
    retVal = '$' + retVal + "0".repeat(3 - (retVal.length - retVal.indexOf('.')));
    return retVal;
}

function recalc() {
//    var rate = $('#invoicesubmission-rate option:selected').text();
//    alert(rate);
      var rate = String(($('#invoicesubmission-fkrateid option:selected').text()).match(/\$[\d\.]+/))
              .substring(1);
//    alert(rate);
    var comp = ($('#invoicesubmission-hours').val() * rate) || 0;
    var mileage_due = ($('#invoicesubmission-miles').val() * $('#mileage').val()) || 0;
    $('#compensation').val(formatDollars(comp));
    $('#mileage-due').val(formatDollars(mileage_due));
    $('#invoice-total').val(formatDollars(comp + mileage_due));
}

// Restrict keystrokes to digits for presentations, participants, hours, miles

$('#invoicesubmission-hours').keydown(function(e) {
    return isNumeric(e.keyCode);
});
$('#invoicesubmission-miles').keydown(function(e) {
    return isNumeric(e.keyCode);
});
$('#invoicesubmission-presentations').keydown(function(e) {
    return isNumeric(e.keyCode);
});
$('#invoicesubmission-participants').keydown(function(e) {
    return isNumeric(e.keyCode);
});

// Recalculate on any relevant nput change

$('#invoicesubmission-hours').blur(function() { recalc(); });

$('#invoicesubmission-fkrateid').change(function() { recalc(); });

$('#invoicesubmission-miles').blur(function() { recalc(); });

