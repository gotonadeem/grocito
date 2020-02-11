<?php
        if($user[0]!=0)
            {
                $growthRate = ($user[1] - $user[0]) / $user[0] * 100;
                $growthRate = ($growthRate > 0) ? number_format($growthRate,  2, '.', '') . "%" : "0%";
            }
        else{
            $growthRate='0%';
        }

?>
<div class="col-md-8">
<div class="box-body">
    <div class="chart">
        <canvas id="barChart" style="height:230px"></canvas>
    </div>
</div>
</div>
<div class="col-md-4" style="padding:75px 0 0 0"><h4>Growth Rate: <strong>{{@$growthRate}}</strong></h4></div>
<div style="clear:both"></div>


<script>
$(function () {
        
        var barChartCanvas = $("#barChart").get(0).getContext("2d");
        var barChart = new Chart(barChartCanvas);
        var barChartData = {
          labels: ["<?php echo $months[0]?>","<?php echo $months[1]?>"],
          datasets: [
            {
              label: "Total Users",
              fillColor: "rgba(210, 214, 222, 1)",
              strokeColor: "rgba(210, 214, 222, 1)",
              pointColor: "rgba(210, 214, 222, 1)",
              pointStrokeColor: "#c1c7d1",
              pointHighlightFill: "#fff",
              pointHighlightStroke: "rgba(220,220,220,1)",
              data: [<?php echo implode(",",$user) ?>]
            },
            
          ]
        };
        barChartData.datasets[0].fillColor = "#00a65a";
        barChartData.datasets[0].strokeColor = "#00a65a";
        barChartData.datasets[0].pointColor = "#00a65a";
        var barChartOptions = {
          //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
          scaleBeginAtZero: true,
          //Boolean - Whether grid lines are shown across the chart
          scaleShowGridLines: true,
          //String - Colour of the grid lines
          scaleGridLineColor: "rgba(0,0,0,.05)",
          //Number - Width of the grid lines
          scaleGridLineWidth: 1,
          //Boolean - Whether to show horizontal lines (except X axis)
          scaleShowHorizontalLines: true,
          //Boolean - Whether to show vertical lines (except Y axis)
          scaleShowVerticalLines: true,
          //Boolean - If there is a stroke on each bar
          barShowStroke: true,
          //Number - Pixel width of the bar stroke
          barStrokeWidth: 2,
          //Number - Spacing between each of the X value sets
          barValueSpacing: 5,
          //Number - Spacing between data sets within X values
          barDatasetSpacing: 1,
          //String - A legend template
          legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].fillColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
          //Boolean - whether to make the chart responsive
          responsive: true,          
          maintainAspectRatio: true,
          tooltipTemplate: "<%if (label){%><%= datasetLabel %>: <%}%><%= value %>", 
        };

        barChartOptions.datasetFill = false;
        barChart.Bar(barChartData, barChartOptions);
    
      });
</script>