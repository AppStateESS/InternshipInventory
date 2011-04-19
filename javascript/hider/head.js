<script type="text/javascript" src="mod/intern/javascript/hider/hider.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        var id = {OPEN};
        $(".result-row").each(function(){
            // Create new hider Obj. 
            // Pass ID.
            var hiderId = $(this).attr("id");
            var hider = new Hider(hiderId);
            // If the 'open row id' is equal to this Hider then open it.
            if(hiderId == id){
                hider.show();
            }
        });
    });
</script>