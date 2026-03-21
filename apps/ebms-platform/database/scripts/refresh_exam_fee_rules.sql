-- ============================================================
-- Refresh exam_fee_rules: new regular fees, preserve supply/improvement
-- Run: mysql -u uascexams_nova -p uascexams_ebmsnova < refresh_exam_fee_rules.sql
-- ============================================================

SET @now = NOW();

-- Step 1: Backup existing supplementary & improvement fees into a temp table
DROP TEMPORARY TABLE IF EXISTS _tmp_existing_fees;

-- Scope backup to exam_id 336 only to avoid fan-out duplicates on the LEFT JOIN
CREATE TEMPORARY TABLE _tmp_existing_fees AS
SELECT exam_id, course, group_code, fee_supply_upto2, fee_improvement, fee_fine
FROM exam_fee_rules
WHERE exam_id = 336;

-- Step 2: Clear the table
DELETE FROM exam_fee_rules;

-- Step 3: Insert new rows
--   - Regular fees from the provided data
--   - Supplementary / improvement / fine carried over from the backup
--   - exam_id taken from the first matching existing row (one exam assumed)

-- Use exam_id = 336 explicitly for all rows
SET @exam_id = 336;

INSERT INTO exam_fee_rules
  (exam_id, course, group_code, fee_regular, fee_supply_upto2, fee_improvement, fee_fine, created_at, updated_at)

SELECT
  @exam_id,
  n.course,
  n.group_code,
  n.fee_regular,
  t.fee_supply_upto2,
  t.fee_improvement,
  COALESCE(t.fee_fine, 0)             AS fee_fine,
  @now,
  @now
FROM (
  -- New regular fee data (Course, Group, Regular_fee)
  SELECT 'BA'   AS course, 'EHPS'  AS group_code, 650 AS fee_regular UNION ALL
  SELECT 'BA',            'EHPSS',               650 UNION ALL
  SELECT 'BA',            'EPP',                 650 UNION ALL
  SELECT 'BA',            'HSO',                 750 UNION ALL
  SELECT 'BA',            'SPP',                 650 UNION ALL
  SELECT 'BA',            'JSP',                 650 UNION ALL
  SELECT 'BCOM',          'GEN',                 650 UNION ALL
  SELECT 'BCOM',          'CA',                  750 UNION ALL
  SELECT 'BCOM',          'FIN',                 750 UNION ALL
  SELECT 'BSC',           'BZC',                 750 UNION ALL
  SELECT 'BSC',           'BZG',                 750 UNION ALL
  SELECT 'BSC',           'BTZC',                750 UNION ALL
  SELECT 'BSC',           'MPC',                 750 UNION ALL
  SELECT 'BSC',           'MPG',                 750 UNION ALL
  SELECT 'BSC',           'MSTCS',               750 UNION ALL
  SELECT 'BSC',           'MPE',                 750 UNION ALL
  SELECT 'BSC',           'MPCS',                750
) AS n
LEFT JOIN _tmp_existing_fees t
  ON t.course = n.course AND t.group_code = n.group_code;

-- Step 4: Cleanup
DROP TEMPORARY TABLE IF EXISTS _tmp_existing_fees;

-- Step 5: Verify
SELECT id, exam_id, course, group_code, fee_regular, fee_supply_upto2, fee_improvement, fee_fine
FROM exam_fee_rules
ORDER BY course, group_code;
