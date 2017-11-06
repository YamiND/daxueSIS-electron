<?php

include_once '../dbConnect.php';
include_once '../functions.php';

session_start(); // Our custom secure way of starting a PHP session.

if ((login_check($mysqli) == true) && (isAdmin($mysqli)))
{
	editClassSelect($mysqli);
}
else
{
   	http_response_code(403);
}

function editClassSelect($mysqli)
{
	if (isset($_POST['classID']) && !empty($_POST['classID']))
	{
		$classID = $_POST['classID'];

		if ($stmt = $mysqli->prepare("SELECT classes.className from classes WHERE classID = ?"))
		{
			$stmt->bind_param('i', $classID);

			if ($stmt->execute())
			{
				$stmt->bind_result($className);
				$stmt->store_result();
				$stmt->fetch();

				$return_arr[] = array(
										"classID" => $classID,
										"className" => $className,
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
	                          title: 'Error Fetching Class Info!',
	                          text: 'Could not select class from database',
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
                          title: 'Error Editing Class Info!',
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
                          title: 'Error Editing Class Info!',
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