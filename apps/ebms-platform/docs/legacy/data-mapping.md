# Legacy Data Mapping

Maps between legacy `uascexams_ebms` schema and new EBMS schema.

---

## Students (`studentsmaster` → `students`)

| Legacy | New | Notes |
|--------|-----|-------|
| `HALLTICKET` | `hall_ticket` | |
| `NAME` | `name` | |
| `DOB` | `date_of_birth` | `0000-00-00` → null |
| `AADHAAR` | `aadhaar` | PII — stored hidden |
| `DOSTID` | `dost_id` | |
| `COURSE` | `course` | |
| `GROUP` | `group_code` | |
| `SEM` | `semester` | |
| `MEDIUM` | `medium` | |

---

## Exams (`examsmaster` → `exams`)

| Legacy | New | Notes |
|--------|-----|-------|
| `EXAMID` | Stored in `$examMap` | Primary identifier |
| `EXAMNAME` | `name` | |
| `EXAMTYPE` | `exam_type` | `regular` / `supplementary` / `improvement` |
| `SEMESTER` | `semester` | |
| `MONTH` | `month` | |
| `YEAR` | `year` | |
| `FEE` | `fee_supply_upto2` (supply) or `fee_regular` (regular) | |
| `ABOVE2SUBS` | `fee_regular` | Supply exams — 3+ papers fee |
| `IMPROVEMENT` | `fee_improvement` | |
| `FINE` | `fee_fine` | |

---

## Results (`resultsmaster` → `results`)

| Legacy | New | Notes |
|--------|-----|-------|
| `INT` | `itotal` | MySQL reserved word — accessed as `$row->{'INT'}` |
| `EXT` | `etotal` | |
| `TOTAL` | `marks_secured` | |
| `FL` | `floatation_marks` | |
| `FLDEDCT` | `float_deduct` | |
| `FLSCRIPTCODE` | `fl_scriptcode` | |
| `MODMARKS` | `moderation_marks` | |
| `ACMARKS` | `ac_marks` | |
| `ISMOD` | `is_moderated` | |

---

## Photos

Legacy photos are stored on the legacy server at:
```
/home/uascexams/students.uasckuexams.in/upload/images/{aadhaar}.jpg
/home/uascexams/students.uasckuexams.in/upload/signatures/{aadhaar}.jpg
```

The migration command:
1. Looks up each student's `aadhaar` → `hall_ticket` mapping from DB
2. Copies photo to `storage/app/public/students/photos/{hall_ticket}.jpg`
3. Copies signature to `storage/app/public/students/signatures/{hall_ticket}.jpg`
4. Updates `students.photo_path` / `students.signature_path`
