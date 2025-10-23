<?php
session_start();
require_once __DIR__ . '/../config/db_config.php';
$pdo = db();

if (empty($_SESSION['user_id'])) {
    header("Location: http://localhost/cartsy/login/login.php");
    exit();
}

$user_id = (int)$_SESSION['user_id'];

// ✅ Fetch unique conversations (buyer ↔ seller per product)
$sql = "
    SELECT 
        CASE 
            WHEN m.sender_id = ? THEN m.receiver_id
            ELSE m.sender_id
        END AS other_user_id,
        u.name AS other_user_name,
        p.product_id,
        p.product_name,
        pi.image_path AS thumbnail,
        MAX(m.created_at) AS last_message_time
    FROM messages m
    JOIN users u 
        ON u.id = CASE WHEN m.sender_id = ? THEN m.receiver_id ELSE m.sender_id END
    JOIN products p 
        ON p.product_id = m.product_id
    LEFT JOIN product_images pi 
        ON pi.product_id = p.product_id
    WHERE (m.sender_id = ? OR m.receiver_id = ?)
    GROUP BY other_user_id, p.product_id
    ORDER BY last_message_time DESC
";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id, $user_id, $user_id, $user_id]);

$conversations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Cartsy Chat</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <style>
    body { font-family: "Poppins", sans-serif; }
    .chat-item:hover { background-color: #f0f0f0; cursor: pointer; }
    .chat-box { background-color: #e9ecef; height: 80vh; overflow-y: auto; }
    .message { padding: 10px 15px; margin: 5px 0; border-radius: 10px; max-width: 70%; word-wrap: break-word; }
    .sent { background-color: #d1ffd1; align-self: flex-end; text-align: right; }
    .received { background-color: #ffffff; align-self: flex-start; }
    .navbar { background-color: #fff; border-bottom: 1px solid #ddd; padding: 1rem 2rem; }
    .navbar-brand { font-family: "Suranna", serif; font-size: 30px; color: #343a40; }

    /* ===== iPhone-style Location Sheet (scoped) ===== */
    .loc-overlay{position:fixed;inset:0;background:rgba(0,0,0,.65);display:none;z-index:9999}
    .loc-sheet{position:absolute;inset:0;display:flex;flex-direction:column}
    .loc-head{padding:14px 16px;display:flex;justify-content:space-between;align-items:center;color:#fff}
    .loc-title{font-weight:700}
    .loc-done{color:#0a84ff;font-weight:600;cursor:pointer}
    .loc-search-wrap{padding:0 16px}
    .loc-search{width:100%;padding:10px 12px;border-radius:12px;border:1px solid #2a3141;background:#0f1320;color:#eaeef7}
    #loc-map{flex:1;margin:12px 16px;border-radius:16px;overflow:hidden;border:1px solid #2a3141}
    .loc-foot{padding:12px 16px 18px;display:flex;flex-direction:column;gap:10px}
    .loc-cta{width:100%;padding:14px;border:0;border-radius:14px;font-weight:700;font-size:16px;background:#0a84ff;color:#fff;cursor:pointer}
    .loc-cta-ghost{background:#0e1118;color:#eaeef7;border:1px solid #2a3141}

    /* Floating mini live map (only when live tracking is active) */
    #liveMapWrap {
      position: fixed;
      right: 16px;
      bottom: 90px;
      width: 240px;
      height: 240px;
      background: #fff;
      border: 1px solid #ddd;
      border-radius: 12px;
      box-shadow: 0 8px 24px rgba(0,0,0,.15);
      overflow: hidden;
      display: none;
      z-index: 1000;
    }
    #liveMapHeader {
      font-weight: 600;
      font-size: 14px;
      padding: 6px 10px;
      background: #111827;
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    #liveMap { width: 100%; height: calc(100% - 32px); }
    #btnStopLive {
      background: transparent; border: 0; color: #fff; cursor: pointer; font-size: 13px;
    }
  </style>
</head>
<body>
<nav class="navbar sticky-top navbar-light">
  <div class="container-fluid">
    <a class="navbar-brand fs-3" href="http://localhost/cartsy/index/beta_index.php">Cartsy</a>
    <form class="d-flex flex-grow-1 mx-3" action="http://localhost/cartsy/search-product/test-4.php" method="GET">
      <div class="input-group">
        <input class="form-control" type="search" name="query" placeholder="Search" required>
        <button class="btn btn-dark" type="submit">Search</button>
      </div>
    </form>
    <a href="http://localhost/cartsy/seller/test-1.php" class="btn btn-outline-dark me-3">Sell</a>
    <a href="http://localhost/cartsy/saved/test-6.php" class="btn btn-outline-danger me-3">
      <i class="bi bi-heart-fill"></i>
    </a>
    <div>
      <a href="http://localhost/cartsy/chat/conversation.php"><i class="bi bi-chat fs-4 me-3"></i></a>
      <a href="http://localhost/cartsy/profile/index-7.php"><i class="bi bi-person-circle fs-4"></i></a>
    </div>
  </div>
</nav>

<div class="container-fluid">
  <div class="row vh-100">
    <!-- Sidebar -->
    <div class="col-3 bg-light border-end p-3">
      <div class="mt-4">
        <h5 class="fw-semibold">Chats</h5>
        <ul class="list-unstyled" id="chat-sidebar">
          <?php foreach ($conversations as $conv): ?>
            <li class="d-flex align-items-center p-2 chat-item"
                data-product-id="<?= htmlspecialchars($conv['product_id']) ?>"
                data-other-id="<?= htmlspecialchars($conv['other_user_id']) ?>">
              <img src="/cartsy/seller/<?= htmlspecialchars($conv['thumbnail'] ?? 'default-thumbnail.jpg') ?>" 
                   class="rounded-circle me-2" 
                   style="width: 40px; height: 40px; object-fit: cover;" />
              <div>
                <div class="fw-bold"><?= htmlspecialchars($conv['other_user_name']) ?></div>
                <small class="text-muted"><?= htmlspecialchars($conv['product_name']) ?></small>
              </div>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>

    <!-- Chat Area -->
    <div class="col-9 d-flex flex-column p-0">
      <div id="chat-box" class="flex-grow-1 p-3 chat-box overflow-auto bg-light-gray">
        <p class="text-center text-muted mt-5">Select a conversation to view messages</p>
      </div>

      <div class="p-3 border-top d-flex align-items-center bg-white">
        <form id="message-form" class="w-100 d-flex align-items-center">
          <div class="dropdown me-2">
            <button class="btn btn-secondary rounded-circle" type="button" id="attachMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bi bi-plus-lg"></i>
            </button>
            <ul class="dropdown-menu" aria-labelledby="attachMenuButton">
              <li>
                <button class="dropdown-item d-flex align-items-center" type="button" id="sendLocationBtn">
                  <i class="bi bi-geo-alt me-2 text-danger"></i> Send Location
                </button>
              </li>
              <li>
                <button class="dropdown-item d-flex align-items-center" type="button" id="sendImageBtn">
                  <i class="bi bi-image me-2 text-primary"></i> Send Picture
                </button>
              </li>
            </ul>
          </div>

          <input type="text" name="message" id="message-input" class="form-control me-2" placeholder="Type a message..." required />
          <button type="submit" class="btn btn-dark rounded-circle"><i class="bi bi-send"></i></button>
        </form>
        <input type="file" id="imageInput" accept="image/*" style="display:none;">
      </div>
    </div>
  </div>
</div>

<!-- ===== Location Sheet Markup ===== -->
<div id="locOverlay" class="loc-overlay">
  <div class="loc-sheet">
    <div class="loc-head">
      <div class="loc-title">Location</div>
      <div class="loc-done" id="locDone">Done</div>
    </div>
    <div class="loc-search-wrap">
      <input id="locSearch" class="loc-search" placeholder="Find a place or address">
    </div>
    <div id="loc-map"></div>
    <div class="loc-foot">
      <button id="btnSendPin" class="loc-cta">Send location</button>
      <button id="btnShareLive" class="loc-cta loc-cta-ghost">Start sharing live location</button>
    </div>
  </div>
</div>
<!-- ================================ -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
const chatBox = document.getElementById('chat-box');
const sidebar = document.getElementById('chat-sidebar');
const form = document.getElementById('message-form');
const input = document.getElementById('message-input');
let currentProductId = null;
let currentOtherId = null;
let autoRefreshEnabled = true;
const USER_ID = <?= (int)$user_id ?>;

/* ================= Base chat behavior ================= */
chatBox.addEventListener('scroll', () => {
  const distanceFromBottom = chatBox.scrollHeight - chatBox.scrollTop - chatBox.clientHeight;
  autoRefreshEnabled = distanceFromBottom < 100;
});

function loadMessages(productId, otherId) {
  currentProductId = productId;
  currentOtherId   = otherId;

  fetch(`fetch.php?product_id=${productId}&other_id=${otherId}`)
    .then(res => res.json())
    .then(data => {
      chatBox.innerHTML = '';
      data.forEach(msg => {
        const div = document.createElement('div');
        div.className = 'd-flex flex-column ' + 
          (msg.sender_id == USER_ID ? 'align-items-end' : 'align-items-start') + ' mb-3';

        let content = '';
        if (msg.message_type === 'image' && msg.image_path) {
          content = `<a href="${msg.image_path}" target="_blank">
                       <img src="${msg.image_path}" class="img-fluid rounded" style="max-width:200px; border:1px solid #ccc;"/>
                     </a>`;
        } else if (msg.message_type === 'location' && msg.latitude && msg.longitude) {
          // render a static Leaflet map inside this bubble (do NOT refresh this later)
          const mapId = 'map-' + msg.message_id;
          content = `
            <div style="width:200px; height:200px; border:1px solid #ccc; border-radius:10px;" id="${mapId}"></div>
            <small><a href="https://www.openstreetmap.org/?mlat=${msg.latitude}&mlon=${msg.longitude}" target="_blank" rel="noopener">View on OpenStreetMap</a></small>
          `;
          setTimeout(() => {
            const map = L.map(mapId, { zoomControl: false }).setView([msg.latitude, msg.longitude], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);
            L.marker([msg.latitude, msg.longitude]).addTo(map);
          }, 300);
        } else {
          const span = document.createElement('span'); 
          span.textContent = msg.message || '';
          content = span.outerHTML;
        }

        div.innerHTML = `
          <div class="message ${msg.sender_id == USER_ID ? 'sent' : 'received'}">
            <strong>${msg.sender_id == USER_ID ? 'You' : msg.sender_name}:</strong> ${content}
          </div>`;
        chatBox.appendChild(div);
      });
      chatBox.scrollTop = chatBox.scrollHeight;
    });
}

setInterval(() => {
  if (currentProductId && currentOtherId && autoRefreshEnabled) loadMessages(currentProductId, currentOtherId);
}, 3000);

sidebar.addEventListener('click', e => {
  const item = e.target.closest('.chat-item');
  if (!item) return;
  const productId = item.getAttribute('data-product-id');
  const otherId = item.getAttribute('data-other-id');
  loadMessages(productId, otherId);
});

/* ================= Sending text & images ================= */
form.addEventListener('submit', e => {
  e.preventDefault();
  if (!currentProductId || !currentOtherId) return;
  const message = input.value.trim();
  if (!message) return;

  fetch('send.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `product_id=${currentProductId}&receiver_id=${currentOtherId}&message=${encodeURIComponent(message)}`
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      input.value = '';
      loadMessages(currentProductId, currentOtherId);
    }
  });
});

document.getElementById('sendImageBtn').addEventListener('click', () => {
  document.getElementById('imageInput').click();
});
document.getElementById('imageInput').addEventListener('change', function () {
  const file = this.files[0];
  if (!file || !currentProductId || !currentOtherId) return;

  const formData = new FormData();
  formData.append('image', file);
  formData.append('product_id', currentProductId);
  formData.append('receiver_id', currentOtherId);

  fetch('send_image.php', { method: 'POST', body: formData })
    .then(res => res.json())
    .then(data => {
      if (data.success) loadMessages(currentProductId, currentOtherId);
      else alert('Failed to send image');
    });
});

/* ================= Location modal elements ================= */
const sendLocationBtn = document.getElementById('sendLocationBtn');
const locOverlay  = document.getElementById('locOverlay');
const locDone     = document.getElementById('locDone');
const locSearch   = document.getElementById('locSearch');
const btnSendPin  = document.getElementById('btnSendPin');   // Send location (meeting pin)
const btnShare    = document.getElementById('btnShareLive'); // Start sharing live location (exact GPS once)

let locMap, locMarker;

/* open modal */
sendLocationBtn.addEventListener('click', () => {
  if (!currentProductId || !currentOtherId) { alert('Select a conversation first.'); return; }
  locOverlay.style.display = 'block';
  setTimeout(initLocMap, 60);
});
locDone.addEventListener('click', () => { locOverlay.style.display = 'none'; });

function initLocMap() {
  if (!locMap) {
    locMap = L.map('loc-map', { zoomControl: true }).setView([14.8791, 120.4569], 14);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(locMap);
    locMap.on('click', e => {
      if (locMarker) locMap.removeLayer(locMarker);
      locMarker = L.marker(e.latlng).addTo(locMap);
    });
  }
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(pos => {
      locMap.setView([pos.coords.latitude, pos.coords.longitude], 16);
    }, ()=>{}, { enableHighAccuracy: true, maximumAge: 10000, timeout: 10000 });
  }
}

/* Search (Enter) — precise, centers & drops pin, fixes sizing */
async function geocodePlace(q) {
  if (!q) return;
  try {
    const url = `https://nominatim.openstreetmap.org/search?format=jsonv2&limit=1&q=${encodeURIComponent(q)}`;
    const res = await fetch(url, { headers: { 'Accept-Language': 'en' } });
    const data = await res.json();

    if (!Array.isArray(data) || !data[0]) {
      alert('Place not found. Try a more specific name or address.');
      return;
    }

    const lat = parseFloat(data[0].lat);
    const lon = parseFloat(data[0].lon);

    if (!locMap) initLocMap();

    locMap.setView([lat, lon], 16);
    setTimeout(() => locMap.invalidateSize(true), 0);

    if (locMarker) locMap.removeLayer(locMarker);
    locMarker = L.marker([lat, lon]).addTo(locMap);
  } catch (e) {
    console.error(e);
    alert('Search failed. Please try again.');
  }
}

// Press Enter to search; prevent default so nothing else intercepts it
locSearch.addEventListener('keydown', (e) => {
  if (e.key === 'Enter') {
    e.preventDefault();
    geocodePlace(locSearch.value.trim());
  }
});

/* ============== 1) Send meeting pin (send once) ============== */
btnSendPin.addEventListener('click', async () => {
  if (!locMarker) { alert('Tap the map to drop a pin first.'); return; }
  const { lat, lng } = locMarker.getLatLng();

  const body = new URLSearchParams();
  body.set('product_id', currentProductId);
  body.set('receiver_id', currentOtherId);
  body.set('message', `📍 Meeting point: ${lat.toFixed(6)}, ${lng.toFixed(6)}`);
  body.set('latitude',  lat);
  body.set('longitude', lng);
  body.set('type', 'location');

  btnSendPin.disabled = true;
  const res = await fetch('send.php', { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body });
  const data = await res.json();
  btnSendPin.disabled = false;

  if (data.success) {
    locOverlay.style.display = 'none';
    loadMessages(currentProductId, currentOtherId);
  }
});

/* ===========================================================
   2) Start sharing live location (send once, separate preview)
   - Sends your current GPS once (message bubble is static)
   - Shows a floating Leaflet mini-map that updates ONLY there
   - Adds jitter filter (~15m) to reduce laptop Wi-Fi/IP drift
=========================================================== */
let livePreviewWrap = null, livePreviewMap = null, livePreviewMarker = null, livePreviewTimer = null;
let lastLiveLat = null, lastLiveLon = null;

function ensureLivePreview() {
  if (livePreviewWrap) return;

  const css = document.createElement('style');
  css.textContent = `
    #livePreviewWrap {
      position: fixed; right: 16px; bottom: 90px; width: 260px; height: 260px;
      background: #fff; border:1px solid #ddd; border-radius:12px;
      box-shadow: 0 8px 24px rgba(0,0,0,.15); overflow: hidden; z-index: 1000; display: none;
    }
    #livePreviewHeader {
      height: 32px; display:flex; align-items:center; justify-content:space-between;
      background:#111827; color:#fff; padding: 0 10px; font-weight:600; font-size:14px;
    }
    #livePreviewMap { width: 100%; height: calc(100% - 32px); }
    #btnCloseLivePreview { background: transparent; border: 0; color: #fff; cursor: pointer; font-size: 13px; }
  `;
  document.head.appendChild(css);

  livePreviewWrap = document.createElement('div');
  livePreviewWrap.id = 'livePreviewWrap';
  livePreviewWrap.innerHTML = `
    <div id="livePreviewHeader">
      <span>🔵 Your live position</span>
      <button id="btnCloseLivePreview" title="Hide">Hide</button>
    </div>
    <div id="livePreviewMap"></div>
  `;
  document.body.appendChild(livePreviewWrap);

  document.getElementById('btnCloseLivePreview').addEventListener('click', () => {
    livePreviewWrap.style.display = 'none';
    if (livePreviewTimer) clearInterval(livePreviewTimer);
    livePreviewTimer = null;
  });
}

function startLiveVisualRefresh(initialLat, initialLon) {
  ensureLivePreview();

  // show container first so Leaflet can measure it
  livePreviewWrap.style.display = 'block';

  // create the map once, centered on initial position
  if (!livePreviewMap) {
    livePreviewMap = L.map('livePreviewMap', { zoomControl: true }).setView([initialLat, initialLon], 16);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19, attribution: ''
    }).addTo(livePreviewMap);
    livePreviewMarker = L.marker([initialLat, initialLon]).addTo(livePreviewMap);

    requestAnimationFrame(() => {
      livePreviewMap.invalidateSize(true);
      livePreviewMap.setView([initialLat, initialLon], 16);
    });
  } else {
    livePreviewMarker.setLatLng([initialLat, initialLon]);
    livePreviewMap.setView([initialLat, initialLon], Math.max(livePreviewMap.getZoom(), 16));
    livePreviewMap.invalidateSize(true);
  }

  lastLiveLat = initialLat;
  lastLiveLon = initialLon;

  // clear any prior timer and start smooth updates (front-end only)
  if (livePreviewTimer) clearInterval(livePreviewTimer);
  livePreviewTimer = setInterval(() => {
    if (!navigator.geolocation) return;
    navigator.geolocation.getCurrentPosition(pos => {
      const lat = pos.coords.latitude;
      const lon = pos.coords.longitude;

      // ✅ jitter filter (~15m)
      const dLat = lat - lastLiveLat;
      const dLon = lon - lastLiveLon;
      const distanceMeters = Math.sqrt(dLat * dLat + dLon * dLon) * 111000;
      if (distanceMeters < 15) return;

      lastLiveLat = lat;
      lastLiveLon = lon;

      livePreviewMarker.setLatLng([lat, lon]);
      livePreviewMap.panTo([lat, lon], { animate: true, duration: 0.5 });
    }, () => {}, { enableHighAccuracy: true, maximumAge: 10000, timeout: 10000 });
  }, 5000);
}

/* click: send once + show floating preview (no DB spam, no bubble refresh) */
btnShare.addEventListener('click', () => {
  if (!navigator.geolocation) { alert("Geolocation not supported."); return; }
  if (!currentProductId || !currentOtherId) { alert("Select a conversation first."); return; }

  navigator.geolocation.getCurrentPosition(pos => {
    const lat = pos.coords.latitude;
    const lon = pos.coords.longitude;

    // 1) send ONE location message (bubble remains static)
    fetch('send.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `product_id=${currentProductId}&receiver_id=${currentOtherId}&message=${encodeURIComponent('📡 Live location shared')}&latitude=${lat}&longitude=${lon}&type=location`
    })
    .then(res => res.json())
    .then(() => {
      locOverlay.style.display = 'none';
      loadMessages(currentProductId, currentOtherId);

      // 2) start visual-only updates, centered right away
      startLiveVisualRefresh(lat, lon);
    });
  }, () => { alert("Unable to get your location."); }, { enableHighAccuracy: true, maximumAge: 10000, timeout: 10000 });
});
</script>



</body>
</html>
