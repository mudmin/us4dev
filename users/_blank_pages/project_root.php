<?php ob_start();
/*
UserSpice 4
An Open Source PHP User Management System
by Curtis Parham and Dan Hoover at http://UserSpice.com

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
?><?php require_once("users/includes/frontend/header.php"); ?>

<?php require_once("users/includes/frontend/navigation.php"); ?>

<?php if (!securePage($_SERVER['PHP_SELF'])){die();} ?>

        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Welcome to UserSpice
                            <small>An Open Source PHP User Management Framework</small>
                        </h1>
                    </div>
                </div>
                <!-- /.row -->
<!-- Content goes here -->







<!-- Content Ends Here -->
<?php require_once("users/includes/frontend/page_footer.php"); // the final html footer copyright row + the external js calls ?>

<!-- Place any per-page javascript here -->

<?php require_once("users/includes/frontend/html_footer.php"); // currently just the closing /body and /html ?>
