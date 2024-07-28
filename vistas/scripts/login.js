$("#FrmLogin").on('submit', function(e)
{
    e.preventDefault();
    login = $("#login").val()
    clave = $("#clave").val()

    $.post("../ajax/Usuario.php?operacion=VerificarSesion",
    {
        "loginacceso":login,
        "claveacceso":clave
    },
    function(data)
    {
        if(data != "null")
        {
            $(location).attr("href","VEscritorio.php");
        }
        else
        {
            bootbox.alert("Usuario y/o Contraseña Incorrectos, Por favor Verifique")
        }
    })
})