<?php

include_once '../dbConnect.php';
include_once '../functions.php';

session_start(); // Our custom secure way of starting a PHP session.

if ((login_check($mysqli) == true) && (isAdmin($mysqli)))
{
	deleteSchoolYear($mysqli);
}
else
{
   	http_response_code(403);
}

function deleteSchoolYear($mysqli)
{
	if (isset($_POST['schoolYearID']) && !empty($_POST['schoolYearID']))
	{
		$schoolYearID = $_POST['schoolYearID'];

		if ($stmt = $mysqli->prepare("DELETE FROM schoolYear WHERE schoolYearID = ?"))
		{
			$stmt->bind_param('i', $schoolYearID);

			if ($stmt->execute())
			{
		?> 
   				<script type="text/javascript">
						new PNotify({
                              title: 'School Year Deleted',
                              text: 'School Year has been deleted',
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
	                          title: 'Error Deleting School Year!',
	                          text: 'Could not delete from database',
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
                          title: 'Error Deleting School Year!',
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
                          title: 'Error Deleting School Year!',
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