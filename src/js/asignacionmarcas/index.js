import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import { validarFormulario } from '../funciones';
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

const FormAsignaciones = document.getElementById('FormAsignaciones');
const BtnGuardar = document.getElementById('BtnGuardar');
const BtnModificar = document.getElementById('BtnModificar');
const BtnLimpiar = document.getElementById('BtnLimpiar');
const SelectUsuario = document.getElementById('id_usuario');
const SelectMarca = document.getElementById('id_marca');
const SelectAsignador = document.getElementById('usuario_asignador');

const GuardarAsignacion = async (event) => {
    event.preventDefault();
    BtnGuardar.disabled = true;

    if (!validarFormulario(FormAsignaciones, ['id_asignacion'])) {
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

    const body = new FormData(FormAsignaciones);

    const url = '/final_armamento/asignacion_marcas/guardarAPI';
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
            BuscarAsignaciones();

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

const BuscarAsignaciones = async () => {
    const url = '/final_armamento/asignacion_marcas/buscarAPI';
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

const CargarUsuarios = async () => {
    const url = '/final_armamento/asignacion_marcas/usuariosAPI';
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos

        if (codigo == 1) {
            SelectUsuario.innerHTML = '<option value="">Seleccione un usuario</option>';
            SelectAsignador.innerHTML = '<option value="">Seleccione quién asigna</option>';
            data.forEach(usuario => {
                SelectUsuario.innerHTML += `<option value="${usuario.id_usuario}">${usuario.nombre_completo}</option>`;
                SelectAsignador.innerHTML += `<option value="${usuario.id_usuario}">${usuario.nombre_completo}</option>`;
            });
        } else {
            console.log('Error al cargar usuarios:', mensaje);
        }

    } catch (error) {
        console.log(error)
    }
}

const CargarMarcas = async () => {
    const url = '/final_armamento/asignacion_marcas/marcasAPI';
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos

        if (codigo == 1) {
            SelectMarca.innerHTML = '<option value="">Seleccione una marca</option>';
            data.forEach(marca => {
                SelectMarca.innerHTML += `<option value="${marca.id_marca}">${marca.nombre_marca}</option>`;
            });
        } else {
            console.log('Error al cargar marcas:', mensaje);
        }

    } catch (error) {
        console.log(error)
    }
}

const datatable = new DataTable('#TableAsignaciones', {
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
            title: 'No.',
            data: 'id_asignacion',
            width: '5%',
            render: (data, type, row, meta) => meta.row + 1
        },
        { 
            title: 'Usuario Asignado', 
            data: 'usuario_asignado',
            width: '20%'
        },
        { 
            title: 'Marca', 
            data: 'nombre_marca',
            width: '15%',
            render: (data, type, row) => {
                return `<span class="badge bg-primary">${data}</span>`;
            }
        },
        { 
            title: 'Fecha Asignación', 
            data: 'fecha_asignacion',
            width: '15%',
            render: (data, type, row) => {
                const fecha = new Date(data);
                return fecha.toLocaleDateString('es-GT');
            }
        },
        { 
            title: 'Asignado Por', 
            data: 'asignado_por',
            width: '15%'
        },
        { 
            title: 'Observaciones', 
            data: 'observaciones',
            width: '20%',
            render: (data, type, row) => {
                return data || '<span class="text-muted">Sin observaciones</span>';
            }
        },
        {
            title: 'Acciones',
            data: 'id_asignacion',
            width: '10%',
            searchable: false,
            orderable: false,
            render: (data, type, row, meta) => {
                return `
                 <div class='d-flex justify-content-center'>
                     <button class='btn btn-warning btn-sm modificar mx-1' 
                         data-id="${data}" 
                         data-usuario="${row.id_usuario}"  
                         data-marca="${row.id_marca}"  
                         data-observaciones="${row.observaciones || ''}"
                         title="Modificar asignación">
                         <i class='bi bi-pencil-square me-1'></i> Modificar
                     </button>
                     <button class='btn btn-danger btn-sm eliminar mx-1' 
                         data-id="${data}"
                         data-usuario="${row.usuario_asignado}"
                         data-marca="${row.nombre_marca}"
                         title="Eliminar asignación">
                        <i class="bi bi-x-circle me-1"></i>Eliminar
                     </button>
                 </div>`;
            }
        }
    ]
});

const llenarFormulario = async (event) => {
    const datos = event.currentTarget.dataset

    document.getElementById('id_asignacion').value = datos.id
    document.getElementById('observaciones').value = datos.observaciones
    
    // Seleccionar usuario
    SelectUsuario.value = datos.usuario;
    
    // Seleccionar marca
    SelectMarca.value = datos.marca;

    BtnGuardar.classList.add('d-none');
    BtnModificar.classList.remove('d-none');

    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}

const limpiarTodo = () => {
    FormAsignaciones.reset();
    BtnGuardar.classList.remove('d-none');
    BtnModificar.classList.add('d-none');
    
    const inputs = FormAsignaciones.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.classList.remove('is-valid', 'is-invalid');
    });
}

const ModificarAsignacion = async (event) => {
    event.preventDefault();
    BtnModificar.disabled = true;

    if (!validarFormulario(FormAsignaciones, [])) {
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

    const body = new FormData(FormAsignaciones);

    const url = '/final_armamento/asignacion_marcas/modificarAPI';
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
            BuscarAsignaciones();

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

const EliminarAsignacion = async (e) => {
    const idAsignacion = e.currentTarget.dataset.id
    const nombreUsuario = e.currentTarget.dataset.usuario
    const nombreMarca = e.currentTarget.dataset.marca

    const AlertaConfirmarEliminar = await Swal.fire({
        position: "center",
        icon: "question",
        title: "¿Desea eliminar esta asignación?",
        text: `Se eliminará la asignación de "${nombreMarca}" a "${nombreUsuario}"`,
        showConfirmButton: true,
        confirmButtonText: 'Sí, eliminar',
        confirmButtonColor: '#dc3545',
        cancelButtonText: 'No, Cancelar',
        showCancelButton: true
    });

    if (AlertaConfirmarEliminar.isConfirmed) {
        const url = `/final_armamento/asignacion_marcas/eliminarAPI?id=${idAsignacion}`;
        const config = {
            method: 'GET'
        }

        try {
            const consulta = await fetch(url, config);
            const respuesta = await consulta.json();
            const { codigo, mensaje } = respuesta;

            if (codigo == 1) {
                await Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Éxito",
                    text: mensaje,
                    showConfirmButton: true,
                });

                BuscarAsignaciones();
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
}

// Inicializar
CargarUsuarios();
CargarMarcas();
BuscarAsignaciones();

// Event Listeners
datatable.on('click', '.eliminar', EliminarAsignacion);
datatable.on('click', '.modificar', llenarFormulario);
FormAsignaciones.addEventListener('submit', GuardarAsignacion);
BtnLimpiar.addEventListener('click', limpiarTodo);
BtnModificar.addEventListener('click', ModificarAsignacion);