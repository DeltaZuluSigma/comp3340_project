function chg_tbl() {
    var change = document.getElementById("table").options[document.getElementById("table").selectedIndex].value;
    switch (change) {
        case 'receipts':
            window.location = "records.php?table=receipts";
            break;
        case 'reserve':
            window.location = "records.php?table=reserve";
            break;
        case 'queue':
            window.location = "records.php?table=queue";
            break;
    }
}

function change_receipt() {
    var change = document.getElementById("chg_option").options[document.getElementById("chg_option").selectedIndex].value;
    switch (change) {
        case 'date':
            document.getElementById("cdl").innerHTML = "Date";
            document.getElementById("chg_detail").type = "date";
            document.getElementById("chg_qtty").disabled = true;
            break;
        case 'time':
            document.getElementById("cdl").innerHTML = "Time";
            document.getElementById("chg_detail").type = "time";
            document.getElementById("chg_qtty").disabled = true;
            break;
        case 'name':
            document.getElementById("cdl").innerHTML = "Customer Name";
            document.getElementById("chg_detail").type = "text";
            document.getElementById("chg_qtty").disabled = true;
            break;
        case 'table':
            document.getElementById("cdl").innerHTML = "Table #";
            document.getElementById("chg_detail").type = "number";
            document.getElementById("chg_qtty").disabled = true;
            break;
        case 'item':
            document.getElementById("cdl").innerHTML = "Item ID";
            document.getElementById("chg_detail").type = "number";
            document.getElementById("chg_qtty").disabled = false;
            break;
    }
}

function change_perm() {
    var change = document.getElementById("chg_perm").options[document.getElementById("chg_perm").selectedIndex].value;
    switch (change) {
        case 'remove':
            document.getElementById("cpl").innerHTML = "Remove Receipt by #";
            break;
        case 'update':
            document.getElementById("cpl").innerHTML = "Update State by Receipt #";
            break;
    }
}

function change_reserve() {
    var change = document.getElementById("chg_option").options[document.getElementById("chg_option").selectedIndex].value;
    switch (change) {
        case 'name':
            document.getElementById("cdl").innerHTML = "Customer Name";
            document.getElementById("chg_detail").type = "text";
            break;
        case 'size':
            document.getElementById("cdl").innerHTML = "Group Size";
            document.getElementById("chg_detail").type = "number";
            break;
        case 'date':
            document.getElementById("cdl").innerHTML = "Date";
            document.getElementById("chg_detail").type = "date";
            break;
        case 'time':
            document.getElementById("cdl").innerHTML = "Time";
            document.getElementById("chg_detail").type = "time";
            break;
    }
}

function change_queue() {
    var change = document.getElementById("chg_option").options[document.getElementById("chg_option").selectedIndex].value;
    switch (change) {
        case 'name':
            document.getElementById("cdl").innerHTML = "Customer Name";
            document.getElementById("chg_detail").type = "text";
            break;
        case 'size':
            document.getElementById("cdl").innerHTML = "Group Size";
            document.getElementById("chg_detail").type = "number";
            break;
        case 'time':
            document.getElementById("cdl").innerHTML = "Time";
            document.getElementById("chg_detail").type = "time";
            break;
    }
}