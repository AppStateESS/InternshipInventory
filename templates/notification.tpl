<script type="text/javascript">
	$(document).ready(function() {
		if ($(".nq").text() !== "") {
			$(".nq-container").hide();
			$(".nq-container").slideDown();
		}
	});
</script>
<div class="nq-container">  
    <!-- BEGIN NOTIFICATIONS -->
    <div class="notification">
    <!-- BEGIN ERROR -->
    <p class="nq error">{ERROR}</p>
    <!-- END ERROR -->
    <!-- BEGIN WARNING -->
    <p class="nq warning">{WARNING}</p>
    <!-- END WARNING -->
    <!-- BEGIN SUCCESS -->
    <p class="nq success">{SUCCESS}</p>
    <!-- END SUCCESS -->

    <!-- BEGIN UNKNOWN -->
    <p class="nq unknown">{UNKNOWN}</p>
    <!-- END UNKNOWN -->
    </div>
    <!-- END NOTIFICATIONS -->
</div>
