$("body").on("submit", ".emitirCertificado", function (e) {
    let originalEvent = e;
    var shouldSubmit = false;
    var form = $(this);

    $.ajax({
        url: base_url + "controle.php?emiteCertificado=true&cid=" + form.find("input[name=curso_id]").val(),
        async: false,
        type: "POST",
        data: form.serialize(),
        dataType: "json",
        success: function (response) {
            if (response.success === "true" && response.temp === "true") {
                originalEvent.preventDefault();
                
                let certificadoTemp = document.createElement("certificadoTemp");
                let typeData = "";

                if (form.find("input[name=type]").val()) {
                    typeData = ', "type": "' + form.find("input[name=type]").val() + '"';
                }

                certificadoTemp.setAttribute("data-title", "Certificado Digital Temporário");
                certificadoTemp.setAttribute(
                    "json-datas",
                    '{"id_curso": ' + form.find("input[name=curso_id]").val() + ',"page": "certificados"' + typeData + "}"
                );
                certificadoTemp.setAttribute(
                    "data-page",
                    base_url + "aluno/modals/dialogues/certificado-temporario"
                );

                window_modal(certificadoTemp);
            } else if (response.success === "true") {
                shouldSubmit = true;
            } else if (response.pesquisa !== undefined) {
                pesquisaDeSatisfacao(
                    form.find("input[name=curso_id]").val(),
                    form.find("input[name=cname]").val(),
                    form.find("input[name=type]").val(),
                    base_url,
                    response.pesquisa
                );
                shouldSubmit = false;
            } else if (response.message !== undefined) {
                $.pnotify({
                    title: "Problemas.",
                    text: response.message,
                    type: "error",
                    delay: 5000
                });
                shouldSubmit = false;
            } else {
                if(response.cpfError === "true"){
                    $('#cpfModal').modal('show');
                } else {
                    window.location.href = base_url + "aluno/sala-de-aula/material/" + $("input[name=curso_id]").val();
                }
            }
        },
        error: function () {
            $.pnotify({
                title: "Problemas.",
                text: "Ocorreu um problema na solicitação. Recarregue a página e tente novamente.",
                type: "error",
                delay: 5000
            });
        }
    });

    return shouldSubmit;
});
