import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import { validarFormulario } from "../funciones";
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";
import { Chart } from "chart.js/auto";

// ELEMENTOS DEL DOM PARA LAS GRÁFICAS DE ARMAMENTOS
const canvasModelos = document.getElementById("graficoModelos");
const canvasMarcas = document.getElementById("graficoMarcas");
const canvasUsuarios = document.getElementById("graficoUsuarios");
const canvasAsignaciones = document.getElementById("graficoAsignaciones");

// Verificar que los elementos existen antes de obtener el contexto
const graficoModelos = canvasModelos ? canvasModelos.getContext("2d") : null;
const graficoMarcas = canvasMarcas ? canvasMarcas.getContext("2d") : null;
const graficoUsuarios = canvasUsuarios ? canvasUsuarios.getContext("2d") : null;
const graficoAsignaciones = canvasAsignaciones ? canvasAsignaciones.getContext("2d") : null;

// FUNCIÓN DE COLORES ESPECÍFICA PARA ARMAMENTOS
function getColorForArmamentos(cantidad) {
    let color = "";
  
    if(cantidad > 10){
        color = "#28a745"; // Verde fuerte
    } else if(cantidad > 5 && cantidad <= 10){
        color = "#ffc107"; // Amarillo
    } else if(cantidad > 2 && cantidad <= 5){
        color = "#fd7e14"; // Naranja
    } else if(cantidad <= 2){
        color = "#dc3545"; // Rojo
    }
   
    return color;
}

// FUNCIÓN PARA GENERAR COLORES ALEATORIOS
function generarColoresAleatorios(cantidad) {
    const colores = [
        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', 
        '#9966FF', '#FF9F40', '#FF6384', '#C9CBCF',
        '#4BC0C0', '#FF6384', '#36A2EB', '#FFCE56'
    ];
    return Array.from({length: cantidad}, (_, i) => colores[i % colores.length]);
}

// CREAR LAS GRÁFICAS DE ARMAMENTOS SOLO SI LOS ELEMENTOS EXISTEN
if (graficoModelos) {
    window.graficaModelos = new Chart(graficoModelos, {
        type: 'bar',
        data: {
            labels: [],
            datasets: []
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Modelos Más Asignados'
                },
                legend: {
                    display: false
                }
            }
        }
    });
} else {
    console.warn('Elemento graficoModelos no encontrado');
}

if (graficoMarcas) {
    window.graficaMarcas = new Chart(graficoMarcas, {
        type: 'doughnut',
        data: {
            labels: [],
            datasets: []
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Marcas Más Populares'
                }
            }
        }
    });
} else {
    console.warn('Elemento graficoMarcas no encontrado');
}

if (graficoUsuarios) {
    window.graficaUsuarios = new Chart(graficoUsuarios, {
        type: 'polarArea',
        data: {
            labels: [],
            datasets: []
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Usuarios del Sistema'
                }
            }
        }
    });
} else {
    console.warn('Elemento graficoUsuarios no encontrado');
}

if (graficoAsignaciones) {
    window.graficaAsignaciones = new Chart(graficoAsignaciones, {
        type: 'bar',
        data: {
            labels: [],
            datasets: []
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Top Usuarios con Más Armamentos'
                },
                legend: {
                    display: false
                }
            }
        }
    });
} else {
    console.warn('Elemento graficoAsignaciones no encontrado');
}

// FUNCIÓN PARA BUSCAR MODELOS MÁS ASIGNADOS
const BuscarModelos = async () => {
    const url = '/final_armamento/estadisticas/buscarModelosAPI';
    
    try {
        console.log('🔍 Buscando modelos en:', url);
        const respuesta = await fetch(url);
        console.log('📡 Respuesta recibida, status:', respuesta.status);
        
        if (!respuesta.ok) {
            throw new Error(`HTTP ${respuesta.status}: ${respuesta.statusText}`);
        }
        
        const datos = await respuesta.json();
        console.log('📊 Datos de modelos:', datos);
        
        const { codigo, mensaje, data } = datos;
        
        if (codigo == 1) {
            console.log('✅ Modelos encontrados:', data.length, 'registros');
            
            if (data.length === 0) {
                console.warn('⚠️ No hay datos de modelos para mostrar');
                return;
            }
            
            // Tomar solo los top 10 para mejor visualización
            const topModelos = data.slice(0, 10);
            
            const etiquetasModelos = topModelos.map(d => d.modelo);
            const cantidadAsignaciones = topModelos.map(d => parseInt(d.cantidad_asignaciones));
            
            console.log('🏷️ Etiquetas:', etiquetasModelos);
            console.log('📈 Cantidades:', cantidadAsignaciones);
            
            if (window.graficaModelos) {
                window.graficaModelos.data.labels = etiquetasModelos;
                window.graficaModelos.data.datasets = [
                    {
                        label: 'Cantidad de Asignaciones',
                        data: cantidadAsignaciones,
                        backgroundColor: cantidadAsignaciones.map(cantidad => getColorForArmamentos(cantidad)),
                        borderColor: 'rgba(0, 0, 0, 0.1)',
                        borderWidth: 1
                    }
                ];
                window.graficaModelos.update();
                console.log('✅ Gráfica de modelos actualizada');
            } else {
                console.error('❌ window.graficaModelos no existe');
            }

        } else {
            console.error('❌ Error en respuesta de modelos:', mensaje);
            console.error('❌ Código de error:', codigo);
        }

    } catch (error) {
        console.error('❌ Error al cargar modelos:', error);
        console.error('❌ Tipo de error:', error.name);
        console.error('❌ Mensaje:', error.message);
    }
}

// FUNCIÓN PARA BUSCAR MARCAS MÁS ASIGNADAS
const BuscarMarcas = async () => {
    const url = '/final_armamento/estadisticas/buscarMarcasAPI';
    const config = {
        method: 'GET'
    }

    try {
        console.log('Iniciando búsqueda de marcas...');
        const respuesta = await fetch(url, config);
        
        if (!respuesta.ok) {
            throw new Error(`HTTP error! status: ${respuesta.status}`);
        }
        
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;
        
        if (codigo == 1) {
            console.log('Marcas encontradas:', data);
            
            const etiquetasMarcas = data.map(d => d.marca);
            const cantidadAsignaciones = data.map(d => parseInt(d.cantidad_asignaciones));
            
            if (window.graficaMarcas) {
                window.graficaMarcas.data.labels = etiquetasMarcas;
                window.graficaMarcas.data.datasets = [{
                    label: 'Cantidad de Asignaciones',
                    data: cantidadAsignaciones,
                    backgroundColor: generarColoresAleatorios(etiquetasMarcas.length),
                    borderColor: '#fff',
                    borderWidth: 2
                }];
                window.graficaMarcas.update();
                console.log('Gráfica de marcas actualizada');
            }

        } else {
            console.error('Error en marcas:', mensaje);
            await Swal.fire({
                position: "center",
                icon: "error",
                title: "Error",
                text: mensaje,
                showConfirmButton: true,
            });
        }

    } catch (error) {
        console.error('Error al cargar marcas:', error);
        await Swal.fire({
            position: "center",
            icon: "error",
            title: "Error de conexión",
            text: "No se pudo conectar con la API de marcas: " + error.message,
            showConfirmButton: true,
        });
    }
}

// FUNCIÓN PARA BUSCAR USUARIOS DEL SISTEMA
const BuscarUsuarios = async () => {
    const url = '/final_armamento/estadisticas/buscarUsuariosAPI';
    const config = {
        method: 'GET'
    }

    try {
        console.log('Iniciando búsqueda de usuarios...');
        const respuesta = await fetch(url, config);
        
        if (!respuesta.ok) {
            throw new Error(`HTTP error! status: ${respuesta.status}`);
        }
        
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;
        
        if (codigo == 1) {
            console.log('Usuarios encontrados:', data);
            
            // Tomar solo los top 8 para mejor visualización
            const topUsuarios = data.slice(0, 8);
            
            const etiquetasUsuarios = topUsuarios.map(d => `${d.usuario} (${d.rol})`);
            const armamentosAsignados = topUsuarios.map(d => parseInt(d.armamentos_asignados));
            
            if (window.graficaUsuarios) {
                window.graficaUsuarios.data.labels = etiquetasUsuarios;
                window.graficaUsuarios.data.datasets = [{
                    label: 'Armamentos Asignados',
                    data: armamentosAsignados,
                    backgroundColor: generarColoresAleatorios(etiquetasUsuarios.length),
                    borderColor: '#fff',
                    borderWidth: 2
                }];
                window.graficaUsuarios.update();
                console.log('Gráfica de usuarios actualizada');
            }

        } else {
            console.error('Error en usuarios:', mensaje);
            await Swal.fire({
                position: "center",
                icon: "error",
                title: "Error",
                text: mensaje,
                showConfirmButton: true,
            });
        }

    } catch (error) {
        console.error('Error al cargar usuarios:', error);
        await Swal.fire({
            position: "center",
            icon: "error",
            title: "Error de conexión",
            text: "Error al obtener los usuarios: " + error.message,
            showConfirmButton: true,
        });
    }
}

// FUNCIÓN PARA BUSCAR TOP USUARIOS CON MÁS ARMAMENTOS
const BuscarAsignaciones = async () => {
    const url = '/final_armamento/estadisticas/buscarAsignacionesAPI';
    const config = {
        method: 'GET'
    }

    try {
        console.log('Iniciando búsqueda de asignaciones...');
        const respuesta = await fetch(url, config);
        
        if (!respuesta.ok) {
            throw new Error(`HTTP error! status: ${respuesta.status}`);
        }
        
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;
        
        if (codigo == 1) {
            console.log('Asignaciones encontradas:', data);
            
            const etiquetasAsignaciones = data.map(d => d.usuario);
            const totalArmamentos = data.map(d => parseInt(d.total_armamentos));
            
            if (window.graficaAsignaciones) {
                window.graficaAsignaciones.data.labels = etiquetasAsignaciones;
                window.graficaAsignaciones.data.datasets = [
                    {
                        label: 'Total de Armamentos',
                        data: totalArmamentos,
                        backgroundColor: totalArmamentos.map(cantidad => getColorForArmamentos(cantidad)),
                        borderColor: 'rgba(0, 0, 0, 0.1)',
                        borderWidth: 1
                    }
                ];
                
                window.graficaAsignaciones.update();
                console.log('Gráfica de asignaciones actualizada');
            }

        } else {
            console.error('Error en asignaciones:', mensaje);
            await Swal.fire({
                position: "center",
                icon: "error",
                title: "Error",
                text: mensaje,
                showConfirmButton: true,
            });
        }

    } catch (error) {
        console.error('Error al cargar asignaciones:', error);
        await Swal.fire({
            position: "center",
            icon: "error",
            title: "Error de conexión",
            text: "Error al obtener las asignaciones: " + error.message,
            showConfirmButton: true,
        });
    }
}

// FUNCIÓN PARA MOSTRAR ESTADÍSTICAS GENERALES EN CARDS
const MostrarResumenGeneral = async () => {
    const url = '/final_armamento/estadisticas/buscarResumenGeneralAPI';
    
    try {
        const respuesta = await fetch(url);
        const datos = await respuesta.json();
        
        if (datos.codigo == 1 && datos.data) {
            const stats = datos.data;
            
            // Actualizar los cards de estadísticas
            const totalUsuarios = document.getElementById('totalUsuarios');
            const totalMarcas = document.getElementById('totalMarcas');
            const totalModelos = document.getElementById('totalModelos');
            const totalAsignaciones = document.getElementById('totalAsignaciones');
            
            if (totalUsuarios) totalUsuarios.textContent = stats.total_usuarios || 0;
            if (totalMarcas) totalMarcas.textContent = stats.total_marcas || 0;
            if (totalModelos) totalModelos.textContent = stats.total_modelos || 0;
            if (totalAsignaciones) totalAsignaciones.textContent = stats.total_asignaciones || 0;
            
            console.log('Resumen general actualizado:', stats);
        }
    } catch (error) {
        console.error('Error cargando resumen general:', error);
    }
};

// FUNCIÓN PARA MOSTRAR ESTADÍSTICAS POR ROL EN UNA TABLA
const MostrarEstadisticasRol = async () => {
    const url = '/final_armamento/estadisticas/buscarEstadisticasRolAPI';
    
    try {
        const respuesta = await fetch(url);
        const datos = await respuesta.json();
        
        if (datos.codigo == 1) {
            const estadisticasRol = datos.data;
            const tablaContainer = document.getElementById('estadisticasRol');
            
            if (tablaContainer && estadisticasRol.length > 0) {
                let html = `
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Estadísticas por Rol de Usuario</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Rol</th>
                                            <th>Total Usuarios</th>
                                            <th>Total Asignaciones</th>
                                            <th>Promedio por Usuario</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                `;
                
                estadisticasRol.forEach((rol, index) => {
                    const promedio = rol.total_usuarios > 0 ? (rol.total_asignaciones / rol.total_usuarios).toFixed(1) : 0;
                    html += `
                        <tr>
                            <td>${index + 1}</td>
                            <td><strong>${rol.rol}</strong></td>
                            <td><span class="badge bg-primary">${rol.total_usuarios}</span></td>
                            <td><span class="badge bg-success">${rol.total_asignaciones}</span></td>
                            <td><span class="badge bg-info">${promedio}</span></td>
                        </tr>
                    `;
                });
                
                html += `
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                `;
                
                tablaContainer.innerHTML = html;
                console.log('Tabla de estadísticas por rol actualizada');
            }
        }
    } catch (error) {
        console.error('Error cargando estadísticas por rol:', error);
    }
};

// FUNCIÓN PRINCIPAL PARA CARGAR TODAS LAS ESTADÍSTICAS
const CargarEstadisticasArmamentos = async () => {
    console.log('Iniciando carga de estadísticas de armamentos...');
    
    try {
        // Cargar resumen general primero
        console.log('Cargando resumen general...');
        await MostrarResumenGeneral();
        
        // Cargar gráficas
        console.log('Cargando modelos...');
        await BuscarModelos();
        
        console.log('Cargando marcas...');
        await BuscarMarcas();
        
        console.log('Cargando usuarios...');
        await BuscarUsuarios();
        
        console.log('Cargando asignaciones...');
        await BuscarAsignaciones();
        
        console.log('Cargando estadísticas por rol...');
        await MostrarEstadisticasRol();
        
        console.log('Todas las estadísticas cargadas correctamente');
    } catch (error) {
        console.error('Error cargando estadísticas:', error);
    }
};

// EVENTO PARA ACTUALIZAR ESTADÍSTICAS
const btnActualizarEstadisticas = document.getElementById('btnActualizarEstadisticas');
if (btnActualizarEstadisticas) {
    btnActualizarEstadisticas.addEventListener('click', () => {
        Swal.fire({
            title: 'Actualizando estadísticas...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
                CargarEstadisticasArmamentos();
                
                setTimeout(() => {
                    Swal.close();
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: "Estadísticas actualizadas",
                        showConfirmButton: false,
                        timer: 1500
                    });
                }, 3000);
            }
        });
    });
}

// INICIALIZAR AL CARGAR LA PÁGINA
document.addEventListener('DOMContentLoaded', () => {
    console.log('DOM cargado, iniciando estadísticas de armamentos...');
    CargarEstadisticasArmamentos();
});

// ACTUALIZACIÓN AUTOMÁTICA CADA 5 MINUTOS
setInterval(() => {
    console.log('Actualizando estadísticas automáticamente...');
    MostrarResumenGeneral();
}, 300000); // 5 minutos