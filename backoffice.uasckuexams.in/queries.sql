INSERT INTO RESULTS_PREMOD(EXAMID,PAPERCODE,HALLTICKET,EID,`CODE`,EXT,ETOTAL,`INT`,ITOTAL)
SELECT triple_entries.examid,triple_entries.papercode,triple_entries.hallticket,triple_entries.enrollment_id,
triple_entries.scriptcode,triple_entries.external_marks,triple_entries.internal_total,triple_entries.internal_total,triple_entries.internal_total FROM triple_entries;