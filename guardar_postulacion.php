<?php
// guardar_postulacion.php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. Incluir la conexión
    require_once "conexion.php";

    // 2. Recibir datos del formulario
    $nombre   = trim($_POST["nombre"] ?? "");
    $correo   = trim($_POST["correo"] ?? "");
    $telefono = trim($_POST["telefono"] ?? "");

    // 3. Validación básica
    if ($nombre === "" || $correo === "" || $telefono === "") {
        // Puedes redirigir de nuevo con un mensaje, por ahora mostramos texto simple
        echo "Por favor completa todos los campos.";
        exit;
    }

    // 4. Preparar la consulta para evitar inyección SQL
    $sql = "INSERT INTO postulaciones (nombre, correo, telefono) VALUES (?, ?, ?)";
    $stmt = $conexion->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("sss", $nombre, $correo, $telefono);

        if ($stmt->execute()) {
            // Éxito: puedes mostrar un mensaje o redirigir de vuelta a la página
            echo "
            <script>
                alert('¡Tu postulación se ha enviado correctamente!');
                window.location.href = 'trabajaconnosotros.php';
            </script>
            ";
        } else {
            echo "Error al guardar la postulación: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error en la preparación de la consulta: " . $conexion->error;
    }

    $conexion->close();

} else {
    // Si alguien entra directamente sin enviar el formulario
    header("Location: trabajaconnosotros.php");
    exit;
}
?>
