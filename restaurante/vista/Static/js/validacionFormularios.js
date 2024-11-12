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