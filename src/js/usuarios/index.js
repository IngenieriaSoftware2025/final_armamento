import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import { validarFormulario } from '../funciones';
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

const FormUsuarios = document.getElementById('FormUsuarios');
const BtnGuardar = document.getElementById('BtnGuardar');
const BtnModificar = document.getElementById('BtnModificar');
const BtnLimpiar = document.getElementById('BtnLimpiar');
const InputEmail = document.getElementById('email');
const SelectRol = document.getElementById('id_rol');

const ValidarEmail = () => {
    const email = InputEmail.value;
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (email.length < 1) {
        InputEmail.classList.remove('is-valid', 'is-invalid');
    } else {
        if (!emailPattern.test(email)) {
            Swal.fire({
                position: "center",
                icon: "error",
                title: "Email inválido",
                text: "El formato del email no es válido",
                showConfirmButton: true,
            });

            InputEmail.classList.remove('is-valid');
            InputEmail.classList.add('is-invalid');
        } else {
            InputEmail.classList.remove('is-invalid');
            InputEmail.classList.add('is-valid');
        }
    }
}

const GuardarUsuario = async (event) => {
    event.preventDefault();
    BtnGuardar.disabled = true;

    if (!validarFormulario(FormUsuarios, ['id_usuario'])) {
        Swal.fire({
            position: "center",
            icon: "info",
            title: "FORMULARIO INCOMPLETO",
            text: "Debe de validar todos los campos",
            showConfirmButton: true,
        });
        BtnGuardar.disabled = false;
        return;
    }

    const body = new FormData(FormUsuarios);

    const url = '/final_armamento/usuarios/guardarAPI';
    const config = {
        method: 'POST',
        body
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje } = datos

        if (codigo == 1) {
            await Swal.fire({
                position: "center",
                icon: "success",
                title: "Éxito",
                text: mensaje,
                showConfirmButton: true,
            });

            limpiarTodo();
            BuscarUsuarios();

        } else {
            await Swal.fire({
                position: "center",
                icon: "error",
                title: "Error",
                text: mensaje,
                showConfirmButton: true,
            });
        }

    } catch (error) {
        console.log(error)
    }
    BtnGuardar.disabled = false;
}

const BuscarUsuarios = async () => {
    const url = '/final_armamento/usuarios/buscarAPI';
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos

        if (codigo == 1) {
            datatable.clear().draw();
            datatable.rows.add(data).draw();
        } else {
            await Swal.fire({
                position: "center",
                icon: "error",
                title: "Error",
                text: mensaje,
                showConfirmButton: true,
            });
        }

    } catch (error) {
        console.log(error)
    }
}

const CargarRoles = async () => {
    const url = '/final_armamento/usuarios/rolesAPI';
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos

        if (codigo == 1) {
            SelectRol.innerHTML = '<option value="">Seleccione un rol</option>';
            data.forEach(rol => {
                SelectRol.innerHTML += `<option value="${rol.id_rol}">${rol.nombre_rol}</option>`;
            });
        } else {
            console.log('Error al cargar roles:', mensaje);
        }

    } catch (error) {
        console.log(error)
    }
}

// Función para eliminar usuario - CORREGIDA
// Función para eliminar usuario - RUTA CORREGIDA
const eliminarUsuario = async (id, nombre) => {
    const result = await Swal.fire({
        position: "center",
        icon: "question",
        title: "¿Desea eliminar este usuario?",
        text: `El usuario "${nombre}" será desactivado pero no eliminado permanentemente`,
        showConfirmButton: true,
        confirmButtonText: 'Sí, eliminar',
        confirmButtonColor: '#dc3545',
        cancelButtonText: 'No, Cancelar',
        showCancelButton: true
    });

    if (result.isConfirmed) {
        try {
            const url = `/final_armamento/usuarios/EliminarAPI?id=${id}`; // ← RUTA CORREGIDA
            const response = await fetch(url, {
                method: 'GET'
            });
            
            const datos = await response.json();
            const { codigo, mensaje } = datos;

            if (codigo == 1) {
                await Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Éxito",
                    text: mensaje,
                    showConfirmButton: true,
                });
                BuscarUsuarios();
            } else {
                await Swal.fire({
                    position: "center",
                    icon: "error",
                    title: "Error",
                    text: mensaje,
                    showConfirmButton: true,
                });
            }
        } catch (error) {
            console.error('Error al eliminar usuario:', error);
            await Swal.fire({
                position: "center",
                icon: "error",
                title: "Error",
                text: "Error de conexión al eliminar usuario",
                showConfirmButton: true,
            });
        }
    }
};

const datatable = new DataTable('#TableUsuarios', {
    dom: `
        <"row mt-3 justify-content-between" 
            <"col" l> 
            <"col" B> 
            <"col-3" f>
        >
        t
        <"row mt-3 justify-content-between" 
            <"col-md-3 d-flex align-items-center" i> 
            <"col-md-8 d-flex justify-content-end" p>
        >
    `,
    language: lenguaje,
    data: [],
    columns: [
        {
            title: 'Foto',
            data: 'foto',
            width: '8%',
            searchable: false,
            orderable: false,
            render: (data, type, row) => {
                if (data) {
                    return `<div class="text-center">
                                <img src="/${data}" 
                                     alt="Foto de ${row.nombre_completo}" 
                                     style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid #007bff; cursor: pointer;"
                                     title="${row.nombre_completo}"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-block';">
                                <i class="bi bi-person-circle" style="font-size: 40px; color: #6c757d; display: none;" title="Error al cargar foto"></i>
                            </div>`;
                } else {
                    return `<div class="text-center">
                                <i class="bi bi-person-circle" style="font-size: 40px; color: #6c757d;" title="Sin foto"></i>
                            </div>`;
                }
            }
        },
        {
            title: 'No.',
            data: 'id_usuario',
            width: '5%',
            render: (data, type, row, meta) => meta.row + 1
        },
        { 
            title: 'Usuario', 
            data: 'nombre_usuario',
            width: '12%'
        },
        { 
            title: 'Nombre Completo', 
            data: 'nombre_completo',
            width: '20%'
        },
        { 
            title: 'Email', 
            data: 'email',
            width: '18%',
            render: (data, type, row) => {
                return data || '<span class="text-muted">No especificado</span>';
            }
        },
        { 
            title: 'Teléfono', 
            data: 'telefono',
            width: '10%',
            render: (data, type, row) => {
                return data || '<span class="text-muted">No especificado</span>';
            }
        },
        { 
            title: 'Rol', 
            data: 'nombre_rol',
            width: '12%',
            render: (data, type, row) => {
                let badge = '';
                
                if (data === 'Administrador') {
                    badge = '<span class="badge bg-danger">Administrador</span>';
                } else if (data === 'Empleado') {
                    badge = '<span class="badge bg-primary">Empleado</span>';
                } else if (data === 'Técnico') {
                    badge = '<span class="badge bg-success">Técnico</span>';
                } else {
                    badge = `<span class="badge bg-secondary">${data}</span>`;
                }
                
                return badge;
            }
        },
        {
            title: 'Acciones',
            data: null,
            width: '15%',
            searchable: false,
            orderable: false,
            render: (data, type, row, meta) => {
                return `
                 <div class='d-flex justify-content-center'>
                     <button class='btn btn-warning btn-sm modificar mx-1' 
                         data-id="${row.id_usuario}" 
                         data-usuario="${row.nombre_usuario}"  
                         data-nombre="${row.nombre_completo}"  
                         data-email="${row.email || ''}"  
                         data-telefono="${row.telefono || ''}"  
                         data-rol="${row.nombre_rol}"
                         data-foto="${row.foto || ''}"
                         data-id_rol="${row.id_rol || ''}"
                         title="Modificar usuario">
                         <i class='bi bi-pencil-square me-1'></i> Modificar
                     </button>
                     <button class='btn btn-danger btn-sm eliminar mx-1' 
                         onclick="eliminarUsuario(${row.id_usuario}, '${row.nombre_completo}')"
                         title="Eliminar usuario">
                        <i class="bi bi-x-circle me-1"></i>Eliminar
                     </button>
                 </div>`;
            }
        }
    ]
});

// Hacer la función global para que funcione el onclick
window.eliminarUsuario = eliminarUsuario;

const llenarFormulario = async (event) => {
    const datos = event.currentTarget.dataset

    document.getElementById('id_usuario').value = datos.id
    document.getElementById('nombre_usuario').value = datos.usuario
    document.getElementById('nombre_completo').value = datos.nombre
    document.getElementById('email').value = datos.email
    document.getElementById('telefono').value = datos.telefono
    
    // Seleccionar el rol por ID
    if (datos.id_rol) {
        document.getElementById('id_rol').value = datos.id_rol;
    }

    // Mostrar foto actual si existe
    const preview = document.getElementById('imagen-preview');
    if (preview) {
        if (datos.foto) {
            preview.src = `/${datos.foto}`;
            preview.style.display = 'block';
        } else {
            preview.style.display = 'none';
        }
    }

    // Limpiar el campo de password
    document.getElementById('password').value = '';
    
    // Cambiar el placeholder del password
    document.getElementById('password').placeholder = 'Dejar vacío para mantener la actual';

    BtnGuardar.classList.add('d-none');
    BtnModificar.classList.remove('d-none');

    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}

const limpiarTodo = () => {
    FormUsuarios.reset();
    BtnGuardar.classList.remove('d-none');
    BtnModificar.classList.add('d-none');
    
    // Limpiar preview de imagen
    const preview = document.getElementById('imagen-preview');
    if (preview) {
        preview.style.display = 'none';
        preview.src = '';
    }
    
    // Restaurar placeholder original del password
    document.getElementById('password').placeholder = 'Contraseña';
    
    const inputs = FormUsuarios.querySelectorAll('input, select');
    inputs.forEach(input => {
        input.classList.remove('is-valid', 'is-invalid');
    });
}

const ModificarUsuario = async (event) => {
    event.preventDefault();
    BtnModificar.disabled = true;

    if (!validarFormulario(FormUsuarios, ['password'])) {
        Swal.fire({
            position: "center",
            icon: "info",
            title: "FORMULARIO INCOMPLETO",
            text: "Debe de validar todos los campos",
            showConfirmButton: true,
        });
        BtnModificar.disabled = false;
        return;
    }

    const body = new FormData(FormUsuarios);

    const url = '/final_armamento/usuarios/modificarAPI';
    const config = {
        method: 'POST',
        body
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje } = datos

        if (codigo == 1) {
            await Swal.fire({
                position: "center",
                icon: "success",
                title: "Éxito",
                text: mensaje,
                showConfirmButton: true,
            });

            limpiarTodo();
            BuscarUsuarios();

        } else {
            await Swal.fire({
                position: "center",
                icon: "error",
                title: "Error",
                text: mensaje,
                showConfirmButton: true,
            });
        }

    } catch (error) {
        console.log(error)
    }
    BtnModificar.disabled = false;
}

// JavaScript para vista previa de imagen
document.addEventListener('DOMContentLoaded', function() {
    const fotoInput = document.getElementById('foto');
    const preview = document.getElementById('imagen-preview');
    
    if (fotoInput && preview) {
        fotoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            
            if (file) {
                // Validar tipo de archivo
                const tiposPermitidos = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                if (!tiposPermitidos.includes(file.type)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Archivo no válido',
                        text: 'Solo se permiten archivos JPG, PNG y GIF'
                    });
                    e.target.value = '';
                    preview.style.display = 'none';
                    return;
                }
                
                // Validar tamaño (2MB)
                if (file.size > 2097152) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Archivo muy grande',
                        text: 'El archivo debe ser menor a 2MB'
                    });
                    e.target.value = '';
                    preview.style.display = 'none';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
            }
        });
    }
});

// Inicializar
CargarRoles();
BuscarUsuarios();

// Event Listeners
datatable.on('click', '.modificar', llenarFormulario);
FormUsuarios.addEventListener('submit', GuardarUsuario);
InputEmail.addEventListener('change', ValidarEmail);
BtnLimpiar.addEventListener('click', limpiarTodo);
BtnModificar.addEventListener('click', ModificarUsuario);