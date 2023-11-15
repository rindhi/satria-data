<?php
    include "koneksi.php";
    $sql1 = "SELECT a.Year, (SELECT ROUND(SUM(NetweightKG)/1000,2) FROM barang2 WHERE Year = a.Year AND TradeFlow = 'Export') AS export, (SELECT ROUND(SUM(NetweightKG)/1000,2) FROM barang2 WHERE Year = a.Year AND TradeFlow = 'Import') AS import FROM barang AS a GROUP BY a.Year ORDER BY a.Year";
    $sql2 = "SELECT Commodity, ROUND(SUM(NetweightKG)/1000,2) AS jumlah FROM barang WHERE TradeFlow = 'Export' GROUP BY Commodity ORDER BY ROUND(SUM(NetweightKG)/1000,2) DESC LIMIT 5";
    $sql3 = "SELECT Commodity, ROUND(SUM(NetweightKG)/1000,2) AS jumlah FROM barang WHERE TradeFlow = 'Import' GROUP BY Commodity ORDER BY ROUND(SUM(NetweightKG)/1000,2) DESC LIMIT 5";
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Grafik Tim 1</title>
        <link href="tabel.css" rel="stylesheet">
    </head>
    <body>
        <table>
            <tr>
                <th>Tahun</th><th>Jumlah Export</th><th>Jumlah Import</th>
            </tr>
            <?php
                $dt = mysqli_query($koneksi, $sql1);
                while ($z = mysqli_fetch_array($dt)){
                    $thn = $z["Year"];
                    $ex = $z["export"];
                    $im = $z["import"];
                    echo "<tr><td>$thn</td><td>$ex</td><td>$im</td></tr>";
                }
            ?>
        </table>
        <div style="width: 100%; margin-top: 10px; margin-bottom: 10px; height: 450px;" id="gftimeline"></div>
        <div style="width: 100%; margin-top: 10px; margin-bottom: 10px; height: 450px;" id="gf1"></div>
        <div style="width: 100%; margin-top: 10px; margin-bottom: 10px; height: 450px;" id="gf2"></div>
        <table style="margin-bottom: 25px;">
            <tr>
                <th>Komoditas</th><th>Jumlah Export (Ton)</th>
            </tr>
            <?php
                $dt = mysqli_query($koneksi, $sql2);
                while ($z = mysqli_fetch_array($dt)){
                    $prt = $z["Commodity"];
                    $jml = $z["jumlah"];
                    echo "<tr><td>$prt</td><td>$jml</td></tr>";
                }
            ?>
        </table>
        <div style="width: 100%; margin-top: 10px; margin-bottom: 10px; height: 450px;" id="gf3"></div>
        <table style="margin-bottom: 25px;">
            <tr>
                <th>Komoditas</th><th>Jumlah Import (Ton)</th>
            </tr>
            <?php
                $dt = mysqli_query($koneksi, $sql3);
                while ($z = mysqli_fetch_array($dt)){
                    $kmd = $z["Commodity"];
                    $jml = $z["jumlah"];
                    echo "<tr><td>$kmd</td><td>$jml</td></tr>";
                }
            ?>
        </table>
        <div style="width: 100%; margin-top: 10px; margin-bottom: 10px; height: 450px;" id="gf4"></div>
        <script src="highcharts.js"></script>
        <script src="exporting.js"></script>
        <script>
            Highcharts.chart('gftimeline', {
                chart: {type: 'column'},
                title: {text: 'Grafik Timeline Expor Impor'},
                subtitle: {text: 'Pertahun'},
                xAxis: {
                    categories: [
                        <?php
                            $dt = mysqli_query($koneksi, $sql1);
                            while ($z = mysqli_fetch_array($dt)){
                                echo "'".$z["Year"]."',";
                            }
                        ?>
                    ],
                    crosshair: true
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Jumlah (Ton)'
                    }
                },
                tooltip: {
                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                        '<td style="padding:0"><b>{point.y:.2f} Ton</b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: [{
                    name: 'Expor',
                    data: [
                        <?php
                            $dt = mysqli_query($koneksi, $sql1);
                            while ($z = mysqli_fetch_array($dt)){
                                echo $z["export"].",";
                            }
                        ?>
                    ]

                }, {
                    name: 'Impor',
                    data: [
                        <?php
                            $dt = mysqli_query($koneksi, $sql1);
                            while ($z = mysqli_fetch_array($dt)){
                                echo $z["import"].",";
                            }
                        ?>
                    ]

                }]
            });

            Highcharts.chart('gf1', {
                chart: {type: 'areaspline'},
                title: {text: 'Grafik Import Per Tahun'},
                legend: {
                    layout: 'vertical',
                    align: 'left',
                    verticalAlign: 'top',
                    x: 150,
                    y: 100,
                    floating: true,
                    borderWidth: 1,
                    backgroundColor:
                        Highcharts.defaultOptions.legend.backgroundColor || '#FFFFFF'
                },
                xAxis: {
                    categories: [
                        <?php
                            $dt = mysqli_query($koneksi, $sql1);
                            while ($z = mysqli_fetch_array($dt)){
                                echo "'".$z["Year"]."',";
                            }
                        ?>
                    ]
                },
                yAxis: {
                    title: {
                        text: 'Jumlah (Ton)'
                    }
                },
                tooltip: {
                    shared: true,
                    valueSuffix: ' Ton'
                },
                credits: {
                    enabled: false
                },
                plotOptions: {
                    areaspline: {
                        fillOpacity: 0.5
                    }
                },
                series: [{
                    name: 'Import',
                    data: [
                        <?php
                            $dt = mysqli_query($koneksi, $sql1);
                            while ($z = mysqli_fetch_array($dt)){
                                echo $z["import"].",";
                            }
                        ?>
                    ]
                }]
            });

            Highcharts.chart('gf2', {
                chart: {type: 'areaspline'},
                title: {text: 'Grafik Export Per Tahun'},
                legend: {
                    layout: 'vertical',
                    align: 'left',
                    verticalAlign: 'top',
                    x: 150,
                    y: 100,
                    floating: true,
                    borderWidth: 1,
                    backgroundColor:
                        Highcharts.defaultOptions.legend.backgroundColor || '#FFFFFF'
                },
                xAxis: {
                    categories: [
                        <?php
                            $dt = mysqli_query($koneksi, $sql1);
                            while ($z = mysqli_fetch_array($dt)){
                                echo "'".$z["Year"]."',";
                            }
                        ?>
                    ]
                },
                yAxis: {
                    title: {
                        text: 'Jumlah (Ton)'
                    }
                },
                tooltip: {
                    shared: true,
                    valueSuffix: ' Ton'
                },
                credits: {
                    enabled: false
                },
                plotOptions: {
                    areaspline: {
                        fillOpacity: 0.5
                    }
                },
                series: [{
                    name: 'Export',
                    data: [
                        <?php
                            $dt = mysqli_query($koneksi, $sql1);
                            while ($z = mysqli_fetch_array($dt)){
                                echo $z["export"].",";
                            }
                        ?>
                    ]
                }]
            });

            Highcharts.chart('gf3', {
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie'
                },
                title: {text: 'Grafik Komoditas Export Terbesar'},
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                        }
                    }
                },
                series: [{
                    name: 'Komoditas',
                    colorByPoint: true,
                    data: [
                        <?php
                            $dt = mysqli_query($koneksi, $sql2);
                            while ($z = mysqli_fetch_array($dt)){
                                echo "{name:'".$z["Commodity"]."', y:".$z["jumlah"]."},";
                            }
                        ?>
                    ]
                }]
            });

            Highcharts.chart('gf4', {
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie'
                },
                title: {text: 'Grafik Komoditas Import Terbesar'},
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                        }
                    }
                },
                series: [{
                    name: 'Komoditas',
                    colorByPoint: true,
                    data: [
                        <?php
                            $dt = mysqli_query($koneksi, $sql3);
                            while ($z = mysqli_fetch_array($dt)){
                                echo "{name:'".$z["Commodity"]."', y:".$z["jumlah"]."},";
                            }
                        ?>
                    ]
                }]
            });
        </script>
    </body>
</html>