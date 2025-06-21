<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión de Armamentos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); min-height: 100vh; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">

<!-- Estilos para dar vida -->
<style>
    /* Animaciones y efectos hover */
    .card:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 20px 40px rgba(30, 60, 114, 0.3) !important;
        transition: all 0.3s ease;
    }

    .btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    }

    /* Pulso en iconos */
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }

    .stat-icon {
        animation: pulse 2s ease-in-out infinite;
    }

    /* Rotación en hover */
    .service-icon:hover {
        transform: rotate(360deg);
        transition: transform 0.5s ease;
    }

    /* Efecto de brillo */
    .card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }

    .card:hover::before {
        left: 100%;
    }
</style>

<div class="container">
    <!-- Header -->
    <section style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); color: white; padding: 50px 0; margin-bottom: 40px; position: relative; overflow: hidden; border-radius: 20px; margin-top: 20px;">
        <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: url('data:image/svg+xml,<svg xmlns=&quot;http://www.w3.org/2000/svg&quot; viewBox=&quot;0 0 100 20&quot;><defs><radialGradient id=&quot;a&quot; cx=&quot;50%&quot; cy=&quot;40%&quot;><stop offset=&quot;0%&quot; stop-color=&quot;%23ffffff&quot; stop-opacity=&quot;0.1&quot;/><stop offset=&quot;100%&quot; stop-color=&quot;%23ffffff&quot; stop-opacity=&quot;0&quot;/></radialGradient></defs><rect width=&quot;100&quot; height=&quot;20&quot; fill=&quot;url(%23a)&quot;/></svg>'); opacity: 0.3;"></div>
        <div class="container" style="position: relative; z-index: 2;">
            <div class="text-center">
                <div style="width: 80px; height: 80px; background: rgba(255, 255, 255, 0.15); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; border: 3px solid rgba(255, 255, 255, 0.2); animation: pulse 2s ease-in-out infinite;">
                    <i class="fas fa-shield-alt" style="font-size: 2.2rem; color: #fff;"></i>
                </div>
                <h1 style="font-size: 2.8rem; font-weight: 700; margin-bottom: 15px; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);">
                     REGISTRO DE ARMAS  
                </h1>
               
                
                <!-- Estadísticas -->
                <div class="row" style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border-radius: 15px; padding: 30px; margin-top: 20px; box-shadow: 0 8px 32px rgba(0,0,0,0.1); border: 1px solid rgba(255, 255, 255, 0.2);">
                    <div class="col-md-3 col-6">
                        <div style="text-align: center; padding: 20px;">
                            <div class="stat-icon" style="margin-bottom: 10px;">
                                <i class="fas fa-crosshairs" style="font-size: 2rem; color: #ffd700;"></i>
                            </div>
                            <span style="font-size: 2.5rem; font-weight: 700; color: white; display: block; text-shadow: 0 2px 4px rgba(0,0,0,0.2);">127</span>
                            <div style="font-size: 0.9rem; color: rgba(255,255,255,0.8); margin-top: 8px; text-transform: uppercase; letter-spacing: 1px;">
                                🔫 Armamentos
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div style="text-align: center; padding: 20px;">
                            <div class="stat-icon" style="margin-bottom: 10px;">
                                <i class="fas fa-user-shield" style="font-size: 2rem; color: #00ff7f;"></i>
                            </div>
                            <span style="font-size: 2.5rem; font-weight: 700; color: white; display: block; text-shadow: 0 2px 4px rgba(0,0,0,0.2);">89</span>
                            <div style="font-size: 0.9rem; color: rgba(255,255,255,0.8); margin-top: 8px; text-transform: uppercase; letter-spacing: 1px;">
                                👥 Personal Activo
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div style="text-align: center; padding: 20px;">
                            <div class="stat-icon" style="margin-bottom: 10px;">
                                <i class="fas fa-handshake" style="font-size: 2rem; color: #ff6b6b;"></i>
                            </div>
                            <span style="font-size: 2.5rem; font-weight: 700; color: white; display: block; text-shadow: 0 2px 4px rgba(0,0,0,0.2);">45</span>
                            <div style="font-size: 0.9rem; color: rgba(255,255,255,0.8); margin-top: 8px; text-transform: uppercase; letter-spacing: 1px;">
                                📋 Asignaciones Hoy
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div style="text-align: center; padding: 20px;">
                            <div class="stat-icon" style="margin-bottom: 10px;">
                                <i class="fas fa-warehouse" style="font-size: 2rem; color: #4ecdc4;"></i>
                            </div>
                            <span style="font-size: 2.5rem; font-weight: 700; color: white; display: block; text-shadow: 0 2px 4px rgba(0,0,0,0.2);">15</span>
                            <div style="font-size: 0.9rem; color: rgba(255,255,255,0.8); margin-top: 8px; text-transform: uppercase; letter-spacing: 1px;">
                                🏪 En Almacén
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Servicios -->
    <div class="row mb-5" style="margin-top: -20px; position: relative; z-index: 3;">
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100" style="border-radius: 20px; box-shadow: 0 10px 30px rgba(30, 60, 114, 0.2); border: 1px solid rgba(30, 60, 114, 0.1); transition: all 0.3s ease; background: white; overflow: hidden; position: relative;">
                <div class="card-body text-center" style="padding: 40px 30px;">
                    <div class="service-icon" style="width: 70px; height: 70px; background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 25px; color: white; box-shadow: 0 8px 25px rgba(30, 60, 114, 0.3); transition: transform 0.5s ease;">
                        <i class="fas fa-handshake" style="font-size: 1.8rem;"></i>
                    </div>
                    <h5 style="font-weight: 600; color: #2c3e50; margin-bottom: 15px; font-size: 1.3rem;">
                        📋 Asignaciones de Armamentos
                    </h5>
                    <p style="color: #6c757d; font-size: 0.95rem; margin-bottom: 25px; line-height: 1.6;">
                        🎯 Gestiona las asignaciones de armamentos al personal autorizado y controla su retiro.
                    </p>
                    <a href="/proyecto_lopez/asignaciones" class="btn" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); color: white; border-radius: 25px; padding: 12px 30px; font-size: 0.9rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; transition: all 0.3s ease; border: none; text-decoration: none;">
                        <i class="fas fa-hand-holding me-2"></i>Gestionar
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100" style="border-radius: 20px; box-shadow: 0 10px 30px rgba(42, 82, 152, 0.2); border: 1px solid rgba(42, 82, 152, 0.1); transition: all 0.3s ease; background: white; overflow: hidden; position: relative;">
                <div class="card-body text-center" style="padding: 40px 30px;">
                    <div class="service-icon" style="width: 70px; height: 70px; background: linear-gradient(135deg, #2a5298 0%, #3b6bb8 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 25px; color: white; box-shadow: 0 8px 25px rgba(42, 82, 152, 0.3); transition: transform 0.5s ease;">
                        <i class="fas fa-crosshairs" style="font-size: 1.8rem;"></i>
                    </div>
                    <h5 style="font-weight: 600; color: #2c3e50; margin-bottom: 15px; font-size: 1.3rem;">
                        🔫 Tipos de Armamentos
                    </h5>
                    <p style="color: #6c757d; font-size: 0.95rem; margin-bottom: 25px; line-height: 1.6;">
                        🏷️ Registra y administra los diferentes tipos de armamentos disponibles en el sistema.
                    </p>
                    <a href="/proyecto_lopez/armamentos" class="btn" style="background: linear-gradient(135deg, #2a5298 0%, #3b6bb8 100%); color: white; border-radius: 25px; padding: 12px 30px; font-size: 0.9rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; transition: all 0.3s ease; border: none; text-decoration: none;">
                        <i class="fas fa-list me-2"></i>Ver Tipos
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100" style="border-radius: 20px; box-shadow: 0 10px 30px rgba(59, 107, 184, 0.2); border: 1px solid rgba(59, 107, 184, 0.1); transition: all 0.3s ease; background: white; overflow: hidden; position: relative;">
                <div class="card-body text-center" style="padding: 40px 30px;">
                    <div class="service-icon" style="width: 70px; height: 70px; background: linear-gradient(135deg, #3b6bb8 0%, #4c7dd8 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 25px; color: white; box-shadow: 0 8px 25px rgba(59, 107, 184, 0.3); transition: transform 0.5s ease;">
                        <i class="fas fa-users-cog" style="font-size: 1.8rem;"></i>
                    </div>
                    <h5 style="font-weight: 600; color: #2c3e50; margin-bottom: 15px; font-size: 1.3rem;">
                        👥 Gestión de Usuarios
                    </h5>
                    <p style="color: #6c757d; font-size: 0.95rem; margin-bottom: 25px; line-height: 1.6;">
                        🛡️ Administra el personal autorizado y controla los roles de acceso al sistema.
                    </p>
                    <a href="/proyecto_lopez/usuarios" class="btn" style="background: linear-gradient(135deg, #3b6bb8 0%, #4c7dd8 100%); color: white; border-radius: 25px; padding: 12px 30px; font-size: 0.9rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; transition: all 0.3s ease; border: none; text-decoration: none;">
                        <i class="fas fa-user-cog me-2"></i>Administrar
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Acciones y Actividad -->
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card" style="border-radius: 20px; box-shadow: 0 10px 30px rgba(30, 60, 114, 0.1); border: 1px solid rgba(30, 60, 114, 0.1); background: white; position: relative; overflow: hidden;">
                <div class="card-body" style="padding: 30px;">
                    <h5 style="color: #2c3e50; margin-bottom: 25px; font-weight: 600; font-size: 1.2rem;">
                        <i class="fas fa-bolt me-2" style="color: #1e3c72;"></i>⚡ Acciones Rápidas
                    </h5>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <a href="/proyecto_lopez/usuarios/nuevo" class="btn w-100" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); color: white; border-radius: 12px; padding: 15px 20px; font-size: 0.85rem; font-weight: 600; transition: all 0.3s ease; border: none; text-decoration: none;">
                                <i class="fas fa-user-plus me-2"></i>👤 Nuevo Usuario
                            </a>
                        </div>
                        <div class="col-6 mb-3">
                            <a href="/proyecto_lopez/asignaciones/nueva" class="btn w-100" style="background: linear-gradient(135deg, #2a5298 0%, #3b6bb8 100%); color: white; border-radius: 12px; padding: 15px 20px; font-size: 0.85rem; font-weight: 600; transition: all 0.3s ease; border: none; text-decoration: none;">
                                <i class="fas fa-plus-circle me-2"></i>📝 Nueva Asignación
                            </a>
                        </div>
                        <div class="col-6 mb-3">
                            <a href="/proyecto_lopez/armamentos/buscar" class="btn w-100" style="background: linear-gradient(135deg, #3b6bb8 0%, #4c7dd8 100%); color: white; border-radius: 12px; padding: 15px 20px; font-size: 0.85rem; font-weight: 600; transition: all 0.3s ease; border: none; text-decoration: none;">
                                <i class="fas fa-search me-2"></i>🔍 Buscar Armamento
                            </a>
                        </div>
                        <div class="col-6 mb-3">
                            <a href="/proyecto_lopez/reportes" class="btn w-100" style="background: linear-gradient(135deg, #4c7dd8 0%, #e6f3ff 100%); color: #2c3e50; border-radius: 12px; padding: 15px 20px; font-size: 0.85rem; font-weight: 600; transition: all 0.3s ease; border: none; text-decoration: none;">
                                <i class="fas fa-chart-line me-2"></i>📊 Reportes
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card" style="border-radius: 20px; box-shadow: 0 10px 30px rgba(30, 60, 114, 0.1); border: 1px solid rgba(30, 60, 114, 0.1); background: white; position: relative; overflow: hidden;">
                <div class="card-body" style="padding: 30px;">
                    <h5 style="color: #2c3e50; margin-bottom: 25px; font-weight: 600; font-size: 1.2rem;">
                        <i class="fas fa-clock me-2" style="color: #1e3c72;"></i>🕐 Actividad Reciente
                    </h5>
                    
                    <div style="display: flex; align-items: center; padding: 15px 0; border-bottom: 1px solid #f1f3f4; transition: all 0.3s ease;" onmouseover="this.style.transform='translateX(10px)'; this.style.backgroundColor='rgba(30, 60, 114, 0.05)'" onmouseout="this.style.transform='translateX(0)'; this.style.backgroundColor='transparent'">
                        <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 15px; color: white;">
                            <i class="fas fa-hand-holding" style="font-size: 0.9rem;"></i>
                        </div>
                        <div style="flex: 1;">
                            <div style="font-weight: 600; color: #2c3e50; margin-bottom: 5px; font-size: 0.95rem;">
                                ✅ Asignación de Glock 17 completada
                            </div>
                            <div style="font-size: 0.85rem; color: #6c757d;">
                                🎯 Personal: Sgt. Ramírez • ⏰ Hace 15 minutos 
                            </div>
                        </div>
                    </div>
                    
                    <div style="display: flex; align-items: center; padding: 15px 0; border-bottom: 1px solid #f1f3f4; transition: all 0.3s ease;" onmouseover="this.style.transform='translateX(10px)'; this.style.backgroundColor='rgba(42, 82, 152, 0.05)'" onmouseout="this.style.transform='translateX(0)'; this.style.backgroundColor='transparent'">
                        <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #2a5298 0%, #3b6bb8 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 15px; color: white;">
                            <i class="fas fa-user-check" style="font-size: 0.9rem;"></i>
                        </div>
                        <div style="flex: 1;">
                            <div style="font-weight: 600; color: #2c3e50; margin-bottom: 5px; font-size: 0.95rem;">
                                👤 Nuevo usuario registrado
                            </div>
                            <div style="font-size: 0.85rem; color: #6c757d;">
                                📝 Oficial García • ⏰ Hace 32 minutos
                            </div>
                        </div>
                    </div>
                    
                    <div style="display: flex; align-items: center; padding: 15px 0; border-bottom: 1px solid #f1f3f4; transition: all 0.3s ease;" onmouseover="this.style.transform='translateX(10px)'; this.style.backgroundColor='rgba(59, 107, 184, 0.05)'" onmouseout="this.style.transform='translateX(0)'; this.style.backgroundColor='transparent'">
                        <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #3b6bb8 0%, #4c7dd8 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 15px; color: white;">
                            <i class="fas fa-undo" style="font-size: 0.9rem;"></i>
                        </div>
                        <div style="flex: 1;">
                            <div style="font-weight: 600; color: #2c3e50; margin-bottom: 5px; font-size: 0.95rem;">
                                🔄 Retiro de Rifle M4A1
                            </div>
                            <div style="font-size: 0.85rem; color: #6c757d;">
                                🎯 Personal: Cabo López • ⏰ Hace 1 hora
                            </div>
                        </div>
                    </div>
                    
                    <div style="display: flex; align-items: center; padding: 15px 0; transition: all 0.3s ease;" onmouseover="this.style.transform='translateX(10px)'; this.style.backgroundColor='rgba(76, 125, 216, 0.05)'" onmouseout="this.style.transform='translateX(0)'; this.style.backgroundColor='transparent'">
                        <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #4c7dd8 0%, #e6f3ff 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 15px; color: #2c3e50;">
                            <i class="fas fa-plus" style="font-size: 0.9rem;"></i>
                        </div>
                        <div style="flex: 1;">
                            <div style="font-weight: 600; color: #2c3e50; margin-bottom: 5px; font-size: 0.95rem;">
                                🔫 Nuevo armamento registrado
                            </div>
                            <div style="font-size: 0.85rem; color: #6c757d;">
                                📱 Beretta 92FS • ⏰ Hace 2 horas
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Contador animado para las estadísticas
function animateCounter(element, target, duration = 2000) {
    let start = 0;
    const increment = target / (duration / 16);
    
    function timer() {
        start += increment;
        if (start >= target) {
            element.textContent = target;
        } else {
            element.textContent = Math.floor(start);
            requestAnimationFrame(timer);
        }
    }
    timer();
}

// Inicializar contadores cuando se carga la página
document.addEventListener('DOMContentLoaded', function() {
    const counters = document.querySelectorAll('[style*="font-size: 2.5rem"]');
    
    setTimeout(() => {
        counters.forEach(counter => {
            const target = parseInt(counter.textContent);
            counter.textContent = '0';
            animateCounter(counter, target);
        });
    }, 500);
});

// Efectos de hover adicionales
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-10px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
});
</script>

</body>
</html>


<script src="build/js/inicio.js"></script>