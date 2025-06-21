<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="build/js/app.js"></script>
    <link rel="shortcut icon" href="<?= asset('images/cit.png') ?>" type="image/x-icon">
    <link rel="stylesheet" href="<?= asset('build/styles.css') ?>">
    <title>Sistema de Armamento - CIT</title>
    
    <style>
        /* Navbar moderna y funcional */
        .navbar {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 50%, #2c3e50 100%) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.3);
            border-bottom: 2px solid #3498db;
            position: relative;
            z-index: 1030; /* Alto z-index para navbar */
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.2rem;
            color: #ffffff !important;
            transition: all 0.3s ease;
        }
        
        .navbar-brand:hover {
            color: #3498db !important;
            transform: scale(1.02);
        }
        
        .navbar-nav .nav-link {
            color: #ecf0f1 !important;
            font-weight: 500;
            padding: 8px 15px !important;
            margin: 0 3px;
            border-radius: 8px;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .navbar-nav .nav-link:hover {
            color: #ffffff !important;
            background: rgba(52, 152, 219, 0.2);
            transform: translateY(-1px);
        }
        
        .navbar-nav .nav-link:before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: #3498db;
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }
        
        .navbar-nav .nav-link:hover:before {
            width: 80%;
        }
        
        /* DROPDOWN CORREGIDO Y LIMPIO */
        .navbar-nav .dropdown {
            position: relative;
        }
        
        .dropdown-menu {
            background: rgba(44, 62, 80, 0.95) !important;
            border: 1px solid rgba(52, 152, 219, 0.3);
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            backdrop-filter: blur(10px);
            margin-top: 0 !important;
            padding: 8px 0;
            min-width: 200px;
            z-index: 1050 !important;
            position: absolute !important;
            top: 100% !important;
            left: 0 !important;
            transform: none !important;
        }
        
        /* Mostrar dropdown al hacer hover o click */
        .dropdown-menu.show {
            display: block !important;
        }
        
        .dropdown-item {
            color: #ecf0f1 !important;
            padding: 8px 16px !important;
            border-radius: 6px;
            margin: 2px 6px;
            transition: all 0.2s ease;
            font-weight: 500;
            white-space: nowrap;
            display: flex !important;
            align-items: center;
            font-size: 0.9rem;
        }
        
        .dropdown-item:hover,
        .dropdown-item:focus {
            background: linear-gradient(45deg, #3498db, #2980b9) !important;
            color: #ffffff !important;
            transform: none;
            outline: none;
        }
        
        .dropdown-divider {
            border-color: #34495e;
            margin: 8px 12px;
        }
        
        /* Botón MENÚ */
        .btn-menu {
            background: linear-gradient(45deg, #e74c3c, #c0392b);
            border: none;
            border-radius: 20px;
            font-weight: 600;
            padding: 8px 20px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(231, 76, 60, 0.3);
            color: white;
        }
        
        .btn-menu:hover {
            background: linear-gradient(45deg, #c0392b, #a93226);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(231, 76, 60, 0.4);
            color: white;
        }
        
        /* Barra de progreso */
        .progress-bar {
            background: linear-gradient(90deg, #3498db, #2980b9, #3498db);
            background-size: 200% 100%;
            animation: wave 2s ease-in-out infinite;
        }
        
        @keyframes wave {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        
        /* Iconos */
        .navbar-nav .nav-link i {
            margin-right: 8px;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }
        
        .navbar-nav .nav-link:hover i {
            transform: scale(1.1);
            color: #3498db;
        }
        
        .dropdown-item i {
            margin-right: 8px;
            font-size: 1rem;
            color: #3498db;
            width: 16px;
            text-align: center;
        }
        
        /* Contenido principal */
        .main-content {
            position: relative;
            z-index: 1;
            margin-top: 0;
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .navbar-nav .nav-link {
                text-align: center;
                margin: 2px 0;
            }
            
            .dropdown-menu {
                text-align: center;
                position: relative !important;
                width: 100% !important;
                box-shadow: none;
                border: none;
                background: rgba(52, 73, 94, 0.9) !important;
            }
        }
        
        /* Footer mejorado */
        .footer-text {
            background: linear-gradient(45deg, #34495e, #2c3e50);
            color: #ecf0f1;
            padding: 15px 0;
            margin-top: 20px;
            border-top: 2px solid #3498db;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggler" aria-controls="navbarToggler" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <a class="navbar-brand" href="/final_armamento/">
            <img src="<?= asset('./images/cit.png') ?>" width="35px" alt="cit" class="me-2">
            Sistema de Armamento
        </a>
        
        <div class="collapse navbar-collapse" id="navbarToggler">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                
                <li class="nav-item">
                    <a class="nav-link" href="/final_armamento/inicio">
                        <i class="bi bi-house-door-fill"></i>Inicio
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/final_armamento/usuarios">
                        <i class="bi bi-people-fill"></i>Usuarios
                    </a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" id="armamentoDropdown">
                        <i class="bi bi-shield-fill"></i>Armamento
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="armamentoDropdown">
                        <li>
                            <a class="dropdown-item" href="/final_armamento/marcas">
                                <i class="bi bi-tags-fill"></i>Tipos de Armamento
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="/final_armamento/modelos">
                                <i class="bi bi-gear-wide-connected"></i>Modelos de Armamento
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/final_armamento/asignacionmarcas">
                        <i class="bi bi-person-check-fill"></i>Asignaciones
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/final_armamento/estadisticas">
                        <i class="bi bi-graph-up-arrow"></i>Estadísticas
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/final_armamento/mapas">
                        <i class="bi bi-geo-alt-fill"></i>Mapas
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/final_armamento/roles">
                        <i class="bi bi-person-gear"></i>Roles
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/final_armamento/historial">
                        <i class="bi bi-clock-history"></i>Historial
                    </a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" id="opcionesDropdown">
                        <i class="bi bi-three-dots"></i>Más Opciones
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="opcionesDropdown">
                        <li>
                            <a class="dropdown-item" href="/aplicaciones/nueva">
                                <i class="bi bi-plus-circle-fill"></i>Nueva Función
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="#">
                                <i class="bi bi-gear-fill"></i>Configuración
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#">
                                <i class="bi bi-question-circle-fill"></i>Ayuda
                            </a>
                        </li>
                    </ul>
                </li>

            </ul> 
            
            <div class="d-grid">
                <a  href="/final_armamento/logout" class="btn btn-menu">
                    <i class="bi bi-arrow-bar-left me-1"></i>Cerrar Sesión
                </a>
            </div>

        </div>
    </div>
</nav>


<div style="margin-top: 70px;"></div>


<div class="progress fixed-bottom" style="height: 4px;">
    <div class="progress-bar progress-bar-animated" id="bar" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
</div>

<div class="container-fluid main-content pt-3 mb-4" style="min-height: 85vh">
    <?php echo $contenido; ?>
</div>

<div class="footer-text">
    <div class="container-fluid">
        <div class="row justify-content-center text-center">
            <div class="col-12">
                <p class="mb-0" style="font-size: 0.9rem; font-weight: 600;">
                    <i class="bi bi-shield-check me-2"></i>
                    Comando de Informática y Tecnología • <?= date('Y') ?> &copy; • Sistema de Armamento
                    <i class="bi bi-lock-fill ms-2"></i>
                </p>
            </div>
        </div>
    </div>
</div>

</body>
</html>