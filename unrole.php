<?php
require_once('./logic/loginvalidation.php');
$user = Validation::validateLoginUnrole($_COOKIE['auth_token'] ?? null, '../logic/login.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>401 Unauthorized - POS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        * {
            user-select: none;
            /* Mencegah seleksi teks */
            -webkit-tap-highlight-color: transparent;
            /* Menghilangkan efek highlight biru di mobile */
        }

        /* DINDING GUDANG REALISTIS */
        body {
            cursor:none;
            background: linear-gradient(180deg, #4e3b2a 0%, #24150b 100%);
            background-size: cover;
            background-attachment: fixed;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
            position: relative;
        }

        /* Efek Tekstur Dinding Kasar */
        body::after {
            content: "";
            position: absolute;
            width: 100%;
            height: 100%;
            background: repeating-linear-gradient(0deg,
                    rgba(0, 0, 0, 0.05) 0px,
                    rgba(0, 0, 0, 0.05) 2px,
                    transparent 2px,
                    transparent 50px),
                repeating-linear-gradient(90deg,
                    rgba(0, 0, 0, 0.03) 0px,
                    rgba(0, 0, 0, 0.03) 1px,
                    transparent 1px,
                    transparent 50px);
            opacity: 0.3;
            z-index: -998;
        }

        /* Efek Noda Kotoran di Dinding */
        .dirt-effect {
            position: absolute;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 30% 40%, rgba(0, 0, 0, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 70% 70%, rgba(0, 0, 0, 0.08) 0%, transparent 60%);
            z-index: -997;
            opacity: 0.5;
        }


        /* Efek Bayangan di Bagian Bawah */
        .shadow-bottom {
            position: absolute;
            bottom: 0;
            width: 100%;
            height: 100px;
            background: linear-gradient(0deg, rgba(0, 0, 0, 0.8), transparent);
            z-index: -997;
        }



        /* Lantai Gudang - Sekarang Paling Bawah & Paling Belakang */
        .floor {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 200px;
            background: linear-gradient(180deg, #6b4f3f 0%, #3e2a1f 100%);
            /* Warna kayu atau beton kasar */
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: -999;
            /* Paling belakang */
            box-shadow: 0px -15px 25px rgba(0, 0, 0, 0.8);
            /* Efek bayangan */
            transform: perspective(800px) rotateX(25deg);
            /* Efek kedalaman 3D */
        }

        /* Efek ubin atau papan kayu */
        .floor::before {
            content: "";
            position: absolute;
            width: 100%;
            height: 100%;
            background: repeating-linear-gradient(90deg,
                    rgba(0, 0, 0, 0.3) 0px,
                    rgba(0, 0, 0, 0.3) 3px,
                    transparent 3px,
                    transparent 50px), repeating-linear-gradient(0deg,
                    rgba(0, 0, 0, 0.15) 0px,
                    rgba(0, 0, 0, 0.15) 3px,
                    transparent 3px,
                    transparent 50px);
            opacity: 0.6;
            z-index: -998;
        }

        /* Efek cahaya lebih realistis */
        .floor::after {
            content: "";
            position: absolute;
            top: 0;
            width: 100%;
            height: 80px;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.1), transparent);
            opacity: 0.2;
            z-index: -997;
        }




        .pos-container {
            background: #c19a6b;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.3);
            border: 3px solid #a07948;
            position: relative;
            display: inline-block;
            transform-origin: center;
            transition: transform 0.3s ease-in-out;
        }

        .pos-container:hover {
            transform: scale(1.05) rotate(-2deg);
        }

        .pos-container::after {
            content: "Tunggu Sebentar yah!! 📦";
            position: absolute;
            bottom: -15px;
            right: 10px;
            font-size: 12px;
            font-weight: bold;
            color: #773d00;
            background: #ffdead;
            padding: 3px 6px;
            border-radius: 3px;
            transform: rotate(-5deg);
            box-shadow: 0 2px 3px rgba(0, 0, 0, 0.2);
        }

        .glitch {
            font-size: 48px;
            font-weight: bold;
            color: #ff0000;
            text-shadow: 2px 2px 10px rgba(255, 0, 0, 0.7);
            animation: glitch 0.8s infinite alternate;
        }

        @keyframes glitch {
            0% {
                transform: translateX(-2px);
            }

            100% {
                transform: translateX(2px);
            }
        }

        .receipt {
            position: absolute;
            width: 200px;
            min-height: 280px;
            background: white;
            border-radius: 5px;
            box-shadow: 0px 0px 15px rgba(255, 204, 0, 0.8);
            opacity: 0.95;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            font-family: 'Courier New', Courier, monospace;
            font-size: 14px;
            color: #333;
            padding: 20px;
            border-bottom: 8px dashed #ffcc00;
            animation: drop 6s infinite ease-in-out;
        }

        .receipt::before {
            content: '🛍️ Ngolab POS';
            font-weight: bold;
            font-size: 16px;
            text-align: center;
            width: 100%;
            border-bottom: 2px solid #ddd;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }

        .receipt .item {
            display: flex;
            justify-content: space-between;
            width: 100%;
            margin: 4px 0;
        }

        .receipt .total {
            font-weight: bold;
            border-top: 2px dashed #333;
            margin-top: 15px;
            padding-top: 5px;
            width: 100%;
            text-align: right;
        }

        .receipt .barcode {
            width: 100%;
            height: 40px;
            background: repeating-linear-gradient(90deg,
                    black,
                    black 2px,
                    white 2px,
                    white 4px);
            margin-top: 15px;
        }

        @keyframes drop {
            0% {
                transform: translateY(-300px) rotate(0deg) scale(1);
                opacity: 0;
            }

            20% {
                transform: translateY(30vh) rotate(3deg) scale(1.05);
                opacity: 0.8;
            }

            50% {
                transform: translateY(60vh) rotate(-5deg) scale(1);
                opacity: 0.9;
            }

            100% {
                transform: translateY(90vh) rotate(2deg) scale(0.95);
                opacity: 0;
            }
        }

        .glitch {
            font-size: 5rem;
            font-weight: bold;
            color: black;
            position: relative;
            text-shadow: 0px 0px 10px rgba(0, 0, 0, 0.8);
            animation: glitch 2s infinite alternate;
        }

        @keyframes blink {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        #message {
            color: black;
            animation: fade-in 2s ease-in-out, blink 3s infinite;
        }

        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        #custom-cursor {
            position: absolute;
            width: 40px;
            height: 40px;
            pointer-events: none;
            background: url('https://cdn-icons-png.flaticon.com/512/12278/12278848.png') no-repeat center/contain;
            transform: rotate(180deg);
            z-index: 1000;
        }

        .product {
            position: absolute;
            font-size: 24px;
            animation: fall 2s linear forwards;
        }

        @keyframes fall {
            0% {
                transform: translateY(0) rotate(0deg);
                opacity: 1;
            }

            100% {
                transform: translateY(80vh) rotate(720deg);
                opacity: 0;
            }
        }
    </style>
</head>

<body class="bg-gray-900 text-white flex flex-col items-center justify-center h-screen text-center relative overflow-hidden">
    <div class="pos-container relative z-10">
        <div id="lock-icon" class="text-7xl mb-4 opacity-80 transition-all duration-500 animate-bounce">
            🛒
        </div>
        <h1 class="glitch">401</h1>
        <p class="text-xl mb-6" style="color: black;">Unauthorized Access - POS</p>
        <p id="message" class="text-lg opacity-0 translate-y-4 transition-all duration-700">
            Silahkan hubungi admin untuk mendapatkan akses ke sistem Point of Sale.
        </p>
    </div>
    <div id="custom-cursor"></div>
    <div class="shadow-bottom"></div>
    <div class="floor"></div>
    <div class="dirt-effect"></div>




    <script>
        document.addEventListener("DOMContentLoaded", function() {
            setTimeout(() => {
                document.getElementById("message").classList.remove("opacity-0", "translate-y-4");
            }, 1000);

            const cursor = document.getElementById("custom-cursor");
            document.addEventListener("mousemove", (e) => {
                cursor.style.left = `${e.clientX - cursor.offsetWidth / 2}px`; // Menempatkan kursor di tengah posisi mouse
                cursor.style.top = `${e.clientY - cursor.offsetHeight / 2}px`;
            });

            const itemNames = ["Sayur", "Buah-buahan", "ikan", "Makanan", "Minuman", "Masako", "Sambal", "Bumbu", "Kecap", "Saus", "Teh", "Kopi", "Gula", "Garam", "Merica", "Kecap", "Susu", "Roti", "Biskuit", "Kerupuk", "Mie", "Sambal", "Sarden", "Saus", "Sosis", "Telur", "Ayam", "Daging", "Ikan", "Udang", "Kerang", "Kepiting", "Kacang", "Ketan", "Tape", "Tahu", "Tempe", "Bakso", "Soto", "Bakmi", "Bakpao", "Martabak", "Pempek", "Pecel", "Gado-gado", "Sate", "Rendang", "Gulai", "Sop", "Bakwan", "Lumpia", "Pisang", "Coklat", "Permen", "Kue", "Es", "Jus", "Soda", "Air", "Kopi", "Teh", "Susu", "Coklat", "Vanili", "Strawberry", "Mangga", "Jeruk", "Apel", "Anggur", "Melon", "Semangka", "Nanas", "Pisang", "Durian", "Salak", "Kelapa", "Alpukat", "Pepaya", "Paprika", "Tomat", "Terong ", "Kacang", "Buncis", "Wortel", "Kol", "Bayam", "Kangkung", "Sawi", "Selada", "Ketimun", "Labu", "Labu", "Buncis"];

            for (let i = 0; i < 10; i++) {
                let receipt = document.createElement("div");
                receipt.classList.add("receipt");
                let total = 0;
                let items = "";
                for (let j = 0; j < 3; j++) {
                    let itemName = itemNames[Math.floor(Math.random() * itemNames.length)];
                    let price = (Math.random() * 10000 + 5000).toFixed(2);
                    total += parseFloat(price);
                    items += `<div class='item'><span>${itemName}</span><span>Rp${price}</span></div>`;
                }
                receipt.innerHTML = `
                    ${items}
                    <div class='total'>Total: Rp${total.toFixed(2)}</div>
                    <div class='barcode'></div>
                `;
                receipt.style.left = `${Math.random() * 80}vw`;
                receipt.style.top = "-150px";
                receipt.style.animationDuration = `${Math.random() * 10 + 4}s`;
                receipt.style.animationDelay = `${Math.random() * 3}s`;
                document.body.appendChild(receipt);
            }
        });

        document.addEventListener("DOMContentLoaded", function() {
            const cursor = document.getElementById("custom-cursor");
            const products = ["🥤", "🍞", "🍎", "🌶️", "🥕", "🧂"];

            document.addEventListener("mousemove", (e) => {
                cursor.style.left = `${e.clientX - cursor.offsetWidth / 2}px`;
                cursor.style.top = `${e.clientY - cursor.offsetHeight / 2}px`;

                // Buat elemen produk
                const product = document.createElement("div");
                product.classList.add("product");
                product.innerHTML = products[Math.floor(Math.random() * products.length)];
                product.style.left = `${e.clientX}px`;
                product.style.top = `${e.clientY}px`;
                document.body.appendChild(product);

                // Tambahkan efek jatuh
                product.animate([{
                        transform: "translateY(0) rotate(0deg)",
                        opacity: 1
                    },
                    {
                        transform: "translateY(80vh) rotate(720deg)",
                        opacity: 0
                    }
                ], {
                    duration: 2000,
                    easing: "ease-in-out",
                    fill: "forwards"
                });

                // Hapus produk setelah animasi selesai
                setTimeout(() => {
                    product.remove();
                }, 2000);
            });
        });
    </script>
</body>

</html>