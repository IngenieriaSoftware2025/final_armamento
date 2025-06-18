<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <section class="vh-100 gradient-custom">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="login-card">
                        <div class="card-body p-5 text-center">
                            
                            <!-- Logo/Icono -->
                            <div class="login-icon mb-4">
                                <i class="fas fa-user-shield"></i>
                            </div>

                            <form id="FormLogin">
                                <div class="mb-md-5 mt-md-4 pb-4">

                                    <h2 class="fw-bold mb-4 login-title">Iniciar Sesión</h2>

                                    <!-- Campo DPI -->
                                    <div class="form-floating mb-4">
                                        <input 
                                            type="text" 
                                            name="usu_codigo" 
                                            id="usu_codigo" 
                                            class="form-control form-control-lg"
                                            placeholder="DPI"
                                            required
                                        />
                                        <label for="usu_codigo">
                                            <i class="fas fa-id-card me-2"></i>DPI
                                        </label>
                                    </div>

                                    <!-- Campo Contraseña -->
                                    <div class="form-floating mb-4 position-relative">
                                        <input 
                                            type="password" 
                                            name="usu_password" 
                                            id="usu_password" 
                                            class="form-control form-control-lg"
                                            placeholder="Contraseña"
                                            required
                                        />
                                        <label for="usu_password">
                                            <i class="fas fa-lock me-2"></i>Contraseña
                                        </label>
                                        <button 
                                            type="button" 
                                            class="btn-toggle-password"
                                            onclick="togglePassword()"
                                        >
                                            <i class="fas fa-eye" id="toggleIcon"></i>
                                        </button>
                                    </div>

                                    <!-- Checkbox Recordarme -->
                                    <div class="form-check mb-4 text-start">
                                        <input class="form-check-input" type="checkbox" id="remember_me">
                                        <label class="form-check-label text-light" for="remember_me">
                                            Recordarme
                                        </label>
                                    </div>

                                    <!-- Botón Ingresar -->
                                    <button type="submit" id="BtnIniciar" class="btn btn-login btn-lg w-100 mb-4">
                                        <span class="btn-text">
                                            <i class="fas fa-sign-in-alt me-2"></i>Ingresar
                                        </span>
                                        <span class="btn-loading d-none">
                                            <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                                            Ingresando...
                                        </span>
                                    </button>

                                    <!-- Link Olvidé Contraseña -->
                                    <div class="mb-4">
                                        <a href="#" class="forgot-password">¿Olvidaste tu contraseña?</a>
                                    </div>

                                    <!-- Redes Sociales -->
                                    <div class="social-section">
                                        <p class="social-text">O ingresa con:</p>
                                        <div class="d-flex justify-content-center">
                                            <a href="#" class="social-btn social-facebook">
                                                <i class="fab fa-facebook-f"></i>
                                            </a>
                                            <a href="#" class="social-btn social-google">
                                                <i class="fab fa-google"></i>
                                            </a>
                                            <a href="#" class="social-btn social-twitter">
                                                <i class="fab fa-twitter"></i>
                                            </a>
                                        </div>
                                    </div>

                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Toggle password visibility
        function togglePassword() {
            const passwordInput = document.getElementById('usu_password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
        
        // Form submission with loading state
        document.getElementById('FormLogin').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const btnSubmit = document.getElementById('BtnIniciar');
            const btnText = btnSubmit.querySelector('.btn-text');
            const btnLoading = btnSubmit.querySelector('.btn-loading');
            
            // Show loading state
            btnText.classList.add('d-none');
            btnLoading.classList.remove('d-none');
            btnSubmit.disabled = true;
            
            // Simulate login process
            setTimeout(() => {
                // Reset button
                btnText.classList.remove('d-none');
                btnLoading.classList.add('d-none');
                btnSubmit.disabled = false;
                
                // Your actual login logic here
                console.log('Login submitted');
            }, 2000);
        });
        
        // Simple form validation
        document.getElementById('usu_codigo').addEventListener('input', function() {
            if (this.value.length >= 13) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else if (this.value.length > 0) {
                this.classList.remove('is-valid');
                this.classList.add('is-invalid');
            }
        });
        
        document.getElementById('usu_password').addEventListener('input', function() {
            if (this.value.length >= 6) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else if (this.value.length > 0) {
                this.classList.remove('is-valid');
                this.classList.add('is-invalid');
            }
        });
    </script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
        }

        .gradient-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .login-card {
            background: rgba(0, 0, 0, 0.85);
            backdrop-filter: blur(10px);
            border-radius: 1.5rem;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            transition: transform 0.3s ease;
        }

        .login-card:hover {
            transform: translateY(-5px);
        }

        .login-icon {
            width: 70px;
            height: 70px;
            margin: 0 auto;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: white;
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }

        .login-title {
            color: white;
            font-weight: 600;
            font-size: 1.8rem;
            margin-bottom: 2rem;
        }

        .form-floating > .form-control {
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.2);
            color: white;
            font-size: 1rem;
            padding: 1rem 0.75rem;
            transition: all 0.3s ease;
        }

        .form-floating > .form-control:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            color: white;
        }

        .form-floating > .form-control::placeholder {
            color: transparent;
        }

        .form-floating > label {
            color: rgba(255, 255, 255, 0.8);
            font-weight: 500;
        }

        .form-floating > .form-control:focus ~ label,
        .form-floating > .form-control:not(:placeholder-shown) ~ label {
            color: #667eea;
            transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem);
        }

        .btn-toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.7);
            font-size: 1rem;
            cursor: pointer;
            z-index: 10;
            transition: color 0.3s ease;
        }

        .btn-toggle-password:hover {
            color: white;
        }

        .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }

        .form-check-input:focus {
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .btn-login {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            color: white;
            font-weight: 600;
            font-size: 1.1rem;
            padding: 0.8rem 2rem;
            border-radius: 0.8rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #5a67d8, #6b46c1);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login:disabled {
            opacity: 0.8;
            cursor: not-allowed;
            transform: none;
        }

        .forgot-password {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }

        .forgot-password:hover {
            color: #667eea;
            text-decoration: underline;
        }

        .social-section {
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            padding-top: 1.5rem;
            margin-top: 1rem;
        }

        .social-text {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .social-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            margin: 0 0.5rem;
            text-decoration: none;
            font-size: 1.2rem;
            transition: all 0.3s ease;
            border: 2px solid rgba(255, 255, 255, 0.2);
            color: rgba(255, 255, 255, 0.8);
        }

        
        /* Responsive */
        @media (max-width: 768px) {
            .login-card {
                margin: 1rem;
            }
            
            .card-body {
                padding: 2rem !important;
            }
            
            .login-title {
                font-size: 1.5rem;
            }
        }


        /* Animación suave */
        .login-card {
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</body>
</html>

<script src="<?= asset('build/js/login/login.js') ?>"></script>

