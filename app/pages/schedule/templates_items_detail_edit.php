<?php
function throw_bad_request($e)
{
    http_response_code(400);
    echo "<script>alert('400 : Bad Request - $e')</script>";
    echo "<script>history.back()</script>";
    die();
}

function throw_internal_server_error($e)
{
    http_response_code(500);
    echo "<script>alert('500 : Internal Server Error - $e')</script>";
    echo "<script>history.back()</script>";
    die();
}

function get_first_six_chars($string)
{
    return substr($string, 0, 8);
}

if (!isset($_GET['id'])) {
    throw_bad_request("Params Id missing");
    die();
}
$get_id = $_GET['id'];

$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];
$full_url = $protocol . $host;

//Tread include the like, this file in app/index.html file 
require_once('./pages/schedule/schedule_objects.php');
include('../conf/mssql-connect-ShipmentSchedule.php');

///Query for Get Templates
$query_get_templates = "SELECT * FROM schedule_template WHERE id=:id";

$sth = $conn->prepare($query_get_templates);
$sth->bindParam(':id', $get_id, PDO::PARAM_INT);
try {
    $sth->execute();
} catch (Exception $e) {
    throw_internal_server_error($e->getMessage());
}
$templates_res = $sth->fetch();

$query_get_templates_item = "SELECT * FROM schedule_template_item WHERE templateid=:id";

///Query for Get Templates Items
$sth2 = $conn->prepare($query_get_templates_item);
$sth2->bindParam(':id', $get_id, PDO::PARAM_INT);
try {
    $sth2->execute();
} catch (Exception $e) {
    throw_internal_server_error($e->getMessage());
}
$templates_items = $sth2->fetchAll();

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
                <form action="./controller/templates/template_create.php" id="mainForm" method="">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="name">Template Name</label>
                            <input type="text" name="templateName" id="inputName" class="form-control" value="<?php echo $templates_res['name'] ?>">
                            <input type="hidden" name="templateId" type="text" value="<?php echo $templates_res['id'] ?>">
                        </div>
                    </div class="col-6">
                    <div class="schedule-rows" id="schedules-row">
                        <?php
                        foreach ($templates_items as $key => $schedule) {
                        ?>
                            <div class="row field">
                                <input type="hidden" name="id-<?php echo $key + 1 ?>" value="<?php echo $schedule['id'] ?>">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="task">Task <?php echo $key + 1 ?></label>
                                            <input type="text" name="task-<?php echo $key + 1 ?>" id="task-<?php echo $key + 1 ?>" class="form-control" value="<?php echo $schedule['item'] ?>">
                                        </div>
                                    </div>
                                </div><!-- /.col -->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="totalTime">Fix Time (Minute)</label>
                                            <input type="number" name="totalTime-<?php echo $key + 1 ?>" id="totaltime-<?php echo $key + 1 ?>" class="form-control" value="<?php echo $schedule['FixDurationMinute'] ?>">
                                        </div>
                                    </div>
                                </div><!-- /.col -->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="startTime">Fix Start Time</label>
                                            <input type="time" name="startTime-<?php echo $key + 1 ?>" id="startTime-<?php echo $key + 1 ?>" class="form-control" value="<?php echo get_first_six_chars($schedule['FixStartTime']) ?>">
                                        </div>
                                    </div>
                                </div><!-- /.col -->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="endtime">Fix End Time</label>
                                            <input type="time" name="endTime-<?php echo $key + 1 ?>" id="endTime-<?php echo $key + 1 ?>" class="form-control" value="<?php echo get_first_six_chars($schedule['FixEndTime']) ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <?php } ?>
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

<script src="./lib/time-calibrator.js"></script>
<script>
    function addField() {
        const objectFields = document.getElementById('schedules-row');

        const newField = document.createElement('div');
        newField.classList.add('row');
        newField.classList.add('field');

        newField.innerHTML = `
            <input type="hidden" name="id-${objectFields.children.length + 1}" value="0">
            <div class="col-md-3">
                <div class="form-group">
                    <div class="form-group">
                        <label for="task-${objectFields.children.length + 1}">Task ${objectFields.children.length + 1}</label>
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

    function submitForm() {
        const form = document.getElementById('mainForm');
        const formData = new FormData(form);
        const scheduleData = [];

        const scheduleRows = document.querySelectorAll('.field');
        scheduleRows.forEach((row, index) => {
            const scheduleItem = {
                id: row.querySelector(`[name="id-${index+1}"]`).value,
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

        fetch('./controller/templates/template_item_update.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(dataToSend),
            })
            .then(response => {
                if (response.ok) {
                    alert('Template Schedules Updated');

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
                alert("Failed To Create Object, Please Try Again or Contact The Administrator");
            });
    }
</script>