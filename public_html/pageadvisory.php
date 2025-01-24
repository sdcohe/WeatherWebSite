<?php 
require_once "classes/nwsadvisories.php";
$advisories = new NWSAdvisories();
?>

<script>
$(document).ready(function(){
	
	$("#alertDisplay").css("display", "none");
	
	// get weather advisories every 10 minutes
	$(document).everyTime(advisoriesRefreshRate, retrieveNWSAdvisories);
});
</script>

<div id="alertDisplay" data-bind="visible: isWarningInEffect(HighestPriorityAdvisory())" >
	<div data-bind="style: {'background': WarningBoxColor, 'color': WarningBoxBackgroundColor}" 
		class="advisoryBox" style="background:<?php print warningBoxColor($advisories->getHighestPriorityAdvisory())?>; color:<?php print contrastingTextColor(warningboxColor($advisories->getHighestPriorityAdvisory()))?>;">
		<a href="advisories.php" data-bind="style: {'color': WarningBoxBackgroundColor}" style="color:<?php print contrastingTextColor(warningboxColor($advisories->getHighestPriorityAdvisory()))?>;">
			<b>
				<span data-bind="text: HighestPriorityAdvisory()"><?php print trim($advisories->getHighestPriorityAdvisory())?></span>
			</b>
				<span data-bind="visible: NumberOfAdvisories() > 1;"> and <span data-bind="text: NumberOfAdvisories()-1;"></span> more advisories.</span>
			 ... [Click here for more]
		</a><br />
	</div>
</div>
