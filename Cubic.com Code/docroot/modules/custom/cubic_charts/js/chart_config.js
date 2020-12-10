var div_ids = drupalSettings.cubic_charts.cubic_charts_doughnut.div_id;
allData = drupalSettings.cubic_charts.cubic_charts_doughnut.config;

for (var div_id in div_ids) {
    var ctx = document.getElementById(div_id);
    eval('data = ' + allData[div_id] + ';');
    var myDoughnutChart = new Chart(ctx, {
        type: 'doughnut',
        data: data,
        options: {
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        var allData = data.datasets[tooltipItem.datasetIndex].data;
                        var tooltipLabel = data.labels[tooltipItem.index];
                        var tooltipData = allData[tooltipItem.index];
                        var total = 0;
                        for (var i in allData) {
                            total += allData[i];
                        }
                        var tooltipPercentage = Math.round((tooltipData / total) * 100);
                        return tooltipLabel + ': ' + tooltipPercentage + '%';
                    }
                }
            }
        }
    });
}
