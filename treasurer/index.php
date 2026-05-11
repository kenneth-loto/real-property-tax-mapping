<?php
include 'header.php';
include_once '../conn.php';

// Create an instance of the connection class
$dbConnection = new conn();
$pdo = $dbConnection->conn; // Access the PDO connection

// Create an instance of the Functions class
$functions = new Functions();

// Fetch barangay data for graphs
$barangaysData = $functions->fetchBarangaysReport('Naval');

$barangays = [];
$currentCollections = [];
$targetCollections = [];
$collectionEfficiencies = [];

if (!empty($barangaysData)) {
    foreach ($barangaysData as $data) {
        $barangays[] = $data['barangay'];
        $currentCollections[] = $data['current_collection'];
        $targetCollections[] = $data['target_collection'];
        $collectionEfficiencies[] = ($data['target_collection'] > 0) ? 
            ($data['current_collection'] / $data['target_collection']) * 100 : 0; // Calculate efficiency
    }
}

?>

<script src="../assets/libs/jquery/dist/jquery.min.js"></script>
<script src="../assets/libs/apexcharts/dist/apexcharts.min.js"></script>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <h3 class="fw-semibold mb-4 text-dark">Tax Collection Reports</h3>
            <div class="row mb-3">

            <!-- Chart Section -->
            <div class="row mb-3 text-dark">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="fw-semibold text-dark mb-4 mt-3">Barangay Collection Overview</h3>
                            <div id="barangayChart" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 mt-4">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="fw-semibold text-dark mb-4 mt-3">Barangay Collection Effeciency</h3>
                            <div id="efficiencyChart" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Barangay Overview Chart
    const barangayOptions = {
        chart: {
            type: 'bar',
            height: 500
        },
        series: [{
            name: 'Target Collection',
            data: [<?= implode(',', $targetCollections); ?>] // Use target collections
        }, {
            name: 'Current Collection',
            data: [<?= implode(',', $currentCollections); ?>] // Use current collections
        }],
        xaxis: {
            categories: [<?= '"' . implode('","', $barangays) . '"'; ?>] // Use barangay names
        },
        colors: ['#4682b4', '#228b22'] // Set colors for the bars
    };

    const barangayChart = new ApexCharts(document.querySelector("#barangayChart"), barangayOptions);
    barangayChart.render();

    // Collection Efficiency Chart
    const efficiencyOptions = {
        chart: {
            type: 'line',
            height: 500
        },
        series: [{
            name: 'Collection Efficiency',
            data: [<?= implode(',', $collectionEfficiencies); ?>] // Use collection efficiencies
        }],
        xaxis: {
            categories: [<?= '"' . implode('","', $barangays) . '"'; ?>] // Use barangay names
        },
        colors: ['#e67e22'], // Set color for the line
        stroke: {
            width: 3
        }
    };

    const efficiencyChart = new ApexCharts(document.querySelector("#efficiencyChart"), efficiencyOptions);
    efficiencyChart.render();
</script>
<script src="../assets/js/sidebarmenu.js"></script>
</body>
</html>
