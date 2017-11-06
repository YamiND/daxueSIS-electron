<?php

include_once '../dbConnect.php';
include_once '../functions.php';

session_start(); // Our custom secure way of starting a PHP session.

if ((login_check($mysqli) == true) && (isTeacher($mysqli)))
{
	getAssignmentList($mysqli);
}
else
{
   	http_response_code(403);
}

function getAssignmentList($mysqli)
{
	if (isset($_POST['classID']) && !empty($_POST['classID']))
	{
		$classID = $_POST['classID'];

	    if ($stmt = $mysqli->prepare("SELECT assignmentName, assignmentPointsPossible FROM assignments WHERE assignmentClassID = ?"))
	    {
	    	$stmt->bind_param('i', $classID);

	    	if ($stmt->execute())
	    	{
	    		$stmt->bind_result($assignmentName, $assignmentPointsPossible);
	    		$stmt->store_result();
			?>
				<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
					<thead>
				    	<tr>
				      		<th>Assignment Name</th>
				      		<th>Assignment Points Possible</th>
				    	</tr>
				  	</thead>
				  	<tbody>
				<?php
                        
                    while ($stmt->fetch())
                    {
                ?>
                      <tr>
                        <td><?php echo $assignmentName; ?></td>
                        <td><?php echo $assignmentPointsPossible; ?></td>
                      </tr>
                <?php
                    }          
                      ?>
                      </tbody>
                    </table>
				
				<?php
	    	}
	    	else
	    	{
	    		?> 
   				<script type="text/javascript">
					new PNotify({
                                  title: 'Error Fetching Assignments!',
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
                              title: 'Error Creating Assignment!',
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
   		http_response_code(403);
	}
}

?>