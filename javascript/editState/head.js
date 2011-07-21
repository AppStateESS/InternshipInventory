<script type="text/javascript" src="{source_http}mod/intern/javascript/editState/Row.js"></script>
<script type="text/javascript">
//lolwut
    $(document).ready(function(){
        /* Act like a link */
        $(".edit-prog").hover(function(){ $(this).css('text-decoration', 'underline'); },
                                    function() { $(this).css('text-decoration', 'none'); });
        
        /* Create new Row object for each row! */
        $(".edit-prog").each(function(){
            /* Below returns { ID , "edit-prog" } */
            var classes = $(this).attr('class').split(/\s/);
            var id = classes[0];
            var nameSelect = ".prog#"+id;

            new Row(nameSelect, id, "{EDIT_ACTION}");
        });
    });
</script>