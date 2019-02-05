<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'inc/coreIncludes.php'; ?>

    <!-- specific -->
    <!-- css -->
    <link href="css/setup.css" rel="stylesheet">
</head>

<body role="document">

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
        <div class="container">
            <a class="navbar-brand" href="setup.php"><img src="images/logo/monoto_logo_white.png" width="63" height="25"></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link js-scroll-trigger" href="#database">Database</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link js-scroll-trigger" href="#account">Account</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- welcome foo -->
    <header class="bg-primary text-white">
        <div class="container text-center">
            <h1>Welcome to monoto</h1>
            <p class="lead">This is a simple install script to get you started.</p>
        </div>
    </header>

    <!-- spacer -->
    <div class="row">&nbsp;</div>

    <!-- section: database -->
    <section id="database" class="bg-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <h2><i class="fas fa-database fa-2x"></i> Database</h2>
                    <p class="lead">Please create a database and all related tables according to the instructions in <span class="badge badge-secondary">doc/INSTALL.md</span> and adjust the values in <span class="badge badge-secondary">conf/config.php</span> according to it.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- spacer -->
    <div class="row">&nbsp;</div>

    <!-- section: account -->
    <section id="account">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <h2><i class="fas fa-user-circle fa-2x"></i> Account</h2>
                    <p class="lead">As final step you can create your initial admin account</p>
                    <form name="login" action="setup.php" method="post" enctype="multipart/form-data">
                        <!-- Username -->
                        <div class="row">
                            <div class="col">
                                <label for="username">Username</label>
                            </div>
                            <div class="col">
                                <input type="text" name="username" placeholder="Username" required="required" autocomplete="username"/>
                            </div>
                            <div class="col">
                                <small>(max. 64 chars)</small>
                            </div>
                        </div>

                        <!-- Mail -->
                        <div class="row">
                            <div class="col">
                                <label for="email">Email</label>
                            </div>
                            <div class="col">
                                <input type="email" name="email" placeholder="Email" required="required" autocomplete="email" />
                            </div>
                            <div class="col">
                                &nbsp;
                            </div>
                        </div>

                        <!-- Password 1 -->
                        <div class="row">
                            <div class="col">
                                Password
                            </div>
                            <div class="col">
                                <input type="password" name="password1" placeholder="Password" required="required" autocomplete="new-password" />
                            </div>
                            <div class="col">
                                <small>(desired password)</small>
                            </div>
                        </div>

                        <!-- Password 2 -->
                        <div class="row">
                            <div class="col">
                                Password
                            </div>
                            <div class="col">
                                <input type="password" name="password2" placeholder="Password" required="required" autocomplete="new-password" />
                            </div>
                            <div class="col">
                                <small>(again the desired password)</small>
                            </div>
                        </div>

                        <!-- Password 2 -->
                        <div class="row">
                            <div class="col">
                                <input type="submit" class="btn btn-primary" value="Create" name="doCreateAdminAccount"  />
                            </div>
                            <div class="col">
                                &nbsp;
                            </div>
                            <div class="col">
                                &nbsp;
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- spacer -->
    <div class="row">&nbsp;</div>

    <!-- warning -->
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="alert alert-danger"><strong>Warning:</strong> &nbsp;Please delete <i>setup.php</i> after finishing the install procedure. It is a risk to keep that file.</div>
            </div>
        </div>
    </div>


    <!-- Footer -->
    <footer class="py-5 bg-dark">
        <div class="container">
            <p class="m-0 text-center text-white"><?php require 'inc/footer.php'; ?></p>
        </div>
        <!-- /.container -->
    </footer>

    <!-- Plugin JavaScript -->
    <script src="js/jquery.easing.min.js"></script>

    <!-- Custom JavaScript for this theme -->
    <script src="js/scrolling-nav.js"></script>

</body>
</html>


<?php


// creating the initial admin-account
if ( isset($_POST["doCreateAdminAccount"]) )
{
    $con = connectToDB();

    // check if user has already manually created the table: m_users
    $val = mysqli_query($con, 'select 1 from `m_users`');
    if($val !== FALSE)
    {
        // table m_users EXISTS - get the data
        //$username = $_POST['username'];
        $username= filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);

        //$email = $_POST['email'];
        $email= filter_input(INPUT_POST, "email", FILTER_SANITIZE_STRING);

        //$password1 = $_POST['password1'];
        $password1= filter_input(INPUT_POST, "password1", FILTER_SANITIZE_STRING);

        //$password2 = $_POST['password2'];
        $password2= filter_input(INPUT_POST, "password2", FILTER_SANITIZE_STRING);

        //$username = mysqli_real_escape_string($con, $username);

        // compare passwords
        if($password1 == $password2) // both passwords do match
        {
            // playing with hash
            $hash = hash('sha256', $password1);
            function createSalt() // playing with salt - creates a 3 character sequence
            {
                $string = md5(uniqid(rand(), true));
                return substr($string, 0, 3);
            }
            $salt = createSalt();
            $hash = hash('sha256', $salt . $hash);

            $query = "INSERT INTO m_users ( username, password, salt, is_admin, email, admin_note ) VALUES ( '$username' , '$hash' , '$salt', '1', '$email', 'monoto-admin' );";
            mysqli_query($con, $query) or die ("Failed Query of " . $query);
            mysqli_close($con); // close sql connection

            displayNoty('Finished installer. Forwarding to login page.', 'success');
            echo '<script type="text/javascript">window.location="index.php"</script>'; // whyever that works - but header not anymore. must be related to our header rework
        }
        else // Password mismatch
        {
            displayNoty('Password issues: password mismatch.', 'error');
        }
    }
    else // mysql tables dont exist
    {
        displayNoty('Database issues: table m_users does not exist.', 'error');
    }
}
?>
