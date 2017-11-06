<?php

include_once '../dbConnect.php';
include_once '../functions.php';

session_start(); // Our custom secure way of starting a PHP session.

if ((login_check($mysqli) == true) && (isTeacher($mysqli)))
{
	deleteAssignmentSelect($mysqli);
}
else
{
   	http_response_code(403);
}

function deleteAssignmentSelect($mysqli)
{
	if (isset($_POST['classID']) && !empty($_POST['classID']))
	{
		$classID = $_POST['classID'];

		if ($stmt = $mysqli->prepare("SELECT assignmentID, assignmentName, assignmentPointsPossible FROM assignments WHERE assignmentClassID = ?"))
		{
			$stmt->bind_param('i', $classID);

			if ($stmt->execute())
			{
				$stmt->bind_result($assignmentID, $assignmentName, $assignmentPointsPossible);
				$stmt->store_result();
				
				while ($stmt->fetch())
		        {
		  ?>
		          <option value="<?php echo $assignmentID; ?>"><?php echo "$assignmentName - $assignmentPointsPossible"; ?></option>
		  <?php
		        }
				
			}
			else
			{
			?> 
				<script type="text/javascript">
				new PNotify({
	                          title: 'Error Fetching Data!',
	                          text: 'Could not execute class query.',
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
                          text: 'SQL Prepare for Fetch failed',
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
                          title: 'Error Fetching Data!',
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