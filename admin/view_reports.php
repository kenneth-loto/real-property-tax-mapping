<?php
include 'header.php';

$functions = new Functions();

// Fetch the report data for the municipality of Naval
$barangaysReport = $functions->fetchBarangaysReport('Naval');

$totalAssessedValue = 0;
$totalCurrentCollection = 0;
$totalTargetCollection = 0;

if (!empty($barangaysReport)) {
    foreach ($barangaysReport as $row) {
        $totalAssessedValue += $row['total_assessed_value'];
        $totalCurrentCollection += $row['current_collection'];
        $totalTargetCollection += $row['target_collection'];
    }
}

// Calculate the total efficiency rate based on the current collection compared to the target collection
$totalEfficiencyRate = ($totalTargetCollection > 0) ? ($totalCurrentCollection / $totalTargetCollection) * 100 : 0;

?>

<link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.bootstrap5.css">
<script defer src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script defer src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script defer src="https://cdn.datatables.net/2.0.3/js/dataTables.js"></script>
<script defer src="https://cdn.datatables.net/2.0.3/js/dataTables.bootstrap5.js"></script>
<link rel="stylesheet" href="../assets/css/requests.css">
<link rel="stylesheet" href="../assets/css/table.css">

<div class="container-fluid text-dark">
    <div class="card">
        <div class="card-body">
            <h3 class="fw-semibold mb-4 mt-2 text-dark">Barangay Reports for Naval</h3>
            <table class="table table-bordered table-hover" id="table">
                <thead>
                    <tr>
                        <th class="header text-light">ID</th>
                        <th class="header text-light">Barangays</th>
                        <th class="header text-light">Total Assessed Value</th>
                        <th class="header text-light">Current Collection</th>
                        <th class="header text-light">Target Collection</th>
                        <th class="header text-light">Collection Efficiency Rate (%)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($barangaysReport)) {
                        $id = 1; // Initialize ID counter
                        foreach ($barangaysReport as $row) :
                            $barangay = $row['barangay'];
                            $total_assessed_value = number_format($row['total_assessed_value'], 2);
                            $current_collection = number_format($row['current_collection'], 2);
                            $target_collection = number_format($row['target_collection'], 2);

                            // Calculate efficiency rate for each barangay
                            $collection_efficiency = ($row['target_collection'] > 0) ? ($row['current_collection'] / $row['target_collection']) * 100 : 0;
                            $formatted_efficiency = number_format($collection_efficiency, 2);
                    ?>
                            <tr class="text-align-left text-dark">
                                <td class='text-center'><?= htmlspecialchars($id); ?></td> <!-- Displaying the ID -->
                                <td><?= htmlspecialchars($barangay); ?></td>
                                <td class='text-center'><?= $total_assessed_value; ?></td>
                                <td class='text-center'><?= $current_collection; ?></td>
                                <td class='text-center'><?= $target_collection; ?></td>
                                <td class='text-center'><?= $formatted_efficiency; ?></td>
                            </tr>
                    <?php
                            $id++; // Increment ID counter
                        endforeach;
                    } else {
                        // Handle case where no barangays exist
                        echo "<tr><td colspan='6' class='text-center text-dark'>No data found for Barangays in Naval</td></tr>";
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr class="text-center text-dark">
                        <td class="text-center"><strong>Total</strong></td>
                        <td></td> <!-- Empty cell for total label alignment -->
                        <td class='text-center'><strong><?= number_format($totalAssessedValue, 2); ?></strong></td>
                        <td class='text-center'><strong><?= number_format($totalCurrentCollection, 2); ?></strong></td>
                        <td class='text-center'><strong><?= number_format($totalTargetCollection, 2); ?></strong></td>
                        <td class="text-center"><strong><?= number_format($totalEfficiencyRate, 2); ?></strong></td> <!-- Total Efficiency Rate -->
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<script src="../assets/libs/jquery/dist/jquery.min.js"></script>
<script src="../assets/js/sidebarmenu.js"></script>

<script>
    $(document).ready(function() {
        $('#table').DataTable();
    });
</script>

</body>
</html>
