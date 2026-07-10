<?php
include_once("config.php");

// 1. Cek apakah ada kata kunci yang diketik di kolom pencarian
$keyword = "";
if (isset($_GET['search'])) {
    $keyword = mysqli_real_escape_string($mysqli, $_GET['search']);
    // Query jika user melakukan pencarian
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Reset & Base Style */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #eef2f7; /* Background abu-biru lembut */
            color: #333;
            padding: 40px 20px;
        }
        
        /* Container */
        .container {
            max-width: 1100px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        /* Header */
        .main-header {
            margin-bottom: 25px;
            border-bottom: 3px solid #3b82f6; /* Garis bawah biru */
            padding-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .main-header h2 {
            color: #1e3a8a; /* Biru gelap modern */
            font-size: 24px;
            font-weight: 700;
        }
        .main-header i {
            color: #3b82f6;
            font-size: 26px;
        }

        /* Top Bar Actions */
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
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background-color: #2563eb; /* Biru cerah */
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.2s ease;
            box-shadow: 0 2px 5px rgba(37, 99, 235, 0.2);
        }
        .btn-tambah:hover {
            background-color: #1d4ed8;
            transform: translateY(-1px);
        }

        /* Search Form Style */
        .search-form {
            display: flex;
            gap: 8px;
        }
        .search-input {
            padding: 10px 15px;
            border: 2px solid #cbd5e1;
            border-radius: 6px;
            font-size: 14px;
            width: 280px;
            outline: none;
            transition: border-color 0.2s;
        }
        .search-input:focus {
            border-color: #2563eb;
        }
        .btn-search {
            padding: 10px 20px;
            background-color: #1e3a8a;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: background-color 0.2s;
        }
        .btn-search:hover {
            background-color: #172554;
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
            border-radius: 8px;
            overflow: hidden;
        }
        
        /* Table Header Berwarna Biru Maskulin */
        th {
            background-color: #1e40af; /* Royal Blue */
            color: white;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            padding: 16px;
            letter-spacing: 0.5px;
        }

        /* Table Body & Grid */
        td {
            padding: 16px;
            border-bottom: 1px solid #e2e8f0;
            color: #334155;
            font-size: 15px;
        }
        
        /* Zebra Striping (Variasi Biru Lembut) */
        tr:nth-child(even) td {
            background-color: #f8fafc;
        }
        tr:hover td {
            background-color: #e0f2fe; /* Highlight biru muda saat di-hover */
        }

        /* Kolom Ikon Medis */
        .alat-name-wrapper {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .alat-icon {
            background-color: #dbeafe;
            color: #2563eb;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 14px;
        }

        /* Action Links Inside Table */
        .actions a {
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 6px;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .actions .edit {
            color: #2563eb; 
            background-color: #eff6ff;
            border: 1px solid #bfdbfe;
            margin-right: 5px;
        }
        .actions .edit:hover {
            background-color: #dbeafe;
        }
        .actions .delete {
            color: #dc2626; 
            background-color: #fef2f2;
            border: 1px solid #fca5a5;
        }
        .actions .delete:hover {
            background-color: #fee2e2;
        }

        /* Footer Style */
        .main-footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #e2e8f0;
            text-align: center;
            font-size: 13px;
            color: #64748b;
        }
        .main-footer strong {
            color: #0f172a;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="main-header">
        <i class="fa-solid fa-heart-pulse"></i> <h2>Sistem Informasi Manajemen RS - Data Alat Elektromedis</h2>
    </div>

    <div class="top-bar">
        <a href="add.php" class="btn-tambah">
            <i class="fa-solid fa-plus"></i> Tambah Alat Baru
        </a>
        
        <form action="index.php" method="GET" class="search-form">
            <input type="text" name="search" class="search-input" placeholder="Cari nama alat, merek, atau lokasi..." value="<?php echo htmlspecialchars($keyword); ?>">
            <button type="submit" class="btn-search">
                <i class="fa-solid fa-magnifying-glass"></i> Cari
            </button>
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
                        echo "<td>
                                <div class='alat-name-wrapper'>
                                    <div class='alat-icon'><i class='fa-solid fa-stethoscope'></i></div> <strong>".$user_data['nama_alat']."</strong>
                                </div>
                              </td>";
                        echo "<td><i class='fa-regular fa-calendar-days' style='color:#64748b; margin-right:5px;'></i>".$user_data['tahun']."</td>";
                        echo "<td><i class='fa-solid fa-microchip' style='color:#64748b; margin-right:5px;'></i>".$user_data['merek']."</td>";
                        echo "<td><i class='fa-solid fa-hospital-user' style='color:#64748b; margin-right:5px;'></i>".$user_data['lokasi']."</td>";
                        echo "<td class='actions' style='text-align: center;'>
                                <a href='edit.php?id=$user_data[id]' class='edit'><i class='fa-solid fa-pen-to-square'></i> Edit</a>
                                <a href='delete.php?id=$user_data[id]' class='delete' onclick='return confirm(\"Yakin ingin menghapus data ini?\")'><i class='fa-solid fa-trash'></i> Delete</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' style='text-align: center; color: #a0aec0; padding: 40px;'><i class='fa-solid fa-folder-open' style='font-size: 24px; display:block; margin-bottom:10px;'></i>Data tidak ditemukan atau belum ada.</td></tr>";
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