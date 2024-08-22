function RestaurarBD()
{
    var rutaarchivo = document.getElementById('script');
    var archivo = rutaarchivo.files[0];

    if (archivo)
    {
        var formData = new FormData();
        formData.append('script', archivo);

        var xhr = new XMLHttpRequest();
        xhr.open('POST', '../ajax/Restaurar.php', true);

        xhr.onload = function ()
        {
            if (xhr.status === 200)
            {
                bootbox.alert('Restauración completada: ' + xhr.responseText);
            }
            else
            {
                bootbox.alert('Error durante la restauración: ' + xhr.status);
            }
        };

        xhr.onerror = function () {
            bootbox.alert('Hubo un problema al intentar realizar la solicitud. Por favor, intente nuevamente.');
        };

        xhr.send(formData);
    }
    else
    {
        bootbox.alert('Por favor, seleccione un archivo antes de restaurar.');
    }
}