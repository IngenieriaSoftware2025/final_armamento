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
const SelectModelo = document.getElementById('id_modelo');
const SelectEstado = document.getElementById('estado');
const FechaDevolucion = document.getElementById('fecha_devolucion');

const GuardarAsignacion = async (event) => {
    event.preventDefault();
    BtnGuardar.disabled = true;

    if (!validarFormulario(FormAsignaciones, ['id_asignacion', 'fecha_devolucion'])) {
        Swal.fire({
            position: "center",
            icon: "info",
            title: "FORMULARIO INCOMPLETO",
            text: "Debe validar todos los campos obligatorios",
            showConfirmButton: true,
        });
        BtnGuardar.disabled = false;
        return;
    }

    const body = new FormData(FormAsignaciones);

    const url = '/final_armamento/asignaciones/guardarAPI';
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
    const url = '/final_armamento/asignaciones/buscarAPI';
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
    const url = '/final_armamento/asignaciones/usuariosAPI';
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos

        if (codigo == 1) {
            SelectUsuario.innerHTML = '<option value="">Seleccione un usuario</option>';
            data.forEach(usuario => {
                SelectUsuario.innerHTML += `<option value="${usuario.id_usuario}">${usuario.nombre_completo} (${usuario.nombre_usuario})</option>`;
            });
        } else {
            console.log('Error al cargar usuarios:', mensaje);
        }

    } catch (error) {
        console.log(error)
    }
}

const CargarModelos = async () => {
    const url = '/final_armamento/asignaciones/modelosAPI';
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos

        if (codigo == 1) {
            SelectModelo.innerHTML = '<option value="">Seleccione un modelo</option>';
            data.forEach(modelo => {
                SelectModelo.innerHTML += `<option value="${modelo.id_modelo}">${modelo.modelo_completo}</option>`;
            });
        } else {
            console.log('Error al cargar modelos:', mensaje);
        }

    } catch (error) {
        console.log(error)
    }
}

const ManejarEstado = () => {
    if (SelectEstado.value === 'DEVUELTO') {
        FechaDevolucion.required = true;
        FechaDevolucion.parentElement.classList.remove('d-none');
    } else {
        FechaDevolucion.required = false;
        FechaDevolucion.parentElement.classList.add('d-none');
        FechaDevolucion.value = '';
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
            title: 'Usuario', 
            data: 'usuario',
            width: '20%'
        },
        { 
            title: 'Armamento', 
            data: 'armamento_completo',
            width: '20%'
        },
        { 
            title: 'N° Serie', 
            data: 'numero_serie',
            width: '12%'
        },
        { 
            title: 'F. Asignación', 
            data: 'fecha_asignacion',
            width: '10%',
            render: (data, type, row) => {
                if (data) {
                    const fecha = new Date(data);
                    return fecha.toLocaleDateString('es-GT');
                }
                return '';
            }
        },
        { 
            title: 'F. Devolución', 
            data: 'fecha_devolucion',
            width: '10%',
            render: (data, type, row) => {
                if (data) {
                    const fecha = new Date(data);
                    return fecha.toLocaleDateString('es-GT');
                }
                return '<span class="text-muted">N/A</span>';
            }
        },
        { 
            title: 'Estado', 
            data: 'estado',
            width: '10%',
            render: (data, type, row) => {
                let badge = '';
                
                switch(data) {
                    case 'ASIGNADO':
                        badge = '<span class="badge bg-success">Asignado</span>';
                        break;
                    case 'DEVUELTO':
                        badge = '<span class="badge bg-primary">Devuelto</span>';
                        break;
                    case 'PERDIDO':
                        badge = '<span class="badge bg-danger">Perdido</span>';
                        break;
                    case 'DAÑADO':
                        badge = '<span class="badge bg-warning">Dañado</span>';
                        break;
                    default:
                        badge = `<span class="badge bg-secondary">${data}</span>`;
                }
                
                return badge;
            }
        },
        {
            title: 'Acciones',
            data: 'id_asignacion',
            width: '13%',
            searchable: false,
            orderable: false,
            render: (data, type, row, meta) => {
                return `
                 <div class='d-flex justify-content-center'>
                     <button class='btn btn-warning btn-sm modificar mx-1' 
                         data-id="${data}" 
                         data-numero="${row.numero_serie}"  
                         data-estado="${row.estado}"  
                         data-fecha-devolucion="${row.fecha_devolucion || ''}"  
                         data-observaciones="${row.observaciones || ''}"
                         title="Modificar asignación">
                         <i class='bi bi-pencil-square me-1'></i> Modificar
                     </button>
                     <button class='btn btn-danger btn-sm eliminar mx-1' 
                         data-id="${data}"
                         data-usuario="${row.usuario}"
                         data-armamento="${row.armamento_completo}"
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
    document.getElementById('numero_serie').value = datos.numero
    document.getElementById('estado').value = datos.estado
    document.getElementById('fecha_devolucion').value = datos.fechaDevolucion
    document.getElementById('observaciones').value = datos.observaciones
    
    // Manejar el campo de fecha de devolución según el estado
    ManejarEstado();

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
    
    // Ocultar fecha de devolución
    FechaDevolucion.parentElement.classList.add('d-none');
    FechaDevolucion.required = false;
    
    // Establecer fecha actual
    EstablecerFechaActual();
    
    const inputs = FormAsignaciones.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.classList.remove('is-valid', 'is-invalid');
    });
}

const ModificarAsignacion = async (event) => {
    event.preventDefault();
    BtnModificar.disabled = true;

    const camposExcluir = ['id_usuario', 'id_modelo', 'fecha_asignacion'];
    if (SelectEstado.value !== 'DEVUELTO') {
        camposExcluir.push('fecha_devolucion');
    }

    if (!validarFormulario(FormAsignaciones, camposExcluir)) {
        Swal.fire({
            position: "center",
            icon: "info",
            title: "FORMULARIO INCOMPLETO",
            text: "Debe validar todos los campos obligatorios",
            showConfirmButton: true,
        });
        BtnModificar.disabled = false;
        return;
    }

    const body = new FormData(FormAsignaciones);

    const url = '/final_armamento/asignaciones/modificarAPI';
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
    const armamento = e.currentTarget.dataset.armamento

    const AlertaConfirmarEliminar = await Swal.fire({
        position: "center",
        icon: "question",
        title: "¿Desea eliminar esta asignación?",
        text: `La asignación de "${armamento}" a "${nombreUsuario}" será eliminada`,
        showConfirmButton: true,
        confirmButtonText: 'Sí, eliminar',
        confirmButtonColor: '#dc3545',
        cancelButtonText: 'No, Cancelar',
        showCancelButton: true
    });

    if (AlertaConfirmarEliminar.isConfirmed) {
        const url = `/final_armamento/asignaciones/eliminarAPI?id=${idAsignacion}`;
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

// Función para establecer la fecha actual por defecto
const EstablecerFechaActual = () => {
    const hoy = new Date();
    const fechaFormateada = hoy.toISOString().split('T')[0];
    document.getElementById('fecha_asignacion').value = fechaFormateada;
}

// Función para validar que la fecha de devolución no sea anterior a la de asignación
const ValidarFechas = () => {
    const fechaAsignacion = document.getElementById('fecha_asignacion').value;
    const fechaDevolucion = document.getElementById('fecha_devolucion').value;
    
    if (fechaAsignacion && fechaDevolucion && fechaDevolucion < fechaAsignacion) {
        Swal.fire({
            position: "center",
            icon: "warning",
            title: "Fecha inválida",
            text: "La fecha de devolución no puede ser anterior a la fecha de asignación",
            showConfirmButton: true,
        });
        document.getElementById('fecha_devolucion').value = '';
    }
}

// Inicializar
EstablecerFechaActual();
CargarUsuarios();
CargarModelos();
BuscarAsignaciones();

// Event Listeners
datatable.on('click', '.eliminar', EliminarAsignacion);
datatable.on('click', '.modificar', llenarFormulario);
FormAsignaciones.addEventListener('submit', GuardarAsignacion);
SelectEstado.addEventListener('change', ManejarEstado);
document.getElementById('fecha_devolucion').addEventListener('change', ValidarFechas);
BtnLimpiar.addEventListener('click', limpiarTodo);
BtnModificar.addEventListener('click', ModificarAsignacion);