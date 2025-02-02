<?php

//Tread include the like, this file in app/index.html file 
require_once('./pages/shipment/shipment_objects.php');
include('../conf/mysql-connect-ShipmentSchedule.php');


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
                    <li class="breadcrumb-item">Daily Shipment</li>
                    <li class="breadcrumb-item active">Daily Shipment</li>
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
                <h3 class="card-title">Daily Shipment</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>

            <div class="card-body">
                <form action="" method="">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="name">Choose Shipment Date</label>
                            <input type="date" name="shipmentDate" id="shipment-date" class="form-control">
                        </div>
                        <button type="button" id="select-date" class="btn btn-primary" onclick="getShipments()"> SELECT
                        </button>
                    </div class="col-6">
                </form>
            </div>
        </div>
        <div id="timeline" style="height: 500px; padding:0px 10px"></div>
    </div>
</section>


<script>
    function getShipments() {
        const shipmentDate = document.getElementById('shipment-date');
        const shipmentDateVal = shipmentDate.value;

        if (shipmentDateVal == undefined || shipmentDateVal == null) {
            alert("Selected Tempalte Item is Undefined || NULL " + shipmentDateVal);
            return;
        }

        url = 'controller/daily-shipment/get-daily-shipment.php?shipmentDate=' + shipmentDateVal;

        fetch(url, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                }
            })
            .then(response => {
                if (response.ok) {
                    console.log(response);
                    return response.json();
                } else {
                    alert("Failed To Select Object, Please Try Again");
                    console.error('Error posting object:', response.status);
                }
            })
            .then(data => {
                console.log(data);
                CreateChart(data);
            });
    }

    function CreateChart(data) {
        google.charts.load('current', {
            'packages': ['timeline']
        });
        google.charts.setOnLoadCallback(function() {
            drawChart(data);
        });

        function drawChart(data) {
            var container = document.getElementById('timeline');
            var chart = new google.visualization.Timeline(container);
            var dataTable = new google.visualization.DataTable();

            dataTable.addColumn({
                type: 'string',
                id: 'Item Name'
            });
            dataTable.addColumn({
                type: 'string',
                id: 'Shipment Name'
            });
            dataTable.addColumn({
                type: 'date',
                id: 'Start'
            });
            dataTable.addColumn({
                type: 'date',
                id: 'End'
            });

            rows = [];

            data.forEach(i => {

                var hourS = extractStringTimeHour(i.startTime);
                var minS = extractStringTimeMinute(i.startTime);
                var hourE = extractStringTimeHour(i.endTime);
                var minE = extractStringTimeMinute(i.endTime);

                rows.push([i.item, i.name, new Date(0, 0, 0, hourS, minS), new Date(0, 0, 0, hourE, minE)]);
            })

            console.log(rows);

            dataTable.addRows(rows);

            chart.draw(dataTable);
        }
    }

    function extractStringTimeHour($timeString) {
        // Extract hour from time string (e.g., "18:00:00" => 18)
        var timeParts = $timeString.split(':');
        return parseInt(timeParts[0]);
    }

    function extractStringTimeMinute($timeString) {
        // Extract minute from time string (e.g., "18:30:00" => 30)
        var timeParts = $timeString.split(':');
        return parseInt(timeParts[1]);
    }
</script>