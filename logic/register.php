<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Register</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Custom Style for Background Animation -->
    <style>
        .animated-bg {
            background: linear-gradient(270deg, #1a1a2e, #090e1c, #ff7700, #000000);
            background-size: 800% 800%;
            animation: gradientBG 10s ease infinite;
        }
        @keyframes gradientBG {
            0% { background-position: 0% 0%; }
            50% { background-position: 100% 100%; }
            100% { background-position: 0% 0%; }
        }
    </style>
</head>
<body class="animated-bg text-white min-h-screen flex flex-col">

    <!-- Navigation -->
    <nav class="bg-gray-900 py-4 shadow-md">
        <div class="container mx-auto flex justify-between items-center">
            <a href="login.html" class="text-2xl font-bold">
                My App
            </a>
            <div>
                <ul class="flex space-x-4">
                    <li>
                        <a href="login.php" class="px-4 py-2 transition border border-transparent hover:border-orange-500 hover:text-orange-500">
                            Login
                        </a>
                    </li>
                    <li>
                        <a href="register.php" class="px-4 py-2 transition border border-transparent hover:border-orange-500 hover:text-orange-500">
                            Register
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow flex justify-center items-center">
        <div class="bg-gray-800 p-8 rounded-lg shadow-lg max-w-md w-full">
            <h2 class="text-center text-3xl font-bold mb-6">Register</h2>

            <form method="POST" action="https://ngolab.id/api/users/register">
                <div class="mb-4">
                    <label for="name" class="block mb-1">Full Name</label>
                    <input id="name" type="text" class="w-full px-3 py-2 bg-gray-900 text-white rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500" name="name" required autofocus>
                </div>

                <div class="mb-4">
                    <label for="email" class="block mb-1">E-Mail Address</label>
                    <input id="email" type="email" class="w-full px-3 py-2 bg-gray-900 text-white rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500" name="email" required>
                </div>

                <div class="mb-4">
                    <label for="password" class="block mb-1">Password</label>
                    <input id="password" type="password" class="w-full px-3 py-2 bg-gray-900 text-white rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500" name="password" required>
                </div>

                <div class="mb-4">
                    <label for="password-confirm" class="block mb-1">Confirm Password</label>
                    <input id="password-confirm" type="password" class="w-full px-3 py-2 bg-gray-900 text-white rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500" name="password_confirmation" required>
                </div>

                <div class="mb-4">
                    <label for="position" class="block mb-1">Position</label>
                    <input id="position" type="text" class="w-full px-3 py-2 bg-gray-900 text-white rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500" name="position" required>
                </div>

                <div class="mb-4">
                    <label for="employee_id" class="block mb-1">Employee ID</label>
                    <input id="employee_id" type="text" class="w-full px-3 py-2 bg-gray-900 text-white rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500" name="employee_id" required>
                </div>

                <div>
                    <button type="submit" class="w-full py-2 px-4 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-md transition">
                        Register
                    </button>
                </div>
            </form>
        </div>
    </main>

    <!-- Tailwind JS (optional for animations) -->
    <script>
        // Custom hover effects for buttons can be added via Tailwind's transition classes
    </script>
</body>
</html>