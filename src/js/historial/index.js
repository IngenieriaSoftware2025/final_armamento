import { Modal } from "bootstrap";
import Swal from "sweetalert2";
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

console.log('🚀 Iniciando Historial de Actividades...');

// Verificar que los elementos existen antes de usarlos
const getElement = (id) => {
    const element = document.getElementById(id);
    if (!element) {
        console.warn(`⚠️ Elemento ${id} no encontrado`);
    }
    return element;
};

// Elementos del DOM con verificación
const FormFiltros = getElement('FormFiltros');
const BtnBuscar = getElement('BtnBuscar');
const BtnLimpiarFiltros = getElement('BtnLimpiarFiltros');
const BtnEstadisticas = getElement('BtnEstadisticas');
const BtnExportar = getElement('BtnExportar');
const SelectUsuario = getElement('filtro_usuario');
const SelectTipo = getElement('filtro_tipo');
const SelectModulo = getElement('filtro_modulo');
const FechaDesde = getElement('filtro_fecha_desde');
const FechaHasta = getElement('filtro_fecha_hasta');

console.log('✅ Elementos del DOM verificados');

const BuscarHistorial = async (aplicarFiltros = false) => {
    console.log('🔍 Buscando historial...');
    
    let url = '/final_armamento/historial/buscarAPI';
    
    if (aplicarFiltros) {
        const params = new URLSearchParams();
        if (SelectUsuario?.value) params.append('usuario', SelectUsuario.value);
        if (SelectTipo?.value) params.append('tipo_actividad', SelectTipo.value);
        if (SelectModulo?.value) params.append('modulo', SelectModulo.value);
        if (FechaDesde?.value) params.append('fecha_desde', FechaDesde.value);
        if (FechaHasta?.value) params.append('fecha_hasta', FechaHasta.value);
        
        if (params.toString()) {
            url += '?' + params.toString();
        }
    }

    console.log('🌐 URL de búsqueda:', url);

    try {
        const respuesta = await fetch(url);
        console.log('📡 Respuesta recibida:', respuesta.status);
        
        if (!respuesta.ok) {
            throw new Error(`HTTP ${respuesta.status}: ${respuesta.statusText}`);
        }
        
        const datos = await respuesta.json();
        console.log('📦 Datos recibidos:', datos);
        
        const { codigo, mensaje, data } = datos;

        if (codigo == 1) {
            console.log('✅ Datos obtenidos correctamente');
            if (window.datatable) {
                window.datatable.clear().draw();
                if (data && Array.isArray(data) && data.length > 0) {
                    console.log(`📊 Agregando ${data.length} registros a la tabla`);
                    window.datatable.rows.add(data).draw();
                } else {
                    console.log('📭 No hay datos para mostrar');
                }
            } else {
                console.error('❌ DataTable no está inicializada');
            }
        } else {
            console.error('❌ Error en respuesta del servidor:', mensaje);
            await Swal.fire({
                position: "center",
                icon: "error",
                title: "Error del servidor",
                text: mensaje || 'Error desconocido',
                showConfirmButton: true,
            });
        }

    } catch (error) {
        console.error('❌ Error en la solicitud:', error);
        await Swal.fire({
            position: "center",
            icon: "error",
            title: "Error de conexión",
            text: `No se pudo conectar: ${error.message}`,
            showConfirmButton: true,
        });
    }
}

const CargarUsuarios = async () => {
    console.log('👥 Cargando usuarios...');
    
    if (!SelectUsuario) {
        console.warn('⚠️ Select de usuarios no encontrado');
        return;
    }
    
    try {
        const respuesta = await fetch('/final_armamento/usuarios/usuariosAPI');
        console.log('📡 Respuesta usuarios:', respuesta.status);
        
        if (!respuesta.ok) {
            throw new Error(`HTTP ${respuesta.status}`);
        }
        
        const datos = await respuesta.json();
        console.log('📦 Datos usuarios:', datos);
        
        const { codigo, mensaje, data } = datos;

        if (codigo == 1) {
            SelectUsuario.innerHTML = '<option value="">Todos los usuarios</option>';
            if (data && Array.isArray(data) && data.length > 0) {
                data.forEach(usuario => {
                    if (usuario.id_usuario && usuario.nombre_completo) {
                        SelectUsuario.innerHTML += `<option value="${usuario.id_usuario}">${usuario.nombre_completo} (${usuario.nombre_usuario || 'N/A'})</option>`;
                    }
                });
                console.log(`✅ ${data.length} usuarios cargados`);
            } else {
                console.log('📭 No hay usuarios disponibles');
            }
        } else {
            console.error('❌ Error cargando usuarios:', mensaje);
        }

    } catch (error) {
        console.error('❌ Error en fetch usuarios:', error);
        SelectUsuario.innerHTML = '<option value="">Error cargando usuarios</option>';
    }
}

const CargarTiposActividad = async () => {
    console.log('📋 Cargando tipos de actividad...');
    
    if (!SelectTipo) {
        console.warn('⚠️ Select de tipos no encontrado');
        return;
    }
    
    try {
        const respuesta = await fetch('/final_armamento/historial/tiposActividadAPI');
        console.log('📡 Respuesta tipos:', respuesta.status);
        
        if (!respuesta.ok) {
            throw new Error(`HTTP ${respuesta.status}`);
        }
        
        const datos = await respuesta.json();
        console.log('📦 Datos tipos:', datos);
        
        const { codigo, mensaje, data } = datos;

        if (codigo == 1) {
            SelectTipo.innerHTML = '<option value="">Todos los tipos</option>';
            if (data && Array.isArray(data) && data.length > 0) {
                data.forEach(tipo => {
                    if (tipo.tipo_actividad) {
                        SelectTipo.innerHTML += `<option value="${tipo.tipo_actividad}">${tipo.tipo_actividad}</option>`;
                    }
                });
                console.log(`✅ ${data.length} tipos cargados`);
            } else {
                console.log('📭 No hay tipos disponibles');
                // Agregar tipos por defecto
                const tiposDefecto = ['LOGIN', 'LOGOUT', 'CREAR', 'EDITAR', 'ELIMINAR'];
                tiposDefecto.forEach(tipo => {
                    SelectTipo.innerHTML += `<option value="${tipo}">${tipo}</option>`;
                });
            }
        } else {
            console.error('❌ Error cargando tipos:', mensaje);
        }

    } catch (error) {
        console.error('❌ Error en fetch tipos:', error);
        SelectTipo.innerHTML = '<option value="">Error cargando tipos</option>';
    }
}

const CargarModulos = async () => {
    console.log('📦 Cargando módulos...');
    
    if (!SelectModulo) {
        console.warn('⚠️ Select de módulos no encontrado');
        return;
    }
    
    try {
        const respuesta = await fetch('/final_armamento/historial/modulosAPI');
        console.log('📡 Respuesta módulos:', respuesta.status);
        
        if (!respuesta.ok) {
            throw new Error(`HTTP ${respuesta.status}`);
        }
        
        const datos = await respuesta.json();
        console.log('📦 Datos módulos:', datos);
        
        const { codigo, mensaje, data } = datos;

        if (codigo == 1) {
            SelectModulo.innerHTML = '<option value="">Todos los módulos</option>';
            if (data && Array.isArray(data) && data.length > 0) {
                data.forEach(modulo => {
                    if (modulo.modulo) {
                        SelectModulo.innerHTML += `<option value="${modulo.modulo}">${modulo.modulo}</option>`;
                    }
                });
                console.log(`✅ ${data.length} módulos cargados`);
            } else {
                console.log('📭 No hay módulos disponibles');
                // Agregar módulos por defecto
                const modulosDefecto = ['SISTEMA', 'USUARIOS', 'MARCAS', 'MODELOS', 'ASIGNACIONES'];
                modulosDefecto.forEach(modulo => {
                    SelectModulo.innerHTML += `<option value="${modulo}">${modulo}</option>`;
                });
            }
        } else {
            console.error('❌ Error cargando módulos:', mensaje);
        }

    } catch (error) {
        console.error('❌ Error en fetch módulos:', error);
        SelectModulo.innerHTML = '<option value="">Error cargando módulos</option>';
    }
}

// Verificar que existe la tabla antes de inicializar DataTable
const tableElement = document.getElementById('TableHistorial');
if (!tableElement) {
    console.error('❌ Tabla #TableHistorial no encontrada');
} else {
    console.log('✅ Tabla encontrada, inicializando DataTable...');
    
    try {
        // DataTable mejorado con manejo de errores
        window.datatable = new DataTable('#TableHistorial', {
            language: lenguaje,
            data: [],
            processing: true,
            serverSide: false,
            pageLength: 10,
            responsive: true,
            columns: [
                {
                    title: 'No.',
                    data: 'id_actividad',
                    width: '5%',
                    render: (data, type, row, meta) => meta.row + 1
                },
                { 
                    title: 'Fecha/Hora', 
                    data: 'fecha_actividad_formato',
                    width: '15%',
                    render: (data, type, row) => {
                        return data ? `<small>${data}</small>` : '<small class="text-muted">N/A</small>';
                    }
                },
                { 
                    title: 'Usuario', 
                    data: 'nombre_usuario_display',
                    width: '20%',
                    render: (data, type, row) => {
                        return data || '<span class="text-muted">Usuario desconocido</span>';
                    }
                },
                { 
                    title: 'Tipo', 
                    data: 'tipo_actividad',
                    width: '15%',
                    render: (data, type, row) => {
                        if (!data) return '<span class="badge bg-secondary">N/A</span>';
                        
                        let badgeClass = '';
                        switch(data.toUpperCase()) {
                            case 'LOGIN':
                                badgeClass = 'bg-success';
                                break;
                            case 'LOGOUT':
                                badgeClass = 'bg-secondary';
                                break;
                            case 'CREAR':
                                badgeClass = 'bg-primary';
                                break;
                            case 'EDITAR':
                                badgeClass = 'bg-warning';
                                break;
                            case 'ELIMINAR':
                                badgeClass = 'bg-danger';
                                break;
                            default:
                                badgeClass = 'bg-info';
                        }
                        
                        return `<span class="badge ${badgeClass}">${data}</span>`;
                    }
                },
                { 
                    title: 'Módulo', 
                    data: 'modulo',
                    width: '15%',
                    render: (data, type, row) => {
                        return data || '<span class="text-muted">N/A</span>';
                    }
                },
                { 
                    title: 'Descripción', 
                    data: 'descripcion',
                    width: '25%',
                    render: (data, type, row) => {
                        if (!data) return '<span class="text-muted">Sin descripción</span>';
                        return data.length > 50 ? data.substring(0, 50) + '...' : data;
                    }
                },
                {
                    title: 'Acciones',
                    data: 'id_actividad',
                    width: '5%',
                    searchable: false,
                    orderable: false,
                    render: (data, type, row, meta) => {
                        if (!data) return '';
                        return `
                         <div class='d-flex justify-content-center'>
                             <button class='btn btn-info btn-sm ver-detalle' 
                                 data-id="${data}" 
                                 title="Ver detalles">
                                 <i class='bi bi-eye'></i>
                             </button>
                         </div>`;
                    }
                }
            ]
        });
        
        console.log('✅ DataTable inicializada correctamente');
        
    } catch (error) {
        console.error('❌ Error inicializando DataTable:', error);
    }
}

const VerDetalle = async (e) => {
    const idActividad = e.currentTarget.dataset.id;
    console.log('👁️ Viendo detalle:', idActividad);
    
    if (!idActividad) {
        console.error('❌ ID de actividad no válido');
        return;
    }
    
    try {
        const respuesta = await fetch(`/final_armamento/historial/detalleAPI?id=${idActividad}`);
        
        if (!respuesta.ok) {
            throw new Error(`HTTP ${respuesta.status}`);
        }
        
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;

        if (codigo == 1 && data) {
            MostrarModalDetalle(data);
        } else {
            await Swal.fire({
                position: "center",
                icon: "error",
                title: "Error",
                text: mensaje || 'No se pudo obtener el detalle',
                showConfirmButton: true,
            });
        }

    } catch (error) {
        console.error('❌ Error al obtener detalle:', error);
        await Swal.fire({
            position: "center",
            icon: "error",
            title: "Error de conexión",
            text: `No se pudo obtener el detalle: ${error.message}`,
            showConfirmButton: true,
        });
    }
}

const MostrarModalDetalle = (data) => {
    if (!data) {
        console.error('❌ No hay datos para mostrar en el modal');
        return;
    }
    
    let html = `
        <div class="row">
            <div class="col-md-6">
                <strong>ID:</strong> ${data.id_actividad || 'N/A'}<br>
                <strong>Usuario:</strong> ${data.nombre_completo || 'N/A'}<br>
                <strong>Tipo:</strong> <span class="badge bg-primary">${data.tipo_actividad || 'N/A'}</span><br>
                <strong>Módulo:</strong> ${data.modulo || 'N/A'}<br>
            </div>
            <div class="col-md-6">
                <strong>Fecha:</strong> ${data.fecha_actividad ? new Date(data.fecha_actividad).toLocaleString() : 'N/A'}<br>
                <strong>IP:</strong> ${data.ip_usuario || 'N/A'}<br>
                <strong>Tabla:</strong> ${data.tabla_afectada || 'N/A'}<br>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <strong>Descripción:</strong><br>
                <div class="bg-light p-2 rounded">${data.descripcion || 'Sin descripción'}</div>
            </div>
        </div>
    `;
    
    const modalContent = document.getElementById('ContenidoDetalles');
    if (modalContent) {
        modalContent.innerHTML = html;
        
        try {
            const modal = new Modal(document.getElementById('ModalDetalles'));
            modal.show();
        } catch (error) {
            console.error('❌ Error mostrando modal:', error);
        }
    } else {
        console.error('❌ Elemento ContenidoDetalles no encontrado');
    }
}

const LimpiarFiltros = () => {
    console.log('🧹 Limpiando filtros...');
    if (FormFiltros) {
        FormFiltros.reset();
    }
    EstablecerFechasPorDefecto();
    BuscarHistorial();
}

const EstablecerFechasPorDefecto = () => {
    if (FechaHasta && FechaDesde) {
        try {
            const hoy = new Date();
            FechaHasta.value = hoy.toISOString().split('T')[0];
            
            const hace7Dias = new Date();
            hace7Dias.setDate(hace7Dias.getDate() - 7);
            FechaDesde.value = hace7Dias.toISOString().split('T')[0];
            
            console.log('📅 Fechas establecidas:', FechaDesde.value, 'a', FechaHasta.value);
        } catch (error) {
            console.error('❌ Error estableciendo fechas:', error);
        }
    }
}

// Función de inicialización segura
const inicializar = async () => {
    console.log('🚀 Iniciando carga de datos...');
    
    try {
        EstablecerFechasPorDefecto();
        
        // Cargar datos en paralelo
        await Promise.allSettled([
            CargarUsuarios(),
            CargarTiposActividad(),
            CargarModulos()
        ]);
        
        console.log('✅ Datos de filtros cargados');
        
        // Buscar historial después de cargar filtros
        setTimeout(() => {
            BuscarHistorial();
        }, 500);
        
    } catch (error) {
        console.error('❌ Error en inicialización:', error);
    }
}

// Event Listeners con verificación
if (window.datatable) {
    window.datatable.on('click', '.ver-detalle', VerDetalle);
}

if (BtnBuscar) {
    BtnBuscar.addEventListener('click', () => BuscarHistorial(true));
    console.log('✅ Event listener BtnBuscar agregado');
}

if (BtnLimpiarFiltros) {
    BtnLimpiarFiltros.addEventListener('click', LimpiarFiltros);
    console.log('✅ Event listener BtnLimpiarFiltros agregado');
}

if (BtnEstadisticas) {
    BtnEstadisticas.addEventListener('click', () => {
        Swal.fire({
            icon: 'info',
            title: 'Estadísticas',
            text: 'Funcionalidad en desarrollo'
        });
    });
    console.log('✅ Event listener BtnEstadisticas agregado');
}

if (BtnExportar) {
    BtnExportar.addEventListener('click', () => {
        Swal.fire({
            icon: 'info',
            title: 'Exportar',
            text: 'Funcionalidad en desarrollo'
        });
    });
    console.log('✅ Event listener BtnExportar agregado');
}

// Inicializar cuando el DOM esté listo
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', inicializar);
} else {
    inicializar();
}

console.log('✅ Historial de Actividades configurado completamente');