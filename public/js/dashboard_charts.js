 $(function () {
    Highcharts.chart('total_orders', {
        data: {
            table: 'ordertable'
        },
        chart: {
            type: 'line'
        },
        credits: {
            enabled: false
        },
        colors: ['#ff940a','#09b50b'
        ],
        title: {
            text: ''
        },
        yAxis: {
            allowDecimals: false,
            title: {
                text: 'Units'
            }
        },
        tooltip: {
            formatter: function () {
                return '<b>' + this.series.name + '</b><br/>' +
                    this.point.y + ' - ' + this.point.name.toLowerCase();
            }
        }
    });
});
    
$(function () {
    Highcharts.chart('stock_container', {
        data: {
            table: 'datatable'
        },
        chart: {
            type: 'column',
        },
        credits: {
            enabled: false
        },
        legend: {
            align: 'right',
            verticalAlign: 'top',
            layout: 'vertical',
            x: 0,
            y: 100
        },
        plotOptions: {
            series: {
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                    format: '{point.y}'
                }
            }
        },
        colors: ['#ff940a','#09b50b'
        ],
        title: {
            text: ''
        },
        xAxis: {
            labels: {
                overflow: 'justify'
            }
        },
        yAxis: {
            allowDecimals: false,
            title: {
                text: 'Units'
            }
        },
        tooltip: {
            formatter: function () {
                return '<b>' + this.series.name + '</b><br/>' +
                    this.point.y + ' - ' + this.point.name.toLowerCase();
            }
        }
    });
});