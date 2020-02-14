// Javascript for populating org address fields on request/org screen.

var OrgListBox = document.getElementById("request-fkorgid");

/* To set pointers to the First Request Yes/No buttons, we assume that Yii
 * generated them as a "request-neworg" DIV containing two labels, with
 * an individual radio button inside each label. Further assume that "Yes"
 * is the first label/button pair (div.children[0]). We do it this way because
 * Yii doesn't give them their own ID's, and I don't know if there's a way to
 * do it in the options parameter of $form->field() or not. I didn't want to
 * use document.getElementByName because it's deprecated in HTML 5.
 */

var newOrgDiv = document.getElementById("request-neworg");
var newOrgTrue = newOrgDiv.children[0].children[0];
var newOrgFalse= newOrgDiv.children[1].children[0];

OrgListBox.onchange = function() {
    
    // Set "First Request" radio group to 'No'
    newOrgFalse.checked = true;
    
    // Populate the text fields
    var label = this.options[this.selectedIndex].text;
    var values = label.split('; ');
    document.getElementById("request-orgname").value = values[0];
    document.getElementById("request-orgaddress").value = values[1];
    document.getElementById("request-orgcity").value = values[2];
    document.getElementById("request-orgzip").value = values[3];
};

newOrgTrue.onchange = function() {
    if(this.value == 1) { // If Yes, clear the text fields
        document.getElementById("request-orgname").value = '';
        document.getElementById("request-orgaddress").value = '';
        document.getElementById("request-orgcity").value = '';
        document.getElementById("request-orgzip").value = '';
        document.getElementById("request-title").value = '';
        OrgListBox.selectedIndex = -1; // Clear selection from org list
    }
};

newOrgFalse.onchange = function() {
    //alert(this.value);
    if(this.value == 0) {
        if(OrgListBox.selectedIndex == -1)
            OrgListBox.selectedIndex = 0;        
    }
};