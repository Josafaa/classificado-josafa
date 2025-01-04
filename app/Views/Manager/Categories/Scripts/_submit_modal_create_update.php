<script>

//TUDO CERTO AQUI NÃO MECHER!


$('#categories-form').submit(function(e) {

    e.preventDefault(); // Impede o envio padrão do formulário

    var form = this; // Referência ao formulário atual


    $.ajax({
        url: $(form).attr('action'), // URL do backend
        method: $(form).attr('method'), // Método definido no formulário
        data: new FormData(form), // Dados do formulário com suporte a arquivos
        processData: false, // Não processar os dados
        dataType: 'JSON', // Resposta esperada no formato JSON
        contentType: false,
        beforeSend: function(){
            $(form).find("span.error-text").text(''); // Limpa erros anteriores
        },
        success: function(response) {
            
            window.refreshCSRFToken(response.token);

            if (response.success == false) {

                toastr.error('Verifique os erros e tente novamente');

                $.each(response.errors, function(field, value) {

                    console.log(field);

                    $(form).find('span.' + field).text(value);

                });

                return;
            }

            // Se a categoria foi salva com sucesso
            toastr.sucess(response.massage);

            $('#categoryModal').modal('hide');

            $(form)[0].reset();

            $('#dataTable').DataTable().ajax.reload(null, false);

            $('.modal-title').text('Criar categoria');

            $(form).attr('action', '<?php echo route_to('categories.create'); ?>');
            $(form).find('input[name="id"]').val('');
            $(['name=_method']).remove();
        },
        error: function(xhr, status, error) {
            console.log('Erro Ajax:', xhr.responseText); // Exibe o erro completo no console
            alert('Error ajax _submit_modal_create_update.php');
        }
    });
});

</script>