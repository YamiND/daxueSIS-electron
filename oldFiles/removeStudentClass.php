<?php
  include_once 'includes/dbConnect.php';
  include_once 'includes/functions.php';

  session_start();

  // Set permission restrictions here
  if (!isAdmin($mysqli))
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

    <title>Remove Student from Class</title>

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
                    <h2 id="classHolder">Remove Student from Class</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />
                    <div id="form-content"></div>
                    <form method="post" id="remove-student-form" class="form-horizontal form-label-left">
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Select Class to Remove Student from:</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <select class="select2_single form-control" tabindex="-1" name="classID" id="classID">
                            
                          </select>
                        </div>
                      </div>
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-5">
                          <button type="submit" class="btn btn-success">Next</button>
                        </div>
                      </div>
                    </form>

                    <form method="post" id="remove-student-form-2" class="form-horizontal form-label-left">
                      <input type="hidden" id="selectedClassID" name="selectedClassID">
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="studentID">Student: <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <select class="select2_single form-control" tabindex="-1" name="studentID" id="studentID">
                            
                          </select>
                        </div>
                      </div>
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-5">
                          <button type="button" class="btn btn-primary" onclick="switchClass()">Switch Class</button>
                          <button type="submit" class="btn btn-success" name="edit" value="edit">Remove Student from Class</button>
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

        $("#remove-student-form-2").hide();

        // submit form using $.ajax() method
        $('#remove-student-form').submit(function(e){
          e.preventDefault(); // Prevent Default Submission
          
          $.ajax({
            url: '/includes/classes/assignClassSelect.php',
            type: 'POST',
            dateType: 'JSON',
            data: $(this).serialize() // it will serialize the form data
          })
          .done(function(data){
            $("#remove-student-form").hide();

            var classInfo = JSON.parse(data);

            $('#selectedClassID').val(classInfo[0].classID);
            
            $('#classHolder').text("Class Name: " + classInfo[0].className);
            $("#remove-student-form-2").show();
            $("#studentID").select2({
              placeholder: "Select Student",
              allowClear: true
            });
            console.log(classInfo[0]);
            loadStudents(classInfo[0].classID);

          })
          .fail(function(){
            alert('Ajax Submit Failed ...');  
          });
        });
    

        // submit form using $.ajax() method
        $('#remove-student-form-2').submit(function(e){
          e.preventDefault(); // Prevent Default Submission
          
          $.ajax({
            url: '/includes/classes/removeStudentClass.php',
            type: 'POST',
            data: $(this).serialize() // it will serialize the form data
          })
          .done(function(data){
              $('#form-content').fadeIn('slow').html(data);

              $("#remove-student-form-2").hide();
              $("#remove-student-form-2")[0].reset();
              $('#classHolder').text('Remove Student from Class');
              // On load have our classes populate
              loadClasses();
              $('#classID').val([]).trigger('change');
              $("#remove-student-form").show();
          })
          .fail(function(){
            alert('Ajax Submit Failed ...');  
          });
        });

        function loadClasses() {
        $.ajax({
            url:'/includes/classes/editClassList.php',
            type:'POST',
            success:function(results) {
                $("#classID").html(results);
            }
          });
        }

        function loadStudents(classID) {
        $.ajax({
            url:'/includes/classes/removeClassStudentList.php',
            type:'POST',
            data: {classID: classID},
            success:function(results) {
                $("#studentID").html(results);
                $("#studentID").val([]).trigger("change");
            }
          });
        }

        // On load have our Classes populate
        loadClasses();

      });

      function switchClass()
      {
        // Reset our form values, hide the form, and show the form
        $("#remove-student-form-2").hide();
        $("#remove-student-form-2")[0].reset();
        $('#classHolder').text('Assign Student to Class');
        $('#classID').val([]).trigger('change');
        $("#remove-student-form").show();
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
