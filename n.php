<?php include 'inc/checkSession.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- HTML Head -->
    <?php include 'inc/coreIncludes.php'; ?>

    <!-- specific -->
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="css/monoto/notes.css" title="default" />

    <!-- JS-->
    <!-- ckeditor-->
    <!-- 4.11.2 -->
    <script type="text/javascript" src="js/ckeditor/4.11.2/ckeditor.js"></script>

    <!-- note specific functios -->
    <script type="text/javascript" src="js/monoto/m_noteFunctions.js"></script>

    <!-- -->
    <script type="text/javascript">
    var currentRow = -1; // fill var for ugly row-selection hack with a default value
    var oTable;
    var giRedraw = false;

    $(document).ready(function()
    {
        onReady();
    } );

    // Get the rows which are currently selected
    function fnGetSelected( oTableLocal )
    {
        var aReturn = new Array();
        var aTrs = oTableLocal.fnGetNodes();
        for ( var i=0 ; i<aTrs.length ; i++ )
        {
            if ( $(aTrs[i]).hasClass('row_selected') )
            {
                aReturn.push( aTrs[i] );
            }
        }
        return aReturn;
    }

    // Enable bootstrap tooltips
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })

</script>
</head>


<body role="document">

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark static-top">
        <div class="container">
            <a class="navbar-brand" href="n.php"><img src="images/logo/monoto_logo_white.png" height="26"></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item active"><a class="nav-link" href="n.php"><i class="fas fa-edit"></i> <?php echo translateString("Notes") ?><span class="sr-only">(current)</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="m.php"><i class="fas fa-user"></i> <?php echo translateString("MyMonoto") ?></a></li>
                    <li class="nav-item"><a class="nav-link" href="k.php"><i class="fas fa-keyboard"></i> <?php echo translateString("Keyboard") ?></a></li>
                    <?php
                    if($_SESSION['admin'] == 1) // show admin-section
                    {
                        echo '<li class="nav-item"><a class="nav-link" href="a.php"><i class="fas fa-cog"></i>';
                        echo translateString("Admin");
                        echo '</a></li>';
                    }
                    ?>
                    <li class="nav-item"><a class="nav-link" href="#" onclick="showLogoutDialog();"><i class="fas fa-sign-out-alt"></i> <?php echo translateString("Logout") ?></a></li>
                </ul>
            </div>
        </div>
    </nav>


    <!-- Page Content -->
    <div class="container theme-showcase" role="main">
        <div id="container">

            <!-- content-->
            <div id="content">

                <?php
                // display if it is not a stable/official release
                if($m_release == false )
                {
                    echo '<div class="alert alert-warning" role="alert">This is a <strong>development release</strong>, please don\'t use for production environment.</div>';
                }
                ?>

                <form name="myform" method="post" action="n.php">

                    <!-- search -->
                    <div class="row">
                        <div class="col-sm-10">
                            <input type="text" class="form-control" placeholder="<?php echo translateString("search here");?>" id="searchField" name="searchField" type="text" tabindex="1" autofocus>
                        </div>
                        <div class="col-sm-2">

                            <?php

                            if($m_release == true ) // its a release: hide'em
                            {
                                echo '<input type="hidden" class="form-control" style="width: 90px; padding: 2px" title="Note ID" name="noteID" id="noteID" tabindex="-1" disabled placeholder="Note ID" />';
                                echo '<input type="hidden" class="form-control" style="width: 90px; padding: 2px" title="Note version" name="noteVersion" id="noteVersion" tabindex="-1" disabled placeholder="Note version">';
                            }
                            else // its nbot a release: show'em
                            {
                                echo '<input type="text" class="form-control" title="Note ID" name="noteID" id="noteID" tabindex="-1" disabled placeholder="Note ID" />';
                                echo '<input type="text" class="form-control" title="Note version" name="noteVersion" id="noteVersion" tabindex="-1" disabled placeholder="Note version">';
                            }
                            ?>

                        </div>
                    </div>

                    <!-- new note title -->
                    <div class="row">
                        <div class="col-sm-10">
                            <input type="text" id="noteTitle" name="noteTitle" placeholder="<?php echo translateString("Note title");?>" disabled style="width:100%; " class="form-control" onkeyUp="prepareNewNoteStepTwo();" />
                        </div>
                        <div class="col-sm-2">
                            <button type="button" class="btn btn-sm btn-primary buttonNotes" data-toggle="tooltip" title="" name="bt_prepareNoteCreation" id="bt_prepareNoteCreation" onClick="prepareNewNoteStepOne();" tabindex="2"><i class="fas fa-plus"></i> <?php echo translateString("new");?></button>
                            <button type="button" class="btn btn-sm btn-success buttonNotes" data-toggle="tooltip" title="" name="bt_saveNote" id="bt_saveNote" onClick="saveNote();" disabled="disabled"><i class="fas fa-save"></i> <?php echo translateString("save");?></button>
                            <button type="submit" class="btn btn-sm btn-success buttonNotes" data-toggle="tooltip" title="" name="bt_createNewNote" id="bt_createNewNote"  onClick="createNewNote()" disabled="disabled"><i class="fas fa-edit"></i> <?php echo translateString("create");?></button>
                        </div>
                    </div>

                    <!-- ckeditor -->
                    <div class="row">
                        <div class="col-sm-10">
                            <textarea cols="110" id="editor1" name="editor1" tabindex="3"></textarea>
                        </div>
                        <div class="col-sm-2">


                            <!--
                            <input type="hidden" style="width: 90px; padding: 2px" name="noteID" id="noteID" disabled placeholder="ID" />
                        -->

                        <button type="submit" class="btn btn-sm btn-warning buttonNotes" data-toggle="tooltip" title="" id="bt_cancelNewNote" name="bt_cancelNewNote" onClick="resetNotesUI()" disabled="disabled"><i class="fas fa-undo"></i> <?php echo translateString("cancel");?></button>
                        <button type="button" class="btn btn-sm btn-danger buttonNotes" data-toggle="tooltip" title="" name="bt_deleteNote" id="bt_deleteNote" onClick="deleteNote();" disabled="disabled"><i class="fas fa-trash-alt"></i> <?php echo translateString("delete");?></button>
                    </div>
                </div>

                <!-- datatable -->
                <div class="row">
                    <div class="col-sm-10">
                        <table cellpadding="0" cellspacing="0" class="display" id="example" width="100%" align="left">
                            <tbody>
                                <?php
                                $con = connectToDB();
                                $rowID = 0;
                                $owner = $_SESSION['username'];    // only select notes of this user
                                $result = mysqli_query($con, "SELECT id, title, content, date_mod, save_count FROM m_notes WHERE owner='".$owner."' ORDER by date_mod ASC ");
                                while($row = mysqli_fetch_array($result))
                                {
                                    echo '<tr class="odd gradeU"><td>'.$rowID.'</td><td>'.$row[0].'</td><td>'.$row[1].'</td><td>'.$row[2].'</td><td>'.$row[3].'</td><td>'.$row[4].'</td></tr>';
                                    $rowID = $rowID +1;
                                }
                                ?>
                            </tbody>
                        </table> <!-- /datatable -->
                    </div>
                    <div class="col-sm-2">
                        &nbsp;
                    </div>
                </div>
            </form>

            <!-- footer -->
            <?php require 'inc/footer.php'; ?>

        </div> <!-- /content -->

    </div> <!-- /container -->
</div>

<script type="text/javascript" src="js/monoto/m_keyPressNotes.js"></script>
</body>
</html>
