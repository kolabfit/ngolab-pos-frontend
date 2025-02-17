function getCookie(name) {
  const cookieString = `; ${document.cookie}`;
  const parts = cookieString.split(`; ${name}=`);
  if (parts.length === 2) {
    return parts.pop().split(';').shift();
  }
  return null;
}

// Fungsi showAlert menampilkan notifikasi di tengah atas halaman
function showAlert(message, type = 'success', duration = 3000) {
  let alertContainer = document.getElementById('alert-container');
  if (!alertContainer) {
    alertContainer = document.createElement('div');
    alertContainer.id = 'alert-container';
    alertContainer.className = "fixed top-5 left-1/2 transform -translate-x-1/2 z-50";
    document.body.appendChild(alertContainer);
  }

  let bgColor, borderColor, textColor;
  switch (type) {
    case 'success':
      bgColor = 'bg-green-100';
      borderColor = 'border-green-400';
      textColor = 'text-green-700';
      break;
    case 'error':
      bgColor = 'bg-red-100';
      borderColor = 'border-red-400';
      textColor = 'text-red-700';
      break;
    case 'warning':
      bgColor = 'bg-yellow-100';
      borderColor = 'border-yellow-400';
      textColor = 'text-yellow-700';
      break;
    case 'info':
      bgColor = 'bg-blue-100';
      borderColor = 'border-blue-400';
      textColor = 'text-blue-700';
      break;
    default:
      bgColor = 'bg-gray-100';
      borderColor = 'border-gray-400';
      textColor = 'text-gray-700';
  }

  const alertBox = document.createElement('div');
  alertBox.className = `px-6 py-4 ${bgColor} ${borderColor} ${textColor} border rounded shadow-md mb-2`;
  alertBox.textContent = message;
  alertContainer.appendChild(alertBox);

  setTimeout(() => {
    alertBox.classList.add("opacity-0", "transition-opacity", "duration-500");
    setTimeout(() => {
      alertBox.remove();
      if (!alertContainer.hasChildNodes()) {
        alertContainer.remove();
      }
    }, 500);
  }, duration);
}

document.addEventListener("DOMContentLoaded", function () {
  let orderItems = [];
  let subtotal = 0;
  let discount = 0;         // Akan diisi dari API voucher (jika ada)
  let finalPriceLocal = 0;  // Perhitungan lokal total pesanan setelah diskon
  let selectedOrderType = "dine_in";
  let currentTransaction = null;
  let currentOutletId = null;

  // Elemen DOM utama
  const orderSummary = document.getElementById("order-items");
  const subtotalEl = document.getElementById("subtotal");
  const totalEl = document.getElementById("total");
  const voucherInput = document.getElementById("voucher");
  const applyVoucherBtn = document.getElementById("apply-voucher");
  const confirmPaymentBtn = document.getElementById("confirm-payment");
  const searchInput = document.getElementById("search");

  // Input data customer
  const customerNameInput = document.getElementById("customerName");
  const customerPhoneInput = document.getElementById("customerPhone");
  const customerEmailInput = document.getElementById("customerEmail");
  const customerInstagramInput = document.getElementById("customerInstagram");

  // Payment method (hidden input + dropdown)
  const paymentTypeInput = document.getElementById("paymentType");
  const paymentDropdownButton = document.getElementById("paymentDropdownButton");
  const paymentDropdownMenu = document.getElementById("paymentDropdownMenu");
  const selectedPayment = document.getElementById("selectedPayment");

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

  // Logout & Profile
  const profileButton = document.getElementById("profileButton");
  const profileDropdown = document.getElementById("profileDropdown");
  const logoutButton = document.getElementById("logoutButton");

  // Modal Recap Pesanan (untuk menampilkan rangkuman pesanan lokal)
  const transactionRecapModal = document.getElementById("transactionRecapModal");
  const transactionRecapContent = document.getElementById("transactionRecapContent");
  const closeRecapModal = document.getElementById("closeRecapModal");
  const proceedPaymentBtn = document.getElementById("proceedPaymentBtn");

  // Modal Payment Code (untuk menampilkan kode pembayaran/QRIS)
  const paymentCodeModal = document.getElementById("paymentCodeModal");
  const paymentCodeContent = document.getElementById("paymentCodeContent");
  const closePaymentModal = document.getElementById("closePaymentModal");
  const confirmPaidBtn = document.getElementById("confirmPaidBtn");

  // ===========================
  // Payment Dropdown Setup
  // ===========================
  if (paymentDropdownButton) {
    paymentDropdownButton.addEventListener("click", function (e) {
      e.stopPropagation();
      paymentDropdownMenu.classList.toggle("hidden");
    });
    document.querySelectorAll("#paymentDropdownMenu a").forEach(item => {
      item.addEventListener("click", function (e) {
        e.preventDefault();
        const value = this.getAttribute("data-value");
        selectedPayment.textContent = this.textContent;
        paymentTypeInput.value = value;
        paymentDropdownMenu.classList.add("hidden");
      });
    });
    window.addEventListener("click", function (e) {
      if (!paymentDropdownButton.contains(e.target)) {
        paymentDropdownMenu.classList.add("hidden");
      }
    });
  }

  // ===========================
  // Filtering Menu (Search)
  // ===========================
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

  // ===========================
  // Update Order Summary
  // ===========================
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

    const totalAfterDiscountEl = document.getElementById("totalAfterDiscount");
    if (discount > 0) {
      finalPriceLocal = subtotal - discount;
      totalEl.classList.add("hidden");
      totalAfterDiscountEl.textContent = `Rp${finalPriceLocal.toLocaleString()}`;
      totalAfterDiscountEl.classList.remove("hidden");
    } else {
      discount = 0;
      finalPriceLocal = subtotal;
      totalAfterDiscountEl.classList.add("hidden");
      totalEl.textContent = `Rp${finalPriceLocal.toLocaleString()}`;
      totalEl.classList.remove("hidden");
    }
  }

  // ===========================
  // Order Type Buttons
  // ===========================
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

  // ===========================
  // Filter Menu by Outlet
  // ===========================
  function filterMenuByOutlet(outletId) {
    menuItems.forEach((item) => {
      if (item.getAttribute("data-outlet") === outletId) {
        item.classList.remove("hidden");
      } else {
        item.classList.add("hidden");
      }
    });
  }
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

  // ===========================
  // Modal Edit Order
  // ===========================
  let currentEditIndex = null;
  function showEditOrderModal(index) {
    const order = orderItems[index];
    document.getElementById("editOrderImage").src = order.image;
    document.getElementById("editOrderName").textContent = order.item;
    document.getElementById("editOrderPrice").textContent = `Rp ${order.price.toLocaleString()}`;
    orderQuantityEl.textContent = order.quantity;
    orderNoteTextarea.value = order.note || "";
    currentEditIndex = index;
    editOrderModal.classList.remove("hidden");
  }
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
        showAlert("Pesanan berhasil dihapus.", "success", 2000);
      }
    }
  });

  // ===========================
  // Add Order from Menu
  // ===========================
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
      showAlert("Pesanan berhasil ditambahkan.", "success", 2000);
    });
  });

  // ===========================
  // Quantity Adjustment in Edit Modal
  // ===========================
  if (increaseQuantityBtn) {
    increaseQuantityBtn.addEventListener("click", function () {
      let currentQuantity = parseInt(orderQuantityEl.textContent);
      orderQuantityEl.textContent = ++currentQuantity;
    });
  }
  if (decreaseQuantityBtn) {
    decreaseQuantityBtn.addEventListener("click", function () {
      let currentQuantity = parseInt(orderQuantityEl.textContent);
      if (currentQuantity > 1) {
        orderQuantityEl.textContent = --currentQuantity;
      }
    });
  }
  if (confirmEditOrderBtn) {
    confirmEditOrderBtn.addEventListener("click", function () {
      if (currentEditIndex !== null) {
        const updatedQuantity = parseInt(orderQuantityEl.textContent);
        const updatedNote = orderNoteTextarea.value.trim();
        orderItems[currentEditIndex].quantity = updatedQuantity;
        orderItems[currentEditIndex].note = updatedNote;
        updateOrderSummary();
        editOrderModal.classList.add("hidden");
        showAlert("Pesanan berhasil diperbarui.", "success", 3000);
        if (updatedNote !== "") {
          showAlert("Catatan: " + updatedNote, "info", 3000);
        }
      }
    });
  }
  if (closeEditModalBtn) {
    closeEditModalBtn.addEventListener("click", function () {
      editOrderModal.classList.add("hidden");
    });
  }

  // ===========================
  // Apply Voucher
  // ===========================
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
    fetch("http://127.0.0.1:8000/api/transactions/discount", {
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
        discount = data.data.total_discount;
        finalPriceLocal = data.data.final_price;
        document.getElementById('total_discount').textContent = `-Rp ${discount.toLocaleString()}`;
        document.getElementById('totalAfterDiscount').textContent = `Rp ${finalPriceLocal.toLocaleString()}`;
        document.getElementById('totalAfterDiscount').classList.remove('hidden');
        document.getElementById('total').classList.add('hidden');
        updateOrderSummary();
      })
      .catch(error => {
        console.error("Terjadi error:", error);
        alert("Terjadi error saat mengkonfirmasi voucher: " + error.message);
      });
  });

  // ===========================
  // TRANSACTION FLOW
  // ===========================
  // 1. Confirm Payment -> Tampilkan modal recap pesanan lokal (tanpa mengirim data ke API)
  confirmPaymentBtn.addEventListener("click", function (event) {
    event.preventDefault();
    if (orderItems.length === 0) {
      alert("Pesanan kosong.");
      return;
    }
    const customerName = customerNameInput.value.trim();
    if (!customerName) {
      alert("Nama customer wajib diisi.");
      return;
    }
    const paymentMethod = paymentTypeInput.value.toLowerCase();
    if (!["tunai", "qris"].includes(paymentMethod)) {
      alert("Metode pembayaran tidak valid.");
      return;
    }
    buildLocalRecap();
    transactionRecapModal.classList.remove("hidden");
  });

  // Fungsi untuk membangun recap pesanan lokal
  function buildLocalRecap() {
    let serviceTypeText = (selectedOrderType === 'dine_in') ? 'Dine In' : 'Take Away';
    const paymentMethod = paymentTypeInput.value.toLowerCase();
    let finalLocal = discount > 0 ? (subtotal - discount) : subtotal;
    const recapHTML = `
      <p><strong>Customer Name:</strong> ${customerNameInput.value.trim()}</p>
      <p><strong>Service Type:</strong> ${serviceTypeText}</p>
      <p><strong>Payment Method:</strong> ${paymentMethod}</p>
      <p><strong>Discount:</strong> Rp ${discount.toLocaleString()}</p>
      <p><strong>Final Price (Local):</strong> Rp ${finalLocal.toLocaleString()}</p>
      <hr />
      <h3 class="text-lg font-semibold">Detail Pesanan:</h3>
      <ul class="list-disc pl-5">
        ${orderItems.map(item => `<li>${item.item} (Qty: ${item.quantity})${item.note ? ' - Catatan: ' + item.note : ''}</li>`).join("")}
      </ul>
    `;
    transactionRecapContent.innerHTML = recapHTML;
  }

  // 2. Lanjut ke Pembayaran -> Kirim payload ke API dan tampilkan modal Payment Code
  proceedPaymentBtn.addEventListener("click", function () {
    const payload = {
      outlet_products: orderItems.map(order => ({
        outlet_product_id: parseInt(order.id, 10),
        quantity: order.quantity,
        notes: String(order.note).trim()
      })),
      customer_name: customerNameInput.value.trim(),
      payment_status: "pending",
      payment_method: paymentTypeInput.value.toLowerCase(),
      service_type: selectedOrderType,
      ...(voucherInput.value.trim() ? { voucher_code: voucherInput.value.trim() } : {}),
      ...(customerPhoneInput && customerPhoneInput.value.trim() ? { customer_phone: customerPhoneInput.value.trim() } : {}),
      ...(customerEmailInput && customerEmailInput.value.trim() ? { customer_email: customerEmailInput.value.trim() } : {}),
      ...(customerInstagramInput && customerInstagramInput.value.trim() ? { customer_instagram: customerInstagramInput.value.trim() } : {})
    };

    fetch("http://127.0.0.1:8000/api/transactions", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Authorization: `${getCookie('auth_token')}`
      },
      body: JSON.stringify(payload)
    })
      .then(response => {
        if (!response.ok) throw new Error("Gagal membuat transaksi: " + response.statusText);
        return response.json();
      })
      .then(data => {
        currentTransaction = data.data;
        transactionRecapModal.classList.add("hidden");
        buildPaymentCodeModal();
        paymentCodeModal.classList.remove("hidden");
        showAlert("Transaksi berhasil dibuat. Silahkan konfirmasi pembayaran di kasir.", "success", 3000);
      })
      .catch(error => {
        console.error("Error:", error);
        alert("Terjadi error saat membuat transaksi: " + error.message);
      });
  });

  // Fungsi untuk menampilkan modal Payment Code
  function buildPaymentCodeModal() {
    paymentCodeContent.innerHTML = "";
    if (!currentTransaction) return;
    let finalPrice = currentTransaction.final_price || 0;
    const infoWrapper = document.createElement("div");
    infoWrapper.classList.add("p-4", "border", "rounded-lg", "shadow-sm", "space-y-2", "bg-gray-50");

    const priceEl = document.createElement("p");
    priceEl.classList.add("text-lg", "font-medium", "text-gray-700");
    priceEl.textContent = `Total Tagihan: Rp${finalPrice.toLocaleString()}`;
    infoWrapper.appendChild(priceEl);
    paymentCodeContent.appendChild(infoWrapper);

    if (currentTransaction.payment_method === "tunai") {
      const codeEl = document.createElement("p");
      codeEl.classList.add("text-lg", "font-semibold", "text-blue-600", "mt-4");
      codeEl.textContent = `Kode Pembayaran: ${currentTransaction.id}`;
      paymentCodeContent.appendChild(codeEl);
    } else if (currentTransaction.payment_method === "qris") {
      fetch("http://127.0.0.1:8000/api/outlets", {
        method: "GET",
        headers: {
          "Content-Type": "application/json",
          Authorization: `${getCookie('auth_token')}`
        }
      })
        .then(response => {
          if (!response.ok) throw new Error("Gagal mengambil data outlet: " + response.statusText);
          return response.json();
        })
        .then(data => {
          const outlets = data.data || [];
          let outlet = outlets.find(o => String(o.id) === currentOutletId);
          if (!outlet && outlets.length > 0) {
            outlet = outlets[0];
          }
          if (outlet && outlet.qris) {
            const codeEl = document.createElement("p");
            codeEl.classList.add("text-lg", "font-semibold", "text-blue-600", "mt-4");
            codeEl.textContent = `Kode Pembayaran: ${currentTransaction.id}`;
            paymentCodeContent.appendChild(codeEl);

            const qrEl = document.createElement("img");
            qrEl.src = outlet.qris;
            qrEl.alt = "QRIS Code";
            qrEl.classList.add("w-40", "mt-4", "rounded-md", "border", "block", "mx-auto");
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

  // Tombol "Konfirmasi Sudah Dibayar" hanya menutup modal Payment Code
  confirmPaidBtn.addEventListener("click", function () {
    // Tutup modal Payment Code
    paymentCodeModal.classList.add("hidden");
    // Kosongkan orderItems sehingga order summary tidak muncul lagi
    orderItems = [];
    updateOrderSummary();
  });

  // Tutup modal recap dan modal payment code
  closeRecapModal.addEventListener("click", function () {
    transactionRecapModal.classList.add("hidden");
  });
  closePaymentModal.addEventListener("click", function () {
    paymentCodeModal.classList.add("hidden");
  });

  // Fungsi Logout
  async function handleLogout() {
    const authToken = getCookie("auth_token");
    if (!authToken) {
      alert("Anda tidak memiliki token otentikasi.");
      return;
    }
    const confirmation = confirm("Apakah Anda yakin ingin logout?");
    if (!confirmation) return;
    try {
      const response = await fetch("http://127.0.0.1:8000/api/users/logout", {
        method: "DELETE",
        headers: {
          "Content-Type": "application/json",
          Authorization: `${getCookie('auth_token')}`
        }
      });
      if (!response.ok) throw new Error("Gagal logout. Silakan coba lagi.");
      document.cookie = 'auth_token=; path=/; expires=Thu, 01 Jan 1970 00:00:00 UTC;';
      alert("Logout berhasil.");
      window.location.href = "../logic/login.php";
    } catch (error) {
      console.error("Terjadi kesalahan saat logout:", error);
      alert("Terjadi kesalahan saat logout.");
    }
  }
  logoutButton.addEventListener("click", handleLogout);

  // Toggle Profile Dropdown
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
