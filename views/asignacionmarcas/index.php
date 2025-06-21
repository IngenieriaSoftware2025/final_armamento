<div class="row justify-content-center p-3">
    <div class="col-lg-10">
        <div class="card custom-card shadow-lg" style="border-radius: 10px; border: 1px solid #007bff;">
            <div class="card-body p-3">
                <div class="row mb-3">
                      <h3 class="text-center text-success">ASIGNACION DE ARMAS</h3>
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
                                <label for="id_marca" class="form-label">SELECCIONE UN ARMA</label>
                                <select class="form-select" id="id_marca" name="id_marca" required>
                                    <option value="">Seleccione una marca</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3 justify-content-center">
                            <div class="col-lg-6">
                                <label for="usuario_asignador" class="form-label">ASIGNADO POR</label>
                                <select class="form-select" id="usuario_asignador" name="usuario_asignador" required>
                                    <option value="">Seleccione quién asigna</option>
                                </select>
                            </div>
                            <div class="col-lg-6">
                                <label for="observaciones" class="form-label">OBSERVACIONES</label>
                                <textarea class="form-control" id="observaciones" name="observaciones" rows="2" placeholder="Observaciones adicionales (opcional)"></textarea>
                            </div>
                        </div>

                        <div class="row justify-content-center mt-5">
                            <div class="col-auto">
                                <button class="btn btn-success" type="submit" id="BtnGuardar">
                                    <i class="bi bi-save me-1"></i>Guardar
                                </button>
                            </div>

                            <div class="col-auto ">
                                <button class="btn btn-warning d-none" type="button" id="BtnModificar">
                                    <i class="bi bi-pencil-square me-1"></i>Modificar
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
                <h3 class="text-center text-success">ASIGNACIONES DE ARMAS</h3>

                <div class="table-responsive p-2">
                    <table class="table table-striped table-hover table-bordered w-100 table-sm" id="TableAsignaciones">
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>


<script src="<?= asset('build/js/asignacionmarcas/index.js') ?>"></script>

