<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="{!! url('assets/js/gantt-master/codebase/dhtmlxgantt.js') !!}"></script>
    <link rel="stylesheet" href="{!! url('assets/js/gantt-master/codebase/dhtmlxgantt.css') !!}" type="text/css" />
    <title>Document</title>
</head>

<body>
    <div id="gantt_here" style="width: 100%; height: 100vh"></div>
    <script>
        gantt.config.date_format = "%d-%m-%Y %H:%i:%s";
        gantt.config.order_branch = true;
        gantt.config.order_branch_free = true;
        gantt.init("gantt_here");

        gantt.load("/api/data");

        var dp = new gantt.dataProcessor("/api");
        dp.init(gantt);
        dp.setTransactionMode("REST");
    </script>
</body>

</html>
