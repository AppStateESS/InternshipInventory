<script type="text/javascript">
    $(document).ready(function(){
        $("input:button[name=reset]").click(function(){
            /* Clear all search fields */
            $("input[name='name']").val("");
            $("select").val("");
            $("input:checkbox").attr('checked', false);
            $("input:radio").attr('checked', false);
        });
    });
</script>