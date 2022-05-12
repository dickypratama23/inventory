@extends('layouts.semantic')
@section('title', 'Laporan Transaksi In')
@section('content')

<figure class="highcharts-figure">
  <div id="container"></div>
  <p class="highcharts-description">
    A variation of a 3D pie chart with an inner radius added.
    These charts are often referred to as donut charts.
  </p>
</figure>

<script type="text/javascript">
  var chart = Highcharts.chart('container', {

    chart: {
        type: 'column'
    },

    title: {
        text: 'Highcharts responsive chart'
    },

    subtitle: {
        text: 'Resize the frame or click buttons to change appearance'
    },

    legend: {
        align: 'right',
        verticalAlign: 'middle',
        layout: 'vertical'
    },

    xAxis: {
        categories: ['LX-310', 'WDCP', 'UPS'],
        labels: {
            x: -10
        }
    },

    yAxis: {
        allowDecimals: false,
        title: {
            text: 'Amount'
        }
    },

    series: [{
        name: 'January',
        data: [1, 4, 3]
    }, {
        name: 'February',
        data: [6, 4, 2]
    }, {
        name: 'Maret',
        data: [8, 4, 3]
    }, {
        name: 'April',
        data: [8, 4, 3]
    }, {
        name: 'May',
        data: [8, 4, 3]
    }, {
        name: 'Juny',
        data: [8, 4, 3]
    }, {
        name: 'July',
        data: [8, 4, 3]
    }, {
        name: 'August',
        data: [8, 4, 3]
    }, {
        name: 'September',
        data: [8, 4, 3]
    }, {
        name: 'October',
        data: [8, 4, 3]
    }, {
        name: 'November',
        data: [8, 4, 3]
    }, {
        name: 'December',
        data: [8, 4, 3]
    }],

    responsive: {
        rules: [{
            condition: {
                maxWidth: 1500
            },
            chartOptions: {
                legend: {
                    align: 'center',
                    verticalAlign: 'bottom',
                    layout: 'horizontal'
                },
                yAxis: {
                    labels: {
                        align: 'left',
                        x: 0,
                        y: -5
                    },
                    title: {
                        text: null
                    }
                },
                subtitle: {
                    text: null
                },
                credits: {
                    enabled: false
                }
            }
        }]
    }
});

chart.setSize(null);

</script>



@endsection