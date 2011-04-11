<script type="text/javascript" src="mod/intern/javascript/hider/hider.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        var sem = new Semaphore(1);
        $(".result-row").each(function(){
            // Create new hider Obj. 
            // Pass ID and Semaphore
            new Hider($(this).attr("id"), sem);
        });
    });
</script>