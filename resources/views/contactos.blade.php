<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda de Teléfono</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div class="container mt-5">
        <h1>Agenda de Teléfono</h1>

        <!-- Formulario -->
        <form id="contactoForm" class="mb-4">
            <div class="mb-3">
                <input type="text" class="form-control" id="nombre" placeholder="Nombre" required>
            </div>
            <div class="mb-3">
                <input type="number" class="form-control" id="telefono" placeholder="Teléfono" required>
            </div>
            <div class="mb-3">
                <input type="date" class="form-control" id="fecha_nacimiento" required>
            </div>
            <button type="submit" class="btn btn-primary">Agregar Contacto</button>
        </form>

        <!-- Tabla -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>Fecha de Nacimiento</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="contactosTable"></tbody>
        </table>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        // Configurar el token CSRF para Axios
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Cargar contactos al iniciar
        function loadContactos() {
            axios.get('/contactos')
                .then(response => {
                    const contactos = response.data;
                    const tableBody = document.getElementById('contactosTable');
                    tableBody.innerHTML = '';
                    contactos.forEach(contacto => {
                        tableBody.innerHTML += `
                            <tr>
                                <td>${contacto.id}</td>
                                <td>${contacto.nombre}</td>
                                <td>${contacto.telefono}</td>
                                <td>${contacto.fecha_nacimiento}</td>
                                <td>
                                    <button class="btn btn-danger btn-sm" onclick="deleteContacto(${contacto.id})">Eliminar</button>
                                </td>
                            </tr>
                        `;
                    });
                })
                .catch(error => console.error('Error al cargar contactos:', error));
        }

        // Crear contacto
        document.getElementById('contactoForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const contacto = {
                nombre: document.getElementById('nombre').value,
                telefono: document.getElementById('telefono').value,
                fecha_nacimiento: document.getElementById('fecha_nacimiento').value
            };

            axios.post('/contactos', contacto)
                .then(response => {
                    loadContactos();
                    this.reset();
                })
                .catch(error => {
                    if (error.response && error.response.data.errors) {
                        alert('Errores: ' + JSON.stringify(error.response.data.errors));
                    } else {
                        console.error('Error al crear contacto:', error);
                    }
                });
        });

        // Eliminar contacto
        function deleteContacto(id) {
            if (confirm('¿Seguro que deseas eliminar este contacto?')) {
                axios.delete(`/contactos/${id}`)
                    .then(() => loadContactos())
                    .catch(error => console.error('Error al eliminar contacto:', error));
            }
        }

        // Cargar contactos al cargar la página
        loadContactos();
    </script>
</body>
</html>