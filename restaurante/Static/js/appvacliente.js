function validacionc(){
    if(document.frm1.nombre.value.length==0){
        document.getElementById("nombre").focus();
        return false;
    }
    if(document.frm1.email.value.length==0){
        document.getElementById("email").focus();
        return false;
    }
    if(document.frm1.password.value.length==0){
        document.getElementById("contrasena").focus();
        return false;
    }
    frm1.submit();
}