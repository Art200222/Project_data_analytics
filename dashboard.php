<?php
// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "student_data"; // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Step 3: Fetch data for dashboard metrics
$sql_total_students = "SELECT COUNT(*) AS total_students FROM student_data";
$result_total_students = $conn->query($sql_total_students);
$total_students = $result_total_students->fetch_assoc()['total_students'];

$sql_students_enrolled_this_year = "SELECT COUNT(*) AS students_enrolled FROM student_data WHERE `Year of Admission` = YEAR(CURDATE())";
$result_students_enrolled_this_year = $conn->query($sql_students_enrolled_this_year);
$students_enrolled_this_year = $result_students_enrolled_this_year->fetch_assoc()['students_enrolled'];

$sql_total_revenue = "SELECT SUM(Fees) AS total_revenue FROM student_data";
$result_total_revenue = $conn->query($sql_total_revenue);
$total_revenue = $result_total_revenue->fetch_assoc()['total_revenue'];

$sql_fields = "SELECT `Field of Study`, COUNT(*) AS student_count FROM student_data GROUP BY `Field of Study`";
$result_fields = $conn->query($sql_fields);

$students_in_fields = [];
while ($row = $result_fields->fetch_assoc()) {
    $students_in_fields[$row['Field of Study']] = $row['student_count'];
}

$field_icons = [
    'Chemical Engineering' => 'fas fa-flask',
    'Computer Science' => 'fas fa-laptop-code',
    'Civil Engineering' => 'fas fa-drafting-compass',
    'Mechanical Engineering' => 'fas fa-cogs',
    'Electrical Engineering' => 'fas fa-bolt',
];

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DASHBOARD</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Your existing styles... */
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
            color: #fff;
            margin: 0;
        }
        .content4 {
            margin-left: 220px;
            padding: 20px;
        }
        .container4 {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            gap: 30px;
            margin-left: 30px;
            margin-top: 60px;
        }
        .card4 {
            position: relative;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            width: 280px;
            height: 250px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.5);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card4:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 30px rgba(0, 255, 255, 0.6);
        }
        .card4::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #00c6ff, #0072ff);
            z-index: -1;
            clip-path: circle(0% at 50% 50%);
            transition: clip-path 0.6s ease-in-out;
        }
        .card4:hover::before {
            clip-path: circle(150% at 50% 50%);
        }
        .icon, .icon-field {
            font-size: 30px;
            margin-bottom: 10px;
            color: #00c6ff;
        }
        .card4 h3, .card4 h4 {
            font-size: 19px;
            margin: 10px 0;
        }
        .card4 p {
            font-size: 28px;
            font-weight: bold;
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
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Exit</a> <!-- Add logout link -->
    </div>

    <!-- Main Content -->
    <div class="content4">
        <h2 style="text-align:center; margin-bottom: 20px; font-size: 50px; color: #00c6ff">WELCOME TO DASHBOARD</h2>
        <div class="container4">
            <!-- Card for Total Students -->
            <div class="card4">
                <div class="icon"><i class="fas fa-users"></i></div>
                <h3>Total Students</h3>
                <p><?php echo number_format($total_students); ?></p>
            </div>

            <!-- Card for Students Enrolled This Year -->
            <div class="card4">
                <div class="icon"><i class="fas fa-user-plus"></i></div>
                <h3>Students Enrolled This Year</h3>
                <p><?php echo number_format($students_enrolled_this_year); ?></p>
            </div>
            git config --global user.email "johnart@gmail.com"
            git config --global user.name "Pacatang"
            <!-- Card for Total Revenue -->
            <div class="card4">
                <div class="icon"><i class="fas fa-dollar-sign"></i></div>
                <h3>Total Revenue</h3>
                <p>₱<?php echo number_format($total_revenue); ?></p>
            </div>

            <!-- Cards for each Field of Study -->
            <?php
            foreach ($students_in_fields as $field => $student_count) {
                $icon = isset($field_icons[$field]) ? $field_icons[$field] : 'fas fa-graduation-cap';
                echo "
                    <div class='card4'>
                        <div class='icon-field'><i class='$icon'></i></div>
                        <h3>$field</h3>
                        <p>" . number_format($student_count) . "</p>
                    </div>
                ";
            }
            ?>
        </div>
    </div>
</body>
</html>
