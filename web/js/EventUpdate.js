/* jQuery for event updating */

function populate() {
    if($('#event-isatorgaddress').prop('checked')) {
        var label = $('#event-fkorgid option:selected').text();
        var values = (label).split('; ');
        //alert(values[4]);
        $('#event-address1').val(values[1]);
        $('#event-city').val(values[2]);
        $('#event-state').val('ME');
        $('#event-zipcode').val(values[3]);
        
        /*
         * County not being set reliably. Tried various options from 
         * https://forum.jquery.com/topic/setting-selected-option-in-dropdownlist-by-the-text-not-value
         * but nothing seemed to work every time. Suspect maybe related to
         * options that have street address missing, and/or unselecting
         * "On Premise", but as yet am unable to diagnose a root cause.
         */ 

        $("#event-county option:contains(" + values[4] + ")").attr('selected', 'selected');

//        for(i=0; i < $('#event-county').options.length; ++i) {
//            
//        }
//        $("#event-county option").each(function () {
//                if ($(this).html() == values[4]) {
//                    $(this).attr("selected", "selected");
//                    return;
//                }
//        });    
    }
    else { // clear fields
        $('#event-address1').val('');
//        $('#event-city').val('');     // Might as well leave the same town  by default
//        $('#event-state').val('ME');
//        $('#event-zipcode').val('');
//        $('#event-county').prop('selectedIndex', 0);
    }
}

// Call populate on change of org dropdown or "On premises" checkbox

$('#event-fkorgid').change(populate);
$('#event-isatorgaddress').change(populate);
