<?php

include_once '../dbConnect.php';
include_once '../functions.php';

session_start(); // Our custom secure way of starting a PHP session.

if ((login_check($mysqli) == true) && (isTeacher($mysqli)))
{
	deleteAssignment($mysqli);
}
else
{
   	http_response_code(403);
}

function deleteAssignment($mysqli)
{
	if (isset($_POST['selectedClassID'], $_POST['assignmentID']) && !empty($_POST['selectedClassID']) && !empty($_POST['assignmentID']))
	{
		$classID = $_POST['selectedClassID'];
		$assignmentID = $_POST['assignmentID'];

		if ($stmt = $mysqli->prepare("DELETE FROM assignments WHERE assignmentID = ? AND assignmentClassID = ?"))
		{
			$stmt->bind_param('ii', $assignmentID, $classID);

			if ($stmt->execute())
			{
				?> 
				<script type="text/javascript">
				new PNotify({
	                          title: 'Assignment Deleted!',
	                          text: 'Assignment deleted from class.',
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
	                          title: 'Error Fetching Data!',
	                          text: 'Could not execute assignment query.',
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
                          title: 'Error Fetching Data!',
                          text: 'SQL Prepare for Deletion failed',
                          type: 'error',
                          styling: 'bootstrap3'
                      });
			</script>
	<?php
				exit;
		}
	}
	else
	{
   		?> 
			<script type="text/javascript">
				new PNotify({
                          title: 'Error Deleting Data!',
                          text: 'Data not sent',
                          type: 'error',
                          styling: 'bootstrap3'
                      });
			</script>
	<?php
				exit;
	}
}

?>