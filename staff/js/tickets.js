function changeoption() {
    var change = document.getElementById("chg_opt").options[document.getElementById("chg_opt").selectedIndex].value;
    switch(change) {
        case 'update':
            document.getElementById("itemid").disabled = true;
            document.getElementById("quantity").disabled = true;
            break;
        case 'inc':
            document.getElementById("itemid").disabled = false;
            document.getElementById("quantity").disabled = false;
            break;
    }
}