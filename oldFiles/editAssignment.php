<?php
  include_once 'includes/dbConnect.php';
  include_once 'includes/functions.php';

  session_start();

  // Set permission restrictions here
  if (!isTeacher($mysqli))
  {
    header("Location: /page_403.php");

  }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Edit Assignment</title>

   <?php include("includes/genericCSS.php"); ?>
   
    <!-- Select2 -->
    <link href="vendors/select2/dist/css/select2.min.css" rel="stylesheet">
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <?php 
          include("includes/navPanel.php"); 
        ?>
        
        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Edit Assignment</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />
                    <div id="form-content"></div>
                    <form method="post" id="edit-assignment-form" class="form-horizontal form-label-left">
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Select Class to edit Assignment for</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <select class="select2_single form-control" tabindex="-1" name="classID" id="classID">
                            
                          </select>
                        </div>
                      </div>
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-5">
                          <button type="submit" class="btn btn-success" name="classSelect" id="classSelect">Next</button>
                        </div>
                      </div>
                    </form>

                    <form method="post" id="edit-assignment-form-2" class="form-horizontal form-label-left">
                      <input type="hidden" id="selectedClassID" name="selectedClassID">
                      
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="assignmentID">Assignment Name <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <select class="select2_single form-control" tabindex="-1" name="assignmentID" id="assignmentID">
                            
                          </select>
                        </div>
                      </div>
                      
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-5">
                          <button type="button" class="btn btn-primary" onclick="switchClass()">Switch Class</button>
                          <button type="submit" class="btn btn-success" name="edit" value="edit">Choose Assignment</button>
                        </div>
                      </div>
                    </form>

                    <form method="post" id="edit-assignment-form-3" class="form-horizontal form-label-left">
                      <input type="hidden" id="selected-class-id" name="selected-class-id">
                      <input type="hidden" id="selected-assignment-id" name="selected-assignment-id">

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="assignment-name">Assignment Name <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" id="assignment-name" name="assignment-name" required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="assignment-points-possible">Assignment Points Possible <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="number" min="0" id="assignment-points-possible" name="assignment-points-possible" required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>
                      
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-5">
                          <button type="button" class="btn btn-primary" onclick="switchAssignment()">Switch Assignment</button>
                          <button type="submit" class="btn btn-success" name="edit" value="edit">Edit Assignment</button>
                        </div>
                      </div>
                    </form>

                  </div>
                </div>
              </div>
            </div>
            
          </div>
        </div>
        <!-- /page content -->

        <?php include("includes/footer.php"); ?>
      </div>
    </div>

    <?php include("includes/genericJS.php"); ?>
    <!-- bootstrap-progressbar -->
    <script src="vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
    <!-- Select2 -->
    <script src="vendors/select2/dist/js/select2.full.min.js"></script>
    <!-- Custom Theme Scripts -->
    <script src="js/custom.min.js"></script>


    <!--Ajax Submit Form-->
    <script>
      $(document).ready(function() {  

        $("#edit-assignment-form-2").hide();
        $("#edit-assignment-form-3").hide();

        // submit form using $.ajax() method
        $('#edit-assignment-form').submit(function(e){
          e.preventDefault(); // Prevent Default Submission
          
          $.ajax({
            url: '/includes/assignments/deleteAssignmentSelect.php',
            type: 'POST',
            data: $(this).serialize() // it will serialize the form data
          })
          .done(function(data){
            $("#edit-assignment-form").hide();
            $("#selectedClassID").val($("#classID").val());
            $('#assignmentID').fadeIn('slow').html(data);

            $("#edit-assignment-form-2").show();

            $("#assignmentID").select2({
              placeholder: "Select Assignment",
              allowClear: true
            });

          })
          .fail(function(){
            alert('Ajax Submit Failed ...');  
          });
        });
    

        // submit form using $.ajax() method
        $('#edit-assignment-form-2').submit(function(e){
          e.preventDefault(); // Prevent Default Submission
          
          $.ajax({
            url: '/includes/assignments/editAssignmentSelect.php',
            type: 'POST',
            dateType: 'JSON',
            data: $(this).serialize() // it will serialize the form data
          })
          .done(function(data){
              var assignmentInfo = JSON.parse(data);

              $('#selected-class-id').val($('#selectedClassID').val());
              $('#selected-assignment-id').val($('#assignmentID').val());

              $('#assignment-name').val(assignmentInfo[0].assignmentName);
              $('#assignment-points-possible').val(assignmentInfo[0].assignmentPointsPossible);

              $("#edit-assignment-form-2").hide();

              $("#edit-assignment-form-3").show();
          })
          .fail(function(){
            alert('Ajax Submit Failed ...');  
          });
        });

        

        // submit form using $.ajax() method
        $('#edit-assignment-form-3').submit(function(e){
          e.preventDefault(); // Prevent Default Submission
          
          $.ajax({
            url: '/includes/assignments/editAssignment.php',
            type: 'POST',
            data: $(this).serialize() // it will serialize the form data
          })
          .done(function(data){
              $('#form-content').fadeIn('slow').html(data);

              $("#edit-assignment-form-3").hide();
              $("#edit-assignment-form-3")[0].reset();
              $("#edit-assignment-form-2")[0].reset();

              // On load have our classes populate
              loadTeacherClasses();
              $('#classID').val([]).trigger('change');
              $("#edit-assignment-form").show();
          })
          .fail(function(){
            alert('Ajax Submit Failed ...');  
          });
        });

        function loadTeacherClasses() {
        $.ajax({
            url:'/includes/assignments/assignmentClassList.php',
            type:'POST',
            success:function(results) {
                $("#classID").html(results);
            }
          });
        }

        // On load have our Classes populate
        loadTeacherClasses();

      });

      function switchClass()
      {
        // Reset our form values, hide the form, and show the form
        $("#edit-assignment-form-2").hide();
        $("#edit-assignment-form-2")[0].reset();

        $('#classID').val([]).trigger('change');
        $("#edit-assignment-form").show();
      }

      function switchAssignment()
      {
        // Reset our form values, hide the form, and show the form
        $("#edit-assignment-form-3").hide();
        $("#edit-assignment-form-3")[0].reset();

        $('#assignmentID').val([]).trigger('change');
        $("#edit-assignment-form-2").show();
      }
    </script>

    <!-- Select2 -->
    <script>
      $(document).ready(function() {
        $("#classID").select2({
          placeholder: "Select Class Name",
          allowClear: true
        });
      });
    </script>
    <!-- /Select2 -->
  </body>
</html>
