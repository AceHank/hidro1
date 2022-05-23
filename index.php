<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
}else{
    include "conn.php";
    $user = $_SESSION['user'];
    $sql_user = "SELECT * FROM user WHERE user='$user'";
    $result_user = $conn->query($sql_user);
    $data_user = mysqli_fetch_assoc($result_user);

    //Data tampilan
    $sql = "SELECT * FROM alat_izul ORDER BY ID DESC LIMIT 1";
    $result = $conn->query($sql);
    $data = mysqli_fetch_assoc($result);

    $sql2 = "SELECT * FROM alat_desi ORDER BY ID DESC LIMIT 1";
    $result2 = $conn->query($sql2);
    $data2 = mysqli_fetch_assoc($result2);
    
    //mengambil data untuk grapik
    $sqlChart = "SELECT date(tgl) as tgl, AVG(ph) as ph, AVG(tds) as tds FROM alat_izul GROUP BY DATE_FORMAT(tgl, '%Y%m%d') 
                 ORDER BY tgl DESC LIMIT 7";
    $resultChart = $conn->query($sqlChart);

    $sqlChart2 = "SELECT date(tgl) as tgl, AVG(kelembaban) as kelembaban, AVG(suhu) as suhu FROM alat_desi GROUP BY DATE_FORMAT(tgl, '%Y%m%d') 
                  ORDER BY tgl DESC LIMIT 7";
    $resultChart2 = $conn->query($sqlChart2);

    //Data Chart Izul
    $tgl = [];
    $ph  = [];
    $tds  = [];
    while ($r = mysqli_fetch_assoc($resultChart)){
  
    array_push($tgl, $r['tgl']); //memasukan data ke array
    array_push($ph, $r['ph']);
    array_push($tds, $r['tds']);
    }

    $tgl = array_reverse($tgl); //membaik urutan data
    $ph = array_reverse($ph);
    $tds = array_reverse($tds);

    //Data Chart Desi
    $kelembaban  = [];
    $suhu  = [];
    while ($r2 = mysqli_fetch_assoc($resultChart2)){

    array_push($kelembaban, $r2['kelembaban']);
    array_push($suhu, $r2['suhu']);
    }

    
    $kelembaban = array_reverse($kelembaban);
    $suhu = array_reverse($suhu);
    //Charts

//==============================================================================================================================================
?>

<?php include "head_html.php"; ?>

<!-- PAGE-HEADER -->
<br>
<!-- PAGE-HEADER END -->

<!-- ROW-1 -->
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
                <div class="card overflow-hidden">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="mt-2">
                                <h3 class="">PH</h3>
                                <h1 class="mb-0 number-font"><?php echo $data['ph'] ?> PH</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
                <div class="card overflow-hidden">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="mt-2">
                                <h3 class="">TDS</h3>
                                <h1 class="mb-0 number-font"><?php echo $data['tds'] ?> ppm</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
                <div class="card overflow-hidden">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="mt-2">
                                <h3 class="">Kelembaban</h3>
                                <h1 class="mb-0 number-font"><?php echo $data2['kelembaban'] ?> RH</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
                <div class="card overflow-hidden">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="mt-2">
                                <h3 class="">Suhu</h3>
                                <h1 class="mb-0 number-font"><?php echo $data2['suhu'] ?> °C</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ROW-1 END -->

<!-- ROW-2 -->
<div class="row">
    <div class="col-lg-6 col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Alat 1</h3>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="myChart" class="h-275"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Alat 2</h3>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="myChart2" class="h-275"></canvas>
                </div>
            </div>
        </div>
    </div>
    <!-- COL END -->

</div>
<!-- ROW-2 END -->

<!-- CHARTS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
//myChart
const labels = <?php echo json_encode($tgl); ?>;
const labels2 = <?php echo json_encode($tgl); ?>;

//Data Chart
const data = {
    labels: labels,
    datasets: [{
            label: 'PH',
            backgroundColor: 'black',
            borderColor: 'black',
            data: <?php echo json_encode($ph); ?>,
        },
        {
            label: 'TDS',
            backgroundColor: 'green',
            borderColor: 'green',
            data: <?php echo json_encode($tds); ?>,
        },
    ]
};
const data2 = {
    labels: labels2,
    datasets: [{
            label: 'Kelembaban',
            backgroundColor: 'yellow',
            borderColor: 'yellow',
            data: <?php echo json_encode($kelembaban); ?>,
        },
        {
            label: 'Suhu',
            backgroundColor: 'red',
            borderColor: 'red',
            data: <?php echo json_encode($suhu); ?>,
        },
    ]
};

//Pengaturan tipe dan data chart Izul dan Desi
const config = {
    type: 'line',
    data: data,
    options: {}
};
const config2 = {
    type: 'line',
    data: data2,
    options: {}
};

//Membuat chart Izul dan Desi
const myChart = new Chart(
    document.getElementById('myChart'),
    config
);
const myChart2 = new Chart(
    document.getElementById('myChart2'),
    config2
);
</script>

<?php include "footer_html.php"; ?>










<?php
//================================================================================================================================================


}
?>