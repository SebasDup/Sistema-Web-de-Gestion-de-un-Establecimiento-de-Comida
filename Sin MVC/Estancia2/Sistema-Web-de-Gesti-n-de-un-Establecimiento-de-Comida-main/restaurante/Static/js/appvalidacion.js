function validacion(){
    if(document.frm1.nombre.value.length==0){
        document.getElementById("nombre").focus();
        return false;
    }
    if(document.frm1.precio.value.length==0){
        document.getElementById("precio").focus();
        return false;
    }
    frm1.submit();
}