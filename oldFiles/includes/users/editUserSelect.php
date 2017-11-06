<?php

include_once '../dbConnect.php';
include_once '../functions.php';

session_start(); // Our custom secure way of starting a PHP session.

if ((login_check($mysqli) == true) && (isAdmin($mysqli)))
{
	editUserForm($mysqli);
}
else
{
   	http_response_code(403);
}

function editUserForm($mysqli)
{
	if (isset($_POST['userID']) && !empty($_POST['userID']))
	{
		$userID = $_POST['userID'];

		if ($stmt = $mysqli->prepare("SELECT userEmail, userFirstName, userLastName, isAdmin, isTeacher, isStudent, studentID, studentMajor FROM users WHERE userID = ?"))
		{
			$stmt->bind_param('i', $userID);

			if ($stmt->execute())
			{
				$stmt->bind_result($userEmail, $userFirstName, $userLastName, $isAdmin, $isTeacher, $isStudent, $studentID, $studentMajor);
				$stmt->store_result();
				$stmt->fetch();

				$return_arr[] = array(
										"userID" => $userID,
										"userEmail" => $userEmail,
					                    "userFirstName" => $userFirstName,
					                    "userLastName" => $userLastName,
					                    "isAdmin" => $isAdmin,
					                    "isTeacher" => $isTeacher,
					                    "isStudent" => $isStudent,
					                    "studentID" => $studentID,
					                    "studentMajor" => $studentMajor,
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
	                          title: 'Error Editing Account!',
	                          text: 'Could not select user from database',
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
                          title: 'Error Editing Account!',
                          text: 'SQL Prepare for Edit failed',
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
                          title: 'Error Editing Account!',
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