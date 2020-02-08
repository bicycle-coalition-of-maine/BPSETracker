//$('#request-form').on('beforeSubmit', function (e) {   // Haven't figured out how to hook in jQuery yet

var theForm = document.getElementById('w0'); // Why is form named "w0", and will it always be?
theForm.onsubmit = function() {
    var checked = 0;
    var elements =  document.getElementsByTagName('INPUT');

    // Count event type checkboxes that are checked, not including "Other"
    for (i=0; i < elements.length; ++i )
        if(elements[i].name.startsWith('eventType') && elements[i].name != 'eventType[-1]')

            if(elements[i].checked)
                ++checked;
    
    if(checked == 0) {
        alert('Please check at least one Event Type checkbox (not counting "Other").');
        return false;
    }
    else
        return true;

};
