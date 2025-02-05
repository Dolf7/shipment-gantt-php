<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<h1>Gantt Chart Examples</h1>

<div id="chart_div" style="border: 2px solid red;"></div>
<div id="timeline" style="height: 200px; padding:0px 10px"></div>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', {
        'packages': ['timeline']
    });
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var container = document.getElementById('timeline');
        var chart = new google.visualization.Timeline(container);
        var dataTable = new google.visualization.DataTable();

        dataTable.addColumn({
            type: 'string',
            id: 'President'
        });
        dataTable.addColumn({
            type: 'string',
            id: 'Test'
        });
        dataTable.addColumn({
            type: 'date',
            id: 'Start'
        });
        dataTable.addColumn({
            type: 'date',
            id: 'End'
        });
        dataTable.addRows([
            ['Washington', 'ABC', new Date(0, 0, 0, 10, 20), new Date(0, 0, 0, 12, 30)],
            ['Adams', 'EFG', new Date(0, 0, 0, 20, 30), new Date(0, 0, 0, 20, 50)],
            ['Jefferson', 'HIJ', new Date(0, 0, 0, 20, 50), new Date(0, 0, 0, 21, 30)]
        ]);

        chart.draw(dataTable);
    }
</script>