<h1>Prueba tecnica Edwin Jose Backend</h1>

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
php artisan test --filter testDatabaseConnection
```
3. Si el test es exitoso, significa que la configuración de la base de datos es correcta.

<code>string(3) "ddd"

   PASS  Tests\Unit\ConnectionTest
  ✓ database connection                                                                                                                    0.11s  

  Tests:    1 passed (2 assertions)
  Duration: 0.15s</code>

Estas instrucciones te ayudarán a configurar y ejecutar el proyecto Laravel correctamente.
``` 
<h2> Ejecucion y documentacion </h2>
Para entra en la domentacion del proyecton debe ejecutar los siguientes comando desde la Raiz

php artisan serve // Para ejecutar el proyecto


El sistema tiene la posibilidad de importar los datos de Json a la base de datos con el metodo 

Para esto debera quitar las referencia a imagenes y convertir el json a string https://jsontostring.com/ Ejmeplo 

# Encuesta de Prueba para Neurociencia

Esta es una encuesta de prueba web diseñada para recopilar datos sobre diferentes aspectos.

## Estructura de la Encuesta

```string
{
  "json": "{\"title\":\"EncuentadepruebaparaNeurociencia\",\"description\":\"Encuestadepruebaweb\",\"logoPosition\":\"right\",\"pages\":[{\"name\":\"page1\",\"elements\":[{\"type\":\"text\",\"name\":\"question1\",\"title\":\"Mail\",\"isRequired\":true,\"inputType\":\"email\"}],\"title\":\"Email\"},{\"name\":\"page2\",\"elements\":[{\"type\":\"dropdown\",\"name\":\"question2\",\"title\":\"Indicatugénero\",\"isRequired\":true,\"choices\":[{\"value\":\"Item1\",\"text\":\"Hombre\"},{\"value\":\"Item2\",\"text\":\"Mujer\"},{\"value\":\"Item3\",\"text\":\"No-binario\"},{\"value\":\"Item4\",\"text\":\"Prefieronocontestar\"}]}],\"title\":\"Genero\"},{\"name\":\"page3\",\"elements\":[{\"type\":\"text\",\"name\":\"question3\",\"title\":\"Fechadenacimineto\",\"isRequired\":true,\"inputType\":\"datetime-local\"}],\"title\":\"Edad\"},{\"name\":\"page4\",\"elements\":[{\"type\":\"image\",\"name\":\"question5\",\"imageFit\":\"cover\",\"imageHeight\":\"auto\",\"imageWidth\":\"100%\"},{\"type\":\"imagepicker\",\"name\":\"question4\",\"title\":\"Queprefieres?\",\"isRequired\":true,\"choices\":[{\"value\":\"Image1\"},{\"value\":\"Image2\"}],\"imageFit\":\"cover\"}],\"title\":\"Preferencia\"}]}"
}



Recuerda que eliminar la comas que marcaban las imagenes para que el json sea valiso 

