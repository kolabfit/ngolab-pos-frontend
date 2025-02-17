<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .animated-bg {
            background: linear-gradient(270deg, #1a1a2e, #090e1c, #ff7700, #000000);
            background-size: 800% 800%;
            animation: gradientBG 10s ease infinite;
        }

        @keyframes gradientBG {
            0% {
                background-position: 0% 0%;
            }

            50% {
                background-position: 100% 100%;
            }

            100% {
                background-position: 0% 0%;
            }
        }
    </style>
</head>

<body class="animated-bg text-white min-h-screen flex flex-col">
    <!-- Navigation -->
    <nav class="bg-gray-900 py-4 shadow-md">
        <div class="container mx-auto flex justify-between items-center">
            <a href="login.html" class="text-2xl font-bold">Ngolab POS</a>
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

            <!-- Notification -->
            <div id="notification" class="hidden p-4 mb-4 text-center rounded-md"></div>

            <form id="registerForm">
                <div class="mb-4">
                    <label for="name" class="block mb-1">Full Name</label>
                    <input id="name" type="text" class="w-full px-3 py-2 bg-gray-900 text-white rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500" name="name" required autofocus>
                    <small class="text-red-500 hidden" id="nameError"></small>
                </div>

                <div class="mb-4">
                    <label for="email" class="block mb-1">E-Mail Address</label>
                    <input id="email" type="email" class="w-full px-3 py-2 bg-gray-900 text-white rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500" name="email" required>
                    <small class="text-red-500 hidden" id="emailError"></small>
                </div>

                <div class="mb-4">
                    <label for="password" class="block mb-1">Password</label>
                    <input id="password" type="password" class="w-full px-3 py-2 bg-gray-900 text-white rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500" name="password" required>
                    <small class="text-red-500 hidden" id="passwordError"></small>
                </div>

                <div class="mb-4">
                    <label for="password-confirm" class="block mb-1">Confirm Password</label>
                    <input id="password-confirm" type="password" class="w-full px-3 py-2 bg-gray-900 text-white rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500" name="password_confirmation" required>
                    <small class="text-red-500 hidden" id="confirmPasswordError"></small>
                </div>

                <div class="mb-4">
                    <label for="position" class="block mb-1">Position</label>
                    <input id="position" type="text" class="w-full px-3 py-2 bg-gray-900 text-white rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500" name="position" required>
                    <small class="text-red-500 hidden" id="positionError"></small>
                </div>

                <div class="mb-4">
                    <label for="employee_id" class="block mb-1">Employee ID</label>
                    <input id="employee_id" type="text" class="w-full px-3 py-2 bg-gray-900 text-white rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500" name="employee_id" required>
                    <small class="text-red-500 hidden" id="employeeIdError"></small>
                </div>

                <div>
                    <button type="submit" class="w-full py-2 px-4 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-md transition">
                        Register
                    </button>
                </div>
            </form>

            <script>
                document.getElementById('registerForm').addEventListener('submit', async function(event) {
                    event.preventDefault();

                    clearErrors();

                    const name = document.getElementById('name').value.trim();
                    const email = document.getElementById('email').value.trim();
                    const password = document.getElementById('password').value;
                    const passwordConfirm = document.getElementById('password-confirm').value;
                    const position = document.getElementById('position').value.trim();
                    const employeeId = document.getElementById('employee_id').value.trim();

                    let isValid = true;

                    if (name.length < 3) {
                        showError('nameError', 'Nama harus minimal 3 karakter');
                        isValid = false;
                    }

                    if (!validateEmail(email)) {
                        showError('emailError', 'Format email tidak valid');
                        isValid = false;
                    }

                    if (password.length < 8) {
                        showError('passwordError', 'Password minimal 8 karakter');
                        isValid = false;
                    }

                    if (password !== passwordConfirm) {
                        showError('confirmPasswordError', 'Password dan konfirmasi harus sama');
                        isValid = false;
                    }

                    if (!isNaN(position)) {
                        showError('positionError', 'Position tidak boleh hanya angka');
                        isValid = false;
                    }

                    if (!/^\d{5,}$/.test(employeeId)) {
                        showError('employeeIdError', 'Employee ID harus minimal 5 angka');
                        isValid = false;
                    }

                    if (!isValid) return;

                    try {
                        const response = await fetch('https://ngolab.id/api/users/register', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                name,
                                email,
                                password,
                                position,
                                employee_id: employeeId
                            })
                        });



                        const result = await response.json();

                        if (response.ok) {
                            showNotification('Registrasi berhasil! Redirecting...', 'bg-green-500');
                            setTimeout(() => {
                                window.location.href = '/logic/login.php';
                            }, 2000);
                        } else {
                            if (result.employee_id) {
                                showError('employeeIdError', 'Employee ID sudah ada, silakan buat yang baru.');
                            }
                            if (result.email) {
                                showError('emailError', 'Email sudah digunakan, silakan pakai email lain.');
                            }

                            if (!result.employee_id && !result.email) {
                                showNotification(result.message || 'Registrasi gagal', 'bg-red-500');
                            }
                        }


                    } catch (error) {
                        showNotification('Terjadi kesalahan, coba lagi', 'bg-red-500');
                    }
                });

                function showNotification(message, bgColor) {
                    const notification = document.getElementById('notification');
                    notification.textContent = message;
                    notification.className = `p-3 text-center text-white rounded-md ${bgColor}`;
                    notification.classList.remove('hidden');

                    setTimeout(() => {
                        notification.classList.add('hidden');
                    }, 3000); // Notifikasi akan hilang setelah 3 detik
                }

                function showError(id, message) {
                    const element = document.getElementById(id);
                    element.textContent = message;
                    element.classList.remove('hidden');
                }

                function clearErrors() {
                    document.querySelectorAll('small.text-red-500').forEach(el => {
                        el.textContent = '';
                        el.classList.add('hidden');
                    });
                }

                function validateEmail(email) {
                    const re = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                    return re.test(email);
                }

                document.querySelectorAll('input').forEach(input => {
                    input.addEventListener('keyup', () => clearErrors());
                });
            </script>

</body>

</html>