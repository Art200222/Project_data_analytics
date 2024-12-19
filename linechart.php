<?php
// Step 1: Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "student_data"; // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Step 2: Fetch data for the Yearly Revenue Trend (Total Fees Collected per Year)
$sql_revenue = "SELECT `Year of Admission`, SUM(`Fees`) AS total_revenue FROM student_data GROUP BY `Year of Admission` ORDER BY `Year of Admission` DESC";
$result_revenue = $conn->query($sql_revenue);

$admission_years_revenue = [];
$total_revenue_per_year = [];

// Process the data for the total fees collected each year
if ($result_revenue->num_rows > 0) 
    while ($row = $result_revenue->fetch_assoc()) {
        $admission_years_revenue[] = $row['Year of Admission'];
        $total_revenue_per_year[] = $row['total_revenue'];
}

// Reverse the data so the current year is last
$admission_years_revenue = array_reverse($admission_years_revenue);
$total_revenue_per_year = array_reverse($total_revenue_per_year);

// Step 3: Fetch data for the Trend of Discount Percentage on Fees (Average Discount Percentage per Year)
$sql_discount = "SELECT `Year of Admission`, AVG((`Discount on Fees` / `Fees`) * 100) AS avg_discount_percentage 
                 FROM student_data 
                 WHERE `Fees` > 0
                 GROUP BY `Year of Admission` 
                 ORDER BY `Year of Admission` DESC";
$result_discount = $conn->query($sql_discount);

$admission_years_discount = [];
$avg_discount_percentage = [];

// Process the data for the average discount percentage each year
if ($result_discount->num_rows > 0) 
    while ($row = $result_discount->fetch_assoc()) {
        $admission_years_discount[] = $row['Year of Admission'];
        $avg_discount_percentage[] = $row['avg_discount_percentage'];
}

// Reverse the data so the current year is last
$admission_years_discount = array_reverse($admission_years_discount);
$avg_discount_percentage = array_reverse($avg_discount_percentage);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LINE CHARTS</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Chart.js Library -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet"> <!-- Google Font -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet"> <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="styles.css"> <!-- Link to your main CSS file -->
</head>
<body>
    <!-- Sidebar with Links -->
    <div class="sidebar">
        <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="index.php"><i class="fas fa-users"></i> Student Data</a>
        <a href="barcharts.php"><i class="fas fa-chart-bar"></i> Bar Charts</a>
        <a href="piechart.php"><i class="fas fa-pie-chart"></i> Pie Charts</a>
        <a href="linechart.php"><i class="fas fa-chart-line"></i> Line Charts</a> <!-- New Link for Line Charts -->
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Exit</a>
    </div>

    <!-- Main Content Section -->
    <div class="content">
        <!-- Line Chart Section for Yearly Revenue Trend -->
        <div class="container2" style="margin-top: 30px;">
            <h2>Yearly Revenue Trend (Line Chart)</h2>

            <!-- Line Chart for Yearly Revenue -->
            <div class="chart-container1">
                <canvas id="lineRevenueChart"></canvas>
            </div>
        </div>

        <!-- Line Chart Section for Trend of Discount Percentage on Fees -->
        <div class="container2" style="margin-top: 30px;">
            <h2>Trend of Discount Percentage on Fees (Line Chart)</h2>

            <!-- Line Chart for Discount Percentage -->
            <div class="chart-container1">
                <canvas id="lineDiscountChart"></canvas>
            </div>
        </div>
    </div>

    <script>
        // Line Chart for Yearly Revenue Trend
        var ctx1 = document.getElementById('lineRevenueChart').getContext('2d');
        var lineRevenueChart = new Chart(ctx1, {
            type: 'line', // Line chart type
            data: {
                labels: <?php echo json_encode($admission_years_revenue); ?>, // X-axis labels (Year of Admission)
                datasets: [{
                    label: 'Total Fees Collected (₱)',
                    data: <?php echo json_encode($total_revenue_per_year); ?>, // Y-axis data (Total Revenue)
                    borderColor: 'rgba(75, 192, 192, 1)', // Line color
                    backgroundColor: 'rgba(75, 192, 192, 0.2)', // Fill color
                    borderWidth: 2,
                    tension: 0.4 // Smooth line
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                // Format currency
                                return '₱' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });

        // Line Chart for Trend of Discount Percentage on Fees
        var ctx2 = document.getElementById('lineDiscountChart').getContext('2d');
        var lineDiscountChart = new Chart(ctx2, {
            type: 'line', // Line chart type
            data: {
                labels: <?php echo json_encode($admission_years_discount); ?>, // X-axis labels (Year of Admission)
                datasets: [{
                    label: 'Average Discount Percentage on Fees (%)',
                    data: <?php echo json_encode($avg_discount_percentage); ?>, // Y-axis data (Average Discount Percentage)
                    borderColor: 'rgba(153, 102, 255, 1)', // Line color
                    backgroundColor: 'rgba(153, 102, 255, 0.2)', // Fill color
                    borderWidth: 2,
                    tension: 0.4 // Smooth line
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                // Format percentage
                                return value.toFixed(2) + '%';
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
