<?php 
include_once("config.php"); 
 
if(isset($_POST['update'])) { 
    $id = $_POST['id']; 
    $nama_alat = $_POST['nama_alat']; 
    $tahun = $_POST['tahun']; 
    $merek = $_POST['merek']; 
    $lokasi = $_POST['lokasi']; 
 
    $result = mysqli_query($mysqli, "UPDATE alat SET nama_alat='$nama_alat', 
tahun='$tahun', merek='$merek', lokasi='$lokasi' WHERE id=$id"); 
    header("Location: index.php"); 
    exit(); 
} 
 
$id = $_GET['id']; 
$result = mysqli_query($mysqli, "SELECT * FROM alat WHERE id=$id"); 
 
while($user_data = mysqli_fetch_array($result)) { 
    $nama_alat = $user_data['nama_alat']; 
    $tahun = $user_data['tahun']; 
    $merek = $user_data['merek']; 
    $lokasi = $user_data['lokasi']; 
} 
?>