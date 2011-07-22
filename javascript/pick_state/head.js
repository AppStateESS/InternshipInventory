<script type="text/javascript">
    $(document).ready(function(){
        $('.add-state').click(function(){
            abbr = $(this).attr('id');
            $.get('index.php?module=intern&action=add_state', {'abbr' : abbr},
                function(data) {
                    location.reload(true);
                });
        });
        
        $('.remove-state').click(function(){
            abbr = $(this).attr('id');
            $.get('index.php?module=intern&action=remove_state', {'abbr' : abbr},
                function(data) {
                    location.reload(true);
                });
        });
    });
</script>
