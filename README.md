<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instrucciones para ejecutar el proyecto Laravel</title>
</head>
<body>
    <h1>Instrucciones para ejecutar el proyecto Laravel</h1>
    
    <h2>Requisitos previos</h2>
    <ul>
        <li>PHP 7.1 o superior instalado en tu equipo.</li>
        <li>Un servidor web Apache y MySQL (o similar como MariaDB).</li>
        <li><a href="https://getcomposer.org/">Composer</a> instalado en tu equipo.</li>
    </ul>
    
    <h2>Extensiones de PHP requeridas</h2>
    <p>Asegúrate de tener las siguientes extensiones de PHP habilitadas en tu archivo <code>php.ini</code>:</p>
    <pre>
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
    </pre>
    
    <h2>Configuración de la base de datos</h2>
    <ol>
        <li>En tu gestor de MySQL, crea una base de datos vacía.</li>
        <li>Copia el archivo <code>.env.example</code> y renómbralo como <code>.env</code> en la raíz de tu proyecto Laravel.</li>
        <li>Configura las siguientes variables en tu archivo <code>.env</code> con los detalles de tu base de datos:
            <pre>
                DB_HOST=nombre_del_host
                DB_PORT=puerto
                DB_DATABASE=nombre_de_la_base_de_datos
                DB_USERNAME=nombre_de_usuario
                DB_PASSWORD=contraseña
            </pre>
        </li>
    </ol>
    
    <h2>Instalación de dependencias</h2>
    <ol>
        <li>Abre una terminal en la carpeta raíz de tu proyecto Laravel.</li>
        <li>Ejecuta el comando <code>composer install</code> para instalar todas las dependencias del proyecto.</li>
    </ol>
    
    <h2>Prueba de conexión a la base de datos</h2>
    <ol>
        <li>Abre una terminal en la carpeta raíz de tu proyecto Laravel.</li>
        <li>Ejecuta el siguiente comando para ejecutar el test unitario de conexión y asegurarte de que la configuración de la base de datos sea correcta:
            <pre>php artisan test tests/Unit/ExampleTest.php --filter test_database_connection</pre>
        </li>
        <li>Si el test es exitoso, significa que la configuración de la base de datos es correcta.</li>
    </ol>
    
    <footer>
        <p>Estas instrucciones te ayudarán a configurar y ejecutar el proyecto Laravel correctamente.</p>
    </footer>
</body>
</html>
