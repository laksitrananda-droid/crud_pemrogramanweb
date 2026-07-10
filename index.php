<?php
include_once("config.php");

// 1. Cek apakah ada kata kunci yang diketik di kolom pencarian
$keyword = "";
if (isset($_GET['search'])) {
    $keyword = mysqli_real_escape_string($mysqli, $_GET['search']);
    // Query jika user melakukan pencarian (mencari berdasarkan nama alat ATAU merek ATAU lokasi)
    $query = "SELECT * FROM alat WHERE 
              nama_alat LIKE '%$keyword%' OR 
              merek LIKE '%$keyword%' OR 
              lokasi LIKE '%$keyword%' 
              ORDER BY id DESC";
} else {
    // Query default jika tidak ada pencarian
    $query = "SELECT * FROM alat ORDER BY id DESC";
}

$result = mysqli_query($mysqli, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIM RS - Data Alat Elektromedis</title>
    <style>
        /* Reset & Base Style */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
            color: #333;
            padding: 40px 20px;
        }
        
        /* Container */
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        /* Header */
        .main-header {
            margin-bottom: 25px;
            border-bottom: 2px solid #edf2f7;
            padding-bottom: 15px;
        }
        .main-header h2 {
            color: #2d3748;
            font-size: 24px;
            font-weight: 600;
        }

        /* Top Bar Actions (Tombol + Search Form) */
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 20px;
        }

        /* Action Button */
        .btn-tambah {
            display: inline-block;
            padding: 10px 20px;
            background-color: #10b981; 
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        .btn-tambah:hover {
            background-color: #059669;
            transform: translateY(-1px);
        }

        /* Search Form Style */
        .search-form {
            display: flex;
            gap: 8px;
        }
        .search-input {
            padding: 10px 15px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 14px;
            width: 250px;
            outline: none;
            transition: border-color 0.2s;
        }
        .search-input:focus {
            border-color: #3b82f6;
        }
        .btn-search {
            padding: 10px 20px;
            background-color: #3b82f6;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: background-color 0.2s;
        }
        .btn-search:hover {
            background-color: #2563eb;
        }
        .btn-reset {
            padding: 10px 15px;
            background-color: #64748b;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
            transition: background-color 0.2s;
        }
        .btn-reset:hover {
            background-color: #475569;
        }

        /* Table Design */
        .table-responsive {
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            margin-top: 5px;
        }
        
        /* Table Header */
        th {
            background-color: #f8fafc;
            color: #4a5568;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            padding: 16px;
            border-bottom: 2px solid #e2e8f0;
        }

        /* Table Body */
        td {
            padding: 16px;
            border-bottom: 1px solid #edf2f7;
            color: #4a5568;
            font-size: 15px;
        }
        
        /* Zebra Striping & Hover Effect */
        tr:nth-child(even) td {
            background-color: #fcfdfd;
        }
        tr:hover td {
            background-color: #f1f5f9;
        }

        /* Action Links Inside Table */
        .actions a {
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            padding: 4px 8px;
            border-radius: 4px;
            transition: all 0.2s;
        }
        .actions .edit {
            color: #3b82f6; 
            background-color: #eff6ff;
            margin-right: 5px;
        }
        .actions .edit:hover {
            background-color: #dbeafe;
        }
        .actions .delete {
            color: #ef4444; 
            background-color: #fef2f2;
        }
        .actions .delete:hover {
            background-color: #fee2e2;
        }

        /* Footer Style */
        .main-footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #edf2f7;
            text-align: center;
            font-size: 13px;
            color: #a0aec0;
        }
        .main-footer strong {
            color: #4a5568;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="main-header">
        <h2>Sistem Informasi Manajemen RS - Data Alat</h2>
    </div>

    <div class="top-bar">
        <a href="add.php" class="btn-tambah">+ Tambah Alat Baru</a>
        
        <form action="index.php" method="GET" class="search-form">
            <input type="text" name="search" class="search-input" placeholder="Cari nama alat, merek, atau lokasi..." value="<?php echo htmlspecialchars($keyword); ?>">
            <button type="submit" class="btn-search">Cari</button>
            <?php if ($keyword != ""): ?>
                <a href="index.php" class="btn-reset">Reset</a>
            <?php endif; ?>
        </form>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Nama Alat</th>
                    <th>Tahun</th>
                    <th>Merek</th>
                    <th>Lokasi</th>
                    <th style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while($user_data = mysqli_fetch_array($result)) {
                        echo "<tr>";
                        echo "<td><strong>".$user_data['nama_alat']."</strong></td>";
                        echo "<td>".$user_data['tahun']."</td>";
                        echo "<td>".$user_data['merek']."</td>";
                        echo "<td>".$user_data['lokasi']."</td>";
                        echo "<td class='actions' style='text-align: center;'>
                                <a href='edit.php?id=$user_data[id]' class='edit'>Edit</a>
                                <a href='delete.php?id=$user_data[id]' class='delete' onclick='return confirm(\"Yakin ingin menghapus data ini?\")'>Delete</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' style='text-align: center; color: #a0aec0; padding: 30px;'>Data tidak ditemukan atau belum ada.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="main-footer">
        Aplikasi dikembangkan oleh: <strong>Nanda Laksitra Juang_2202505060</strong>
    </div>
</div>

</body>
</html>