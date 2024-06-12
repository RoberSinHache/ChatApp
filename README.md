# Instalación

## Instalación de XAMPP

### Windows

1. Descargar XAMPP:
   - Acceder a la página oficial de XAMPP [https://www.apachefriends.org/index.html](https://www.apachefriends.org/index.html) y descargar el instalador para Windows.

2. Instalar XAMPP:
   - Ejecutar el instalador descargado y seguir las instrucciones del asistente de instalación.
   - Seleccionar los componentes necesarios (Apache, MySQL, PHP, y phpMyAdmin).
   - Completar la instalación siguiendo los pasos del instalador.

### Linux

1. Descargar XAMPP:
   - Acceder a la página oficial de XAMPP [https://www.apachefriends.org/index.html](https://www.apachefriends.org/index.html) y descargar el archivo `.run` para Linux.

2. Instalar XAMPP:
   - Abrir una terminal y navegar al directorio donde descargaste el archivo.
   - Editar el archivo para que sea ejecutable y abrir el instalador:
     ```sh
     chmod +x xampp-linux-x64-<version>.run
     sudo ./xampp-linux-x64-<version>.run
     ```
   - Seguir las instrucciones del asistente de instalación.

### macOS

1. Descargar XAMPP:
   - Acceder a la página oficial de XAMPP [https://www.apachefriends.org/index.html](https://www.apachefriends.org/index.html) y descargar el instalador para macOS.

2. Instalar XAMPP:
   - Ejecutar el archivo `.dmg` descargado y arrastrar XAMPP a la carpeta de aplicaciones.
   - Abrir XAMPP desde la carpeta de aplicaciones y seguir las instrucciones del asistente de instalación.

## Configuración de XAMPP

1. Iniciar XAMPP:
   - Abrir el Panel de Control de XAMPP y arrancar los servicios de Apache y MySQL.

2. Configurar el archivo `hosts`:
   - **Windows:**
     - Abrir `C:\Windows\System32\drivers\etc\hosts` con un editor de texto como administrador.
   - **Linux y macOS:**
     - Editar el archivo `/etc/hosts` usando un editor de texto con privilegios de superusuario:
       ```sh
       sudo nano /etc/hosts
       ```
   - Añadir la siguiente línea al final del archivo para mapear el dominio a localhost:
     ```
     127.0.0.1    chatapp.local
     ```

3. Configurar el archivo `httpd-vhosts.conf`:
   - Abrir el archivo de configuración de virtual hosts en XAMPP:
     - **Windows:** `C:\xampp\apache\conf\extra\httpd-vhosts.conf`
     - **Linux y macOS:** `/opt/lampp/etc/extra/httpd-vhosts.conf`
   - Añadir la siguiente configuración para crear un virtual host:
     ```apache
     <VirtualHost *:80>
         DocumentRoot "/opt/lampp/htdocs/ChatApp"
         ServerName chatapp.local
         <Directory "/opt/lampp/htdocs/ChatApp">
             Options Indexes FollowSymLinks
             AllowOverride All
             Require all granted
         </Directory>
     </VirtualHost>
     ```

 	
### Windows:

```txt
<VirtualHost *:80>
        DocumentRoot "C:/xampp/htdocs/ChatApp"
        ServerName chatapp.local

        <Directory "C:/xampp/htdocs/ChatApp">
                Options Indexes FollowSymLinks Includes ExecCGI
                    AllowOverride All
                    Require all granted
        </Directory>
</VirtualHost>
```


### Linux y macOS:
```txt 	
<VirtualHost *:80>
       	DocumentRoot "/opt/lampp/htdocs/ChatApp"
       	ServerName chatapp.local

       	<Directory "/opt/lampp/htdocs/ChatApp">
           	Options Indexes FollowSymLinks Includes ExecCGI
           	AllowOverride All
           	Require all granted
       	</Directory>
   </VirtualHost>
 ```

   	  	 
## Reiniciar Apache
- Reiniciar Apache desde el panel de control de XAMPP para aplicar los cambios.

## Configuración del Proyecto

1. Descargar y Descomprimir el Proyecto:
   - Colocar el archivo raíz del proyecto en el directorio `htdocs` de XAMPP.
     - Windows: `C:\xampp\htdocs\ChatApp`
     - Linux y macOS: `/opt/lampp/htdocs/ChatApp`

2. Configurar la Base de Datos:
   - Crear una base de datos en phpMyAdmin:
     - Abrir [http://localhost/phpmyadmin](http://localhost/phpmyadmin) en el navegador.
     - Crear una nueva base de datos llamada `chatapp`.

   - Importar el esquema de la base de datos:
     - En phpMyAdmin, seleccionar la base de datos creada y usar la opción de importar para cargar el archivo `.sql` proporcionado con el proyecto dentro de la carpeta `db`.

   - Configurar la conexión a la base de datos en el archivo de configuración del proyecto (`includes/config.php`). Si no se han realizado configuraciones aparte de las mencionadas anteriormente, no se ha de sustituir nada.

 ```php
<?php
require 'vendor/autoload.php';

$host = '127.0.0.1';
$db = 'chatapp';
$usuario = 'root';
$contrasenia = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$opciones = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $usuario, $contrasenia, $opciones);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>
```

- Configurar los datos del correo que se utilizará para mandar las confirmaciones de registro y cambios de contraseña (`includes/mail_config.php`) cambiando las variables `Username`, `Password` y `setFrom`.
  - El `Username` será el correo que envíe las confirmaciones.
  - La `Password` es una contraseña de app que ha de crearse en [https://myaccount.google.com/apppasswords](https://myaccount.google.com/apppasswords) teniendo la sesión de Google iniciada. Si no fuese posible acceder a ese apartado, se recomienda acceder primero a [https://myaccount.google.com/security](https://myaccount.google.com/security) y desde ahí cambiar la URL a [https://myaccount.google.com/apppasswords](https://myaccount.google.com/apppasswords).
  - El `setFrom` es para indicar de nuevo desde el correo desde el que se envían todos los que necesite la app, es decir, el mismo que se indique en el apartado `Username`.

```php
<?php
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'correo@gmail.com';
$mail->Password = 'contraseña';
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = 587;

$mail->setFrom('correo@gmail.com', 'ChatApp');
$mail->CharSet = "UTF-8";
$mail->isHTML(true);
?>
```


# TEST

Para realizar test se pueden usar las siguientes cuentas:

- **Usuario: Usuario A**
  - **Mail:** a@a.com
  - **Contraseña:** a

- **Usuario: Usuario B**
  - **Mail:** b@b.com
  - **Contraseña:** b

