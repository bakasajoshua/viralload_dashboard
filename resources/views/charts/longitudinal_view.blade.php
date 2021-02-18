<!-- <div  style="margin-left:2em;"> -->
<div>

    <div id="{{ $div }}">

    </div>
    <p>
     Patients on Art as reported on DHIS as at <?php echo date('F', strtotime($as_at)) . ', ' . date('Y', strtotime($as_at)); ?>  - <?php echo number_format($total_patients) ; ?> <br />
     Total Unique Patients Tested - <?php echo number_format($unique_patients) ; ?> <br />

        <?php  
            for ($i=0; $i < $size; $i++) { 
                "No of patients with " . $categories[$i] . " tests - " . number_format($outcomes[0]['data'][$i]) . "<br />";
            }

        ?>
    Total tests - <?php echo number_format($total_tests) ; ?> <br />
    VL Uptake - <?php echo number_format($coverage) ; ?>% <br />
    </p>
</div>

<script type="text/javascript">
    $(function () {
        $('#{{ $div }}abc').highcharts({
            plotOptions: {
                column: {
                    stacking: 'normal'
                }
            },
            chart: {
                type: 'column'
            },
            title: {
                text: "<?php echo $title;?>"
            },
            xAxis: {
                categories: <?php echo json_encode($categories);?>
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Tests'
                },
                stackLabels: {
                    rotation: 0,
                    enabled: true,
                    style: {
                        fontWeight: 'bold',
                        color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                    },
                    y:-10
                }
            },
            legend: {
                layout: 'horizontal',
                align: 'left',
                x: 5,
                verticalAlign: 'bottom',
                y: 5,
                floating: false,
                width: $(window).width() - 20,
                backgroundColor: '#FFFFFF'
            },
            series: <?php echo json_encode($outcomes);?>
        });


        $('#{{ $div }}').highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: ''
            },
            tooltip: {
                pointFormat: '{series.name}: {point.z} <b>({point.percentage:.1f} %)</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.z} ({point.percentage:.1f} %)',
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        }
                    },
                    showInLegend: true
                }
            },
            series: [<?php echo json_encode([
                    'data' => [
                        [
                            'name' => 'Got VL test',
                            'y' => $coverage,
                            'z' => number_format($unique_patients),
                            'color' => '#1BA39C',
                        ],
                        [
                            'name' => 'No VL done',
                            'y' => (100.0 - $coverage),
                            'z' => number_format($total_patients - $unique_patients),
                            'color' => '#F2784B',
                        ],
                    ],
                ]); ?>]

        });



    });
    
</script>