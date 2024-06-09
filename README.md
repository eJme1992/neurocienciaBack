# Prueba Técnica Edwin José Backend

## Instrucciones para ejecutar el proyecto Laravel

### Requisitos previos
- PHP 7.1 o superior instalado en tu equipo.
- Un servidor web Apache y MySQL (o similar como MariaDB).
- [Composer](https://getcomposer.org/) instalado en tu equipo.

### Extensiones de PHP requeridas
Asegúrate de tener las siguientes extensiones de PHP habilitadas en tu archivo `php.ini`:

```ini
extension=bcmath.so
extension=bz2.so
extension=calendar.so
extension=ctype.so
extension=curl.so
extension=dba.so
extension=dom.so
extension=enchant.so
extension=exif.so
extension=fileinfo.so
extension=ftp.so
extension=gd.so
extension=gettext.so
extension=gmp.so
extension=iconv.so
extension=imap.so
extension=intl.so
extension=ldap.so
extension=mbstring.so
extension=mysqli.so
extension=oci8.so ; (si estás utilizando Oracle)
extension=odbc.so
extension=openssl.so
extension=pdo.so
extension=pdo_mysql.so
extension=pdo_pgsql.so
extension=pdo_sqlite.so
extension=pgsql.so
extension=shmop.so
extension=soap.so
extension=sockets.so
extension=sodium.so
extension=sqlite3.so
extension=sysvmsg.so
extension=sysvsem.so
extension=sysvshm.so
extension=tidy.so
extension=tokenizer.so
extension=wddx.so
extension=xml.so
extension=xmlreader.so
extension=xmlrpc.so
extension=xmlwriter.so
extension=xsl.so
extension=zip.so
```

### Configuración de la base de datos
1. En tu gestor de MySQL, crea una base de datos vacía.
2. Copia el archivo `.env.example` y renómbralo como `.env` en la raíz de tu proyecto Laravel.
3. Configura las siguientes variables en tu archivo `.env` con los detalles de tu base de datos:

```env
DB_HOST=nombre_del_host
DB_PORT=puerto
DB_DATABASE=nombre_de_la_base_de_datos
DB_USERNAME=nombre_de_usuario
DB_PASSWORD=contraseña
```

### Instalación de dependencias
1. Abre una terminal en la carpeta raíz de tu proyecto Laravel.
2. Ejecuta el comando `composer install` para instalar todas las dependencias del proyecto.

### Prueba de conexión a la base de datos
1. Abre una terminal en la carpeta raíz de tu proyecto Laravel.
2. Ejecuta el siguiente comando para ejecutar el test unitario de conexión y asegurarte de que la configuración de la base de datos sea correcta:

```bash
php artisan test --filter testDatabaseConnection
```

Luego, ejecuta el comando:

```bash
php artisan key:generate
```

3. Si el test es exitoso, significa que la configuración de la base de datos es correcta.

```plaintext
   PASS  Tests\Unit\ConnectionTest
  ✓ database connection                                                                                                                    0.11s  

  Tests:    1 passed (2 assertions)
  Duration: 0.15s
```

Estas instrucciones te ayudarán a configurar y ejecutar el proyecto Laravel correctamente. Si la base de datos está funcional, ejecuta desde la raíz el comando:

```bash
php artisan migrate
```

## Ejecución y documentación

Para acceder a la documentación del proyecto, debes ejecutar los siguientes comandos desde la raíz:

```bash
php artisan serve
```

El sistema tiene la posibilidad de importar los datos de JSON a la base de datos con el método correspondiente. Para esto, deberás quitar las referencias a imágenes y convertir el JSON a string usando [JsonToString](https://jsontostring.com/).

### Encuesta de Prueba para Neurociencia

1. Registra un usuario Administrador en la pestaña Auth con el método register.
2. Inicia sesión con el usuario administrador en la pestaña Auth usando el método login con los datos del método 1.

Al hacer login, el método responde con el token de autenticación requerido para exportar los datos de las cuentas.

### Estructura de la Encuesta

```json
{
  "json": "{\"title\":\"EncuentadepruebaparaNeurociencia\",\"description\":\"Encuestadepruebaweb\",\"logoPosition\":\"right\",\"pages\":[{\"name\":\"page1\",\"elements\":[{\"type\":\"text\",\"name\":\"question1\",\"title\":\"Mail\",\"isRequired\":true,\"inputType\":\"email\"}],\"title\":\"Email\"},{\"name\":\"page2\",\"elements\":[{\"type\":\"dropdown\",\"name\":\"question2\",\"title\":\"Indicatugénero\",\"isRequired\":true,\"choices\":[{\"value\":\"Item1\",\"text\":\"Hombre\"},{\"value\":\"Item2\",\"text\":\"Mujer\"},{\"value\":\"Item3\",\"text\":\"No-binario\"},{\"value\":\"Item4\",\"text\":\"Prefieronocontestar\"}]}],\"title\":\"Genero\"},{\"name\":\"page3\",\"elements\":[{\"type\":\"text\",\"name\":\"question3\",\"title\":\"Fechadenacimineto\",\"isRequired\":true,\"inputType\":\"datetime-local\"}],\"title\":\"Edad\"},{\"name\":\"page4\",\"elements\":[{\"type\":\"image\",\"name\":\"question5\",\"imageFit\":\"cover\",\"imageHeight\":\"auto\",\"imageWidth\":\"100%\"},{\"type\":\"imagepicker\",\"name\":\"question4\",\"title\":\"Queprefieres?\",\"isRequired\":true,\"choices\":[{\"value\":\"Image1\"},{\"value\":\"Image2\"}],\"imageFit\":\"cover\"}],\"title\":\"Preferencia\"}]}"
}
```

Recuerda eliminar las comas que marcaban las imágenes para que el JSON sea válido.