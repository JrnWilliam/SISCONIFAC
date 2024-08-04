function IniciarBackUp(event)
{
    event.preventDefault()
    RealizarBackUp()
}

function RealizarBackUp()
{
    
    $.ajax(
        {
            url: '../ajax/BackUp.php',
            type: 'GET',
            success: function(response)
            {
                bootbox.alert("Se Realizo Correctamente el BackUp de la BD")
            },
            error: function(xhr, status, error)
            {
                bootbox.alert("Error Al Realizar El BackUp: " + error)
            }
        }
    )
}