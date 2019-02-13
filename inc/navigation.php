<?php include 'inc/checkSession.php'; ?>

<!--  NAVIGATION -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark static-top">
    <div class="container">
        <a class="navbar-brand" href="n.php"><img src="images/logo/monoto_logo_white.png" height="26"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item" id="n"><a class="nav-link" href="n.php"><i class="fas fa-edit"></i> <?php echo translateString("Notes") ?><span class="sr-only">(current)</span></a></li>
                <li class="nav-item" id="m"><a class="nav-link" href="m.php"><i class="fas fa-user"></i> <?php echo translateString("MyMonoto") ?></a></li>
                <li class="nav-item" id="k"><a class="nav-link" href="k.php"><i class="fas fa-keyboard"></i> <?php echo translateString("Keyboard") ?></a></li>
                <?php
                if($_SESSION['admin'] == 1) // show admin-section
                {
                    echo '<li class="nav-item" id="a"><a class="nav-link" href="a.php"><i class="fas fa-cog"></i>';
                    echo translateString("Admin");
                    echo '</a></li>';
                }
                ?>
                <li class="nav-item"><a class="nav-link" href="#" onclick="showLogoutDialog();"><i class="fas fa-sign-out-alt"></i> <?php echo translateString("Logout") ?></a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- set active list item -->
<script type="text/javascript" src="js/monoto/m_navigation.js"></script>
