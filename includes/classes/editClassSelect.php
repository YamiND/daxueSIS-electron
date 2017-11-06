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

		if ($stmt = $mysqli->prepare("SELECT classes.className, classes.classStartTime, classes.classEndTime, users.userID, users.userFirstName, users.userLastName, users.userEmail FROM users INNER JOIN (classes) ON (classes.classTeacherID = users.userID) INNER JOIN (schoolYear) ON (classes.schoolYearID = schoolYear.schoolYearID AND fallSemesterStart <= CURDATE() AND springSemesterEnd >= CURDATE()) WHERE classID = ?"))
		{
			$stmt->bind_param('i', $classID);

			if ($stmt->execute())
			{
				$stmt->bind_result($className, $classStartTime, $classEndTime, $teacherID, $teacherFirstName, $teacherLastName, $teacherEmail);
				$stmt->store_result();
				$stmt->fetch();

				$return_arr[] = array(
										"classID" => $classID,
										"className" => $className,
					                    "classStartTime" => $classStartTime,
					                    "classEndTime" => $classEndTime,
					                    "teacherID" => $teacherID,
					                    "teacherFirstName" => $teacherFirstName,
					                    "teacherLastName" => $teacherLastName,
					                    "teacherEmail" => $teacherEmail,
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