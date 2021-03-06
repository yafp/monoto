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
        <!-- github logo/link and project name -->
        <a href="<?php echo $m_githubProjectLink; ?>" title="visit monoto at github"><i class="fab fa-github"></i></a>&nbsp;<b><?php echo $m_name; ?></b>
        <br>

        <!-- version informations -->
        Version: <?php echo $m_version; ?> (<?php echo $m_buildDate; ?>)
        <br>

        <!-- links to docu, changelog and issue tracker -->
        <a href="<?php echo $m_githubWikiLink; ?>">Documentation</a>
        &nbsp;/&nbsp;
        <a href="<?php echo $m_githubChangelogLink; ?>">Changelog</a>
        &nbsp;/&nbsp;
        <a href="<?php echo $m_githubIssueLink; ?>">Issues</a>
        <br>
        <br>
</small>
</div>
