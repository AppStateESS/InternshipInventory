<script type="text/javascript">
     $(document).ready(function(){
             $("#export-csv").click(function(){
                     // Get ID of each row item.
                     var ids = new Array();
                     $(".result-row").each(function(){
                             var id = $(this).attr("id");
                             ids.push(id);
                         });
                     var url = 'index.php?module=intern&action=csv';
                     for(var id in ids){
                         url += '&ids['+id+']='+ids[id];
                     }
                     window.location = url;
                 });
         });
</script>