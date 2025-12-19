<?php
include 'config.php';
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id'])) { header("Location: index.php"); exit; }

$user_id = $_SESSION['user_id'];
$data = mysqli_query($conn, "
  SELECT s.*, IF(l.id IS NULL, 0, 1) AS loved
  FROM skins s
  LEFT JOIN skin_love l ON s.id = l.skin_id AND l.user_id='$user_id'
  WHERE s.user_id='$user_id'
  ORDER BY s.created_at DESC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Galeri Skin - ML Gallery</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<style>
body {
  background: radial-gradient(circle at top left, #0a0a0a, #1a1a2e);
  color: #fff;
  font-family: 'Poppins', sans-serif;
  overflow-x: hidden;
}
.btn-custom {
  background: linear-gradient(90deg, #0ff, #09f);
  border: none;
  color: #000;
  font-weight: bold;
  transition: all 0.3s ease;
}
.btn-custom:hover {
  background: linear-gradient(90deg, #09f, #0ff);
  color: #fff;
}
.gallery-container {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  gap: 25px;
  padding: 40px;
}
.image-container {
  position: relative;
  border-radius: 15px;
  overflow: hidden;
  cursor: pointer;
  aspect-ratio: 9 / 16;
  box-shadow: 0 0 20px rgba(0,255,255,0.25),
              inset 0 0 15px rgba(0,255,255,0.15);
  transition: transform 0.3s ease, box-shadow 0.4s ease;
}
.image-container:hover {
  transform: scale(1.05);
  box-shadow: 0 0 50px white, inset 0 0 20px rgba(255,255,255,0.2);
}
.image-container img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 15px;
}
.image-container.shine::before,
.image-container.shine::after {
  content: '';
  position: absolute;
  top: -30%;
  left: -150%;
  width: 200%;
  height: 160%;
  pointer-events: none;
  filter: blur(25px);
  opacity: 0.8;
  border-radius: 15px;
}
.image-container.shine::before {
  background: linear-gradient(90deg,
    rgba(255,0,0,0.25) 0%,
    rgba(255,165,0,0.4) 25%,
    rgba(0,255,0,0.4) 50%,
    rgba(0,255,255,0.4) 75%,
    rgba(0,0,255,0.3) 100%);
  animation: rainbowShine 12s ease-in-out infinite;
}
.image-container.shine::after {
  background: linear-gradient(90deg,
    rgba(255,255,255,0) 0%,
    rgba(255,255,255,0.3) 30%,
    rgba(255,255,255,0.9) 50%,
    rgba(255,255,255,0.3) 70%,
    rgba(255,255,255,0) 100%);
  animation: whiteShine 12s ease-in-out infinite;
}
@keyframes rainbowShine {
  0% { transform: translateX(-150%) scaleX(1.2); opacity: 0.9; }
  30% { transform: translateX(150%) scaleX(1.2); opacity: 1; }
  33% { opacity: 0; }
  100% { opacity: 0; transform: translateX(150%); }
}
@keyframes whiteShine {
  0% { transform: translateX(-150%) scaleX(1.5); opacity: 0; }
  35% { opacity: 0; }
  40% { transform: translateX(-150%) scaleX(1.5); opacity: 1; }
  45% { transform: translateX(150%) scaleX(1.5); opacity: 1; }
  46% { opacity: 0; }
  50% { transform: translateX(-150%) scaleX(1.5); opacity: 1; }
  55% { transform: translateX(150%) scaleX(1.5); opacity: 1; }
  56% { opacity: 0; }
  80% { opacity: 0; }
  85% { transform: translateX(150%) scaleX(1.5); opacity: 1; }
  90% { transform: translateX(-150%) scaleX(1.5); opacity: 1; }
  91% { opacity: 0; }
  100% { opacity: 0; }
}
@keyframes glowPulse {
  0%, 5% {
    box-shadow: 0 0 15px rgba(0,255,255,0.2),
                0 0 30px rgba(0,255,255,0.1),
                inset 0 0 10px rgba(0,255,255,0.15);
  }
  10%, 30% {
    box-shadow: 0 0 25px rgba(255,0,150,0.4),
                0 0 50px rgba(0,255,255,0.3),
                inset 0 0 20px rgba(255,255,255,0.25);
  }
  40%, 55% {
    box-shadow: 0 0 30px rgba(255,255,255,0.6),
                0 0 60px rgba(0,200,255,0.4),
                inset 0 0 30px rgba(255,255,255,0.3);
  }
  80% {
    box-shadow: 0 0 20px rgba(0,255,255,0.25),
                0 0 40px rgba(0,255,255,0.2),
                inset 0 0 20px rgba(0,255,255,0.15);
  }
  100% {
    box-shadow: 0 0 15px rgba(0,255,255,0.2),
                inset 0 0 10px rgba(0,255,255,0.15);
  }
}
.image-container.shine {
  animation: glowPulse 6s ease-in-out infinite;
}

/* ===== POPUP ===== */
.popup {
  position: fixed;
  top: 0; left: 0;
  width: 100vw; height: 100vh;
  background: rgba(0,0,0,0.8);
  display: none;
  justify-content: center;
  align-items: center;
  z-index: 999;
}
.popup.active { display: flex; }
.popup-content {
  background: rgba(10,10,30,0.95);
  border: 2px solid #0ff;
  border-radius: 20px;
  padding: 25px;
  color: #fff;
  text-align: center;
  max-width: 400px;
  position: relative;
  animation: popupShow 0.5s ease;
}
.popup-content.active {
  animation: colorGlow 6s linear infinite, popupShow 0.5s ease;
  box-shadow: 0 0 50px 10px #ff0000;
}
.popup-content.active::before {
  content: '';
  position: absolute;
  top: -2px; left: -2px; right: -2px; bottom: -2px;
  background: linear-gradient(270deg, red, orange, yellow, lime, cyan, blue, violet, magenta, red);
  background-size: 800% 800%;
  border-radius: 20px;
  filter: blur(12px);
  animation: borderMove 6s linear infinite;
  z-index: -1;
}
@keyframes popupShow { from {transform: scale(0.7);opacity:0;} to {transform: scale(1);opacity:1;} }
@keyframes colorGlow {
  0% { box-shadow: 0 0 50px 10px #ff0000; }
  15% { box-shadow: 0 0 50px 10px #ff9900; }
  30% { box-shadow: 0 0 50px 10px #ffff00; }
  45% { box-shadow: 0 0 50px 10px #00ff00; }
  60% { box-shadow: 0 0 50px 10px #00ffff; }
  75% { box-shadow: 0 0 50px 10px #0000ff; }
  90% { box-shadow: 0 0 50px 10px #ff00ff; }
  100% { box-shadow: 0 0 50px 10px #ff0000; }
}
@keyframes borderMove {
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}
.popup-content img {
  width: 100%;
  border-radius: 10px;
  margin-bottom: 15px;
}
.icon-bar {
  display: flex;
  justify-content: center;
  gap: 25px;
  margin-top: 10px;
}
.icon-bar i {
  font-size: 28px;
  cursor: pointer;
  transition: transform 0.3s, color 0.3s;
}
.icon-bar i:hover { transform: scale(1.2); }
.icon-love { color: #999; }
.icon-love.active { color: #ff3b9b; text-shadow: 0 0 15px #ff3b9b; }
.icon-like.active { color: #00ffff; text-shadow: 0 0 15px #0ff; }
</style>
</head>
<body>

<div class="container text-center mt-4">
  <h2 class="text-info mb-3">Galeri Skin Anda</h2>
  <a href="skins.php" class="btn btn-custom mb-4">← Kembali ke Data Skin</a>
</div>

<div class="gallery-container">
<?php
if (mysqli_num_rows($data) > 0) {
  while ($row = mysqli_fetch_assoc($data)) {
    $lovedClass = ($row['loved'] == 1) ? 'shine' : '';
echo "
<div class='image-container $lovedClass' 
     data-id='{$row['id']}'
     data-loved='{$row['loved']}'
     data-nama='{$row['nama']}'
     data-subname='{$row['subname']}'
     data-tanggal='{$row['tanggal']}'
     data-keterangan='{$row['keterangan']}'
     data-gambar='{$row['file_path']}'>
  <img src='{$row['file_path']}' alt='{$row['nama']}'>
</div>";
  }
} else {
  echo "<p class='text-center text-secondary'>Belum ada data skin ditambahkan.</p>";
}
?>
</div>

<!-- Popup -->
<div class="popup" id="popup">
  <div class="popup-content" id="popupContent">
    <img id="popup-img" src="" alt="Preview">
    <h4 id="popup-nama"></h4>
    <p id="popup-sub"></p>
    <p id="popup-tanggal" class="text-info"></p>
    <p id="popup-ket" class="text-light"></p>

    <div class="icon-bar">
      <i id="likeBtn" class="fa-solid fa-thumbs-up icon-like"></i>
      <i id="loveBtn" class="fa-solid fa-heart icon-love"></i>
    </div>

    <button class="btn btn-outline-info mt-3" onclick="closePopup()">Tutup</button>
  </div>
</div>

<script>
const cards = document.querySelectorAll('.image-container');
const popup = document.getElementById('popup');
const popupContent = document.getElementById('popupContent');
const popupImg = document.getElementById('popup-img');
const popupNama = document.getElementById('popup-nama');
const popupSub = document.getElementById('popup-sub');
const popupTanggal = document.getElementById('popup-tanggal');
const popupKet = document.getElementById('popup-ket');
const loveBtn = document.getElementById('loveBtn');
const likeBtn = document.getElementById('likeBtn');
let currentCard = null;
let lovedCards = new Set();

// Inisialisasi data-loved
cards.forEach(card => {
  if (card.dataset.loved === "1") lovedCards.add(card.dataset.id);
});

// Klik gambar → buka popup
cards.forEach(card => {
  card.addEventListener('click', () => {
    popupImg.src = card.dataset.gambar;
    popupNama.textContent = card.dataset.nama;
    popupSub.textContent = "Subname: " + (card.dataset.subname || '-');
    popupTanggal.textContent = "Tanggal: " + (card.dataset.tanggal || '-');
    popupKet.textContent = "Keterangan: " + (card.dataset.keterangan || '-');
    popup.classList.add('active');
    popupContent.classList.add('active');
    currentCard = card;
    loveBtn.classList.toggle('active', lovedCards.has(card.dataset.id));
    likeBtn.classList.remove('active');
  });
});

// Klik Love (AJAX)
loveBtn.addEventListener('click', () => {
  if (!currentCard) return;
  const id = currentCard.dataset.id;
  fetch('toggle_love.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: 'skin_id=' + encodeURIComponent(id)
  })
  .then(res => res.text())
  .then(res => {
    if (res === 'loved') {
      lovedCards.add(id);
      currentCard.classList.add('shine');
      loveBtn.classList.add('active');
    } else if (res === 'unloved') {
      lovedCards.delete(id);
      currentCard.classList.remove('shine');
      loveBtn.classList.remove('active');
    }
  })
  .catch(console.error);
});

// Fungsi Tutup Popup
function closePopup() {
  popup.classList.remove('active');
  popupContent.classList.remove('active');
}

// Klik luar popup → tutup
popup.addEventListener('click', (e) => {
  if (e.target === popup) closePopup();
});
</script>
</body>
</html>
