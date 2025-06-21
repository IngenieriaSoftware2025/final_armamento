// Versión corregida para archivo .js separado
import Swal from "sweetalert2";
import { Dropdown } from "bootstrap";

console.log('🚀 JavaScript cargado correctamente');

const FormAsignaciones = document.getElementById('FormAsignaciones');
const BtnGuardar = document.getElementById('BtnGuardar');
const BtnModificar = document.getElementById('BtnModificar');
const BtnLimpiar = document.getElementById('BtnLimpiar');
const SelectUsuario = document.getElementById('id_usuario');
const SelectMarca = document.getElementById('id_marca');
const SelectAsignador = document.getElementById('usuario_asignador');

// Variable para controlar el modo de edición
let modoEdicion = false;
let idAsignacionActual = null;

// Función para cargar usuarios
const CargarUsuarios = async () => {
    try {
        const respuesta = await fetch('/final_armamento/usuarios/usuariosAPI');
        const datos = await respuesta.json();
        
        if (datos.codigo == 1) {
            SelectUsuario.innerHTML = '<option value="">Seleccione un usuario</option>';
            SelectAsignador.innerHTML = '<option value="">Seleccione quién asigna</option>';
            
            datos.data.forEach(usuario => {
                SelectUsuario.innerHTML += `<option value="${usuario.id_usuario}">${usuario.nombre_completo}</option>`;
                SelectAsignador.innerHTML += `<option value="${usuario.id_usuario}">${usuario.nombre_completo}</option>`;
            });
            console.log('✅ Usuarios cargados');
        }
    } catch (error) {
        console.error('Error al cargar usuarios:', error);
    }
};

// Función para cargar marcas
const CargarMarcas = async () => {
    try {
        const respuesta = await fetch('/final_armamento/asignacionmarcas/marcasAPI');
        const datos = await respuesta.json();
        
        if (datos.codigo == 1) {
            SelectMarca.innerHTML = '<option value="">Seleccione una marca</option>';
            
            datos.data.forEach(marca => {
                SelectMarca.innerHTML += `<option value="${marca.id_marca}">${marca.nombre_marca}</option>`;
            });
            console.log('✅ Marcas cargadas');
        }
    } catch (error) {
        console.error('Error al cargar marcas:', error);
    }
};

// Función para buscar asignaciones
const BuscarAsignaciones = async () => {
    try {
        console.log('🔍 Buscando asignaciones...');
        const respuesta = await fetch('/final_armamento/asignacionmarcas/buscarAPI');
        const datos = await respuesta.json();
        
        console.log('Respuesta asignaciones:', datos);
        
        if (datos.codigo == 1) {
            console.log('✅ Asignaciones encontradas:', datos.data.length);
            mostrarAsignaciones(datos.data);
        } else {
            console.log('No se encontraron asignaciones');
            mostrarAsignaciones([]);
        }
    } catch (error) {
        console.error('Error al buscar asignaciones:', error);
        mostrarAsignaciones([]);
    }
};

// Función para mostrar asignaciones con HEADERS
const mostrarAsignaciones = (asignaciones) => {
    let contenedor = document.querySelector('#TableAsignaciones');
    
    if (!contenedor) {
        const contenedorTabla = document.querySelector('.table-responsive') || 
                              document.querySelector('.card-body:last-child');
        
        if (contenedorTabla) {
            contenedor = document.createElement('table');
            contenedor.id = 'TableAsignaciones';
            contenedor.className = 'table table-striped table-hover table-bordered w-100 table-sm';
            contenedorTabla.appendChild(contenedor);
        }
    }
    
    if (!contenedor) {
        console.log('📋 No se pudo crear/encontrar la tabla');
        return;
    }
    
    // Crear tabla completa con HEADERS
    let tablaHTML = `
        <thead class="table-dark">
            <tr>
                <th style="width: 5%">No.</th>
                <th style="width: 20%">Usuario Asignado</th>
                <th style="width: 15%">Armas</th>
                <th style="width: 15%">Fecha Asignación</th>
                <th style="width: 15%">Asignado Por</th>
                <th style="width: 20%">Observaciones</th>
                <th style="width: 10%">Acciones</th>
            </tr>
        </thead>
        <tbody>
    `;
    
    if (asignaciones.length === 0) {
        tablaHTML += `
            <tr>
                <td colspan="7" class="text-center text-muted">No hay asignaciones registradas</td>
            </tr>
        `;
    } else {
        asignaciones.forEach((asignacion, index) => {
            tablaHTML += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${asignacion.usuario_asignado || 'N/A'}</td>
                    <td><span class="badge bg-primary">${asignacion.nombre_marca || 'N/A'}</span></td>
                    <td>${asignacion.fecha_asignacion ? new Date(asignacion.fecha_asignacion).toLocaleDateString('es-GT') : 'N/A'}</td>
                    <td>${asignacion.asignado_por || 'N/A'}</td>
                    <td>${asignacion.observaciones || '<span class="text-muted">Sin observaciones</span>'}</td>
                    <td>
                        <div class="d-flex justify-content-center">
                            <button class="btn btn-warning btn-sm mx-1 btn-modificar" 
                                data-id="${asignacion.id_asignacion}"
                                data-usuario="${asignacion.id_usuario}"
                                data-marca="${asignacion.id_marca}"
                                data-asignador="${asignacion.usuario_asignador || ''}"
                                data-observaciones="${asignacion.observaciones || ''}"
                                title="Modificar asignación">
                                <i class="bi bi-pencil-square me-1"></i>Modificar
                            </button>
                            <button class="btn btn-danger btn-sm mx-1 btn-eliminar" 
                                data-id="${asignacion.id_asignacion}"
                                data-usuario="${asignacion.usuario_asignado}"
                                data-marca="${asignacion.nombre_marca}"
                                title="Eliminar asignación">
                                <i class="bi bi-x-circle me-1"></i>Eliminar
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });
    }
    
    tablaHTML += '</tbody>';
    contenedor.innerHTML = tablaHTML;
    
    // VINCULAR EVENTOS CORRECTAMENTE
    vinculareEventosTabla();
    
    console.log('✅ Asignaciones mostradas en tabla con headers');
};

// Función para vincular eventos de la tabla
const vinculareEventosTabla = () => {
    document.querySelectorAll('.btn-modificar').forEach(btn => {
        btn.addEventListener('click', function(e) {
            const datos = e.currentTarget.dataset;
            modificarAsignacion(datos.id, datos.usuario, datos.marca, datos.asignador, datos.observaciones);
        });
    });
    
    document.querySelectorAll('.btn-eliminar').forEach(btn => {
        btn.addEventListener('click', function(e) {
            const datos = e.currentTarget.dataset;
            eliminarAsignacion(datos.id, datos.usuario, datos.marca);
        });
    });
};

// Función para guardar o modificar asignación
const GuardarAsignacion = async (event) => {
    event.preventDefault();
    console.log('💾 Intentando guardar asignación...');
    BtnGuardar.disabled = true;
    
    if (!SelectUsuario.value) {
        await Swal.fire({
            position: "center",
            icon: "info",
            title: "FORMULARIO INCOMPLETO",
            text: "Debe seleccionar un usuario",
            showConfirmButton: true,
        });
        BtnGuardar.disabled = false;
        return;
    }
    
    if (!SelectMarca.value) {
        await Swal.fire({
            position: "center",
            icon: "info",
            title: "FORMULARIO INCOMPLETO",
            text: "Debe seleccionar una marca",
            showConfirmButton: true,
        });
        BtnGuardar.disabled = false;
        return;
    }
    
    if (!SelectAsignador.value) {
        await Swal.fire({
            position: "center",
            icon: "info",
            title: "FORMULARIO INCOMPLETO",
            text: "Debe seleccionar quién asigna",
            showConfirmButton: true,
        });
        BtnGuardar.disabled = false;
        return;
    }
    
    const body = new FormData(FormAsignaciones);
    
    // Determinar si es guardar o modificar
    const url = modoEdicion ? 
        '/final_armamento/asignacionmarcas/modificarAPI' : 
        '/final_armamento/asignacionmarcas/guardarAPI';
    
    try {
        const respuesta = await fetch(url, {
            method: 'POST',
            body
        });
        
        const datos = await respuesta.json();
        
        if (datos.codigo == 1) {
            await Swal.fire({
                position: "center",
                icon: "success",
                title: "¡Éxito!",
                text: datos.mensaje,
                showConfirmButton: true,
            });
            limpiarTodo();
            BuscarAsignaciones();
        } else {
            await Swal.fire({
                position: "center",
                icon: "error",
                title: "Error",
                text: datos.mensaje,
                showConfirmButton: true,
            });
        }
    } catch (error) {
        console.error('Error al guardar:', error);
        await Swal.fire({
            position: "center",
            icon: "error",
            title: "Error de conexión",
            text: "Error al procesar la asignación",
            showConfirmButton: true,
        });
    }
    BtnGuardar.disabled = false;
};

const limpiarTodo = () => {
    FormAsignaciones.reset();
    modoEdicion = false;
    idAsignacionActual = null;
    
    // Cambiar botones
    BtnGuardar.textContent = 'Guardar';
    BtnGuardar.className = 'btn btn-success';
    if (BtnModificar) {
        BtnModificar.classList.add('d-none');
    }
    
    console.log('🧹 Formulario limpiado');
};

// Función de eliminación simplificada para testing
// Función para eliminar asignación - RUTA CORREGIDA
const eliminarAsignacion = async (id, nombreUsuario, nombreMarca) => {
    console.log('🗑️ Eliminando ID:', id);
    
    const confirmar = await Swal.fire({
        position: "center",
        icon: "question",
        title: "¿Eliminar asignación?",
        text: `${nombreMarca} - ${nombreUsuario}`,
        showConfirmButton: true,
        confirmButtonText: 'Sí, eliminar',
        confirmButtonColor: '#dc3545',
        showCancelButton: true,
        cancelButtonText: 'Cancelar'
    });

    if (!confirmar.isConfirmed) return;

    try {
        // RUTA CORREGIDA según tu archivo de rutas (línea 93)
        const url = `/final_armamento/asignacionmarcas/eliminar?id=${id}`;
        
        console.log(`🔄 Eliminando con URL: ${url}`);
        
        const response = await fetch(url, {
            method: 'GET'
        });
        
        const datos = await response.json();
        console.log('Respuesta del servidor:', datos);
        
        if (datos.codigo == 1) {
            await Swal.fire({
                position: "center",
                icon: "success",
                title: "¡Eliminado!",
                text: datos.mensaje,
                showConfirmButton: true,
            });
            
            BuscarAsignaciones(); // Recargar tabla
        } else {
            await Swal.fire({
                position: "center",
                icon: "error", 
                title: "Error",
                text: datos.mensaje,
                showConfirmButton: true,
            });
        }
        
    } catch (error) {
        console.error('Error al eliminar:', error);
        await Swal.fire({
            position: "center",
            icon: "error",
            title: "Error de conexión", 
            text: "No se pudo eliminar la asignación",
            showConfirmButton: true,
        });
    }
};

const modificarAsignacion = async (id, idUsuario, idMarca, idAsignador, observaciones) => {
    console.log('✏️ Cargando datos para modificar:', {id, idUsuario, idMarca, idAsignador, observaciones});
    
    // Cargar datos en el formulario
    document.getElementById('id_asignacion').value = id;
    SelectUsuario.value = idUsuario;
    SelectMarca.value = idMarca;
    SelectAsignador.value = idAsignador;
    document.getElementById('observaciones').value = observaciones || '';
    
    // Activar modo edición
    modoEdicion = true;
    idAsignacionActual = id;
    
    // Cambiar el botón a modo modificar
    BtnGuardar.textContent = 'Actualizar';
    BtnGuardar.className = 'btn btn-warning';
    
    await Swal.fire({
        position: "center",
        icon: "info",
        title: "Modo edición",
        text: "Los datos han sido cargados en el formulario. Modifica y guarda los cambios.",
        showConfirmButton: true,
    });
    
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
};

// Inicializar cuando cargue la página
document.addEventListener('DOMContentLoaded', function() {
    console.log('📄 Página cargada, inicializando...');
    CargarUsuarios();
    CargarMarcas();
    BuscarAsignaciones();
    
    if (FormAsignaciones) FormAsignaciones.addEventListener('submit', GuardarAsignacion);
    if (BtnLimpiar) BtnLimpiar.addEventListener('click', limpiarTodo);
});

// Ejecutar inmediatamente también
CargarUsuarios();
CargarMarcas();
BuscarAsignaciones();