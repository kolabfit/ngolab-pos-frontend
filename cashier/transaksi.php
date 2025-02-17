<?php
require_once('../logic/loginvalidation.php');
require_once('../logic/slotvalidation.php');
$user = Validation::validateLoginCashier($_COOKIE['auth_token'] ?? null, '../logic/login.php');
Slotvalidation::isnotfillslot($_COOKIE['auth_token'] ?? null, );

$token = $_COOKIE['auth_token'];

// Ambil nilai slot yang dipilih (misalnya disimpan di cookie 'selected_slot')
$selected_slot = $_COOKIE['selected_slot'] ?? null;

// Ambil data outlet dari API
$outletApiUrl = 'http://127.0.0.1:8000/api/outlets';
$outletResponse = file_get_contents($outletApiUrl);
$outlets = json_decode($outletResponse, true)['data'] ?? [];

// Ambil data produk dari API
$productApiUrl = 'http://127.0.0.1:8000/api/outlets/products';
$productResponse = file_get_contents($productApiUrl);
$outletProducts = json_decode($productResponse, true)['data'] ?? [];
?>


<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Ko+Lab Menu</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/heroicons@2.0.13/dist/heroicons.min.js"></script>
</head>

<body class="bg-white">
  <!-- Navbar -->
  <nav class="bg-white border-b sticky top-0 z-50 w-full">
    <div class="flex items-center justify-between h-14 px-4">
      <div class="flex items-center space-x-8">
        <img src="media/All_Logo_KoLab.png" alt="Ko+Lab Logo" class="h-12" />
        <div class="relative">
          <input type="text" placeholder="Search menu..." id="search"
            class="w-80 px-4 py-2 pl-10 rounded-lg bg-gray-100 focus:outline-none focus:ring-2 focus:ring-yellow-400" />
          <div class="absolute left-3 top-2.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24"
              stroke="currentColor" stroke-width="2">
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
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700" fill="none" viewBox="0 0 24 24"
              stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M12 12c2.28 0 4-1.72 4-4s-1.72-4-4-4-4 1.72-4 4 1.72 4 4 4zm0 2c-4.42 0-8 1.79-8 4v2h16v-2c0-2.21-3.58-4-8-4z" />
            </svg>
          </button>

          <!-- Dropdown -->
          <div id="profileDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white border rounded-lg shadow-md">
            <button id="logoutButton"
              class="block w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100 rounded-t-lg">
              Logout
            </button>
          </div>
        </div>
      </div>
    </div>
  </nav>



  <div class="flex h-[calc(100vh-3.5rem)]">
    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-md h-screen sticky top-0 self-start">
      <nav class="p-6 space-y-4">
        <!-- Menu Beranda -->
        <?php if ($selected_slot != "5"): ?>
          <a href="index.php" class="block py-2 px-4 mb-3 rounded-lg font-medium text-gray-600 hover:bg-gray-100">
            Beranda
          </a>
        <?php else: ?>
          <span class="hidden py-2 px-4 p-3 mb-3 rounded-lg font-medium text-gray-400 cursor-not-allowed">
            Beranda
          </span>
        <?php endif; ?>
        <!-- Menu Transaksi -->
        <a href="transaksi.php"
          class="block py-2 px-4 p-3 mb-3 rounded-lg font-medium bg-gradient-to-r from-orange-400 to-yellow-400 text-white">
          Transaksi
        </a>
        <!-- Menu List Transaksi hanya muncul jika bukan Self Service (slot‑5) -->
        <?php if ($selected_slot !== '5'): ?>
          <a href="listtransaksi.php" class="block py-2 px-4 mb-3 rounded-lg font-medium text-gray-600 hover:bg-gray-100">
            List Transaksi
          </a>
        <?php else: ?>
          <!-- Jika self service, tampilkan sebagai nonaktif -->
          <span class="hidden py-2 px-4 mb-3 rounded-lg font-medium text-gray-400 cursor-not-allowed">
            List Transaksi
          </span>
        <?php endif; ?>
      </nav>
    </aside>

    <!-- Center Content -->
    <div class="flex-1 p-4 overflow-y-auto">
      <div class="container mx-auto">
        <!-- Filter Outlet Buttons -->
        <div class="flex flex-wrap gap-4 mb-6">
          <?php foreach ($outlets as $outlet): ?>
            <button class="px-4 py-2 bg-gray-200 rounded-lg font-medium outlet-filter" data-outlet="<?= $outlet['id'] ?>">
              <?= $outlet['name'] ?>
            </button>
          <?php endforeach; ?>
        </div>

        <!-- Menu Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4" id="menu-grid">
          <?php
          foreach ($outletProducts as $outletProduct) {
            $outletId = $outletProduct['outlet_id'];
            $outletProductId = $outletProduct['outlet_product_id'];
            $product = $outletProduct['product'];


            echo "<div class='bg-white rounded-lg shadow-sm menu-item' data-outlet='$outletId'>";
            echo "<img src='{$product['image']}' alt='{$product['name']}' class='w-full h-40 object-cover rounded-t-lg' />";
            echo "<div class='p-3'>";
            echo "<h3 class='font-medium'>{$product['name']}</h3>";
            echo "<p class='text-gray-600'>Rp " . number_format($product['price'], 0, ',', '.') . "</p>";
            echo "<button class='mt-2 w-full py-2 rounded-lg text-white bg-gradient-to-r from-orange-400 to-yellow-400 hover:opacity-90 transition font-medium add-to-order' data-outlet-product-id='{$outletProductId}' data-item='{$product['name']}' data-price='{$product['price']}'>Tambah</button>";
            echo "</div>";
            echo "</div>";
          }
          ?>
        </div>
      </div>
    </div>

    <!-- Right Sidebar -->
    <div class="w-80 border-l bg-white flex flex-col">
      <div class="p-4 border-b">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-lg font-semibold">Order Summary</h2>
        </div>
        <div class="flex space-x-2">
          <button class="px-6 py-2 rounded-lg font-medium bg-gradient-to-r from-orange-400 to-yellow-400 text-white"
            data-type="dine_in">
            Dine In
          </button>
          <button class="px-6 py-2 rounded-lg bg-gray-100 hover:bg-gray-200" data-type="take_away">
            Takeaway
          </button>
        </div>
      </div>
      <div class="flex-1 overflow-y-auto p-4 space-y-4" id="order-items"></div>
      <div class="p-4 border-t">
        <form id="voucherForm">
          <!-- Field untuk input nama customer -->
          <div class="mb-3">
            <label for="customerName" class="block text-gray-800 font-medium mb-2">
              Wajib mengisi Nama Customer <span class="text-red-500">*</span>
            </label>
            <input type="text" id="customerName" name="customerName" placeholder="Masukkan nama customer..."
              class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent shadow-sm" />
          </div>

          <!-- Field untuk input voucher code -->
          <input type="text" id="voucher" name="voucher"
            class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent shadow-sm mb-3"
            placeholder="Enter voucher code" />

          <button id="apply-voucher"
            class="w-full py-2 rounded-lg bg-gradient-to-r from-orange-400 to-yellow-400 hover:opacity-90 text-white mb-4 transition">
            Konfirmasi
          </button>

          <div class="space-y-2 mb-4 border rounded-lg p-4 text-sm">
            <div class="flex justify-between">
              <span>Subtotal</span>
              <span id="subtotal">Rp0</span>
            </div>
            <div class="flex justify-between">
              <span>Discount</span>
              <span id="total_discount">Rp0</span>
            </div>
            <div class="flex justify-between font-medium border-t pt-2">
              <span>Total Payment</span>
              <span id="total">Rp0</span>
              <span id="totalAfterDiscount" class="hidden">Rp0</span>
            </div>
          </div>

          <!-- Dropdown Payment Button -->
          <div class="relative inline-block text-left mb-3">
            <button id="paymentDropdownButton" type="button"
              class="inline-flex justify-between items-center w-full px-4 py-2 bg-gradient-to-r from-orange-400 to-yellow-400 border border-transparent rounded-md shadow-sm text-white font-medium hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-yellow-400">
              <span id="selectedPayment">Cash</span>
              <svg class="ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </button>
            <!-- Dropdown Menu -->
            <div id="paymentDropdownMenu"
              class="origin-top-right absolute right-0 mt-2 w-36 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none hidden">
              <div class="py-1">
                <a href="#" data-value="tunai" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Cash</a>
                <a href="#" data-value="qris" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">QRIS</a>
              </div>
            </div>
          </div>
          <!-- Input tersembunyi untuk menyimpan nilai pilihan pembayaran -->
          <input type="hidden" id="paymentType" name="paymentType" value="tunai">

          <button id="confirm-payment"
            class="w-full py-3 rounded-lg text-white font-medium bg-gradient-to-r from-orange-400 to-yellow-400 hover:opacity-90 transition">
            Confirm Payment
          </button>
        </form>
      </div>

    </div>
  </div>

  <!-- Modal for Edit Order -->
  <div id="editOrderModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white rounded-lg w-[800px] p-6 shadow-lg">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold">Edit Pesanan</h2>
        <button id="closeEditModal" class="text-gray-500 hover:text-gray-700 text-xl">&times;</button>
      </div>
      <div class="flex space-x-6">
        <img id="editOrderImage" src="" alt="Order Item" class="w-40 h-40 object-cover rounded-lg" />
        <div class="flex-1">
          <h3 id="editOrderName" class="text-xl font-semibold"></h3>
          <p id="editOrderPrice" class="text-gray-600 text-lg"></p>
          <div class="flex items-center mt-4 space-x-4">
            <button id="decreaseQuantity"
              class="w-10 h-10 flex items-center justify-center bg-yellow-400 text-white rounded-lg text-xl font-bold hover:opacity-90">-</button>
            <span id="orderQuantity" class="text-lg font-semibold">1</span>
            <button id="increaseQuantity"
              class="w-10 h-10 flex items-center justify-center bg-yellow-400 text-white rounded-lg text-xl font-bold hover:opacity-90">+</button>
          </div>
          <textarea id="orderNote" class="w-full mt-4 p-2 border rounded-lg resize-none"
            placeholder="Catatan..."></textarea>
        </div>
      </div>
      <div class="mt-6 flex justify-end">
        <button id="confirmEditOrder"
          class="px-6 py-3 bg-gradient-to-r from-yellow-400 to-orange-400 text-white font-medium rounded-lg hover:opacity-90">
          Konfirmasi
        </button>
      </div>
    </div>
  </div>

  <!-- Modal Recap Pesanan -->
  <div id="transactionRecapModal"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden transition duration-300">
    <div class="bg-white rounded-lg w-[600px] p-6 shadow-lg transform transition duration-300">
      <!-- Bagian Header Modal -->
      <div class="flex justify-between items-center border-b pb-2 mb-4">
        <h2 class="text-2xl font-semibold">Recap Pesanan</h2>
        <button id="closeRecapModal" class="text-gray-500 hover:text-gray-700 text-xl focus:outline-none">
          &times;
        </button>
      </div>

      <!-- Konten Detail Transaksi -->
      <div id="transactionRecapContent" class="overflow-y-auto max-h-[400px] text-sm text-gray-700 space-y-4">
        <!-- 
        KONTEN RECAP AKAN DIINJEKSIKAN DARI JAVASCRIPT 
        (buildTransactionRecap()) 
      -->
      </div>

      <!-- Tombol Lanjut ke Pembayaran -->
      <div class="mt-6 flex justify-end">
        <button id="proceedPaymentBtn"
          class="px-6 py-3 rounded-lg bg-gradient-to-r from-orange-400 to-yellow-400 text-white font-medium hover:opacity-90 transition">
          Lanjut ke Pembayaran
        </button>
      </div>
    </div>
  </div>


  <!-- Modal Payment Code -->
  <div id="paymentCodeModal"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden transition duration-300">
    <div class="bg-white rounded-lg w-[400px] p-6 shadow-lg transform transition duration-300">
      <!-- Bagian Header Modal -->
      <div class="flex justify-between items-center border-b pb-2 mb-4">
        <h2 class="text-2xl font-semibold">Kode Pembayaran</h2>
        <button id="closePaymentModal" class="text-gray-500 hover:text-gray-700 text-xl focus:outline-none">
          &times;
        </button>
      </div>

      <div id="alert-3" class="flex items-center p-4 mb-4 text-green-800 rounded-lg bg-green-50" role="alert">
        <svg class="shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
          viewBox="0 0 20 20">
          <path
            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
        </svg>
        <span class="sr-only">Info</span>
        <div class="ms-3 text-sm font-medium">
          Silahkan lakukan konfirmasi pembayaran menuju kasir sesuai dengan kode dibawah ini
        </div>
      </div>

      <!-- Konten Kode Pembayaran atau QRIS -->
      <div id="paymentCodeContent" class="overflow-y-auto max-h-[300px] text-sm text-gray-700 space-y-4">
        <!-- Konten akan diinject oleh JavaScript (buildPaymentCodeModal) -->
      </div>

      <!-- Tombol Konfirmasi Sudah Dibayar -->
      <div class="mt-6 flex justify-end">
        <button id="confirmPaidBtn" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
          Konfirmasi Sudah Dibayar
        </button>
      </div>
    </div>
  </div>




  <script src="menu.js" defer></script>
</body>

</html>