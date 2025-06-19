<!-- AGREGAR ESTO A TU VISTA DE ESTADÍSTICAS O CREAR UNA NUEVA SECCIÓN -->

<div class="container-fluid py-4">
    <!-- Header con botón de actualizar -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">
                    <i class="bi bi-shield-check text-success me-2"></i>
                    Estadísticas de Armamentos
                </h2>
                <button id="btnActualizarEstadisticas" class="btn btn-primary">
                    <i class="bi bi-arrow-clockwise me-1"></i>
                    Actualizar Estadísticas
                </button>
            </div>
        </div>
    </div>

    <!-- Cards de estadísticas generales -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white h-100">
                <div class="card-body text-center">
                    <i class="bi bi-shield-fill-exclamation fs-1 mb-2"></i>
                    <h3 id="totalTipos" class="mb-1">0</h3>
                    <p class="card-text">Tipos de Armamentos</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white h-100">
                <div class="card-body text-center">
                    <i class="bi bi-check-circle-fill fs-1 mb-2"></i>
                    <h3 id="totalAsignaciones" class="mb-1">0</h3>
                    <p class="card-text">Asignaciones Activas</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white h-100">
                <div class="card-body text-center">
                    <i class="bi bi-people-fill fs-1 mb-2"></i>
                    <h3 id="usuariosConArmamentos" class="mb-1">0</h3>
                    <p class="card-text">Usuarios con Armamentos</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white h-100">
                <div class="card-body text-center">
                    <i class="bi bi-tags-fill fs-1 mb-2"></i>
                    <h3 id="marcasDiferentes" class="mb-1">0</h3>
                    <p class="card-text">Marcas Diferentes</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficas principales -->
    <div class="row mb-4">
        <!-- Gráfica de tipos de armamentos -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-bar-chart text-primary me-2"></i>
                        Tipos de Armamentos Más Asignados
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="graficoTipos" width="400" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Gráfica de usuarios con armamentos -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-pie-chart text-success me-2"></i>
                        Usuarios con Más Armamentos
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="graficoUsuarios" width="400" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <!-- Gráfica de marcas -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-diagram-3 text-warning me-2"></i>
                        Marcas Más Populares
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="graficoMarcas" width="400" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Gráfica de asignaciones por mes -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-graph-up text-info me-2"></i>
                        Asignaciones por Mes
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="graficoAsignacionesMes" width="400" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de top asignadores -->
    <div class="row mb-4">
        <div class="col-12">
            <div id="topAsignadores">
                <!-- El contenido se carga dinámicamente desde JavaScript -->
                <div class="card">
                    <div class="card-body text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="mt-2 text-muted">Cargando estadísticas de asignadores...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Información adicional -->
    <div class="row">
        <div class="col-12">
            <div class="card border-info">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        Información sobre las Estadísticas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6><strong>Código de Colores:</strong></h6>
                            <ul class="list-unstyled">
                                <li><span class="badge" style="background-color: #28a745;">Verde</span> - Más de 10 asignaciones</li>
                                <li><span class="badge" style="background-color: #ffc107; color: black;">Amarillo</span> - 6-10 asignaciones</li>
                                <li><span class="badge" style="background-color: #fd7e14;">Naranja</span> - 3-5 asignaciones</li>
                                <li><span class="badge" style="background-color: #dc3545;">Rojo</span> - 1-2 asignaciones</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6><strong>Actualización:</strong></h6>
                            <p class="small mb-0">
                                Las estadísticas se actualizan automáticamente cada 5 minutos.
                                También puedes usar el botón "Actualizar Estadísticas" para una actualización manual.
                            </p>
                            <p class="small text-muted mb-0 mt-2">
                                <i class="bi bi-clock me-1"></i>
                                Última actualización: <span id="ultimaActualizacion"><?= date('Y-m-d H:i:s') ?></span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Incluir Bootstrap Icons si no está incluido -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<!-- Script específico para estadísticas de armamentos --


<script src="<?= asset('build/js/asignaciones/index.js') ?>"></script>