<div class="row justify-content-center p-3">
    <div class="col-lg-10">
        <div class="card custom-card shadow-lg" style="border-radius: 10px; border: 1px solid #007bff;">
            <div class="card-body p-3">
                <div class="row mb-3">
                      <h3 class="text-center text-success">REGISTRAR USUARIOS</h3>
                </div>

                <div class="row justify-content-center p-5 shadow-lg">

                    <form id="FormUsuarios" enctype="multipart/form-data">
                        <input type="hidden" id="id_usuario" name="id_usuario">

                        <div class="row mb-3 justify-content-center">
                            <div class="col-lg-6">
                                <label for="nombre_usuario" class="form-label">NOMBRE DE USUARIO</label>
                                <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario" placeholder="Usuario de acceso" required>
                            </div>
                            <div class="col-lg-6">
                                <label for="password" class="form-label">CONTRASEÑA</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña" required>
                            </div>
                        </div>

                        <div class="row mb-3 justify-content-center">
                            <div class="col-lg-8">
                                <label for="nombre_completo" class="form-label">NOMBRE COMPLETO</label>
                                <input type="text" class="form-control" id="nombre_completo" name="nombre_completo" placeholder="Nombre completo del usuario" required>
                            </div>
                            <div class="col-lg-4">
                                <label for="id_rol" class="form-label">PERMISOS</label>
                                <select class="form-select" id="id_rol" name="id_rol" required>
                                    <option value="">Seleccione un Permiso</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3 justify-content-center">
                            <div class="col-lg-6">
                                <label for="email" class="form-label">CORREO</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="correo@ejemplo.com">
                            </div>
                            <div class="col-lg-6">
                                <label for="telefono" class="form-label">TELÉFONO</label>
                                <input type="text" class="form-control" id="telefono" name="telefono" placeholder="1234-5678">
                            </div>
                        </div>

                        <!-- NUEVA SECCIÓN PARA LA FOTO -->
                        <div class="row mb-3 justify-content-center">
                            <div class="col-lg-6">
                                <label for="foto" class="form-label">FOTO DE PERFIL</label>
                                <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                                <small class="form-text text-muted">Formatos permitidos: JPG, PNG, GIF. Tamaño máximo: 2MB</small>
                            </div>
                            <div class="col-lg-6">
                                <div id="preview-foto" class="text-center">
                                    <img id="imagen-preview" src="" alt="Vista previa" style="max-width: 150px; max-height: 150px; display: none; border-radius: 10px; border: 2px solid #ddd;">
                                </div>
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
                <h3 class="text-center text-success">USUARIOS REGISTRADOS</h3>

                <div class="table-responsive p-2">
                    <table class="table table-striped table-hover table-bordered w-100 table-sm" id="TableUsuarios">
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
// JavaScript para vista previa de imagen
document.getElementById('foto').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('imagen-preview');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none';
    }
});

// Limpiar preview al resetear formulario
document.getElementById('BtnLimpiar').addEventListener('click', function() {
    document.getElementById('imagen-preview').style.display = 'none';
});
</script>

<script src="<?= asset('build/js/usuarios/index.js') ?>"></script>

<style>
/* Estilos para la vista previa de imagen */
#preview-foto {
    border: 2px dashed #ddd;
    border-radius: 10px;
    padding: 20px;
    text-align: center;
    background-color: #f8f9fa;
}

#imagen-preview {
    max-width: 150px;
    max-height: 150px;
    border-radius: 10px;
    border: 2px solid #007bff;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

#imagen-preview:hover {
    transform: scale(1.05);
}

/* Estilos para las fotos en la tabla */
.table img {
    border: 2px solid #007bff;
    transition: transform 0.2s ease;
}

.table img:hover {
    transform: scale(1.1);
    cursor: pointer;
}

/* Estilo para el input de archivo */
input[type="file"] {
    border: 2px dashed #007bff;
    padding: 10px;
    border-radius: 8px;
    background-color: #f8f9fa;
}

input[type="file"]:focus {
    outline: none;
    border-color: #0056b3;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
}
</style>