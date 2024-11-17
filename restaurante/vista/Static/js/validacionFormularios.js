function validarFormulario(formId) {
    const form = document.getElementById(formId);
    if (form.checkValidity() === false) {
        form.classList.add('was-validated');
    } else {
        form.submit();
    }
}

document.querySelectorAll('input').forEach(input => {
    input.addEventListener('input', () => {
        if (input.checkValidity()) {
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
        } else {
            input.classList.remove('is-valid');
            input.classList.add('is-invalid');
        }
    });
});

function actualizarZonaId() {
    document.querySelector('select[name="zona"]').addEventListener('change', function() {
        document.getElementById('zona_id').value = this.value;
    });
}
document.addEventListener('DOMContentLoaded', actualizarZonaId);

function actualizarMesaId() {
    document.querySelector('select[name="zona"]').addEventListener('change', function() {
        document.getElementById('mesa_id').value = this.value;
    });
}
document.addEventListener('DOMContentLoaded', actualizarMesaId);