<?php
require_once('../logic/loginvalidation.php');
$user = Validation::validateLoginOperational($_COOKIE['auth_token'] ?? null, '../login.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Order List</title>
</head>

<body class="bg-white">
  <!-- Navbar -->
  <nav class="bg-white border-b sticky top-0 z-50 w-full">
    <div class="flex items-center justify-between h-14 px-4">
      <!-- Brand & Search -->
      <div class="flex items-center space-x-8">
        <!-- <img src="/media/All_Logo_KoLab.png" alt="Ko+Lab Logo" class="h-12" /> -->
        <img src="/cashier/media/All_Logo_KoLab.png" alt="logo kolab" class="h-12">
      </div>

      <!-- Profile section -->
      <div class="flex items-center space-x-4">
        <!-- User Name -->
        <span id="userNameDisplay"
          class="text-gray-700 font-medium hidden md:block"><?= htmlspecialchars($user['data']['name']) ?></span>

        <!-- Profile button and dropdown -->
        <div class="relative">
          <!-- Button -->
          <button id="profileButton"
            class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700" fill="none" viewBox="0 0 24 24"
              stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M12 12c2.28 0 4-1.72 4-4s-1.72-4-4-4-4 1.72-4 4 1.72 4 4 4zm0 2c-4.42 0-8 1.79-8 4v2h16v-2c0-2.21-3.58-4-8-4z" />
            </svg>
          </button>

          <!-- Dropdown -->
          <div id="profileDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white border rounded-lg shadow-md">
            <button id="logoutButton"
              class="block w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100 rounded-t-lg">Logout</button>
          </div>
        </div>
      </div>
    </div>
  </nav>

  <div class="max-w-7xl mx-auto relative">
    <div class="mt-6 w-full max-w-7xl bg-white z-10">
      <table class="w-full table-fixed border-b border-gray-100">
        <colgroup>
          <col class="w-1/6" />
          <col class="w-1/6" />
          <col class="w-1/4" />
          <col class="w-1/6" /> <!-- Tambahan kolom untuk Quantity -->
          <col class="w-1/4" />
          <col class="w-1/6" />
        </colgroup>
        <thead class="bg-white shadow">
          <tr>
            <th class="px-6 py-3 text-sm font-medium text-gray-500 text-center">Order</th>
            <th class="px-6 py-3 text-sm font-medium text-gray-500 text-center">Type</th>
            <th class="px-6 py-3 text-sm font-medium text-gray-500 text-left">Pesanan</th>
            <th class="px-6 py-3 text-sm font-medium text-gray-500 text-center">Quantity</th> <!-- Tambahan kolom Quantity -->
            <th class="px-6 py-3 text-sm font-medium text-gray-500 text-left">Keterangan</th>
            <th class="px-6 py-3 text-sm font-medium text-gray-500 text-left">Status</th>
          </tr>
        </thead>
      </table>
    </div>

    <div class="px-4">
      <div class="space-y-4 pt-14" id="ordersContainer"></div>
    </div>
  </div>


  <script>
    let orders = [];
    const statusOptions = ['Pending', 'Process', 'Success', 'Cancel'];
    const categoryColors = {
      pending: 'bg-yellow-100 text-yellow-800',
      process: 'bg-blue-100 text-blue-800',
      success: 'bg-green-100 text-green-800',
      cancel: 'bg-red-100 text-red-800',
    };

    const typeColors = {
      'dine-in': 'bg-blue-100 text-blue-800',
      'takeaway': 'bg-orange-100 text-orange-800',
    };

    const typeLabels = {
      'dine-in': 'Dine In',
      'takeaway': 'Takeaway'
    };

    document.addEventListener('DOMContentLoaded', function() {
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
      profileButton.addEventListener('click', function() {
        profileDropdown.classList.toggle('hidden');
      });

      // Menutup dropdown jika klik di luar
      document.addEventListener('click', function(event) {
        if (!profileButton.contains(event.target) && !profileDropdown.contains(event.target)) {
          profileDropdown.classList.add('hidden');
        }
      });
    });

    async function fetchOrders() {
      try {
        const response = await fetch('https://ngolab.id/api/transactions');

        if (!response.ok) {
          throw new Error(`Gagal mengambil data: ${response.status} ${response.statusText}`);
        }

        const data = await response.json();

        if (!data.success) {
          throw new Error('Respon API tidak berhasil.');
        }

        orders = data.data.map(transaction => ({
          time: new Date(transaction.created_at).toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit'
          }),
          date: new Date(transaction.created_at).toLocaleDateString('id-ID'),
          type: 'dine-in',
          transaction_id: transaction.id,
          items: transaction.details.map(detail => ({
            name: detail.outlet_product.product.name,
            notes: detail.notes || '-',
            status: detail.status,
            id: detail.id,
            category: detail.outlet_product.product.category_id,
            quantity: detail.quantity // Menambahkan quantity dari API
          }))
        }));

        renderOrders();
      } catch (error) {
        console.error('Terjadi kesalahan saat mengambil pesanan:', error);
        alert('Gagal memuat pesanan. Silakan coba lagi.');
      }
    }

    function getCookie(name) {
      const cookies = document.cookie.split('; ');
      for (let i = 0; i < cookies.length; i++) {
        const cookie = cookies[i].split('=');
        if (cookie[0] === name) {
          return decodeURIComponent(cookie[1]);
        }
      }
      return null;
    }

    const token = getCookie('auth_token');

    async function updateStatus(orderIndex, newStatus) {
      const token = getCookie('auth_token');
      if (!token) {
        console.error('Token tidak ditemukan, tidak dapat mengupdate status.');
        return;
      }

      const statusMapping = {
        'pending': 'pending',
        'process': 'process',
        'success': 'success',
        'cancel': 'cancel'
      };

      const formattedStatus = statusMapping[newStatus] || newStatus;

      try {
        const response = await fetch('https://ngolab.id/api/transactions/detail/status', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': token
          },
          body: JSON.stringify({
            transaction_detail_id: orderIndex,
            status: formattedStatus
          })
        });

        const textResponse = await response.text();
        const data = JSON.parse(textResponse);
        if (response.ok) {
          console.log('Berhasil update status:', data);
          // Refresh data after successful update without affecting header color
          fetchOrders();
        } else {
          console.error('Gagal update status:', data);
        }
      } catch (error) {
        console.error('Terjadi kesalahan:', error);
      }
    }


    function renderOrders() {
      const container = document.getElementById('ordersContainer');
      container.innerHTML = '';

      orders.forEach((order) => {
      const card = document.createElement('div');
      card.className = 'bg-white rounded-lg shadow-xl mb-4';

      const table = document.createElement('table');
      table.className = 'w-full table-fixed border-collapse rounded-lg';

      table.innerHTML = `
      <colgroup>
      <col class="w-1/6" />
      <col class="w-1/6" />
      <col class="w-1/4" />
      <col class="w-1/6" />
      <col class="w-1/4" />
      <col class="w-1/6" />
      </colgroup>
      `;

      const tbody = document.createElement('tbody');
      // Use order.headerStatus as returned from the database
      let headerStatus = order.headerStatus;
      console.log(headerStatus);
      let statusClass = '';
      let statusLabel = '';
      switch (headerStatus) {
      case 'success':
        statusClass = 'bg-green-100 text-green-800';
        statusLabel = 'All Done <span class="text-green-600">✔</span>';
        break;
      case 'cancel':
        statusClass = 'bg-red-100 text-red-800';
        statusLabel = 'Declined <span class="text-red-600">✖</span>';
        break;
      case 'process':
        statusClass = 'bg-blue-100 text-blue-800';
        statusLabel = 'In Progress <span class="text-blue-600">●</span>';
        break;
      case 'pending':
      default:
        statusClass = 'bg-yellow-100 text-yellow-800';
        statusLabel = 'On Hold <span class="text-yellow-600">⏳</span>';
        break;
      }

      const isSingleItem = order.items.length === 1;

      order.items.forEach((item, iIndex) => {
      const tr = document.createElement('tr');
      if (iIndex < order.items.length - 1) {
        tr.className = 'border-b border-gray-200';
      }

      if (iIndex === 0) {
        const tdOrder = document.createElement('td');
        tdOrder.className = `px-4 sm:px-6 py-6 ${isSingleItem ? 'align-middle' : 'align-top'} text-center`;
        tdOrder.rowSpan = order.items.length;
        tdOrder.innerHTML = `
        <div class="rounded-lg p-2 sm:p-4 space-y-3 flex flex-col items-center">
        <div class="text-lg font-medium text-gray-900">Order #${item.id}</div>
        <div class="text-2xl font-bold text-gray-900">${order.time}</div>
        <div class="w-full">
          <span class="inline-block w-full px-3 py-2 rounded-full text-sm font-medium ${statusClass}">
          ${statusLabel}
          </span>
        </div>
        <div class="text-sm text-gray-500">${order.date}</div>
        </div>
        `;
        tr.appendChild(tdOrder);

        const tdType = document.createElement('td');
        tdType.className = 'px-4 sm:px-6 py-4 align-middle text-center';
        tdType.rowSpan = order.items.length;
        tdType.innerHTML = `
        <div class="flex items-center justify-center h-full">
        <span class="w-full sm:w-auto px-4 py-2 rounded-md text-sm font-medium ${typeColors[order.type]}">
          ${typeLabels[order.type]}
        </span>
        </div>
        `;
        tr.appendChild(tdType);
      }

      const tdPesanan = document.createElement('td');
      tdPesanan.className = `px-4 sm:px-6 py-4 ${isSingleItem ? 'align-middle' : 'align-top'}`;
      tdPesanan.innerHTML = `
        <div class="shadow rounded-md px-4 py-2 ${categoryColors[item.category]} hover:shadow-md transition-shadow duration-200">
        ${item.name}
        </div>
      `;
      tr.appendChild(tdPesanan);

      const tdQuantity = document.createElement('td');
      tdQuantity.className = 'px-4 sm:px-6 py-4 align-middle text-center';
      tdQuantity.textContent = item.quantity;
      tr.appendChild(tdQuantity);

      const tdKeterangan = document.createElement('td');
      tdKeterangan.className = `px-4 sm:px-6 py-4 ${isSingleItem ? 'align-middle' : 'align-top'} text-sm text-gray-700`;
      tdKeterangan.innerHTML = `
        <div class="bg-white shadow rounded-md px-4 py-2 hover:shadow-md transition-shadow duration-200">
        ${item.notes}
        </div>
      `;
      tr.appendChild(tdKeterangan);

      const tdStatus = document.createElement('td');
      tdStatus.className = `px-4 sm:px-6 py-4 ${isSingleItem ? 'align-middle' : 'align-top'}`;
      const select = document.createElement('select');
      select.className = 'w-full px-3 py-2 border border-yellow-600 rounded-md bg-white shadow-sm focus:outline-none focus:ring focus:border-yellow-500';

      ['pending', 'process', 'success', 'cancel'].forEach(status => {
        const option = document.createElement('option');
        option.value = status;
        option.textContent = status.charAt(0).toUpperCase() + status.slice(1);
        if (item.status === status) {
        option.selected = true;
        }
        select.appendChild(option);
      });

      // Update individual item status asynchronously via API.
      select.addEventListener('change', async (event) => {
        await updateStatus(item.id, event.target.value);
      });

      tdStatus.appendChild(select);
      tr.appendChild(tdStatus);
      tbody.appendChild(tr);
      });

      // Long dropdown row: updating overall order status based on the value in the database.
      if (order.items.length > 0) {
      const trDropdown = document.createElement('tr');
      const tdDropdown = document.createElement('td');
      tdDropdown.className = 'px-4 sm:px-6 py-4 align-middle bg-white';
      tdDropdown.colSpan = 6;

      const selectAll = document.createElement('select');
      selectAll.className = 'w-full px-3 py-2 border border-yellow-600 rounded-md bg-white shadow-sm focus:outline-none focus:ring focus:border-yellow-500';

      ['pending', 'process', 'success', 'cancel'].forEach(status => {
        const option = document.createElement('option');
        option.value = status;
        option.textContent = status.charAt(0).toUpperCase() + status.slice(1);
        // Set default option according to the database value.
        if (headerStatus === status) {
        option.selected = true;
        }
        selectAll.appendChild(option);
      });

      selectAll.addEventListener('change', async (event) => {
        try {
        const token = getCookie('auth_token');
        if (!token) {
          alert('Token tidak ditemukan');
          return;
        }
        const newStatus = event.target.value;
        const response = await fetch('https://ngolab.id/api/transactions/status', {
          method: 'POST',
          mode: 'cors',
          headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'Authorization': token
          },
          body: JSON.stringify({
          transaction_id: order.transaction_id,
          status: newStatus
          })
        });

        if (!response.ok) {
          const errorText = await response.text();
          throw new Error('Gagal memperbarui status transaksi: ' + errorText);
        }

        const data = await response.json();
        if (data.success) {
          // Update headerStatus locally and re-render orders to change the header span's color.
          order.headerStatus = newStatus;
          renderOrders();
        } else {
          alert('Gagal memperbarui status transaksi: ' + (data.message || 'Terjadi kesalahan.'));
        }
        } catch (error) {
        console.error('Terjadi kesalahan:', error);
        alert('Terjadi kesalahan saat memperbarui status transaksi:\n' + error.message);
        }
      });

      tdDropdown.appendChild(selectAll);
      trDropdown.appendChild(tdDropdown);
      tbody.appendChild(trDropdown);
      }

      table.appendChild(tbody);
      card.appendChild(table);
      container.appendChild(card);
      });
    }


    async function fetchOrders() {
      try {
      const response = await fetch('https://ngolab.id/api/transactions');

      if (!response.ok) {
        throw new Error(`Gagal mengambil data: ${response.status} ${response.statusText}`);
      }

      const data = await response.json();

      if (!data.success) {
        throw new Error('Respon API tidak berhasil.');
      }

      // Directly use the database value for headerStatus instead of a fallback.
      orders = data.data.map(transaction => ({
        time: new Date(transaction.created_at).toLocaleTimeString('id-ID', {
        hour: '2-digit',
        minute: '2-digit'
        }),
        date: new Date(transaction.created_at).toLocaleDateString('id-ID'),
        type: 'dine-in',
        transaction_id: transaction.id,
        headerStatus: transaction.status,  // Use database value
        items: transaction.details.map(detail => ({
        name: detail.outlet_product.product.name,
        notes: detail.notes || '-',
        status: detail.status,
        id: detail.id,
        category: detail.outlet_product.product.category_id,
        quantity: detail.quantity
        }))
      }));

      renderOrders();
      } catch (error) {
      console.error('Terjadi kesalahan saat mengambil pesanan:', error);
      alert('Gagal memuat pesanan. Silakan coba lagi.');
      }
    }

    fetchOrders();

    setInterval(fetchOrders, 10000);
  </script>
</body>

</html>