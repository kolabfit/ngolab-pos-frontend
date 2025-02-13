<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Register</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

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
            <a href="login.html" class="text-2xl font-bold">My App</a>
            <ul class="flex space-x-4">
                <li><a href="login.php" class="px-4 py-2 transition border border-transparent hover:border-orange-500 hover:text-orange-500">Login</a></li>
                <li><a href="register.php" class="px-4 py-2 transition border border-transparent hover:border-orange-500 hover:text-orange-500">Register</a></li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow flex justify-center items-center">
        <div class="bg-gray-800 p-8 rounded-lg shadow-lg max-w-md w-full">
            <h2 class="text-center text-3xl font-bold mb-6">Register</h2>
            <div id="notification" class="hidden p-4 mb-4 text-center rounded-md"></div>
            <form id="registerForm">
                <input id="name" type="text" placeholder="Full Name" class="w-full px-3 py-2 mb-2 bg-gray-900 text-white rounded-md" required>
                <input id="email" type="email" placeholder="E-Mail Address" class="w-full px-3 py-2 mb-2 bg-gray-900 text-white rounded-md" required>
                <input id="password" type="password" placeholder="Password" class="w-full px-3 py-2 mb-2 bg-gray-900 text-white rounded-md" required>
                <input id="password-confirm" type="password" placeholder="Confirm Password" class="w-full px-3 py-2 mb-2 bg-gray-900 text-white rounded-md" required>
                <input id="position" type="text" placeholder="Position" class="w-full px-3 py-2 mb-2 bg-gray-900 text-white rounded-md" required>
                <input id="employee_id" type="text" placeholder="Employee ID" class="w-full px-3 py-2 mb-4 bg-gray-900 text-white rounded-md" required>
                <button type="submit" class="w-full py-2 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-md">Register</button>
            </form>
        </div>
    </main>

    <script>
        document.getElementById('registerForm').addEventListener('submit', async function(event) {
            event.preventDefault();
            
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            const passwordConfirm = document.getElementById('password-confirm').value;
            const position = document.getElementById('position').value.trim();
            const employeeId = document.getElementById('employee_id').value.trim();

            // Validasi Form
            if (name.length < 3) {
                showNotification('Nama harus minimal 3 karakter', 'bg-red-500');
                return;
            }

            if (!validateEmail(email)) {
                showNotification('Format email tidak valid', 'bg-red-500');
                return;
            }

            if (password.length < 8) {
                showNotification('Password harus minimal 8 karakter', 'bg-red-500');
                return;
            }

            if (password !== passwordConfirm) {
                showNotification('Password dan konfirmasi harus sama', 'bg-red-500');
                return;
            }

            if (position === "" || employeeId === "") {
                showNotification('Position dan Employee ID harus diisi', 'bg-red-500');
                return;
            }

            try {
                const response = await fetch('https://ngolab.id/api/users/register', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ name, email, password, position, employee_id: employeeId })
                });

                const result = await response.json();
                
                if (response.ok) {
                    showNotification('Registrasi berhasil! Redirecting...', 'bg-green-500');
                    setTimeout(() => { window.location.href = '/logic/login.php'; }, 2000);
                } else {
                    showNotification(result.message || 'Registrasi gagal', 'bg-red-500');
                }
            } catch (error) {
                showNotification('Terjadi kesalahan, coba lagi', 'bg-red-500');
            }
        });

        function showNotification(message, bgColor) {
            const notification = document.getElementById('notification');
            notification.textContent = message;
            notification.className = `p-4 mb-4 text-center rounded-md ${bgColor}`;
            notification.classList.remove('hidden');
        }

        function validateEmail(email) {
            const re = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            return re.test(email);
        }
    </script>
</body>
</html>
