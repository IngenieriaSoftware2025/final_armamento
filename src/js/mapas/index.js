// mapas/index.js - Sistema de Mapas para Asignaciones de Armamento
import { Dropdown } from "bootstrap";
// Variables globales
let mapa;
let marcadores = [];

// Coordenadas de Guatemala City (centro por defecto)
const GUATEMALA_CENTER = [14.6349, -90.5069];

// Datos de ejemplo de ubicaciones (posteriormente conectar con tu API)
const ubicacionesEjemplo = [
    {
        id: 1,
         nombre: "Brigada de Comunicaciones",
        lat: 14.57422,
        lng: -90.53342,
        tipo: "Area Militar",
        usuario: "BCE",
        estado: "activo",
        fecha_asignacion: "2025-01-10"
    },
    
];

// Función para inicializar el mapa
function inicializarMapa() {
    try {
        // Verificar si Leaflet está disponible
        if (typeof L === 'undefined') {
            console.error('Leaflet no está cargado. Agregando dinámicamente...');
            cargarLeaflet();
            return;
        }

        // Inicializar el mapa
        mapa = L.map('map').setView(GUATEMALA_CENTER, 11);
        
        // Agregar capa de mapa (OpenStreetMap)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 18,
            minZoom: 10
        }).addTo(mapa);
        
        // Cargar marcadores
        cargarMarcadores();
        
        // Agregar controles personalizados
        agregarControles();
        
        console.log('Mapa inicializado correctamente');
        
    } catch (error) {
        console.error('Error al inicializar el mapa:', error);
        mostrarErrorMapa();
    }
}

// Función para cargar Leaflet dinámicamente
function cargarLeaflet() {
    // Cargar CSS de Leaflet
    const link = document.createElement('link');
    link.rel = 'stylesheet';
    link.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
    document.head.appendChild(link);
    
    // Cargar JS de Leaflet
    const script = document.createElement('script');
    script.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
    script.onload = () => {
        console.log('Leaflet cargado dinámicamente');
        // Esperar un poco para que se inicialice completamente
        setTimeout(inicializarMapa, 500);
    };
    script.onerror = () => {
        console.error('Error al cargar Leaflet');
        mostrarErrorMapa();
    };
    document.head.appendChild(script);
}

// Función para cargar marcadores en el mapa
function cargarMarcadores() {
    // Limpiar marcadores existentes
    marcadores.forEach(marcador => mapa.removeLayer(marcador));
    marcadores = [];
    
    ubicacionesEjemplo.forEach(ubicacion => {
        const icono = obtenerIcono(ubicacion.tipo, ubicacion.estado);
        
        const marcador = L.marker([ubicacion.lat, ubicacion.lng], {
            icon: icono
        }).addTo(mapa);
        
        const popupContent = crearPopupContent(ubicacion);
        marcador.bindPopup(popupContent, {
            maxWidth: 300,
            className: 'custom-popup'
        });
        
        marcadores.push(marcador);
    });
}

// Función para obtener icono según tipo
function obtenerIcono(tipo, estado) {
    let color = '#007bff';
    let simbolo = '👤';
    
    switch(tipo) {
        case 'estacion':
            color = '#28a745';
            simbolo = '🪖';
            break;
        
    }
    
    if (estado === 'alerta') {
        color = '#ffc107';
    }
    
    return L.divIcon({
        className: 'custom-div-icon',
        html: `<div style="
            background-color: ${color}; 
            width: 35px; 
            height: 35px; 
            border-radius: 50%; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            color: white; 
            font-weight: bold; 
            border: 3px solid white; 
            box-shadow: 0 3px 8px rgba(0,0,0,0.3);
            font-size: 16px;
            cursor: pointer;
            transition: transform 0.2s ease;
        " onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
            ${simbolo}
        </div>`,
        iconSize: [35, 35],
        iconAnchor: [17, 17]
    });
}

// Función para crear contenido del popup
function crearPopupContent(ubicacion) {
    return `
        <div style="font-family: 'Segoe UI', Arial, sans-serif; padding: 10px;">
            <h6 style="color: #2c3e50; margin-bottom: 8px; font-weight: bold;">
                📍 ${ubicacion.nombre}
            </h6>
            
            <div style="color: #555; font-size: 14px; line-height: 1.5;">
                <p style="margin: 5px 0;">
                    <strong>👤 Usuario:</strong> ${ubicacion.usuario}
                </p>
                <p style="margin: 5px 0;">
                    <strong>🔫 Armamento:</strong> ${ubicacion.armamento}
                </p>
                <p style="margin: 5px 0;">
                    <strong>📅 Asignado:</strong> ${formatearFecha(ubicacion.fecha_asignacion)}
                </p>
                <p style="margin: 5px 0;">
                    <strong>📊 Estado:</strong> 
                    <span style="background: ${ubicacion.estado === 'activo' ? '#28a745' : '#ffc107'}; 
                                 color: white; 
                                 padding: 2px 8px; 
                                 border-radius: 12px; 
                                 font-size: 12px;">
                        ${ubicacion.estado.toUpperCase()}
                    </span>
                </p>
                <p style="margin: 5px 0; font-size: 12px; color: #666;">
                    <strong>🌍 Coordenadas:</strong> ${ubicacion.lat.toFixed(4)}, ${ubicacion.lng.toFixed(4)}
                </p>
            </div>
            
            <div style="margin-top: 10px; text-align: center;">
                <button onclick="verDetallesUbicacion(${ubicacion.id})" 
                        style="background: #007bff; color: white; border: none; padding: 5px 10px; 
                               border-radius: 4px; cursor: pointer; font-size: 12px; margin-right: 5px;">
                    Ver Detalles
                </button>
                <button onclick="actualizarUbicacion(${ubicacion.id})" 
                        style="background: #28a745; color: white; border: none; padding: 5px 10px; 
                               border-radius: 4px; cursor: pointer; font-size: 12px;">
                    Actualizar
                </button>
            </div>
        </div>
    `;
}

// Función para agregar controles personalizados
function agregarControles() {
    // Control para centrar el mapa
    const controlCentrar = L.control({position: 'topright'});
    controlCentrar.onAdd = function(map) {
        const div = L.DomUtil.create('div', 'leaflet-bar leaflet-control leaflet-control-custom');
        div.style.backgroundColor = 'white';
        div.style.width = '40px';
        div.style.height = '40px';
        div.style.cursor = 'pointer';
        div.style.display = 'flex';
        div.style.alignItems = 'center';
        div.style.justifyContent = 'center';
        div.innerHTML = '🎯';
        div.title = 'Centrar mapa en Guatemala';
        
        div.onclick = function(){
            map.setView(GUATEMALA_CENTER, 11);
        }
        
        return div;
    };
    controlCentrar.addTo(mapa);
    
    // Control de información
    const controlInfo = L.control({position: 'bottomleft'});
    controlInfo.onAdd = function(map) {
        const div = L.DomUtil.create('div', 'info-control');
        div.style.background = 'rgba(255,255,255,0.9)';
        div.style.padding = '10px';
        div.style.borderRadius = '5px';
        div.style.boxShadow = '0 2px 5px rgba(0,0,0,0.2)';
        div.style.fontSize = '12px';
        div.innerHTML = `
            <strong>📊 Resumen:</strong><br>
            👮 Usuarios: ${ubicacionesEjemplo.filter(u => u.tipo === 'usuario').length}<br>
            🏢 Estaciones: ${ubicacionesEjemplo.filter(u => u.tipo === 'estacion').length}<br>
            🔫 Total Armamentos: ${ubicacionesEjemplo.length}
        `;
        
        return div;
    };
    controlInfo.addTo(mapa);
}

// Función para mostrar error si el mapa no se puede cargar
function mostrarErrorMapa() {
    const mapContainer = document.getElementById('map');
    if (mapContainer) {
        mapContainer.innerHTML = `
            <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; 
                        height: 100%; background: #f8f9fa; border-radius: 8px; padding: 20px; text-align: center;">
                <div style="font-size: 48px; margin-bottom: 15px;">🗺️</div>
                <h5 style="color: #dc3545; margin-bottom: 10px;">Error al cargar el mapa</h5>
                <p style="color: #6c757d; margin-bottom: 15px;">
                    No se pudo inicializar el sistema de mapas.<br>
                    Verifique su conexión a internet.
                </p>
                <button onclick="location.reload()" style="background: #007bff; color: white; border: none; 
                        padding: 8px 16px; border-radius: 4px; cursor: pointer;">
                    🔄 Reintentar
                </button>
            </div>
        `;
    }
}

// Función para formatear fecha
function formatearFecha(fecha) {
    const date = new Date(fecha);
    return date.toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
}

// Funciones de interacción
function verDetallesUbicacion(id) {
    const ubicacion = ubicacionesEjemplo.find(u => u.id === id);
    if (ubicacion) {
        alert(`Detalles de: ${ubicacion.nombre}\n\nUsuario: ${ubicacion.usuario}\nArmamento: ${ubicacion.armamento}\nEstado: ${ubicacion.estado}\nFecha de asignación: ${ubicacion.fecha_asignacion}`);
    }
}

function actualizarUbicacion(id) {
    alert('Función de actualización - Conectar con tu sistema de asignaciones');
}

// Función para cargar datos reales desde tu API (opcional)
async function cargarUbicacionesReales() {
    try {
        const response = await fetch('/final_armamento/asignaciones/buscarAPI');
        const data = await response.json();
        
        if (data.codigo === 1) {
            // Convertir datos de asignaciones a formato de ubicaciones
            const ubicacionesReales = data.data.map((asignacion, index) => ({
                id: asignacion.id_asignacion,
                nombre: `Ubicación de ${asignacion.usuario}`,
                lat: GUATEMALA_CENTER[0] + (Math.random() - 0.5) * 0.1, // Coordenadas aleatorias cerca de Guatemala
                lng: GUATEMALA_CENTER[1] + (Math.random() - 0.5) * 0.1,
                tipo: 'usuario',
                usuario: asignacion.usuario,
                armamento: asignacion.armamento_completo + ' - Serie: ' + asignacion.numero_serie,
                estado: asignacion.estado.toLowerCase(),
                fecha_asignacion: asignacion.fecha_asignacion
            }));
            
            // Reemplazar datos de ejemplo con datos reales
            ubicacionesEjemplo.splice(0, ubicacionesEjemplo.length, ...ubicacionesReales);
            
            // Recargar marcadores
            if (mapa) {
                cargarMarcadores();
            }
        }
    } catch (error) {
        console.log('No se pudieron cargar datos reales, usando datos de ejemplo');
    }
}

// Inicializar cuando se carga la página
document.addEventListener('DOMContentLoaded', function() {
    console.log('Inicializando sistema de mapas...');
    
    // Intentar cargar datos reales primero
    cargarUbicacionesReales().finally(() => {
        // Inicializar mapa después de intentar cargar datos
        setTimeout(inicializarMapa, 100);
    });
});

// Asegurar que el mapa se redimensione correctamente
window.addEventListener('resize', function() {
    if (mapa) {
        setTimeout(() => {
            mapa.invalidateSize();
        }, 100);
    }
});