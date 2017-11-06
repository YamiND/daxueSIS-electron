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

    <title>Edit Class</title>

   <?php include("includes/genericCSS.php"); ?>
   
    <!-- Select2 -->
    <link href="vendors/select2/dist/css/select2.min.css" rel="stylesheet">
    <!-- jquery-timepicker -->
    <link href="vendors/jquery-timepicker/jquery.timepicker.min.css" rel="stylesheet">
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
                    <h2>Edit Class</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />
                    <div id="form-content"></div>
                    <form method="post" id="edit-class-form" class="form-horizontal form-label-left">
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Select Class to Edit</label>
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

                    <form method="post" id="edit-class-form-2" class="form-horizontal form-label-left">
                      <input type="hidden" id="selectedClassID" name="selectedClassID">
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="class-name">Class Name <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" id="class-name" name="class-name" required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="teacherID">Teacher <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <select class="select2_single form-control" tabindex="-1" name="teacherID" id="teacherID">
                            
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="class-start-time">Start Time <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="class-start-time" name="class-start-time" required="required" class="timepicker form-control col-md-7 col-xs-12">
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="class-end-time">End Time <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="class-end-time" name="class-end-time" required="required" class="timepicker form-control col-md-7 col-xs-12">
                        </div>
                      </div>
                      
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-5">
                          <button type="button" class="btn btn-primary" onclick="switchClass()">Switch Class</button>
                          <button type="submit" class="btn btn-success" name="edit" value="edit">Edit Class</button>
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
    <!--jquery-timepicker -->
    <script src="vendors/jquery-timepicker/jquery.timepicker.min.js"></script>
    <!-- Custom Theme Scripts -->
    <script src="js/custom.min.js"></script>


    <!--Ajax Submit Form-->
    <script>
      $(document).ready(function() {  

        $("#edit-class-form-2").hide();

        // submit form using $.ajax() method
        $('#edit-class-form').submit(function(e){
          e.preventDefault(); // Prevent Default Submission
          
          $.ajax({
            url: '/includes/classes/editClassSelect.php',
            type: 'POST',
            dateType: 'JSON',
            data: $(this).serialize() // it will serialize the form data
          })
          .done(function(data){
            $("#edit-class-form").hide();

            var classInfo = JSON.parse(data);

            $('#selectedClassID').val(classInfo[0].classID);
            $('#class-name').val(classInfo[0].className);
            $('#class-start-time').val(classInfo[0].classStartTime);
            $('#class-end-time').val(classInfo[0].classEndTime);
            
            $("#edit-class-form-2").show();
            $("#teacherID").select2({
              placeholder: "Select Teacher",
              allowClear: true
            });

            loadTeachers(classInfo[0].teacherID);

          })
          .fail(function(){
            alert('Ajax Submit Failed ...');  
          });
        });
    

        // submit form using $.ajax() method
        $('#edit-class-form-2').submit(function(e){
          e.preventDefault(); // Prevent Default Submission
          
          $.ajax({
            url: '/includes/classes/editClass.php',
            type: 'POST',
            data: $(this).serialize() // it will serialize the form data
          })
          .done(function(data){
              $('#form-content').fadeIn('slow').html(data);

              $("#edit-class-form-2").hide();
              $("#edit-class-form-2")[0].reset();
              // On load have our classes populate
              loadClasses();
              $('#classID').val([]).trigger('change');
              $("#edit-class-form").show();
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

        function loadTeachers(teacherID) {
        $.ajax({
            url:'/includes/classes/editClassTeacherList.php',
            type:'POST',
            success:function(results) {
                $("#teacherID").html(results);
                $("#teacherID").val(teacherID).trigger("change");
            }
          });
        }

        // On load have our Classes populate
        loadClasses();

      });

      function switchClass()
      {
        // Reset our form values, hide the form, and show the form
        $("#edit-class-form-2").hide();
        $("#edit-class-form-2")[0].reset();

        $('#classID').val([]).trigger('change');
        $("#edit-class-form").show();
      }

       // Time Picker Form Entry
        $('.timepicker').timepicker({
          timeFormat: 'HH:mm',
          interval: 60,
          minTime: '8',
          maxTime: '8:00pm',
          defaultTime: '8',
          startTime: '8:00',
          dynamic: false,
          dropdown: true,
          scrollbar: true
      });
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
