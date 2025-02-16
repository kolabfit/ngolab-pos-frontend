function getCookie(name) {
  const cookieString = `; ${document.cookie}`;
  const parts = cookieString.split(`; ${name}=`);
  if (parts.length === 2) {
    return parts.pop().split(';').shift();
  }
  return null;
}

document.addEventListener("DOMContentLoaded", function () {
  let orderItems = [];
  let subtotal = 0;
  let discount = 0;
  let selectedOrderType = "dine_in"; // Default order type

  // Elemen-elemen DOM utama
  const orderSummary = document.getElementById("order-items");
  const subtotalEl = document.getElementById("subtotal");
  const totalEl = document.getElementById("total");
  const voucherInput = document.getElementById("voucher");
  const applyVoucherBtn = document.getElementById("apply-voucher");
  const confirmPaymentBtn = document.getElementById("confirm-payment");
  const searchInput = document.getElementById("search");

  // Input data customer
  const customerNameInput = document.getElementById("customerName");
  const customerPhoneInput = document.getElementById("customerPhone"); // Opsional
  const customerEmailInput = document.getElementById("customerEmail"); // Opsional
  const customerInstagramInput = document.getElementById("customerInstagram"); // Opsional

  // Select untuk metode pembayaran
  const paymentTypeSelect = document.getElementById("paymentType");

  // Modal Edit Order
  const editOrderModal = document.getElementById("editOrderModal");
  const orderQuantityEl = document.getElementById("orderQuantity");
  const orderNoteTextarea = document.getElementById("orderNote");
  const confirmEditOrderBtn = document.getElementById("confirmEditOrder");
  const closeEditModalBtn = document.getElementById("closeEditModal");
  const increaseQuantityBtn = document.getElementById("increaseQuantity");
  const decreaseQuantityBtn = document.getElementById("decreaseQuantity");

  // Tombol order type & outlet filter
  const orderTypeButtons = document.querySelectorAll("button[data-type]");
  const outletFilterButtons = document.querySelectorAll(".outlet-filter");
  const menuItems = document.querySelectorAll(".menu-item");

  // Logout dan Profile
  const profileButton = document.getElementById("profileButton");
  const profileDropdown = document.getElementById("profileDropdown");
  const logoutButton = document.getElementById("logoutButton");

  // Modal Recap Pesanan (menampilkan detail transaksi)
  const transactionRecapModal = document.getElementById("transactionRecapModal");
  const transactionRecapContent = document.getElementById("transactionRecapContent");
  const closeRecapModal = document.getElementById("closeRecapModal");
  const proceedPaymentBtn = document.getElementById("proceedPaymentBtn");

  // Modal Payment Code (menampilkan kode pembayaran atau QRIS)
  const paymentCodeModal = document.getElementById("paymentCodeModal");
  const paymentCodeContent = document.getElementById("paymentCodeContent");
  const closePaymentModal = document.getElementById("closePaymentModal");
  const confirmPaidBtn = document.getElementById("confirmPaidBtn");

  let currentEditIndex = null;
  let currentTransaction = null;
  let currentOutletId = null; // Outlet aktif (jika difilter)

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

  searchInput.addEventListener("input", function () {
    const searchQuery = searchInput.value.trim();
    filterMenuBySearch(searchQuery);
  });

  // Fungsi untuk memperbarui ringkasan pesanan
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
            <p class="font-medium text-base">Rp ${(order.price * order.quantity).toLocaleString()}</p>
            <div class="flex space-x-2">
              <button class="edit-order text-gray-500 hover:text-gray-700" data-index="${index}">Edit</button>
              <button class="delete-order text-red-500 hover:text-red-700" data-index="${index}">Hapus</button>
            </div>
          </div>
        </div>
      `;
      orderSummary.appendChild(orderCard);
    });
    subtotalEl.textContent = `Rp${subtotal.toLocaleString()}`;
    totalEl.textContent = `Rp${(subtotal - discount).toLocaleString()}`;
  }

  // Event listener untuk tombol order type (Dine In, Takeaway, dsb.)
  orderTypeButtons.forEach((button) => {
    button.addEventListener("click", function () {
      selectedOrderType = this.getAttribute("data-type");
      orderTypeButtons.forEach((btn) => {
        btn.classList.remove("bg-gradient-to-r", "from-orange-400", "to-yellow-400", "text-white");
        btn.classList.add("bg-gray-100", "text-gray-700");
      });
      this.classList.remove("bg-gray-100", "text-gray-700");
      this.classList.add("bg-gradient-to-r", "from-orange-400", "to-yellow-400", "text-white");
      updateOrderSummary();
    });
  });

  // Fungsi untuk memfilter menu berdasarkan outlet
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
      currentOutletId = this.getAttribute("data-outlet");
      filterMenuByOutlet(currentOutletId);
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

  // Event listener untuk edit dan hapus pada ringkasan pesanan
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
      const item = this.getAttribute("data-item");
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

  // Perubahan kuantitas pada modal edit pesanan
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
  // Konfirmasi edit pesanan
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

  // Event listener untuk apply voucher
  applyVoucherBtn.addEventListener("click", function (e) {
    e.preventDefault();
    const voucherCode = voucherInput.value.trim().toUpperCase();
    if (!voucherCode) {
      alert("Masukkan kode voucher terlebih dahulu.");
      return;
    }
    const payload = {
      outlet_products: orderItems.map(item => ({
        outlet_product_id: parseInt(item.id, 10),
        quantity: item.quantity
      })),
      voucher_code: voucherCode
    };
    fetch("https://ngolab.id/api/transactions/discount", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Authorization: `${getCookie('auth_token')}`
      },
      body: JSON.stringify(payload)
    })
      .then(response => {
        if (!response.ok) throw new Error("Network response was not ok " + response.statusText);
        return response.json();
      })
      .then(data => {
        document.getElementById('total_discount').textContent = `-Rp ${data.data.total_discount.toLocaleString()}`;
        document.getElementById('totalAfterDiscount').textContent = `Rp ${data.data.final_price.toLocaleString()}`;
        document.getElementById('totalAfterDiscount').classList.remove('hidden');
        document.getElementById('total').classList.add('hidden');
        updateOrderSummary();
      })
      .catch(error => {
        console.error("Terjadi error:", error);
        alert("Terjadi error saat mengkonfirmasi voucher: " + error.message);
      });
  });

  // --- FLOW TRANSAKSI ---

  // 1. Saat user menekan Confirm Payment: Buat transaksi dengan payment_status pending
  confirmPaymentBtn.addEventListener("click", function (event) {
    event.preventDefault();
    if (orderItems.length === 0) {
      alert("Pesanan kosong.");
      return;
    }
    const customerName = customerNameInput ? customerNameInput.value.trim() : "";
    if (!customerName) {
      alert("Nama customer wajib diisi.");
      return;
    }
    const paymentMethod = paymentTypeSelect ? paymentTypeSelect.value.toLowerCase() : "";
    if (!["tunai", "qris"].includes(paymentMethod)) {
      alert("Metode pembayaran tidak valid.");
      return;
    }
    const orderType = selectedOrderType;
    const outlet_products = orderItems.map(order => {
      return {
        outlet_product_id: parseInt(order.id, 10),
        quantity: order.quantity,
        notes: String(order.note).trim()
      };
    });
    const customerPhone = customerPhoneInput ? customerPhoneInput.value.trim() : "";
    const customerEmail = customerEmailInput ? customerEmailInput.value.trim() : "";
    const customerInstagram = customerInstagramInput ? customerInstagramInput.value.trim() : "";
    const voucherCode = voucherInput.value.trim();
    const payload = {
      outlet_products,
      customer_name: customerName,
      payment_status: "pending", // transaksi baru dengan status pending
      payment_method: paymentMethod,
      service_type: orderType,
      ...(voucherCode ? { voucher_code: voucherCode } : {}),
      ...(customerPhone ? { customer_phone: customerPhone } : {}),
      ...(customerEmail ? { customer_email: customerEmail } : {}),
      ...(customerInstagram ? { customer_instagram: customerInstagram } : {})
    };

    // Cek saldo outlet product
    fetch("https://ngolab.id/api/balances/outlet-product/check", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Authorization: `${getCookie('auth_token')}`
      },
      body: JSON.stringify(payload)
    })
      .then(response => {
        if (!response.ok) throw new Error("Network response was not ok " + response.statusText);
        return response.json();
      })
      .then(() => {
        // Buat transaksi via API
        return fetch("https://ngolab.id/api/transactions", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            Authorization: `${getCookie('auth_token')}`
          },
          body: JSON.stringify(payload)
        });
      })
      .then(response => {
        if (!response.ok) throw new Error("Gagal membuat transaksi: " + response.statusText);
        return response.json();
      })
      .then(data => {
        // Simpan data transaksi yang baru dibuat
        currentTransaction = data.data;
        // Bangun modal recap transaksi dan tampilkan
        buildTransactionRecap();
        transactionRecapModal.classList.remove("hidden");
      })
      .catch(error => {
        console.error("Error:", error);
        alert("Terjadi error saat membuat transaksi: " + error.message);
        window.location.href = "index.php";
      });
  });

  // Fungsi untuk membangun isi modal Recap Pesanan
  function buildTransactionRecap() {
    transactionRecapContent.innerHTML = "";
    if (!currentTransaction) return;

    // Format service type agar tampil sesuai keinginan
    let serviceTypeText = currentTransaction.service_type;
    if (serviceTypeText === 'dine_in') {
      serviceTypeText = 'Dine In';
    } else if (serviceTypeText === 'take_away') {
      serviceTypeText = 'Take Away';
    }

    // Buat konten recap dengan informasi transaksi
    const recapHTML = `
      <p><strong>ID Transaksi:</strong> ${currentTransaction.id}</p>
      <p><strong>Customer ID:</strong> ${currentTransaction.customer ? currentTransaction.customer.id : '-'}</p>
      <p><strong>Customer Name:</strong> ${currentTransaction.customer ? currentTransaction.customer.name : currentTransaction.customer_name}</p>
      <p><strong>Service Type:</strong> ${serviceTypeText}</p>
      <p><strong>Payment Method:</strong> ${currentTransaction.payment_method}</p>
      <p><strong>Total Discount:</strong> Rp${(currentTransaction.total_discount || 0).toLocaleString()}</p>
      <p><strong>Total Price:</strong> Rp${(currentTransaction.total_price || 0).toLocaleString()}</p>
      <p><strong>Final Price:</strong> Rp${(currentTransaction.final_price || 0).toLocaleString()}</p>
      <hr />
      <h3 class="text-lg font-semibold">Detail Pesanan:</h3>
      <ul class="list-disc pl-5">
        ${currentTransaction.details && currentTransaction.details.length
        ? currentTransaction.details.map(detail => {
          const productName = detail.outlet_product && detail.outlet_product.product ? detail.outlet_product.product.name : 'Item';
          return `<li>${productName} (Qty: ${detail.quantity}) ${detail.notes ? '- Catatan: ' + detail.notes : ''}</li>`;
        }).join("")
        : "<li>Tidak ada detail pesanan.</li>"
      }
      </ul>
    `;
    transactionRecapContent.innerHTML = recapHTML;
  }


  // Ketika user menekan tombol "Lanjut ke Pembayaran" pada modal recap
  proceedPaymentBtn.addEventListener("click", function () {
    transactionRecapModal.classList.add("hidden");
    buildPaymentCodeModal();
    paymentCodeModal.classList.remove("hidden");
  });

  // Fungsi untuk membangun isi modal Payment Code
  function buildPaymentCodeModal() {
    paymentCodeContent.innerHTML = "";
    if (!currentTransaction) return;

    const paymentMethod = currentTransaction.payment_method;
    const finalPrice = currentTransaction.final_price || 0; // final_price dari transaksi

    // Bagian pertama: informasi umum (final_price)
    const infoWrapper = document.createElement("div");
    infoWrapper.classList.add("p-4", "border", "rounded-lg", "shadow-sm", "space-y-2", "bg-gray-50");

    // Tampilkan final_price
    const priceEl = document.createElement("p");
    priceEl.classList.add("text-lg", "font-medium", "text-gray-700");
    priceEl.textContent = `Total Tagihan: Rp${finalPrice.toLocaleString()}`;
    infoWrapper.appendChild(priceEl);

    // Sisipkan infoWrapper ke paymentCodeContent
    paymentCodeContent.appendChild(infoWrapper);

    // Bagian kedua: menampilkan kode pembayaran atau QRIS
    if (paymentMethod === "tunai") {
      // Tampilkan ID transaksi sebagai kode pembayaran
      const codeEl = document.createElement("p");
      codeEl.classList.add("text-lg", "font-semibold", "text-blue-600", "mt-4");
      codeEl.textContent = `Kode Pembayaran: ${currentTransaction.id}`;
      paymentCodeContent.appendChild(codeEl);
    } else if (paymentMethod === "qris") {
      // Fetch data outlet untuk mendapatkan gambar QRIS
      fetch("https://ngolab.id/api/outlets", {
        method: "GET",
        headers: {
          "Content-Type": "application/json",
          Authorization: `${getCookie('auth_token')}`
        }
      })
        .then(response => {
          if (!response.ok) {
            throw new Error("Gagal mengambil data outlet: " + response.statusText);
          }
          return response.json();
        })
        .then(data => {
          const outlets = data.data || [];
          // Cari outlet yang sesuai dengan currentOutletId
          let outlet = outlets.find(o => String(o.id) === currentOutletId);
          if (!outlet && outlets.length > 0) {
            outlet = outlets[0];
          }
          if (outlet && outlet.qris) {
            const qrEl = document.createElement("img");
            qrEl.src = outlet.qris;
            qrEl.alt = "QRIS Code";
            qrEl.classList.add("w-40", "mt-4", "rounded-md", "border", "block", "mx-auto");
            const codeEl = document.createElement("p");
            codeEl.classList.add("text-lg", "font-semibold", "text-blue-600", "mt-4");
            codeEl.textContent = `Kode Pembayaran: ${currentTransaction.id}`;
            paymentCodeContent.appendChild(codeEl);
            paymentCodeContent.appendChild(qrEl);
          } else {
            const msgEl = document.createElement("p");
            msgEl.textContent = "QRIS tidak tersedia.";
            paymentCodeContent.appendChild(msgEl);
          }
        })
        .catch(error => {
          console.error("Error mengambil QRIS:", error);
          const msgEl = document.createElement("p");
          msgEl.textContent = "Gagal menampilkan QRIS.";
          paymentCodeContent.appendChild(msgEl);
        });
    }
  }


  // Ubah event listener untuk tombol "Konfirmasi Sudah Dibayar"
  document.getElementById("confirmPaidBtn").addEventListener("click", function () {
    // Hanya menutup modal Payment Code
    document.getElementById("paymentCodeModal").classList.add("hidden");
  });


  // Tombol untuk menutup modal Recap Pesanan dan Payment Code
  closeRecapModal.addEventListener("click", function () {
    transactionRecapModal.classList.add("hidden");
  });
  closePaymentModal.addEventListener("click", function () {
    paymentCodeModal.classList.add("hidden");
  });

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
      if (!response.ok) throw new Error("Gagal logout. Silakan coba lagi.");
      document.cookie = "auth_token=; path=/; expires=Thu, 01 Jan 1970 00:00:00 UTC;";
      alert("Logout berhasil.");
      window.location.href = "../logic/login.php";
    } catch (error) {
      console.error("Terjadi kesalahan saat logout:", error);
      alert("Terjadi kesalahan saat logout.");
    }
  }
  logoutButton.addEventListener("click", handleLogout);

  // Toggle dropdown profile
  profileButton.addEventListener("click", function (event) {
    event.stopPropagation();
    profileDropdown.classList.toggle("hidden");
  });
  document.addEventListener("click", function (event) {
    if (!profileButton.contains(event.target) && !profileDropdown.contains(event.target)) {
      profileDropdown.classList.add("hidden");
    }
  });

  updateOrderSummary();
});
