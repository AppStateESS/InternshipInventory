<script type="text/javascript" src="{source_http}mod/intern/javascript/editState/Row.js"></script>
<script type="text/javascript">
//lolwut
    $(document).ready(function(){
        /* Act like a link */
        $(".edit-state-prog").hover(function(){ $(this).css('text-decoration', 'underline'); },
                                    function() { $(this).css('text-decoration', 'none'); });
        
        /* Create new Row object for each row! */
        $(".edit-state-prog").each(function(){
            /* Below returns { ID , "edit-state-prog" } */
            var classes = $(this).attr('class').split(/\s/);
            var id = classes[0];
            var nameSelect = ".state-prog#"+id;

            new Row(nameSelect, id, "{EDIT_ACTION}");
        });
    });
</script>