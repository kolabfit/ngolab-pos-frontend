<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Slot Kasir</title>
    <style>
        .animated-bg {
            background: linear-gradient(270deg, #1a1a2e, #090e1c, #ff7700, #000000);
            background-size: 200% 200%;
            animation: gradientBGAnimation 10s ease infinite;
        }

        @keyframes gradientBGAnimation {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #141414;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            text-align: center;
        }

        h2 {
            margin-bottom: 30px;
            font-size: 24px;
        }

        .slots {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .slot {
            background-color: #333;
            padding: 20px;
            width: 120px;
            height: 120px;
            border-radius: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            transition: transform 0.3s ease, background-color 0.3s ease, box-shadow 0.3s ease;
            position: relative;
        }

        .slot.disabled {
            background-color: #666;
            cursor: not-allowed;
            pointer-events: none;
        }

        .slot:not(.disabled):hover {
            transform: scale(1.1);
            background-color: #ff7700;
            box-shadow: 0 8px 15px rgba(255, 119, 0, 0.6);
        }

        .slot-text {
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            color: #fff;
        }
    </style>
</head>

<body class="animated-bg">
    <div class="container">
        <h2>Pilih Slot Kasir</h2>
        <div class="slots">
            <div id="slot-1" class="slot" onclick="pilihSlot(1)">
                <div class="slot-text">Kasir 1</div>
            </div>
            <div id="slot-2" class="slot" onclick="pilihSlot(2)">
                <div class="slot-text">Kasir 2</div>
            </div>
            <div id="slot-3" class="slot" onclick="pilihSlot(3)">
                <div class="slot-text">Kasir 3</div>
            </div>
            <div id="slot-4" class="slot" onclick="pilihSlot(4)">
                <div class="slot-text">Kasir 4</div>
            </div>
            <!-- Slot baru untuk Self Service -->
            <div id="slot-5" class="slot" onclick="pilihSlot(5)">
                <div class="slot-text">Self Service</div>
            </div>
        </div>
    </div>

    <script>
        // Fungsi untuk mendapatkan nilai cookie
        function getCookie(name) {
            const cookieString = `; ${document.cookie}`;
            const parts = cookieString.split(`; ${name}=`);
            if (parts.length === 2) {
                return parts.pop().split(';').shift();
            }
            return null;
        }

        // Fungsi untuk memuat status slot dari API
        async function loadSlots() {
            try {
                const response = await fetch('http://127.0.0.1:8000/api/cashier-slots', {
                    method: 'GET',
                    headers: {
                        'Authorization': `${getCookie('auth_token')}`
                    }
                });

                if (!response.ok) {
                    throw new Error('Gagal memuat data slot.');
                }

                const result = await response.json();

                // Periksa dan perbarui slot berdasarkan data yang diterima
                result.data.forEach(slotData => {
                    const slotElement = document.getElementById(`slot-${slotData.number}`);
                    if (slotElement) {
                        // Nonaktifkan slot jika sudah diisi
                        slotElement.classList.add('disabled');
                        slotElement.onclick = null;

                        // Tampilkan nama kasir di slot
                        const slotText = slotElement.querySelector('.slot-text');
                        if (slotText) {
                            slotText.textContent = slotData.cashier.name;
                        }
                    }
                });
            } catch (error) {
                alert(`Terjadi kesalahan saat memuat slot: ${error.message}`);
            }
        }

        // Fungsi untuk memilih slot
        async function pilihSlot(slotNumber) {
            try {
                const response = await fetch('http://127.0.0.1:8000/api/cashier-slots/fill', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `${getCookie('auth_token')}`
                    },
                    body: JSON.stringify({ number: slotNumber })
                });

                if (!response.ok) {
                    alert('Gagal mengisi slot. Silakan coba lagi.');
                    return;
                }

                const result = await response.json();
                // Simpan slot yang dipilih dalam cookie agar bisa digunakan di middleware
                document.cookie = `selected_slot=${slotNumber}; path=/`;
                alert(`Berhasil memilih slot ${slotNumber}: ${result.message}`);
                window.location.href = 'index.php';
            } catch (error) {
                alert(`Terjadi kesalahan: ${error.message}`);
            }
        }

        // Panggil fungsi untuk memuat status slot saat halaman dimuat
        window.onload = loadSlots;
    </script>
</body>

</html>
