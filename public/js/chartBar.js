$(function () {

//-------------
    //- BAR CHART -
    //-------------
    var barChartCanvas                   = $('#salesChart').get(0).getContext('2d');
    // barChartCanvas.backgroundColor[1] = '#5c656b';
    var barChart                         = new ChartV1(barChartCanvas);
    var barChartData = salesChartData;

    barChartData.datasets[0].fillColor   = '#00a65a';
    // barChartData.datasets[0].bar[1].fillColor   = '#a3a4a6';
    barChartData.datasets[0].strokeColor = '#00a65a';
    barChartData.datasets[0].pointColor  = '#5c656b';


    var barChartOptions                  = {
        //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
        scaleBeginAtZero        : true,
        //Boolean - Whether grid lines are shown across the chart
        scaleShowGridLines      : true,
        //String - Colour of the grid lines
        scaleGridLineColor      : 'rgba(0,0,0,.05)',
        //Number - Width of the grid lines
        scaleGridLineWidth      : 1,
        //Boolean - Whether to show horizontal lines (except X axis)
        scaleShowHorizontalLines: true,
        //Boolean - Whether to show vertical lines (except Y axis)
        scaleShowVerticalLines  : true,
        //Boolean - If there is a stroke on each bar
        barShowStroke           : true,
        //Number - Pixel width of the bar stroke
        barStrokeWidth          : 2,
        //Number - Spacing between each of the X value sets
        barValueSpacing         : 5,
        //Number - Spacing between data sets within X values
        barDatasetSpacing       : 1,
        //String - A legend template
        legendTemplate          : '' +
            '<ul class="<%=name.toLowerCase()%>-legend">' +
            '<% for (var i=0; i<datasets.length; i++){%>' +
            '<li>' +
            '<span style="background-color:<%=datasets[i].fillColor%>">' +
            '</span>' +
            '<%if(datasets[i].label){%><%=datasets[i].label%><%}%>' +
            '</li>' +
            '<%}%>' +
            '</ul>',
        //Boolean - whether to make the chart responsive
        responsive              : true,
        maintainAspectRatio     : true
    };
    barChartOptions.datasetFill = false;

    barChart.Bar(barChartData, barChartOptions);


    // ---------------------------
    // - END MONTHLY SALES CHART -
    // ---------------------------


});