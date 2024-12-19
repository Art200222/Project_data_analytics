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

// Step 2: Fetch data for the bar chart (student count per field of study)
$sql = "SELECT `Field of Study`, COUNT(*) AS student_count FROM student_data GROUP BY `Field of Study`";
$result = $conn->query($sql);

$field_of_study = [];
$student_count = [];

// Process the data into arrays for Chart.js
if ($result->num_rows > 0) 
    while ($row = $result->fetch_assoc()) {
        $field_of_study[] = $row['Field of Study'];
        $student_count[] = $row['student_count'];
}

// Step 3: Fetch data for the horizontal bar chart (students count per year of admission)
// Updated query to reflect the correct column name "Year of Admission"
// Sorting by Year of Admission in descending order
$sql_year = "SELECT `Year of Admission`, COUNT(*) AS student_count FROM student_data GROUP BY `Year of Admission` ORDER BY `Year of Admission` DESC";
$result_year = $conn->query($sql_year);

$admission_years = [];
$students_by_year = [];

// Process the data for admission year
if ($result_year->num_rows > 0) 
    while ($row = $result_year->fetch_assoc()) {
        $admission_years[] = $row['Year of Admission'];
        $students_by_year[] = $row['student_count'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BAR CHARTS</title>
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
        <a href="linechart.php"><i class="fas fa-chart-line"></i> Line Charts</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Exit</a>
    </div>

    <!-- Main Content Section -->
    <div class="content">
        <div class="container" style="margin-bottom: 30px;">
            <h2>Student Count per Field of Study</h2>
            <h2>(Vertical Bar Chart)</h2>

            <!-- Vertical Bar Chart -->
            <div class="chart-container3">
                <canvas id="barChart"></canvas>
            </div>
        </div>

        <div class="container"style="margin-top: 30px;">
            <h2>Student Count per Year of Admission </h2>
            <h2> (Horizontal Bar Chart)</h2>

            <!-- Horizontal Bar Chart -->
            <div class="chart-container3">
                <canvas id="horizontalBarChart"></canvas>
            </div>
        </div>
    </div>

    <script>
        // Vertical Bar Chart (Field of Study)
        var ctx1 = document.getElementById('barChart').getContext('2d');
        var barChart = new Chart(ctx1, {
            type: 'bar', // Bar chart type
            data: {
                labels: <?php echo json_encode($field_of_study); ?>, // Labels for the X-axis
                datasets: [{
                    label: 'Number of Students',
                    data: <?php echo json_encode($student_count); ?>, // Data for the Y-axis
                    backgroundColor: 'rgba(54, 162, 235, 0.2)', // Bar color
                    borderColor: 'rgba(54, 162, 235, 1)', // Border color for bars
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Horizontal Bar Chart (Year of Admission)
        var ctx2 = document.getElementById('horizontalBarChart').getContext('2d');
        var horizontalBarChart = new Chart(ctx2, {
            type: 'bar', // Bar chart type
            data: {
                labels: <?php echo json_encode($admission_years); ?>, // Labels for the Y-axis
                datasets: [{
                    label: 'Number of Students',
                    data: <?php echo json_encode($students_by_year); ?>, // Data for the X-axis
                    backgroundColor: 'rgba(255, 99, 132, 0.2)', // Bar color
                    borderColor: 'rgba(255, 99, 132, 1)', // Border color for bars
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                indexAxis: 'y', // Set the axis to be horizontal
                scales: {
                    x: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>

<?php
// Step 4: Close the database connection
$conn->close();
?>
