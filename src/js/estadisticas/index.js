import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import { validarFormulario } from "../funciones";
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";
import { Chart } from "chart.js/auto";

// ELEMENTOS DEL DOM PARA LAS GRÁFICAS DE ARMAMENTOS
const graficoTipos = document.getElementById("graficoTipos").getContext("2d");
const graficoUsuarios = document.getElementById("graficoUsuarios").getContext("2d");
const graficoMarcas = document.getElementById("graficoMarcas").getContext("2d");
const graficoAsignacionesMes = document.getElementById("graficoAsignacionesMes").getContext("2d");

// FUNCIÓN DE COLORES (la misma que usas)
function getColorForEstado(cantidad) {
    let color = "";
  
    if(cantidad > 5){
        color = "lightblue";
    } else if(cantidad > 2 && cantidad <= 5){
        color = 'lightpink';
    } else if(cantidad <= 2){
        color = 'mistyrose';
    }
   
    return color;
}

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

// CREAR LAS GRÁFICAS DE ARMAMENTOS
window.graficaTiposArmamentos = new Chart(graficoTipos, {
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
                text: 'Tipos de Armamentos Más Asignados'
            },
            legend: {
                display: false
            }
        }
    }
});

window.graficaUsuariosArmamentos = new Chart(graficoUsuarios, {
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
                text: 'Usuarios con Más Armamentos Asignados'
            }
        }
    }
});

window.graficaMarcasArmamentos = new Chart(graficoMarcas, {
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
                text: 'Marcas de Armamentos Más Populares'
            }
        }
    }
});

window.graficaAsignacionesMes = new Chart(graficoAsignacionesMes, {
    type: 'line',
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
                text: 'Asignaciones de Armamentos por Mes'
            }
        }
    }
});

// FUNCIÓN PARA BUSCAR TIPOS DE ARMAMENTOS MÁS ASIGNADOS
const BuscarTiposArmamentos = async () => {
    const url = '/final_armamento/estadisticas/buscarTiposArmamentosAPI';
    const config = {
        method: 'GET'
    }

    try {
        console.log('Iniciando búsqueda de tipos de armamentos...');
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;
        
        if (codigo == 1) {
            console.log('Tipos de armamentos:', data);
            
            // Tomar solo los top 10 para mejor visualización
            const topTipos = data.slice(0, 10);
            
            const etiquetasTipos = topTipos.map(d => `${d.tipo_armamento}\n(${d.marca} ${d.modelo})`);
            const cantidadAsignaciones = topTipos.map(d => parseInt(d.total_asignaciones));
            const asignacionesActivas = topTipos.map(d => parseInt(d.asignaciones_activas));
            
            if (window.graficaTiposArmamentos) {
                window.graficaTiposArmamentos.data.labels = etiquetasTipos;
                window.graficaTiposArmamentos.data.datasets = [
                    {
                        label: 'Total Asignaciones',
                        data: cantidadAsignaciones,
                        backgroundColor: cantidadAsignaciones.map(cantidad => getColorForArmamentos(cantidad)),
                        borderColor: 'rgba(0, 0, 0, 0.1)',
                        borderWidth: 1
                    }
                ];
                window.graficaTiposArmamentos.update();
                console.log('Gráfica de tipos de armamentos actualizada');
            }

        } else {
            console.error('Error en tipos de armamentos:', mensaje);
        }

    } catch (error) {
        console.error('Error al cargar tipos de armamentos:', error);
    }
}

// FUNCIÓN PARA BUSCAR USUARIOS CON MÁS ARMAMENTOS
const BuscarUsuariosArmamentos = async () => {
    const url = '/final_armamento/estadisticas/buscarUsuariosArmamentosAPI';
    const config = {
        method: 'GET'
    }

    try {
        console.log('Iniciando búsqueda de usuarios con armamentos...');
        const respuesta = await fetch(url, config);
        
        if (!respuesta.ok) {
            throw new Error(`HTTP error! status: ${respuesta.status}`);
        }
        
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;
        
        if (codigo == 1) {
            console.log('Usuarios con armamentos encontrados:', data);
            
            // Tomar solo los top 8 para mejor visualización
            const topUsuarios = data.slice(0, 8);
            
            const etiquetasUsuarios = topUsuarios.map(d => `${d.usuario}\n(${d.nombre_usuario})`);
            const armamentosActivos = topUsuarios.map(d => parseInt(d.armamentos_activos));
            
            if (window.graficaUsuariosArmamentos) {
                window.graficaUsuariosArmamentos.data.labels = etiquetasUsuarios;
                window.graficaUsuariosArmamentos.data.datasets = [{
                    label: 'Armamentos Activos',
                    data: armamentosActivos,
                    backgroundColor: armamentosActivos.map(cantidad => getColorForArmamentos(cantidad)),
                    borderColor: '#fff',
                    borderWidth: 2
                }];
                window.graficaUsuariosArmamentos.update();
                console.log('Gráfica de usuarios con armamentos actualizada');
            }

        } else {
            console.error('Error en usuarios con armamentos:', mensaje);
            await Swal.fire({
                position: "center",
                icon: "error",
                title: "Error",
                text: mensaje,
                showConfirmButton: true,
            });
        }

    } catch (error) {
        console.error('Error al cargar usuarios con armamentos:', error);
        await Swal.fire({
            position: "center",
            icon: "error",
            title: "Error de conexión",
            text: "No se pudo conectar con la API de usuarios: " + error.message,
            showConfirmButton: true,
        });
    }
}

// FUNCIÓN PARA BUSCAR MARCAS DE ARMAMENTOS MÁS POPULARES
const BuscarMarcasArmamentos = async () => {
    const url = '/final_armamento/estadisticas/buscarMarcasArmamentosAPI';
    const config = {
        method: 'GET'
    }

    try {
        console.log('Iniciando búsqueda de marcas de armamentos...');
        const respuesta = await fetch(url, config);
        
        if (!respuesta.ok) {
            throw new Error(`HTTP error! status: ${respuesta.status}`);
        }
        
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;
        
        if (codigo == 1) {
            console.log('Marcas de armamentos encontradas:', data);
            
            const etiquetasMarcas = data.map(d => d.marca);
            const totalAsignaciones = data.map(d => parseInt(d.total_asignaciones));
            const asignacionesActivas = data.map(d => parseInt(d.asignaciones_activas));
            
            if (window.graficaMarcasArmamentos) {
                window.graficaMarcasArmamentos.data.labels = etiquetasMarcas;
                window.graficaMarcasArmamentos.data.datasets = [{
                    label: 'Asignaciones Activas',
                    data: asignacionesActivas,
                    backgroundColor: asignacionesActivas.map(cantidad => getColorForArmamentos(cantidad)),
                    borderColor: '#fff',
                    borderWidth: 2
                }];
                window.graficaMarcasArmamentos.update();
                console.log('Gráfica de marcas de armamentos actualizada');
            }

        } else {
            console.error('Error en marcas de armamentos:', mensaje);
            await Swal.fire({
                position: "center",
                icon: "error",
                title: "Error",
                text: mensaje,
                showConfirmButton: true,
            });
        }

    } catch (error) {
        console.error('Error al cargar marcas de armamentos:', error);
        await Swal.fire({
            position: "center",
            icon: "error",
            title: "Error de conexión",
            text: "Error al obtener las marcas: " + error.message,
            showConfirmButton: true,
        });
    }
}

// FUNCIÓN PARA BUSCAR ASIGNACIONES POR MES
const BuscarAsignacionesMes = async () => {
    const url = '/final_armamento/estadisticas/buscarAsignacionesMesAPI';
    const config = {
        method: 'GET'
    }

    try {
        console.log('Iniciando búsqueda de asignaciones por mes...');
        const respuesta = await fetch(url, config);
        
        if (!respuesta.ok) {
            throw new Error(`HTTP error! status: ${respuesta.status}`);
        }
        
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;
        
        if (codigo == 1) {
            console.log('Asignaciones por mes encontradas:', data);
            
            const etiquetasMeses = data.map(d => d.mes);
            const totalAsignaciones = data.map(d => parseInt(d.total_asignaciones));
            const asignacionesActivas = data.map(d => parseInt(d.asignaciones_activas));
            const asignacionesRetiradas = data.map(d => parseInt(d.asignaciones_retiradas));
            const usuariosDiferentes = data.map(d => parseInt(d.usuarios_diferentes));
            
            if (window.graficaAsignacionesMes) {
                window.graficaAsignacionesMes.data.labels = etiquetasMeses;
                window.graficaAsignacionesMes.data.datasets = [
                    {
                        label: 'Total Asignaciones',
                        data: totalAsignaciones,
                        borderColor: '#007bff',
                        backgroundColor: 'rgba(0, 123, 255, 0.1)',
                        tension: 0.1,
                        pointBackgroundColor: totalAsignaciones.map(cantidad => getColorForArmamentos(cantidad))
                    },
                    {
                        label: 'Asignaciones Activas',
                        data: asignacionesActivas,
                        borderColor: '#28a745',
                        backgroundColor: 'rgba(40, 167, 69, 0.1)',
                        tension: 0.1,
                        pointBackgroundColor: asignacionesActivas.map(cantidad => getColorForArmamentos(cantidad))
                    },
                    {
                        label: 'Asignaciones Retiradas',
                        data: asignacionesRetiradas,
                        borderColor: '#dc3545',
                        backgroundColor: 'rgba(220, 53, 69, 0.1)',
                        tension: 0.1,
                        pointBackgroundColor: asignacionesRetiradas.map(cantidad => getColorForArmamentos(cantidad))
                    },
                    {
                        label: 'Usuarios Únicos',
                        data: usuariosDiferentes,
                        borderColor: '#ffc107',
                        backgroundColor: 'rgba(255, 193, 7, 0.1)',
                        tension: 0.1,
                        pointBackgroundColor: usuariosDiferentes.map(cantidad => getColorForArmamentos(cantidad))
                    }
                ];
                
                window.graficaAsignacionesMes.update();
                console.log('Gráfica de asignaciones por mes actualizada');
            }

        } else {
            console.error('Error en asignaciones por mes:', mensaje);
            await Swal.fire({
                position: "center",
                icon: "error",
                title: "Error",
                text: mensaje,
                showConfirmButton: true,
            });
        }

    } catch (error) {
        console.error('Error al cargar asignaciones por mes:', error);
        await Swal.fire({
            position: "center",
            icon: "error",
            title: "Error de conexión",
            text: "Error al obtener las asignaciones por mes: " + error.message,
            showConfirmButton: true,
        });
    }
}

// FUNCIÓN PARA MOSTRAR ESTADÍSTICAS GENERALES EN CARDS
const MostrarEstadisticasGenerales = async () => {
    const url = '/final_armamento/estadisticas/buscarEstadisticasGeneralesAPI';
    
    try {
        const respuesta = await fetch(url);
        const datos = await respuesta.json();
        
        if (datos.codigo == 1 && datos.data.length > 0) {
            const stats = datos.data[0];
            
            // Actualizar los cards de estadísticas
            const totalTipos = document.getElementById('totalTipos');
            const totalAsignaciones = document.getElementById('totalAsignaciones');
            const usuariosConArmamentos = document.getElementById('usuariosConArmamentos');
            const marcasDiferentes = document.getElementById('marcasDiferentes');
            
            if (totalTipos) totalTipos.textContent = stats.total_tipos_armamentos || 0;
            if (totalAsignaciones) totalAsignaciones.textContent = stats.asignaciones_activas || 0;
            if (usuariosConArmamentos) usuariosConArmamentos.textContent = stats.usuarios_con_armamentos || 0;
            if (marcasDiferentes) marcasDiferentes.textContent = stats.marcas_diferentes || 0;
            
            console.log('Estadísticas generales actualizadas:', stats);
        }
    } catch (error) {
        console.error('Error cargando estadísticas generales:', error);
    }
};

// FUNCIÓN PARA MOSTRAR TOP ASIGNADORES EN UNA TABLA
const MostrarTopAsignadores = async () => {
    const url = '/final_armamento/estadisticas/buscarTopAsignadorasAPI';
    
    try {
        const respuesta = await fetch(url);
        const datos = await respuesta.json();
        
        if (datos.codigo == 1) {
            const topAsignadores = datos.data;
            const tablaContainer = document.getElementById('topAsignadores');
            
            if (tablaContainer && topAsignadores.length > 0) {
                let html = `
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Top Usuarios que Asignan Armamentos</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Asignador</th>
                                            <th>Total Asignaciones</th>
                                            <th>Activas</th>
                                            <th>Usuarios Asignados</th>
                                            <th>Tipos Asignados</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                `;
                
                topAsignadores.forEach((asignador, index) => {
                    html += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>
                                <strong>${asignador.asignador}</strong><br>
                                <small class="text-muted">${asignador.nombre_usuario}</small>
                            </td>
                            <td><span class="badge bg-primary">${asignador.total_asignaciones_realizadas}</span></td>
                            <td><span class="badge bg-success">${asignador.asignaciones_activas_realizadas}</span></td>
                            <td><span class="badge bg-info">${asignador.usuarios_diferentes_asignados}</span></td>
                            <td><span class="badge bg-warning">${asignador.tipos_diferentes_asignados}</span></td>
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
                console.log('Tabla de top asignadores actualizada');
            }
        }
    } catch (error) {
        console.error('Error cargando top asignadores:', error);
    }
};

// FUNCIÓN PRINCIPAL PARA CARGAR TODAS LAS ESTADÍSTICAS
const CargarEstadisticasArmamentos = () => {
    console.log('Iniciando carga de estadísticas de armamentos...');
    
    // Cargar estadísticas generales primero
    MostrarEstadisticasGenerales();
    
    // Cargar gráficas con delays para mejor rendimiento
    BuscarTiposArmamentos();
    
    setTimeout(() => {
        BuscarUsuariosArmamentos();
    }, 500);
    
    setTimeout(() => {
        BuscarMarcasArmamentos();
    }, 1000);
    
    setTimeout(() => {
        BuscarAsignacionesMes();
    }, 1500);
    
    setTimeout(() => {
        MostrarTopAsignadores();
    }, 2000);
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
    MostrarEstadisticasGenerales();
}, 300000); // 5 minutos