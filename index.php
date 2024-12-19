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

// Step 2: Get the search query (if any)
$search = isset($_GET['search']) ? $_GET['search'] : ''; // Get search query

// Step 3: Set up pagination variables
$records_per_page = 10; // Number of records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Get current page or default to 1
$offset = ($page - 1) * $records_per_page; // Calculate offset for SQL query

// Step 4: Modify the SQL query to include the search filter
$sql = "SELECT * FROM student_data WHERE `Student Name` LIKE ? OR `Field of Study` LIKE ? LIMIT $records_per_page OFFSET $offset";

// Prepare the SQL query
$stmt = $conn->prepare($sql);
$search_term = "%" . $search . "%"; // Add wildcards for partial matching
$stmt->bind_param("ss", $search_term, $search_term); // Bind parameters

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

// Step 5: Calculate total records for pagination links
$total_records_sql = "SELECT COUNT(*) FROM student_data WHERE `Student Name` LIKE ? OR `Field of Study` LIKE ?";
$total_stmt = $conn->prepare($total_records_sql);
$total_stmt->bind_param("ss", $search_term, $search_term);
$total_stmt->execute();
$total_result = $total_stmt->get_result();
$total_records = $total_result->fetch_row()[0];
$total_pages = ceil($total_records / $records_per_page); // Total pages based on records
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>STUDENT DATA</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet"> <!-- Google Font -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet"> <!-- Font Awesome for icons -->
    <style>
        /* General Layout */
        body, h2, table, th, td {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f7f7f7;
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styling */
        .sidebar {
            width: 250px;
            background: linear-gradient(135deg, #3498db, #8e44ad); /* Gradient background */
            color: white;
            padding-top: 30px;
            position: fixed;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 10;
            box-shadow: 4px 0 6px rgba(0, 0, 0, 0.1); /* Add shadow for depth */
        }

        .sidebar a {
            padding: 15px 20px;
            text-decoration: none;
            font-size: 18px;
            color: white;
            display: block;
            transition: background-color 0.3s ease;
            margin-bottom: 15px; /* Space between links */
        }

        .sidebar a:hover {
            background-color: rgba(255, 255, 255, 0.1); /* Light hover background */
        }

        .sidebar a i {
            margin-right: 15px;
            font-size: 20px;
            transition: transform 0.3s ease;
        }

        .sidebar a:hover i {
            transform: translateX(5px); /* Slight movement of icon on hover */
        }

        /* Content Area */
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

        /* Search Bar */
        .search-bar {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
        }

        .search-bar input {
            padding: 10px;
            font-size: 16px;
            width: 60%;
            max-width: 400px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .search-bar button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .search-bar button:hover {
            background-color: #0056b3;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #007BFF;
            color: white;
            font-weight: 500;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        /* Pagination Styling */
        .pagination {
            text-align: center;
            margin-top: 30px;
        }

        .pagination a {
            padding: 10px 20px;
            margin: 0 5px;
            text-decoration: none;
            background-color: #007BFF;
            color: white;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .pagination a:hover {
            background-color: #0056b3;
        }

        .pagination .disabled {
            background-color: #ddd;
            pointer-events: none;
        }

        .pagination .active {
            background-color: #28a745;
        }

        /* Responsive Design */
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

            .sidebar a {
                text-align: center;
                font-size: 20px;
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
<h2 style="text-align:center; margin-bottom: 20px; font-size: 50px; color:rgb(2, 46, 97)" >STUDENT DATA OVERVIEW</h2>

    <!-- Right-aligned Search Bar -->
    <div class="search-bar">
        <form method="get" action="">
            <input type="text" name="search" placeholder="Student Name" value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">Search</button>
        </form>
    </div>

    <?php
    // Check if there are rows
    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<tr>";
        echo "<th>Student ID</th>";
        echo "<th>Student Name</th>";
        echo "<th>Date of Birth</th>";
        echo "<th>Field of Study</th>";
        echo "<th>Year of Admission</th>";
        echo "<th>Year Level</th>";  
        echo "<th>Fees</th>";
        echo "<th>Discount on Fees</th>";
        echo "</tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['Student ID'] . "</td>";
            echo "<td>" . $row['Student Name'] . "</td>";
            echo "<td>" . $row['Date of Birth'] . "</td>";
            echo "<td>" . $row['Field of Study'] . "</td>";
            echo "<td>" . $row['Year of Admission'] . "</td>";
            echo "<td>" . $row['Year Level'] . "</td>";  
            echo "<td>" . $row['Fees'] . "</td>";
            echo "<td>" . $row['Discount on Fees'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";

        // Pagination Links
        echo "<div class='pagination'>";
        if ($page <= 1) {
            echo "<span class='disabled'>&laquo; Prev</span>";
        } else {
            echo "<a href='?page=" . ($page - 1) . "&search=" . urlencode($search) . "'>&laquo; Prev</a>";
        }
        if ($page >= $total_pages) {
            echo "<span class='disabled'>Next &raquo;</span>";
        } else {
            echo "<a href='?page=" . ($page + 1) . "&search=" . urlencode($search) . "'>Next &raquo;</a>";
        }
        echo "</div>";
    } else {
        echo "<p>No data found.</p>";
    }
    ?>
</div>

</body>
</html>

<?php
// Step 10: Close the connection
$conn->close();
?>
