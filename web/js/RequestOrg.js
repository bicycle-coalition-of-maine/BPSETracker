// Javascript for populating org address fields on request/org screen.

var OrgListBox = document.getElementById("request-fkorgid");
OrgListBox.onchange = function() {
    if(this.selectedIndex == 0) {
        document.getElementById("request-orgname").value = '';
        document.getElementById("request-orgaddress").value = '';
        document.getElementById("request-orgcity").value = '';
        document.getElementById("request-orgzip").value = '';
    }
    else {
        var label = this.options[this.selectedIndex].text;
        var values = label.split('; ');
        document.getElementById("request-orgname").value = values[0];
        document.getElementById("request-orgaddress").value = values[1];
        document.getElementById("request-orgcity").value = values[2];
        document.getElementById("request-orgzip").value = values[3];
    }
};

// Pre-select the "New organization" entry
OrgListBox.selectedIndex = 0;