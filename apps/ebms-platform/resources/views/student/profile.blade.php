@extends('layouts.student')
@section('title', 'My Profile')

@section('content')

<div class="animate-in" style="margin-bottom:24px;">
    <h1 class="font-display" style="font-size:26px;font-weight:600;color:var(--navy);margin:0 0 4px;">My Profile</h1>
    <p style="font-size:14px;color:var(--muted);margin:0;">Photo &amp; Signature for hall tickets and attendance sheets</p>
</div>

@if(session('success'))
<div class="animate-in" style="background:#F0FDF4;border:1px solid #BBF7D0;border-radius:10px;padding:12px 16px;margin-bottom:20px;font-size:14px;color:#166534;font-weight:600;">
    {{ session('success') }}
</div>
@endif

<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">

    {{-- Photo Card --}}
    <div class="card animate-in delay-1" style="padding:24px;">
        <h2 style="font-size:15px;font-weight:700;color:var(--navy);margin:0 0 16px;">Photograph</h2>
        <div style="margin-bottom:16px;text-align:center;">
            @if($student->photo_url)
                <img src="{{ $student->photo_url }}" alt="Photo"
                     style="width:120px;height:150px;border-radius:8px;border:2px solid var(--amber);object-fit:cover;box-shadow:0 2px 8px rgba(212,145,46,.2);">
            @else
                <div style="width:120px;height:150px;background:#EEF0F3;border-radius:8px;border:2px dashed var(--border);display:inline-flex;align-items:center;justify-content:center;flex-direction:column;gap:8px;">
                    <svg width="32" height="32" fill="none" stroke="#8A9AB0" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span style="font-size:11px;color:var(--muted);font-weight:600;">No photo</span>
                </div>
            @endif
        </div>
        <p style="font-size:12px;color:var(--muted);margin:0 0 14px;line-height:1.6;">
            3.5 &times; 4.5 cm &nbsp;·&nbsp; Max 200 KB &nbsp;·&nbsp; JPEG / PNG
        </p>
        <form id="form-photo" method="POST" action="{{ route('student.profile.photo') }}" enctype="multipart/form-data">
            @csrf
            @error('photo')
                <p style="font-size:12px;color:#DC2626;margin:0 0 8px;">{{ $message }}</p>
            @enderror
            {{-- Picker: visually hidden, activated by label --}}
            <input type="file" id="pick-photo" accept="image/jpeg,image/png"
                   style="position:absolute;width:1px;height:1px;opacity:0;overflow:hidden;">
            {{-- Cropped blob injected here programmatically --}}
            <input type="file" id="input-photo" name="photo" style="display:none;">
            <label for="pick-photo" class="btn-ghost btn-sm"
                   style="width:100%;display:flex;align-items:center;justify-content:center;cursor:pointer;">
                Choose &amp; Crop Photo
            </label>
        </form>
    </div>

    {{-- Signature Card --}}
    <div class="card animate-in delay-2" style="padding:24px;">
        <h2 style="font-size:15px;font-weight:700;color:var(--navy);margin:0 0 16px;">Signature</h2>
        <div style="margin-bottom:16px;text-align:center;">
            @if($student->signature_url)
                <img src="{{ $student->signature_url }}" alt="Signature"
                     style="max-width:180px;height:60px;border-radius:8px;border:1px solid var(--border);background:#fff;object-fit:contain;">
            @else
                <div style="width:180px;height:60px;background:#EEF0F3;border-radius:8px;border:2px dashed var(--border);display:inline-flex;align-items:center;justify-content:center;gap:8px;">
                    <svg width="24" height="24" fill="none" stroke="#8A9AB0" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                    </svg>
                    <span style="font-size:11px;color:var(--muted);font-weight:600;">No signature</span>
                </div>
            @endif
        </div>
        <p style="font-size:12px;color:var(--muted);margin:0 0 14px;line-height:1.6;">
            3.5 &times; 1.5 cm &nbsp;·&nbsp; Max 100 KB &nbsp;·&nbsp; JPEG / PNG
        </p>
        <form id="form-signature" method="POST" action="{{ route('student.profile.signature') }}" enctype="multipart/form-data">
            @csrf
            @error('signature')
                <p style="font-size:12px;color:#DC2626;margin:0 0 8px;">{{ $message }}</p>
            @enderror
            <input type="file" id="pick-signature" accept="image/jpeg,image/png"
                   style="position:absolute;width:1px;height:1px;opacity:0;overflow:hidden;">
            <input type="file" id="input-signature" name="signature" style="display:none;">
            <label for="pick-signature" class="btn-ghost btn-sm"
                   style="width:100%;display:flex;align-items:center;justify-content:center;cursor:pointer;">
                Choose &amp; Crop Signature
            </label>
        </form>
    </div>

</div>

{{-- ── Crop Modal ──────────────────────────────────────────────────────── --}}
<div id="crop-modal"
     style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(10,20,35,.85);
            backdrop-filter:blur(6px);-webkit-backdrop-filter:blur(6px);
            align-items:center;justify-content:center;padding:16px;">
    <div style="background:#fff;border-radius:20px;padding:24px;width:100%;max-width:420px;
                box-shadow:0 24px 64px rgba(0,0,0,.4);">

        {{-- Header --}}
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
            <h3 id="crop-title"
                style="font-size:16px;font-weight:700;color:var(--navy);margin:0;font-family:'Fraunces',serif;"></h3>
            <button id="crop-cancel-x" type="button"
                    style="background:none;border:none;cursor:pointer;padding:4px;color:var(--muted);" aria-label="Close">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Canvas viewport — JS sets width/height attributes directly --}}
        <div style="text-align:center;margin-bottom:14px;position:relative;">
            <canvas id="crop-canvas"
                    style="display:block;margin:0 auto;border-radius:10px;border:2.5px solid var(--amber);
                           cursor:grab;touch-action:none;max-width:100%;"></canvas>
            {{-- Corner decoration overlay (cosmetic only) --}}
            <div style="position:absolute;inset:0;pointer-events:none;">
                <div style="position:absolute;top:6px;left:6px;width:16px;height:16px;border-top:2px solid rgba(212,145,46,.8);border-left:2px solid rgba(212,145,46,.8);border-radius:2px 0 0 0;"></div>
                <div style="position:absolute;top:6px;right:6px;width:16px;height:16px;border-top:2px solid rgba(212,145,46,.8);border-right:2px solid rgba(212,145,46,.8);border-radius:0 2px 0 0;"></div>
                <div style="position:absolute;bottom:6px;left:6px;width:16px;height:16px;border-bottom:2px solid rgba(212,145,46,.8);border-left:2px solid rgba(212,145,46,.8);border-radius:0 0 0 2px;"></div>
                <div style="position:absolute;bottom:6px;right:6px;width:16px;height:16px;border-bottom:2px solid rgba(212,145,46,.8);border-right:2px solid rgba(212,145,46,.8);border-radius:0 0 2px 0;"></div>
            </div>
        </div>

        {{-- Zoom control --}}
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;">
            <button id="zoom-out" type="button"
                    style="width:30px;height:30px;border-radius:50%;border:1.5px solid var(--border);background:#fff;
                           cursor:pointer;font-size:18px;line-height:1;color:var(--navy);
                           display:flex;align-items:center;justify-content:center;flex-shrink:0;">−</button>
            <input type="range" id="crop-zoom-slider" min="0" max="200" value="0" step="1"
                   style="flex:1;accent-color:var(--amber);cursor:pointer;">
            <button id="zoom-in" type="button"
                    style="width:30px;height:30px;border-radius:50%;border:1.5px solid var(--border);background:#fff;
                           cursor:pointer;font-size:18px;line-height:1;color:var(--navy);
                           display:flex;align-items:center;justify-content:center;flex-shrink:0;">+</button>
        </div>

        <p style="font-size:11px;color:var(--muted);text-align:center;margin:0 0 16px;letter-spacing:.2px;">
            Drag to reposition &nbsp;·&nbsp; Scroll or pinch to zoom
        </p>

        <div style="display:flex;gap:10px;">
            <button id="crop-cancel"  type="button" class="btn-ghost btn-sm"   style="flex:1;">Cancel</button>
            <button id="crop-confirm" type="button" class="btn-primary btn-sm" style="flex:1;">Upload</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script nonce="{{ $csp_nonce ?? '' }}">
(function () {
'use strict';

// ── Configuration ────────────────────────────────────────────────────────────
const CONFIGS = {
    photo: {
        title:    'Crop Photograph',
        aspectW:  7,    // 3.5 cm
        aspectH:  9,    // 4.5 cm
        viewW:    280,  // canvas display px (height computed from ratio)
        outputW:  420,  // final output px
        outputH:  540,
        inputId:  'input-photo',
        formId:   'form-photo',
        fileName: 'photo.jpg',
    },
    signature: {
        title:    'Crop Signature',
        aspectW:  7,    // 3.5 cm
        aspectH:  3,    // 1.5 cm
        viewW:    350,
        outputW:  560,
        outputH:  240,
        inputId:  'input-signature',
        formId:   'form-signature',
        fileName: 'signature.jpg',
    },
};

// ── State ────────────────────────────────────────────────────────────────────
var cfg      = null;   // active CONFIGS entry
var img      = null;   // decoded HTMLImageElement (off-screen)
var cvW      = 0;      // canvas buffer width  (= viewW)
var cvH      = 0;      // canvas buffer height (= viewW * aspectH/aspectW)
var ox       = 0;      // visible region origin-x in IMAGE natural pixels
var oy       = 0;      // visible region origin-y in IMAGE natural pixels
var scale    = 1;      // canvas-pixels per image-pixel (zoom level)
var minScale = 1;
var maxScale = 4;

// drag
var dragging = false, lastX = 0, lastY = 0;
// pinch
var pinching = false, lastDist = 0;

// ── DOM refs ─────────────────────────────────────────────────────────────────
var modal    = document.getElementById('crop-modal');
var canvas   = document.getElementById('crop-canvas');
var ctx      = canvas.getContext('2d');
var titleEl  = document.getElementById('crop-title');
var slider   = document.getElementById('crop-zoom-slider');
var btnIn    = document.getElementById('zoom-in');
var btnOut   = document.getElementById('zoom-out');
var btnOK    = document.getElementById('crop-confirm');
var btnCanc  = document.getElementById('crop-cancel');
var btnX     = document.getElementById('crop-cancel-x');

// ── Render ───────────────────────────────────────────────────────────────────
function render() {
    ctx.clearRect(0, 0, cvW, cvH);
    if (!img) return;
    var srcW = cvW / scale;
    var srcH = cvH / scale;
    ctx.drawImage(img, ox, oy, srcW, srcH, 0, 0, cvW, cvH);
}

// ── Clamp: keep visible region inside image bounds ───────────────────────────
function clamp() {
    var srcW = cvW / scale;
    var srcH = cvH / scale;
    ox = Math.max(0, Math.min(img.naturalWidth  - srcW, ox));
    oy = Math.max(0, Math.min(img.naturalHeight - srcH, oy));
}

// ── Zoom around a pivot point (in canvas CSS pixels) ─────────────────────────
function setScale(newS, pivCssX, pivCssY) {
    if (pivCssX == null) { pivCssX = cvW / 2; pivCssY = cvH / 2; }

    newS = Math.max(minScale, Math.min(maxScale, newS));
    if (newS === scale) return;

    // Convert pivot from CSS pixels to canvas buffer pixels
    var rect   = canvas.getBoundingClientRect();
    var scaleX = cvW / rect.width;
    var scaleY = cvH / rect.height;
    var px     = pivCssX * scaleX;
    var py     = pivCssY * scaleY;

    // Adjust ox/oy so the image point under the pivot stays fixed
    ox += px / scale - px / newS;
    oy += py / scale - py / newS;
    scale = newS;
    clamp();
    render();

    slider.value = Math.round((scale - minScale) / (maxScale - minScale) * 200);
}

// ── Open modal ───────────────────────────────────────────────────────────────
function openModal(key, file) {
    cfg  = CONFIGS[key];
    titleEl.textContent = cfg.title;

    // Set canvas buffer size to match the crop aspect ratio
    cvW = cfg.viewW;
    cvH = Math.round(cvW * cfg.aspectH / cfg.aspectW);
    canvas.width  = cvW;
    canvas.height = cvH;
    canvas.style.width  = cvW + 'px';
    canvas.style.height = cvH + 'px';

    // Decode file via FileReader (data URL avoids blob: CSP issues)
    var reader = new FileReader();
    reader.onload = function (e) {
        var image  = new Image();
        image.onload = function () {
            img = image;

            // Cover fit: scale so image fills the canvas on both axes
            minScale = Math.max(cvW / img.naturalWidth, cvH / img.naturalHeight);
            maxScale = minScale * 4;
            scale    = minScale;

            // Center the visible region
            var srcW = cvW / scale;
            var srcH = cvH / scale;
            ox = (img.naturalWidth  - srcW) / 2;
            oy = (img.naturalHeight - srcH) / 2;
            clamp();
            render();

            slider.value = 0;
            modal.style.display = 'flex';
        };
        image.src = e.target.result;
    };
    reader.readAsDataURL(file);
}

// ── Close modal ───────────────────────────────────────────────────────────────
function closeModal() {
    modal.style.display = 'none';
    ctx.clearRect(0, 0, cvW, cvH);
    img = null;
    cfg = null;
}

btnCanc.addEventListener('click', closeModal);
btnX.addEventListener('click', closeModal);
modal.addEventListener('click', function (e) { if (e.target === modal) closeModal(); });

// ── Mouse drag ────────────────────────────────────────────────────────────────
canvas.addEventListener('mousedown', function (e) {
    dragging = true;
    lastX = e.clientX; lastY = e.clientY;
    canvas.style.cursor = 'grabbing';
    e.preventDefault();
});
window.addEventListener('mousemove', function (e) {
    if (!dragging || !img) return;
    var rect   = canvas.getBoundingClientRect();
    var scaleX = cvW / rect.width;
    var scaleY = cvH / rect.height;
    var dx = (e.clientX - lastX) * scaleX;
    var dy = (e.clientY - lastY) * scaleY;
    lastX = e.clientX; lastY = e.clientY;
    ox -= dx / scale;
    oy -= dy / scale;
    clamp(); render();
});
window.addEventListener('mouseup', function () {
    dragging = false;
    canvas.style.cursor = 'grab';
});

// ── Wheel zoom ────────────────────────────────────────────────────────────────
canvas.addEventListener('wheel', function (e) {
    e.preventDefault();
    if (!img) return;
    var rect  = canvas.getBoundingClientRect();
    var pivX  = e.clientX - rect.left;
    var pivY  = e.clientY - rect.top;
    setScale(scale * (e.deltaY < 0 ? 1.1 : 0.909), pivX, pivY);
}, { passive: false });

// ── Touch drag + pinch ────────────────────────────────────────────────────────
canvas.addEventListener('touchstart', function (e) {
    e.preventDefault();
    if (e.touches.length === 1) {
        dragging = true; pinching = false;
        lastX = e.touches[0].clientX;
        lastY = e.touches[0].clientY;
    } else if (e.touches.length === 2) {
        dragging = false; pinching = true;
        lastDist = Math.hypot(
            e.touches[0].clientX - e.touches[1].clientX,
            e.touches[0].clientY - e.touches[1].clientY
        );
    }
}, { passive: false });

canvas.addEventListener('touchmove', function (e) {
    e.preventDefault();
    if (!img) return;
    if (dragging && e.touches.length === 1) {
        var rect   = canvas.getBoundingClientRect();
        var scaleX = cvW / rect.width;
        var scaleY = cvH / rect.height;
        var dx = (e.touches[0].clientX - lastX) * scaleX;
        var dy = (e.touches[0].clientY - lastY) * scaleY;
        lastX = e.touches[0].clientX;
        lastY = e.touches[0].clientY;
        ox -= dx / scale;
        oy -= dy / scale;
        clamp(); render();
    } else if (pinching && e.touches.length === 2) {
        var dist = Math.hypot(
            e.touches[0].clientX - e.touches[1].clientX,
            e.touches[0].clientY - e.touches[1].clientY
        );
        var rect = canvas.getBoundingClientRect();
        var pivX = ((e.touches[0].clientX + e.touches[1].clientX) / 2) - rect.left;
        var pivY = ((e.touches[0].clientY + e.touches[1].clientY) / 2) - rect.top;
        setScale(scale * (dist / lastDist), pivX, pivY);
        lastDist = dist;
    }
}, { passive: false });

canvas.addEventListener('touchend', function () {
    dragging = false; pinching = false;
});

// ── Slider & zoom buttons ─────────────────────────────────────────────────────
slider.addEventListener('input', function () {
    var t = parseInt(this.value, 10) / 200;
    setScale(minScale + t * (maxScale - minScale));
});
btnIn.addEventListener('click',  function () { setScale(scale * 1.15); });
btnOut.addEventListener('click', function () { setScale(scale / 1.15); });

// ── Confirm: draw → blob → inject file → submit form ─────────────────────────
btnOK.addEventListener('click', function () {
    if (!cfg || !img) return;

    btnOK.textContent = 'Processing…';
    btnOK.disabled    = true;

    var out  = document.createElement('canvas');
    out.width  = cfg.outputW;
    out.height = cfg.outputH;
    var octx = out.getContext('2d');

    // Source region: what is currently visible in the viewport
    var srcW = cvW / scale;
    var srcH = cvH / scale;
    octx.drawImage(img, ox, oy, srcW, srcH, 0, 0, cfg.outputW, cfg.outputH);

    out.toBlob(function (blob) {
        if (!blob) {
            btnOK.textContent = 'Upload';
            btnOK.disabled    = false;
            return;
        }

        var file = new File([blob], cfg.fileName, { type: 'image/jpeg' });
        var dt   = new DataTransfer();
        dt.items.add(file);

        var inputEl = document.getElementById(cfg.inputId);
        inputEl.files = dt.files;

        // Close modal and submit immediately — no extra button click needed
        closeModal();
        document.getElementById(cfg.formId).submit();

    }, 'image/jpeg', 0.92);
});

// ── Wire file picker inputs to openModal ──────────────────────────────────────
document.getElementById('pick-photo').addEventListener('change', function () {
    if (this.files && this.files[0]) openModal('photo', this.files[0]);
    this.value = '';
});
document.getElementById('pick-signature').addEventListener('change', function () {
    if (this.files && this.files[0]) openModal('signature', this.files[0]);
    this.value = '';
});

})();
</script>
@endpush
