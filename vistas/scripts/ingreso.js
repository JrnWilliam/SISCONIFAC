var tablaingresos

function IniciarIngresos()
{
    MostrarFormularioIngreso(false)
    ListarRegistrosIngreso()

    $("#FormularioRegistroIngresos").on("submit",function(e)
    {
        GuardarRegistroIngreso(e)
    })
}

function MostrarFormularioIngreso(valor)
{

}

function ListarRegistrosIngreso()
{

}

IniciarIngresos()