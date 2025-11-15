<?php
/**
 * ARCHIVO: exportar_postulaciones.php
 * DESCRIPCIÓN: Este archivo maneja la exportación de postulaciones a Excel
 * 
 * FUNCIONAMIENTO:
 * 1. Cuando se accede con ?descargar=1, ejecuta un script de Python
 * 2. El script Python conecta a MySQL, lee la tabla 'postulaciones' y genera un Excel
 * 3. El archivo PHP lee ese Excel generado y lo envía como descarga al navegador
 * 4. Si se accede sin parámetros, muestra la interfaz HTML con el botón de descarga
 */

// Incluir archivo con configuración de rutas de Python
require_once "config_python.php";

// Habilitar visualización de errores para depuración (útil en desarrollo)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verificar si se solicitó la descarga del archivo Excel
if (isset($_GET['descargar'])) {

    // PASO 1: EJECUTAR EL SCRIPT DE PYTHON
    // El script Python se conecta a MySQL, extrae datos y genera el Excel
    
    // Obtener rutas desde config_python.php
    $rutaPython = $PYTHON_PATH;  // Ruta al ejecutable de Python
    $rutaScript = $PYTHON_SCRIPTS . 'exportar_postulaciones_excel.py';  // Ruta al script

    // Construir el comando para ejecutar Python de forma segura
    // escapeshellcmd() previene inyección de comandos maliciosos
    $comando = escapeshellcmd($rutaPython . ' ' . $rutaScript);

    // Ejecutar el comando y capturar la salida
    // 2>&1 captura tanto la salida estándar como los errores
    $salida = shell_exec($comando . ' 2>&1');
    
    // Descomentar la siguiente línea si necesitas ver errores del script Python
    // echo "<pre>$salida</pre>";

    // PASO 2: VERIFICAR QUE EL ARCHIVO SE GENERÓ CORRECTAMENTE
    // __DIR__ obtiene el directorio actual del archivo PHP
    $rutaArchivo = __DIR__ . '/exports/postulaciones.xlsx';

    if (!file_exists($rutaArchivo)) {
        // Si el archivo no existe, mostrar error y detener ejecución
        echo "No se pudo generar el archivo de Excel. Verifica rutas y permisos.";
        exit;
    }

    // PASO 3: ENVIAR EL ARCHIVO EXCEL AL NAVEGADOR COMO DESCARGA
    
    // Establecer el tipo de contenido para archivos Excel (.xlsx)
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    
    // Indicar al navegador que es una descarga y el nombre del archivo
    header('Content-Disposition: attachment; filename="postulaciones.xlsx"');
    
    // Especificar el tamaño del archivo para la barra de progreso de descarga
    header('Content-Length: ' . filesize($rutaArchivo));
    
    // Enviar el contenido del archivo al navegador
    readfile($rutaArchivo);
    
    // Detener la ejecución para que no se renderice el HTML
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Exportar Postulaciones - Nebulink Store</title>
    <!-- Fuente personalizada -->
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&display=swap" rel="stylesheet">
    <!-- Estilos del sitio -->
    <link rel="stylesheet" href="./assets/css/styles.css">
</head>
<body>

    <!-- HEADER: Misma estructura de navegación del sitio -->
    <header class="header">
        <div class="container header-inner">
            <a class="logo" href="index.html">
                <img src="assets/images/logos/nebulink_store_logo.png" alt="Nebulink Store" />
            </a>
            <nav class="nav">
                <a href="index.html">Inicio</a>
                <a href="#">Consolas</a>
                <a href="#">Videojuegos</a>
            </nav>
            <div class="header-right">
                <div class="search">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                        <path d="M21 21l-4.2-4.2M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15z"
                              stroke="currentColor" opacity=".8" stroke-width="2" stroke-linecap="round" />
                    </svg>
                    <input type="text" placeholder="Buscar…">
                </div>
                <button class="nav-toggle" aria-label="Abrir menú">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </div>
    </header>

    <main>
        <div class="container">
            <section class="section">
                <div class="text-center">
                    <h1>Revisar estado de postulación</h1>
                </div>

                <!-- BOTÓN DE EXPORTACIÓN
                     Al hacer clic, recarga esta página con el parámetro ?descargar=1
                     Esto activa el código PHP arriba que genera y descarga el Excel -->
                <div class="text-center" style="margin-top: 32px;">
                    <a href="exportar_postulaciones.php?descargar=1" class="btn-primary">
                        Exportar a Excel
                    </a>
                </div>

                <!-- INFORMACIÓN EXPLICATIVA DEL PROCESO -->
                <div style="margin-top: 40px; max-width: 700px; margin-inline: auto; text-align: center; opacity: .8;">
                    <p>
                        Al hacer clic en el botón, el sistema ejecuta un script en Python que se conecta a MySQL,
                        lee la tabla <strong>postulaciones</strong> y genera el archivo
                        <strong>postulaciones.xlsx</strong>, listo para abrirse en Excel.
                    </p>
                </div>
            </section>
        </div>
    </main>

    <!-- WIDGET DE CHAT: Disponible en todas las páginas del sitio -->
    <div class="chat-widget">
        <button id="chat-toggle" class="chat-toggle">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                 viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
            </svg>
        </button>
        <div class="chat-container" id="chat-container">
            <div class="chat-header">
                <h3>Asistente Virtual</h3>
                <button id="chat-close" class="chat-close">&times;</button>
            </div>
            <div class="chat-body" id="chat-body"></div>
            <div class="chat-footer">
                <input type="text" id="chat-input" placeholder="Escribe un mensaje...">
                <button id="chat-send">Enviar</button>
            </div>
        </div>
    </div>

    <!-- FOOTER: Información de contacto y enlaces institucionales -->
    <footer class="footer">
        <div class="container footer-grid">
            <div>
                <h4>Nebulink Store</h4>
                <a href="sobrenosotros.html">Sobre nosotros</a>
                <a href="#">Nuestros servicios</a>
                <a href="trabajaconnosotros.php">Trabaja con nosotros</a>
            </div>
            <div>
                <h4>Contáctanos</h4>
                <p>Tel: +57 3133226068</p>
                <p>Email: info.nebulink@gmail.com</p>
            </div>
            <div>
                <h4>Síguenos</h4>
                <div class="redes">
                    <a href="https://www.facebook.com/share/19xb5fJQ54/" target="_blank">
                        <img src="./assets/images/logos/logo_facebook.png" alt="Facebook Logo">
                    </a>
                    <a href="https://www.instagram.com/nebulinkstore" target="_blank">
                        <img src="./assets/images/logos/logo_instagram.png" alt="Instagram Logo">
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Script principal: Maneja el menú móvil y el chatbot -->
    <script src="./assets/js/main.js"></script>
</body>
</html>