<!-- This Source Code Form is subject to the terms of the Mozilla Public
   - License, v. 2.0. If a copy of the MPL was not distributed with this
   - file, You can obtain one at http://mozilla.org/MPL/2.0/. -->



<?php
session_start();
include '../db_connect_params.inc';

if (isset($_SESSION['user_name']) && isset($_SESSION['user_id'])) {
    ?>

    <html>

        <head>
            <title>Links Explorer</title>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <script src="../libraries/jquery-3.1.1.min.js"></script>
            <link href="../libraries/bootstrap/css/bootstrap-theme.css" rel="stylesheet" />
            <link href="../libraries/bootstrap/css/bootstrap.css" rel="stylesheet" />
            <script src="../libraries/bootstrap/js/bootstrap.js"></script>
            <script type="text/javascript">
                function check() {
                    if (confirm("Are you sure want to DELETE? This can't be undone!")) {
                        return true;
                    } else {
                        return false;
                    }
                }

	    </script>

	    <style type="text/css">
		table { table-layout:fixed; }
		td { word-wrap:break-word; }
		th { word-wrap:break-word; }
	    </style>

        </head>

        <body style="padding-top:10px; padding-bottom: 100px;">

            <div id="top_nav_bar_vj">
                <nav class="navbar navbar-default navbar-fixed-top">
                    <div class="container-fluid" style="background-color:#0b4a91; font-variant-caps:all-petite-caps;">
                        <!--This gives enough padding for navbar elements-->
                        <div class="navbar-header" style="color:#ffffff;">
                            <button style="background-color: #ffffff;" type="button" class="navbar-toggle" data-target="#resize_menu_vj_top" data-toggle="collapse">
                                <!-- To get THREE bars(Icon bars) when we resize the window to smaller size-->
                                <span style="color:#0b4a91;">
                                    <span class="glyphicon glyphicon-menu-hamburger"></span>
                                    <span>Menu</span>
                                </span>
                            </button>
                        </div>
                        <div class="navbar-collapse collapse" id="resize_menu_vj_top">
                            <ul class="nav navbar-nav">
                                <li id="list_id_home"><a href="../index.php"><span class="glyphicon glyphicon-home" style="font-size: 20px; color:white;"></span><span style="font-size: medium;color:#ffffff;">&nbsp; HOME </span></a></li>
                            </ul>
                            <ul class="nav navbar-nav">
                                <li id="list_id_add_link"><a href="add_link.php"><span class="glyphicon glyphicon-plus" style="font-size: 20px; color:white;"></span><span style="font-size: medium;color:#ffffff;">&nbsp; ADD LINK </span></a></li>
                            </ul>
                            <ul class="nav navbar-nav">
                                <li id="list_id_import"><a href="import_data.php"><span class="glyphicon glyphicon-import" style="font-size: 20px; color:white;"></span><span style="font-size: medium;color:#ffffff;">&nbsp; IMPORT FROM FILE </span></a></li>
                            </ul>
                            <ul class="nav navbar-nav">
                                <li id="list_id_export"><a href="export_data.php"><span class="glyphicon glyphicon-export" style="font-size: 20px; color:white;"></span><span style="font-size: medium;color:#ffffff;">&nbsp; EXPORT TO FILE </span></a></li>
                            </ul>
                            <ul class="nav navbar-nav navbar-right">                                
                                <li id="list_id_logout"><a href="../logout.php"><span class="glyphicon glyphicon-log-out" style="font-size: 20px; color:white;"></span><span style="font-size:medium;color:#ffffff;">&nbsp; LOGOUT</span></a></li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>  

            <div style=" padding: 50px 0px 30px 0px; text-align: center;"><h1>Link Explorer</h1></div>

            <div class="container-fluid" style="padding-top: 10px;">
                <form action="delete_link_entry_script.php" method="post" onsubmit="return check()" style="padding:19px 29px 29px;margin: 0 auto;background-color:#f2f2f2; border: 0px solid #080808; border-radius: 5px;box-shadow: 0 1px 70px rgb(0, 0, 0);font-family: Tahoma, Geneva, sans-serif;font-weight: lighter;">
                    <?php
                    $con = mysqli_connect($database_host, $database_user, $database_password, $database_name);
                    if (!$con) {
                        die('Could not connect to database: ' . mysql_error());
                    }
                    $user_name = $_SESSION['user_name'];
                    $user_id = $_SESSION['user_id'];
                    $qry = "select * from link_data where user_id='$user_id' AND user_name='$user_name' order by views desc, added_on desc";
                    $result = mysqli_query($con, $qry);
                    if (mysqli_affected_rows($con) == 0 || (!$result)) {
                        echo"<h1 style='text-align:center;padding:20px;'>You don't have any links yet in the server!</h1>";
                    } else {
                        $out_res = "<table class='table table-striped'><tr><th>Sl. No.<th>Link</th><th>Description</th><th>Added on (IST) </th><th>Views</th><td><input type='submit' name='submit_invalid' value='Delete Checked' class='btn btn-danger' /></td></tr>";
                        $sl_no = 0;
                        while ($row = mysqli_fetch_array($result)) {
                            $sl_no++;
			    $actual_link = $row['link_txt'];
			    $encoded_url = rawurlencode($actual_link);
                            $link_id = $row['link_id'];
                            $views = $row['views'];
                            $out_res .= "<tr><td>" . $sl_no . "</td><td><a target='_blank' href = 'view_link.php?link_id=$link_id&views=$views&link=$encoded_url'>$actual_link</a></td><td>" . htmlentities($row['description'], ENT_QUOTES) . "</td><td>" . $row['added_on'] . "</td><td>" . $views . "</td><td><label style='color:red'><input type='checkbox' name='link_id[]' value='" . $row['link_id'] . "'/>&nbsp;&nbsp;Delete</label></td></tr>";
                        }
                        mysqli_close($con);
                        $out_res .= "</table><br/><br/>";
                        echo "<br/>$out_res<br/>";
                    }
                    ?>                    
                </form>
            </div>
        </body>

    </html>

    <?php
} else {
    header("location:../login.php");
}
?>
