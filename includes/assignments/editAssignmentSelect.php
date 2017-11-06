<?php

include_once '../dbConnect.php';
include_once '../functions.php';

session_start(); // Our custom secure way of starting a PHP session.

if ((login_check($mysqli) == true) && (isTeacher($mysqli)))
{
	editAssignmentSelect($mysqli);
}
else
{
   	http_response_code(403);
}

function editAssignmentSelect($mysqli)
{
	if (isset($_POST['selectedClassID'], $_POST['assignmentID']) && !empty($_POST['selectedClassID']) && !empty($_POST['assignmentID']))
	{
		$classID = $_POST['selectedClassID'];
		$assignmentID = $_POST['assignmentID'];

		if ($stmt = $mysqli->prepare("SELECT assignmentName, assignmentPointsPossible FROM assignments WHERE assignmentID = ? AND assignmentClassID = ?"))
		{
			$stmt->bind_param('ii', $assignmentID, $classID);

			if ($stmt->execute())
			{
				$stmt->bind_result($assignmentName, $assignmentPointsPossible);
				$stmt->store_result();
				$stmt->fetch();

				$return_arr[] = array(
										"assignmentName" => $assignmentName,
										"assignmentPointsPossible" => $assignmentPointsPossible,
					                );
				// Encoding array in JSON format
				echo json_encode($return_arr);
				die;
			}
			else
			{
		?> 
				<script type="text/javascript">
					new PNotify({
	                          title: 'Error Selecting Assignment!',
	                          text: 'Could not select assignment from database',
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
                          title: 'Error Selecting Assignment!',
                          text: 'SQL Prepare for select failed',
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
                          title: 'Error Selecting Assignment!',
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