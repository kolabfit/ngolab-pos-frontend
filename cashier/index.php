<?php
require_once('../logic/loginvalidation.php');
require_once('../logic/slotvalidation.php');
require_once('../logic/selfServiceMiddleware.php');

Validation::validateLoginCashier($_COOKIE['auth_token'] ?? null, '../logic/login.php');
Slotvalidation::isnotfillslot($_COOKIE['auth_token'] ?? null);

// Panggil middleware untuk mencegah akses ke index.php jika slot Self Service
SelfServiceMiddleware::restrictListTransaksiIndexForSelfService($_COOKIE['auth_token'] ?? null);

$token = $_COOKIE['auth_token'];

// Ambil nilai slot yang dipilih (misalnya disimpan di cookie 'selected_slot')
$selected_slot = $_COOKIE['selected_slot'] ?? null;

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem POS - Beranda</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-white border-b sticky top-0 z-50 w-full">
        <div class="flex items-center justify-between h-14 px-4">
            <!-- Brand & Search -->
            <div class="flex items-center space-x-8">
                <!-- <img src="/media/All_Logo_KoLab.png" alt="Ko+Lab Logo" class="h-12" /> -->
                <img src="media/All_Logo_KoLab.png" alt="logo kolab" class="h-12">
                <div class="relative">
                    <input type="text" placeholder="Search transaction..."
                        class="w-80 px-4 py-2 pl-10 rounded-lg bg-gray-100 focus:outline-none focus:ring-2 focus:ring-yellow-400"
                        id="search" />
                    <div class="absolute left-3 top-2.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M11 4a7 7 0 100 14 7 7 0 000-14zM21 21l-4.35-4.35" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Profile section -->
            <div class="flex items-center space-x-4">
                <!-- User Name -->
                <span id="userNameDisplay" class="text-gray-700 font-medium hidden md:block"></span>

                <!-- Profile button and dropdown -->
                <div class="relative">
                    <!-- Button -->
                    <button id="profileButton"
                        class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 12c2.28 0 4-1.72 4-4s-1.72-4-4-4-4 1.72-4 4 1.72 4 4 4zm0 2c-4.42 0-8 1.79-8 4v2h16v-2c0-2.21-3.58-4-8-4z" />
                        </svg>
                    </button>

                    <!-- Dropdown -->
                    <div id="profileDropdown"
                        class="hidden absolute right-0 mt-2 w-48 bg-white border rounded-lg shadow-md">
                        <button id="logoutButton"
                            class="block w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100 rounded-t-lg">Logout</button>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar & Main Content -->
    <div class="flex">
        <aside class="w-64 bg-white shadow-md h-screen sticky top-0 self-start">
            <nav class="p-6 space-y-4">
                <!-- Menu Beranda: hanya aktif jika bukan Self Service -->
                <?php if ($selected_slot != "5"): ?>
                    <a href="index.php"
                        class="block py-2 px-4 p-3 mb-3 rounded-lg font-medium bg-gradient-to-r from-orange-400 to-yellow-400 text-white">
                        Beranda
                    </a>
                <?php else: ?>
                    <span class="block py-2 px-4 p-3 mb-3 rounded-lg font-medium text-gray-400 cursor-not-allowed">
                        Beranda
                    </span>
                <?php endif; ?>

                <!-- Menu Transaksi -->
                <a href="transaksi.php"
                    class="block py-2 px-4 mb-3 rounded-lg font-medium text-gray-600 hover:bg-gray-100">
                    Transaksi
                </a>
                <!-- Menu List Transaksi (misalnya, aktif jika tidak Self Service) -->
                <?php if ($selected_slot !== '5'): ?>
                    <a href="listtransaksi.php"
                        class="block py-2 px-4 mb-3 rounded-lg font-medium text-gray-600 hover:bg-gray-100">
                        List Transaksi
                    </a>
                <?php else: ?>
                    <span class="block py-2 px-4 mb-3 rounded-lg font-medium text-gray-400 cursor-not-allowed">
                        List Transaksi
                    </span>
                <?php endif; ?>
            </nav>
        </aside>


        <!-- Main Content -->
        <main class="flex-1 p-8">
            <div class="bg-white shadow-md rounded-lg p-8">
                <h1 class="text-2xl font-semibold mb-4">Selamat Datang di Sistem POS</h1>
                <p class="text-gray-600">Pilih menu dari sidebar untuk memulai transaksi.</p>

                <div class="my-2 mt-4">
                    <select id="outletSelect"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-yellow-400 mb-2">
                        <option value="" disabled selected>Pilih Outlet</option>
                    </select>
                </div>

                <!-- Input Saldo -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt4">
                    <div class="border border-gray-200 rounded-lg p-4">
                        <form id="initialBalanceForm">
                            <label class="block text-gray-700 font-medium mb-2">Input Saldo Awal</label>
                            <input type="number" id="initialBalance" placeholder="Rp"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-yellow-400 mb-2">
                            <button type="submit"
                                class="bg-gradient-to-r from-orange-400 to-yellow-400 text-white px-3 py-3 text-sm rounded-full hover:opacity-90">Set
                                Saldo Awal</button>
                        </form>
                    </div>
                    <div class="border border-gray-200 rounded-lg p-4">
                        <form id="finalBalanceForm">
                            <label class="block text-gray-700 font-medium mb-2">Input Saldo Akhir</label>
                            <input type="number" id="finalBalance" placeholder="Rp"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-yellow-400 mb-2">
                            <button type="submit"
                                class="bg-gradient-to-r from-orange-400 to-yellow-400 text-white px-3 py-3 text-sm rounded-full hover:opacity-90">Set
                                Saldo Akhir</button>
                        </form>
                    </div>
                </div>

                <!-- Balance Display -->
                <div id="balanceDisplay" class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="bg-white shadow-md border rounded-lg p-6">
                        <div class="text-center">
                            <h3 class="text-gray-700 font-medium">Saldo Awal</h3>
                            <p class="text-2xl font-bold text-gray-800" id="displayInitialBalance">Rp 0</p>
                        </div>
                    </div>
                    <div class="bg-white shadow-md border rounded-lg p-6">
                        <div class="text-center">
                            <h3 class="text-gray-700 font-medium">Saldo Akhir</h3>
                            <p class="text-2xl font-bold text-gray-800" id="displayFinalBalance">Rp 0</p>
                        </div>
                    </div>
                    <div class="bg-white shadow-md border rounded-lg p-6">
                        <div class="text-center">
                            <h3 class="text-gray-700 font-medium">Selisih Saldo</h3>
                            <p class="text-2xl font-bold" id="displayBalanceDifference">Rp 0</p>
                        </div>
                    </div>
                    <div class="bg-white shadow-md border rounded-lg p-6">
                        <div class="text-center">
                            <h3 class="text-gray-700 font-medium">Total Produk Terjual</h3>
                            <p class="text-2xl font-bold text-gray-800" id="displayTotalTransactions">0 pcs</p>
                        </div>
                    </div>
                </div>

                <!-- Total Statistics -->
                <!-- <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="bg-white shadow-md border rounded-lg p-6">
                        <div class="text-center">
                            <h3 class="text-gray-700 font-medium">Total Pendapatan Bulan Ini</h3>
                            <p class="text-2xl font-bold text-gray-800">Rp 45,000,000</p>
                        </div>
                    </div>
                    <div class="bg-white shadow-md border rounded-lg p-6">
                        <div class="text-center">
                            <h3 class="text-gray-700 font-medium">Pengeluaran Bulan Ini</h3>
                            <p class="text-2xl font-bold text-gray-800">Rp 15,000,000</p>
                        </div>
                    </div>
                    <div class="bg-white shadow-md border rounded-lg p-6">
                        <div class="text-center">
                            <h3 class="text-gray-700 font-medium">Laba Kotor</h3>
                            <p class="text-2xl font-bold text-gray-800">Rp 30,000,000</p>
                        </div>
                    </div>
                    <div class="bg-white shadow-md border rounded-lg p-6">
                        <div class="text-center">
                            <h3 class="text-gray-700 font-medium">Total Transaksi</h3>
                            <p class="text-2xl font-bold text-gray-800">150 Transaksi</p>
                        </div>
                    </div>
                </div> -->

                <!-- Transactions Table -->
                <div class="mt-8">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4">Transaksi Terbaru</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white rounded-lg shadow-md overflow-hidden">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="py-2 px-6 text-left text-sm font-medium text-gray-700">Customer</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-gray-700">Date</th>
                                    <th class="py-1 px-6 text-left text-sm font-medium text-gray-700">No. Order</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-gray-700">Service Type</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-gray-700">Item Name</th>
                                    <th class="py-3 px-6 text-center text-sm font-medium text-gray-700">QTY</th>
                                    <th class="py-3 px-6 text-center text-sm font-medium text-gray-700">Status</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-gray-700">Payment</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-gray-700">Total</th>
                                </tr>
                            </thead>
                            <tbody id="transactionTableBody">
                                <!-- Data dari API akan ditampilkan di sini -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- PHP untuk Fetch Data API -->
    <?php
    $apiUrl = 'https://ngolab.id/api/transactions';
    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    $transactions = json_decode($response, true)['data'];
    ?>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let initialBalanceValue = 0;
            let finalBalanceValue = 0;
            let selectedOutletId = null;

            const outletSelect = document.getElementById('outletSelect');
            const initialBalanceElement = document.getElementById('displayInitialBalance');
            const finalBalanceElement = document.getElementById('displayFinalBalance');
            const differenceElement = document.getElementById('displayBalanceDifference');
            const totalTransactionsElement = document.getElementById('displayTotalTransactions');
            const tableBody = document.getElementById('transactionTableBody');

            // Fungsi untuk mendapatkan nilai cookie
            function getCookie(name) {
                const cookieString = `; ${document.cookie}`;
                const parts = cookieString.split(`; ${name}=`);
                if (parts.length === 2) {
                    return parts.pop().split(';').shift();
                }
                return null;
            }

            // Fungsi format currency
            function formatCurrency(amount) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(amount);
            }

            // Fungsi untuk memperbarui tampilan saldo
            function updateBalanceDisplay() {
                initialBalanceElement.textContent = formatCurrency(initialBalanceValue);
                finalBalanceElement.textContent = formatCurrency(finalBalanceValue);

                const difference = finalBalanceValue - initialBalanceValue;
                differenceElement.textContent = formatCurrency(difference);
                differenceElement.className = `text-2xl font-bold ${difference >= 0 ? 'text-green-600' : 'text-red-600'}`;
            }

            // Fungsi untuk memuat saldo hari ini berdasarkan outlet
            async function loadTodayBalances() {
                try {
                    const response = await fetch(`https://ngolab.id/api/balances/today?outlet_id=${selectedOutletId}`, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'Authorization': `${getCookie('auth_token')}`
                        }
                    });

                    if (!response.ok) {
                        alert('Gagal memuat data saldo hari ini');
                    }

                    const data = await response.json();
                    const balanceData = data.data[0];
                    if (balanceData) {
                        initialBalanceValue = balanceData.balance_open;
                        finalBalanceValue = balanceData.balance_close;
                        updateBalanceDisplay();
                    } else {
                        alert('Tidak ada data saldo untuk outlet ini.');
                    }
                } catch (error) {
                    console.error('Terjadi error saat memuat saldo hari ini:', error);
                    alert('Gagal memuat saldo hari ini. Silakan coba lagi.');
                }
            }

            // Fungsi untuk memuat total transaksi hari ini berdasarkan outlet
            async function loadTodayTransactions() {
                try {
                    const response = await fetch(`https://ngolab.id/api/outlet/transactions/sales/day?outlet_id=${selectedOutletId}`, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'Authorization': `${getCookie('auth_token')}`
                        }
                    });

                    if (!response.ok) {
                        throw new Error('Gagal memuat data transaksi hari ini');
                    }

                    const data = await response.json();
                    const transactionData = data.data;
                    if (transactionData) {
                        totalTransactionsElement.textContent = `${transactionData.total_quantity} pcs`;
                    } else {
                        alert('Tidak ada data transaksi untuk outlet ini.');
                    }
                } catch (error) {
                    console.error('Terjadi error saat memuat transaksi hari ini:', error);
                    alert('Gagal memuat transaksi hari ini. Silakan coba lagi.');
                }
            }

            // Fungsi untuk memuat list transaksi berdasarkan outlet
            async function loadTransactionList() {
                try {
                    const response = await fetch(`https://ngolab.id/api/transactions?outlet_id=${selectedOutletId}`, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'Authorization': `${getCookie('auth_token')}`
                        }
                    });

                    if (!response.ok) {
                        throw new Error('Gagal memuat list transaksi');
                    }

                    const data = await response.json();
                    const transactions = data.data;
                    console.log('List Transaksi:', transactions);

                    // Bersihkan tabel sebelum mengisi data baru
                    tableBody.innerHTML = '';

                    // Iterasi setiap transaksi dan tambahkan ke tabel
                    transactions.forEach(transaction => {
                        const row = document.createElement('tr');
                        row.classList.add('hover:bg-gray-50');

                        const dateTime = new Date(transaction.created_at);
                        const formattedDate = dateTime.toLocaleDateString('id-ID');
                        const formattedTime = dateTime.toLocaleTimeString('id-ID').replace(/\./g, ':');

                        const itemDetails = transaction.details
                            ?.map(detail => `<li>${detail?.outlet_product?.product?.name || 'N/A'}</li>`)
                            .join('') || '<li>N/A</li>';

                        const itemQuantities = transaction.details
                            ?.map(detail => `<li>${detail?.quantity || 'N/A'}</li>`)
                            .join('') || '<li>N/A</li>';

                        row.innerHTML = `
                        <td class="py-3 px-1 text-sm text-red-600">${transaction.customer.name}</td>
                        <td class="px-6 py-4 text-sm">${formattedDate} ${formattedTime}</td>
                        <td class="py-3 px-6 text-sm text-gray-700">${transaction.id}</td>
                        <td class="py-3 px-6 text-sm text-gray-700">${transaction.service_type === "dine_in" ? "Dine In" : "Take Away"}</td>
                        <td class="px-1 py-1 text-sm"><ul>${itemDetails}</ul></td>
                        <td class="px-6 py-4 text-sm"><ul>${itemQuantities}</ul></td>
                        <td class="py-1 px-2 text-center">
                            <span class="bg-${transaction.status === 'pending' ? 'yellow' : 'green'}-200 text-${transaction.status === 'pending' ? 'yellow' : 'green'}-800 px-3 py-1 rounded-full text-xs font-medium">
                                ${transaction.status}
                            </span>
                        </td>
                        <td class="py-3 px-6 text-sm text-gray-700">${transaction.payment_method || 'N/A'}</td>
                        <td class="py-3 px-6 text-sm text-gray-700">Rp ${transaction.final_price.toLocaleString('id-ID')}</td>
                    `;
                        tableBody.prepend(row);
                    });
                } catch (error) {
                    console.error('Terjadi error saat memuat list transaksi:', error);
                    alert('Gagal memuat list transaksi. Silakan coba lagi.');
                }
            }

            // Fungsi untuk memuat semua data berdasarkan outlet
            function loadDataByOutlet() {
                if (!selectedOutletId) {
                    alert('Pilih outlet terlebih dahulu.');
                    return;
                }

                loadTodayBalances();
                loadTodayTransactions();
                loadTransactionList();
            }

            // Event listener untuk memilih outlet
            outletSelect.addEventListener('change', function () {
                selectedOutletId = outletSelect.value;
                loadDataByOutlet();
            });

            // Fungsi untuk memuat daftar outlet
            async function loadOutlets() {
                try {
                    const response = await fetch('https://ngolab.id/api/outlets', {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'Authorization': `${getCookie('auth_token')}`
                        }
                    });

                    if (!response.ok) {
                        throw new Error('Gagal memuat data outlet');
                    }

                    const data = await response.json();
                    data.data.forEach(outlet => {
                        const option = document.createElement('option');
                        option.value = outlet.id;
                        option.textContent = `${outlet.name}`;
                        outletSelect.appendChild(option);
                    });
                } catch (error) {
                    console.error('Terjadi error saat memuat data outlet:', error);
                    alert('Gagal memuat outlet. Silakan coba lagi.');
                }
            }

            // Panggil fungsi untuk memuat daftar outlet saat halaman pertama kali dimuat
            loadOutlets();
        });

        document.addEventListener('DOMContentLoaded', function () {
            let initialBalanceValue = 0;
            let selectedOutletId = null;

            const outletSelect = document.getElementById('outletSelect');
            const initialBalanceElement = document.getElementById('displayInitialBalance');
            const initialBalanceForm = document.getElementById('initialBalanceForm');
            const initialBalanceInput = document.getElementById('initialBalance');

            // Fungsi untuk mendapatkan nilai cookie
            function getCookie(name) {
                const cookieString = `; ${document.cookie}`;
                const parts = cookieString.split(`; ${name}=`);
                if (parts.length === 2) {
                    return parts.pop().split(';').shift();
                }
                return null;
            }

            // Fungsi format currency
            function formatCurrency(amount) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(amount);
            }

            // Fungsi untuk memperbarui tampilan saldo awal
            function updateInitialBalanceDisplay(balance) {
                initialBalanceElement.textContent = formatCurrency(balance);
            }

            // Fungsi untuk mengirim saldo awal ke API
            async function submitInitialBalance() {
                if (!selectedOutletId) {
                    alert('Silakan pilih outlet terlebih dahulu.');
                    return;
                }

                const balanceOpen = parseInt(initialBalanceInput.value, 10);
                if (isNaN(balanceOpen) || balanceOpen <= 0) {
                    alert('Masukkan saldo awal yang valid.');
                    return;
                }

                try {
                    const response = await fetch('https://ngolab.id/api/balances/open', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Authorization': `${getCookie('auth_token')}`
                        },
                        body: JSON.stringify({
                            outlet_id: selectedOutletId,
                            balance_open: balanceOpen
                        })
                    });

                    if (!response.ok) {
                        throw new Error('Gagal menyimpan saldo awal. Silakan coba lagi.');
                    }

                    const data = await response.json();
                    if (data.success) {
                        initialBalanceValue = balanceOpen;
                        updateInitialBalanceDisplay(initialBalanceValue);
                        alert('Saldo awal berhasil disimpan.');
                    } else {
                        alert('Gagal menyimpan saldo awal.');
                    }
                } catch (error) {
                    console.error('Error submitting initial balance:', error);
                    alert('Terjadi kesalahan saat menyimpan saldo awal.');
                }
            }

            // Event listener untuk form saldo awal
            initialBalanceForm.addEventListener('submit', function (event) {
                event.preventDefault();
                submitInitialBalance();
            });

            // Event listener untuk memilih outlet
            outletSelect.addEventListener('change', function () {
                selectedOutletId = outletSelect.value;
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            let initialBalanceValue = 0;
            let finalBalanceValue = 0;
            let selectedOutletId = null;

            const outletSelect = document.getElementById('outletSelect');
            const initialBalanceElement = document.getElementById('displayInitialBalance');
            const finalBalanceElement = document.getElementById('displayFinalBalance');
            const finalBalanceForm = document.getElementById('finalBalanceForm');
            const finalBalanceInput = document.getElementById('finalBalance');

            // Fungsi untuk mendapatkan nilai cookie
            function getCookie(name) {
                const cookieString = `; ${document.cookie}`;
                const parts = cookieString.split(`; ${name}=`);
                if (parts.length === 2) {
                    return parts.pop().split(';').shift();
                }
                return null;
            }

            // Fungsi format currency
            function formatCurrency(amount) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(amount);
            }

            // Fungsi untuk memperbarui tampilan saldo akhir
            function updateFinalBalanceDisplay(balance) {
                finalBalanceElement.textContent = formatCurrency(balance);
            }

            // Fungsi untuk mengirim saldo akhir ke API
            async function submitFinalBalance() {
                if (!selectedOutletId) {
                    alert('Silakan pilih outlet terlebih dahulu.');
                    return;
                }

                const balanceClose = parseInt(finalBalanceInput.value, 10);
                if (isNaN(balanceClose) || balanceClose <= 0) {
                    alert('Masukkan saldo akhir yang valid.');
                    return;
                }

                try {
                    const response = await fetch('https://ngolab.id/api/balances/close', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Authorization': `${getCookie('auth_token')}`
                        },
                        body: JSON.stringify({
                            outlet_id: selectedOutletId,
                            balance_close: balanceClose
                        })
                    });

                    if (!response.ok) {
                        throw new Error('Gagal menyimpan saldo akhir. Silakan coba lagi.');
                    }

                    const data = await response.json();
                    if (data.success) {
                        finalBalanceValue = balanceClose;
                        updateFinalBalanceDisplay(finalBalanceValue);
                        alert('Saldo akhir berhasil disimpan.');
                    } else {
                        alert('Gagal menyimpan saldo akhir.');
                    }
                } catch (error) {
                    console.error('Error submitting final balance:', error);
                    alert('Terjadi kesalahan saat menyimpan saldo akhir.');
                }
            }

            // Event listener untuk form saldo akhir
            finalBalanceForm.addEventListener('submit', function (event) {
                event.preventDefault();
                submitFinalBalance();
            });

            // Event listener untuk memilih outlet
            outletSelect.addEventListener('change', function () {
                selectedOutletId = outletSelect.value;
            });
        });



        document.addEventListener('DOMContentLoaded', function () {
            const profileButton = document.getElementById('profileButton');
            const profileDropdown = document.getElementById('profileDropdown');
            const logoutButton = document.getElementById('logoutButton');
            const userNameDisplay = document.getElementById('userNameDisplay');

            // Fungsi untuk mendapatkan nilai cookie
            function getCookie(name) {
                const cookieString = `; ${document.cookie}`;
                const parts = cookieString.split(`; ${name}=`);
                if (parts.length === 2) {
                    return parts.pop().split(';').shift();
                }
                return null;
            }

            // Fungsi untuk memuat nama user dari API login
            async function loadUserName() {
                const authToken = getCookie('auth_token');
                if (!authToken) {
                    userNameDisplay.textContent = 'Tidak ada pengguna yang login';
                    return;
                }

                try {
                    const response = await fetch('https://ngolab.id/api/users', {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'Authorization': `${getCookie('auth_token')}`
                        }
                    });

                    if (!response.ok) {
                        throw new Error('Gagal memuat data pengguna.');
                    }

                    const data = await response.json();
                    console.log('Data pengguna:', data); // Debugging

                    // Ambil nama dari data user
                    const currentUser = data.data;

                    if (currentUser) {
                        userNameDisplay.textContent = currentUser.name;
                    } else {
                        userNameDisplay.textContent = 'Pengguna tidak ditemukan';
                    }
                } catch (error) {
                    console.error('Terjadi kesalahan saat memuat data pengguna:', error);
                    userNameDisplay.textContent = 'Kesalahan memuat data';
                }
            }

            // Fungsi logout dengan API
            async function handleLogout() {
                const authToken = getCookie('auth_token');
                if (!authToken) {
                    alert('Anda tidak memiliki token otentikasi.');
                    return;
                }

                const confirmation = confirm('Apakah Anda yakin ingin logout?');
                if (!confirmation) return;

                try {
                    const response = await fetch('https://ngolab.id/api/users/logout', {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'Authorization': `${getCookie('auth_token')}`
                        }
                    });

                    if (!response.ok) {
                        throw new Error('Gagal logout. Silakan coba lagi.');
                    }

                    // Hapus cookie auth_token setelah logout berhasil
                    document.cookie = 'auth_token=; path=/; expires=Thu, 01 Jan 1970 00:00:00 UTC;';

                    alert('Logout berhasil.');
                    window.location.href = '../logic/login.php'; // Arahkan pengguna kembali ke halaman login
                } catch (error) {
                    console.error('Terjadi error saat logout:', error);
                    alert('Terjadi kesalahan saat logout.');
                }
            }

            // Event listener untuk tombol logout
            logoutButton.addEventListener('click', handleLogout);

            // Event listener untuk toggle dropdown
            profileButton.addEventListener('click', function () {
                profileDropdown.classList.toggle('hidden');
            });

            // Menutup dropdown jika klik di luar
            document.addEventListener('click', function (event) {
                if (!profileButton.contains(event.target) && !profileDropdown.contains(event.target)) {
                    profileDropdown.classList.add('hidden');
                }
            });

            // Panggil fungsi untuk memuat nama user
            loadUserName();


            const searchInput = document.getElementById('search');
            const tableBody = document.getElementById('transactionTableBody');
            let transactions = []; // Menyimpan data transaksi untuk filtering

            // Fungsi untuk menampilkan data transaksi
            function renderTransactions(filteredTransactions) {
                tableBody.innerHTML = '';

                filteredTransactions.forEach(transaction => {
                    const dateTime = new Date(transaction.created_at);
                    const formattedDate = dateTime.toLocaleDateString('id-ID');
                    const formattedTime = dateTime.toLocaleTimeString('id-ID').replace(/\./g, ':');

                    const itemDetails = transaction.details
                        ?.map(detail => `<li>${detail?.outlet_product?.product?.name || 'N/A'}</li>`)
                        .join('') || '<li>N/A</li>';

                    const itemQuantities = transaction.details
                        ?.map(detail => `<li>${detail?.quantity || 'N/A'}</li>`)
                        .join('') || '<li>N/A</li>';

                    const row = document.createElement('tr');
                    row.innerHTML = `
            <td class="py-3 px-6 text-sm text-gray-700">${transaction.customer.name || 'N/A'}</td>
            <td class="py-3 px-6 text-sm text-gray-700">${formattedDate} ${formattedTime}</td>
            <td class="py-3 px-6 text-sm text-gray-700">${transaction.id}</td>
            <td class="py-3 px-6 text-sm text-gray-700">${transaction.service_type === 'dine_in' ? 'Dine In' : 'Take Away'}</td>
            <td class="py-3 px-6 text-sm text-gray-700"><ul>${itemDetails}</ul></td>
            <td class="py-3 px-6 text-sm text-center"><ul>${itemQuantities}</ul></td>
            <td class="py-3 px-6 text-sm text-center">
                <span class="px-2 py-1 rounded-full text-xs font-medium bg-yellow-200 text-yellow-800">
                    ${transaction.status}
                </span>
            </td>
            <td class="py-3 px-6 text-sm text-gray-700">${transaction.payment_method || 'N/A'}</td>
            <td class="py-3 px-6 text-sm text-gray-700">Rp ${transaction.final_price.toLocaleString('id-ID')}</td>
        `;

                    tableBody.appendChild(row);
                });
            }


            // Fungsi untuk mendapatkan class status
            function getStatusClass(status) {
                switch (status) {
                    case 'pending': return 'bg-yellow-200 text-yellow-800';
                    case 'process': return 'bg-blue-200 text-blue-800';
                    case 'success': return 'bg-green-200 text-green-800';
                    case 'cancel': return 'bg-red-200 text-red-800';
                    default: return 'bg-gray-200 text-gray-800';
                }
            }

            // Fungsi untuk mencari transaksi berdasarkan input nama customer
            function filterTransactions() {
                const searchValue = searchInput.value.toLowerCase();

                // Filter transaksi berdasarkan nama customer
                const filteredTransactions = transactions.filter(transaction => {
                    return transaction.customer?.name?.toLowerCase().includes(searchValue);
                });

                renderTransactions(filteredTransactions);
            }


            // Event listener untuk input search
            searchInput.addEventListener('input', filterTransactions);

            // Fungsi untuk memuat data transaksi dari API
            async function loadTransactions() {
                try {
                    console.log('Loading transactions...'); // Log awal
                    const response = await fetch('https://ngolab.id/api/transactions', {
                        headers: {
                            'Authorization': `${getCookie('auth_token')}`
                        }
                    });
                    console.log('Transactions:', transactions);



                    if (!response.ok) {
                        console.error('API error:', response.statusText); // Log jika API gagal
                        return;
                    }

                    const data = await response.json();
                    console.log('API Response:', data); // Log data API

                    if (data.success) {
                        transactions = data.data;
                        console.log('Transactions:', transactions); // Log transaksi yang diterima
                        renderTransactions(transactions);
                    } else {
                        alert('Gagal memuat data transaksi.');
                    }
                } catch (error) {
                    console.error('Error fetching transactions:', error);
                    alert('Terjadi kesalahan saat memuat data transaksi.');
                }
            }

            // Fungsi untuk mendapatkan nilai cookie
            function getCookie(name) {
                const cookieString = `; ${document.cookie}`;
                const parts = cookieString.split(`; ${name}=`);
                if (parts.length === 2) {
                    return parts.pop().split(';').shift();
                }
                return null;
            }

            // Panggil fungsi untuk memuat data transaksi saat halaman pertama kali dimuat
            loadTransactions();
        });
    </script>

</body>

</html>