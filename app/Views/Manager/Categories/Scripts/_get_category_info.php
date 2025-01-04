<script>
    ////////////////////////////////////////////////////////////////
    //TUDO CERTO AQUI NÃO PRECISA DE ALTERAÇÃO SÓ FURUTRAS AO CASO!//
    //AS INFORMAÇÕES SÃO RECEBIDAS AQUI DA MODAL E SÃO ENVIADAS PARA CAtegoriesController.php//
    ////////////////////////////////////////////////////////////////


    //ISSO AQUI ATIVA O MODAL EDITAR/UPDATE
    $(document).on('click', '#updateCategoryBtn', function() {

        var id = $(this).data('id');

        var url = '<?php echo route_to('categories.get.info'); ?>';

        $.get(url , {

            id: id

        }, function(response) {

            $('#categoryModal').modal('show');// Abre o modal
            $('.modal-title').text('Atualizar categoria');// Atualiza o título do modal
            $('#categories-form').attr('action', '<?php echo route_to('categories.update'); ?>');// Atualiza o atributo 'action' do formulário
            // Preenche os campos do formulário com os valores recebidos
            $('#categories-form').find('input[name="id"]').val(response.category.id);
            $('#categories-form').find('input[name="name"]').val(response.category.name);
            $('#categories-form').append("<input type='hidden' name='_method' value='PUT'>"); // Adiciona o campo `_method` ao formulário
            $('#boxParents').html(response.parents);// Adiciona as opções de categorias pai no boxParents
            $('#categories-form').find("span.error-text").text('');// Limpa as mensagens de erro
        }, 'json');
    });
</script>