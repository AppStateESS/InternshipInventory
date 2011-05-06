<script type="text/javascript">
  $(document).ready(function(){
      if($(".notification").text() !== ""){
          $(".notification").hide();
          $(".notification").slideDown();
      }
  });
</script>
<div class="nq-container">
<!-- BEGIN NOTIFICATIONS -->
<!-- BEGIN ERROR -->
<div class="notification nq-error">
  {ERROR}
</div>
<!-- END ERROR -->
<!-- BEGIN WARNING -->
<div class="notification nq-warning">
  {WARNING}
</div>
<!-- END WARNING -->
<!-- BEGIN SUCCESS -->
<div class="notification nq-success">
  {SUCCESS}
</div>
<!-- END SUCCESS -->
<!-- BEGIN UNKNOWN -->
<div class="notification nq-unknown">
  {UNKNOWN}
</div>
<!-- END UNKNOWN -->
<!-- END NOTIFICATIONS -->
</div>
