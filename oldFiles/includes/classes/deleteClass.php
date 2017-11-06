<?php

include_once '../dbConnect.php';
include_once '../functions.php';

session_start(); // Our custom secure way of starting a PHP session.

if ((login_check($mysqli) == true) && (isAdmin($mysqli)))
{
	deleteClass($mysqli);
}
else
{
   	http_response_code(403);
}

function deleteClass($mysqli)
{
	if (isset($_POST['classID']) && !empty($_POST['classID']))
	{
		$classID = $_POST['classID'];

		if ($stmt = $mysqli->prepare("DELETE FROM classes WHERE classID = ?"))
		{
			$stmt->bind_param('i', $classID);

			if ($stmt->execute())
			{
		?> 
   				<script type="text/javascript">
						new PNotify({
                              title: 'Class Deleted',
                              text: 'Class has been deleted',
                              type: 'success',
                              styling: 'bootstrap3'
                          });
						$('#classID').val([]).trigger('change');
						$("#classID option[value='<?php echo $classID; ?>']").remove();

					</script>

		<?php
			}
			else
			{
		?> 
				<script type="text/javascript">
					new PNotify({
	                          title: 'Error Deleting Class!',
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
                          title: 'Error Deleting Class!',
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
                          title: 'Error Deleting Class!',
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