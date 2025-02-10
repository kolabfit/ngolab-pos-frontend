<?php
require_once('../logic/loginvalidation.php');
require_once('../logic/slotvalidation.php');
$user = Validation::validateLoginCashier($_COOKIE['auth_token'] ?? null, '../logic/login.php');
Slotvalidation::isnotfillslot($_COOKIE['auth_token'] ?? null, );
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Ko+Lab - List Transaksi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-white border-b sticky top-0 z-50 w-full">
        <div class="flex items-center justify-between h-14 px-4">
            <div class="flex items-center space-x-8">
                <img src="media/All_Logo_KoLab.png" alt="Ko+Lab Logo" class="h-12" />
                <div class="relative">
                    <input type="text" placeholder="Search menu..." id="search"
                        class="w-80 px-4 py-2 pl-10 rounded-lg bg-gray-100 focus:outline-none focus:ring-2 focus:ring-yellow-400" />
                    <div class="absolute left-3 top-2.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M11 4a7 7 0 100 14 7 7 0 000-14zM21 21l-4.35-4.35" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- User Profile Section -->
            <div class="flex items-center space-x-4">
                <!-- Nama User ditampilkan di sini -->
                <span id="userNameDisplay"
                    class="text-gray-700 font-medium hidden md:block"><?= htmlspecialchars($user['data']['name']) ?></span>

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
                            class="block w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100 rounded-t-lg">
                            Logout
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar & Main Content -->
    <div class="flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-md h-screen sticky top-0 self-start">
            <nav class="p-6 space-y-4">
                <a href="index.php"
                    class="block py-2 px-4 rounded-lg font-medium text-gray-600 hover:bg-gray-100">Beranda</a>
                <a href="transaksi.php"
                    class="block py-2 px-4 rounded-lg font-medium text-gray-600 hover:bg-gray-100">Transaksi</a>
                <a href="listtransaksi.php"
                    class="block py-2 px-4 rounded-lg font-medium bg-gradient-to-r from-orange-400 to-yellow-400 text-white">List
                    Transaksi</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            <div class="bg-white shadow-md rounded-lg p-6">
                <h1 class="text-2xl font-semibold mb-4">List Transaksi</h1>
                <p class="text-gray-500 mb-6">Transaksi yang terakhir tercatat oleh sistem</p>

                <!-- Filter Buttons -->
                <div class="flex space-x-4 mb-6">
                    <input type="date" id="filterDate" class="px-4 py-2 border rounded-lg"
                        onchange="filterTransactions()" />

                    <!-- Dropdown Filter Status -->
                    <div class="relative">
                        <button id="dropdownButton" onclick="toggleDropdownStatus()"
                            class="px-4 py-2 border rounded-lg flex items-center bg-white">
                            <span id="selectedStatus">All Status</span>
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </button>
                        <div id="dropdownMenu" class="absolute mt-2 w-40 bg-white border rounded-lg shadow-lg hidden">
                            <ul class="py-2">
                                <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer" onclick="selectStatus('All')">All
                                </li>
                                <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer"
                                    onclick="selectStatus('pending')">Pending</li>
                                <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer"
                                    onclick="selectStatus('process')">Process</li>
                                <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer" onclick="selectStatus('success')">
                                    Success</li>
                                <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer"
                                    onclick="selectStatus('cancel')">Cancel</li>
                            </ul>
                        </div>
                    </div>
                    <!-- Dropdown Pilihan Outlet -->
                    <div class="relative">
                        <button id="outletDropdownButton" class="px-4 py-2 border rounded-lg flex items-center bg-white"
                            onclick="toggleDropdown()">
                            <span id="selectedOutlet">Pilih Outlet</span>
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </button>
                        <div id="outletDropdownMenu"
                            class="absolute mt-2 w-60 bg-white border rounded-lg shadow-lg hidden"></div>
                    </div>
                </div>

                <!-- Table -->
                <div class="bg-white rounded-lg shadow overflow-x-auto">
                    <table class="w-full" id="transactionsTable">
                        <thead class="bg-gradient-to-r from-orange-400 to-yellow-400 text-white">
                            <tr class="border-b">
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase">Customer Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase">No. Order</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase">Service Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase">Item Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase">QTY</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase">Payment</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase">Outlet</th>
                            </tr>
                        </thead>
                        <tbody id="transactionsBody" class="divide-y">
                            <!-- Data rows will be inserted here by JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script>
        let currentStatus = 'all';
        let currentOutletId = '';
        let outletMap = {};

        async function fetchOutlets() {
            try {
                const response = await fetch('http://127.0.0.1:8000/api/outlets');
                const data = await response.json();

                if (data.success) {
                    // Simpan data outlet dalam bentuk map agar mudah diakses
                    outletMap = {};
                    data.data.forEach(outlet => {
                        outletMap[outlet.id] = outlet;
                    });

                    renderOutletDropdown(data.data);
                } else {
                    console.error('Failed to fetch outlets:', data.message);
                }
            } catch (error) {
                console.error('Error fetching outlets:', error);
            }
        }

        function renderOutletDropdown(outlets) {
            const outletMenu = document.getElementById('outletDropdownMenu');
            outletMenu.innerHTML = '';
            console.log(outlets);

            outlets.forEach(outlet => {
                const listItem = document.createElement('li');
                listItem.className = 'px-4 py-2 hover:bg-gray-100 cursor-pointer outlet-item';
                listItem.setAttribute('data-outlet-id', outlet.id);
                listItem.textContent = `${outlet.name}`;

                listItem.addEventListener('click', () => selectOutlet(outlet.id, `${outlet.name}`));

                outletMenu.prepend(listItem);
            });
        }

        async function fetchTransactions() {
            const apiUrl = `http://127.0.0.1:8000/api/transactions${currentOutletId ? `?outlet_id=${currentOutletId}` : ''}`;
            console.log(`Fetching data from: ${apiUrl}`);

            try {
                const response = await fetch(apiUrl);
                const data = await response.json();

                if (data.success) {
                    // Urutkan data berdasarkan created_at (terbaru di atas)
                    const sortedData = data.data.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
                    renderTransactions(sortedData);
                } else {
                    console.error('Failed to fetch transactions:', data.message);
                }
            } catch (error) {
                console.error('Error fetching transactions:', error);
            }
        }


        function getStatusClass(status) {
            switch (status) {
                case 'pending':
                    return 'bg-yellow-100 text-yellow-600';
                case 'process':
                    return 'bg-blue-100 text-blue-600';
                case 'done':
                    return 'bg-green-100 text-green-600';
                case 'cancelled':
                    return 'bg-red-100 text-red-600';
                default:
                    return 'bg-gray-100 text-gray-600';
            }
        }

        function selectStatus(status) {
            // Update currentStatus ke status yang dipilih
            currentStatus = status.toLowerCase();

            // Update tampilan teks pada tombol dropdown
            const statusText = status.charAt(0).toUpperCase() + status.slice(1);
            document.getElementById('selectedStatus').textContent = status === 'all' ? 'All Status' : statusText;

            // Sembunyikan dropdown setelah memilih status
            toggleDropdownStatus();

            // Jalankan filter berdasarkan status
            filterTransactions();
        }


        function renderTransactions(transactions) {
            const tbody = document.getElementById('transactionsBody');
            tbody.innerHTML = '';
            transactions.forEach((transaction, index) => {
                const statusClass = getStatusClass(transaction.status);
                const row = document.createElement('tr');
                row.classList.add('hover:bg-gray-50');

                // Format tanggal dan waktu
                const dateTime = new Date(transaction.created_at);
                const formattedDate = dateTime.toLocaleDateString('id-ID');
                const formattedTime = dateTime.toLocaleTimeString('id-ID').replace(/\./g, ':');

                // Cari informasi outlet dari details
                const outletId = transaction.details[0]?.outlet_product?.outlet_id;
                const outletInfo = outletId && outletMap[outletId] ? `${outletMap[outletId].name} - ${outletMap[outletId].address}` : 'N/A';

                row.innerHTML = `
                <td class="px-6 py-4 text-sm">${transaction.customer.name || 'N/A'}</td>
                <td class="px-6 py-4 text-sm" data-date="${dateTime.toISOString()}">${formattedDate} ${formattedTime}</td>
                <td class="px-6 py-4 text-sm">${index + 1}</td>
                <td class="px-6 py-4 text-sm">${transaction.service_type === "dine_in" ? "Dine In" : "Take Away"}</td>
                <td class="px-6 py-4 text-sm">
                    <ul>
                        ${transaction.details?.map(detail => `<li>${detail?.outlet_product?.product?.name || 'N/A'}</li>`).join('') || '<li>N/A</li>'}
                    </ul>
                </td>
                <td class="px-6 py-4 text-sm">
                    <ul>
                        ${transaction.details?.map(detail => `<li>${detail?.quantity || 'N/A'}</li>`).join('') || '<li>N/A</li>'}
                    </ul>
                </td>
                <td class="px-6 py-4 text-sm">
                    <span class="px-3 py-1 rounded-full text-xs font-semibold ${statusClass}">
                        ${transaction.status.charAt(0).toUpperCase() + transaction.status.slice(1)}
                    </span>
                </td>
                <td class="px-6 py-4 text-sm">${transaction.payment_method || 'Cash'}</td>
                <td class="px-6 py-4 text-sm">Rp ${transaction.final_price.toLocaleString()}</td>
                <td class="px-6 py-4 text-sm">${outletInfo}</td>
            `;
                tbody.appendChild(row);
            });
        }

        function toggleDropdown() {
            const dropdownMenu = document.getElementById('outletDropdownMenu');
            dropdownMenu.classList.remove('hidden');

        }

        function toggleDropdownStatus() {
            const dropdownMenu = document.getElementById('dropdownMenu');
            dropdownMenu.classList.toggle('hidden');
        }


        function selectOutlet(outletId, outletName) {
            currentOutletId = outletId;
            document.getElementById('selectedOutlet').textContent = outletName;
            toggleDropdown('outletDropdownMenu');
            fetchTransactions();
        }

        function filterTransactions() {
            const searchInputElement = document.getElementById('search');
            const filterDateElement = document.getElementById('filterDate');

            const searchValue = searchInputElement ? searchInputElement.value.toLowerCase() : '';
            const filterDateValue = filterDateElement ? filterDateElement.value : '';

            const rows = document.querySelectorAll('#transactionsBody tr');

            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                const customerName = cells[0]?.textContent?.toLowerCase() || '';
                const dateText = cells[1]?.getAttribute('data-date') || '';
                const status = cells[6]?.textContent?.trim().toLowerCase() || '';

                const matchesSearch = !searchValue || customerName.includes(searchValue);
                const matchesDate = !filterDateValue || dateText.startsWith(filterDateValue);
                const matchesStatus = currentStatus === 'all' || status === currentStatus;

                row.style.display = matchesSearch && matchesDate && matchesStatus ? '' : 'none';
            });
        }

        window.onload = function () {
            const filterDateElement = document.getElementById('filterDate');
            const searchInputElement = document.getElementById('search');
            const outletDropdownButton = document.getElementById('outletDropdownButton');

            if (filterDateElement) {
                filterDateElement.addEventListener('input', filterTransactions);
            } else {
                console.warn("Element with ID 'filterDate' not found.");
            }

            if (searchInputElement) {
                searchInputElement.addEventListener('input', filterTransactions);
            } else {
                console.warn("Element with ID 'search' not found.");
            }

            if (outletDropdownButton) {
                outletDropdownButton.addEventListener('click', () => toggleDropdown('outletDropdownMenu'));
            } else {
                console.warn("Element with ID 'outletDropdownButton' not found.");
            }

            fetchOutlets();
            fetchTransactions();
        };


        window.onclick = function (event) {
            const dropdown = document.getElementById('outletDropdownMenu');
            if (!event.target.matches('#outletDropdownButton') && !dropdown.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        };

        document.addEventListener('DOMContentLoaded', function () {
            const profileButton = document.getElementById('profileButton');
            const profileDropdown = document.getElementById('profileDropdown');
            const logoutButton = document.getElementById('logoutButton');

            // Fungsi logout
            async function handleLogout() {
                const authToken = getCookie('auth_token');
                if (!authToken) {
                    alert('Anda tidak memiliki token otentikasi.');
                    return;
                }

                const confirmation = confirm('Apakah Anda yakin ingin logout?');
                if (!confirmation) return;

                try {
                    const response = await fetch('http://127.0.0.1:8000/api/users/logout', {
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
                    console.error('Terjadi kesalahan saat logout:', error);
                    alert('Terjadi kesalahan saat logout.');
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
        });
    </script>

</body>

</html>