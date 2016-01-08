<?php
include_once '_dbconfig.inc.php';
?>
<?php
include_once '_header.inc.php';
?>

<?php
/* presente $help_text when not empty */
if ($help_text) {
		echo '<div class="container bg-info 90_percent" >' ;
			echo "<a data-toggle=\"collapse\" data-target=\"#collapse_help_event\" >PSEUDO CODE FOR EVENT_GRID PHP - Later here will be the helptext&nbsp;<i class=\"fa fa-chevron-down\"></i></a>";
			echo "<div id=\"collapse_help_event\" class=\"collapse\"> ";
			include_once 'syllabus_helptxt.php';
			echo "</div>";
		echo "</div><p></p><p></p>";
		
}
?>

<div class="clearfix"></div>
<div class="container 90_percent">
</div>
</div>

