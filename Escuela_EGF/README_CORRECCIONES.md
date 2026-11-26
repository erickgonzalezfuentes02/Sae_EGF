# Escuela_EGF corregido

Esta copia se basa en los archivos de `C:\xampp\htdocs\Escuela_EGF`.

## Cambios incluidos

- La asignación de materias guarda con los nombres reales de columnas (`Id_mat`, `Id_alumno`, `Id_profesor`), permite elegir profesor y actualiza la asignación existente.
- La captura individual y masiva valida calificaciones de 0 a 100. Al guardar en grupo conserva el grupo seleccionado, verifica la pertenencia y realiza la operación en una transacción.
- Los reportes de calificaciones y materias asignadas muestran nombre y ambos apellidos; se incluyen los reportes faltantes en el menú.
- Los botones de edición y baja usan los parámetros esperados por sus pantallas.

## Sobre el ZIP de referencia

El archivo `Escuela IICP.zip` recibido es un alias de macOS de 868 bytes y no contiene los archivos del proyecto. Para comparar todos los procesos, catálogos y reportes con el trabajo de tu compañero, vuelve a adjuntar el ZIP real.

## Base de datos

El volcado SQL incluido en esta copia no trae las tablas `usuarios` y `puesto`, que el inicio de sesión necesita. Si la base `sae_egf` que usas en XAMPP ya tiene esas tablas, conserva esa base. Para una instalación nueva habrá que agregar las tablas y crear la cuenta administradora antes de iniciar sesión.
