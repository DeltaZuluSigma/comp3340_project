function changedetail() {
    var change = document.getElementById("cg_que").options[document.getElementById("cg_que").selectedIndex].value;
    switch(change) {
        case 'remove':
            document.getElementById("detail").disabled = true;
            break;
        case 'name':
            document.getElementById("detail").disabled = false;
            document.getElementById("detail").type = "text";
            document.getElementById("compdtl").innerHTML = 'Customer Name:';
            break;
        case 'gsize':
            document.getElementById("detail").disabled = false;
            document.getElementById("detail").type = "number";
            document.getElementById("compdtl").innerHTML = 'Group Size:';
            break;
    }
}