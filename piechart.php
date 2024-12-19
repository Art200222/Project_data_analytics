<?php
// connection sa database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "student_data";

$conn = new mysqli($servername, $username, $password, $dbname);

// Mao ni code for checking connection sa database
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// code para mo kuha sa data nga gamiton sa pie chart
$field_of_study = [];
$total_fees = [];
$total_discounts = [];

$sql = "SELECT `Field of Study`, SUM(`Fees`) AS total_fees, SUM(`Discount on Fees`) AS total_discounts FROM student_data GROUP BY `Field of Study`";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $field_of_study[] = $row['Field of Study'];
        $total_fees[] = $row['total_fees'];
        $total_discounts[] = $row['total_discounts'];
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PIE CHARTS</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css"> 
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f7f7f7;
            display: flex;
            min-height: 100vh;
            margin: 0;
        }

        .sidebar {
            width: 250px;
            background: linear-gradient(135deg, #3498db, #8e44ad);
            color: white;
            padding-top: 30px;
            position: fixed;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 10;
            box-shadow: 4px 0 6px rgba(0, 0, 0, 0.1);
        }

        .sidebar a {
            padding: 15px 20px;
            text-decoration: none;
            font-size: 18px;
            color: white;
            display: block;
            margin-bottom: 15px;
        }

        .sidebar a:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .content {
            margin-left: 270px;
            padding: 20px;
            width: calc(100% - 270px);
        }

        h2 {
            text-align: center;
            font-size: 40px;
            color: #333;
            margin-bottom: 20px;
        }

        .chart-section {
            margin-top: 20px;
        }

        .chart-container {
            position: relative;
            width: 80%;
            height: 400px;
            margin: 20px auto;
        }

        canvas {
            width: 100% !important;
            height: 100% !important;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                padding-top: 0;
            }

            .content {
                margin-left: 0;
                width: 100%;
            }
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="index.php"><i class="fas fa-users"></i> Student Data</a>
    <a href="barcharts.php"><i class="fas fa-chart-bar"></i> Bar Charts</a>
    <a href="piechart.php"><i class="fas fa-pie-chart"></i> Pie Charts</a>
    <a href="linechart.php"><i class="fas fa-chart-line"></i> Line Charts</a>
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Exit</a>
</div>

<!-- Content Area -->
<div class="content">

    <div class="container" style="margin-bottom: 30px;">
            <h2>Total Fees Collected per Field of Study</h2>
            <div class="chart-container">
                <canvas id="feesPieChart"></canvas>
            </div>
    </div>

    <div class="container" style="margin-bottom: 30px;">
            <h2>Total Discounts per Field of Study</h2>
            <div class="chart-container">
                <canvas id="discountsPieChart"></canvas>
            </div>
    </div>

   
    
</div>

<script>
    // Pag gama sa pie chart gamit ang Chart.js
    var ctxFees = document.getElementById('feesPieChart').getContext('2d');
    new Chart(ctxFees, {
        type: 'pie',
        data: {
            labels: <?php echo json_encode($field_of_study); ?>,
            datasets: [{
                label: 'Total Fees Collected',
                data: <?php echo json_encode($total_fees); ?>,
                backgroundColor: ['rgba(54, 162, 235, 0.2)', 'rgba(255, 99, 132, 0.2)', 'rgba(75, 192, 192, 0.2)', 'rgba(153, 102, 255, 0.2)', 'rgba(255, 159, 64, 0.2)'],
                borderColor: ['rgba(54, 162, 235, 1)', 'rgba(255, 99, 132, 1)', 'rgba(75, 192, 192, 1)', 'rgba(153, 102, 255, 1)', 'rgba(255, 159, 64, 1)'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' },
            }
        }
    });

    var ctxDiscounts = document.getElementById('discountsPieChart').getContext('2d');
    new Chart(ctxDiscounts, {
        type: 'pie',
        data: {
            labels: <?php echo json_encode($field_of_study); ?>,
            datasets: [{
                label: 'Total Discounts',
                data: <?php echo json_encode($total_discounts); ?>,
                backgroundColor: ['rgba(75, 192, 192, 0.2)', 'rgba(153, 102, 255, 0.2)', 'rgba(255, 159, 64, 0.2)', 'rgba(54, 162, 235, 0.2)', 'rgba(255, 99, 132, 0.2)'],
                borderColor: ['rgba(75, 192, 192, 1)', 'rgba(153, 102, 255, 1)', 'rgba(255, 159, 64, 1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 99, 132, 1)'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' },
            }
        }
    });
</script>

</body>
</html>
