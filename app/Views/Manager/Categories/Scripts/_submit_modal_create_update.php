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

            // Verifique a resposta
            console.log(response); // Verifica o que está sendo retornado do backend

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
            toastr.success(response.message);

            // Teste de fechamento do modal
            console.log('Fechando o modal...');

            $('#categoryModal').modal('hide'); // Fechar o modal

            $(form)[0].reset(); // Resetar o formulário

            // Atualizar a tabela
            $('#dataTable').DataTable().ajax.reload(null, false);

            // Resetar o título e a ação do formulário
            $('.modal-title').text('Criar categoria');
            $(form).attr('action', '<?php echo route_to('categories.create'); ?>');
            $(form).find('input[name="id"]').val('');
            $('input[name="_method"]').remove();
        },
        error: function() {
            alert('Error ajax _submit_modal_create_update.php');
        }
    });
});

</script>