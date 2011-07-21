<script type="text/javascript" src="{source_http}mod/intern/javascript/hider/hider.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $(".result-row").each(function(){
            // Create new hider Obj. 
            // Pass ID.
            var hiderId = $(this).attr("id");
            var hider = new Hider(hiderId);
        });
    });
</script>