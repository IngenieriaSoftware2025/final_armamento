import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import { validarFormulario } from "../funciones";
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";
import { Chart } from "chart.js/auto";

const grafico1 = document.getElementById("grafico1").getContext("2d");
const grafico2 = document.getElementById("grafico2").getContext("2d");
const grafico3 = document.getElementById("grafico3").getContext("2d");
const grafico4 = document.getElementById("grafico4").getContext("2d");


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

// Crear las gráficas
window.graficaProductos = new Chart(grafico1, {
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
                text: 'Productos Vendidos'
            }
        }
    }
});

window.graficaProductos2 = new Chart(grafico2, {
    type: 'pie',
    data: {
        labels: [],
        datasets: []
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: 'Distribución de Productos'
            }
        }
    }
});

window.graficaProductos3 = new Chart(grafico3, {
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
                text: 'Clientes con Más Productos Comprados'
            }
        }
    }
});

window.graficaProductos4 = new Chart(grafico4, {
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
                text: 'Ventas por Mes'
            }
        }
    }
});

// Función para buscar productos vendidos (tu función original)
const BuscarProductos = async () => {
    const url = '/final_armamento/estadisticas/buscarAPI';
    const config = {
        method: 'GET'
    }

    try {
        console.log('Iniciando búsqueda de productos...');
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;
        
        if (codigo == 1) {
            console.log('Productos:', data)
            const productos = [];
            const datosProductos = new Map();
            
            data.forEach(d => {
                if (!datosProductos.has(d.producto)) {
                    datosProductos.set(d.producto, d.cantidad);
                    productos.push({ 
                        producto: d.producto, 
                        pro_id: d.pro_id, 
                        cantidad: d.cantidad 
                    });
                }
            });
            
            const etiquetasProductos = [...new Set(data.map(d => d.producto))];
            
            const datasets = productos.map(e => ({
                label: e.producto,
                data: etiquetasProductos.map(productos => {
                    const match = data.find(d => d.producto === productos && e.producto === d.producto);
                    return match ? match.cantidad : 0;
                }),
                backgroundColor: getColorForEstado(e.cantidad)
            }));
            
            if (window.graficaProductos) {
                window.graficaProductos.data.labels = etiquetasProductos;
                window.graficaProductos.data.datasets = datasets;
                window.graficaProductos.update();
            }

            if (window.graficaProductos2) {
                window.graficaProductos2.data.labels = etiquetasProductos;
                window.graficaProductos2.data.datasets = [{
                    data: productos.map(p => p.cantidad),
                    backgroundColor: productos.map(p => getColorForEstado(p.cantidad))
                }];
                window.graficaProductos2.update();
            }

        } else {
            console.error('Error en productos:', mensaje);
        }

    } catch (error) {
        console.error('Error al cargar productos:', error);
    }
}

// Función para buscar clientes con más productos comprados
const BuscarClientes = async () => {
    const url = '/final_armamento/estadisticas/buscarClientesAPI';
    const config = {
        method: 'GET'
    }

    try {
        console.log('Iniciando búsqueda de clientes...');
        const respuesta = await fetch(url, config);
        
        if (!respuesta.ok) {
            throw new Error(`HTTP error! status: ${respuesta.status}`);
        }
        
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;
        
        if (codigo == 1) {
            console.log('Clientes encontrados:', data);
            
            const etiquetasClientes = data.map(d => d.cliente);
            const cantidadProductos = data.map(d => parseInt(d.total_productos));
            
            if (window.graficaProductos3) {
                window.graficaProductos3.data.labels = etiquetasClientes;
                window.graficaProductos3.data.datasets = [{
                    label: 'Productos Comprados',
                    data: cantidadProductos,
                    // USANDO LA MISMA FUNCIÓN DE COLORES
                    backgroundColor: cantidadProductos.map(cantidad => getColorForEstado(cantidad)),
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }];
                window.graficaProductos3.update();
                console.log('Gráfica de clientes actualizada');
            }

        } else {
            console.error('Error en clientes:', mensaje);
            await Swal.fire({
                position: "center",
                icon: "error",
                title: "Error",
                text: mensaje,
                showConfirmButton: true,
            });
        }

    } catch (error) {
        console.error('Error al cargar clientes:', error);
        await Swal.fire({
            position: "center",
            icon: "error",
            title: "Error de conexión",
            text: "No se pudo conectar con la API de clientes: " + error.message,
            showConfirmButton: true,
        });
    }
}

// Función para buscar ventas por mes
const BuscarVentasMes = async () => {
    const url = '/final_armamento/estadisticas/buscarVentasMesAPI';
    const config = {
        method: 'GET'
    }

    try {
        console.log('Iniciando búsqueda de ventas por mes...');
        const respuesta = await fetch(url, config);
        
        if (!respuesta.ok) {
            throw new Error(`HTTP error! status: ${respuesta.status}`);
        }
        
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;
        
        if (codigo == 1) {
            console.log('Ventas por mes encontradas:', data);
            
            const etiquetasMeses = data.map(d => d.mes);
            const totalVentas = data.map(d => parseInt(d.total_ventas));
            const totalIngresos = data.map(d => parseFloat(d.total_ingresos));
            
            if (window.graficaProductos4) {
                window.graficaProductos4.data.labels = etiquetasMeses;
                window.graficaProductos4.data.datasets = [
                    {
                        label: 'Número de Ventas',
                        data: totalVentas,
                        // USANDO LA MISMA FUNCIÓN DE COLORES para las líneas
                        borderColor: 'lightblue',
                        backgroundColor: 'rgba(173, 216, 230, 0.3)', // lightblue transparente
                        tension: 0.1,
                        pointBackgroundColor: totalVentas.map(cantidad => getColorForEstado(cantidad))
                    },
                    {
                        label: 'Ingresos ($)',
                        data: totalIngresos,
                        // Color consistente para ingresos
                        borderColor: 'lightpink',
                        backgroundColor: 'rgba(255, 182, 193, 0.3)', // lightpink transparente
                        tension: 0.1,
                        pointBackgroundColor: totalIngresos.map(ingreso => getColorForEstado(Math.round(ingreso/100))) // Ajustar escala para ingresos
                    }
                ];
                
                window.graficaProductos4.update();
                console.log('Gráfica de ventas por mes actualizada');
            }

        } else {
            console.error('Error en ventas por mes:', mensaje);
            await Swal.fire({
                position: "center",
                icon: "error",
                title: "Error",
                text: mensaje,
                showConfirmButton: true,
            });
        }

    } catch (error) {
        console.error('Error al cargar ventas por mes:', error);
        await Swal.fire({
            position: "center",
            icon: "error",
            title: "Error de conexión",
            text: "Error al obtener las ventas por mes: " + error.message,
            showConfirmButton: true,
        });
    }
}

// Llamar todas las funciones con delay para debug
console.log('Iniciando carga de gráficas...');
BuscarProductos();

setTimeout(() => {
    BuscarClientes();
}, 1000);

setTimeout(() => {
    BuscarVentasMes();
}, 2000);