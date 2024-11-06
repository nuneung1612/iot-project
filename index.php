<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather Monitoring Dashboard</title>
    <!-- Add Tailwind CSS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.js"></script>
    <style>
        body {
            font-family: "Quicksand", sans-serif;
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .gradient-bg {
            background: white;
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        .float-animation {
            animation: float 3s ease-in-out infinite;
        }

        .weather-card {
            transition: transform 0.3s ease;
        }

        .weather-card:hover {
            transform: scale(1.05);
        }

        .dashboard-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 2rem;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            max-width: 100%;
        }

        /* Added margin between the cards */
        .weather-card {
            margin-bottom: 1.5rem;
        }

        /* Adjusting text size and margins */
        .weather-card h2 {
            margin-bottom: 0.5rem;
        }

        .text-update {
            font-size: 1.2rem;
            margin-top: 1rem;
        }

        /* Improve spacing between weather cards */
        .grid {
            gap: 2rem;
        }

        .float-animation {
            animation: float 3s ease-in-out infinite;
        }
    </style>
</head>
<body class="gradient-bg min-h-screen">

    <?php include("mqtt_test.php"); ?>

    <div class="container">
        <h1 class="text-4xl font-bold text-center text-gray-800 mb-8">
            Weather Monitoring Dashboard
        </h1>

        <p class="text-update text-center text-gray-600">
            Last Updated: <span id="lastUpdate" class="font-semibold"></span>
        </p>

        <div class="grid md:grid-cols-2 gap-6 max-w-4xl mx-auto">
            <!-- Temperature Card -->
            <div class="glass-effect p-6 weather-card">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl text-gray-700 mb-2">Temperature</h2>
                        <div class="text-4xl font-bold text-gray-800">
                            <span id="tempValue">--</span>
                        </div>
                    </div>
                    <div class="text-5xl text-red-500 float-animation">
                        <i class="fas fa-temperature-high"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="h-2 bg-gray-200 rounded">
                        <div class="h-2 bg-red-500 rounded" style="width: 70%"></div>
                    </div>
                </div>
            </div>

            <!-- Humidity Card -->
            <div class="glass-effect p-6 weather-card">
                <div class="flex items-center justify-center">
                    <div>
                        <h2 class="text-xl text-gray-700 mb-2">Humidity</h2>
                        <div class="text-4xl font-bold text-gray-800">
                            <span id="humidityValue">--</span>
                        </div>
                    </div>
                    <div class="text-5xl text-blue-500 float-animation">
                        <i class="fas fa-tint"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="h-2 bg-gray-200 rounded">
                        <div class="h-2 bg-blue-500 rounded" style="width: 60%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Weather Status -->
        <div class="glass-effect p-6 mt-4 max-w-2xl">
            <div class="flex justify-center">
                <img id="weatherIcon" src="weather.png" alt="Weather Icon" class="w-16 h-16 float-animation">
            </div>
        </div>
    </div>

    <script>
        // Update last update time
        function updateLastUpdate() {
            const now = new Date();
            document.getElementById('lastUpdate').textContent = now.toLocaleTimeString();
        }
        
        // Update every 1 second
        setInterval(updateLastUpdate, 1000);
        
        // Initial call
        updateLastUpdate();
    </script>
</body>
</html>
