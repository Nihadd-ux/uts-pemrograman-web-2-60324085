<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah Kategori - UTS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php
    require_once 'config/database.php';

    $errors = [];
    $kode = '';
    $nama = '';
    $deskripsi = '';
    $status = 'Aktif';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $kode      = trim(htmlspecialchars($_POST['kode_kategori']));
        $nama      = trim(htmlspecialchars($_POST['nama_kategori']));
        $deskripsi = trim(htmlspecialchars($_POST['deskripsi']));
        $status    = trim(htmlspecialchars($_POST['status']));

        if (empty($kode)) {
            $errors[] = "Kode kategori wajib diisi.";
        } elseif (strlen($kode) < 4 || strlen($kode) > 10) {
            $errors[] = "Kode kategori harus 4-10 karakter.";
        } elseif (substr($kode, 0, 4) !== 'KAT-') {
            $errors[] = "Kode kategori harus diawali 'KAT-'.";
        } else {
            $cek = $conn->prepare("SELECT id_kategori FROM kategori WHERE kode_kategori = ?");
            $cek->bind_param("s", $kode);
            $cek->execute();
            $cek->store_result();
            if ($cek->num_rows > 0) {
                $errors[] = "Kode kategori sudah digunakan, gunakan kode lain.";
            }
            $cek->close();
        }

        if (empty($nama)) {
            $errors[] = "Nama kategori wajib diisi.";
        } elseif (strlen($nama) < 3) {
            $errors[] = "Nama kategori minimal 3 karakter.";
        } elseif (strlen($nama) > 50) {
            $errors[] = "Nama kategori maksimal 50 karakter.";
        }

        if (!empty($deskripsi) && strlen($deskripsi) > 200) {
            $errors[] = "Deskripsi maksimal 200 karakter.";
        }

        if (!in_array($status, ['Aktif', 'Nonaktif'])) {
            $errors[] = "Status tidak valid.";
        }

        if (empty($errors)) {
            $stmt = $conn->prepare("INSERT INTO kategori (kode_kategori, nama_kategori, deskripsi, status) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $kode, $nama, $deskripsi, $status);

            if ($stmt->execute()) {
                header("Location: index.php?pesan=Kategori berhasil ditambahkan");
                exit();
            } else {
                $errors[] = "Gagal menyimpan data, coba lagi.";
            }
            $stmt->close();
        }
    }
    ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Tambah Kategori Baru</h4>
                    </div>
                    <div class="card-body">

                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    <?php foreach ($errors as $error): ?>
                                        <li><?= $error ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form method="POST">

                            <div class="mb-3">
                                <label class="form-label">Kode Kategori <span class="text-danger">*</span></label>
                                <input type="text" name="kode_kategori" class="form-control"
                                    value="<?= $kode ?>" placeholder="Contoh: KAT-001" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                                <input type="text" name="nama_kategori" class="form-control"
                                    value="<?= $nama ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="deskripsi" class="form-control" rows="3"><?= $deskripsi ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Status</label><br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status" value="Aktif"
                                        <?= $status == 'Aktif' ? 'checked' : '' ?>>
                                    <label class="form-check-label">Aktif</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status" value="Nonaktif"
                                        <?= $status == 'Nonaktif' ? 'checked' : '' ?>>
                                    <label class="form-check-label">Nonaktif</label>
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <a href="index.php" class="btn btn-secondary">Kembali</a>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>