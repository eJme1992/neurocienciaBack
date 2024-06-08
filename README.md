Para que las etiquetas HTML se muestren correctamente en un archivo README de Git, puedes utilizar el formato de Markdown. Aquí está el contenido con las etiquetas HTML convertidas a Markdown:

```markdown
# Instrucciones para ejecutar el proyecto Laravel

## Requisitos previos
- PHP 7.1 o superior instalado en tu equipo.
- Un servidor web Apache y MySQL (o similar como MariaDB).
- [Composer](https://getcomposer.org/) instalado en tu equipo.

## Extensiones de PHP requeridas
Asegúrate de tener las siguientes extensiones de PHP habilitadas en tu archivo `php.ini`:
```
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

## Configuración de la base de datos
1. En tu gestor de MySQL, crea una base de datos vacía.
2. Copia el archivo `.env.example` y renómbralo como `.env` en la raíz de tu proyecto Laravel.
3. Configura las siguientes variables en tu archivo `.env` con los detalles de tu base de datos:
```
DB_HOST=nombre_del_host
DB_PORT=puerto
DB_DATABASE=nombre_de_la_base_de_datos
DB_USERNAME=nombre_de_usuario
DB_PASSWORD=contraseña
```

## Instalación de dependencias
1. Abre una terminal en la carpeta raíz de tu proyecto Laravel.
2. Ejecuta el comando `composer install` para instalar todas las dependencias del proyecto.

## Prueba de conexión a la base de datos
1. Abre una terminal en la carpeta raíz de tu proyecto Laravel.
2. Ejecuta el siguiente comando para ejecutar el test unitario de conexión y asegurarte de que la configuración de la base de datos sea correcta:
```
php artisan test tests/Unit/ExampleTest.php --filter test_database_connection
```
3. Si el test es exitoso, significa que la configuración de la base de datos es correcta.

Estas instrucciones te ayudarán a configurar y ejecutar el proyecto Laravel correctamente.
``` 

Este formato Markdown es compatible con Git y se verá correctamente renderizado en la plataforma.