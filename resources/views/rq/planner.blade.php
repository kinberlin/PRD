<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="{!! url('assets/js/gantt-master/codebase/dhtmlxgantt.js') !!}"></script>
    <link rel="stylesheet" href="{!! url('assets/js/gantt-master/codebase/dhtmlxgantt.css') !!}" type="text/css" />
    <title>Document</title>
    <style>
        html,
        body {
            height: 100%;
            padding: 0px;
            margin: 0px;
            overflow: hidden;
        }

        .weekend {
            background: #f4f7f4 !important;
        }
    </style>
</head>

<body>
    <div id="gantt_here" style="width: 100%; height: 100vh"></div>
    <script>
        gantt.config.date_format = "%Y-%m-%d %H:%i:%s";
        gantt.config.order_branch = true;
        gantt.config.order_branch_free = true;
        gantt.i18n.setLocale('fr');
        gantt.plugins({
            quick_info: true,
            tooltip: true,
            marker: true,
            grouping: true,
            auto_scheduling: true
        });
        gantt.attachEvent("onGanttReady", function() {
            var tooltips = gantt.ext.tooltips;
            tooltips.tooltip.setViewport(gantt.$task_data);
        });

        gantt.i18n.setLocale({
            labels: {
                time_enable_button: 'Planifier',
                time_disable_button: 'Laisser',
            }
        });
        gantt.serverList("process", [{
                key: 1,
                label: "John",
                backgroundColor: "#03A9F4",
                textColor: "#FFF"
            },
            {
                key: 2,
                label: "Mike",
                backgroundColor: "#f57730",
                textColor: "#FFF"
            },
            {
                key: 3,
                label: "Anna",
                backgroundColor: "#e157de",
                textColor: "#FFF"
            },
            {
                key: 4,
                label: "Bill",
                backgroundColor: "#78909C",
                textColor: "#FFF"
            },
            {
                key: 7,
                label: "Floe",
                backgroundColor: "#8D6E63",
                textColor: "#FFF"
            }
        ]);
        gantt.config.lightbox.sections = [{
                name: "description",
                height: 38,
                map_to: "description",
                type: "textarea",
                focus: true
            },
            {
                name: "text",
                height: 22,
                map_to: "text",
                type: "textarea",

            },
            {
                name: "process",
                height: 22,
                map_to: "process",
                type: "select",
                options: gantt.serverList("process")
            },
            {
                name: "time",
                type: "duration",
                map_to: "auto"
            }
        ];
        gantt.templates.rightside_text = function(start, end, task) {
            return byId(gantt.serverList('process'), task.process);
        };
        gantt.templates.grid_row_class =
            gantt.templates.task_row_class =
            gantt.templates.task_class = function(start, end, task) {
                var css = [];
                if (task.$virtual || task.type == gantt.config.types.project)
                    css.push("summary-bar");

                if (task.process) {
                    css.push("gantt_resource_task gantt_resource_" + task.process);
                }

                return css.join(" ");
            };
        gantt.attachEvent("onLightboxSave", function(id, task, is_new) {
            task.unscheduled = !task.start_date;
            return true;
        });

        gantt.templates.scale_cell_class = function(date) {
            if (!gantt.isWorkTime(date)) {
                return "weekend";
            }
        };
        gantt.templates.timeline_cell_class = function(item, date) {
            if (!gantt.isWorkTime(date)) {
                return "weekend";
            }
        };

        gantt.config.work_time = true;


        gantt.config.auto_scheduling = true;
        gantt.config.auto_scheduling_strict = true;
        gantt.config.auto_scheduling_compatibility = true;
        gantt.config.scales = [{
                unit: "month",
                format: "%F, %Y"
            },
            {
                unit: "day",
                step: 1,
                format: "%j, %D"
            }
        ];
        gantt.attachEvent("onBeforeAutoSchedule", function() {
            gantt.message("Recalculating project schedule...");
            return true;
        });
        gantt.attachEvent("onAfterTaskAutoSchedule", function(task, new_date, constraint, predecessor) {
            if (task && predecessor) {
                gantt.message({
                    text: "<b>" + task.text + "</b> has been rescheduled to " + gantt.templates.task_date(
                        new_date) + " due to <b>" + predecessor.text + "</b> constraint",
                    expire: 4000
                });
            }
        });
        /*gantt.config.columns = [{
                name: "text",
                label: "Action(Tâche)",
                align: "center",
                width: "100"
            },
            {
                name: "start_date",
                label: "Début",
                align: "center",
                width: "70"
            },
            {
                name: "duration",
                label: "Durée",
                align: "center",
                width: "60"
            },
            {
                name: "process",
                label: "Processus",
                align: "center",
                width: "100"
            }
        ];*/

        var labels = gantt.locale.labels;
        labels.column_process = labels.section_process = "Processus Ciblé";
        labels.column_text = labels.section_text = "Action";
        labels.column_description = labels.section_description = "Description";

        function byId(list, id) {
            for (var i = 0; i < list.length; i++) {
                if (list[i].key == id)
                    return list[i].label || "";
            }
            return "";
        }
        gantt.config.columns = [{
                name: "text",
                label: "Action (Tâche)",
                tree: true,
                width: '100'
            },
            {
                name: "start_date",
                label: "Date Initiale",
                width: '80'
            },
            {
                name: "duration",
                label: "Durée",
                width: '80'
            },
            {
                name: "process",
                width: 150,
                align: "center",
                label: 'Processus',
                template: function(item) {
                    return byId(gantt.serverList('process'), item.process)
                }
            },
            {
                name: "add",
                width: 40
            }
        ];
        gantt.config.scale_height = 3 * 28;
        gantt.config.order_branch = true;
        gantt.config.grid_width = 420;
        gantt.config.row_height = 24;
        gantt.config.grid_resize = true;
        gantt.init("gantt_here");

        gantt.load("/api/data");

        var dp = new gantt.dataProcessor("/api");
        dp.init(gantt);
        dp.setTransactionMode("REST");
    </script>
</body>

</html>
