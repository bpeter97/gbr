<!DOCTYPE html>
<html class="full" lang="en">
 
<head>

    <?php include(Config::get('site/baseurl').Config::get('site/assets').'/header.php'); ?>

</head>

<body>
    <div class="container">
        <div id="loginbox" class="mainbox col-md-6 col-md-offset-3 col-sm-6 col-sm-offset-3">
            <div class="row">
                <img id="lockedlogo" class="img-responsive" src="<?php echo Config::get('site/siteurl').Config::get('site/resources/img').'/logo.png'; ?>">
            </div>
            <div class="panel panel-default" >
                <div class="panel-heading">
                    <div class="panel-title text-center"><b>SYSTEM LOCKED</b></div>
                </div>

                <div class="panel-body" >
                    <form name="form" id="form" class="form-horizontal" enctype="multipart/form-data" method="POST" action="<?php echo Config::get('site/siteurl').'/'; ?>">
                        <p class="text-center">
                            This session has been locked by either timing out and not doing anything for 5 minutes or the user decided to lock the system for security purposes. Please type your login password to get back into the system.
                        </p>
                        <br/>
                         <div class="input-group">
                            <span class="input-group-addon green border-1px-solid-black"><i class="glyphicon glyphicon-lock locked-glyph-login"></i></span>
                            <input id="username" type="username" class="form-control border-1px-solid-black" name="username" placeholder="username">
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon green border-1px-solid-black"><i class="glyphicon glyphicon-lock locked-glyph-login"></i></span>
                            <input id="password" type="password" class="form-control border-1px-solid-black" name="password" placeholder="password">
                        </div>
                        <div class="form-group">
                            <!-- Button -->
                            <div class="col-sm-12 controls">
                                <button type="submit" id="lockedbtn" class="btn btn-default pull-right" name="submit"><i class="glyphicon glyphicon-log-in locked-glyph-login"></i> Log in</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <?php include(Config::get('site/baseurl').Config::get('site/assets').'/copyright.php'); ?>
        </div>

    </div>

    <?php include(Config::get('site/baseurl').Config::get('site/assets').'/botjsincludes.php'); ?>

</body>

</html>
