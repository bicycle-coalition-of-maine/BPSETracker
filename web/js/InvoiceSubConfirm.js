/* jQuery for invoice-sub/detail view */

function setSubmit() {
    var disable = !( $('#invoicesubmission-accurate').prop('checked') 
                && ($('#invoicesubmission-email').val()).length > 10
                && ($('#invoicesubmission-email').val()).indexOf('@') > 1); 
    //alert(disable);
    $('.btn-success').prop('disabled', disable);
}

// Set initial state (could be a re-display from a prior problem)
setSubmit();

// Enable submit button when checkbox checked and email filled out
$('#invoicesubmission-accurate').click(setSubmit);
$('#invoicesubmission-email').change(setSubmit);
