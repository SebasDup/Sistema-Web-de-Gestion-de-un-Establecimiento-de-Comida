
function validacion(){
    if(document.frm1.usuario.value.length==0){
        document.getElementById("usuario").focus();
        return false;
    }
    if(document.frm1.contrasena.value.length==0){
        document.getElementById("contrasena").focus();
        return false;
    }
    frm1.submit();
}