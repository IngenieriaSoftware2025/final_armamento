<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Gestión de Armamentos</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
            max-width: 450px;
            width: 100%;
        }

        .login-header {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 40px 30px 30px;
            text-align: center;
            position: relative;
        }

        .login-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 20"><defs><radialGradient id="a" cx="50%" cy="40%"><stop offset="0%" stop-color="%23ffffff" stop-opacity="0.1"/><stop offset="100%" stop-color="%23ffffff" stop-opacity="0"/></radialGradient></defs><rect width="100" height="20" fill="url(%23a)"/></svg>');
            opacity: 0.3;
        }

        .company-logo {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            border: 3px solid rgba(255, 255, 255, 0.2);
            animation: pulse 2s ease-in-out infinite;
        }

        .company-logo i {
            font-size: 2.5rem;
            color: #fff;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .company-name {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 5px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .company-tagline {
            font-size: 0.95rem;
            opacity: 0.9;
            font-weight: 300;
        }

        .login-body {
            padding: 40px 30px;
        }

        .form-floating {
            margin-bottom: 20px;
        }

        .form-floating input {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 12px 15px;
            height: auto;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-floating input:focus {
            border-color: #1e3c72;
            box-shadow: 0 0 0 0.2rem rgba(30, 60, 114, 0.25);
            transform: translateY(-2px);
        }

        .form-floating label {
            padding: 12px 15px;
            color: #6c757d;
        }

        .input-group-text {
            background: transparent;
            border: 2px solid #e9ecef;
            border-right: none;
            border-radius: 12px 0 0 12px;
            color: #6c757d;
        }

        .btn-login {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            border: none;
            border-radius: 12px;
            padding: 15px;
            font-size: 1.1rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(30, 60, 114, 0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .floating-elements {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
        }

        .floating-weapon {
            position: absolute;
            color: rgba(255, 255, 255, 0.1);
            animation: float 6s ease-in-out infinite;
        }

        .floating-weapon:nth-child(1) {
            top: 10%;
            left: 10%;
            font-size: 2rem;
            animation-delay: 0s;
        }

        .floating-weapon:nth-child(2) {
            top: 20%;
            right: 15%;
            font-size: 1.5rem;
            animation-delay: 2s;
        }

        .floating-weapon:nth-child(3) {
            bottom: 30%;
            left: 5%;
            font-size: 1.8rem;
            animation-delay: 4s;
        }

        .floating-weapon:nth-child(4) {
            bottom: 15%;
            right: 10%;
            font-size: 2.2rem;
            animation-delay: 1s;
        }

        .floating-weapon:nth-child(5) {
            top: 50%;
            left: 3%;
            font-size: 1.6rem;
            animation-delay: 3s;
        }

        .floating-weapon:nth-child(6) {
            top: 60%;
            right: 5%;
            font-size: 1.9rem;
            animation-delay: 5s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }

        .footer-text {
            text-align: center;
            margin-top: 30px;
            color: rgba(30, 60, 114, 0.8);
            font-size: 0.9rem;
            font-weight: 500;
        }

        .security-badge {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-top: 15px;
            display: inline-block;
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
        }

        @media (max-width: 576px) {
            .login-card {
                margin: 10px;
                border-radius: 15px;
            }
            
            .login-header {
                padding: 30px 20px 20px;
            }
            
            .login-body {
                padding: 30px 20px;
            }
            
            .company-name {
                font-size: 1.5rem;
            }
        }

        /* Loading animation */
        .btn-login.loading {
            position: relative;
            color: transparent;
        }

        .btn-login.loading::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            top: 50%;
            left: 50%;
            margin: -10px 0 0 -10px;
            border: 2px solid #ffffff;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .alert-security {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            border: 1px solid #f6d55c;
            color: #856404;
            border-radius: 10px;
            padding: 12px 15px;
            margin-bottom: 20px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
        }

        .alert-security i {
            margin-right: 10px;
            color: #f39c12;
        }
    </style>
</head>
<body>
    <!-- Elementos flotantes decorativos -->
    <div class="floating-elements">
        <i class="fas fa-crosshairs floating-weapon"></i>
        <i class="fas fa-shield-alt floating-weapon"></i>
        <i class="fas fa-user-shield floating-weapon"></i>
        <i class="fas fa-lock floating-weapon"></i>
        <i class="fas fa-eye floating-weapon"></i>
        <i class="fas fa-key floating-weapon"></i>
    </div>

    <div class="login-container">
        <div class="login-card">
            <!-- Header de la empresa -->
            <div class="login-header">
                <div class="company-logo">
                    <i class="fas fa-shield-alt"></i>
                </div>

                <h1 class="company-name">Usuario  paolita</h1>
                <p class="company-tagline">Contraseña 12345678</p>


                <h1 class="company-name">🛡️ Sistema López</h1>
                <p class="company-tagline">Control y Gestión de Armamentos Institucional</p>
                <div class="security-badge">
                    <i class="fas fa-lock me-1"></i>ACCESO RESTRINGIDO
                </div>
            </div>

            <!-- Formulario de login -->
            <div class="login-body">
                <!-- Alerta de seguridad -->
                <div class="alert-security">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>Sistema de acceso controlado. Solo personal autorizado.</span>
                </div>

                <form id="FormLogin">
                    <div class="form-floating">
                        <input type="text" 
                               class="form-control" 
                               id="nombre_usuario" 
                               name="nombre_usuario" 
                               placeholder="Usuario" 
                               required>
                        <label for="nombre_usuario">
                            <i class="fas fa-user-shield me-2"></i>Código de Usuario
                        </label>
                    </div>

                    <div class="form-floating">
                        <input type="password" 
                               class="form-control" 
                               id="password" 
                               name="password" 
                               placeholder="Contraseña" 
                               required>
                        <label for="password">
                            <i class="fas fa-key me-2"></i>Clave de Acceso
                        </label>
                    </div>

                    <button type="submit" 
                            class="btn btn-login w-100" 
                            id="BtnIniciar">
                        <i class="fas fa-sign-in-alt me-2"></i>Acceder al Sistema
                    </button>
                </form>

                <div class="footer-text">
                    <i class="fas fa-shield-alt me-1"></i>
                    Acceso seguro y monitorizado
                    <br>
                    <small class="text-muted mt-2 d-block">
                        <i class="fas fa-eye me-1"></i>
                        Todos los accesos son registrados
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div style="position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%); color: rgba(255,255,255,0.8); font-size: 0.85rem; text-align: center;">
        <i class="fas fa-copyright me-1"></i>2025 Sistema López - Gestión de Armamentos Institucional
        <br>
        <small style="opacity: 0.7;">
            <i class="fas fa-shield-alt me-1"></i>Sistema de Seguridad Nivel Alto
        </small>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // Simulación del comportamiento del botón
        document.getElementById('FormLogin').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const btn = document.getElementById('BtnIniciar');
            const originalText = btn.innerHTML;
            
            btn.classList.add('loading');
            btn.disabled = true;
            
            // Simular proceso de login
            setTimeout(() => {
                btn.classList.remove('loading');
                btn.disabled = false;
                btn.innerHTML = originalText;
                
                // Aquí iría tu lógica de login real
                // window.location.href = '/proyecto_lopez/dashboard';
                
                // Simulación de éxito
                btn.innerHTML = '<i class="fas fa-check me-2"></i>Acceso Autorizado';
                btn.style.background = 'linear-gradient(135deg, #28a745 0%, #20c997 100%)';
                
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.style.background = 'linear-gradient(135deg, #1e3c72 0%, #2a5298 100%)';
                }, 2000);
                
            }, 2000);
        });

        // Efecto de enfoque automático
        window.addEventListener('load', function() {
            document.getElementById('nombre_usuario').focus();
        });

        // Validación de seguridad visual
        document.getElementById('nombre_usuario').addEventListener('input', function() {
            const value = this.value;
            if (value.length > 0) {
                this.style.borderColor = '#28a745';
            } else {
                this.style.borderColor = '#e9ecef';
            }
        });

        document.getElementById('password').addEventListener('input', function() {
            const value = this.value;
            if (value.length >= 6) {
                this.style.borderColor = '#28a745';
            } else if (value.length > 0) {
                this.style.borderColor = '#ffc107';
            } else {
                this.style.borderColor = '#e9ecef';
            }
        });

        // Efecto de parpadeo en elementos de seguridad
        setInterval(() => {
            const securityElements = document.querySelectorAll('.security-badge, .alert-security i');
            securityElements.forEach(el => {
                el.style.opacity = el.style.opacity === '0.7' ? '1' : '0.7';
            });
        }, 3000);
    </script>
</body>
</html>

<script src="<?= asset('build/js/login/login.js') ?>"></script>