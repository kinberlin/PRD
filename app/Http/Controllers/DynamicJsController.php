<?php

namespace App\Http\Controllers;

use App\Models\Dysfunction;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;

class DynamicJsController extends Controller
{
    public function admin(Request $request)
    {
        //visitor chart
        // Generate the JavaScript content dynamically
        $dysresults = $this->dysAddedLastWeekByDay();
        $dyscounts = $dysresults['dystring'];
        $dysarrays = $dysresults['dysarray'];
        $colors = $this->generateColorsArray($dysarrays);
        $colorsString = '[' . implode(', ', $colors) . ']';
        //activity chart
        $alldys = Dysfunction::whereYear('created_at', Carbon::now()->year)->get();
        $a_dys = $alldys->filter(function ($item) {
            return $item->status == 4;
        })->sortByDesc('created_at')->take(10);
        $codes = $a_dys->pluck('code')->map(function ($code) {
            return "'$code'";
        })->toArray();
        $a_dysStringCat = '[' . implode(', ', $codes) . ']';
        $a_tasks = Task::whereIn('dysfunction', $a_dys->pluck('id'))->where('parent', 0)->get();
        $a_tdata = [];
        foreach ($a_dys->pluck('id')->toArray() as $_a) {
            $a_tdata[] = (100 * $a_tasks->where('dysfunction', $_a)->first()->progress);
        }
        $a_tdataString = '[' . implode(', ', $a_tdata) . ']';
        $a_avgtProgression = collect($a_tdata)->avg() . ' %';
        $longestTask = $a_tasks->pluck('duration')->max() . 'J';
        //Site Chart
        $siteCounts = $alldys->groupBy('site')->map(function ($group) {
            return $group->count();
        });

        // Step 2: Find the maximum count
        $maxCount = $siteCounts->max();

        // Step 3: Filter the first sites that have the maximum count
        $mostFrequentSites = $siteCounts->filter(function ($count) use ($maxCount) {
            return $count == $maxCount;
        });

        $sites = count($alldys) > 0 ? $mostFrequentSites->sum() * 100 / count($alldys) : 0;
        $jsContent = <<<EOT
        "use strict";
!(function () {
    let o, e, r, t, s, a, i, n, l;
    l = isDarkStyle
        ? ((o = config.colors_dark.cardColor),
          (e = config.colors_dark.headingColor),
          (r = config.colors_dark.textMuted),
          (s = config.colors_dark.borderColor),
          (t = "dark"),
          (a = "#4f51c0"),
          (i = "#595cd9"),
          (n = "#8789ff"),
          "#c3c4ff")
        : ((o = config.colors.cardColor),
          (e = config.colors.headingColor),
          (r = config.colors.textMuted),
          (s = config.colors.borderColor),
          (t = ""),
          (a = "#e1e2ff"),
          (i = "#c3c4ff"),
          (n = "#a5a7ff"),
          "#696cff");
          $('#activityDysProgression').text('$a_avgtProgression');
        $('#longestduration').text('$longestTask');
    var d = document.querySelector("#visitorsChart"),
        c = {
            chart: {
                height: 120,
                width: 200,
                parentHeightOffset: 0,
                type: "bar",
                toolbar: { show: !1 },
            },
            plotOptions: {
                bar: {
                    barHeight: "75%",
                    columnWidth: "60%",
                    startingShape: "rounded",
                    endingShape: "rounded",
                    borderRadius: 9,
                    distributed: !0,
                },
            },
            grid: { show: !1, padding: { top: -25, bottom: -12 } },
            colors: $colorsString,
            dataLabels: { enabled: !1 },
            series: [{ data: $dyscounts}],
            legend: { show: !1 },
            responsive: [
                {
                    breakpoint: 1440,
                    options: {
                        plotOptions: {
                            bar: { borderRadius: 9, columnWidth: "60%" },
                        },
                    },
                },
                {
                    breakpoint: 1300,
                    options: {
                        plotOptions: {
                            bar: { borderRadius: 9, columnWidth: "60%" },
                        },
                    },
                },
                {
                    breakpoint: 1200,
                    options: {
                        plotOptions: {
                            bar: { borderRadius: 8, columnWidth: "50%" },
                        },
                    },
                },
                {
                    breakpoint: 1040,
                    options: {
                        plotOptions: {
                            bar: { borderRadius: 8, columnWidth: "50%" },
                        },
                    },
                },
                {
                    breakpoint: 991,
                    options: {
                        plotOptions: {
                            bar: { borderRadius: 8, columnWidth: "50%" },
                        },
                    },
                },
                {
                    breakpoint: 420,
                    options: {
                        plotOptions: {
                            bar: { borderRadius: 8, columnWidth: "50%" },
                        },
                    },
                },
            ],
            xaxis: {
                categories: ["L", "M", "M", "J", "V", "S", "D"],
                axisBorder: { show: !1 },
                axisTicks: { show: !1 },
                labels: { style: { colors: r, fontSize: "13px" } },
            },
            yaxis: { labels: { show: !1 } },
        },
        d =
            (null !== d && new ApexCharts(d, c).render(),
            document.querySelector("#activityChart")),
        c = {
            chart: {
                height: 120,
                width: 280,
                parentHeightOffset: 0,
                toolbar: { show: !0 },
                type: "area",
            },
            dataLabels: { enabled: !1 },
            stroke: { width: 2, curve: "smooth" },
            series: [{ data: $a_tdataString }],
            colors: [config.colors.success],
            fill: {
                type: "gradient",
                gradient: {
                    shade: t,
                    shadeIntensity: 0.8,
                    opacityFrom: 0.8,
                    opacityTo: 0.25,
                    stops: [0, 85, 100],
                },
            },
            grid: { show: !1, padding: { top: -20, bottom: -8 } },
            legend: { show: !1 },
            xaxis: {
                categories: $a_dysStringCat,
                axisBorder: { show: !1 },
                axisTicks: { show: !1 },
                labels: { style: { fontSize: "8px", colors: r } },
            },
            yaxis: { labels: { show: !1 } },
        },
        d =
            (null !== d && new ApexCharts(d, c).render(),
            document.querySelector("#enterpriseChart")),
        c = {
            series: [{ data: [58, 28, 50, 80] }, { data: [50, 22, 65, 72] }],
            chart: {
                type: "bar",
                height: 80,
                toolbar: { tools: { download: !1 } },
            },
            plotOptions: {
                bar: {
                    columnWidth: "65%",
                    startingShape: "rounded",
                    endingShape: "rounded",
                    borderRadius: 3,
                    dataLabels: { show: !1 },
                },
            },
            grid: {
                show: !1,
                padding: { top: -30, bottom: -12, left: -10, right: 0 },
            },
            colors: [config.colors.success, config.colors_label.success],
            dataLabels: { enabled: !1 },
            stroke: { show: !0, width: 5, colors: r },
            legend: { show: !1 },
            xaxis: {
                categories: ["Jan", "Apr", "Jul", "Oct"],
                axisBorder: { show: !1 },
                axisTicks: { show: !1 },
                labels: { style: { colors: r, fontSize: "13px" } },
            },
            yaxis: { labels: { show: !1 } },
        },
        d =
            (null !== d && new ApexCharts(d, c).render(),
            document.querySelector("#sitesChart")),
        c = {
            chart: {
                height: 130,
                sparkline: { enabled: !0 },
                parentHeightOffset: 0,
                type: "radialBar",
            },
            colors: [config.colors.primary],
            series: [$sites],
            plotOptions: {
                radialBar: {
                    startAngle: -90,
                    endAngle: 90,
                    hollow: { size: "55%" },
                    track: { background: config.colors_label.secondary },
                    dataLabels: {
                        name: { show: !1 },
                        value: {
                            fontSize: "22px",
                            color: e,
                            fontWeight: 500,
                            offsetY: 0,
                        },
                    },
                },
            },
            grid: { show: !1, padding: { left: -10, right: -10, top: -10 } },
            stroke: { lineCap: "round" },
            labels: ["Progress"],
        },
        d =
            (null !== d && new ApexCharts(d, c).render(),
            document.querySelector("#totalIncomeChart")),
        c = {
            chart: {
                height: 250,
                type: "area",
                toolbar: !1,
                dropShadow: {
                    enabled: !0,
                    top: 14,
                    left: 2,
                    blur: 3,
                    color: config.colors.primary,
                    opacity: 0.15,
                },
            },
            series: [
                {
                    data: [
                        3350, 3350, 4800, 4800, 2950, 2950, 1800, 1800, 3750,
                        3750, 5700, 5700,
                    ],
                },
            ],
            dataLabels: { enabled: !1 },
            stroke: { width: 3, curve: "straight" },
            colors: [config.colors.primary],
            fill: {
                type: "gradient",
                gradient: {
                    shade: t,
                    shadeIntensity: 0.8,
                    opacityFrom: 0.7,
                    opacityTo: 0.25,
                    stops: [0, 95, 100],
                },
            },
            grid: {
                show: !0,
                borderColor: s,
                padding: { top: -15, bottom: -10, left: 0, right: 0 },
            },
            xaxis: {
                categories: [
                    "Jan",
                    "Feb",
                    "Mar",
                    "Apr",
                    "May",
                    "Jun",
                    "Jul",
                    "Aug",
                    "Sep",
                    "Oct",
                    "Nov",
                    "Dec",
                ],
                labels: { offsetX: 0, style: { colors: r, fontSize: "13px" } },
                axisBorder: { show: !1 },
                axisTicks: { show: !1 },
                lines: { show: !1 },
            },
            yaxis: {
                labels: {
                    offsetX: -15,
                    formatter: function (o) {
                        return "$" + parseInt(o / 1e3) + "k";
                    },
                    style: { fontSize: "13px", colors: r },
                },
                min: 1e3,
                max: 6e3,
                tickAmount: 5,
            },
        },
        d =
            (null !== d && new ApexCharts(d, c).render(),
            document.querySelector("#performanceChart")),
        c = {
            series: [
                { name: "Income", data: [26, 29, 31, 40, 29, 24] },
                { name: "Earning", data: [30, 26, 24, 26, 24, 40] },
            ],
            chart: {
                height: 270,
                type: "radar",
                toolbar: { show: !1 },
                dropShadow: {
                    enabled: !0,
                    enabledOnSeries: void 0,
                    top: 6,
                    left: 0,
                    blur: 6,
                    color: "#000",
                    opacity: 0.14,
                },
            },
            plotOptions: {
                radar: { polygons: { strokeColors: s, connectorColors: s } },
            },
            stroke: { show: !1, width: 0 },
            legend: {
                show: !0,
                fontSize: "13px",
                position: "bottom",
                labels: { colors: "#aab3bf", useSeriesColors: !1 },
                markers: { height: 10, width: 10, offsetX: -3 },
                itemMargin: { horizontal: 10 },
                onItemHover: { highlightDataSeries: !1 },
            },
            colors: [config.colors.primary, config.colors.info],
            fill: { opacity: [1, 0.85] },
            markers: { size: 0 },
            grid: { show: !1, padding: { top: -8, bottom: -5 } },
            xaxis: {
                categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
                labels: {
                    show: !0,
                    style: {
                        colors: [r, r, r, r, r, r],
                        fontSize: "13px",
                        fontFamily: "Public Sans",
                    },
                },
            },
            yaxis: { show: !1, min: 0, max: 40, tickAmount: 4 },
        },
        d =
            (null !== d && new ApexCharts(d, c).render(),
            document.querySelector("#conversionRateChart")),
        c = {
            chart: {
                height: 80,
                width: 140,
                type: "line",
                toolbar: { show: !1 },
                dropShadow: {
                    enabled: !0,
                    top: 10,
                    left: 5,
                    blur: 3,
                    color: config.colors.primary,
                    opacity: 0.15,
                },
                sparkline: { enabled: !0 },
            },
            markers: {
                size: 6,
                colors: "transparent",
                strokeColors: "transparent",
                strokeWidth: 4,
                discrete: [
                    {
                        fillColor: config.colors.white,
                        seriesIndex: 0,
                        dataPointIndex: 3,
                        strokeColor: config.colors.primary,
                        strokeWidth: 4,
                        size: 6,
                        radius: 2,
                    },
                ],
                hover: { size: 7 },
            },
            grid: { show: !1, padding: { right: 8 } },
            colors: [config.colors.primary],
            dataLabels: { enabled: !1 },
            stroke: { width: 5, curve: "smooth" },
            series: [{ data: [137, 210, 160, 245] }],
            xaxis: {
                show: !1,
                lines: { show: !1 },
                labels: { show: !1 },
                axisBorder: { show: !1 },
            },
            yaxis: { show: !1 },
        },
        d =
            (null !== d && new ApexCharts(d, c).render(),
            document.querySelector("#expensesBarChart")),
        c = {
            series: [
                {
                    name: "2021",
                    data: [15, 37, 14, 30, 38, 30, 20, 13, 14, 23],
                },
                {
                    name: "2020",
                    data: [-33, -23, -29, -21, -25, -21, -23, -19, -37, -22],
                },
            ],
            chart: {
                height: 150,
                stacked: !0,
                type: "bar",
                toolbar: { show: !1 },
            },
            plotOptions: {
                bar: {
                    horizontal: !1,
                    columnWidth: "40%",
                    borderRadius: 5,
                    startingShape: "rounded",
                },
            },
            colors: [config.colors.primary, config.colors.warning],
            dataLabels: { enabled: !1 },
            stroke: {
                curve: "smooth",
                width: 2,
                lineCap: "round",
                colors: [o],
            },
            legend: { show: !1 },
            grid: { show: !1, padding: { top: -10 } },
            xaxis: {
                show: !1,
                categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul"],
                labels: { show: !1 },
                axisTicks: { show: !1 },
                axisBorder: { show: !1 },
            },
            yaxis: { show: !1 },
            responsive: [
                {
                    breakpoint: 1440,
                    options: {
                        plotOptions: {
                            bar: { borderRadius: 5, columnWidth: "60%" },
                        },
                    },
                },
                {
                    breakpoint: 1300,
                    options: {
                        plotOptions: {
                            bar: { borderRadius: 5, columnWidth: "70%" },
                        },
                    },
                },
                {
                    breakpoint: 1200,
                    options: {
                        plotOptions: {
                            bar: { borderRadius: 4, columnWidth: "50%" },
                        },
                    },
                },
                {
                    breakpoint: 1040,
                    options: {
                        plotOptions: {
                            bar: { borderRadius: 4, columnWidth: "60%" },
                        },
                    },
                },
                {
                    breakpoint: 991,
                    options: {
                        plotOptions: {
                            bar: { borderRadius: 4, columnWidth: "40%" },
                        },
                    },
                },
                {
                    breakpoint: 420,
                    options: {
                        plotOptions: {
                            bar: { borderRadius: 5, columnWidth: "60%" },
                        },
                    },
                },
                {
                    breakpoint: 360,
                    options: {
                        plotOptions: {
                            bar: { borderRadius: 5, columnWidth: "70%" },
                        },
                    },
                },
            ],
            states: {
                hover: { filter: { type: "none" } },
                active: { filter: { type: "none" } },
            },
        },
        d =
            (null !== d && new ApexCharts(d, c).render(),
            document.querySelector("#totalBalanceChart")),
        c = {
            series: [{ data: [137, 210, 160, 275, 205, 315] }],
            chart: {
                height: 250,
                parentHeightOffset: 0,
                parentWidthOffset: 0,
                type: "line",
                dropShadow: {
                    enabled: !0,
                    top: 10,
                    left: 5,
                    blur: 3,
                    color: config.colors.warning,
                    opacity: 0.15,
                },
                toolbar: { show: !1 },
            },
            dataLabels: { enabled: !1 },
            stroke: { width: 4, curve: "smooth" },
            legend: { show: !1 },
            colors: [config.colors.warning],
            markers: {
                size: 6,
                colors: "transparent",
                strokeColors: "transparent",
                strokeWidth: 4,
                discrete: [
                    {
                        fillColor: config.colors.white,
                        seriesIndex: 0,
                        dataPointIndex: 5,
                        strokeColor: config.colors.warning,
                        strokeWidth: 8,
                        size: 6,
                        radius: 8,
                    },
                ],
                hover: { size: 7 },
            },
            grid: {
                show: !1,
                padding: { top: -10, left: 0, right: 0, bottom: 10 },
            },
            xaxis: {
                categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
                axisBorder: { show: !1 },
                axisTicks: { show: !1 },
                labels: { show: !0, style: { fontSize: "13px", colors: r } },
            },
            yaxis: { labels: { show: !1 } },
        };
    null !== d && new ApexCharts(d, c).render();
})();
EOT;

        // Set the appropriate headers for JavaScript content
        return Response::make($jsContent, 200, [
            'Content-Type' => 'application/javascript',
        ]);
    }
    private function generateColorsArray($productCounts)
    {
        $colors = [];

        foreach ($productCounts as $count) {
            if ($count == 0) {
                $colors[] = 'config.colors_label.primary';
            } elseif ($count > 1) {
                $colors[] = 'config.colors.primary';
            } else {
                // Handle other conditions if needed
                $colors[] = 'config.colors_label.primary';
            }
        }

        return $colors;
    }
    public function dysAddedLastWeekByDay()
    {
        // Initialize an array to hold the results
        $dysCounts = [0, 0, 0, 0, 0, 0, 0]; // Array to store counts for each day (Monday to Sunday)

        // Get the start and end dates for last week (Monday to Sunday)
        $startDate = Carbon::now()->subWeek()->startOfWeek()->addDay(); // Start of last week (Monday)
        $endDate = Carbon::now()->subWeek()->endOfWeek()->addDay(); // End of last week (Sunday)

        // Query to fetch all dyss added last week
        $dyss = Dysfunction::whereBetween('created_at', [$startDate, $endDate])->get();

        // Loop through the dyss collection to count dyss for each day
        foreach ($dyss as $dys) {
            $createdDate = Carbon::parse($dys->created_at)->format('N') - 1; // Get day of the week (1 for Monday, ..., 7 for Sunday)
            $dysCounts[$createdDate]++;
        }

        // Return the array of counts (Monday to Sunday)
        return   ['dystring' => '[' . implode(', ', $dysCounts) . ']', 'dysarray' => $dysCounts];
    }
    /*public function admin(Request $request)
    {
        // Path to your original JavaScript file
        $pathToJsFile = public_path('js/original.js');
        
        // Read the content of the JavaScript file
        $jsContent = file_get_contents($pathToJsFile);

        // Example dynamic modification: Inject Laravel variables or any other logic
        $dynamicVariable = 'Some dynamic value';
        $jsContent = str_replace('PLACEHOLDER', $dynamicVariable, $jsContent);

        // Set the appropriate headers for JavaScript content
        return Response::make($jsContent, 200, [
            'Content-Type' => 'application/javascript',
        ]);
    }*/
}
