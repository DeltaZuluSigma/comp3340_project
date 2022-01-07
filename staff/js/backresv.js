function changefield() {
    var change = document.getElementById("cg_rsv").options[document.getElementById("cg_rsv").selectedIndex].value;
    switch(change) {
        case 'remove':
            document.getElementById("field").disabled = true;
            break;
        case 'name':
            document.getElementById("field").disabled = false;
            document.getElementById("field").type = "text";
            document.getElementById("compfld").innerHTML = 'Customer Name:';
            break;
        case 'gsize':
            document.getElementById("field").disabled = false;
            document.getElementById("field").type = "number";
            document.getElementById("compfld").innerHTML = 'Group Size:';
            break;
        case 'date':
            document.getElementById("field").disabled = false;
            document.getElementById("field").type = "date";
            document.getElementById("compfld").innerHTML = 'Date:';
            break;
        case 'time':
            document.getElementById("field").disabled = false;
            document.getElementById("field").type = "time";
            document.getElementById("compfld").innerHTML = 'Time:';
            break;
    }
}