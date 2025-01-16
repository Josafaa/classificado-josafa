<script>
    ////////////////////////////////////////////////////////////////
    //TUDO CERTO AQUI NÃO PRECISA DE ALTERAÇÃO SÓ FURUTRAS AO CASO!//
    //AS INFORMAÇÕES SÃO RECEBIDAS AQUI DA MODAL E SÃO ENVIADAS PARA CAtegoriesController.php//
    ////////////////////////////////////////////////////////////////


    //ISSO AQUI ATIVA O MODAL EDITAR/UPDATE
    $(document).on('click', '#updatePlanBtn', function() {

        var id = $(this).data('id');

        var url = '<?php echo route_to('plans.get.info'); ?>';

        $.get(url , {

            id: id

        }, function(response) {

            $('#modalPlan').modal('show');// Abre o modal
            $('.modal-title').text('Atualizar Plano');// Atualiza o título do modal
            $('#plans-form').attr('action', '<?php echo route_to('plans.update'); ?>');// Atualiza o atributo 'action' do formulário
            // Preenche os campos do formulário com os valores recebidos
            $('#plans-form').find('input[name="id"]').val(response.plan.id);
            $('#plans-form').find('input[name="name"]').val(response.plan.name);
            $('#plans-form').find('input[name="value"]').val(response.plan.value);
            $('#plans-form').find('input[name="adverts"]').val(response.plan.adverts);
            $('#plans-form').find('textarea[name="description"]').val(response.plan.description);

            $('#plans-form').find('input[name="is_highlighted"]').prop('checked', response.plan.is_highlighted);

            $('#plans-form').append("<input type='hidden' name='_method' value='PUT'>"); // Adiciona o campo `_method` ao formulário
            $('#boxRecorrences').html(response.recorrences);// Adiciona as opções de categorias pai no boxParents
            $('#plans-form').find("span.error-text").text('');// Limpa as mensagens de erro
        }, 'json');
    });
</script>