<?php

//Tread include the like, this file in app/index.html file 
require_once('./pages/schedule/schedule_objects.php');
include('../conf/mysql-connect-ShipmentSchedule.php');

///Query for Get All Templates
$query_get_templates = "SELECT * FROM schedule_template";

$sth = $conn->prepare($query_get_templates);
$sth->execute();

$templates_res = $sth->fetchAll(PDO::FETCH_CLASS, "schedule_template");
?>
<!-- Content Header -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Shipment Schedules Template</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item">Schedules</li>
                    <li class="breadcrumb-item active">Template</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<!-- Content Header -->

<!-- Contents -->
<section class="content">
    <div class="container-fluid">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Create Template</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>

            <div class="card-body">
                <form action="./controller/templates/template_create.php" method="POST">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="name">Template Name</label>
                            <input type="text" name="name" id="inputName" class="form-control">
                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div class="col-6">
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"></h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>no</th>
                                    <th>Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                foreach ($templates_res as $k => $i) {
                                    echo "<tr>";
                                    echo "<td>" . $no . "</td>";
                                    echo "<td>" . $i->name . "</td>";
                                ?>
                                    <td>
                                        <a href="#" class="btn btn-primary d-flex justify-content-center align-items-center"><i class="fa fa-info"></i> Details</a>
                                        <a href="#" class="btn btn-danger d-flex justify-content-center align-items-center"><i class="fa fa-trash"></i> Delete</a>
                                    </td>
                                <?php
                                    echo "</    tr>";
                                    $no++;
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Contents -->



<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
    $(function() {
        $("#example1").DataTable({ // FOR TABLE WITH PRINT
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });
    });
</script>