<?php
// -----------------------------------------------------------------------------
// genericFooter.php
// This file contains the footer.
// -----------------------------------------------------------------------------
require "config/build.php";

?>

<div id="footer" class="clear" style="text-align:center;">
<hr>

    <small>
        <a href="<?php echo $m_githubProjectLink; ?>" title="visit monoto at github"><i class="fab fa-github"></i></a>
        &nbsp;<b><?php echo $m_name; ?></b>
        <br>
        Version: <?php echo $m_version; ?> (<?php echo $m_date; ?>)
        <br>
        <a href="<?php echo $m_githubWikiLink; ?>">Documentation</a>
        &nbsp;/&nbsp;
        <a href="<?php echo $m_githubChangelogLink; ?>">Changelog</a>
        &nbsp;/&nbsp;
        <a href="<?php echo $m_githubIssueLink; ?>">Issues</a>

        <!--
        <a href="pwreset.php" title="password reset"><i class="fas fa-key"></i></a>
       -->
</small>
</div>
