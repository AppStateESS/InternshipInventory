<script type="text/javascript">
$(document).ready(function(){
    var missing = {MISSING};
    // Toggle missing class if user types stuff into field
    $(".missing").keyup(function(){
        doSomething(this)
    });
    $(".missing").change(function(){
        doSomething(this)
    });
    var doSomething = function(it){
        if($(it).val() == ""){
            // Nothing in field. Show CSS for missing class.
            $(it).addClass("missing");
        }else if($(it).hasClass("missing")){
            // User typed something in so remove class.
            $(it).removeClass("missing");
        }
    }
});
</script>