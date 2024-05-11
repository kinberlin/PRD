

var deferred = $.Deferred();
$.ajax({
    url: '/api/data',
    method: 'GET',
    dataType: 'json',
    success: function (response) {
        // Parse the JSON response
        var processes = response.processes;

        // Map the processes array to the required format
        var processList = processes.map(function (process) {
            return {
                key: process.id,
                label: process.name
            };
        });
        deferred.resolve(processList);
    },
    error: function (xhr, status, error) {
        console.error('Error fetching data from the API:', error);
        alert('Error fetching data from the API: '.error);
    }
});
deferred.promise().then(function (processList) {
    gantt.config.date_format = "%Y-%m-%d %H:%i:%s";
    gantt.config.order_branch = true;
    gantt.config.order_branch_free = true;
    gantt.i18n.setLocale('fr');
    gantt.plugins({

    });

    gantt.plugins({
        quick_info: true,
        tooltip: true,
        marker: true,
        grouping: true,
        auto_scheduling: true,
        critical_path: true
    });
    function updateCriticalPath(toggle) {
        toggle.enabled = !toggle.enabled;
        if (toggle.enabled) {
            toggle.innerHTML = "Masquer le Chemin Critique";
            gantt.config.highlight_critical_path = true;
        } else {
            toggle.innerHTML = "Afficher le Chemin Critique";
            gantt.config.highlight_critical_path = false;
        }
        gantt.render();
    }
    gantt.attachEvent("onGanttReady", function () {
        var tooltips = gantt.ext.tooltips;
        tooltips.tooltip.setViewport(gantt.$task_data);
    });

    gantt.i18n.setLocale({
        labels: {
            time_enable_button: 'Planifier',
            time_disable_button: 'Laisser',
        }
    });
    gantt.serverList("process", processList);
    gantt.config.lightbox.sections = [{
        name: "description",
        height: 50,
        map_to: "description",
        type: "textarea",
        focus: true
    },
    {
        name: "text",
        height: 30,
        map_to: "text",
        type: "textarea",

    },
    {
        name: "process",
        height: 30,
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
    gantt.templates.rightside_text = function (start, end, task) {
        return byId(gantt.serverList('process'), task.process);
    };
    gantt.templates.grid_row_class =
        gantt.templates.task_row_class =
        gantt.templates.task_class = function (start, end, task) {
            var css = [];
            if (task.$virtual || task.type == gantt.config.types.project)
                css.push("summary-bar");

            if (task.process) {
                css.push("gantt_resource_task gantt_resource_" + task.process);
            }

            return css.join(" ");
        };
    gantt.attachEvent("onLightboxSave", function (id, task, is_new) {
        task.unscheduled = !task.start_date;
        return true;
    });

    gantt.templates.scale_cell_class = function (date) {
        if (!gantt.isWorkTime(date)) {
            return "weekend";
        }
    };
    gantt.templates.timeline_cell_class = function (item, date) {
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
    gantt.attachEvent("onBeforeAutoSchedule", function () {
        gantt.message("Recalculating project schedule...");
        return true;
    });
    gantt.attachEvent("onAfterTaskAutoSchedule", function (task, new_date, constraint, predecessor) {
        if (task && predecessor) {
            gantt.message({
                text: "<b>" + task.text + "</b> has been rescheduled to " + gantt.templates
                    .task_date(
                        new_date) + " due to <b>" + predecessor.text + "</b> constraint",
                expire: 4000
            });
        }
    });
    var labels = gantt.locale.labels;
    labels.column_process = labels.section_process = "Processus Ciblé";
    labels.column_text = labels.section_text = "Action";
    labels.column_description = labels.section_description = "Description";
    gantt.locale.labels["complete_button"] = "Complete";
    gantt.config.buttons_left = ["dhx_save_btn", "dhx_cancel_btn", "complete_button"];
    gantt.templates.task_class = function (start, end, task) {
        if (task.progress == 1)
            return "completed_task";
        return "";
    };
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
        template: function (item) {
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
    gantt.templates.scale_cell_class = function (date) {
        if (date.getDay() == 0 || date.getDay() == 6) {
            return "weekend";
        }
    };
    gantt.templates.timeline_cell_class = function (item, date) {
        if (date.getDay() == 0 || date.getDay() == 6) {
            return "weekend"
        }
    };
    gantt.init("gantt_here");

    gantt.attachEvent("onLightboxButton", function (button_id, node, e) {
        if (button_id == "complete_button") {
            var id = gantt.getState().lightbox;
            gantt.getTask(id).progress = 1;
            gantt.updateTask(id)
            gantt.hideLightbox();
        }
    });
    gantt.attachEvent("onBeforeLightbox", function (id) {
        var task = gantt.getTask(id);
        if (task.progress == 1) {
            gantt.message({ text: "La tâche est déja terminée", type: "completed" });
            return false;
        }
        return true;
    });

    gantt.load("/api/data");

    var dp = new gantt.dataProcessor("/api");
    dp.init(gantt);
    dp.setTransactionMode("REST");


});