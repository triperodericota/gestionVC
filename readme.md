# Gestion de Videoconferencias


APP web realizada con el framework Laravel para la gestión del proceso de videoconferencias orquestados con el bpm Bonita.


La base de datos (dump-videoconferencia-201908171228.sql) es posible levantarla en el servidor local, en este caso configurar el
archivo `.env` en el port correspondiente. Para este desarrollo se utilizó un contenedor docker para levantar el servidor MySQL, el
mismo se puede utilizar ejecutando los siguientes comandos:

`docker run -p3307:3306 -e MYSQL_ROOT_PASSWORD=pass -d mysql:5.7`

Para generar la base de datos se debe ingresar al cliente MySQL con el comando `mysql -uroot -p --port=3307 --protocol=tcp`

Luego ejecutar `create database gestion_videoconferencias;` para la creación de la base de datos.

Una vez creada la base de datos, creamos un nuevo usuario MySQL con todos los permisos para interactuar con la base:

`create user 'cliente'@'$host_ip' identified by 'dssd';`

`grant all privileges on gestion_videoconferencias.* TO 'cliente'@'$host_ip';`

donde $host_ip, es la direccion IP del contenedor docker o del ordenador local.

Para la carga de datos ejecutar `use gestion_videoconferencias; source /path/to/dump-videoconferencia-201908171228.sql`


Para correr el servicio, situarse en la carpeta contenedora (cd /gestion-vc) y ejecutar `php artisan serve`
