function getCookie(name) {
  // Mendapatkan seluruh cookies dan menambahkan '; ' di awal agar mudah di-split
  const cookieString = `; ${document.cookie}`;
  // Memisahkan string berdasarkan nama cookie yang dicari
  const parts = cookieString.split(`; ${name}=`);

  if (parts.length === 2) {
    // Mengembalikan nilai cookie yang telah di-decode (jika ada encoding)
    return parts.pop().split(';').shift();
  }

  // Jika cookie tidak ditemukan, kembalikan null atau nilai default
  return null;
}

document.addEventListener("DOMContentLoaded", function () {
  let orderItems = [];
  let subtotal = 0;
  let discount = 0;
  let selectedOrderType = "dine_in"; // Default order type

  // Elemen-elemen DOM
  const orderSummary = document.getElementById("order-items");
  const subtotalEl = document.getElementById("subtotal");
  const discountEl = document.getElementById("discount");
  const totalEl = document.getElementById("total");
  const voucherInput = document.getElementById("voucher");
  const applyVoucherBtn = document.getElementById("apply-voucher");
  const confirmPaymentBtn = document.getElementById("confirm-payment");
  const searchInput = document.getElementById("search");  // Elemen input pencarian

  // Input data customer
  const customerNameInput = document.getElementById("customerName");
  const customerPhoneInput = document.getElementById("customerPhone"); // Opsional
  const customerEmailInput = document.getElementById("customerEmail"); // Opsional
  const customerInstagramInput = document.getElementById("customerInstagram"); // Opsional
  // Select untuk payment method (misalnya: cash, qris, hutang)
  const paymentTypeSelect = document.getElementById("paymentType");

  const editOrderModal = document.getElementById("editOrderModal");
  const orderQuantityEl = document.getElementById("orderQuantity");
  const orderNoteTextarea = document.getElementById("orderNote");
  const confirmEditOrderBtn = document.getElementById("confirmEditOrder");
  const closeEditModalBtn = document.getElementById("closeEditModal");
  const increaseQuantityBtn = document.getElementById("increaseQuantity");
  const decreaseQuantityBtn = document.getElementById("decreaseQuantity");

  const orderTypeButtons = document.querySelectorAll("button[data-type]");
  const outletFilterButtons = document.querySelectorAll(".outlet-filter");
  const menuItems = document.querySelectorAll(".menu-item"); // Semua produk menu

  //logout
  const profileButton = document.getElementById("profileButton");
  const profileDropdown = document.getElementById("profileDropdown");
  const logoutButton = document.getElementById("logoutButton");

  let currentEditIndex = null;

  // Fungsi untuk memfilter menu berdasarkan input pencarian
  function filterMenuBySearch(query) {
    menuItems.forEach((item) => {
      const itemName = item.querySelector("h3").textContent.toLowerCase();
      if (itemName.includes(query.toLowerCase())) {
        item.classList.remove("hidden");
      } else {
        item.classList.add("hidden");
      }
    });
  }

  // Event listener untuk input pencarian
  searchInput.addEventListener("input", function () {
    const searchQuery = searchInput.value.trim();
    filterMenuBySearch(searchQuery);
  });

  // Fungsi memperbarui ringkasan pesanan
  function updateOrderSummary() {
    orderSummary.innerHTML = "";
    subtotal = 0;

    orderItems.forEach((order, index) => {
      subtotal += order.price * order.quantity;

      const orderCard = document.createElement("div");
      orderCard.classList.add("flex", "items-center", "border", "p-2", "rounded-lg", "shadow-sm");

      orderCard.innerHTML = `
        <img src="${order.image}" alt="${order.item}" class="w-20 h-20 object-cover rounded-lg" />
        <div class="ml-4 flex-1">
          <h3 class="font-medium text-lg">${order.item} <span class="text-gray-500">(${order.quantity})</span></h3>
          <p class="text-sm text-gray-500 mt-1">Catatan: ${order.note || "Tidak ada"}</p>
          <div class="flex items-center justify-between mt-1">
            <p class="font-medium text-base">Rp ${(
          order.price * order.quantity
        ).toLocaleString()}</p>
            <div class="flex space-x-2">
              <button class="edit-order text-gray-500 hover:text-gray-700" data-index="${index}">
                Edit
              </button>
              <button class="delete-order text-red-500 hover:text-red-700" data-index="${index}">
                Hapus
              </button>
            </div>
          </div>
        </div>
      `;
      orderSummary.appendChild(orderCard);
    });

    subtotalEl.textContent = `Rp${subtotal.toLocaleString()}`;
    totalEl.textContent = `Rp${(subtotal - discount).toLocaleString()}`;
  }

  // Event listener untuk tombol filter jenis pesanan (Dine In, To Go, Takeaway)
  orderTypeButtons.forEach((button) => {
    button.addEventListener("click", function () {
      selectedOrderType = this.getAttribute("data-type");

      // Ubah tampilan tombol aktif
      orderTypeButtons.forEach((btn) => {
        btn.classList.remove("bg-gradient-to-r", "from-orange-400", "to-yellow-400", "text-white");
        btn.classList.add("bg-gray-100", "text-gray-700");
      });

      this.classList.remove("bg-gray-100", "text-gray-700");
      this.classList.add("bg-gradient-to-r", "from-orange-400", "to-yellow-400", "text-white");

      updateOrderSummary();
    });
  });

  // Fungsi untuk memfilter produk berdasarkan outlet
  function filterMenuByOutlet(outletId) {
    menuItems.forEach((item) => {
      if (item.getAttribute("data-outlet") === outletId) {
        item.classList.remove("hidden");
      } else {
        item.classList.add("hidden");
      }
    });
  }

  // Event listener untuk tombol filter outlet
  outletFilterButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const outletId = this.getAttribute("data-outlet");
      filterMenuByOutlet(outletId);

      // Ubah tampilan tombol aktif
      outletFilterButtons.forEach((btn) => {
        btn.classList.remove("bg-gradient-to-r", "from-orange-400", "to-yellow-400", "text-white");
        btn.classList.add("bg-gray-200", "text-gray-600");
      });

      this.classList.remove("bg-gray-200", "text-gray-600");
      this.classList.add("bg-gradient-to-r", "from-orange-400", "to-yellow-400", "text-white");
    });
  });

  // Fungsi untuk menampilkan modal edit pesanan
  function showEditOrderModal(index) {
    const order = orderItems[index];
    currentEditIndex = index;

    document.getElementById("editOrderImage").src = order.image;
    document.getElementById("editOrderName").textContent = order.item;
    document.getElementById("editOrderPrice").textContent = `Rp ${order.price.toLocaleString()}`;
    orderQuantityEl.textContent = order.quantity;
    orderNoteTextarea.value = order.note || "";

    editOrderModal.classList.remove("hidden");
  }

  // Event listener untuk tombol edit dan hapus pesanan
  orderSummary.addEventListener("click", function (e) {
    if (e.target.closest(".edit-order")) {
      const index = e.target.closest(".edit-order").getAttribute("data-index");
      showEditOrderModal(index);
    }

    if (e.target.closest(".delete-order")) {
      const index = e.target.closest(".delete-order").getAttribute("data-index");
      if (confirm("Apakah Anda yakin ingin menghapus pesanan ini?")) {
        orderItems.splice(index, 1);
        updateOrderSummary();
      }
    }
  });

  // Menambahkan pesanan dari menu
  document.querySelectorAll(".add-to-order").forEach((button) => {
    button.addEventListener("click", function () {
      const item = this.getAttribute("data-item"); // Diasumsikan sebagai outlet_product_id
      const price = parseInt(this.getAttribute("data-price"));
      const imageSrc = this.parentElement.previousElementSibling.getAttribute("src");
      const id = this.getAttribute("data-outlet-product-id");

      const existingItemIndex = orderItems.findIndex((order) => order.item === item);

      if (existingItemIndex > -1) {
        orderItems[existingItemIndex].quantity++;
      } else {
        orderItems.push({ item, price, quantity: 1, image: imageSrc, note: "", id: id });
      }

      updateOrderSummary();
    });
  });

  // Fungsi untuk perubahan kuantitas di modal edit
  increaseQuantityBtn.addEventListener("click", function () {
    let currentQuantity = parseInt(orderQuantityEl.textContent);
    orderQuantityEl.textContent = ++currentQuantity;
  });

  decreaseQuantityBtn.addEventListener("click", function () {
    let currentQuantity = parseInt(orderQuantityEl.textContent);
    if (currentQuantity > 1) {
      orderQuantityEl.textContent = --currentQuantity;
    }
  });

  // Event listener untuk konfirmasi edit pesanan
  confirmEditOrderBtn.addEventListener("click", function () {
    if (currentEditIndex !== null) {
      const updatedQuantity = parseInt(orderQuantityEl.textContent);
      const updatedNote = orderNoteTextarea.value.trim();

      orderItems[currentEditIndex].quantity = updatedQuantity;
      orderItems[currentEditIndex].note = updatedNote;

      updateOrderSummary();
      editOrderModal.classList.add("hidden");
    }
  });

  closeEditModalBtn.addEventListener("click", function () {
    editOrderModal.classList.add("hidden");
  });

  applyVoucherBtn.addEventListener("click", function (e) {
    e.preventDefault();
  
    const voucherCode = voucherInput.value.trim().toUpperCase();
  
    if (!voucherCode) {
      alert("Masukkan kode voucher terlebih dahulu.");
      return;
    }
  
    // Persiapkan payload untuk API
    const payload = {
      outlet_products: orderItems.map(item => ({
        outlet_product_id: parseInt(item.id, 10),
        quantity: item.quantity
      })),
      voucher_code: voucherCode
    };

    console.log("Sending POST request with payload:", payload);
  
    // Kirim request ke API
    fetch("https://ngolab.id/api/transactions/discount", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        'Authorization': `${getCookie('auth_token')}`
      },
      body: JSON.stringify(payload)
    })
      .then(response => {
        if (!response.ok) {
          throw new Error("Network response was not ok " + response.statusText);
        }
        return response.json();
      })
      .then(data => {
        console.log("Sukses:", data);
        document.getElementById('total_discount').textContent = `-Rp ${data.data.total_discount.toLocaleString()}`;
        document.getElementById('totalAfterDiscount').textContent = `Rp ${data.data.final_price.toLocaleString()}`;
        document.getElementById('totalAfterDiscount').classList.remove('hidden');
        document.getElementById('total').classList.add('hidden');
        updateOrderSummary();
      })
      .catch(error => {
        console.error("Terjadi error:", error);
        alert("Terjadi error saat mengkonfirmasi pembayaran: " + error.message);
      });
  });

  // Event listener untuk tombol Confirm Payment dengan integrasi API
  confirmPaymentBtn.addEventListener("click", function (event) {
 
    if (orderItems.length === 0) {
      alert("Pesanan kosong.");
      return;
    }

    // 1. Persiapkan data outlet_products dari orderItems
    const outlet_products = orderItems.map(order => {
      const outletProductId = parseInt(order.id, 10);
      if (isNaN(outletProductId)) {
        console.error("Invalid outlet product id:", order.id);
        return null;
      }
      return {
        outlet_product_id: outletProductId,
        quantity: order.quantity,
        notes: (String(order.note).trim())
      };
    }).filter(product => product !== null);

    // 2. Ambil nama customer (wajib)
    const customerName = customerNameInput ? customerNameInput.value.trim() : "";
    if (!customerName) {
      alert("Nama customer wajib diisi.");
      return;
    }

    // 3. Data opsional customer
    const customerPhone = customerPhoneInput ? customerPhoneInput.value.trim() : "";
    const customerEmail = customerEmailInput ? customerEmailInput.value.trim() : "";
    const customerInstagram = customerInstagramInput ? customerInstagramInput.value.trim() : "";

    // 4. Ambil voucher jika ada
    const voucherCode = voucherInput.value.trim();

    // 5. Validasi payment_method
    const paymentMethod = paymentTypeSelect ? paymentTypeSelect.value.toLowerCase() : "";
    const validPaymentMethods = ["tunai", "qris"];
    if (!validPaymentMethods.includes(paymentMethod)) {
      alert("Metode pembayaran tidak valid.");
      return;
    }

    // 6. Status pembayaran
    const paymentStatus = "success";
    const orderType = selectedOrderType;
    console.log(orderType);

    // 7. Buat payload dengan field opsional hanya jika ada nilainya
    const payload = {
      outlet_products,
      customer_name: customerName,
      payment_status: paymentStatus,
      payment_method: paymentMethod,
      service_type: orderType,
      ...(voucherCode ? { voucher_code: voucherCode } : {}),
      ...(customerPhone ? { customer_phone: customerPhone } : {}),
      ...(customerEmail ? { customer_email: customerEmail } : {}),
      ...(customerInstagram ? { customer_instagram: customerInstagram } : {})
    };

    // const jsonPayload = JSON.stringify(payload, null, 2); // Parameter kedua (null) dan ketiga (2) digunakan untuk memformat output agar lebih terbaca

    // console.log(jsonPayload);

    // 8. Kirim data ke API
    fetch("https://ngolab.id/api/transactions", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        'Authorization': `${getCookie('auth_token')}`
      },
      body: JSON.stringify(payload)
    })
      .then(response => {
        if (!response.ok) {
          throw new Error("Network response was not ok " + response.statusText);
        }
        return response.json();
      })
      .then(data => {
        console.log("Sukses:", data);
        alert("Pembayaran berhasil dikonfirmasi.");
        orderItems = [];
        updateOrderSummary();
      })
      .catch(error => {
        console.error("Terjadi error:", error);
        alert("Terjadi error saat mengkonfirmasi pembayaran: " + error.message);
      });
  });

  updateOrderSummary();
  
  // Fungsi logout
  async function handleLogout() {
    const authToken = getCookie("auth_token");
    if (!authToken) {
      alert("Anda tidak memiliki token otentikasi.");
      return;
    }

    const confirmation = confirm("Apakah Anda yakin ingin logout?");
    if (!confirmation) return;

    try {
      const response = await fetch("https://ngolab.id/api/users/logout", {
        method: "DELETE",
        headers: {
          "Content-Type": "application/json",
          Authorization: `${getCookie("auth_token")}`,
        },
      });

      if (!response.ok) {
        throw new Error("Gagal logout. Silakan coba lagi.");
      }

      // Hapus cookie auth_token setelah logout berhasil
      document.cookie = "auth_token=; path=/; expires=Thu, 01 Jan 1970 00:00:00 UTC;";

      alert("Logout berhasil.");
      window.location.href = "../logic/login.php"; // Arahkan pengguna kembali ke halaman login
    } catch (error) {
      console.error("Terjadi kesalahan saat logout:", error);
      alert("Terjadi kesalahan saat logout.");
    }
  }

  // Event listener untuk tombol logout
  logoutButton.addEventListener("click", handleLogout);

  // Event listener untuk toggle dropdown
  profileButton.addEventListener("click", function (event) {
    event.stopPropagation(); // Mencegah event bubbling
    profileDropdown.classList.toggle("hidden");
  });

  // Menutup dropdown jika klik di luar
  document.addEventListener("click", function (event) {
    if (!profileButton.contains(event.target) && !profileDropdown.contains(event.target)) {
      profileDropdown.classList.add("hidden");
    }
  });
  
  
});
// console.log("Payment method:", paymentTypeSelect.value);

// console.log("Original order id:", order.id);
// const outletProductId = parseInt(order.id, 10);
// console.log("Parsed outlet product id:", outletProductId);

// console.log("Outlet Products:", outlet_products);

// console.log("Payment method:", paymentTypeSelect.value);
// console.log("Parsed payment method:", paymentMethod);
// console.log("Outlet products:", outlet_products);


// function confirmPayment(){

//   event.preventDefault();

//     // Mengambil form element
//     const formElement = document.getElementById('voucherForm');
  
//     // Membuat objek FormData dari form
//     const formData = new FormData(formElement);
    
//     // Mengubah FormData menjadi objek biasa
//     const data = Object.fromEntries(formData.entries());
    
//     // Menampilkan data di console
//     ajax
  
  
// };
