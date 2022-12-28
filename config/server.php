<?php
    //se definen los parametros de conexion a la base de datos
    const SERVER="localhost";
    const DB="prestamos";
    const USER="root";
    const PASSWORD="Ragnarok123";

    //se crea el objeto de conexion al sistema de gestion de bases de datos
    const SGBD="mysql:host=".SERVER.";dbname=".DB;

    const METHOD="AES-256-CBC";
    const SECRET_KEY='$PRESTAMOS@2022';
    const SECRET_IV='0123456789';