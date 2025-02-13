<?php
require_once('../logic/loginvalidation.php');
require_once('../logic/slotvalidation.php');
$user=Validation::validateLoginCashier($_COOKIE['auth_token'] ?? null, '../logic/login.php');
Slotvalidation::isnotfillslot($_COOKIE['auth_token'] ?? null,);

$token = $_COOKIE['auth_token'];

// Ambil data outlet dari API
$outletApiUrl = 'https://ngolab.id/api/outlets';
$outletResponse = file_get_contents($outletApiUrl);
$outlets = json_decode($outletResponse, true)['data'] ?? [];

// Ambil data produk dari API
$productApiUrl = 'https://ngolab.id/api/outlets/products';
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
        <a href="index.php" class="block py-2 px-4 rounded-lg font-medium text-gray-600 hover:bg-gray-100">Beranda</a>
        <a href="transaksi.php"
          class="block py-2 px-4 rounded-lg font-medium bg-gradient-to-r from-orange-400 to-yellow-400 text-white">
          Transaksi
        </a>
        <a href="listtransaksi.php" class="block py-2 px-4 rounded-lg font-medium text-gray-600 hover:bg-gray-100">
          List Transaksi
        </a>
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
            echo "<button class='mt-2 w-full py-2 rounded-lg text-white bg-gradient-to-r from-orange-400 to-yellow-400 hover:opacity-90 transition font-medium add-to-order' data-outlet-product-id='{$outletProductId}' data-item='{$product['name']}' data-price='{$product['price']}'>Tambah Pesanan</button>";
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
          <input type="text" id="customerName" name="customerName" class="border rounded-lg w-full p-2 mb-2"
            placeholder="Masukkan Nama Customer" />
          <!-- Field untuk input voucher code -->
          <input type="text" id="voucher" name="voucher" class="border rounded-lg w-full p-2 mb-2"
            placeholder="Enter voucher code" />
          <button id="apply-voucher"
            class="w-full py-2 rounded-lg bg-gradient-to-r from-orange-400 to-yellow-400 hover:opacity-90 text-white mb-4">
            Konfirmasi
          </button>
          <div class="space-y-2 mb-4 border rounded-lg p-4 text-sm">
            <div class="flex justify-between">
              <span>Subtotal</span>
              <span id="subtotal">Rp0</span>
            </div>
            <div class="flex justify-between">
              <span>Discount</span>
              <span id="total_discount">-Rp0</span>
            </div>
            <div class="flex justify-between font-medium border-t pt-2">
              <span>Total Payment</span>
              <span id="total">Rp0</span>
              <span id="totalAfterDiscount" class="hidden">Rp0</span>
            </div>
          </div>
          <select id="paymentType">
            <option value="tunai">Cash</option>
            <option value="qris">QRIS</option>
          </select>
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

  <script src="menu.js"></script>
</body>

</html>