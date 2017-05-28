<!DOCTYPE html>
<html lang="en">

<head>

    <?php include(BASEURL.APP.ASSETS.'/header.php'); ?>

</head>

<body>

    <div id="wrapper">

        <?php include BASEURL.APP.ASSETS.'/fixednavbar.php'; ?>

        <!-- Page Content -->
        <div id="page-content-wrapper">

            <div class="container-fluid" id="webbg">

                <!-- include BASEURL.VIEW.'/dashboard.php'; ?> -->

                <!-- include BASEURL.APP.ASSETS.'/copyright.php'; ?> -->

                <?php 
                echo $_SESSION['username'];
                echo $_SESSION['userfname'];
                echo $_SESSION['userlname'];
                echo $_SESSION['usertitle'];
                echo $_SESSION['usertype'];
                echo $_SESSION['loggedin'];
                ?>

            </div>

        </div>

    </div>

    <?php include BASEURL.APP.ASSETS.'/botjsincludes.php'; ?>

</body>

</html>