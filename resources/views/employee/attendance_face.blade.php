<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Face Attendance</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('css/hrms.css') }}">
  <style>
    main { padding:2rem; }
    .page-title { display:flex; align-items:center; gap:10px; }
    .card { background:#fff; border:1px solid #e5e7eb; border-radius:14px; padding:18px; box-shadow:0 8px 18px rgba(15,23,42,0.08); }
    .info-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:12px; margin-bottom:14px; }
    .info-pill { border:1px solid #e5e7eb; border-radius:10px; padding:10px 12px; background:#f8fafc; font-weight:600; }
    .label { font-size:12px; color:#94a3b8; text-transform:uppercase; letter-spacing:.02em; display:block; }
    .value { color:#0f172a; font-weight:700; }
    video, canvas { width:100%; max-width:520px; border-radius:12px; background:#0f172a; }
    #snap-preview { margin-top:10px; width:100%; max-width:520px; border-radius:12px; display:none; border:1px solid #e5e7eb; }
    .controls { display:flex; gap:10px; margin-top:10px; flex-wrap:wrap; }
    .notice { padding:12px 14px; border-radius:10px; margin-bottom:14px; }
    .success { background:#ecfdf3; border:1px solid #bbf7d0; color:#166534; }
    .error { background:#fef2f2; border:1px solid #fecdd3; color:#991b1b; }
    .two-col { display:grid; grid-template-columns:repeat(auto-fit,minmax(260px,1fr)); gap:16px; }
    .subtle { color:#64748b; font-size:12px; margin-top:6px; }
    .status { font-size:12px; color:#64748b; margin-top:6px; }
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
      <div class="page-title">
        <h2 style="margin:0;">Face Attendance</h2>
        <span class="info-pill" style="padding:6px 10px; background:#e0f2fe; color:#0369a1; border-color:#bae6fd;">Self check</span>
      </div>
      <p class="subtitle">Capture a clear frame and submit to verify your identity for attendance.</p>

      @if(session('success'))
        <div class="notice success">{{ session('success') }}</div>
      @endif
      @if($errors->any())
        <div class="notice error">{{ $errors->first() }}</div>
      @endif
      <div class="card" style="margin-top:10px;">
        <div class="info-grid">
          <div class="info-pill">
            <span class="label">Employee</span>
            <span class="value">{{ $employee->employee_code ?? 'N/A' }} — {{ $employee->user->name ?? 'You' }}</span>
          </div>
          <div class="info-pill">
            <span class="label">Model</span>
            <span class="value">{{ config('services.face_api.model', 'buffalo_l') }}</span>
          </div>
          <div class="info-pill">
            <span class="label">Enrollment</span>
            <span class="value">{{ ($hasEnrollment ?? false) ? 'Found' : 'Not enrolled' }}</span>
          </div>
        </div>

        <div class="two-col" style="margin-top:12px;">
          <div>
            <div class="label" style="margin-bottom:6px;">Live Camera</div>
            <video id="camera" autoplay playsinline muted></video>
            <div class="controls">
              <button class="btn btn-secondary btn-small" id="start"><i class="fa-solid fa-camera"></i> Start</button>
              <button class="btn btn-primary btn-small" id="capture"><i class="fa-solid fa-circle-dot"></i> Capture</button>
            </div>
            <div class="subtle">Captured frame replaces the upload input.</div>
          </div>

          <div>
            <div class="label" style="margin-bottom:6px;">Snapshot</div>
            <canvas id="frame" class="mt" hidden></canvas>
            <img id="snap-preview" alt="Captured frame preview">
            <div class="controls" style="justify-content:flex-start; margin-top:8px;">
              <button class="btn btn-secondary btn-small" id="submit" disabled><i class="fa-solid fa-paper-plane"></i> Submit</button>
            </div>
            <div class="subtle">Snapshot is bound to the hidden file field and sent for verification.</div>
          </div>
        </div>

        <form id="attendance-form" method="POST" action="{{ route('employee.attendance.face.post') }}" enctype="multipart/form-data" style="display:none;">
          @csrf
          <input type="file" id="frame-file" name="frame">
        </form>
      </div>
    </main>
  </div>
  <script>
    const video = document.getElementById('camera');
    const canvas = document.getElementById('frame');
    const startBtn = document.getElementById('start');
    const captureBtn = document.getElementById('capture');
    const submitBtn = document.getElementById('submit');
    const frameInput = document.getElementById('frame-file');
    const snapPreview = document.getElementById('snap-preview');
    const cameraStatus = document.createElement('div');
    cameraStatus.className = 'status';
    cameraStatus.textContent = 'Camera idle.';
    // insert status text under the first controls block (live camera)
    const liveControls = document.querySelector('.controls');
    if (liveControls) liveControls.insertAdjacentElement('afterend', cameraStatus);
    let stream;

    function setStatus(msg, isError = false) {
      if (!cameraStatus) return;
      cameraStatus.textContent = msg;
      cameraStatus.style.color = isError ? '#b91c1c' : '#64748b';
    }

    async function stopStream() {
      if (stream) {
        stream.getTracks().forEach(t => t.stop());
        stream = null;
      }
    }

    startBtn?.addEventListener('click', async () => {
      try {
        await stopStream();
        stream = await navigator.mediaDevices.getUserMedia({ video: { width: 640, height: 480, facingMode: 'user' } });
        video.srcObject = stream;
        await video.play();
        setStatus('Camera ready.');
      } catch (e) {
        setStatus('Cannot access camera: ' + e.message, true);
        alert('Cannot access camera: ' + e.message);
      }
    });

    captureBtn?.addEventListener('click', () => {
      if (!video.srcObject) {
        alert('Start the camera first.');
        setStatus('Start the camera first.', true);
        return;
      }
      const ctx = canvas.getContext('2d');
      canvas.width = video.videoWidth || 640;
      canvas.height = video.videoHeight || 480;
      ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
      canvas.toBlob((blob) => {
        if (!blob) {
          alert('Could not capture frame. Try again.');
          return;
        }
        const file = new File([blob], 'frame.jpg', { type: 'image/jpeg' });

        // Attach to hidden file input (with Safari fallback)
        let dt;
        try {
          dt = new DataTransfer();
        } catch (_) {
          dt = new ClipboardEvent('').clipboardData || new DataTransfer();
        }
        dt.items.add(file);
        frameInput.files = dt.files;

        // Show preview and enable submit
        snapPreview.src = URL.createObjectURL(file);
        snapPreview.style.display = 'block';
        submitBtn.disabled = false;
      }, 'image/jpeg', 0.9);
    });

    submitBtn?.addEventListener('click', () => {
      if (!frameInput.files.length) {
        alert('Capture a frame first.');
        return;
      }
      document.getElementById('attendance-form').submit();
    });

    window.addEventListener('beforeunload', () => {
      if (stream) stream.getTracks().forEach(t => t.stop());
    });
  </script>
</body>
</html>
