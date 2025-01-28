<?php

//Tread include the like, this file in app/index.html file 
require_once('./pages/shipment/shipment_objects.php');
include('../conf/mysql-connect-ShipmentSchedule.php');

///Query for Get All Templates
$query_get_templates = "SELECT * FROM schedule_schedules";

$sth = $conn->prepare($query_get_templates);
$sth->execute();

$templates_res = $sth->fetchAll(PDO::FETCH_CLASS, "shipment_schedules");
?>
<!-- Content Header -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Shipment Schedules</h1>
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
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header row" style="justify-content:space-between; width:100%">
                        <h3 class="card-title">Shipment</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row" style="justify-content: flex-end; margin:0px 0px 1vw">
                            <a href="./?page=shipment-create" class="btn btn-primary">
                                Create New Shipment
                            </a>
                        </div>
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>no</th>
                                    <th>Name</th>
                                    <th>Date</th>
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
                                    echo "<td>" . $i->scheduleDate . "</td>";
                                ?>
                                    <td>
                                        <a href="./index.php?page=shipment-detail&id=<?php echo $i->id ?>" class="btn btn-primary d-flex justify-content-center align-items-center"><i class="fa fa-info"></i> Details</a>

                                        <a href="./controller/shipment/delete_shipment.php?id=<?php echo $i->id ?>" class="btn btn-danger d-flex justify-content-center align-items-center" onclick="return confirm('Are you sure you want to delete this shipment?');"><i class="fa fa-trash"></i> Delete</a>
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