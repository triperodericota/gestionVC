#!/usr/bin/env bash
echo "Si lo considera pertinente ejecute este archivo con privilegios root

"
echo "Por favor, ingrese su contraseña de mysql para crear la base de datos: "
read -s root_pass
echo "Por favor, ingrese la IP del host donde se ejecuta su DBMS: "
read host_ip

mysql -uroot -p$root_pass -e "drop database if exists gestion_videoconferencias;
create database gestion_videoconferencias; drop user if exists 'cliente'@'$host_ip';
 create user 'cliente'@'$host_ip' identified by 'dssd';
 grant all privileges on gestion_videoconferencias.* TO 'cliente'@'$host_ip';
 use gestion_videoconferencias;
 source /home/triperodericota/Facultad/DSSD/Practica/EsquemaBD_2019/dump-videoconferencia-201908171228.sql'"

echo "Se le asignó el usuario 'cliente' con la contraseña 'dssd'"

