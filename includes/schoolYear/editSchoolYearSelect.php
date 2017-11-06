<?php

include_once '../dbConnect.php';
include_once '../functions.php';

session_start(); // Our custom secure way of starting a PHP session.

if ((login_check($mysqli) == true) && (isAdmin($mysqli)))
{
	editSchoolYearSelect($mysqli);
}
else
{
   	http_response_code(403);
}

function editSchoolYearSelect($mysqli)
{
	if (isset($_POST['schoolYearID']) && !empty($_POST['schoolYearID']))
	{
		$schoolYearID = $_POST['schoolYearID'];

		if ($stmt = $mysqli->prepare("SELECT schoolYearID, fallSemesterStart, fallSemesterEnd, springSemesterStart, springSemesterEnd FROM schoolYear WHERE schoolYearID = ?"))
		{
			$stmt->bind_param('i', $schoolYearID);

			if ($stmt->execute())
			{
				$stmt->bind_result($schoolYearID, $fallSemesterStart, $fallSemesterEnd, $springSemesterStart, $springSemesterEnd);
				$stmt->store_result();
				$stmt->fetch();

				$return_arr[] = array(
										"schoolYearID" => $schoolYearID,
										"fallSemesterStart" => $fallSemesterStart,
					                    "fallSemesterEnd" => $fallSemesterEnd,
					                    "springSemesterStart" => $springSemesterStart,
					                    "springSemesterEnd" => $springSemesterEnd,
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
	                          title: 'Error Editing School Year!',
	                          text: 'Could not select school year from database',
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
                          title: 'Error Editing School Year!',
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