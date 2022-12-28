<?php
if ($peticionAjax) {
    require_once("../modelos/loginModelo.php");
} else {
    require_once("./modelos/loginModelo.php");
}

class loginControlador extends loginModelo
{

    //Controlador para iniciar sesion
    public function iniciar_sesion_controlador()
    {
        $usuario = mainModel::limpiar_cadena($_POST['usuario_log']);
        $clave = mainModel::limpiar_cadena($_POST['clave_log']);

        //Comprobación de campos vacios
        if ($usuario == "" || $clave == "") {
            echo '<script>
                    Swal.fire({
                        title: "Ocurrio un error inesperado",
                        text: "No se han llenado los campos requeridos",
                        icon: "error",
                        confirmButtonText: "Aceptar"
                      });
                    </script>';
            exit();
        }

        //Validación de integridad de los datos
        if (mainModel::verificar_datos("[a-zA-Z0-9]{1,35}", $usuario)) {
            echo '<script>
                    Swal.fire({
                        title: "Ocurrio un error inesperado",
                        text: "El nombre de usuario no coincide con el formato solicitado",
                        icon: "error",
                        confirmButtonText: "Aceptar"
                      });
                    </script>';
            exit();
        }

        if (mainModel::verificar_datos("[a-zA-Z0-9$@.-]{7,100}", $clave)) {
            echo '<script>
                    Swal.fire({
                        title: "Ocurrio un error inesperado",
                        text: "La contraseña no coincide con el formato solicitado",
                        icon: "error",
                        confirmButtonText: "Aceptar"
                      });
                    </script>';
            exit();
        }
        $clave = mainModel::encryption($clave);

        $datos_login = [
            "Usuario" => $usuario,
            "Clave" => $clave
        ];
        $datos_cuenta = loginModelo::iniciar_sesion_modelo($datos_login);

        if ($datos_cuenta->rowCount() == 1) {
            $row = $datos_cuenta->fetch();
            session_start(['name' => 'SP']);

            $_SESSION['id_sp'] = $row['usuario_id'];
            $_SESSION['nombre_sp'] = $row['usuario_nombre'];
            $_SESSION['apellido_sp'] = $row['usuario_apellido'];
            $_SESSION['usuario_sp'] = $row['usuario_usuario'];
            $_SESSION['privilegio_sp'] = $row['usuario_privilegio'];
            $_SESSION['token_sp'] = md5(uniqid(mt_rand(), true));

            return header("Location: " . SERVERURL . "home/");
        } else {
            '<script>
                    Swal.fire({
                        title: "Ocurrio un error inesperado",
                        text: "El usuario o contraseña son incorrectas",
                        icon: "error",
                        confirmButtonText: "Aceptar"
                      });
                    </script>';
        }
    }

    //Controlador para forzar cierre de sesion
    public function forzar_cierre_sesion_controlador()
    {
        session_unset();
        session_destroy();
        if (headers_sent()) {
            return "<script>window.location.href='" . SERVERURL . "login/'; </script> ";
        } else {
            return header("Location: " . SERVERURL . "login/");
        }
    }

    //Controlador para cerrar la sesion
    public function cerrar_sesion_controlador()
    {
        session_start(['name' => 'SP']);
        $token = mainModel::decryption($_POST['token']);
        $usuario = mainModel::decryption($_POST['usuario']);

        if ($token == $_SESSION['token_sp'] && $usuario == $_SESSION['usuario_sp']) {
            session_unset();
            session_destroy();
            $alerta = [
                "Alerta" => "redireccionar",
                "URL" => SERVERURL . "login/"
            ];
        } else {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error inesperado",
                "Texto" => "No se pudo cerrar la sesion en el sistema",
                "Tipo" => "error"
            ];
        }
        echo json_encode($alerta);
    }
}
