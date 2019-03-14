<?php
// -----------------------------------------------------------------------------
// genericFooter.php
// This file contains the footer.
// -----------------------------------------------------------------------------

// prevent direct call of this script
if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) )
{
    // Up to you which header to send, some prefer 404 even if
    // the files does exist for security
    header( 'HTTP/1.0 403 Forbidden', TRUE, 403 );

    // choose the appropriate page to redirect users
    die( header( 'location: ../404.php' ) );
}

require "config/build.php";

?>

<div id="footer" class="clear" style="text-align:center;">
<hr>
    <small>
        <!-- github logo/link and project name -->
        <a href="<?php echo $m_githubProjectLink; ?>" title="visit monoto at github"><i class="fab fa-github"></i></a>&nbsp;<b><?php echo $m_name; ?></b>
        <br>

        <!-- version informations -->
        Version: <?php echo $m_version; ?> (<?php echo $m_date; ?>)
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
