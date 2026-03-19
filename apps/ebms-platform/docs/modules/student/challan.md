# Challan (Fee Receipt)

**Controller:** `app/Http/Controllers/Student/ChallanController.php`
**Service:** `app/Services/ChallanPdfService.php`
**View:** `resources/views/student/challan/pdf.blade.php`

The challan is a bank deposit slip that the student takes to SBI Subedari Branch to pay their exam fee.

---

## Routes

```
GET /student/challan/{enrollment}         → show (preview page)
GET /student/challan/{enrollment}/download → download (PDF)
```

---

## PDF Structure

A4 landscape, 4 copies in a single row:

```
┌─────────────┬─────────────┬─────────────┬─────────────┐
│ QUADRUPLICATE│ TRIPLICATE  │  DUPLICATE  │  ORIGINAL   │
│ (Bank)      │ (College)   │ (Form)      │ (Student)   │
└─────────────┴─────────────┴─────────────┴─────────────┘
```

Each copy contains:
1. Badge (copy name + recipient)
2. College + Bank header
3. Student metadata (challan no., date, name, class, hall ticket, semester, exam)
4. Subject table (subject code + name, fee amount on first row)
5. Amount in words
6. Candidate signature space
7. "FOR USE BY THE BANK" section

---

## Bank Details

| | |
|--|--|
| Bank | State Bank of India |
| Branch | Subedari Branch, Hanamkonda |
| Account | `52010041880` |

---

## dompdf Notes

- Use `Rs.` not `₹` — the bundled DejaVu fonts don't include U+20B9
- Remove all fixed heights from table rows — dompdf doesn't clip overflow
- `page-break-inside: avoid` must be on both `body` and `.grid`
- `isRemoteEnabled` is `false` — no external resources
