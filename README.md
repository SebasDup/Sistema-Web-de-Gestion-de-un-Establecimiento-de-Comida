# 🍽️ Sistema Web de Gestión de Restaurantes (Establecimientos de Comida)

## 📝 Descripción del Proyecto
Este proyecto es un sistema web centralizado diseñado para optimizar la gestión operativa y administrativa de un establecimiento de comida. Nace como solución a la falta de herramientas accesibles para la administración de mesas, reservaciones, empleados y reportes, eliminando la dependencia de procesos manuales propensos a errores. 

El enfoque principal del sistema es la organización interna del negocio, mejorando la toma de decisiones mediante el análisis de datos y garantizando la integridad de la información mediante procesos de respaldo.

## 🛠️ Tecnologías Utilizadas
* **Frontend:** HTML5, CSS3, JavaScript y Bootstrap
* **Backend:** PHP
* **Base de Datos:** MySQL
* **Arquitectura/Patrones:** MVC (Modelo-Vista-Controlador)

## 🎯 Competencias y Habilidades Aplicadas
Durante el desarrollo de este proyecto, fortalecí las siguientes competencias técnicas y habilidades blandas:
* **Autogestión y Aprendizaje Continuo:** Capacidad de aprendizaje independiente para investigar e implementar nuevas tecnologías durante el ciclo de vida del software.
* **Trabajo Colaborativo:** Delegación de tareas, comunicación efectiva y trabajo en equipo para cumplir con los objetivos del desarrollo.
* **Seguridad y Administración de Datos:** Implementación de políticas de resguardo de información, garantizando la disponibilidad mediante mecanismos de respaldo (backups).
* **Documentación Técnica:** Levantamiento de requerimientos, estructuración de la información y automatización en la elaboración de documentos técnicos del proyecto.
* **Buenas Prácticas:** Trabajo estructurado, ordenado y con código limpio para facilitar el mantenimiento del sistema.

## ⚙️ Módulos y Características Principales
El sistema cuenta con las siguientes funcionalidades clave:

* 👥 **Gestión de Usuarios y Roles:** Control de acceso con privilegios definidos para Administradores, Empleados y Clientes.
* 🍔 **Administración de Menú y Promociones:** Interfaz para crear, actualizar y organizar los productos y ofertas del establecimiento.
* 🗺️ **Control de Zonas y Mesas:** Asignación dinámica de empleados a zonas específicas del restaurante y gestión del estado de las mesas.
* 📅 **Sistema de Reservaciones:** Planificación y asignación estática de reservaciones para mejorar el flujo de clientes.
* 🧾 **Gestión de Comandas:** Registro detallado de las órdenes tomadas en el establecimiento.
* 📊 **Reportes y Analítica:** Generación de reportes operativos (ocupación de mesas, asistencia, desempeño de empleados) para la toma de decisiones.
* 💾 **Respaldo y Restauración:** Módulo integrado para crear backups de la base de datos y restaurarla en caso de contingencias.

## 🚀 Cómo ejecutar el proyecto en Localhost
1. Clona este repositorio: `git clone https://github.com/tu-usuario/tu-repo.git`
2. Configura tu entorno local (ej. XAMPP, Laragon, Docker).
3. Importa la base de datos ubicada en la carpeta `/db` hacia tu gestor de base de datos.
4. Configura las variables de entorno o el archivo de conexión a la BD con tus credenciales locales.
5. Inicia el servidor y accede a `http://localhost/tu-proyecto` en tu navegador.

* **Utilizando XAMPP:**
Si se decide utilizar XAMPP como servidor local es necesario tener instalado XAMPP, el cual puede ser descargado desde `https://www.apachefriends.org/es/index.html` y tener iniciados el servicio Apache y MySQL. El comando para importar la base de datos es: mysql -u root -p < (ruta donde se encuentra la base de datos).

  Ejemplo: `mysql -u root -p < "C:\xampp\htdocs\restaurante\database\schema.sql"`
  Y para la contraseña dejar en blanco y simplemente presionar la tecla "Enter"

## 📌 Alcances y Limitaciones
* **Entorno:** El sistema está diseñado para ejecutarse en un entorno local (Localhost) enfocado en la administración interna, sin despliegue en servidores de paga.
* **Plataforma:** Acceso exclusivo vía navegador web (no cuenta con aplicación móvil nativa).
* **Pagos:** El sistema gestiona las cuentas de manera interna, pero no procesa transacciones bancarias ni pagos en línea.

## 📸 Capturas de Pantalla
1. Inicio de Sesión

Se puede observar que como credenciales se pide el correo electrónico y la contraseña. Así mismo un enlace para ir al registro de una nueva cuenta de acceso.
<img width="1176" height="500" alt="Screenshot 2026-07-22 191747" src="https://github.com/user-attachments/assets/21fe7963-0630-424a-a771-b7ca896c44ec" />


2. Página de Inicio del Sistema

Se puede observar el dashboard con cada una de las diferentes gestiones del sistema y un botón de ajustes para configurar el horario de apertura del restaurante.
<img width="1173" height="590" alt="Screenshot 2026-07-22 191810" src="https://github.com/user-attachments/assets/a9de99ad-4e83-471f-9bd8-4b479941252a" />


3. Gestión de Usuarios

Se puede observar la lista de usuarios, botones de acción para editar o eliminar el registro y el modal de Bootstrap para realizar el registro de un nuevo usuario.
<img width="1022" height="517" alt="Screenshot 2026-07-22 191840" src="https://github.com/user-attachments/assets/521f1602-f5e2-4394-a17c-38a9bea894e2" />


4. Gestión de Mesas

Se puede observar que para la gestión de mesas la interfaz es diferente a las tablas tradicionales, esta vez se optó por un diseño con casillas cuadradas, donde internamente tienen botones de acción para editar o eliminar la mesa y botones dinámicos que al presionarlos cambian el estado y color de la mesa para indicar si está Disponible, Ocupada o Reservada.
<img width="1412" height="753" alt="Screenshot 2026-07-22 192000" src="https://github.com/user-attachments/assets/b4a7aa33-5c05-4736-a99d-8fc69a9df913" />


5. Reporte de Reservaciones

Se puede observar las reservaciones que se han hecho a través del sistema ordenados por fecha de registro.
<img width="1490" height="607" alt="Screenshot 2026-07-22 192056" src="https://github.com/user-attachments/assets/7150f134-140a-41a6-86f6-a24fadfe3601" />


6. Reporte de Empleado con Mayor Antigüedad
<img width="1448" height="848" alt="Screenshot 2026-07-22 192117" src="https://github.com/user-attachments/assets/b384ba1c-1a30-4624-87ee-8161a95d2496" />


7. Respaldo y Restauración de la Base de Datos

Se puede observar
* **Restauración:** El campo para realizar la restauración de datos subiendo un archivo `.SQL`.
* **Respaldo:** El campo para realizar el respaldo, el cual genera una copia de las tablas y registros de la base de datos en un archivo `.SQL`.
* **Historial de Operaciones:** Un campo para mostrar las operaciones que se realizan en esta interfaz, permitiendo ver quién realizó un respaldo o restauración y en qué fecha para mantener la seguridad de este módulo. 
<img width="1701" height="731" alt="Screenshot 2026-07-22 192200" src="https://github.com/user-attachments/assets/b4532b1f-1d85-48bd-a746-eda68626ae4d" />
