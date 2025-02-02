<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipment on Delivery </title>
    <?php
    include('header.php');
    ?>
</head>

<body class="hold-transition skin-red sidebar-mini">
    <div class="wrapper">
        <div class="main-header">
            <!-- Brand logo -->
            <?php //include('logo.php') 
            ?>
            <!-- Navbar  -->
            <?php include('navbar.php') ?>
        </div>
        <!-- Sibebar Aside -->
        <?php include('sidebar.php') ?>

        <!-- MAIN CONTENT -->
        <div class="content-wrapper">
            <?php

            if (isset($page)) {
                if ($page == 'profile') {
                    include('./pages/profile.php');
                } else if ($page == 'gantt-test') {
                    include('./pages/gantt-test.php');
                }
                // TEMPLATES
                else if ($page == 'schedule-templates') {
                    include('./pages/schedule/templates.php');
                } else if ($page == 'schedule-templates-item-create') {
                    include('./pages/schedule/templates_items_create.php');
                } else if ($page == 'schedule-templates-details') {
                    include('./pages/schedule/templates_items_detail_edit.php');
                }
                // SHIPMENT
                else if ($page == 'shipment') {
                    include('./pages/shipment/shipment.php');
                } else if ($page == 'shipment-create') {
                    include('./pages/shipment/shipment-create.php');
                } else if ($page == 'shipment-detail') {
                    include('./pages/shipment/shipment-details.php');
                }
                // DAILY SHIPMENT
                else if ($page == 'daily-shipment') {
                    include('./pages/daily-shipment/daily-shipment.php');
                }

                // USER
                else if ($page == 'user') {
                    include('./pages/user/users.php');
                } else {
                    include('./pages/not-found.php');
                }
            } else {
                include('./pages/profile.php');
            }
            ?>
        </div>
        <!-- MAIN CONTENT -->

        <?php include('footer.php') ?>
    </div>
</body>

</html>