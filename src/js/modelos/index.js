import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import { validarFormulario } from '../funciones';
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

const FormModelos = document.getElementById('FormModelos');
const BtnGuardar = document.getElementById('BtnGuardar');
const BtnModificar = document.getElementById('BtnModificar');
const BtnLimpiar = document.getElementById('BtnLimpiar');
const SelectMarca = document.getElementById('id_marca');
const InputEspecificaciones = document.getElementById('especificaciones');
const InputPrecio = document.getElementById('precio_referencia');

const ValidarEspecificaciones = () => {
    const especificaciones = InputEspecificaciones.value;
    const maxLength = 100;

    if (especificaciones.length > maxLength) {
        Swal.fire({
            position: "center",
            icon: "error",
            title: "Especificaciones muy largas",
            text: `Las especificaciones no pueden exceder los ${maxLength} caracteres`,
            showConfirmButton: true,
        });

        InputEspecificaciones.classList.remove('is-valid');
        InputEspecificaciones.classList.add('is-invalid');
    } else {
        InputEspecificaciones.classList.remove('is-invalid');
        if (especificaciones.length > 0) {
            InputEspecificaciones.classList.add('is-valid');
        } else {
            InputEspecificaciones.classList.remove('is-valid');
        }
    }
}

const ValidarPrecio = () => {
    const precio = InputPrecio.value;

    if (precio.length > 0) {
        const precioNumero = parseFloat(precio);
        
        if (isNaN(precioNumero) || precioNumero < 0) {
            Swal.fire({
                position: "center",
                icon: "error",
                title: "Precio inválido",
                text: "El precio debe ser un número válido mayor o igual a 0",
                showConfirmButton: true,
            });

            InputPrecio.classList.remove('is-valid');
            InputPrecio.classList.add('is-invalid');
        } else if (precioNumero > 99999999.99) {
            Swal.fire({
                position: "center",
                icon: "error",
                title: "Precio muy alto",
                text: "El precio excede el límite máximo permitido",
                showConfirmButton: true,
            });

            InputPrecio.classList.remove('is-valid');
            InputPrecio.classList.add('is-invalid');
        } else {
            InputPrecio.classList.remove('is-invalid');
            InputPrecio.classList.add('is-valid');
        }
    } else {
        InputPrecio.classList.remove('is-valid', 'is-invalid');
    }
}

const FormatearPrecio = () => {
    let precio = InputPrecio.value;
    if (precio.length > 0 && !isNaN(precio)) {
        const precioFormateado = parseFloat(precio).toFixed(2);
        InputPrecio.value = precioFormateado;
    }
}

const GuardarModelo = async (event) => {
    event.preventDefault();
    BtnGuardar.disabled = true;

    if (!validarFormulario(FormModelos, ['id_modelo', 'especificaciones', 'precio_referencia'])) {
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

    const body = new FormData(FormModelos);

    const url = '/final_armamento/modelos/guardarAPI';
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
            BuscarModelos();

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

const BuscarModelos = async () => {
    const url = '/final_armamento/modelos/buscarAPI';
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

const CargarMarcas = async () => {
    const url = '/final_armamento/modelos/marcasAPI';
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

const datatable = new DataTable('#TableModelos', {
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
            data: 'id_modelo',
            width: '5%',
            render: (data, type, row, meta) => meta.row + 1
        },
        { 
            title: 'Marca', 
            data: 'nombre_marca',
            width: '20%'
        },
        { 
            title: 'Modelo', 
            data: 'nombre_modelo',
            width: '25%'
        },
        { 
            title: 'Especificaciones', 
            data: 'especificaciones',
            width: '25%',
            render: (data, type, row) => {
                if (data && data.length > 40) {
                    return data.substring(0, 40) + '...';
                }
                return data || '<span class="text-muted">Sin especificaciones</span>';
            }
        },
        { 
            title: 'Precio Referencia', 
            data: 'precio_referencia',
            width: '15%',
            render: (data, type, row) => {
                if (data && data > 0) {
                    return `<span class="badge bg-success">Q${parseFloat(data).toFixed(2)}</span>`;
                }
                return '<span class="text-muted">No definido</span>';
            }
        },
        {
            title: 'Acciones',
            data: 'id_modelo',
            width: '10%',
            searchable: false,
            orderable: false,
            render: (data, type, row, meta) => {
                return `
                 <div class='d-flex justify-content-center'>
                     <button class='btn btn-warning btn-sm modificar mx-1' 
                         data-id="${data}" 
                         data-marca="${row.id_marca}"  
                         data-nombre="${row.nombre_modelo}"  
                         data-especificaciones="${row.especificaciones || ''}"
                         data-precio="${row.precio_referencia || ''}"
                         title="Modificar modelo">
                         <i class='bi bi-pencil-square me-1'></i> Modificar
                     </button>
                     <button class='btn btn-danger btn-sm eliminar mx-1' 
                         data-id="${data}"
                         data-nombre="${row.nombre_modelo}"
                         data-marca="${row.nombre_marca}"
                         title="Eliminar modelo">
                        <i class="bi bi-x-circle me-1"></i>Eliminar
                     </button>
                 </div>`;
            }
        }
    ]
});

const llenarFormulario = async (event) => {
    const datos = event.currentTarget.dataset

    document.getElementById('id_modelo').value = datos.id
    document.getElementById('id_marca').value = datos.marca
    document.getElementById('nombre_modelo').value = datos.nombre
    document.getElementById('especificaciones').value = datos.especificaciones
    document.getElementById('precio_referencia').value = datos.precio

    BtnGuardar.classList.add('d-none');
    BtnModificar.classList.remove('d-none');

    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}

const limpiarTodo = () => {
    FormModelos.reset();
    BtnGuardar.classList.remove('d-none');
    BtnModificar.classList.add('d-none');
    
    const inputs = FormModelos.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.classList.remove('is-valid', 'is-invalid');
    });
}

const ModificarModelo = async (event) => {
    event.preventDefault();
    BtnModificar.disabled = true;

    if (!validarFormulario(FormModelos, ['id_modelo', 'especificaciones', 'precio_referencia'])) {
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

    const body = new FormData(FormModelos);

    const url = '/final_armamento/modelos/modificarAPI';
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
            BuscarModelos();

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

const EliminarModelo = async (e) => {
    const idModelo = e.currentTarget.dataset.id
    const nombreModelo = e.currentTarget.dataset.nombre
    const nombreMarca = e.currentTarget.dataset.marca

    const AlertaConfirmarEliminar = await Swal.fire({
        position: "center",
        icon: "question",
        title: "¿Desea eliminar este modelo?",
        text: `El modelo "${nombreModelo}" de la marca "${nombreMarca}" será desactivado pero no eliminado permanentemente`,
        showConfirmButton: true,
        confirmButtonText: 'Sí, eliminar',
        confirmButtonColor: '#dc3545',
        cancelButtonText: 'No, Cancelar',
        showCancelButton: true
    });

    if (AlertaConfirmarEliminar.isConfirmed) {
        const url = `/final_armamento/modelos/eliminarAPI?id=${idModelo}`;
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

                BuscarModelos();
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
CargarMarcas();
BuscarModelos();

// Event Listeners
datatable.on('click', '.eliminar', EliminarModelo);
datatable.on('click', '.modificar', llenarFormulario);
FormModelos.addEventListener('submit', GuardarModelo);
InputEspecificaciones.addEventListener('input', ValidarEspecificaciones);
InputPrecio.addEventListener('input', ValidarPrecio);
InputPrecio.addEventListener('blur', FormatearPrecio);
BtnLimpiar.addEventListener('click', limpiarTodo);
BtnModificar.addEventListener('click', ModificarModelo);