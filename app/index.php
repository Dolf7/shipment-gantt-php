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
                } else if ($page == 'form-basic') {
                    include('./pages/form-basic.php');
                } else if ($page == 'data-table') {
                    include('./pages/table-basic.php');
                } else if ($page == 'chart-basic') {
                    include('./pages/chart-basic.php');
                } else if ($page == 'chart-inline') {
                    include('./pages/chart-inline.php');
                } else if ($page == 'gantt-test') {
                    include('./pages/gantt-test.php');
                } else if ($page == 'schedule-templates') {
                    include('./pages/schedule/templates.php');
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