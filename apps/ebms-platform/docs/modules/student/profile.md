# Student Profile & Photo Upload

**Controller:** `app/Http/Controllers/Student/ProfileController.php`

Students can upload a passport-size photo and signature from the profile page. These are displayed in the student portal sidebar and can be included in official documents.

---

## Routes

```
GET  /student/profile            → show
POST /student/profile/photo      → uploadPhoto
POST /student/profile/signature  → uploadSignature
```

---

## File Storage

Files are stored on the **`public` disk** (accessible without authentication) because dompdf requires direct file paths, not auth-gated stream URLs.

| File | Path |
|------|------|
| Photo | `storage/app/public/students/photos/{hall_ticket}.jpg` |
| Signature | `storage/app/public/students/signatures/{hall_ticket}.jpg` |

Public URL: `public/storage/students/photos/{hall_ticket}.jpg`

Filenames use the hall ticket number (not Aadhaar) to balance PII avoidance with debuggability.

Uploading a new file **overwrites** the previous one (same filename, same path).

---

## Validation

| Field | Rules |
|-------|-------|
| Photo | `required\|image\|mimes:jpeg,jpg,png\|max:200` (200 KB) |
| Signature | `required\|image\|mimes:jpeg,jpg,png\|max:100` (100 KB) |

---

## Upload UI: Crop & Resize

The upload page includes an in-browser crop tool built with the Canvas API.

**Why Canvas instead of CSS transforms?**
- CSS `transform: scale()` is unreliable across browsers for aspect ratio enforcement
- Canvas `ctx.drawImage()` is an exact pixel operation

**Why `FileReader.readAsDataURL()` instead of `URL.createObjectURL()`?**
- CSP restricts `blob:` URLs in `img-src` — only `data:` URLs are permitted inline

**Why `<label for="input-id">` instead of `input.click()`?**
- Browsers block programmatic `.click()` on `display:none` inputs — label clicks always work

### Photo Crop

- Viewport: 280×360px (7:9 aspect ratio)
- Output: 420×540px JPEG (passport size: 3.5×4.5cm at 120dpi)

### Signature Crop

- Viewport: 350×150px (7:3 aspect ratio)
- Output: 560×240px JPEG (3.5×1.5cm at 160dpi)

---

## Sidebar Display

The cropped photo appears in two places in the student portal layout (`resources/views/layouts/student.blade.php`):

1. **Desktop sidebar** — ID card at the bottom with photo, name, hall ticket chip, course, and signature strip
2. **Mobile bottom nav** — circular avatar in the Profile tab

If no photo is uploaded, a placeholder icon is shown in both positions.
