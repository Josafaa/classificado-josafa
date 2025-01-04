<script>
    ////////////////////////////////////////////////////////////////
    //TUDO CERTO AQUI NÃO PRECISA DE ALTERAÇÃO SÓ FURUTRAS AO CASO!//
    ////////////////////////////////////////////////////////////////

    //ISSO AQUI ATIVA O MODAL
    $(document).on('click', '#archiveCategoryBtn', function() {


        var url = '<?php echo route_to('categories.parents'); ?>';

        $.get(url, function(response) {

            $('#boxParents').html(response.parents);
            
        }, 'json');
    });
</script>