<script>
    ////////////////////////////////////////////////////////////////
    //TUDO CERTO AQUI NÃO PRECISA DE ALTERAÇÃO SÓ FURUTRAS AO CASO!//
    ////////////////////////////////////////////////////////////////

    //ISSO AQUI ATIVA O MODAL
    $(document).on('click', '#createPlanBtn', function() {

        $('input[name="_id"]').val('');
        $('input[name="_method"]').remove();

        $('.modal-title').text('Criar Plano');// Atualiza o título do modal
        $('#modalPlan').modal('show');// Abre o modal


        $('#plans-form')[0].reset(); //Reseta o Formulário
        $('#plans-form').attr('action', '<?php echo route_to('plans.create'); ?>');// Cria um novo dado no banco de dados
        $('#plans-form').find("span.error-text").text('');// Limpa as mensagens de erro


        var url = '<?php echo route_to('plans.get.recorrences'); ?>';

        $.get(url, function(response) {

            $('#boxRecorrences').html(response.recorrences);
            
        }, 'json');
    });
</script>