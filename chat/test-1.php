<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Cartsy Chat</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="style.css" />
  <style>
    body {
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
}

.chat-item:hover {
  background-color: #f0f0f0;
  cursor: pointer;
}

.chat-box {
  background-color: #d4d4d4;
}

.bg-light-gray {
  background-color: #d4d4d4 !important;
}

  </style>
</head>
<body>
  <div class="container-fluid">
    <div class="row vh-100">
      <!-- Sidebar -->
      <div class="col-3 bg-light border-end p-3">
        <h4 class="fw-bold">Cartsy</h4>
        <div class="mt-4">
          <h5 class="fw-semibold">Chats</h5>
          <ul class="list-unstyled">
            <li class="d-flex align-items-center p-2 chat-item active">
              <div class="rounded-circle bg-secondary me-2" style="width: 40px; height: 40px;"></div>
              <span>Lorem ipsum dolor</span>
            </li>
            <li class="d-flex align-items-center p-2 chat-item bg-light border-start border-3 border-dark">
              <div class="rounded-circle bg-secondary me-2" style="width: 40px; height: 40px;"></div>
              <span>John Wick Doe</span>
            </li>
            <li class="d-flex align-items-center p-2 chat-item">
              <div class="rounded-circle bg-secondary me-2" style="width: 40px; height: 40px;"></div>
              <span>Lorem ipsum dolor</span>
            </li>
            <li class="d-flex align-items-center p-2 chat-item">
              <div class="rounded-circle bg-secondary me-2" style="width: 40px; height: 40px;"></div>
              <span>Lorem ipsum dolor</span>
            </li>
            <li class="d-flex align-items-center p-2 chat-item">
              <div class="rounded-circle bg-secondary me-2" style="width: 40px; height: 40px;"></div>
              <span>Lorem ipsum dolor</span>
            </li>
            <li class="d-flex align-items-center p-2 chat-item">
              <div class="rounded-circle bg-secondary me-2" style="width: 40px; height: 40px;"></div>
              <span>Lorem ipsum dolor</span>
            </li>
          </ul>
        </div>
      </div>

      <!-- Chat Area -->
      <div class="col-9 d-flex flex-column p-0">
        <div class="flex-grow-1 p-3 chat-box overflow-auto bg-light-gray">
          <div class="d-flex flex-column align-items-end mb-3">
            <div class="bg-secondary text-white p-2 rounded">Good Day po, I'm interested in buying those pair of Shoes po. <br>Can i ask a few details about it po. Ano pong sizes nung shoes?</div>
          </div>

          <div class="d-flex flex-column align-items-start mb-3">
            <div class="bg-warning p-2 rounded" style="max-width: 400px;">
              Magandang Araw po. <br>
              Price: 265 <br>
              Heel Height : 2cm <br>
              Colors: Beige/Black <br>
              Weight: 640g <br>
              Size: 40
            </div>
          </div>

          <div class="d-flex flex-column align-items-end mb-3">
            <div class="bg-secondary text-white p-2 rounded">Last Price po? 250</div>
          </div>

          <div class="d-flex flex-column align-items-start mb-3">
            <div class="bg-warning p-2 rounded">Di po kaya 250, 260 Last Price.</div>
          </div>

          <div class="d-flex flex-column align-items-end mb-3">
            <div class="bg-secondary text-white p-2 rounded">Ok po. Pa-Avail po</div>
          </div>

          <div class="d-flex flex-column align-items-start mb-3">
            <div class="bg-warning p-2 rounded" style="max-width: 400px;">
              Pasend nalang po, Complete Details. Name, <br>Location/Address, Age, Gender
            </div>
          </div>
        </div>

        <!-- Message Input -->
        <div class="p-3 border-top d-flex align-items-center bg-white">
          <input type="text" class="form-control me-2" placeholder="Type a message..." />
          <button class="btn btn-dark rounded-circle">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-send" viewBox="0 0 16 16">
              <path d="M15.964.686a.5.5 0 0 1 .003.707L1.935 15.425a.5.5 0 0 1-.811-.447l.897-4.698 4.7-2.35-4.7-2.35L1.124.72A.5.5 0 0 1 1.935.275L15.964.686Z"/>
            </svg>
          </button>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
