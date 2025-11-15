<?php
// exportar_postulaciones.php

// Mostrar errores para depurar
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Si se pidió descargar
if (isset($_GET['descargar'])) {

    // 1. Ejecutar el script de Python que genera el Excel

    // Ruta al ejecutable de Python (AJUSTAR)
    $rutaPython = 'C:\Users\jeron\AppData\Local\Microsoft\WindowsApps\PythonSoftwareFoundation.Python.3.13_qbz5n2kfra8p0\python.exe';

    // Ruta al script de Python
    $rutaScript = 'C:\\xampp\\htdocs\\nebulinkstore\\python\\exportar_postulaciones_excel.py';

    // Comando
    $comando = escapeshellcmd($rutaPython . ' ' . $rutaScript);

    // Ejecutar
    $salida = shell_exec($comando . ' 2>&1');
    // echo "<pre>$salida</pre>"; // Descomenta para ver errores de Python

    // 2. Ruta del archivo Excel generado
    $rutaArchivo = __DIR__ . '/exports/postulaciones.xlsx';

    if (!file_exists($rutaArchivo)) {
        echo "No se pudo generar el archivo de Excel. Verifica rutas y permisos.";
        exit;
    }

    // 3. Enviar el archivo como descarga
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="postulaciones.xlsx"');
    header('Content-Length: ' . filesize($rutaArchivo));
    readfile($rutaArchivo);
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Exportar Postulaciones - Nebulink Store</title>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./assets/css/styles.css">
</head>
<body>

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

                <div class="text-center" style="margin-top: 32px;">
                    <a href="exportar_postulaciones.php?descargar=1" class="btn-primary">
                        Exportar a Excel
                    </a>
                </div>

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

    <script src="./assets/js/main.js"></script>
</body>
</html>
