<?php
ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Data Visualization</title>
    <style>
        /* Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            height: 100vh;
            background: black;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
            overflow: hidden;
            z-index: -1;
        }

        .background-animation {
            position: absolute;
            width: 100%;
            height: 100%;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(50px, 1fr));
        }

        .background-animation div {
            background: rgba(255, 255, 255, 0.1);
            animation: flicker 3s infinite;
            border-radius: 50%;
            width: 10px;
            height: 10px;
        }

        @keyframes flicker {
            0% { opacity: 0.2; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(1.2); }
            100% { opacity: 0.2; transform: scale(1); }
        }

        .container {
            text-align: center;
        }

        .title {
            font-size: 50px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 20px;
            background: linear-gradient(90deg, #00c6ff, #0072ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .button {
            padding: 15px 40px;
            font-size: 1.2rem;
            font-weight: bold;
            color: white;
            background: linear-gradient(90deg, #00c6ff, #0072ff);
            border: none;
            border-radius: 30px;
            text-transform: uppercase;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(0, 118, 255, 0.5);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .button:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(0, 118, 255, 0.7);
        }

        .button a {
            text-decoration: none;
            color: white;
        }

        /* Additional Elements */
        .graphs {
            position: absolute;
            bottom: 0.05%;
            width: 150%;
            display: flex;
            justify-content: space-around;
            align-items: flex-end;
            z-index: 1;
        }

        .bar {
            width: 40px;
            background: linear-gradient(90deg, #00c6ff, #0072ff);
            box-shadow: 0 4px 15px rgba(0, 118, 255, 0.5);
            animation: moveBars 2s infinite;
        }

        @keyframes moveBars {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-50px);
            }
        }

        .bar:nth-child(odd) {
            animation-direction: alternate;
        }

    </style>
</head>
<body>
    <div class="background">
        <div class="background-animation">
            <!-- Creating random glowing dots -->
            <div></div><div></div><div></div><div></div><div></div>
            <div></div><div></div><div></div><div></div><div></div>
        </div>
    </div>
    <div class="container">
        <div class="title">Student Data Visualization</div>
        <button class="button">
            <a href="dashboard.php">Get Started</a>
        </button>
    </div>
    <div class="graphs">
        <div class="bar" style="height: 120px;"></div>
        <div class="bar" style="height: 90px;"></div>
        <div class="bar" style="height: 150px;"></div>
        <div class="bar" style="height: 70px;"></div>
        <div class="bar" style="height: 120px;"></div>
        <div class="bar" style="height: 90px;"></div>
        <div class="bar" style="height: 150px;"></div>
        <div class="bar" style="height: 70px;"></div>
        <div class="bar" style="height: 120px;"></div>
        <div class="bar" style="height: 90px;"></div>
        <div class="bar" style="height: 150px;"></div>
        <div class="bar" style="height: 70px;"></div>
        <div class="bar" style="height: 120px;"></div>
        <div class="bar" style="height: 90px;"></div>
        <div class="bar" style="height: 150px;"></div>
        <div class="bar" style="height: 70px;"></div>
        <div class="bar" style="height: 120px;"></div>
        <div class="bar" style="height: 90px;"></div>
        <div class="bar" style="height: 150px;"></div>
        <div class="bar" style="height: 70px;"></div>
        <div class="bar" style="height: 120px;"></div>
        <div class="bar" style="height: 90px;"></div>
        <div class="bar" style="height: 150px;"></div>
        <div class="bar" style="height: 70px;"></div>
        <div class="bar" style="height: 150px;"></div>
        <div class="bar" style="height: 70px;"></div>
        <div class="bar" style="height: 120px;"></div>
        <div class="bar" style="height: 90px;"></div>
        <div class="bar" style="height: 150px;"></div>
        <div class="bar" style="height: 70px;"></div>
        <div class="bar" style="height: 120px;"></div>
        <div class="bar" style="height: 90px;"></div>
        <div class="bar" style="height: 150px;"></div>
        <div class="bar" style="height: 70px;"></div>
        <div class="bar" style="height: 120px;"></div>
        <div class="bar" style="height: 90px;"></div>
        <div class="bar" style="height: 150px;"></div>
        <div class="bar" style="height: 70px;"></div>
    </div>
</body>
</html>
<?php
// End output buffering and flush the output
ob_end_flush();
?>
