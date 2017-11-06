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
	if (isset($_POST['selectedClassID'], $_POST['assignmentID']) && !empty($_POST['selectedClassID']) && !empty($_POST['assignmentID']))
	{
		$classID = $_POST['selectedClassID'];
		$assignmentID = $_POST['assignmentID'];

	    if ($stmt = $mysqli->prepare("SELECT users.userFirstName, users.userLastName, studentClassIDs.studentID, assignments.assignmentName, grades.gradeAssignmentPointsScored, assignments.assignmentPointsPossible, classes.className FROM studentClassIDs LEFT OUTER JOIN users ON (studentClassIDs.studentID = users.userID) LEFT OUTER JOIN grades ON (grades.gradeStudentID = studentClassIDs.studentID AND grades.gradeAssignmentID = ? AND grades.gradeClassID = ?) LEFT OUTER JOIN assignments ON (assignments.assignmentID = ?) LEFT OUTER JOIN classes ON (classes.classID = studentClassIDs.classID) WHERE studentClassIDs.classID = ?"))
	    {
	    	$stmt->bind_param('iiii', $assignmentID, $classID, $assignmentID, $classID);

	    	if ($stmt->execute())
	    	{
	    		$stmt->bind_result($studentFirstName, $studentLastName, $studentID, $assignmentName, $assignmentPointsScored, $assignmentPointsPossible, $className);
	    		$stmt->store_result();
			?>
				<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
					<thead>
				    	<tr>
				      		<th>Student Name</th>
				      		<th>Assignment Points Scored</th>
				      		<th>Assignment Points Possible</th>
				      		<th>Assignment Grade</th>
				    	</tr>
				  	</thead>
				  	<tbody>
				  	
				  	<input type="hidden" id="tableClassID" name="tableAssignmentID" value="<?php echo $classID; ?>">
				  	<input type="hidden" id="tableAssignmentID" name="tableAssignmentID" value="<?php echo $assignmentID; ?>">
				<?php
                        
                    while ($stmt->fetch())
                    {
                    	if (is_null($assignmentPointsScored))
                    	{
                    		$assignmentPointsScored = "0";
                    	}
                ?>
                      <tr class="studentGrades">
                      	<input type="hidden" class="studentID" name="studentID" value="<?php echo $studentID; ?>">
                      	<td><?php echo "$studentLastName, $studentFirstName"; ?></td>
                        <td><input type="number" min="0" name="assignmentPointsScored" value="<?php echo $assignmentPointsScored; ?>"></td>
                        <td><?php echo "/" . $assignmentPointsPossible; ?></td>
                        <td><?php echo 	number_format((float) (($assignmentPointsScored / $assignmentPointsPossible) * 100), 2, '.', '')  . "%"; ?></td>
                      </tr>
                <?php
                    }          
                      ?>
                      </tbody>
                    </table>
                    <form method="post" id="switch-class-form" class="form-horizontal form-label-left">
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-5">
                        	<button type="button" class="btn btn-primary" onclick="switchAssignment()">Switch Assignment</button>
                         	<button type="button" class="btn btn-success" onclick="applyGrades()">Apply Grades</button>
                        </div>
                      </div>
                    </form>

		        	<h3>Class Name: <?php echo $className; ?></h3>
       	        	<h2>Assignment Name: <?php echo $assignmentName; ?></h2>


       	        	<script type="text/javascript">
       	        		
       	        		function applyGrades()
       	        		{
						    $('.studentGrades').each(function() {
						        var row = $(this)
							 	var studentID = row.find('[name=studentID]').val()
							    var assignmentPointsScored = row.find('[name=assignmentPointsScored]').val()
								var assignmentID = $("#tableAssignmentID").val();
								var classID = $("#tableClassID").val();

						        $.ajax({
						            url:'/includes/assignments/gradeAssignment.php',
						            type:'POST',
						            data: { 
						            		assignmentID: assignmentID,
						            		studentID: studentID,
						            		assignmentPointsScored: assignmentPointsScored,
						            		classID: classID,
						            	  },
						            success:function(results) 
						            {
						                $("#message-content").html(results);
						            }
						          });
						        //$.ajax (do your AJAX call here using values of query and text
						    });

						    new PNotify({
				                                  title: 'Grades Updated',
				                                  text: 'Grades have been updated',
				                                  type: 'success',
				                                  styling: 'bootstrap3'
				                              });
						    switchAssignment();
						}
       	        	</script>
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