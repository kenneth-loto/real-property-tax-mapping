<?php
include 'header.php';
include_once '../conn.php';

// Create an instance of the connection class
$dbConnection = new conn();
$pdo = $dbConnection->conn; // Access the PDO connection

// Fetch properties from the tbl_properties table
$stmt = $pdo->prepare("SELECT id, request_id, name, type, coordinates, payment_status, lot_number FROM properties");
$stmt->execute();
$properties = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Prepare the coordinates for the map
$propertyCoordinates = [];
foreach ($properties as $property) {
    // Assuming 'coordinates' is stored as a JSON array
    $coordinates = json_decode($property['coordinates'], true); // Adjust if needed

    // Check if coordinates are valid
    if (!empty($coordinates) && is_array($coordinates)) {
        $propertyCoordinates[] = [
            'id' => $property['id'],               // Property ID
            'request_id' => $property['request_id'], // Request ID
            'name' => $property['name'],           // Property name
            'type' => $property['type'],           // Property type
            'lot_number' => $property['lot_number'], // Lot number
            'coordinates' => $coordinates,         // Property coordinates
            'payment_status' => $property['payment_status'] // Payment status
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Map</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.bootstrap5.css">
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script> <!-- Removed defer for jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.3/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.3/js/dataTables.bootstrap5.js"></script>
    <link rel="stylesheet" href="../assets/css/header.css">
    <link rel="stylesheet" href="../assets/css/requests.css">
    <link rel="stylesheet" href="../assets/css/table.css">
    <style>
        #map {
            height: 100%;  /* Take the full height of the parent container */
            width: 100%;
            position: relative;
        }

        #legend {
            background: white;
            padding: 10px;
            border: 2px solid black;
            position: absolute;
            bottom: 95px;
            left: 95px;
            z-index: 1000;
            max-width: 250px;
        }

        #table-view {
            display: none;
        }

        /* Full width table */
        table {
            width: 100%; /* Ensures the table takes the full width */
            border-collapse: collapse;
            padding: 10px;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 12px; /* Add padding for better spacing */
            text-align: left;
        }

        #toggle-view {
            position: absolute;
            top: 10px;  /* Position the button at the bottom */
            right: 10px;   /* Position the button to the right */
            z-index: 1001; /* Ensure it is above the map */
        }

        /* DataTable scrolling */
        .dataTables_wrapper {
            max-height: 500px;
            overflow-y: auto;
        }  

        #table-view {
            display: none;
            padding: 40px 30px 30px 30px; /* Add padding on all sides */
            width: 100%; /* Ensure full width of the table container */
            box-sizing: border-box; /* Include padding in the element's total width and height */
        }

        table {
            width: 100%; /* Ensure the table takes the full width */
            border-collapse: collapse;
            padding: 10px;
            box-sizing: border-box; /* Include padding in table's total width */
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 12px; /* Add padding for better spacing */
            text-align: left;
        }    
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="card" style="height: 550px;"> <!-- Set a fixed height for the card -->
        <div class="card-body p-0">
            <div id="map" aria-label="Map showing properties"></div>
            <button id="toggle-view" class="btn btn-secondary">Table View</button>
            <div id="table-view" class="text-dark">
                <h4>Properties Table</h4>
                <table class="table table-bordered table-hover" id="table">
                    <thead class="text-dark">
                        <tr class="text-center">
                            <th class="header text-light">Id</th>
                            <th class="header text-light">Name</th>
                            <th class="header text-light">Type</th>
                            <th class="header text-light">Lot Number</th>
                            <th class="header text-light">Payment Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; // Initialize counter variable ?>
                        <?php foreach ($properties as $property): ?>
                            <tr id="row<?php echo $i; ?>"> <!-- Add unique ID to each row -->
                                <td class="text-center"><?php echo $i; ?></td> <!-- Add Id column value here -->
                                <td><?php echo htmlspecialchars($property['name'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($property['type'] ?? ''); ?></td>
                                <td class="text-center"><?php echo htmlspecialchars($property['lot_number'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($property['payment_status'] ?? ''); ?></td>
                            </tr>
                            <?php $i++; // Increment the counter ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="legend" class="text-dark">
    <h4>Property Types</h4>
    <div><span style="background: #FF4500; width: 15px; height: 15px; display: inline-block;"></span> Commercial</div>
    <div><span style="background: #007FFF; width: 15px; height: 15px; display: inline-block;"></span> Residential</div>
    <div><span style="background: #32CD32; width: 15px; height: 15px; display: inline-block;"></span> Industrial</div>
    <div><span style="background: #FFA500; width: 15px; height: 15px; display: inline-block;"></span> Agricultural</div>
    <div><span style="background: #FF00FF; width: 15px; height: 15px; display: inline-block;"></span> Mixed</div><br>
    <h4>Payment Status</h4>
    <div>
        <span style="background-color: #000; width: 15px; height: 15px; display: inline-block;"></span> Paid
    </div>
    <div>
        <span style="border: 2px solid #000; width: 15px; height: 15px; display: inline-block; background-color: transparent;"></span> Not Yet Paid
    </div>
</div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
    // Function to initialize the map
    function initMap() {
        var map = L.map('map').setView([11.560358021250153, 124.39303069766652], 16); // Center the map

        // Load the base tile layer from OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Define colors for property types
        var colors = {
            "Commercial": "#FF4500",        // Bright Orange
            "Residential": "#007FFF",       // Electric Blue
            "Industrial": "#32CD32",        // Lime Green
            "Agricultural": "#FFA500",      // Harvest Orange
            "Mixed": "#FF00FF"              // Fuchsia
        };

        // Create a blue rectangle for the sample coordinates
        var sampleCoordinates = [
            [11.560318287583371, 124.39163365239023],
            [11.561272038147123, 124.39397860953716],
            [11.560376137513842, 124.3943614828379],
            [11.559385117438296, 124.39203750282138]
        ];

        // Add a blue polygon for the sample area
        var sampleRectangle = L.polygon(sampleCoordinates, { color: "#000000", weight: 2, opacity: 0.5 }).addTo(map); // Blue rectangle
        sampleRectangle.bindPopup("Sample Mapping Area");

        // Coordinates for properties loaded from the database
        const propertyCoordinates = <?php echo json_encode($propertyCoordinates); ?>;

        // Create markers for properties
        propertyCoordinates.forEach((property) => {
            var propertyName = property.name;
            var propertyLotNumber = property.lot_number;
            var propertyType = property.type; 
            var propertyColor = colors[propertyType] || "#ffffff"; // Default to white if type not found
            var fillOpacity = property.payment_status === "Paid" ? 0.5 : 0; // Set fill opacity based on payment status
            var strokeOpacity = property.payment_status === "Paid" ? 0.7 : 1; // Set stroke opacity based on payment status

            // Create a polygon for properties if coordinates have more than 1 point
            if (property.coordinates.length > 1) {
                var polygon = L.polygon(property.coordinates, {
                    color: propertyColor, 
                    weight: 2,
                    fillColor: propertyColor,  // Use the same color for filling
                    fillOpacity: fillOpacity,
                    opacity: strokeOpacity
                }).addTo(map);
                
                // Bind the tooltip to show on hover
                polygon.on('mouseover', function () {
                    this.bindTooltip(`<strong>${propertyName}</strong><br>Type: ${propertyType}<br>Lot Number: ${property.lot_number}<br>Payment Status: ${property.payment_status}`, {
                        permanent: false,
                        direction: 'top',
                        opacity: 1
                    }).openTooltip();
                });
                polygon.on('mouseout', function () {
                    this.closeTooltip();
                });
            } else {
                // If coordinates only represent a single point, create a marker
                var marker = L.marker(property.coordinates).addTo(map);

                // Bind the tooltip to show on hover
                marker.on('mouseover', function () {
                    this.bindTooltip(`<strong>${propertyName}</strong><br>Lot Number: ${propertyLotNumber}`, {
                        permanent: false,
                        direction: 'top',
                        opacity: 1
                    }).openTooltip();
                });
                marker.on('mouseout', function () {
                    this.closeTooltip();
                });
            }
        });

        // Fit the map bounds to include all property polygons and markers
        var allBounds = L.latLngBounds();

        propertyCoordinates.forEach(property => {
            allBounds.extend(property.coordinates);
        });

        map.fitBounds(allBounds);
    }

    // Initialize DataTable with scroll functionality
    function initializeDataTable() {
        $('#table').DataTable({
            scrollY: 300,  // Set the table's vertical scroll limit
            scrollCollapse: true,
            paging: true
        });
    }

    // Toggle between the map and the table view
    document.getElementById('toggle-view').addEventListener('click', function () {
        const mapView = document.getElementById('map');
        const tableView = document.getElementById('table-view');
        const dataTableContainer = $('#table').DataTable();
        const legend = document.getElementById('legend');  // Get the legend element

        if (mapView.style.display === "none") {
            // Map view is hidden, show table view
            mapView.style.display = "block";
            tableView.style.display = "none";
            legend.style.display = "block";
            this.textContent = "Table View";
        } else {
            // Map view is visible, hide it and show table view
            mapView.style.display = "none";
            tableView.style.display = "block";
            legend.style.display = "none";
            this.textContent = "Map View";

            // Destroy existing DataTable instance and reinitialize
            if (dataTableContainer) {
                dataTableContainer.destroy();  // Destroy the existing DataTable
            }
            
            // Reinitialize DataTable after switching to the table view
            initializeDataTable();
        }
    });

    // Initialize the map on window load
    window.onload = function() {
        initMap();
        initializeDataTable();  // Initialize DataTable when the page first loads
    };

</script>

</body>
</html>
