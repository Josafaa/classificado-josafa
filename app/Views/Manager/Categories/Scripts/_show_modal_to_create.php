<script>
    ////////////////////////////////////////////////////////////////
    //TUDO CERTO AQUI NÃO PRECISA DE ALTERAÇÃO SÓ FURUTRAS AO CASO!//
    ////////////////////////////////////////////////////////////////

    //ISSO AQUI ATIVA O MODAL
    $(document).on('click', '#createCategoryBtn', function() {

        $('.modal-title').text('Criar categoria');// Atualiza o título do modal
        
        $('#categoryModal').modal('show');// Abre o modal
        $(['name=_method']).remove();//ESTÁ REMOVENDO QUALQUER _METHOOD DO FORMULÁRIO
        $('#categories-form')[0].reset(); //Reseta o Formulário
        $('#categories-form').attr('action', '<?php echo route_to('categories.create'); ?>');// Cria um novo dado no banco de dados
        $('#categories-form').find("span.error-text").text('');// Limpa as mensagens de erro


        var url = '<?php echo route_to('categories.parents'); ?>';

        $.get(url, function(response) {

            $('#boxParents').html(response.parents);
            
        }, 'json');
    });
</script>