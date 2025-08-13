<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Utama</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
        }
        .welcome {
            color: #2c3e50;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Selamat Datang di Website Kami</h1>
        
        <?php
        // Contoh kode PHP sederhana
        date_default_timezone_set('Asia/Jakarta');
        $waktu = date('H:i:s');
        $tanggal = date('d F Y');
        $nama = "Pengunjung";
        
        echo "<p class='welcome'>Halo $nama! Selamat datang di website kami.</p>";
        echo "<p>Sekarang pukul $waktu, tanggal $tanggal</p>";
        ?>
        
        <h2>Fitur Website</h2>
        <ul>
            <li>Halaman responsif</li>
            <li>Integrasi PHP dengan HTML</li>
            <li>Tampilan modern</li>
        </ul>
        
        <?php
        // Contoh penggunaan kondisi
        $jam = (int)date('H');
        if ($jam < 12) {
            echo "<p>Selamat pagi!</p>";
        } elseif ($jam < 15) {
            echo "<p>Selamat siang!</p>";
        } elseif ($jam < 18) {
            echo "<p>Selamat sore!</p>";
        } else {
            echo "<p>Selamat malam!</p>";
        }
        ?>
    </div>
</body>
</html>