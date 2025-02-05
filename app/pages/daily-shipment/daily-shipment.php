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
                        <button type="button" id="select-date" class="btn btn-primary" onclick="dateselected()"> SELECT
                        </button>
                    </div class="col-6">
                </form>
            </div>
        </div>
        <div id="timeline" style="height: 1000px; padding:0px 10px"></div>
    </div>
</section>


<script>
    function dateselected() {
        const shipmentDate = document.getElementById('shipment-date').value;
        if (shipmentDate) {
            window.location.href = window.location.pathname + '?page=daily-shipment&date=' + shipmentDate;
        } else {
            alert("Please select a date.");
        }
    }

    function getDateGetParams() {
        const urlParams = new URLSearchParams(window.location.search);
        const dateParam = urlParams.get('date');
        return dateParam;
    }

    document.addEventListener('DOMContentLoaded', getShipments);

    function getShipments() {
        const shipmentDateVal = getDateGetParams();

        if (shipmentDateVal == undefined || shipmentDateVal == null) {
            return;
        }

        console.log(shipmentDateVal);

        url = 'controller/daily-shipment/get-daily-shipment.php?shipmentDate=' + shipmentDateVal;

        fetch(url, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                }
            })
            .then(response => {
                if (response.ok) {
                    return response.json();
                } else {
                    alert("Failed To Select Object, Please Try Again");
                    console.error('Error posting object:', response.status);
                }
            })
            .then(data => {
                CreateChart(data, shipmentDateVal);
            });
    }

    function CreateChart(data, date) {
        google.charts.load('current', {
            'packages': ['timeline']
        });
        google.charts.setOnLoadCallback(function() {
            drawChart(data, date);
        });

        function drawChart(data, date) {
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

            console.log(data);

            var dateParts = date.split('-');
            var year = parseInt(dateParts[0]);
            var month = parseInt(dateParts[1]) - 1; // Month is zero-based in JavaScript Date
            var day = parseInt(dateParts[2]);

            rows = [];
            daysStart = day;
            daysEnd = day;

            data.forEach(i => {

                var hourS = extractStringTimeHour(i.startTime);
                var minS = extractStringTimeMinute(i.startTime);
                var hourE = extractStringTimeHour(i.endTime);
                var minE = extractStringTimeMinute(i.endTime);
                var startDate;
                var endDate;

                if (!(hourE > hourS || (hourE === hourS && minE > minS))) {
                    startDate = new Date(year, month, daysStart, hourS, minS);
                    endDate = new Date(year, month, daysEnd, 23, 59);
                    rows.push([i.item, i.name, startDate, endDate]);

                    startDate = new Date(year, month, daysStart, 0, 0);
                    endDate = new Date(year, month, daysEnd, hourE, minE);
                    rows.push([i.item, i.name, startDate, endDate]);
                } else {
                    startDate = new Date(year, month, daysStart, hourS, minS);
                    endDate = new Date(year, month, daysEnd, hourE, minE);
                    rows.push([i.item, i.name, startDate, endDate]);
                }

            })
            dataTable.addRows(rows);

            var options = {
                timeline: {
                    rowLabelStyle: {
                        fontName: 'Helvetica',
                        fontSize: 20,
                        color: '#603913'
                    },
                    barLabelStyle: {
                        fontName: 'Garamond',
                        fontSize: 17
                    }
                },
                avoidOverlappingGridLines: false
            }
            chart.draw(dataTable, options);
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