<script type="text/javascript">
    $(document).ready(function(){
        $("input:button[name=reset]").click(function(){
            /* Clear all search fields */
            $("input[name='name']").val("");
            $("select[name='term_select']").val("");
            $("select[name='dept']").val("");
            $("select[name='major']").val("");
            $("select[name='grad']").val("");
        });
    });
</script>