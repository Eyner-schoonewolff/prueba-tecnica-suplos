### Refactorización y Mejora - Sistema de Gestión de Tareas

Este PR incluye una refactorización significativa del sistema de gestión de tareas, con mejoras tanto en el backend como en el frontend. A continuación, se detallan los cambios implementados.
Problemas Detectados y Soluciones Implementadas:

## 1. Registro de Usuarios

 - No existía un endpoint para registrar usuarios.
 - Se implementó un nuevo endpoint register para permitir la creación de usuarios desde la API.
 - Se añadió la validación en el registro de tareas para verificar la existencia del usuario.
 - Si el usuario no existe, el sistema devuelve un mensaje de error, evitando registros inválidos.

## 2. Mejoras en las Migraciones

- Se añadieron los campos deleted_at tanto en la tabla users como en la tabla tasks para habilitar el uso de SoftDeletes (eliminación lógica), lo que permite conservar los datos sin eliminarlos físicamente de la base de datos.
  En la migración de las relaciones, se modificó el comportamiento de eliminación en cascada (onDelete('cascade')) por la opción de dejar el valor en NULL (onDelete('set null')) cuando se elimina un registro relacionado.
  Esto garantiza la integridad referencial y evita la eliminación no deseada de datos relacionados.

## 3. SoftDeletes en Modelos

- Se implementó la funcionalidad de SoftDeletes en los modelos correspondientes, lo que permite realizar eliminaciones lógicas, preservando los registros para futuras referencias o auditorías.

## 4. Manejo de Excepciones con Try-Catch

- Se añadieron bloques try-catch en las operaciones críticas para mejorar el manejo de excepciones. Esto permite capturar y gestionar errores en tiempo de ejecución, brindando retroalimentación clara al usuario y evitando fallos inesperados en el sistema.

## 5. Actualización de Estado de Tareas

- Se implementó la funcionalidad para actualizar el estado de las tareas mediante un endpoint dedicado, asegurando un flujo de trabajo más eficiente y controlado.

## 6. Validaciones en Operaciones de Eliminación y Actualización

   - Antes de realizar una operación de eliminación o actualización, se agregó una verificación para asegurarse de que la información existe en la base de datos. Si no se encuentra el registro, el sistema retorna un mensaje adecuado, evitando operaciones en registros inexistentes.

# Cambios en el Frontend:

- Dado que mi experiencia previa es limitada con Vue.js, refactoricé el frontend utilizando Blade para las vistas, aprovechando la potencia de las plantillas de PHP para mejorar la integración con el backend.
    El consumo de los endpoints de la API se realizó utilizando la librería Axios.
    Para la gestión de alertas y notificaciones, se integró la librería SweetAlert, mejorando la experiencia de usuario y facilitando la interacción con el sistema.

## Checklist:

- [x] Corrección de errores
- [x] Nueva funcionalidad: Registro de usuarios y validaciones en tareas.
- [x] Refactorización: Migración a SoftDeletes y cambios en relaciones.
- [x] Optimización del código con validaciones y manejo de excepciones.
- [x] Mejora de estilos y presentación con SweetAlert.

# Video Demostración

Se puede acceder a una demostración en video de la aplicación en funcionamiento a través del siguiente enlace: https://www.youtube.com/watch?v=a36sLehdDZE
