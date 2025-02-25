const formularios_ajax= document.querySelectorAll(".FormularioAjax");

function enviar_formulario_ajax(e) {
    e.preventDefault();
    
    // enviar los datos
}

formularios_ajax.forEach(formulario => {
    formulario.addEventListener("submit", enviar_formulario_ajax);
});

function alertas_ajax(alerta) {
    if (alerta.Alerta==="simple") {
        Swal.fire({
            icon: "info",
            title: alerta.Titulo,
            text: alerta.Texto,
            confirmButtonText: "Aceptar"
          });
    }
    else if (alerta.Alerta==="recargar") {
        Swal.fire({
            icon: "info",
            title: alerta.Titulo,
            text: alerta.Texto,
            confirmButtonText: "Aceptar"
          }).then((result) => {
                if (result.isConfirmed) {
                    location.reload();
                }
          });
    }
    else if (alerta.Alerta==="limpiar") {
        Swal.fire({
            icon: "info",
            title: alerta.Titulo,
            text: alerta.Texto,
            confirmButtonText: "Aceptar"
          }).then((result) => {
                if (result.isConfirmed) {
                   document.querySelector(".FormularioAjax").reset();
                }
          });
    }
    else if (alerta.Alerta==="redireccionar") {
        windows.location.href=alerta.URL;
    }
}

