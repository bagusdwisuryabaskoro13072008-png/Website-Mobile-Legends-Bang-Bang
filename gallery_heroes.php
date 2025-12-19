<?php
include 'config.php';
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id'])) { header("Location: index.php"); exit; }

$user_id = $_SESSION['user_id'];

// Query heroes + status love user
$data = mysqli_query($conn, "
  SELECT h.*, IF(l.id IS NULL, 0, 1) AS loved
  FROM heroes h
  LEFT JOIN hero_love l ON h.id = l.hero_id AND l.user_id='$user_id'
  WHERE h.user_id='$user_id'
  ORDER BY h.created_at DESC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Galeri Heroes</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
body {
  background: radial-gradient(circle at center, #101020, #050510 80%);
  color: #fff;
  font-family: 'Poppins', sans-serif;
  overflow-x: hidden;
}
.gallery-container {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  gap: 20px;
  padding: 30px;
}
.image-container {
  position: relative;
  border-radius: 15px;
  overflow: hidden;
  aspect-ratio: 9/16;
  cursor: pointer;
  box-shadow: 0 0 25px rgba(0,255,255,0.4);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.image-container:hover { transform: scale(1.05); }
.image-container img {
  width: 100%; height: 100%;
  object-fit: cover; border-radius: 15px; display: block;
}

/* Tombol love */
.icon-love {
  position: absolute;
  bottom: 10px; right: 10px;
  font-size: 1.8rem;
  color: #aaa;
  transition: all 0.3s ease;
  z-index: 3;
}
.icon-love.active {
  color: #ff0040;
  transform: scale(1.2);
  text-shadow: 0 0 15px #ff0040;
}

/* Efek shine */
.image-container.shine::before,
.image-container.shine::after {
  content: '';
  position: absolute;
  inset: 0;
  border-radius: 15px;
  z-index: 2;
}
.image-container.shine::before {
  background: linear-gradient(90deg,
    rgba(255,255,255,0) 0%,
    rgba(255,255,255,0.4) 50%,
    rgba(255,255,255,0) 100%);
  filter: blur(20px);
  animation: shineMove 6s linear infinite;
}
.image-container.shine::after {
  box-shadow: 0 0 50px rgba(255,0,255,0.8);
  animation: glowShift 6s linear infinite;
}

@keyframes shineMove {
  0% { transform: translateX(-150%); opacity: 0.7; }
  50% { transform: translateX(150%); opacity: 1; }
  100% { transform: translateX(-150%); opacity: 0.7; }
}
@keyframes glowShift {
  0% { box-shadow: 0 0 50px rgba(255,0,0,0.9); }
  20% { box-shadow: 0 0 50px rgba(255,255,0,0.9); }
  40% { box-shadow: 0 0 50px rgba(0,255,0,0.9); }
  60% { box-shadow: 0 0 50px rgba(0,255,255,0.9); }
  80% { box-shadow: 0 0 50px rgba(0,0,255,0.9); }
  100% { box-shadow: 0 0 50px rgba(255,0,0,0.9); }
}

/* ===== POPUP ===== */
.popup {
  position: fixed;
  top: 0; left: 0;
  width: 100vw; height: 100vh;
  background: rgba(0,0,0,0.85);
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
  width: 90%;
  position: relative;
  box-shadow: 0 0 40px rgba(255, 60, 125, 1);
  transition: box-shadow 0.3s ease, transform 0.3s ease;
  animation: popupShow 0.5s ease;
}

.popup-content img {
  width: 100%;
  height: auto;
  border-radius: 15px;
  margin-bottom: 10px;
  object-fit: cover;
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

@keyframes popupShow {
  from { opacity: 0; transform: scale(0.8); }
  to { opacity: 1; transform: scale(1); }
}
@keyframes borderMove {
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}
@keyframes colorGlow {
  0% { box-shadow: 0 0 25px #ff0000; }
  20% { box-shadow: 0 0 25px #ff7f00; }
  40% { box-shadow: 0 0 25px #ffff00; }
  60% { box-shadow: 0 0 25px #00ff00; }
  80% { box-shadow: 0 0 25px #0000ff; }
  100% { box-shadow: 0 0 25px #ff0000; }
}

.icon-bar {
  margin-top: 10px;
}
.icon-like, .icon-love {
  font-size: 1.8rem;
  margin: 0 10px;
  cursor: pointer;
  transition: 0.3s ease;
}
.icon-like.active {
  color: #00bfff;
  transform: scale(1.1);
}
.icon-love.active {
  color: #ff0040;
  transform: scale(1.2);
  text-shadow: 0 0 15px #ff0040;
}
.popup button {
  border-color: cyan;
  color: cyan;
}
.popup button:hover {
  background: cyan;
  color: #111;
}

/* scroll untuk deskripsi */
.popup-content::-webkit-scrollbar { width: 6px; }
.popup-content::-webkit-scrollbar-thumb {
  background: cyan;
  border-radius: 5px;
}
@media(max-width:768px){
  .popup-content{
    width:90vw;
    height:auto;
    max-height:90vh;
  }
}
</style>
</head>
<body>
<div class="container mt-4 text-center">
  <h2 class="text-info mb-4">Galeri Heroes Anda</h2>
  <a href="heroes.php" class="btn btn-outline-info mb-4">← Kembali ke Daftar Heroes</a>
</div>

<div class="gallery-container">
  <?php while ($row = mysqli_fetch_assoc($data)) {
    $shine = ($row['loved'] == 1) ? 'shine' : '';
    $loved = ($row['loved'] == 1) ? 'active' : '';
  ?>
  <div class="image-container <?= $shine ?>"
       data-id="<?= $row['id'] ?>"
       data-loved="<?= $row['loved'] ?>"
       data-nama="<?= htmlspecialchars($row['nama']) ?>"
       data-subname="<?= htmlspecialchars($row['subname']) ?>"
       data-tanggal="<?= htmlspecialchars($row['tanggal']) ?>"
       data-keterangan="<?= htmlspecialchars($row['keterangan']) ?>"
       data-gambar="<?= htmlspecialchars($row['file_path']) ?>">
    <img src="<?= htmlspecialchars($row['file_path']) ?>" alt="<?= htmlspecialchars($row['nama']) ?>">
    <i class="fa-solid fa-heart icon-love <?= $loved ?>"></i>
  </div>
  <?php } ?>
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
const popupImg = document.getElementById('popup-img');
const popupNama = document.getElementById('popup-nama');
const popupSub = document.getElementById('popup-sub');
const popupTanggal = document.getElementById('popup-tanggal');
const popupKet = document.getElementById('popup-ket');
const loveBtn = document.getElementById('loveBtn');
let currentCard = null;

// === BUKA POPUP ===
cards.forEach(card => {
  card.addEventListener('click', e => {
    if (e.target.classList.contains('icon-love')) return; // klik love => jangan buka popup
    currentCard = card;
    popupImg.src = card.dataset.gambar;
    popupNama.textContent = card.dataset.nama;
    popupSub.textContent = "Subname: " + (card.dataset.subname || '-');
    popupTanggal.textContent = "Tanggal: " + (card.dataset.tanggal || '-');
    popupKet.textContent = "Keterangan: " + (card.dataset.keterangan || '-');
    loveBtn.classList.toggle('active', card.querySelector('.icon-love').classList.contains('active'));
    popup.classList.add('active');
  });
});

// === TUTUP POPUP ===
function closePopup() { popup.classList.remove('active'); }
popup.addEventListener('click', e => { if (e.target === popup) closePopup(); });

// === KLIK LOVE DI POPUP ===
loveBtn.addEventListener('click', () => {
  if (!currentCard) return;
  const id = currentCard.dataset.id;

  loveBtn.classList.toggle('active');
  loveBtn.style.transform = 'scale(1.5)';
  setTimeout(() => loveBtn.style.transform = 'scale(1)', 150);

  fetch('toggle_hero_love.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: 'hero_id=' + encodeURIComponent(id)
  })
  .then(res => res.text())
  .then(res => {
    const gridLove = currentCard.querySelector('.icon-love');
    if (res === 'loved') {
      currentCard.classList.add('shine');
      gridLove.classList.add('active');
      loveBtn.classList.add('active');
    } else if (res === 'unloved') {
      currentCard.classList.remove('shine');
      gridLove.classList.remove('active');
      loveBtn.classList.remove('active');
    }
  })
  .catch(err => console.error(err));
});

// === KLIK LOVE DI GRID (tambahan penting) ===
document.querySelectorAll('.icon-love').forEach(icon => {
  icon.addEventListener('click', e => {
    e.stopPropagation(); // cegah buka popup
    const card = e.target.closest('.image-container');
    const id = card.dataset.id;
    const loved = e.target.classList.contains('active');

    // efek animasi love di grid
    e.target.style.transform = 'scale(1.4)';
    setTimeout(() => e.target.style.transform = 'scale(1)', 150);

    fetch('toggle_hero_love.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      body: 'hero_id=' + encodeURIComponent(id)
    })
    .then(res => res.text())
    .then(res => {
      if (res === 'loved') {
        e.target.classList.add('active');
        card.classList.add('shine');
      } else if (res === 'unloved') {
        e.target.classList.remove('active');
        card.classList.remove('shine');
      }
    })
    .catch(err => console.error(err));
  });
});
// === ANIMASI INTERAKTIF PADA POPUP ===
const popupContent = document.getElementById('popupContent');

// efek default lebih kuat
popupContent.style.boxShadow = '0 0 40px rgba(255, 0, 140, 0.8)';

// ketika mouse masuk, sinar meningkat
popupContent.addEventListener('mouseenter', () => {
  popupContent.style.transition = 'box-shadow 0.3s ease, transform 0.3s ease';
  popupContent.style.boxShadow = '0 0 60px 10px rgba(0,255,255,1)';
  popupContent.style.transform = 'scale(1.03)';
});

// ketika mouse keluar, kembali ke semula
popupContent.addEventListener('mouseleave', () => {
  popupContent.style.boxShadow = '0 0 40px rgba(0,255,255,0.8)';
  popupContent.style.transform = 'scale(1)';
});

</script>

</body>
</html>
