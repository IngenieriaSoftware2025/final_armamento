<div class="row justify-content-center p-3">
    <div class="col-lg-12">
        <div class="card custom-card shadow-lg" style="border-radius: 10px; border: 1px solid #007bff;">
            <div class="card-body p-3">
                <div class="row mb-3">
                    <h3 class="text-center text-success">ASIGNAR ARMAMENTO A USUARIOS</h3>
                </div>

                <div class="row justify-content-center p-5 shadow-lg">
                    <form id="FormAsignaciones">
                        <input type="hidden" id="id_asignacion" name="id_asignacion">

                        <div class="row mb-3 justify-content-center">
                            <div class="col-lg-6">
                                <label for="id_usuario" class="form-label">USUARIO</label>
                                <select class="form-select" id="id_usuario" name="id_usuario" required>
                                    <option value="">Seleccione un usuario</option>
                                </select>
                            </div>
                            <div class="col-lg-6">
                                <label for="id_modelo" class="form-label">MODELO DE ARMAMENTO</label>
                                <select class="form-select" id="id_modelo" name="id_modelo" required>
                                    <option value="">Seleccione un modelo</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3 justify-content-center">
                            <div class="col-lg-4">
                                <label for="numero_serie" class="form-label">NÚMERO DE SERIE</label>
                                <input type="text" class="form-control" id="numero_serie" name="numero_serie" 
                                       placeholder="Número de serie del armamento" required>
                            </div>
                            <div class="col-lg-4">
                                <label for="fecha_asignacion" class="form-label">FECHA DE ASIGNACIÓN</label>
                                <input type="date" class="form-control" id="fecha_asignacion" name="fecha_asignacion" required>
                            </div>
                            <div class="col-lg-4">
                                <label for="estado" class="form-label">ESTADO</label>
                                <select class="form-select" id="estado" name="estado" required>
                                    <option value="ASIGNADO">Asignado</option>
                                    <option value="DEVUELTO">Devuelto</option>
                                    <option value="PERDIDO">Perdido</option>
                                    <option value="DAÑADO">Dañado</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3 justify-content-center">
                            <div class="col-lg-6 d-none">
                                <label for="fecha_devolucion" class="form-label">FECHA DE DEVOLUCIÓN</label>
                                <input type="date" class="form-control" id="fecha_devolucion" name="fecha_devolucion">
                            </div>
                            <div class="col-lg-6">
                                <label for="observaciones" class="form-label">OBSERVACIONES</label>
                                <textarea class="form-control" id="observaciones" name="observaciones" 
                                          rows="3" placeholder="Observaciones adicionales..."></textarea>
                            </div>
                        </div>

                        <div class="row justify-content-center mt-5">
                            <div class="col-auto">
                                <button class="btn btn-success" type="submit" id="BtnGuardar">
                                    <i class="bi bi-save me-1"></i>Guardar Asignación
                                </button>
                            </div>

                            <div class="col-auto">
                                <button class="btn btn-warning d-none" type="button" id="BtnModificar">
                                    <i class="bi bi-pencil-square me-1"></i>Modificar Asignación
                                </button>
                            </div>

                            <div class="col-auto">
                                <button class="btn btn-secondary" type="reset" id="BtnLimpiar">
                                    <i class="bi bi-arrow-clockwise me-1"></i>Limpiar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center p-3">
    <div class="col-lg-12">
        <div class="card custom-card shadow-lg" style="border-radius: 10px; border: 1px solid #007bff;">
            <div class="card-body p-3">
                <h3 class="text-center text-success">ASIGNACIONES DE ARMAMENTO</h3>

                <div class="table-responsive p-2">
                    <table class="table table-striped table-hover table-bordered w-100 table-sm" id="TableAsignaciones">
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= asset('build/js/asignaciones/index.js') ?>"></script>