<?php
// Mengambil koneksi database dari config.php
include_once("config.php");

// Mengambil semua data dari tabel 'alat'
$result = mysqli_query($mysqli, "SELECT * FROM alat ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sim Rs - Data Alat</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        /* Style Tombol Tambah Alat (Hiaju) */
        .btn-tambah {
            display: inline-block;
            background-color: #28a745;
            color: white;
            padding: 8px 15px;
            text-decoration: none;
            border-radius: 4px;
            margin-bottom: 15px;
            font-size: 14px;
            font-weight: bold;
        }
        .btn-tambah:hover {
            background-color: #218838;
        }
        /* Style Tabel Oranye Kamu */
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #333;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f39c12; /* Warna Oranye */
            color: white;
        }
        .btn-aksi {
            text-decoration: none;
            color: #007bff;
        }
    </style>
</head>
<body>

    <a href="add.php" class="btn-tambah">+ Tambah Alat Baru</a>

    <table>
        <thead>
            <tr>
                <th>Nama Alat</th>
                <th>Tahun</th>
                <th>Merek</th>
                <th>Lokasi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            // Cek apakah ada data di database
            if ($result && mysqli_num_rows($result) > 0) {
                while($user_data = mysqli_fetch_array($result)) {         
                    echo "<tr>";
                    echo "<td>".$user_data['nama_alat']."</td>";
                    echo "<td>".$user_data['tahun']."</td>";
                    echo "<td>".$user_data['merek']."</td>";    
                    echo "<td>".$user_data['lokasi']."</td>";    
                    echo "<td>
                            <a href='edit.php?id=".$user_data['id']."' class='btn-aksi'>Edit</a> | 
                            <a href='delete.php?id=".$user_data['id']."' class='btn-aksi' style='color: red;' onclick='return confirm(\"Hapus data?\")'>Hapus</a>
                          </td>";
                    echo "</tr>";        
                }
            } else {
                // Jika database kosong, tampilkan baris ini
                echo "<tr><td colspan='5' style='text-align:center; color:#888; padding: 20px;'>Belum ada data alat kesehatan. Klik tombol di atas untuk mengisi.</td></tr>";
            }
            ?>
        </tbody>
    </table>

</body>
</html>