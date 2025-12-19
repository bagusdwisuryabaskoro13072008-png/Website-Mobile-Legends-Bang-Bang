<?php 
require 'config.php'; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login & Register</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <div class="card p-4 shadow-lg">
    <h3 class="text-center mb-4 text-primary fw-bold">Portal Login & Register</h3>

    <div class="mb-3">
      <label for="roleSelect" class="form-label">Login sebagai</label>
      <select id="roleSelect" class="form-select">
        <option value="">-- Pilih Role --</option>
        <option value="admin">Admin</option>
        <option value="user">User</option>
      </select>
    </div>

    <!-- ================= FORM ADMIN ================= -->
    <div id="adminForm" style="display:none;">
      <h5 class="text-secondary">Login Admin</h5>
      <form action="process_login.php" method="post">
        <input type="hidden" name="role" value="admin">
        <div class="mb-2">
          <label>Email</label>
          <input name="email" type="email" class="form-control" required>
        </div>
        <div class="mb-2">
          <label>Password</label>
          <input name="password" type="password" class="form-control" required>
        </div>
        <button class="btn btn-primary w-100">Masuk sebagai Admin</button>
      </form>
    </div>

    <!-- ================= FORM USER ================= -->
    <div id="userForm" style="display:none;">
      <h5 class="text-secondary">Daftar User Baru</h5>
      <form id="regForm" action="register_user.php" method="post">
        <input type="hidden" name="role" value="user">
        <div class="row">
          <div class="col-md-6 mb-2"><input name="nama" class="form-control" placeholder="Nama lengkap" required></div>
          <div class="col-md-6 mb-2">
            <select name="jenis_kelamin" class="form-select" required>
              <option value="">Jenis kelamin</option>
              <option value="Laki-laki">Laki-laki</option>
              <option value="Perempuan">Perempuan</option>
            </select>
          </div>
        </div>
        <div class="mb-2"><input name="email" class="form-control" placeholder="Email" type="email" required></div>
        <div class="mb-2"><input name="no_id" class="form-control" placeholder="No ID (contoh: NIK)" required></div>
        <div class="mb-2">
          <select name="asal" class="form-select">
            <option value="">Asal</option>
            <option value="Kota A">Kota A</option>
            <option value="Kota B">Kota B</option>
          </select>
        </div>
        <div class="mb-2"><input name="password" type="password" class="form-control" placeholder="Password" required></div>
        <div class="mb-2 form-check">
          <input type="checkbox" name="ingat" class="form-check-input" id="ingat">
          <label class="form-check-label" for="ingat">Ingatkan saya</label>
        </div>
        <button class="btn btn-success w-100">Daftar (User)</button>
      </form>

      <hr class="my-3">
      <h5 class="text-secondary">Login User</h5>
      <form action="process_login.php" method="post">
        <input type="hidden" name="role" value="user">
        <div class="mb-2"><input name="email" class="form-control" placeholder="Email" type="email" required></div>
        <div class="mb-2"><input name="password" class="form-control" placeholder="Password" type="password" required></div>
        <button class="btn btn-primary w-100">Masuk sebagai User</button>
      </form>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/script.js"></script>
</body>
</html>
