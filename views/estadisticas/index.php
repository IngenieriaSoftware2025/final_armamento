<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadísticas - Sistema de Armamento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid mt-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h1 class="text-primary">Estadísticas de Armamento</h1>
            </div>
        </div>

        <!-- Cards de Resumen General -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title">Total Usuarios</h4>
                                <h2 id="totalUsuarios">0</h2>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-users fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title">Total Marcas</h4>
                                <h2 id="totalMarcas">0</h2>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-tags fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title">Total Modelos</h4>
                                <h2 id="totalModelos">0</h2>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-cog fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title">Total Asignaciones</h4>
                                <h2 id="totalAsignaciones">0</h2>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-clipboard-list fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráficas Principales -->
        <div class="row mb-4">
            <!-- Gráfica de Modelos -->
            <div class="col-lg-6 col-md-12 mb-4">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Modelos Más Asignados</h5>
                    </div>
                    <div class="card-body">
                        <div style="height: 400px;">
                            <canvas id="graficoModelos"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráfica de Marcas -->
            <div class="col-lg-6 col-md-12 mb-4">
                <div class="card shadow">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Marcas Más Populares</h5>
                    </div>
                    <div class="card-body">
                        <div style="height: 400px;">
                            <canvas id="graficoMarcas"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <!-- Gráfica de Usuarios -->
            <div class="col-lg-6 col-md-12 mb-4">
                <div class="card shadow">
                    <div class="card-header bg-warning text-white">
                        <h5 class="mb-0">Usuarios del Sistema</h5>
                    </div>
                    <div class="card-body">
                        <div style="height: 400px;">
                            <canvas id="graficoUsuarios"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráfica de Asignaciones -->
            <div class="col-lg-6 col-md-12 mb-4">
                <div class="card shadow">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0">Top Usuarios con Más Armamentos</h5>
                    </div>
                    <div class="card-body">
                        <div style="height: 400px;">
                            <canvas id="graficoAsignaciones"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de Estadísticas por Rol -->
        <div class="row mb-4">
            <div class="col-12">
                <div id="estadisticasRol"></div>
            </div>
        </div>

        <!-- Botón de Actualizar -->
        <div class="row mb-4">
            <div class="col-12 text-center">
                <button id="btnActualizarEstadisticas" class="btn btn-primary btn-lg">
                    <i class="fas fa-sync-alt"></i> Actualizar Estadísticas
                </button>
            </div>
        </div>

        <!-- Footer -->
        <div class="row">
            <div class="col-12 text-center">
                <p class="text-muted">Sistema de Armamento - Comando de Informática y Tecnología, 2025 ©</p>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js"></script>
    <script src="<?= asset('build/js/estadisticas/index.js') ?>"></script>
</body>
</html>