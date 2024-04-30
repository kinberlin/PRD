"use strict";

!function () {
    let o, r, e, t, s, i;
    i = (isDarkStyle ? (o = config.colors_dark.cardColor, r = config.colors_dark.headingColor, e = config.colors_dark.bodyColor, t = config.colors_dark.textMuted, config.colors_dark) : (o = config.colors.cardColor, r = config.colors.headingColor, e = config.colors.bodyColor, t = config.colors.textMuted, config.colors)).borderColor;

    $.ajax({
        url: '/admin/dashboard/ep1',
        method: 'GET',
        success: function (data) {
            var a = document.querySelector("#orderStatisticsChart");
            var n = {
                chart: {
                    height: 165,
                    width: 130,
                    type: "donut"
                },
                labels: data.labels,
                series: data.series,
                colors: [config.colors.primary, config.colors.info, config.colors.success],
                stroke: {
                    width: 5,
                    colors: [o]
                },
                dataLabels: {
                    enabled: !1,
                    formatter: function (o, r) {
                        return parseInt(o) + "%"
                    }
                },
                legend: {
                    show: !1
                },
                grid: {
                    padding: {
                        top: 0,
                        bottom: 0,
                        right: 15
                    }
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: "75%",
                            labels: {
                                show: !0,
                                value: {
                                    fontSize: "1.5rem",
                                    fontFamily: "Public Sans",
                                    color: r,
                                    offsetY: -15,
                                    formatter: function (o) {
                                        return parseInt(o) + "%"
                                    }
                                },
                                name: {
                                    offsetY: 20,
                                    fontFamily: "Public Sans"
                                },
                                total: {
                                    show: !0,
                                    fontSize: "0.8125rem",
                                    color: e,
                                    label: data.per,
                                    formatter: function (o) {
                                        return data.avg
                                    }
                                }
                            }
                        }
                    }
                }
            };
            null !== a && new ApexCharts(a, n).render();
        },
        error: function (xhr, status, error) {
            alert('Error fetching data from API:', error);
        }
    });
    $.ajax({
        url: '/admin/dashboard/ep2',
        method: 'GET',
        success: function (data) {
            var s = {
                donut: {
                    series1: config.colors.success,
                    series2: "rgba(113, 221, 55, 0.6)",
                    series3: "rgba(113, 221, 55, 0.4)",
                    series4: "rgba(113, 221, 55, 0.2)",
                },
                line: {
                    series1: config.colors.warning,
                    series2: config.colors.primary,
                    series3: "#7367f029",
                },
            }
            var colors = [];
            var cseries = [s.donut.series1, s.donut.series2, s.donut.series3, s.donut.series4];
            data.colors.forEach(item => {
                // Append each item to the array
                colors.push(cseries[item]);
            });
            var r = {
                chart: { height: 420, parentHeightOffset: 0, type: "donut" },
                labels: data.labels,
                series: data.series,
                colors: colors,
                stroke: { width: 0 },
                dataLabels: {
                    enabled: !1,
                    formatter: function (e, t) {
                        return parseInt(e) + "%";
                    },
                },
                legend: {
                    show: !0,
                    position: "bottom",
                    offsetY: 10,
                    markers: { width: 8, height: 8, offsetX: -3 },
                    itemMargin: { horizontal: 15, vertical: 5 },
                    fontSize: "13px",
                    fontFamily: "Public Sans",
                    fontWeight: 400,
                    labels: { colors: t, useSeriesColors: !1 },
                },
                tooltip: { theme: !1 },
                grid: { padding: { top: 15 } },
                plotOptions: {
                    pie: {
                        donut: {
                            size: "75%",
                            labels: {
                                show: !0,
                                value: {
                                    fontSize: "26px",
                                    fontFamily: "Public Sans",
                                    color: t,
                                    fontWeight: 500,
                                    offsetY: -30,
                                    formatter: function (e) {
                                        return parseInt(e) + "%";
                                    },
                                },
                                name: { offsetY: 20, fontFamily: "Public Sans" },
                                total: {
                                    show: !0,
                                    fontSize: "0.7rem",
                                    label: "Calculus. Errors",
                                    color: e,
                                    formatter: function (e) {
                                        return "~1%";
                                    },
                                },
                            },
                        },
                    },
                },
                responsive: [{ breakpoint: 420, options: { chart: { height: 360 } } }],
            };
            new ApexCharts(document.querySelector("#deliveryExceptionsChart"), r).render();
        },
        error: function (xhr, status, error) {
            console.error(error);
        }
    });


}();
