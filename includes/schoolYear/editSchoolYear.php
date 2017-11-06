<?php

include_once '../dbConnect.php';
include_once '../functions.php';

session_start(); // Our custom secure way of starting a PHP session.

if ((login_check($mysqli) == true) && (isAdmin($mysqli)))
{
	editSchoolYearForm($mysqli);
}
else
{
   	http_response_code(403);
}

function editSchoolYearForm($mysqli)
{
	if (isset($_POST['selectedSchoolYearID'], $_POST['fall-semester-start'], $_POST['fall-semester-end'], $_POST['spring-semester-start'], $_POST['spring-semester-end']) && !empty($_POST['selectedSchoolYearID']) && !empty($_POST['fall-semester-start']) && !empty($_POST['fall-semester-end']) && !empty($_POST['spring-semester-start']) && !empty($_POST['spring-semester-end']))
	{
		$schoolYearID = $_POST['selectedSchoolYearID'];
		$fallSemesterStart = $_POST['fall-semester-start'];
		$fallSemesterEnd = $_POST['fall-semester-end'];
		$springSemesterStart = $_POST['spring-semester-start'];
		$springSemesterEnd = $_POST['spring-semester-end'];
		

		if (($fallSemesterStart === $fallSemesterEnd && $fallSemesterEnd === $springSemesterStart && $springSemesterStart === $springSemesterEnd) || ($fallSemesterStart > $fallSemesterEnd) || ($springSemesterStart > $springSemesterEnd) || ($fallSemesterStart > $springSemesterStart) || ($fallSemesterStart > $springSemesterEnd))        
		{
			// Time sanitization check
			?> 
			<script type="text/javascript">
			new PNotify({
	                      title: 'Error Editing School Year!',
	                      text: 'Invalid Start/End Date range',
	                      type: 'error',
	                      styling: 'bootstrap3'
	                  });
			</script>
		<?php
			exit;
		}

	    if ($stmt = $mysqli->prepare("SELECT schoolYearID FROM schoolYear WHERE schoolYearID <> ? AND (fallSemesterStart = ? OR fallSemesterEnd = ? OR springSemesterStart = ? OR springSemesterEnd = ?)"))
	    {
	    	$stmt->bind_param('issss', $schoolYearID, $fallSemesterStart, $fallSemesterEnd, $springSemesterStart, $springSemesterEnd);

	    	if ($stmt->execute())
	    	{
	    		$stmt->store_result();

				if ($stmt->num_rows > 0)
				{
	?> 
	   				<script type="text/javascript">
						new PNotify({
	                                  title: 'Error Editing School Year!',
	                                  text: 'Another School Year with the selected time period',
	                                  type: 'error',
	                                  styling: 'bootstrap3'
	                              });
	     			</script>
	<?php
				}
				else
				{
					if ($stmt = $mysqli->prepare("UPDATE schoolYear SET fallSemesterStart = ?, fallSemesterEnd = ?, springSemesterStart = ?, springSemesterEnd = ? WHERE schoolYearID = ?"))
					{
	    				$stmt->bind_param('ssssi', $fallSemesterStart, $fallSemesterEnd, $springSemesterStart, $springSemesterEnd, $schoolYearID);

		    			if ($stmt->execute())    // Execute the prepared query.
						{
	?> 
			   				<script type="text/javascript">
		   						new PNotify({
		                                  title: 'School Year Edited',
		                                  text: 'School Year has been edited',
		                                  type: 'success',
		                                  styling: 'bootstrap3'
		                              });
	   						</script>
	<?php
						}
						else
						{
	?> 
			   				<script type="text/javascript">
								new PNotify({
			                                  title: 'Error Editing School Year!',
			                                  text: 'Data could not be updated in database',
			                                  type: 'error',
			                                  styling: 'bootstrap3'
			                              });
			     			</script>
	<?php
						}
					}
					else
					{
						?> 
		   				<script type="text/javascript">
							new PNotify({
		                                  title: 'Error Editing School Year!',
		                                  text: 'SQL Update Prepare failed',
		                                  type: 'error',
		                                  styling: 'bootstrap3'
		                              });
		     			</script>
	<?php
					}
				}
	    	}
	    	else
	    	{
	    		?> 
   				<script type="text/javascript">
					new PNotify({
                                  title: 'Error Editing School Year!',
                                  text: 'Data could not be executed in DB',
                                  type: 'error',
                                  styling: 'bootstrap3'
                              });
     			</script>
	<?php
	    	}
	    }
	    else
	    {
	?> 
			<script type="text/javascript">
				new PNotify({
                              title: 'Error Editing School Year!',
                              text: 'SQL Query Prepare failed for date range',
                              type: 'error',
                              styling: 'bootstrap3'
                          });
 			</script>
	<?php
	    }
	}
	else
	{
		?> 
   				<script type="text/javascript">
					new PNotify({
                                  title: 'Error Editing School Year!',
                                  text: 'Data not sent',
                                  type: 'error',
                                  styling: 'bootstrap3'
                              });
     			</script>
	<?php
	}	
}

?>