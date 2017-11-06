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

	    if ($stmt = $mysqli->prepare("SELECT studentID FROM studentClassIDs WHERE classID = ?"))
	    {
	    	$stmt->bind_param('i', $classID);

	    	if ($stmt->execute())
	    	{
	    		$stmt->bind_result($studentID);
	    		$stmt->store_result();
				
				?>
	    		<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
					<thead>
				    	<tr>
				      		<th>Student Name</th>
				      		<th>Class Grade</th>
				    	</tr>
				  	</thead>
				  	<tbody>
				<?php 

	    		while ($stmt->fetch())
	    		{

	    			if ($stmt2 = $mysqli->prepare("SELECT users.userFirstName, users.userLastName, ((SUM(grades.gradeAssignmentPointsScored) / SUM(assignments.assignmentPointsPossible)) * 100) AS totalGradePercentage FROM studentClassIDs LEFT OUTER JOIN users ON (users.userID = studentClassIDs.studentID) LEFT OUTER JOIN assignments ON (assignments.assignmentClassID = ?) LEFT OUTER JOIN grades ON (grades.gradeStudentID = studentClassIDs.studentID AND grades.gradeClassID = ? AND grades.gradeAssignmentID = assignments.assignmentID) WHERE studentClassIDs.classID = ? AND studentClassIDs.studentID = ?"))
	    			{
	    				$stmt2->bind_param('iiii', $classID, $classID, $classID, $studentID);

	    				if ($stmt2->execute())
	    				{
	    					$stmt2->bind_result($studentFirstName, $studentLastName, $totalGradePercentage);
	    					$stmt2->store_result();

	    					while ($stmt2->fetch())
	    					{
	    						?>
			                    <tr>
		                      		<td><?php echo "$studentLastName, $studentFirstName"; ?></td>
			                    	<td><?php echo number_format((float) ($totalGradePercentage), 2, '.', '') . "%"; ?></td>
		                      	</tr>
		                		<?php
	    					}
	    				}
	    				else
	    				{
    						?> 
			   				<script type="text/javascript">
								new PNotify({
			                                  title: 'Error Fetching Data!',
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
		                                  title: 'Error Fetching Data!',
		                                  text: 'Data could not be executed in DB',
		                                  type: 'error',
		                                  styling: 'bootstrap3'
		                              });
		     			</script>
						<?php
	    			}
	    		}
			?>
				
              </tbody>
            </table>
            <form method="post" id="switch-class-form" class="form-horizontal form-label-left">
              <div class="ln_solid"></div>
              <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-5">
                	<button type="button" class="btn btn-primary" onclick="switchClass()">Switch Class</button>
                </div>
              </div>
            </form>

       	   	<?php
	    	}
	    	else
	    	{
	    		?> 
   				<script type="text/javascript">
					new PNotify({
                                  title: 'Error Fetching Data!',
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
                              title: 'Error Creating Data!',
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