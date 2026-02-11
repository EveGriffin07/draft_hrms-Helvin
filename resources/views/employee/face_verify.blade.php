<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Face Verification - HRMS</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('css/hrms.css') }}">
  <style>
    main { padding: 2rem; }
    .breadcrumb { font-size: .85rem; color:#94a3b8; margin-bottom: 1rem; }
    h2 { color:#0ea5e9; margin:0 0 .25rem 0; }
    .subtitle { color:#64748b; margin-bottom:1rem; }
    .notice { padding:12px 14px; border-radius:10px; margin-bottom:14px; }
    .notice.success { background:#ecfdf3; color:#166534; border:1px solid #bbf7d0; }
    .notice.error { background:#fef2f2; color:#991b1b; border:1px solid #fecdd3; }
    .notice.info { background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe; }
    .camera-wrap { display:grid; grid-template-columns:repeat(auto-fit,minmax(260px,1fr)); gap:16px; margin-top:8px; }
    .camera-card { background:#f8fafc; border:1px dashed #cbd5e1; border-radius:12px; padding:14px; }
    .camera-card video, .camera-card canvas { width:100%; border-radius:10px; background:#0f172a; min-height:200px; }
    .muted { color:#94a3b8; font-size:12px; margin-top:6px; display:block; }
    .pill { display:inline-flex; align-items:center; gap:6px; padding:6px 10px; border-radius:999px; background:#ecfeff; color:#0ea5e9; font-size:12px; margin-left:8px; }
    .stat { display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); gap:12px; margin:12px 0; }
    .stat .card { border:1px solid #e5e7eb; border-radius:10px; padding:12px; background:#fff; }
    .stat .card label { color:#6b7280; font-size:12px; text-transform:uppercase; letter-spacing:.02em; }
    .stat .card strong { color:#0f172a; font-size:15px; }
  </style>
</head>
<body>
  <header>
    <div class="title">Web-Based HRMS</div>
    <div class="user-info">
      <span><i class="fa-regular fa-bell"></i> &nbsp; {{ Auth::user()->name ?? 'Employee' }}</span>
    </div>
  </header>

  <div class="container">
    @include('employee.layout.sidebar')

    <main>
      <div class="breadcrumb">Attendance · Face Verification</div>
      <h2>Face Verification <span class="pill"><i class="fa-solid fa-shield-heart"></i> Self check</span></h2>
      <p class="subtitle">Verify your identity with the stored face embedding. Use a clear, front-facing photo.</p>

      @if (session('success'))
        <div class="notice success">
          <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
      @endif

      @if ($errors->any())
        <div class="notice error">
          <i class="fa-solid fa-triangle-exclamation"></i> {{ $errors->first() }}
        </div>
      @endif

      <div class="form-container">
        <div class="form-card">
          <h3><i class="fa-solid fa-user-shield"></i> Verify My Face</h3>

          <div class="stat">
            <div class="card">
              <label>Employee</label>
              <strong>{{ $employee->employee_code }} — {{ $employee->user->name ?? 'You' }}</strong>
            </div>
            <div class="card">
              <label>Model</label>
              <strong>{{ $faceData->model_name ?? config('services.face_api.model', 'buffalo_l') }}</strong>
            </div>
            <div class="card">
              <label>Last Updated</label>
              <strong>{{ $faceData?->updated_at?->format('M d, Y H:i') ?? 'Not enrolled' }}</strong>
            </div>
          </div>

          <form id="face-verify-form" method="POST" enctype="multipart/form-data" action="{{ route('face.verify', $employee->employee_id) }}">
            @csrf

            <div class="camera-wrap">
              <div class="camera-card">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                  <span style="font-weight:600; color:#0f172a;">Live Camera</span>
                  <button type="button" class="btn btn-secondary btn-small" id="start-camera" {{ $faceData ? '' : 'disabled' }}>
                    <i class="fa-solid fa-camera"></i> Start
                  </button>
                </div>
                <video id="camera-preview" autoplay playsinline muted></video>
                <div style="display:flex; justify-content:flex-end; gap:8px; margin-top:10px;">
                  <button type="button" class="btn btn-primary btn-small" id="capture-photo" {{ $faceData ? '' : 'disabled' }}>
                    <i class="fa-solid fa-circle-dot"></i> Capture
                  </button>
                </div>
                <small class="muted">Captured frame is submitted for verification.</small>
              </div>
              <div class="camera-card">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                  <span style="font-weight:600; color:#0f172a;">Snapshot</span>
                  <button type="button" class="btn btn-secondary btn-small" id="clear-image" {{ $faceData ? '' : 'disabled' }}>
                    <i class="fa-solid fa-rotate-left"></i> Clear
                  </button>
                </div>
                <canvas id="snapshot" aria-label="Captured preview"></canvas>
                <small class="muted" id="snapshot-status">No capture yet.</small>
              </div>
            </div>

            <div style="display:flex; gap:12px; margin-top:18px;">
              <button type="submit" class="btn btn-primary" {{ $faceData ? '' : 'disabled' }}>
                <i class="fa-solid fa-fingerprint"></i> Verify Face
              </button>
              <button type="reset" class="btn btn-secondary" id="reset-form" {{ $faceData ? '' : 'disabled' }}>
                <i class="fa-solid fa-eraser"></i> Reset
              </button>
            </div>

            <input type="file" id="verify-image" name="image" accept="image/*" capture="user" hidden>
          </form>

          @if($verifyResult)
            <div class="notice {{ ($verifyResult['matched'] ?? false) ? 'success' : 'error' }}" style="margin-top:16px;">
              <strong>{{ ($verifyResult['matched'] ?? false) ? 'Match' : 'No Match' }}</strong>
              <br>
              Score: {{ number_format($verifyResult['score'] ?? 0, 4) }} &nbsp; · &nbsp;
              Threshold: {{ $verifyResult['threshold'] ?? config('services.face_api.threshold', 0.35) }}
            </div>
          @endif
        </div>
      </div>
    </main>
  </div>

  <script>
    (function() {
      const fileInput = document.getElementById('verify-image');
      const cameraBtn = document.getElementById('start-camera');
      const captureBtn = document.getElementById('capture-photo');
      const clearBtn = document.getElementById('clear-image');
      const resetBtn = document.getElementById('reset-form');
      const video = document.getElementById('camera-preview');
      const canvas = document.getElementById('snapshot');
      const statusEl = document.getElementById('snapshot-status');
      let stream;

      function setStatus(text) {
        if (statusEl) statusEl.textContent = text;
      }

      cameraBtn?.addEventListener('click', async () => {
        try {
          stream = await navigator.mediaDevices.getUserMedia({ video: { width: 640, height: 480 } });
          video.srcObject = stream;
          setStatus('Camera ready.');
        } catch (err) {
          setStatus('Camera not available: ' + err.message);
        }
      });

      captureBtn?.addEventListener('click', () => {
        if (!video.srcObject) {
          setStatus('Start the camera first.');
          return;
        }
        const ctx = canvas.getContext('2d');
        canvas.width = video.videoWidth || 640;
        canvas.height = video.videoHeight || 480;
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
        canvas.toBlob((blob) => {
          if (!blob) return;
          const file = new File([blob], 'capture.jpg', { type: 'image/jpeg' });
          const dataTransfer = new DataTransfer();
          dataTransfer.items.add(file);
          if (fileInput) {
            fileInput.files = dataTransfer.files;
            setStatus('Snapshot captured and bound to the file input.');
          }
        }, 'image/jpeg');
      });

      clearBtn?.addEventListener('click', () => {
        canvas.width = canvas.height = 0;
        fileInput && (fileInput.value = '');
        setStatus('Snapshot cleared.');
      });

      resetBtn?.addEventListener('click', () => {
        setStatus('No capture yet.');
        canvas.width = canvas.height = 0;
        fileInput && (fileInput.value = '');
      });

      window.addEventListener('beforeunload', () => {
        if (stream) {
          stream.getTracks().forEach(t => t.stop());
        }
      });
    })();
  </script>
</body>
</html>
