var deferred = $.Deferred();
$.ajax({
    url: "/api/data/" + $("#uselessDysId").val(),
    method: "GET",
    dataType: "json",
    success: function (response) {
        // Parse the JSON response
        var processes = response.processes;
        var dysfunctions = response.dysfunctions;

        // Map the processes array to the required format
        var processList = processes.map(function (process) {
            return {
                key: process.id,
                label: process.name,
            };
        });
        var dysList = dysfunctions.map(function (dys) {
            return {
                key: dys.id,
                label:
                    "(No. : " +
                    dys.id +
                    ") " +
                    "Processus : " +
                    JSON.parse(dys.concern_processes)[0],
            };
        });
        deferred.resolve([processList, dysList]);
    },

    error: function (xhr, status, error) {
        console.error("Error fetching data from the API:", error);
        alert("Error fetching data from the API: ".error);
    },
});
deferred.promise().then(function (valuesArray) {
    var processList = valuesArray[0];
    var dysList = valuesArray[1];

    gantt.config.date_format = "%Y-%m-%d %H:%i:%s";
    gantt.config.order_branch = true;
    gantt.config.order_branch_free = true;
        gantt.config.scrollable = true;
    gantt.config.smooth_scroll = true;

    gantt.i18n.setLocale("fr");
    gantt.plugins({});

    gantt.plugins({
        quick_info: true,
        tooltip: true,
        marker: true,
        grouping: true,
        auto_scheduling: true,
        critical_path: true,
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
            time_enable_button: "Planifier",
            time_disable_button: "Laisser",
        },
    });
    gantt.serverList("process", processList);
    gantt.serverList("dysList", dysList);
    gantt.config.lightbox.sections = [
        {
            name: "dysfunction",
            height: 30,
            map_to: "dysfunction",
            type: "select",
            options: gantt.serverList("dysList"),
        },
        {
            name: "description",
            height: 50,
            map_to: "description",
            type: "textarea",
            focus: true,
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
            options: gantt.serverList("process"),
        },
        {
            name: "time",
            type: "duration",
            map_to: "auto",
        },
    ];
    gantt.templates.rightside_text = function (start, end, task) {
        return byId(gantt.serverList("process"), task.process);
    };
    gantt.templates.grid_row_class =
        gantt.templates.task_row_class =
        gantt.templates.task_class =
            function (start, end, task) {
                var css = [];
                if (task.$virtual || task.type == gantt.config.types.project)
                    css.push("summary-bar");

                if (task.process) {
                    css.push(
                        "gantt_resource_task gantt_resource_" + task.process
                    );
                }
                if (task.dysfunction) {
                    css.push(
                        "gantt_resource_task gantt_resource_" + task.dysfunction
                    );
                }
                if (task.type == gantt.config.types.project) {
                    return "hide_project_progress_drag";
                }
                if (task.progress == 1) {
                    return "completed_task";
                } else {
                    return "";
                }
                return css.join(" ");
            };

    gantt.attachEvent("onLightbox", function (taskId) {
        var task = gantt.getTask(taskId);
        var startDateInput = gantt
            .getLightboxSection("time")
            .node.querySelector("[name='start_date']");

        // Set the minimum start date to today
        startDateInput.setAttribute(
            "min",
            currentDate.toISOString().split("T")[0]
        );

        // If the current start date is in the past, reset it to today
        if (new Date(task.start_date) < currentDate) {
            task.start_date = currentDate;
            gantt.updateTask(taskId);
        }
    });
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

    gantt.config.duration_unit = "day";
    gantt.config.auto_scheduling = true;
    gantt.config.auto_scheduling_strict = true;
    gantt.config.auto_scheduling_initial = true;
    gantt.config.auto_scheduling_compatibility = true;
    gantt.templates.quick_info_content = function (start, end, task) {
        let html = `
                <div class='gantt_info'>
            <div class='gantt_info_title'>
            ${
                task.description ? `${task.description}` : "Aucune description"
            } </div> </div>
        <div class='gantt_info_buttons'>
            <div class='gantt_info_btn_set'>
                ${
                    task.proof
                        ? `<a class='gantt_info_btn' href='/all/task/proof/${task.id}' target='_blank'>Voir la Preuve d'Achevement</a>`
                        : ""
                }
            </div>
        </div>
    `;
        return html;
    };
    gantt.config.scales = [
        {
            unit: "month",
            format: "%F, %Y",
        },
        {
            unit: "day",
            step: 1,
            format: "%j, %D",
        },
    ];
    gantt.attachEvent("onBeforeAutoSchedule", function () {
        gantt.message("Recalculating project schedule...");
        return true;
    });
    gantt.attachEvent(
        "onAfterTaskAutoSchedule",
        function (task, new_date, constraint, predecessor) {
            if (task && predecessor) {
                gantt.message({
                    text:
                        "<b>" +
                        task.text +
                        "</b> has been rescheduled to " +
                        gantt.templates.task_date(new_date) +
                        " due to <b>" +
                        predecessor.text +
                        "</b> constraint",
                    expire: 4000,
                });
            }
        }
    );
    // recalculate progress of summary tasks when the progress of subtasks changes
    (function dynamicProgress() {
        function calculateSummaryProgress(task) {
            if (task.type != gantt.config.types.project) return task.progress;
            var totalToDo = 0;
            var totalDone = 0;
            gantt.eachTask(function (child) {
                if (child.type != gantt.config.types.project) {
                    totalToDo += child.duration;
                    totalDone += (child.progress || 0) * child.duration;
                }
            }, task.id);
            if (!totalToDo) return 0;
            else return totalDone / totalToDo;
        }

        function refreshSummaryProgress(id, submit) {
            if (!gantt.isTaskExists(id)) return;

            var task = gantt.getTask(id);
            var newProgress = calculateSummaryProgress(task);

            if (newProgress !== task.progress) {
                task.progress = newProgress;

                if (!submit) {
                    gantt.refreshTask(id);
                } else {
                    gantt.updateTask(id);
                }
            }

            if (!submit && gantt.getParent(id) !== gantt.config.root_id) {
                refreshSummaryProgress(gantt.getParent(id), submit);
            }
        }

        gantt.attachEvent("onParse", function () {
            gantt.eachTask(function (task) {
                task.progress = calculateSummaryProgress(task);
            });
        });

        gantt.attachEvent("onAfterTaskUpdate", function (id) {
            refreshSummaryProgress(gantt.getParent(id), true);
        });

        gantt.attachEvent("onTaskDrag", function (id) {
            refreshSummaryProgress(gantt.getParent(id), false);
        });
        gantt.attachEvent("onAfterTaskAdd", function (id) {
            refreshSummaryProgress(gantt.getParent(id), true);
        });
        gantt.attachEvent("onTaskCreated", function (task) {
            var today = new Date();
            if (new Date(task.start_date) < today) {
                task.start_date = today;
            }
            return true;
        });

        gantt.attachEvent("onTaskLoading", function (task) {
            return true;
        });
        (function () {
            var idParentBeforeDeleteTask = 0;
            gantt.attachEvent("onBeforeTaskDelete", function (id) {
                idParentBeforeDeleteTask = gantt.getParent(id);
            });
            gantt.attachEvent("onAfterTaskDelete", function () {
                refreshSummaryProgress(idParentBeforeDeleteTask, true);
            });
        })();
    })();
    gantt.config.auto_types = true;
    gantt.templates.progress_text = function (start, end, task) {
        return (
            "<span style='text-align:left;'>" +
            Math.round(task.progress * 100) +
            "% </span>"
        );
    };

    //end recalculations
    var labels = gantt.locale.labels;
    labels.column_dysfunction = labels.section_dysfunction =
        "Dysfonctionnement / Signalement";
    labels.column_process = labels.section_process = "Processus Ciblé";
    labels.column_text = labels.section_text = "Action";
    labels.column_description = labels.section_description = "Description";
    gantt.locale.labels["complete_button"] = "Complete";
    gantt.config.buttons_left = [
        "dhx_save_btn",
        "dhx_cancel_btn",
        "complete_button",
    ];

    function byId(list, id) {
        for (var i = 0; i < list.length; i++) {
            if (list[i].key == id) return list[i].label || "";
        }
        return "";
    }
    gantt.config.columns = [
        {
            name: "text",
            label: "Action (Tâche)",
            tree: true,
            width: "100",
        },
        {
            name: "start_date",
            label: "Date Initiale",
            width: "80",
        },
        {
            name: "duration",
            label: "Durée",
            width: "80",
        },
        {
            name: "process",
            width: 150,
            align: "center",
            label: "Processus",
            template: function (item) {
                return byId(gantt.serverList("process"), item.process);
            },
        },
        {
            name: "add",
            width: 40,
        },
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
            return "weekend";
        }
    };

    gantt.init("gantt_here");

    gantt.attachEvent("onLightboxButton", function (button_id, node, e) {
        if (button_id == "complete_button") {
            // Open the file upload modal
            var myModal = new bootstrap.Modal(
                document.getElementById("fileUploadModal")
            );
            myModal.show();

            var id = gantt.getState().lightbox;
            // Store task ID for later use
            document
                .getElementById("uploadProofButton")
                .setAttribute("data-task-id", id);

            gantt.getTask(id).progress = 0.9;
            gantt.updateTask(id);
            gantt.hideLightbox();
        }
    });
    gantt.attachEvent("onBeforeLightbox", function (id) {
        var task = gantt.getTask(id);
        if (task.progress == 1) {
            gantt.message({
                text: "La tâche est déja terminée",
                type: "completed",
            });
            return false;
        }
        return true;
    });
    document
        .getElementById("uploadProofButton")
        .addEventListener("click", function () {
            var taskId = this.getAttribute("data-task-id");
            var fileInput = document.getElementById("proofFile");

            if (fileInput.files[0].size > 5 * 1024 * 1024) {
                alert("Veuillez entrer un fichier de moins de 5mb!");
                return;
            }

            var formData = new FormData();
            formData.append("file", fileInput.files[0]);
            formData.append("task_id", taskId);

            // AJAX request to upload the file
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "/api/task/proof", true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    // Success logic
                    alert("Fichier soumis avec succes");
                    var myModalEl = document.getElementById("fileUploadModal");
                    var modal = bootstrap.Modal.getInstance(myModalEl);
                    modal.hide();
                    gantt.getTask(taskId).progress = 1;
                    gantt.updateTask(taskId);
                    console.log(xhr.action);
                } else {
                    // Error handling
                    alert("File upload failed");
                }
            };
            xhr.send(formData);
        });

    gantt.load("/api/data/" + $("#uselessDysId").val());

    var dp = new gantt.dataProcessor("/api");
    dp.init(gantt);
    dp.setTransactionMode("REST");
});
