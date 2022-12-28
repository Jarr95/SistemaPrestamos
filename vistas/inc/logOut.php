<script>
    let btn_logOut = document.querySelector(".btn-exit-system");

    btn_logOut.addEventListener('click', function(e) {
        e.preventDefault();
        Swal.fire({
            title: '¿Desea cerrar sesión?',
            text: "La sesion actual se cerrara en el sistema",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Aceptar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.value) {

                let url = '<?php echo SERVERURL; ?>ajax/loginAjax.php';
                let token = '<?php echo $login->encryption($_SESSION['token_sp']); ?>';
                let usuario = '<?php echo $login->encryption($_SESSION['usuario_sp']); ?>';

                let datos = new FormData();
                datos.append("token", token);
                datos.append("usuario", usuario);

                fetch(url, {
                        method: 'POST',
                        body: datos
                    })
                    .then(respuesta => respuesta.json())
                    .then(respuesta => {
                        return alertas_ajax(respuesta);
                    });
            }
        });
    });
</script>