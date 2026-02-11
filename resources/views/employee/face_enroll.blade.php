<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Face Enrollment</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('css/hrms.css') }}">
  <style>
    main { padding:2rem; }
    .notice { padding:12px 14px; border-radius:10px; margin-bottom:14px; }
    .success { background:#ecfdf3; border:1px solid #bbf7d0; color:#166534; }
    .error { background:#fef2f2; border:1px solid #fecdd3; color:#991b1b; }
    .grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:10px; margin-top:10px; }
    .thumb { background:#f8fafc; border:1px dashed #cbd5e1; border-radius:10px; padding:10px; text-align:center; }
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
      <h2>Face Enrollment</h2>
      <p class="subtitle">Capture 3–5 clear face images to create your template.</p>

      @if(session('success'))
        <div class="notice success">{{ session('success') }}</div>
      @endif
      @if($errors->any())
        <div class="notice error">{{ $errors->first() }}</div>
      @endif

      <form id="enroll-form" method="POST" enctype="multipart/form-data" action="{{ route('employee.face.enroll.post') }}" class="form-card">
        @csrf
        <div class="form-group">
          <label>Camera Capture (3–5 images required)</label>
          <video id="camera" autoplay playsinline muted style="width:100%; max-width:520px; border-radius:12px; background:#0f172a;"></video>
          <canvas id="snapshot" hidden></canvas>
          <div class="controls" style="display:flex; gap:10px; margin-top:8px; flex-wrap:wrap;">
            <button type="button" class="btn btn-secondary btn-small" id="start"><i class="fa-solid fa-camera"></i> Start Camera</button>
            <button type="button" class="btn btn-primary btn-small" id="capture"><i class="fa-solid fa-circle-dot"></i> Capture</button>
            <button type="button" class="btn btn-secondary btn-small" id="clear-captures"><i class="fa-solid fa-rotate-left"></i> Clear Captures</button>
            <span id="capture-count" style="align-self:center; color:#64748b; font-weight:600;">0 / 5 captured</span>
          </div>
          <div class="grid" id="capture-preview"></div>
          <div id="file-error" style="color:#b91c1c; font-size:13px; margin-top:6px; display:none;">Please capture between 3 and 5 images.</div>
        </div>

        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-cloud-arrow-up"></i> Enroll</button>
      </form>

      <h3 style="margin-top:20px;">Existing Templates</h3>
      <div class="grid">
        @forelse($templates as $tpl)
          <div class="thumb">
            <div style="font-weight:700;">#{{ $loop->iteration }}</div>
            <div>ID: {{ $tpl->id }}</div>
            <div>Active: {{ $tpl->is_active ? 'Yes' : 'No' }}</div>
            <div>Created: {{ $tpl->created_at->format('Y-m-d H:i') }}</div>
            @if($tpl->image_path)
              <img src="{{ asset('storage/'.$tpl->image_path) }}" alt="Template image" style="width:100%; border-radius:10px; margin-top:8px; object-fit:cover; max-height:140px;">
            @else
              <div style="margin-top:8px; color:#94a3b8; font-size:13px;">No image stored</div>
            @endif
            <form method="POST" action="{{ route('employee.face.templates.destroy', $tpl) }}" style="margin-top:8px;">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-secondary btn-small" onclick="return confirm('Delete this template?');">Delete</button>
            </form>
          </div>
        @empty
          <p>No templates yet.</p>
        @endforelse
      </div>
    </main>
  </div>
  <script>
    (function () {
      const camera = document.getElementById('camera');
      const canvas = document.getElementById('snapshot');
      const startBtn = document.getElementById('start');
      const captureBtn = document.getElementById('capture');
      const clearBtn = document.getElementById('clear-captures');
      const preview = document.getElementById('capture-preview');
      const countEl = document.getElementById('capture-count');

      const totalEl = document.createElement('span');
      totalEl.style.alignSelf = 'center';
      totalEl.style.color = '#0f172a';
      totalEl.style.fontWeight = '600';
      totalEl.style.marginLeft = '6px';
      countEl.parentNode.appendChild(totalEl);

      let stream;
      let cameraReady = false;
      let capturedFiles = [];

      const updateCount = () => {
        countEl.textContent = `${capturedFiles.length} / 5 captured`;
        totalEl.textContent = `Total selected: ${capturedFiles.length} / 5`;
        validateCount();
      };

      function validateCount() {
        const err = document.getElementById('file-error');
        const total = capturedFiles.length;
        err.style.display = (total < 3 || total > 5) ? 'block' : 'none';
        return !(total < 3 || total > 5);
      }

      captureBtn.disabled = true;

      startBtn.addEventListener('click', async () => {
        try {
          stream = await navigator.mediaDevices.getUserMedia({ video: { width: 640, height: 480 } });
          camera.srcObject = stream;
          await camera.play();
          cameraReady = true;
          captureBtn.disabled = false;
        } catch (e) {
          alert('Cannot access camera: ' + e.message);
          captureBtn.disabled = true;
        }
      });

      captureBtn.addEventListener('click', () => {
        if (!cameraReady || !camera.srcObject) {
          alert('Start the camera and allow permission first.');
          return;
        }
        if (capturedFiles.length >= 5) {
          alert('You already have 5 images captured. Remove some to capture more.');
          return;
        }

        const ctx = canvas.getContext('2d');
        canvas.width = camera.videoWidth || 640;
        canvas.height = camera.videoHeight || 480;
        ctx.drawImage(camera, 0, 0, canvas.width, canvas.height);

        canvas.toBlob((blob) => {
          if (!blob) {
            alert('Could not capture frame. Try again after a second.');
            return;
          }
          const file = new File([blob], `capture_${Date.now()}.jpg`, { type: 'image/jpeg' });
          capturedFiles.push(file);
          renderPreviews();
        }, 'image/jpeg', 0.9);
      });

      clearBtn.addEventListener('click', () => {
        capturedFiles = [];
        renderPreviews();
      });

      function renderPreviews() {
        preview.innerHTML = '';
        capturedFiles.forEach((file, idx) => {
          const url = URL.createObjectURL(file);
          const div = document.createElement('div');
          div.className = 'thumb';
          div.innerHTML =
            `<img src="${url}" style="width:100%; border-radius:8px; object-fit:cover; max-height:160px;">` +
            `<div style="margin-top:6px; display:flex; justify-content:space-between; align-items:center;">` +
            `<span style="font-size:12px; color:#64748b;">Capture ${idx + 1}</span>` +
            `<button type="button" class="btn btn-secondary btn-small">Remove</button>` +
            `</div>`;
          preview.appendChild(div);

          div.querySelector('button').addEventListener('click', () => {
            capturedFiles.splice(idx, 1);
            renderPreviews();
          });
        });
        updateCount();
      }

      document.getElementById('enroll-form').addEventListener('submit', async (e) => {
        e.preventDefault();

        if (!validateCount()) {
          alert('Please capture between 3 and 5 images.');
          return;
        }

        const submitBtn = document.querySelector('#enroll-form button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Enrolling...';

        const fd = new FormData();
        fd.append('_token', document.querySelector('input[name="_token"]').value);
        capturedFiles.forEach(f => fd.append('images[]', f));

        try {
          const resp = await fetch(e.target.action, {
            method: 'POST',
            body: fd,
            credentials: 'same-origin',
          });

          if (resp.ok) {
            window.location.reload();
            return;
          }

          const text = (await resp.text() || '').trim();
          alert(`Enrollment failed (HTTP ${resp.status}).\nCheck Network tab + laravel.log.\n\n${text.slice(0, 500)}`);

        } catch (err) {
          alert('Network error: ' + err.message);
        } finally {
          submitBtn.disabled = false;
          submitBtn.textContent = 'Enroll';
        }
      });

      window.addEventListener('beforeunload', () => {
        if (stream) stream.getTracks().forEach(t => t.stop());
      });
    })();
</script>
</body>
</html>
