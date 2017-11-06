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

    <title>Delete School Year</title>

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
                    <h2>Delete School Year</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />
                    <div id="form-content"></div>
                    <form method="post" id="delete-schoolYear-form" class="form-horizontal form-label-left">
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Select School Year to Delete</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <select class="select2_single form-control" tabindex="-1" name="schoolYearID" id="schoolYearID">
                            
                          </select>
                        </div>
                      </div>
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-5">
                          <button type="submit" class="btn btn-danger">Delete School Year</button>
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

        // submit form using $.ajax() method
        $('#delete-schoolYear-form').submit(function(e){
          e.preventDefault(); // Prevent Default Submission
          
          $.ajax({
            url: '/includes/schoolYear/deleteSchoolYear.php',
            type: 'POST',
            data: $(this).serialize() // it will serialize the form data
          })
          .done(function(data){
              $('#form-content').fadeIn('slow').html(data);

              // On load have our school year populate
              loadSchoolYears();
              $('#schoolYearID').val([]).trigger('change');
          })
          .fail(function(){
            alert('Ajax Submit Failed ...');  
          });
        });

        function loadSchoolYears() {
        $.ajax({
            url:'/includes/schoolYear/editSchoolYearList.php',
            type:'POST',
            success:function(results) {
                $("#schoolYearID").html(results);
            }
          });
        }

        // On load have our School Years populate
        loadSchoolYears();

      });
    </script>

    <!-- Select2 -->
    <script>
      $(document).ready(function() {
        $("#schoolYearID").select2({
          placeholder: "Select School Year",
          allowClear: true
        });
      });
    </script>
    <!-- /Select2 -->
  </body>
</html>
