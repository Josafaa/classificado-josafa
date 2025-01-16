<script>
    $(document).on('click', '#archiveCategoryBtn', function() {
        var id = $(this).data('id');
        var url = '<?php echo route_to('categories.archive'); ?>';

        $.post(url, {
            _method: 'PUT', // Spoofing do request
            id: id
        }, function(response) {
            toastr.success(response.message);
            $('#dataTable').DataTable().ajax.reload(null, false);
        }, 'json').fail(function(){
            toastr.error('backend-archive-script');
        });
    });
</script>