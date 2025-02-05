<?php
if (!isset($_GET['id'])) {
    http_response_code(400);
    echo "<script>alert('400 : Bad Request')</script>";
    echo "<script>history.back()</script>";
    die();
}
$get_id = $_GET['id'];

$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];
$full_url = $protocol . $host;

//Tread include the like, this file in app/index.html file 
require_once('./pages/schedule/schedule_objects.php');
include('../conf/mysql-connect-ShipmentSchedule.php');

///Query for Get All Templates
$query_get_templates = "SELECT * FROM schedule_template WHERE id=:id";

$sth = $conn->prepare($query_get_templates);
$sth->bindParam(':id', $get_id, PDO::PARAM_INT);
$sth->execute();

$templates_res = $sth->fetch();
?>
<!-- Content Header -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Shipment Schedules Template Details</h1>
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
                <form action="" id="mainForm" method="">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="name">Template Name</label>
                            <input type="text" name="templateName" id="inputName" class="form-control" value="<?php echo $templates_res['name'] ?>">
                            <input type="hidden" name="templateId" type="text" value="<?php echo $templates_res['id'] ?>">
                        </div>
                    </div class="col-6">

                    <div class="schedule-rows" id="schedules-row">
                        <div class="row field">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="form-group">
                                        <label for="task">Task 1</label>
                                        <input type="text" name="task-1" id="task-1" class="form-control" value="">
                                    </div>
                                </div>
                            </div><!-- /.col -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="form-group">
                                        <label for="time">Fix Time (Minute)</label>
                                        <input type="number" name="totalTime-1" id="totaltime-1" class="form-control" value="">
                                    </div>
                                </div>
                            </div><!-- /.col -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="form-group">
                                        <label for="name">Fix Start Time</label>
                                        <input type="time" name="startTime-1" id="startTime" class="form-control" value="">
                                    </div>
                                </div>
                            </div><!-- /.col -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="form-group">
                                        <label for="name">Fix End Time</label>
                                        <input type="time" name="endTime-1" id="endTime" class="form-control" value="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <button type="button" class="btn btn-primary" id="postObject" onclick="submitForm()">Submit</button>
                            <button type="button" class="btn btn-primary" id="addItem" onclick="addField()">Add Item</button>
                        </div>
                    </div>
                </form>
                <div class="col-md-12" style="display:flex; justify-content:flex-end">
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    function addField() {
        const objectFields = document.getElementById('schedules-row');

        const newField = document.createElement('div');
        newField.classList.add('row');
        newField.classList.add('field');

        newField.innerHTML = `
            <div class="col-md-3">
                <div class="form-group">
                    <div class="form-group">
                        <label for="key-1${objectFields.children.length + 1}">Task ${objectFields.children.length + 1}</label>
                        <input type="text" name="task-${objectFields.children.length + 1}" id="task-${objectFields.children.length + 1}" class="form-control">
                    </div>
                </div>
            </div><!-- /.col -->
            <div class="col-md-3">
                <div class="form-group">
                    <div class="form-group">
                        <label for="time">Fix Time (Minute)</label>
                        <input type="number" name="totalTime-${objectFields.children.length + 1}" id="totaltime-${objectFields.children.length + 1}" class="form-control">
                    </div>
                </div>
            </div><!-- /.col -->
            <div class="col-md-3">
                <div class="form-group">
                    <div class="form-group">
                        <label for="name">Fix Start Time</label>
                        <input type="time" name="startTime-${objectFields.children.length + 1}" id="startTime-${objectFields.children.length + 1}" class="form-control">
                    </div>
                </div>
            </div><!-- /.col -->
            <div class="col-md-3">
                <div class="form-group">
                    <div class="form-group">
                        <label for="name">Fix End Time</label>
                        <input type="time" name="endTime-${objectFields.children.length + 1}" id="endTime-${objectFields.children.length + 1}" class="form-control">
                    </div>
                </div>
            </div>
        `;

        objectFields.appendChild(newField);
    }

    function submitForm() {
        const form = document.getElementById('mainForm');
        const formData = new FormData(form);
        const scheduleData = [];

        const scheduleRows = document.querySelectorAll('.field');
        scheduleRows.forEach((row, index) => {
            const scheduleItem = {
                task: row.querySelector(`[name="task-${index + 1}"]`).value,
                totalTime: row.querySelector(`[name="totalTime-${index + 1}"]`).value,
                startTime: row.querySelector(`[name="startTime-${index + 1}"]`).value,
                endTime: row.querySelector(`[name="endTime-${index + 1}"]`).value,
            };
            scheduleData.push(scheduleItem);
        });

        const dataToSend = {
            templateName: formData.get('templateName'),
            templateId: formData.get('templateId'),
            schedules: scheduleData,
        };

        console.log(dataToSend);

        fetch('./controller/templates/template_item_create.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(dataToSend),
            })
            .then(response => {
                if (response.ok) {
                    alert('Template Schedules Created');

                    window.location.href = window.location.pathname + '?page=schedule-templates';
                    // Handle successful submission (e.g., display success message, redirect)
                } else {
                    alert("Failed To Create Object, Please Try Again or Contact The Administrator");

                    console.error('Error posting object:', response.status);
                    // Handle error (e.g., display error message)
                }
            })
            .catch(error => {
                console.error('Error posting object:', error);
                // Handle error (e.g., display error message)
                alert("Failed To Create Object, Please Try Again or Contact The Administrator");
            });
    }
</script>