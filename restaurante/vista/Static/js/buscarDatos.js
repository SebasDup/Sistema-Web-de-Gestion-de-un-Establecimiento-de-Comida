function normalizeText(text) {
    return text.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toUpperCase();
}

function buscarUsuario() {
    var input, filter, table, tr, td, i, txtValue;
    var found = false; // Inicializar fuera del bucle
    input = document.getElementById("buscarUsuario");
    filter = normalizeText(input.value);
    table = document.querySelector(".user-table");
    tr = table.getElementsByTagName("tr");

    for (i = 1; i < tr.length; i++) {
        tr[i].style.display = "none";
        td = tr[i].getElementsByTagName("td");
        for (var j = 0; j < td.length; j++) {
            if (td[j]) {
                txtValue = normalizeText(td[j].textContent || td[j].innerText);
                var words = filter.split(" ");
                if (words.every(word => txtValue.includes(word)) && !txtValue.includes("MODIFICAR") && !txtValue.includes("ELIMINAR")) {
                    tr[i].style.display = "";
                    found = true; // Marcar como encontrado
                    break;
                }
            }
        }
    }

    if (!found) { // Verificar fuera del bucle
        input.classList.remove('is-invalid');
        input.classList.remove('is-valid');
        input.classList.add('is-invalid');
        $_Session['error'] = "No se encontraron resultados.";
    } else {
        input.classList.remove('is-invalid');
        input.classList.add('is-valid');
        $_Session['error'] = "";
    }
}

function mostrarTodosUsuarios() {
    var table, tr, i, input;
    table = document.querySelector(".user-table");
    tr = table.getElementsByTagName("tr");

    for (i = 1; i < tr.length; i++) {
        tr[i].style.display = "";
    }

    input = document.getElementById("buscarUsuario");
    input.value = "";
    input.classList.remove('is-invalid');
    input.classList.remove('is-valid');
}