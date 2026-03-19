# ChallanPdfService

**File:** `app/Services/ChallanPdfService.php`

Generates the bank fee challan PDF for a student enrollment using dompdf.

---

## Constants

```php
ChallanPdfService::SBI_ACCOUNT   // '52010041880'
ChallanPdfService::SBI_BRANCH    // 'Subedari Branch'
```

---

## Method

```php
public function generate(ExamEnrollment $enrollment): Response
```

Eager-loads `student`, `exam`, and `enrollmentSubjects.subject` on the enrollment, renders the Blade view `student.challan.pdf`, and returns a downloadable PDF response.

**Filename:** `challan-{enrollment_id}.pdf`

---

## PDF Layout

The challan is printed on **A4 landscape** and contains **4 copies** side by side:

| Copy | Recipient |
|------|-----------|
| QUADRUPLICATE | Retained by Bank |
| TRIPLICATE | Retained by College |
| DUPLICATE | Attached with Form |
| ORIGINAL | Retained by Student |

Each copy includes:
- College + bank header (SBI Subedari Branch, A/C No.)
- Student details: name, class, hall ticket, semester, exam
- Subject table with fee amount
- Amount in words
- Candidate signature space (15mm padding)
- "FOR USE BY THE BANK" section with Receiving Cashier, Scroll Cashier, Head Cashier, Manager/Acctn.

---

## dompdf Constraints

> These are **known limitations** — do not work around them without testing:

- **No `height: 100%` or `height: Xmm`** on table rows/cells — dompdf does not clip overflow, so fixed heights cause content to overflow onto the next page.
- **No ₹ (U+20B9)** — the bundled DejaVu fonts do not include the Indian Rupee sign. Use `Rs.` instead.
- **No `blob:` URLs** in any view — CSP blocks them.
- **No remote resources** — `isRemoteEnabled` is set to `false`. Use the `public` disk's file path for images.

---

## Amount in Words

`ChallanPdfService::amountInWords(int $amount): string`

Converts an integer rupee amount to English words up to thousands:

```
1200 → "One Thousand Two Hundred Only"
450  → "Four Hundred Fifty Only"
0    → "Zero Only"
```
