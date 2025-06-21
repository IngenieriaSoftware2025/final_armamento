<div class="row justify-content-center p-3">
    <div class="col-lg-12">
        <div class="card custom-card shadow-lg" style="border-radius: 10px; border: 1px solid #007bff;">
            <div class="card-body p-3">
                <div class="row mb-3">
                    <h3 class="text-center text-success">FILTROS DE BÚSQUEDA</h3>
                </div>

                <div class="row justify-content-center p-3 shadow-lg">
                    <form id="FormFiltros">
                        <div class="row mb-3 justify-content-center">
                            <div class="col-lg-3">
                                <label for="filtro_usuario" class="form-label">USUARIO</label>
                                <select class="form-select" id="filtro_usuario" name="filtro_usuario">
                                    <option value="">Todos los usuarios</option>
                                </select>
                            </div>
                            <div class="col-lg-3">
                                <label for="filtro_tipo" class="form-label">TIPO DE ACTIVIDAD</label>
                                <select class="form-select" id="filtro_tipo" name="filtro_tipo">
                                    <option value="">Todos los tipos</option>
                                </select>
                            </div>
                            <div class="col-lg-3">
                                <label for="filtro_modulo" class="form-label">MÓDULO</label>
                                <select class="form-select" id="filtro_modulo" name="filtro_modulo">
                                    <option value="">Todos los módulos</option>
                                </select>
                            </div>
                            <div class="col-lg-3">
                                <label for="filtro_fecha_desde" class="form-label">FECHA DESDE</label>
                                <input type="date" class="form-control" id="filtro_fecha_desde" name="filtro_fecha_desde">
                            </div>
                        </div>

                        <div class="row mb-3 justify-content-center">
                            <div class="col-lg-3">
                                <label for="filtro_fecha_hasta" class="form-label">FECHA HASTA</label>
                                <input type="date" class="form-control" id="filtro_fecha_hasta" name="filtro_fecha_hasta">
                            </div>
                            <div class="col-lg-9 d-flex align-items-end">
                                <button class="btn btn-primary me-2" type="button" id="BtnBuscar">
                                    <i class="bi bi-search me-1"></i>Buscar
                                </button>
                                <button class="btn btn-secondary me-2" type="button" id="BtnLimpiarFiltros">
                                    <i class="bi bi-arrow-clockwise me-1"></i>Limpiar Filtros
                                </button>
                                <button class="btn btn-info me-2" type="button" id="BtnEstadisticas">
                                    <i class="bi bi-bar-chart me-1"></i>Estadísticas
                                </button>
                                <button class="btn btn-warning" type="button" id="BtnExportar">
                                    <i class="bi bi-download me-1"></i>Exportar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Estadísticas Dashboard -->
<div class="row justify-content-center p-3 d-none" id="DashboardEstadisticas">
    <div class="col-lg-12">
        <div class="card custom-card shadow-lg" style="border-radius: 10px; border: 1px solid #28a745;">
            <div class="card-body p-3">
                <h3 class="text-center text-success">ESTADÍSTICAS DE ACTIVIDAD</h3>
                
                <div class="row mt-4">
                    <div class="col-lg-3">
                        <div class="card bg-primary text-white text-center">
                            <div class="card-body">
                                <h4 id="TotalActividades">0</h4>
                                <p>Total de Actividades</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class="card">
                            <div class="card-body">
                                <h5>Actividades por Tipo (Últimos 30 días)</h5>
                                <canvas id="ChartTipos" height="100"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <h5>Actividades por Módulo</h5>
                                <canvas id="ChartModulos" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <h5>Usuarios Más Activos</h5>
                                <div id="UsuariosActivos"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center p-3">
    <div class="col-lg-12">
        <div class="card custom-card shadow-lg" style="border-radius: 10px; border: 1px solid #007bff;">
            <div class="card-body p-3">
                <h3 class="text-center text-success">HISTORIAL DE ACTIVIDADES</h3>

                <div class="table-responsive p-2">
                    <table class="table table-striped table-hover table-bordered w-100 table-sm" id="TableHistorial">
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Modal para ver detalles -->
<div class="modal fade" id="ModalDetalles" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles de Actividad</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="ContenidoDetalles">
                <!-- Se cargará dinámicamente -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script src="<?= asset('build/js/historial/index.js') ?>"></script>    