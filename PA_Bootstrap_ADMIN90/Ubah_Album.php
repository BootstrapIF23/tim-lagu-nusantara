<?php
require 'koneksi.php';

// Pastikan id_album ada dan valid
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_album = $_GET['id'];

    // Mengambil data album dari database
    $query = "SELECT * FROM album WHERE id_album = '$id_album'";
    $result = mysqli_query($conn, $query);
    $album = mysqli_fetch_assoc($result);

    if (!$album) {
        echo "<script>alert('Album tidak ditemukan!'); window.location.href = 'Album_admin.php';</script>";
        exit;
    }

    // Proses form submission untuk mengubah album
    if (isset($_POST['ubah_album'])) {
        $nama_album = $_POST['album'];
        $asal_daerah = $_POST['asal_daerah'];
        $tanggal_rilis = $_POST['tanggal'];

        // Cek jika ada file sampul baru yang diunggah
        if (!empty($_FILES['sampul']['name'])) {
            $sampul_album = $_FILES['sampul']['name'];
            $sampul_tmp = $_FILES['sampul']['tmp_name'];
            $upload_dir = 'sampul/';
            $sampul_path = $upload_dir . $sampul_album;

            // Hapus file sampul lama jika ada
            if (file_exists($upload_dir . $album['sampul_album'])) {
                unlink($upload_dir . $album['sampul_album']);
            }

            // Pindahkan file yang diunggah
            if (move_uploaded_file($sampul_tmp, $sampul_path)) {
                $sql = "UPDATE album SET nama_album='$nama_album', asal_daerah='$asal_daerah', tanggal_rilis='$tanggal_rilis', sampul_album='$sampul_album' WHERE id_album ='$id_album'";
            } else {
                echo "<script>alert('Gagal mengunggah sampul album.');</script>";
                exit;
            }
        } else {
            // Update tanpa mengubah sampul album
            $sql = "UPDATE album SET nama_album='$nama_album', asal_daerah='$asal_daerah', tanggal_rilis='$tanggal_rilis' WHERE id_album='$id_album'";
        }

        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Album berhasil diperbarui!'); window.location.href = 'Album_admin.php'</script>";
        } else {
            echo "<script>alert('Gagal memperbarui album: " . mysqli_error($conn) . "');</script>";
        }
    }
} else {
    echo "<script>alert('ID Album tidak ditemukan!'); window.location.href = 'daftar_album.php';</script>";
    exit;
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Album</title>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/tambah_album.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <nav class="sidebar">
            <div class="sidebar-up">
                <button class="logo-btn" onclick="toggleSidebar()">
                    <img src="Logo/logo.png" alt="logo" class="logo">
                </button>
                <button class="icon-btn">
                    <i class="fa-solid fa-plus-circle" style="font-size: 35px; color: #110905;"></i>
                    <span class="icon-text">Tambah</span>
                </button>
                <button class="icon-btn">
                    <i class="fa-solid fa-music" style="font-size: 30px; color: #110905;"></i>
                    <span class="icon-text">Album</span>
                </button>
            </div>
            <div class="sidebar-logout">
                <button class="icon-btn">
                    <i class="fa-solid fa-right-from-bracket" style="font-size: 35px; color: #110905;"></i>
                    <span class="icon-text">Keluar</span>
                </button>
            </div>
            
        </nav>
        

        <!-- Main Content -->
        <div class="main-content">
            <!-- Navbar -->
            <nav class="navbar">
                <div class="navbar-left">
                    <button class="arrow"><i class="fa-solid fa-chevron-left" style="font-size: 17px; color: #110905; background : #a88f6b"></i></button>
                    <button class="arrow"><i class="fa-solid fa-chevron-right" style="font-size: 17px; color: #110905; background : #a88f6b"></i></button>
                </div>
                <h1 style="color: #a88f6B; font-size:32px; margin-top: 7px;">Ubah Album</h1>
                <div class="account">
                    <img src="Logo/logo.png" alt="Profile" class="profile-img">
                    <span>Admin</span>
                </div>



            </nav>
            <!-- Main Content Body -->
            <div class="main-content-body">
                <main class="form">
                    <form action="" method="POST" enctype="multipart/form-data">
                    <div class="form-section">
                        <label for="album">Nama Album</label>
                        <!-- Pastikan $album ada sebelum mengakses data -->
                        <input type="text" id="album" name="album" value="<?php echo isset($album['nama_album']) ? $album['nama_album'] : ''; ?>" placeholder="Nama Album" required>
                        
                        <label for="asal_daerah">Asal Daerah</label>
                        <input type="text" id="asal_daerah" name="asal_daerah" value="<?php echo isset($album['asal_daerah']) ? $album['asal_daerah'] : ''; ?>" placeholder="Asal daerah" required>

                        <label for="tanggal">Tanggal Rilis</label>
                        <div class="custom-date">
                            <input type="date" id="tanggal" name="tanggal" value="<?php echo isset($album['tanggal_rilis']) ? $album['tanggal_rilis'] : ''; ?>" required>
                            <span class="calendar-icon"></span>
                        </div>
                        
                        <label for="sampul">Unggah Foto Sampul Album</label>
                        <div class="file-upload">
                            <label for="sampul" class="upload-btn">Unggah foto</label>
                            <span class="file-name">Tidak ada file dipilih</span>
                            <input type="file" id="sampul" name="sampul" accept=".jpeg" onchange="showFileName(this)">
                        </div>
                    </div>
                    <div class="form-buttons">
                        <button type="submit" class="btn" name="ubah_album">Ubah</button>
                        <button type="button" class="btn">Batal</button>
                    </div>
                </form>

                </main>
                
            </div> 
        </div>





        
    </div>
    <script src="js/home.js" defer></script>
</body>
</html>
