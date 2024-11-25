<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once("layouts/header.php"); 
$user = $_SESSION['usuario'];
$usuarioRol = $_SESSION['rolUsuario'];
$_SESSION['paginaActual'] = 'comandas';
if(isset($_SESSION['usuario'])) {
    if($usuarioRol == 'administrador' || $usuarioRol == 'empleado') {
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<h2 class="mt-4">Gestión de Comandas</h2>
<?php if(isset($_SESSION['mensaje']) || isset($_SESSION['error'])): ?>
        <div class="alert alert-dismissible fade show <?php echo isset($_SESSION['mensaje']) ? 'alert-success' : 'alert-danger'; ?>" role="alert">
            <?php 
            if(isset($_SESSION['mensaje'])) {
                echo htmlspecialchars($_SESSION['mensaje']);
                unset($_SESSION['mensaje']); 
            }
            if(isset($_SESSION['error'])) {
                echo htmlspecialchars($_SESSION['error']);
                unset($_SESSION['error']); 
            }
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <button class="btn btn-primary my-3 btn-Agregar" data-bs-toggle="modal" data-bs-target="#agregarComandaModal">
        <i class="fas fa-plus-circle me-2"></i>Agregar Comanda
    </button>

<!-- Modal for editing comanda -->
<div class="modal fade" id="editarComandaModal" tabindex="-1" aria-labelledby="editarComandaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editarComandaForm" action="index.php?c=comanda&m=editarComanda" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="editarComandaLabel">Editar Comanda</h5>
                    <input type="hidden" name="comanda_id" id="editarComandaId">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                <div class="mb-3">
                    <label class="form-label fw-bold">Estado de la Comanda:</label>
                    <div class="btn-group estado-comanda-group" role="group" aria-label="Estado Comanda">
                        <button type="button" class="btn btn-estado btn-estado-abierta" id="btnAbrirComanda" onclick="cambiarEstadoComanda('abierta')">
                            <i class="fas fa-door-open me-2"></i>Abrir Comanda
                        </button>
                        <button type="button" class="btn btn-estado btn-estado-cerrada" id="btnCerrarComanda" onclick="cambiarEstadoComanda('cerrada')">
                            <i class="fas fa-door-closed me-2"></i>Cerrar Comanda
                        </button>
                    </div>
                    <input type="hidden" name="estado" id="estadoComanda" value="">
                </div>

<style>
.btn-estado {
    padding: 10px 20px;
    font-weight: 500;
    transition: all 0.3s ease;
    border: none;
    min-width: 150px;
}

.btn-estado-abierta {
    background-color: #28a745;
    color: white;
}

.btn-estado-abierta:hover {
    background-color: #218838;
    color: white;
    transform: translateY(-2px);
}

.btn-estado-cerrada {
    background-color: #dc3545;
    color: white;
}

.btn-estado-cerrada:hover {
    background-color: #c82333;
    color: white;
    transform: translateY(-2px);
}

.btn-estado.active {
    box-shadow: 0 0 0 0.2rem rgba(0,0,0,0.2);
    transform: translateY(1px);
}

.estado-comanda-group {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    border-radius: 4px;
    gap: 10px;
}
</style>

<script>
function cambiarEstadoComanda(estado) {
    // Remove active class from all buttons
    document.querySelectorAll('.btn-estado').forEach(btn => {
        btn.classList.remove('active');
    });

    // Add active class to clicked button
    if (estado === 'abierta') {
        document.getElementById('btnAbrirComanda').classList.add('active');
    } else {
        document.getElementById('btnCerrarComanda').classList.add('active');
    }

    // Set the hidden input value
    document.getElementById('estadoComanda').value = estado;

    // Visual feedback
    const toast = document.createElement('div');
    toast.className = 'alert alert-success position-fixed top-0 end-0 m-3';
    toast.style.zIndex = '1050';
    toast.innerHTML = `
        <i class="fas fa-check-circle me-2"></i>
        Estado cambiado a: ${estado.charAt(0).toUpperCase() + estado.slice(1)}
        <button type="button" class="btn-close float-end" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(toast);

    // Remove toast after 3 seconds
    setTimeout(() => {
        toast.remove();
    }, 3000);
}
</script>

                    <div class="mb-3">
                        <label>Cliente</label>
                        <input type="text" id="editarBuscarCliente" class="form-control" placeholder="Ingrese el nombre del cliente" required>
                        <ul id="editarListaClientes" class="list-group mt-2"></ul>
                        <input type="hidden" name="cliente_id" id="editarClienteId">
                        <div class="invalid-feedback">Por favor, seleccione un cliente válido.</div>
                    </div>

                    <div class="mb-3">
                        <label>Platillos <span class="text-danger">*</span></label>
                        <input type="text" id="editarBuscarPlatillo" class="form-control" placeholder="Ingrese el nombre del platillo" required>
                        <ul id="editarListaPlatillos" class="list-group mt-2"></ul>
                        <div id="editarPlatillosSeleccionados" class="mt-3"></div>
                        <input type="hidden" name="platillos" id="editarPlatillos" value="">
                        <input type="hidden" name="precios" id="editarPrecios" value="">
                        <input type="hidden" name="cantidades" id="editarCantidades" value="">
                        <div class="invalid-feedback" id="editarPlatillosFeedback">Por favor, seleccione al menos un platillo.</div>
                    </div>
                    <script>
                        // Add event listener for form submission
                        document.getElementById('editarComandaForm').addEventListener('submit', function(e) {
                            const platillos = document.getElementById('editarPlatillos').value;
                            const platilloInput = document.getElementById('editarBuscarPlatillo');
                            const feedback = document.getElementById('editarPlatillosFeedback');
                            
                            if (!platillos) {
                                e.preventDefault();
                                platilloInput.classList.add('is-invalid');
                                feedback.style.display = 'block';
                            } else {
                                platilloInput.classList.remove('is-invalid');
                                feedback.style.display = 'none';
                            }
                        });
                    </script>

                    <script>
                    function validarPlatillosEditar() {
                        const platillos = document.getElementById('editarPlatillos').value;
                        const platilloInput = document.getElementById('editarBuscarPlatillo');
                        const feedback = document.getElementById('editarPlatillosFeedback');
                        
                        if (!platillos) {
                            platilloInput.classList.add('is-invalid');
                            feedback.style.display = 'block';
                            return false;
                        } else {
                            platilloInput.classList.remove('is-invalid');
                            feedback.style.display = 'none';
                            return true;
                        }
                    }

                    document.getElementById('editarComandaForm').addEventListener('submit', function(event) {
                        if (!validarPlatillosEditar()) {
                            event.preventDefault();
                        }
                    });
                    </script>

                    <script>
                    function validarPlatillos() {
                        const platillos = document.getElementById('editarPlatillos').value;
                        const feedback = document.getElementById('editarPlatillosFeedback');
                        const platilloInput = document.getElementById('editarBuscarPlatillo');
                        
                        if (!platillos) {
                            feedback.style.display = 'block';
                            platilloInput.classList.add('is-invalid');
                            return false;
                        } else {
                            feedback.style.display = 'none';
                            platilloInput.classList.remove('is-invalid');
                            return true;
                        }
                    }

                    document.getElementById('editarComandaForm').addEventListener('submit', function(event) {
                        if (!validarPlatillos()) {
                            event.preventDefault();
                        }
                    });
                    </script>

                    <script>
                    document.getElementById('editarComandaForm').addEventListener('submit', function(event) {
                        const platillos = document.getElementById('editarPlatillos').value;
                        const feedback = document.getElementById('editarPlatillosFeedback');
                        if (!platillos) {
                            feedback.style.display = 'block';
                            event.preventDefault();
                        } else {
                            feedback.style.display = 'none';
                        }
                    });
                    </script>

                    <script>
                    function agregarPlatilloEditar(platillo) {
                        const div = document.createElement('div');
                        div.className = 'alert alert-secondary d-flex justify-content-between align-items-center';
                        
                        const infoContainer = document.createElement('div');
                        infoContainer.className = 'd-flex align-items-center';
                        
                        const cantidadInput = document.createElement('input');
                        cantidadInput.type = 'number';
                        cantidadInput.className = 'form-control me-2';
                        cantidadInput.style.width = '70px';
                        cantidadInput.value = 1;
                        cantidadInput.min = 1;
                        cantidadInput.dataset.platilloId = platillo.id;
                        cantidadInput.onchange = function() { actualizarPlatillosEditar(); };
                        
                        const textContent = document.createTextNode(platillo.nombre + ' - $' + platillo.precio);
                        
                        infoContainer.appendChild(cantidadInput);
                        infoContainer.appendChild(textContent);
                        div.appendChild(infoContainer);
                        
                        div.dataset.id = platillo.id;
                        div.dataset.precio = platillo.precio;
                        
                        const btnEliminar = document.createElement('button');
                        btnEliminar.className = 'btn btn-danger btn-sm ms-2';
                        btnEliminar.textContent = 'Eliminar';
                        btnEliminar.onclick = function() {
                            div.remove();
                            actualizarPlatillosEditar();
                        };

                        div.appendChild(btnEliminar);
                        document.getElementById('editarPlatillosSeleccionados').appendChild(div);
                        actualizarPlatillosEditar();
                    }

                    function actualizarPlatillosEditar() {
                        const platillosSeleccionados = document.getElementById('editarPlatillosSeleccionados').children;
                        const platillos = [];
                        const cantidades = [];
                        const precios = []; 
                        let total = 0;
                        
                        for (let i = 0; i < platillosSeleccionados.length; i++) {
                            const cantidad = platillosSeleccionados[i].querySelector('input[type="number"]').value;
                            const precio = platillosSeleccionados[i].dataset.precio;
                            platillos.push(platillosSeleccionados[i].dataset.id);
                            cantidades.push(cantidad);
                            precios.push(precio); 
                            total += parseFloat(precio) * parseInt(cantidad);
                        }
                        
                        document.getElementById('editarPlatillos').value = platillos.join(',');
                        document.getElementById('editarCantidades').value = cantidades.join(',');
                        document.getElementById('editarPrecios').value = precios.join(','); 
                        document.getElementById('editarTotalComanda').textContent = '$' + total.toFixed(2);
                        document.getElementById('editarTotalInput').value = total.toFixed(2);
                    }
                    </script>
                    <div class="mb-3">
                        <label>Total:</label>
                        <p id="editarTotalComanda">$0.00</p>
                        <input type="hidden" name="total" id="editarTotalInput" value="0.00">
                    </div>

                    <script>
                        document.getElementById('editarBuscarPlatillo').addEventListener('input', function() {
                            const query = this.value.toLowerCase();
                            const platillos = <?php echo json_encode($platillos); ?>;
                            const listaPlatillos = document.getElementById('editarListaPlatillos');
                            listaPlatillos.innerHTML = '';

                            platillos.forEach(platillo => {
                                const nombrePlatillo = platillo.nombre.toLowerCase();
                                if (nombrePlatillo.includes(query)) {
                                    const li = document.createElement('li');
                                    li.className = 'list-group-item';
                                    li.textContent = platillo.nombre;
                                    li.dataset.id = platillo.id;
                                    li.dataset.precio = platillo.precio;
                                    li.addEventListener('click', function() {
                                        agregarPlatilloEditar(platillo);
                                        listaPlatillos.innerHTML = '';
                                        document.getElementById('editarBuscarPlatillo').value = '\u200B'; // Zero-width space character
                                    });
                                    listaPlatillos.appendChild(li);
                                }
                            });
                        });
                    </script>

                    <div class="mb-3">
                        <label>Mesa</label>
                        <select name="id_mesa" class="form-control" required onchange="actualizarMesaIdEditar(this)">
                            <option value="">Seleccionar Mesa</option>
                            <?php foreach ($mesas as $mesa): ?>
                                <option value="<?= $mesa['id']; ?>">Mesa <?= $mesa['numero']; ?> (capacidad: <?= $mesa['capacidad']; ?> personas)</option>
                            <?php endforeach; ?>
                        </select>
                        <input type="hidden" name="mesa_id" id="editarMesaId">
                        <div class="invalid-feedback">Por favor, seleccione una mesa.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="editarComentarios">Comentarios Adicionales:</label>
                        <textarea id="editarComentarios" name="comentarios" rows="4" class="form-control" placeholder="Ingrese cualquier comentario adicional aquí..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="validarFormularioEditar()">Guardar cambios</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Define global variables for use across all functions
    const usuarios = <?php echo json_encode($usuarios); ?>;
    const platillos = <?php echo json_encode($platillos); ?>;
    const platillos_comanda = <?php echo json_encode($platillos_comanda); ?>;

    document.getElementById('editarBuscarCliente').addEventListener('input', function() {
        const query = this.value.toLowerCase();
        const listaClientes = document.getElementById('editarListaClientes');
        listaClientes.innerHTML = '';

        clientes.forEach(cliente => {
            const nombreCompleto = `${cliente.nombre} ${cliente.apellidoP} ${cliente.apellidoM}`.toLowerCase();
            if (nombreCompleto.includes(query)) {
                const li = document.createElement('li');
                li.className = 'list-group-item';
                li.textContent = nombreCompleto;
                li.dataset.id = cliente.id;
                li.addEventListener('click', function() {
                    document.getElementById('editarBuscarCliente').value = nombreCompleto;
                    document.getElementById('editarClienteId').value = cliente.id;
                    listaClientes.innerHTML = '';
                });
                listaClientes.appendChild(li);
            }
        });
    });

    document.getElementById('editarComandaModal').addEventListener('hidden.bs.modal', function () {
        const form = document.getElementById('editarComandaForm');
        form.reset();
        const invalidFeedbacks = form.querySelectorAll('.invalid-feedback');
        invalidFeedbacks.forEach(feedback => feedback.style.display = 'none');
    });

    function editarComanda(comanda) {
        const editarModal = new bootstrap.Modal(document.getElementById('editarComandaModal'));
        comanda = typeof comanda === 'string' ? JSON.parse(comanda) : comanda;
        document.getElementById('editarComandaForm').action = `index.php?c=comanda&m=editarComanda`;
        document.getElementById('editarComandaId').value = comanda.id;
        document.getElementById('editarBuscarCliente').value = comanda.cliente_nombre;
        document.getElementById('editarClienteId').value = comanda.cliente_id;
        const mesaSelect = document.querySelector('#editarComandaModal select[name="id_mesa"]');
        mesaSelect.value = comanda.mesa_id;
        document.getElementById('editarMesaId').value = comanda.mesa_id;
        document.getElementById('editarComentarios').value = comanda.comentarios;
        document.getElementById('editarPlatillosSeleccionados').innerHTML = '';
        if (comanda.platillos && Array.isArray(comanda.platillos)) {
            comanda.platillos.forEach(platillo => {
                agregarPlatilloEditar({
                    id: platillo.menu_id,
                    nombre: platillo.nombre,
                    precio: platillo.precio,
                    cantidad: platillo.cantidad
                });
            });
        }
        actualizarPlatillosEditar();
        editarModal.show();
    }
</script>

<!-- Modal for confirming comanda deletion -->
<div class="modal fade" id="confirmarComandaModal" tabindex="-1" aria-labelledby="confirmarComandaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmarComandaLabel">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Está seguro de que desea eliminar esta comanda?
                <input type="hidden" id="comandaIdEliminar">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" onclick="confirmarEliminarComanda()">Eliminar</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal for adding comanda -->
<div class="modal fade" id="agregarComandaModal" tabindex="-1" aria-labelledby="agregarComandaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="nuevaComandaForm" action="index.php?c=comanda&m=guardarComanda" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="agregarComandaLabel">Nueva Orden</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Cliente</label>
                        <input type="text" id="buscarCliente" class="form-control" placeholder="Ingrese el nombre del cliente" required>
                        <ul id="listaClientes" class="list-group mt-2"></ul>
                        <input type="hidden" name="cliente_id" id="cliente_id">
                        <div class="invalid-feedback">Por favor, seleccione un cliente válido.</div>
                    </div>

                    <div class="mb-3">
                        <label>Platillos</label>
                        <input type="text" id="buscarPlatillo" class="form-control" placeholder="Ingrese el nombre del platillo" required>
                        <ul id="listaPlatillos" class="list-group mt-2"></ul>
                        <div id="platillosSeleccionados" class="mt-3"></div>
                        <input type="hidden" name="platillos" id="platillos" value="">
                        <input type="hidden" name="precios" id="precios" value="" >
                        <input type="hidden" name="cantidades" id="cantidades" value="" >
                        <div class="invalid-feedback" id="platillosFeedback">Por favor, seleccione al menos un platillo.</div>
                    </div>

                    <script>
                    document.getElementById('nuevaComandaForm').addEventListener('submit', function(event) {
                        const platillos = document.getElementById('platillos').value;
                        const platilloInput = document.getElementById('buscarPlatillo');
                        const feedback = document.getElementById('platillosFeedback');
                        
                        if (!platillos) {
                            event.preventDefault();
                            platilloInput.classList.add('is-invalid');
                            feedback.style.display = 'block';
                        } else {
                            platilloInput.classList.remove('is-invalid');
                            feedback.style.display = 'none';
                        }
                    });
                    
                    function agregarPlatillo(platillo) {
                        const div = document.createElement('div');
                        div.className = 'alert alert-secondary d-flex justify-content-between align-items-center';
                        
                        const infoContainer = document.createElement('div');
                        infoContainer.className = 'd-flex align-items-center';
                        
                        const cantidadInput = document.createElement('input');
                        cantidadInput.type = 'number';
                        cantidadInput.className = 'form-control me-2';
                        cantidadInput.style.width = '70px';
                        cantidadInput.value = 1;
                        cantidadInput.min = 1;
                        cantidadInput.dataset.platilloId = platillo.id;
                        cantidadInput.onchange = function() { actualizarPlatillos(); };
                        
                        const textContent = document.createTextNode(platillo.nombre + ' - $' + platillo.precio);
                        
                        infoContainer.appendChild(cantidadInput);
                        infoContainer.appendChild(textContent);
                        div.appendChild(infoContainer);
                        
                        div.dataset.id = platillo.id;
                        div.dataset.precio = platillo.precio;
                        
                        const btnEliminar = document.createElement('button');
                        btnEliminar.className = 'btn btn-danger btn-sm ms-2';
                        btnEliminar.textContent = 'Eliminar';
                        btnEliminar.onclick = function() {
                            div.remove();
                            actualizarPlatillos();
                        };
                    
                        div.appendChild(btnEliminar);
                        document.getElementById('platillosSeleccionados').appendChild(div);
                        actualizarPlatillos();
                        validarPlatillos(); // Llamar a la función de validación después de agregar un platillo
                    }
                    
                    function actualizarPlatillos() {
                        const platillosSeleccionados = document.getElementById('platillosSeleccionados').children;
                        const platillos = [];
                        const cantidades = [];
                        const precios = []; 
                        let total = 0;
                        
                        for (let i = 0; i < platillosSeleccionados.length; i++) {
                            const cantidad = platillosSeleccionados[i].querySelector('input[type="number"]').value;
                            const precio = platillosSeleccionados[i].dataset.precio;
                            platillos.push(platillosSeleccionados[i].dataset.id);
                            cantidades.push(cantidad);
                            precios.push(precio); 
                            total += parseFloat(precio) * parseInt(cantidad);
                        }
                        
                        document.getElementById('platillos').value = platillos.join(',');
                        document.getElementById('cantidades').value = cantidades.join(',');
                        document.getElementById('precios').value = precios.join(','); 
                        document.getElementById('totalComanda').textContent = '$' + total.toFixed(2);
                        document.getElementById('totalInput').value = total.toFixed(2);

                        
                        validarPlatillos();
                    }

                    function validarPlatillos() {
                        const platillos = document.getElementById('platillos').value;
                        const feedback = document.getElementById('platillosFeedback');
                        const platilloInput = document.getElementById('buscarPlatillo');
                        
                        if (!platillos) {
                            feedback.style.display = 'block';
                            platilloInput.classList.add('is-invalid');
                            return false;
                        } else {
                            feedback.style.display = 'none';
                            platilloInput.classList.remove('is-invalid');
                            return true;
                        }
                    }

                    document.getElementById('nuevaComandaForm').addEventListener('submit', function(event) {
                        if (!validarPlatillos()) {
                            event.preventDefault();
                        }
                    });
                    </script>

                    <script>
                    function agregarPlatillo(platillo) {
                    const div = document.createElement('div');
                    div.className = 'alert alert-secondary d-flex justify-content-between align-items-center';
                    
                    const infoContainer = document.createElement('div');
                    infoContainer.className = 'd-flex align-items-center';
                    
                    const cantidadInput = document.createElement('input');
                    cantidadInput.type = 'number';
                    cantidadInput.className = 'form-control me-2';
                    cantidadInput.style.width = '70px';
                    cantidadInput.value = 1;
                    cantidadInput.min = 1;
                    cantidadInput.dataset.platilloId = platillo.id;
                    cantidadInput.onchange = function() { actualizarPlatillos(); };
                    
                    const textContent = document.createTextNode(platillo.nombre + ' - $' + platillo.precio);
                    
                    infoContainer.appendChild(cantidadInput);
                    infoContainer.appendChild(textContent);
                    div.appendChild(infoContainer);
                    
                    div.dataset.id = platillo.id;
                    div.dataset.precio = platillo.precio;
                    
                    const btnEliminar = document.createElement('button');
                    btnEliminar.className = 'btn btn-danger btn-sm ms-2';
                    btnEliminar.textContent = 'Eliminar';
                    btnEliminar.onclick = function() {
                        div.remove();
                        actualizarPlatillos();
                    };

                    div.appendChild(btnEliminar);
                    document.getElementById('platillosSeleccionados').appendChild(div);
                    actualizarPlatillos();
                    validarPlatillos(); // Llamar a la función de validación después de agregar un platillo
                }

                function actualizarPlatillos() {
                    const platillosSeleccionados = document.getElementById('platillosSeleccionados').children;
                    const platillos = [];
                    const cantidades = [];
                    const precios = []; 
                    let total = 0;
                    
                    for (let i = 0; i < platillosSeleccionados.length; i++) {
                        const cantidad = platillosSeleccionados[i].querySelector('input[type="number"]').value;
                        const precio = platillosSeleccionados[i].dataset.precio;
                        platillos.push(platillosSeleccionados[i].dataset.id);
                        cantidades.push(cantidad);
                        precios.push(precio); 
                        total += parseFloat(precio) * parseInt(cantidad);
                    }
                    
                    document.getElementById('platillos').value = platillos.join(',');
                    document.getElementById('cantidades').value = cantidades.join(',');
                    document.getElementById('precios').value = precios.join(','); 
                    document.getElementById('totalComanda').textContent = '$' + total.toFixed(2);
                    document.getElementById('totalInput').value = total.toFixed(2);
                }

                function validarPlatillos() {
                    const platillos = document.getElementById('platillos').value;
                    const feedback = document.getElementById('platillosFeedback');
                    const platilloInput = document.getElementById('buscarPlatillo');
                    
                    if (!platillos) {
                        feedback.style.display = 'block';
                        platilloInput.classList.add('is-invalid');
                        return false;
                    } else {
                        feedback.style.display = 'none';
                        platilloInput.classList.remove('is-invalid');
                        return true;
                    }
                }

                document.getElementById('nuevaComandaForm').addEventListener('submit', function(event) {
                    if (!validarPlatillos()) {
                        event.preventDefault();
                    }
                });
                    </script>
                    <div class="mb-3">
                        <label>Total:</label>
                        <p id="totalComanda">$0.00</p>
                        <input type="hidden" name="total" id="totalInput" value="0.00">
                    </div>

                    <script>
                        document.getElementById('buscarPlatillo').addEventListener('input', function() {
                            const query = this.value.toLowerCase();
                            const platillos = <?php echo json_encode($platillos); ?>;
                            const listaPlatillos = document.getElementById('listaPlatillos');
                            listaPlatillos.innerHTML = '';

                            platillos.forEach(platillo => {
                                const nombrePlatillo = platillo.nombre.toLowerCase();
                                if (nombrePlatillo.includes(query)) {
                                    const li = document.createElement('li');
                                    li.className = 'list-group-item';
                                    li.textContent = platillo.nombre;
                                    li.dataset.id = platillo.id;
                                    li.dataset.precio = platillo.precio;
                                    li.addEventListener('click', function() {
                                        agregarPlatillo(platillo);
                                        listaPlatillos.innerHTML = '';
                                        document.getElementById('buscarPlatillo').value = '\u200B'; // Zero-width space character
                                    });
                                    listaPlatillos.appendChild(li);
                                }
                            });
                        });
                    </script>

                    <div class="mb-3">
                        <label>Mesa</label>
                        <select name="id_mesa" class="form-control" required onchange="actualizarMesaId(this)">
                            <option value="">Seleccionar Mesa</option>
                            <?php foreach ($mesas as $mesa): ?>
                                <option value="<?= $mesa['id']; ?>">Mesa <?= $mesa['numero']; ?> (capacidad: <?= $mesa['capacidad']; ?> personas)</option>
                            <?php endforeach; ?>
                        </select>
                        <input type="hidden" name="mesa_id" id="mesa_id">
                        <div class="invalid-feedback">Por favor, seleccione una mesa.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="comentarios">Comentarios Adicionales:</label>
                        <textarea id="comentarios" name="comentarios" rows="4" class="form-control" placeholder="Ingrese cualquier comentario adicional aquí..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-Agregar btn btn-primary" onclick="validarFormulario('nuevaComandaForm')">Guardar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('buscarCliente').addEventListener('input', function() {
        const query = this.value.toLowerCase();
        const clientes = <?php echo json_encode($usuarios); ?>;
        const listaClientes = document.getElementById('listaClientes');
        listaClientes.innerHTML = '';

        clientes.forEach(cliente => {
            const nombreCompleto = `${cliente.nombre} ${cliente.apellidoP} ${cliente.apellidoM}`.toLowerCase();
            const clienteInfo = `${cliente.id} - ${nombreCompleto} - ${cliente.email}`;
            if (nombreCompleto.includes(query)) {
                const li = document.createElement('li');
                li.className = 'list-group-item';
                li.innerHTML = `<strong>${nombreCompleto}</strong> (ID: ${cliente.id}, Email: ${cliente.email})`;
                li.dataset.id = cliente.id;
                li.addEventListener('click', function() {
                    document.getElementById('buscarCliente').value = `${nombreCompleto} (ID: ${cliente.id}, Email: ${cliente.email})`;
                    document.getElementById('cliente_id').value = cliente.id;
                    listaClientes.innerHTML = '';
                });
                listaClientes.appendChild(li);
            }
        });
    });

    document.getElementById('agregarComandaModal').addEventListener('hidden.bs.modal', function () {
        const form = document.getElementById('nuevaComandaForm');
        form.reset();
        const invalidFeedbacks = form.querySelectorAll('.invalid-feedback');
        invalidFeedbacks.forEach(feedback => feedback.style.display = 'none');
    });

    function confirmarEliminarComanda() {
        const comandaId = document.getElementById('comandaIdEliminar').value;
        if (!comandaId) {
            alert('No se ha seleccionado ninguna comanda para eliminar.');
            return;
        }

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'index.php?c=comanda&m=eliminarComanda';
        
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'comanda_id';
        input.value = comandaId;
        
        form.appendChild(input);
        document.body.appendChild(form);
        
        form.submit();
    }

    function eliminarComanda(id) {
        document.getElementById('comandaIdEliminar').value = id;
        const confirmarModal = new bootstrap.Modal(document.getElementById('confirmarComandaModal'));
        confirmarModal.show();
    }

    function editarComanda(comanda) {
            const editarModal = new bootstrap.Modal(document.getElementById('editarComandaModal'));
            comanda = typeof comanda === 'string' ? JSON.parse(comanda) : comanda;
            
            document.getElementById('editarComandaId').value = comanda.id;
            
            const cliente = usuarios.find(u => u.id === comanda.cliente_id);
            const clienteNombre = cliente ? `${cliente.nombre} ${cliente.apellidoP} ${cliente.apellidoM}` : '';
            document.getElementById('editarBuscarCliente').value = clienteNombre;
            document.getElementById('editarClienteId').value = comanda.cliente_id;
            
            const mesaSelect = document.querySelector('#editarComandaModal select[name="id_mesa"]');
            mesaSelect.value = comanda.mesa_id;
            document.getElementById('editarMesaId').value = comanda.mesa_id;
            
            document.getElementById('editarComentarios').value = comanda.comentarios || '';
            
            document.getElementById('editarPlatillosSeleccionados').innerHTML = '';
            
            const platillosDeComanda = platillos_comanda.filter(pc => pc.comanda_id === comanda.id);
            
            platillosDeComanda.forEach(pc => {
                const platillo = platillos.find(p => p.id === pc.menu_id);
                if (platillo) {
                    agregarPlatilloEditar({
                        id: platillo.id,
                        nombre: platillo.nombre,
                        precio: platillo.precio,
                        cantidad: pc.cantidad
                    });
    
                    const lastAddedItem = document.getElementById('editarPlatillosSeleccionados').lastChild;
                    if (lastAddedItem) {
                        const cantidadInput = lastAddedItem.querySelector('input[type="number"]');
                        if (cantidadInput) {
                            cantidadInput.value = pc.cantidad;
                        }
                    }
                }
            });

        actualizarPlatillosEditar();
        
        editarModal.show();
    }
</script>

<h3 class="text-center">Lista de Comandas</h3>
<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Cliente</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Mesa</th>
                <th>Platillos</th>
                <th>Total</th>
                <th>Comentarios</th>
                <th>Estado</th>
                <?php if($_SESSION['rolUsuario'] == 'administrador'): ?>
                    <th>Empleado a cargo</th>
                <?php endif; ?>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($comandas as $comanda): ?>
                <?php if($_SESSION['rolUsuario'] == 'administrador' || ($_SESSION['rolUsuario'] == 'empleado' && $comanda['empleado_id'] == $_SESSION['usuario_id'])): ?>
                    <tr>
                        <?php foreach ($usuarios as $usuario): ?>
                            <?php if ($usuario['id'] == $comanda['cliente_id']): ?>
                                <td><?= htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellidoP'] . ' ' . $usuario['apellidoM']); ?></td>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <td><?= htmlspecialchars(date('d/M/Y', strtotime($comanda['fecha']))); ?></td>
                        <td><?= htmlspecialchars(date('H:i', strtotime($comanda['fecha']))); ?></td>
                        <?php foreach ($mesas as $mesa): ?>
                            <?php if ($mesa['id'] == $comanda['mesa_id']): ?>
                                <td><?= htmlspecialchars('Mesa ' . $mesa['numero']); ?></td>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <td>
                            <?php
                            $platillosComanda = [];
                            foreach ($platillos_comanda as $pc):
                                if ($pc['comanda_id'] == $comanda['id']):
                                    foreach ($platillos as $p):
                                        if ($p['id'] == $pc['menu_id']):
                                            $platillosComanda[] = $p['nombre'] . ' (x' . $pc['cantidad'] . ')';
                                        endif;
                                    endforeach;
                                endif;
                            endforeach;
                            echo htmlspecialchars(implode(', ', $platillosComanda));
                            ?>
                        </td>
                        <td>$<?= htmlspecialchars(number_format($comanda['total'], 2)); ?></td>
                        <td><?= !empty($comanda['comentarios']) ? htmlspecialchars($comanda['comentarios']) : 'Sin comentarios'; ?></td>
                        <td><?= htmlspecialchars($comanda['estado']); ?></td>
                        <?php
                        if($_SESSION['rolUsuario'] == 'administrador'):
                            foreach ($usuarios as $usuario):
                                if ($usuario['id'] == $comanda['empleado_id']):
                                    echo '<td>' . htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellidoP'] . ' ' . $usuario['apellidoM']) . '</td>';
                                endif;
                            endforeach;
                        endif;
                        ?>
                        <td>
                            <button class="btn btn-sm btn-outline-primary" onclick="editarComanda(<?= htmlspecialchars(json_encode($comanda)); ?>)">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" onclick="eliminarComanda(<?= $comanda['id']; ?>)">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<style>
    .table th, .table td {
        text-align: center;
        vertical-align: middle;
    }
    .btn-Agregar {
        background-color: #007bff;
        color: white;
    }
    .btn-Agregar:hover {
        background-color: #0056b3;
    }
</style>

<script>
function validarFormularioEditar() {
    const platillos = document.getElementById('editarPlatillos').value;
    const feedback = document.getElementById('editarPlatillosFeedback');
    if (!platillos) {
        feedback.style.display = 'block';
    } else {
        feedback.style.display = 'none';
        document.getElementById('editarComandaForm').submit();
    }
}
</script>
<script src="vista/Static/js/validacionFormularios.js"></script>
<script src="vista/Static/js/buscarDatos.js"></script>
<?php 
    } 
    require_once("layouts/footer.php"); 
} else {
    header("Location: logout.php");
}
?>
