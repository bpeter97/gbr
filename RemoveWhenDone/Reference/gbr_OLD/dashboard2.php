<?php

//Variable Includes
include('cfg/settings.php');

session_start();

if(!isset($_SESSION['loggedin'])) {
    header('location: http://viking.dev/gbr/view/locked.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <!-- Form actions: http://ss1.ciwcertified.com/cgi-bin/process.pl -->

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, shrink-to-fit=no, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>GBR Management System</title>

    <!-- CSS -->
    <link type="text/css" href="css/bootstrap.min.css" rel="stylesheet">
    <link type="text/css" href="css/bootstrap-multiselect.css" rel="stylesheet">
    <link type="text/css" href="css/bootstrap-multiselect.less" rel="stylesheet">
    <link type="text/css" href="css/style.css" rel="stylesheet">

    <!-- Javascript -->
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/bootstrap-multiselect.js"></script>

    <!-- Date Picker -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
    <script type="text/javascript">
        $(document).ready(function(){
            var date_input=$('input[name="frmquotedate"]'); //our date input has the name "date"
            var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
            var options={
                format: 'mm/dd/yyyy',
                container: container,
                todayHighlight: true,
                autoclose: true,
            };
            date_input.datepicker(options);
        })
    </script>

</head>

<body>

    <div id="wrapper">

        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <div class="row" style="width: 100%;height: 10%;">
                <div class="col-lg-12" style="width: 249px;">
                    <div id="sidebarimg"><img src="<?php echo $upperleftlogo ?>"></div>
                </div>
            </div>
            <div class="row" style="width:100%;">
                <div class="col-lg-12 timedate">
                    <!-- This is the script for the clock in the top left of website. -->
                    <script type="text/javascript" src="js/showClock.js"></script>
                    <div id="clockbox" style="margin-left: 15%;margin-top: -190px;"></div>

                </div>
            </div>

            <div class="row" style="width: 99%;height: 60%;">
                <div class="col-lg-12" style="width: 249px;">
                    <?php include('includes/sidebar.php'); ?>
                </div>
            </div>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">

            <div class="container-fluid" id="webbg">

                <?php include('includes/topnavbar.php'); ?>

                <!-- 1st Row. -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading text-center">
                                <b>Quotes</b>
                            </div>
                            <div class="panel-body">
                                <?php include('model/listallquotes.php'); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of 1st Row. -->
                <!-- 2nd Row. -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading text-center">
                                <b>Open Orders</b>
                            </div>
                            <div class="panel-body">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Firstname</th>
                                            <th>Lastname</th>
                                            <th>Email</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="success">
                                            <td>John</td>
                                            <td>Doe</td>
                                            <td>john@example.com</td>
                                        </tr>
                                        <tr class="danger">
                                            <td>Mary</td>
                                            <td>Moe</td>
                                            <td>mary@example.com</td>
                                        </tr>
                                        <tr class="info">
                                            <td>July</td>
                                            <td>Dooley</td>
                                            <td>july@example.com</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of 2nd Row. -->

                <?php include('includes/copyright.php'); ?>

            </div>
        </div>

        <?php include('includes/modals.php'); ?>

    <!-- Menu Toggle Script -->
    <script>
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });
    </script>

</body>

</html>
