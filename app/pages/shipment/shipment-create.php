<?php

$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];
$full_url = $protocol . $host;

require_once('./pages/schedule/schedule_objects.php');
include('../conf/mssql-connect-ShipmentSchedule.php');

///Query for Get All Templates
$query_get_templates = "SELECT * FROM schedule_template";

$sth = $conn->prepare($query_get_templates);
$sth->execute();

$templates_res = $sth->fetchAll();

?>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Shipment Create</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item">Shipment</li>
                    <li class="breadcrumb-item active">Shipment</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Create Shipment</h3>
                    </div>
                    <div class="card-body">
                        <form id="select-template-form">
                            <div class="col-6 ">
                                <div class="form-group">
                                    <label for="name">Select Template</label>
                                    <select name="template-select" id="template-select" class="form-control select2">
                                        <option value="" selected disabled>Select Template</option>
                                        <?php
                                        foreach ($templates_res as $template) {
                                            echo "<option value='" . $template['id'] . "'>" . $template['name'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <button type="button" id="select-tempalte-btn" class="btn btn-primary" onclick="templateSelected()">Select</button>
                            </div class="col-6">
                        </form>
                        <hr />
                        <div class="row mb-4">
                            <h3 class="card-title">Shipment Schedule</h3>
                        </div>
                        <form action="" id="mainForm" method="">
                            <div class="col-12 mb-3">
                                <div style="display: flex; flex-direction:row; justify-content:flex-start">
                                    <div style="width: 25%;" class="mr-2">
                                        <label for="shipment-date">Date</label>
                                        <input type="date" class="form-control" id="shipment-date" name="shipment-date" />
                                    </div>
                                    <div style="width: 25%;" class="mx-2">
                                        <label for="shipment-name">Name</label>
                                        <input type="text" class="form-control" id="shipment-name" name="shipment-name" />
                                    </div>
                                </div>
                            </div>
                            <div class="schedule-rows-header" id="schedules-row-header">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label>Task</label>
                                            </div>
                                        </div>
                                    </div><!-- /.col -->
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label>Fix Time (Minute)</label>
                                            </div>
                                        </div>
                                    </div><!-- /.col -->
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label>Fix Start Time</label>
                                            </div>
                                        </div>
                                    </div><!-- /.col -->
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label>Fix End Time</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="schedule-rows" id="schedules-row">
                                <!-- Will Fill With Forms Input Items -->
                            </div>
                            <div class="col-md-6">
                                <button type="button" class="btn btn-primary" id="postObject" onclick="createAndSentData()" disabled>Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Pretend Include like in index.html -->
<script src="./lib/time-calibrator.js"></script>
<script>
    function templateSelected() {
        clearMainForm();
        getTemplateItems();
        submitBtnToggle();
    }

    function submitBtnToggle() {
        document.getElementById('postObject').disabled = false;
    }

    function clearMainForm() {
        const objectFields = document.getElementById('schedules-row');
        while (objectFields.firstChild) {
            objectFields.removeChild(objectFields.firstChild);
        }
    }

    function getTemplateItems() {
        const templateSelect = document.getElementById('template-select');
        const selectedTemplateId = templateSelect.value;

        if (selectedTemplateId == undefined || selectedTemplateId == null) {
            alert("Selected Tempalte Item is Undefined || NULL" + selectedTemplateId);
            return;
        }

        url = './controller/templates/template_get_template_item.php?id=' + selectedTemplateId;

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
                setItemsDataToForm(data);
            });

    }

    function setItemsDataToForm(datas) {
        if (datas.length > 0) {
            datas.forEach(data => {
                addField(data);
            });
        }
    }

    function addField(data) {
        const id = data.id ?? 0;
        const task = data.item ?? "";

        const duration = data.FixDurationMinute ?? 0;
        const durationDisabled = duration != 0;

        const startTime = data.FixStartTime ?? null;
        const startTimeDisabled = startTime != null;
        const formattedStartTime = startTime ? startTime.substring(0, 5) : null;

        const endTime = data.FixEndTime ?? null
        const formattedEndTime = endTime ? endTime.substring(0, 5) : null;
        const endTimeDisabled = endTime != null;

        const objectFields = document.getElementById('schedules-row');

        const newField = document.createElement('div');
        newField.classList.add('row');
        newField.classList.add('field');

        newField.innerHTML = `
            <div class="col-md-3">
                <input type="hidden" id="id-${objectFields.children.length + 1}" name="id-${objectFields.children.length + 1}"
                value="${id}">
                <div class="form-group">
                    <div class="form-group">
                        <input type="text" name="task-${objectFields.children.length + 1}" id="task-${objectFields.children.length + 1}" class="form-control" value="${task}" readonly>
                    </div>
                </div>
            </div><!-- /.col -->
            <div class="col-md-3">
                <div class="form-group">
                    <div class="form-group">
                        <input type="number" name="totalTime-${objectFields.children.length + 1}" 
                            id="totaltime-${objectFields.children.length + 1}" class="form-control"
                            value="${duration}">
                    </div>
                </div>
            </div><!-- /.col -->
            <div class="col-md-3">
                <div class="form-group">
                    <div class="form-group">
                        <input type="time" name="startTime-${objectFields.children.length + 1}" 
                        id="startTime-${objectFields.children.length + 1}" class="form-control"
                        value="${formattedStartTime}">
                    </div>
                </div>
            </div><!-- /.col -->
            <div class="col-md-3">
                <div class="form-group">
                    <div class="form-group">
                        <input type="time" name="endTime-${objectFields.children.length + 1}" 
                        id="endTime-${objectFields.children.length + 1}" class="form-control"
                        value="${formattedEndTime}">
                    </div>
                </div>
            </div>
        `;

        objectFields.appendChild(newField);
    }

    document.getElementById('mainForm').addEventListener('input', function(event) {
        const target = event.target;
        const fieldIndex = target.name.split('-')[1];
        const totalTimeField = document.getElementById(`totaltime-${fieldIndex}`);
        const startTimeField = document.getElementById(`startTime-${fieldIndex}`);
        const endTimeField = document.getElementById(`endTime-${fieldIndex}`);

        if (target === totalTimeField) {
            if (startTimeField.value) {
                endTimeField.value = calculateEndTime(startTimeField.value, target.value);
            } else if (endTimeField.value) {
                startTimeField.value = calculateStartTime(target.value, endTimeField.value);
            }
        } else if (target === startTimeField) {
            if (totalTimeField.value) {
                endTimeField.value = calculateEndTime(target.value, totalTimeField.value);
            } else if (endTimeField.value) {
                totalTimeField.value = calculateDuration(target.value, endTimeField.value);
            }
        } else if (target === endTimeField) {
            if (totalTimeField.value) {
                startTimeField.value = calculateStartTime(totalTimeField.value, target.value);
            } else if (startTimeField.value) {
                totalTimeField.value = calculateDuration(startTimeField.value, target.value);
            }
        }
    });

    function createAndSentData() {
        const form = document.getElementById('mainForm');
        const formData = new FormData(form);
        const shipmentData = [];

        const scheduleRows = document.querySelectorAll('.field');
        scheduleRows.forEach((row, index) => {
            const scheduleItem = {
                id: row.querySelector(`[name="id-${index + 1}"]`).value,
                task: row.querySelector(`[name="task-${index + 1}"]`).value,
                totalTime: row.querySelector(`[name="totalTime-${index + 1}"]`).value,
                startTime: row.querySelector(`[name="startTime-${index + 1}"]`).value,
                endTime: row.querySelector(`[name="endTime-${index + 1}"]`).value,
            };
            shipmentData.push(scheduleItem);
        });

        const status = checkData(shipmentData);
        if (!status) return;

        shipmentDate = document.getElementById('shipment-date').value;
        shipmentName = document.getElementById('shipment-name').value;
        templateId = document.getElementById('template-select').value;

        fullData = {
            date: shipmentDate,
            name: shipmentName,
            templateId: templateId,
            scheduleItem: shipmentData
        }

        if (!sentData(fullData)) {
            return;
        }

        window.location.href = window.location.pathname + '?page=shipment';
        return;
    }

    function checkData(datas) {
        for (let i = 0; i < datas.length; i++) {
            const data = datas[i];
            if (data.id == null || data.task == null || data.totalTime == '' || data.startTime == '' || data.endTime == '') {
                alert(`There's Data Missing in ${data.task}, Fill All Form First!!!`);
                return false;
            }
        }
        return true;
    }

    function sentData(datas) {
        url = './controller/shipment/create_shipment.php';

        fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(datas),
            })
            .then(response => {
                if (response.ok) {
                    alert('Shipment Created');
                    window.location.href = window.location.pathname + '?page=shipment';
                    return true;
                } else {
                    alert("Failed To Create Object, Please Try Again or Contact The Administrator");
                    console.error('Error posting object:', response.status);
                    return false;
                }
            })
            .catch(error => {
                console.error('Error posting object:', error);
                alert("Failed To Create Object, Please Try Again or Contact The Administrator");
                return false;
            });
    }
</script>