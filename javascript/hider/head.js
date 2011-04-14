<script type="text/javascript" src="mod/intern/javascript/hider/hider.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $(".result-row").each(function(){
            // Create new hider Obj. 
            // Pass ID.
            new Hider($(this).attr("id"));
        });
    });
</script>