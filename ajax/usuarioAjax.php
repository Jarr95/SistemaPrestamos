<?php

$peticionAjax = true;
require_once("../config/app.php");

//Se valida que se este ingresando mediante el formulario y no la url
if (isset($_POST['usuario_dni_reg']) || isset($_POST['usuario_id_del']) || isset($_POST['usuario_id_up'])) {

    //instancia al controlador
    require_once("../controladores/usuarioControlador.php");
    $ins_usuario = new usuarioControlador();

    //Validación para registrar usuario
    if (isset($_POST['usuario_dni_reg'])  && isset($_POST['usuario_nombre_reg'])) {
        echo $ins_usuario->agregar_usuario_controlador();
    }

    //Validación para eliminar usuario
    if (isset($_POST['usuario_id_del'])) {
        echo $ins_usuario->eliminar_usuario_controlador();
    }

    //Validación para actualizar usuario
    if (isset($_POST['usuario_id_up'])) {
        echo $ins_usuario->actualizar_usuario_controlador();
    }
    
} else {
    session_start(['name' => 'SP']);
    session_unset();
    session_destroy();
    header("Location: " . SERVERURL . "login/");
}
