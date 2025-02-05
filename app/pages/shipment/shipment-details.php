<?php
function throw_bad_request($e)
{
    http_response_code(400);
    echo "<script>alert('400 : Bad Request - $e')</script>";
    echo "<script>history.back()</script>";
    die();
}

if (!isset($_GET['id'])) {
    http_response_code(400);
    echo "<script>alert('400 : Bad Request')</script>";
    echo "<script>history.back()</script>";
    die();
}
$shipment_id = $_GET['id'];

$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];
$full_url = $protocol . $host;

require_once('./pages/schedule/schedule_objects.php');
include('../conf/mysql-connect-ShipmentSchedule.php');

///Query for Get All Templates
$query_get_items = "SELECT st.id AS templateId
                            ,st.name AS templateName
                            ,s.id AS scheduleId
                            ,s.scheduleDate
                            ,s.name
                            ,i.id AS itemid
                            ,i.templateitemid
                            ,sti.item AS itemName
                            ,i.durationMinute
                            ,i.startTime
                            ,i.endTime
                        FROM schedule_schedules AS s
                        JOIN schedule_schedules_item AS i ON s.id = i.schedulesid
                        JOIN schedule_template AS st ON s.templateid = st.id
                        JOIN schedule_template_item AS sti ON i.templateitemid = sti.id
                        WHERE s.id = :id
                        ";

$sth = $conn->prepare($query_get_items);
$sth->bindParam(':id', $shipment_id, PDO::PARAM_INT);
$sth->execute();

$templates_res = $sth->fetchAll();
// print_r($templates_res);

$template_id = $templates_res[0]['templateId'];
$template_name = $templates_res[0]['templateName'];
$scheduleName = $templates_res[0]['name'];
$scheduleDate = $templates_res[0]['scheduleDate'];
?>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Shipment Details</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item">Shipment</li>
                    <li class="breadcrumb-item active">Shipment Details</li>
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
                                    <select name="template-select" id="template-select" class="form-control select2" readonly>
                                        <option selected value="<?php echo $template_id ?>"><?php echo $template_name ?></option>
                                    </select>
                                </div>
                                <button type="button" id="select-tempalte-btn" class="btn btn-primary" disabled>Select</button>
                            </div class="col-6">
                        </form>
                        <hr />
                        <div class="row mb-4">
                            <h3 class="card-title">Shipment Schedule</h3>
                        </div>
                        <form action="" id="mainForm" method="">
                            <div class="col-12 mb-3">
                                <div style="display: flex; flex-direction:row; justify-content:flex-start">
                                    <input type="hidden" class="form-control" id="shipment-id" name="shipment-id" value="<?php echo $shipment_id ?>" />
                                    <div style="width: 25%;" class="mr-2">
                                        <label for="shipment-date">Date</label>
                                        <input type="date" class="form-control" id="shipment-date" name="shipment-date" value="<?php echo $scheduleDate ?>" />
                                    </div>
                                    <div style="width: 25%;" class="mx-2">
                                        <label for="shipment-name">Name</label>
                                        <input type="text" class="form-control" id="shipment-name" name="shipment-name" value="<?php echo $scheduleName ?>" />
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
                                <?php foreach ($templates_res as $key => $item) { ?>
                                    <div class="row field">

                                        <div class="col-md-3">
                                            <input type="hidden" id="id-<?php echo ($key + 1) ?>" name="id-<?php echo ($key + 1) ?>"
                                                value="<?php echo ($item['itemid']) ?>" />
                                            <div class="form-group">
                                                <div class="form-group">
                                                    <input type="text" name="task-<?php echo ($key + 1) ?>" id="task-<?php echo ($key + 1) ?>" class="form-control" value="<?php echo $item['itemName'] ?>" readonly>
                                                </div>
                                            </div>
                                        </div><!-- /.col -->
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="form-group">
                                                    <input type="number" name="totalTime-<?php echo ($key + 1) ?>"
                                                        id="totaltime-<?php echo ($key + 1) ?>" class="form-control"
                                                        value="<?php echo $item['durationMinute'] ?>">
                                                </div>
                                            </div>
                                        </div><!-- /.col -->
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="form-group">
                                                    <input type="time" name="startTime-<?php echo ($key + 1) ?>"
                                                        id="startTime-<?php echo ($key + 1) ?>" class="form-control"
                                                        value="<?php echo $item['startTime'] ?>">
                                                </div>
                                            </div>
                                        </div><!-- /.col -->
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="form-group">
                                                    <input type="time" name="endTime-<?php echo ($key + 1) ?>"
                                                        id="endTime-<?php echo ($key + 1) ?>" class="form-control"
                                                        value="<?php echo $item['endTime'] ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="col-md-6">
                                <button type="button" class="btn btn-primary" id="postObject" onclick="createAndSentData()">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
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

        shipmentId = document.getElementById('shipment-id').value;
        shipmentDate = document.getElementById('shipment-date').value;
        shipmentName = document.getElementById('shipment-name').value;
        templateId = document.getElementById('template-select').value;

        fullData = {
            id: shipmentId,
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
        console.log(datas);
        url = './controller/shipment/update_shipment.php';

        fetch(url, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(datas),
            })
            .then(response => {
                if (response.ok) {
                    alert('Shipment Updated');
                    location.reload();
                    return true;
                } else {
                    alert("Failed To Update Shipment, Please Try Again or Contact The Administrator");
                    console.error('Error posting object:', response.status);
                    return false;
                }
            })
            .catch(error => {
                console.error('Error posting object:', error);
                alert("Failed To Update Shipment, Please Try Again or Contact The Administrator");
                return false;
            });
    }
</script>