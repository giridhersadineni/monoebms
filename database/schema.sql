CREATE DATABASE IF NOT EXISTS `uascexams_ebms` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `uascexams_ebms`;

CREATE TABLE `admin` (
  `id` int(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


CREATE TABLE `allpapers` (
  `ID` int(11) NOT NULL,
  `YEAR` int(1) DEFAULT NULL,
  `SEM` int(1) DEFAULT NULL,
  `COURSE` varchar(4) DEFAULT NULL,
  `GROUPCODE` varchar(5) DEFAULT NULL,
  `MEDIUM` varchar(2) DEFAULT NULL,
  `PAPERCODE` varchar(10) DEFAULT NULL,
  `PAPERNAME` varchar(73) DEFAULT NULL,
  `PAPERTYPE` varchar(9) DEFAULT NULL,
  `DESCRIPTION` varchar(12) DEFAULT NULL,
  `ELECTIVEGROUP` varchar(2) DEFAULT NULL,
  `PART` int(11) NOT NULL,
  `SCHEME` varchar(10) NOT NULL,
  `ALPHACODE` varchar(8) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE `ALPHACODES` (
  `ID` int(11) NOT NULL,
  `NUMERICCODE` varchar(10) DEFAULT NULL,
  `TITLE` varchar(100) DEFAULT NULL,
  `ALPHACODE` varchar(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE `esview` (
`ID` int(11)
,`EXAMID` int(11)
,`STUDENTID` int(11)
,`HALLTICKET` varchar(20)
,`S1` varchar(11)
,`S2` varchar(11)
,`S3` varchar(11)
,`S4` varchar(11)
,`S5` varchar(11)
,`S6` varchar(11)
,`S7` varchar(11)
,`S8` varchar(11)
,`S9` varchar(11)
,`S10` varchar(11)
,`E1` varchar(11)
,`E2` varchar(11)
,`E3` varchar(11)
,`E4` varchar(11)
,`E5` varchar(10)
,`ENROLLEDDATE` timestamp
,`CHALLANGENERATED` varchar(4)
,`FEEPAID` varchar(4)
,`FEEAMOUNT` int(11)
,`TXNID` varchar(50)
,`CHALLANSUBMITTEDON` date
,`CHALLANRECBY` varchar(20)
,`EXTYPE` varchar(20)
,`stid` int(100)
,`sname` varchar(60)
,`fname` varchar(60)
,`mname` varchar(60)
,`email` varchar(50)
,`dob` varchar(50)
,`gender` varchar(60)
,`phone` varchar(30)
,`medium` varchar(3)
,`group` varchar(50)
,`haltckt` varchar(60)
,`sem` varchar(30)
,`curryear` int(20)
,`aadhar` varchar(12)
,`address` varchar(50)
,`address2` varchar(60)
,`mandal` varchar(50)
,`city` varchar(30)
,`state` varchar(20)
,`pincode` int(30)
,`caste` varchar(10)
,`subcaste` varchar(200)
,`dostid` varchar(50)
,`challenged_quota` enum('NONE','PHYSICALLY CHALLENGED','VISUALLY CHALLENGED','PARTIALLY CHALLENGED')
);

-- --------------------------------------------------------

--
-- Table structure for table `examenrollments`
--

CREATE TABLE `examenrollments` (
  `ID` int(11) NOT NULL,
  `EXAMID` int(11) NOT NULL,
  `STUDENTID` int(11) NOT NULL,
  `HALLTICKET` varchar(20) NOT NULL,
  `S1` varchar(11) DEFAULT NULL,
  `S2` varchar(11) DEFAULT NULL,
  `S3` varchar(11) DEFAULT NULL,
  `S4` varchar(11) DEFAULT NULL,
  `S5` varchar(11) DEFAULT NULL,
  `S6` varchar(11) DEFAULT NULL,
  `S7` varchar(11) DEFAULT NULL,
  `S8` varchar(11) DEFAULT NULL,
  `S9` varchar(11) DEFAULT NULL,
  `S10` varchar(11) DEFAULT NULL,
  `E1` varchar(11) DEFAULT NULL,
  `E2` varchar(11) DEFAULT NULL,
  `E3` varchar(11) DEFAULT NULL,
  `E4` varchar(11) DEFAULT NULL,
  `E5` varchar(10) NOT NULL,
  `ENROLLEDDATE` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `CHALLANGENERATED` varchar(4) DEFAULT NULL,
  `FEEPAID` varchar(4) DEFAULT NULL,
  `FEEAMOUNT` int(11) DEFAULT NULL,
  `TXNID` varchar(50) DEFAULT NULL,
  `CHALLANSUBMITTEDON` date DEFAULT NULL,
  `CHALLANRECBY` varchar(20) DEFAULT NULL,
  `EXTYPE` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


CREATE TABLE `examsmaster` (
  `EXID` int(11) NOT NULL,
  `SEMESTER` int(11) NOT NULL,
  `COURSE` varchar(10) NOT NULL,
  `EXAMNAME` varchar(200) NOT NULL,
  `MONTH` varchar(13) NOT NULL,
  `YEAR` varchar(4) NOT NULL,
  `EXAMTYPE` varchar(20) NOT NULL,
  `STATUS` varchar(50) NOT NULL,
  `FEE` int(15) NOT NULL,
  `BA_FEE` int(11) NOT NULL,
  `BCOM_FEE` int(11) NOT NULL,
  `BSC_FEE` int(11) NOT NULL,
  `BCOMCA_FEE` int(11) NOT NULL,
  `BAHONS_FEE` int(11) NOT NULL,
  `BA_ABOVE_2` int(11) NOT NULL,
  `BCOM_ABOVE_2` int(11) NOT NULL,
  `BCOMCA_ABOVE_2` int(11) NOT NULL,
  `BSC_ABOVE_2` int(11) NOT NULL,
  `BAHONS_ABOVE_2` int(11) NOT NULL,
  `ABOVE2SUBS` int(15) NOT NULL,
  `IMPROVEMENT` int(15) NOT NULL,
  `FINE` int(11) NOT NULL,
  `SCHEME` varchar(10) NOT NULL,
  `REVALOPEN` tinyint(1) NOT NULL,
  `MON_YEAR` varchar(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE `gpas` (
  `GPAID` int(11) NOT NULL,
  `EXAMID` int(2) DEFAULT NULL,
  `HALLTICKET` varchar(20) DEFAULT NULL,
  `TOTAL` int(3) DEFAULT NULL,
  `RESULT` varchar(1) DEFAULT NULL,
  `SGPA` decimal(3,2) DEFAULT NULL,
  `CGPA` decimal(3,2) DEFAULT NULL,
  `PROCESSID` varchar(25) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


CREATE TABLE `grades` (
  `ID` int(15) NOT NULL,
  `MEMO_NO` int(20) NOT NULL,
  `HALLTICKET` varchar(25) NOT NULL,
  `S1GPA` float NOT NULL,
  `S2GPA` float NOT NULL,
  `S3GPA` float NOT NULL,
  `S4GPA` float NOT NULL,
  `S5GPA` float NOT NULL,
  `S6GPA` float NOT NULL,
  `P1_YOP` varchar(30) NOT NULL,
  `P2_YOP` varchar(30) NOT NULL,
  `ALL_YOP` varchar(30) NOT NULL,
  `ADM_YEAR` varchar(30) NOT NULL,
  `REMARKS` varchar(400) NOT NULL,
  `P1CGPA` float NOT NULL,
  `P2CGPA` float NOT NULL,
  `ALLCGPA` float NOT NULL,
  `P1CF` float NOT NULL,
  `P2CF` float NOT NULL,
  `ALLCF` float NOT NULL,
  `P1DIV` varchar(100) NOT NULL,
  `P2DIV` varchar(100) NOT NULL,
  `FINALDIV` varchar(100) NOT NULL,
  `P1SUBS` varchar(100) NOT NULL,
  `P2SUBS` varchar(300) NOT NULL,
  `P2SUB1` varchar(100) NOT NULL,
  `P2SUB2` varchar(100) NOT NULL,
  `P2SUB3` varchar(100) NOT NULL,
  `P1SUB2` varchar(100) NOT NULL,
  `P1SUB1` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  `modified_by` int(11) NOT NULL,
  `TABULATION_GROUP` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


CREATE TABLE `RESULTS` (
  `RHID` int(11) NOT NULL,
  `EXAMID` int(11) NOT NULL,
  `PAPERCODE` varchar(8) DEFAULT NULL,
  `PAPERNAME` varchar(100) DEFAULT NULL,
  `PART` int(11) NOT NULL,
  `HALLTICKET` varchar(20) DEFAULT NULL,
  `EID` varchar(8) DEFAULT NULL,
  `SCRIPTCODE` varchar(6) DEFAULT NULL,
  `PASSED_BY_FLOATATION` tinyint(1) NOT NULL,
  `IS_MALPRACTICE` tinyint(1) NOT NULL,
  `IS_AB_EXTERNAL` tinyint(1) NOT NULL,
  `IS_AB_INTERNAL` tinyint(1) NOT NULL,
  `EXT` int(3) DEFAULT NULL,
  `MARKS_SECURED` int(11) NOT NULL,
  `ETOTAL` int(3) DEFAULT NULL,
  `INT` int(4) DEFAULT NULL,
  `ITOTAL` int(4) DEFAULT NULL,
  `RESULT` varchar(4) DEFAULT NULL,
  `CREDITS` int(1) DEFAULT NULL,
  `MARKS` int(11) DEFAULT NULL,
  `TOTALMARKS` varchar(6) DEFAULT NULL,
  `PERCENTAGE` varchar(7) DEFAULT NULL,
  `GRADE` varchar(7) DEFAULT NULL,
  `GPV` float NOT NULL DEFAULT 0,
  `GPC` float NOT NULL DEFAULT 0,
  `UPLOADID` varchar(20) NOT NULL,
  `SEMESTER` int(11) NOT NULL,
  `MYEAR` varchar(20) NOT NULL,
  `GPVV` float NOT NULL DEFAULT 0,
  `GPCC` float NOT NULL DEFAULT 0,
  `MARKER` varchar(50) DEFAULT NULL,
  `ATTEMPTS` int(11) DEFAULT NULL,
  `STATUS` varchar(50) DEFAULT NULL,
  `FLOATATION_MARKS` int(11) NOT NULL,
  `AC_MARKS` int(11) NOT NULL,
  `MODERATION_MARKS` int(11) NOT NULL,
  `IS_MODERATED` int(11) NOT NULL,
  `FLOAT_DEDUCT` int(11) NOT NULL,
  `FL_SCRIPTCODE` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_deleted` bit(1) NOT NULL DEFAULT b'1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


CREATE TABLE `RESULTS_FLOATATION` (
  `RHID` int(11) NOT NULL,
  `EXAMID` int(11) NOT NULL,
  `PAPERCODE` varchar(8) DEFAULT NULL,
  `PAPERNAME` varchar(100) DEFAULT NULL,
  `PART` int(11) NOT NULL,
  `HALLTICKET` varchar(20) DEFAULT NULL,
  `EID` varchar(8) DEFAULT NULL,
  `SCRIPTCODE` varchar(6) DEFAULT NULL,
  `PASSED_BY_FLOATATION` tinyint(1) NOT NULL,
  `IS_MALPRACTICE` tinyint(1) NOT NULL,
  `IS_AB_EXTERNAL` tinyint(1) NOT NULL,
  `IS_AB_INTERNAL` tinyint(1) NOT NULL,
  `EXT` int(3) DEFAULT NULL,
  `MARKS_SECURED` int(11) NOT NULL,
  `ETOTAL` int(3) DEFAULT NULL,
  `INT` int(4) DEFAULT NULL,
  `ITOTAL` int(4) DEFAULT NULL,
  `RESULT` varchar(4) DEFAULT NULL,
  `CREDITS` int(1) DEFAULT NULL,
  `MARKS` int(11) DEFAULT NULL,
  `TOTALMARKS` varchar(6) DEFAULT NULL,
  `PERCENTAGE` varchar(7) DEFAULT NULL,
  `GRADE` varchar(7) DEFAULT NULL,
  `GPV` float NOT NULL DEFAULT 0,
  `GPC` float NOT NULL DEFAULT 0,
  `UPLOADID` varchar(20) NOT NULL,
  `SEMESTER` int(11) NOT NULL,
  `MYEAR` varchar(20) NOT NULL,
  `GPVV` float NOT NULL DEFAULT 0,
  `GPCC` float NOT NULL DEFAULT 0,
  `MARKER` varchar(50) DEFAULT NULL,
  `ATTEMPTS` int(11) DEFAULT NULL,
  `STATUS` varchar(50) DEFAULT NULL,
  `FLOATATION_MARKS` int(11) NOT NULL,
  `AC_MARKS` int(11) NOT NULL,
  `MODERATION_MARKS` int(11) NOT NULL,
  `IS_MODERATED` int(11) NOT NULL,
  `FLOAT_DEDUCT` int(11) NOT NULL,
  `FL_SCRIPTCODE` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_deleted` bit(1) NOT NULL DEFAULT b'1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;



CREATE TABLE `RESULTS_POSTMOD` (
  `RHID` int(11) NOT NULL,
  `EXAMID` int(11) NOT NULL,
  `PAPERCODE` varchar(8) DEFAULT NULL,
  `PAPERNAME` varchar(100) DEFAULT NULL,
  `PART` int(11) NOT NULL,
  `HALLTICKET` varchar(20) DEFAULT NULL,
  `EID` varchar(8) DEFAULT NULL,
  `SCRIPTCODE` varchar(6) DEFAULT NULL,
  `PASSED_BY_FLOATATION` tinyint(1) NOT NULL,
  `IS_MALPRACTICE` tinyint(1) NOT NULL,
  `IS_AB_EXTERNAL` tinyint(1) NOT NULL,
  `IS_AB_INTERNAL` tinyint(1) NOT NULL,
  `EXT` int(3) DEFAULT NULL,
  `MARKS_SECURED` int(11) NOT NULL,
  `ETOTAL` int(3) DEFAULT NULL,
  `INT` int(4) DEFAULT NULL,
  `ITOTAL` int(4) DEFAULT NULL,
  `RESULT` varchar(4) DEFAULT NULL,
  `CREDITS` int(1) DEFAULT NULL,
  `MARKS` int(11) DEFAULT NULL,
  `TOTALMARKS` varchar(6) DEFAULT NULL,
  `PERCENTAGE` varchar(7) DEFAULT NULL,
  `GRADE` varchar(7) DEFAULT NULL,
  `GPV` float NOT NULL DEFAULT 0,
  `GPC` float NOT NULL DEFAULT 0,
  `UPLOADID` varchar(20) NOT NULL,
  `SEMESTER` int(11) NOT NULL,
  `MYEAR` varchar(20) NOT NULL,
  `GPVV` float NOT NULL DEFAULT 0,
  `GPCC` float NOT NULL DEFAULT 0,
  `MARKER` varchar(50) DEFAULT NULL,
  `ATTEMPTS` int(11) DEFAULT NULL,
  `STATUS` varchar(50) DEFAULT NULL,
  `FLOATATION_MARKS` int(11) NOT NULL,
  `AC_MARKS` int(11) NOT NULL,
  `MODERATION_MARKS` int(11) NOT NULL,
  `IS_MODERATED` int(11) NOT NULL,
  `FLOAT_DEDUCT` int(11) NOT NULL,
  `FL_SCRIPTCODE` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_deleted` bit(1) NOT NULL DEFAULT b'1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;



CREATE TABLE `RESULTS_STAGE` (
  `RHID` int(11) NOT NULL,
  `EXAMID` int(11) NOT NULL,
  `PAPERCODE` varchar(8) DEFAULT NULL,
  `PAPERNAME` varchar(100) DEFAULT NULL,
  `PART` int(11) NOT NULL,
  `HALLTICKET` varchar(20) DEFAULT NULL,
  `EID` varchar(8) DEFAULT NULL,
  `SCRIPTCODE` varchar(6) DEFAULT NULL,
  `PASSED_BY_FLOATATION` tinyint(1) NOT NULL,
  `IS_MALPRACTICE` tinyint(1) NOT NULL,
  `IS_AB_EXTERNAL` tinyint(1) NOT NULL,
  `IS_AB_INTERNAL` tinyint(1) NOT NULL,
  `EXT` int(3) DEFAULT NULL,
  `MARKS_SECURED` int(11) NOT NULL,
  `ETOTAL` int(3) DEFAULT NULL,
  `INT` int(4) DEFAULT NULL,
  `ITOTAL` int(4) DEFAULT NULL,
  `RESULT` varchar(4) DEFAULT NULL,
  `CREDITS` int(1) DEFAULT NULL,
  `MARKS` int(11) DEFAULT NULL,
  `TOTALMARKS` varchar(6) DEFAULT NULL,
  `PERCENTAGE` varchar(7) DEFAULT NULL,
  `GRADE` varchar(7) DEFAULT NULL,
  `GPV` float NOT NULL DEFAULT 0,
  `GPC` float NOT NULL DEFAULT 0,
  `UPLOADID` varchar(20) NOT NULL,
  `SEMESTER` int(11) NOT NULL,
  `MYEAR` varchar(20) NOT NULL,
  `GPVV` float NOT NULL DEFAULT 0,
  `GPCC` float NOT NULL DEFAULT 0,
  `MARKER` varchar(50) DEFAULT NULL,
  `ATTEMPTS` int(11) DEFAULT NULL,
  `STATUS` varchar(50) DEFAULT NULL,
  `FLOATATION_MARKS` int(11) NOT NULL,
  `AC_MARKS` int(11) NOT NULL,
  `MODERATION_MARKS` int(11) NOT NULL,
  `IS_MODERATED` int(11) NOT NULL,
  `FLOAT_DEDUCT` int(11) NOT NULL,
  `FL_SCRIPTCODE` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_deleted` bit(1) NOT NULL DEFAULT b'1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


CREATE TABLE `revaluationenrollments` (
  `ID` int(15) NOT NULL,
  `EXAMID` int(11) NOT NULL,
  `STUDENTID` int(11) NOT NULL,
  `HALLTICKET` varchar(50) NOT NULL,
  `S1` varchar(11) DEFAULT NULL,
  `S2` varchar(11) DEFAULT NULL,
  `S3` varchar(11) DEFAULT NULL,
  `S4` varchar(11) DEFAULT NULL,
  `S5` varchar(11) DEFAULT NULL,
  `S6` varchar(11) DEFAULT NULL,
  `S7` varchar(11) DEFAULT NULL,
  `S8` varchar(11) DEFAULT NULL,
  `S9` varchar(11) DEFAULT NULL,
  `S10` varchar(11) DEFAULT NULL,
  `E1` varchar(11) DEFAULT NULL,
  `E2` varchar(11) DEFAULT NULL,
  `E3` varchar(11) DEFAULT NULL,
  `E4` varchar(11) DEFAULT NULL,
  `ENROLLEDDATE` timestamp NULL DEFAULT current_timestamp(),
  `CHALLANNUMBER` int(11) DEFAULT NULL,
  `FEEPAID` tinyint(20) DEFAULT NULL,
  `FEEAMOUNT` int(11) DEFAULT NULL,
  `CHALLANSUBMITTEDON` varchar(50) DEFAULT NULL,
  `CHALLANRECBY` varchar(20) DEFAULT NULL,
  `EXTYPE` varchar(15) DEFAULT NULL,
  `challanrecd` tinyint(1) NOT NULL DEFAULT 0,
  `STATUS` enum('OPEN','CLOSE','INVALID','') NOT NULL DEFAULT 'CLOSE'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


CREATE TABLE `students` (
  `stid` int(100) NOT NULL,
  `sname` varchar(60) NOT NULL,
  `fname` varchar(60) NOT NULL,
  `mname` varchar(60) NOT NULL,
  `email` varchar(50) NOT NULL,
  `dob` varchar(50) NOT NULL,
  `gender` varchar(60) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `medium` varchar(3) NOT NULL,
  `course` varchar(20) NOT NULL,
  `course_name` varchar(225) DEFAULT NULL,
  `course_type` varchar(225) DEFAULT NULL,
  `second_language` varchar(225) DEFAULT NULL,
  `group` varchar(50) NOT NULL,
  `haltckt` varchar(60) NOT NULL,
  `sem` varchar(30) NOT NULL,
  `curryear` int(20) NOT NULL,
  `aadhar` varchar(12) NOT NULL,
  `address` varchar(50) NOT NULL,
  `address2` varchar(60) NOT NULL,
  `mandal` varchar(50) NOT NULL,
  `city` varchar(30) NOT NULL,
  `state` varchar(20) NOT NULL,
  `pincode` int(30) NOT NULL,
  `SCHEME` varchar(10) NOT NULL,
  `caste` varchar(10) NOT NULL,
  `subcaste` varchar(200) NOT NULL,
  `dostid` varchar(50) NOT NULL,
  `challenged_quota` enum('NONE','PHYSICALLY CHALLENGED','VISUALLY CHALLENGED','PARTIALLY CHALLENGED') NOT NULL,
  `identification_mark_1` varchar(225) DEFAULT NULL,
  `identification_mark_2` varchar(225) DEFAULT NULL,
  `ssc_hallticket` int(225) DEFAULT NULL,
  `inter_hallticket` int(225) DEFAULT NULL,
  `parent_mobile` int(225) DEFAULT NULL,
  `CMM_NO` varchar(20) NOT NULL,
  `full_address` text NOT NULL,
  `ph_category` varchar(10) NOT NULL,
  `date_of_joining` varchar(50) NOT NULL,
  `onboarding_complete` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;




CREATE TABLE `TABULATION_2019` (
  `RHID` int(11) NOT NULL,
  `EXAMID` int(11) NOT NULL,
  `PAPERCODE` varchar(8) DEFAULT NULL,
  `PAPERNAME` varchar(100) DEFAULT NULL,
  `PART` int(11) NOT NULL,
  `HALLTICKET` varchar(20) DEFAULT NULL,
  `EID` varchar(8) DEFAULT NULL,
  `SCRIPTCODE` varchar(6) DEFAULT NULL,
  `PASSED_BY_FLOATATION` tinyint(1) NOT NULL,
  `IS_MALPRACTICE` tinyint(1) NOT NULL,
  `IS_AB_EXTERNAL` tinyint(1) NOT NULL,
  `IS_AB_INTERNAL` tinyint(1) NOT NULL,
  `EXT` int(3) DEFAULT NULL,
  `ETOTAL` int(3) DEFAULT NULL,
  `INT` int(4) DEFAULT NULL,
  `ITOTAL` int(4) DEFAULT NULL,
  `RESULT` varchar(4) DEFAULT NULL,
  `CREDITS` int(1) DEFAULT NULL,
  `MARKS` int(11) DEFAULT NULL,
  `TOTALMARKS` varchar(6) DEFAULT NULL,
  `PERCENTAGE` varchar(7) DEFAULT NULL,
  `GRADE` varchar(7) DEFAULT NULL,
  `GPV` float NOT NULL DEFAULT 0,
  `GPC` float NOT NULL DEFAULT 0,
  `UPLOADID` varchar(20) NOT NULL,
  `SEMESTER` int(11) NOT NULL,
  `MYEAR` varchar(20) NOT NULL,
  `GPVV` float NOT NULL DEFAULT 0,
  `GPCC` float NOT NULL DEFAULT 0,
  `MARKER` varchar(50) DEFAULT NULL,
  `ATTEMPTS` int(11) DEFAULT NULL,
  `STATUS` varchar(50) DEFAULT NULL,
  `FLOATATION_MARKS` int(11) NOT NULL,
  `AC_MARKS` int(11) NOT NULL,
  `MODERATION_MARKS` int(11) NOT NULL,
  `IS_MODERATED` int(11) NOT NULL,
  `FLOAT_DEDUCT` int(11) NOT NULL,
  `FL_SCRIPTCODE` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


CREATE TABLE `TABULATION_2020` (
  `RHID` int(11) NOT NULL,
  `EXAMID` int(11) NOT NULL,
  `PAPERCODE` varchar(8) DEFAULT NULL,
  `PAPERNAME` varchar(100) DEFAULT NULL,
  `PART` int(11) NOT NULL,
  `HALLTICKET` varchar(20) DEFAULT NULL,
  `EID` varchar(8) DEFAULT NULL,
  `SCRIPTCODE` varchar(6) DEFAULT NULL,
  `PASSED_BY_FLOATATION` tinyint(1) NOT NULL,
  `IS_MALPRACTICE` tinyint(1) NOT NULL,
  `IS_AB_EXTERNAL` tinyint(1) NOT NULL,
  `IS_AB_INTERNAL` tinyint(1) NOT NULL,
  `EXT` int(3) DEFAULT NULL,
  `ETOTAL` int(3) DEFAULT NULL,
  `INT` int(4) DEFAULT NULL,
  `ITOTAL` int(4) DEFAULT NULL,
  `RESULT` varchar(4) DEFAULT NULL,
  `CREDITS` int(1) DEFAULT NULL,
  `MARKS` int(11) DEFAULT NULL,
  `TOTALMARKS` varchar(6) DEFAULT NULL,
  `PERCENTAGE` varchar(7) DEFAULT NULL,
  `GRADE` varchar(7) DEFAULT NULL,
  `GPV` float NOT NULL DEFAULT 0,
  `GPC` float NOT NULL DEFAULT 0,
  `UPLOADID` varchar(20) NOT NULL,
  `SEMESTER` int(11) NOT NULL,
  `MYEAR` varchar(20) NOT NULL,
  `GPVV` float NOT NULL DEFAULT 0,
  `GPCC` float NOT NULL DEFAULT 0,
  `MARKER` varchar(50) DEFAULT NULL,
  `ATTEMPTS` int(11) DEFAULT NULL,
  `STATUS` varchar(50) DEFAULT NULL,
  `FLOATATION_MARKS` int(11) NOT NULL,
  `AC_MARKS` int(11) NOT NULL,
  `MODERATION_MARKS` int(11) NOT NULL,
  `IS_MODERATED` int(11) NOT NULL,
  `FLOAT_DEDUCT` int(11) NOT NULL,
  `FL_SCRIPTCODE` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE `tabulation_monthyears` (
  `TAB_ID` int(11) NOT NULL,
  `TABULATION_YEAR` int(11) DEFAULT NULL,
  `MONTH_YEAR` varchar(20) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `allpapers`
--
ALTER TABLE `allpapers`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `ALPHACODES`
--
ALTER TABLE `ALPHACODES`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `examenrollments`
--
ALTER TABLE `examenrollments`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `idx_enroll_stud` (`STUDENTID`),
  ADD KEY `idx_enroll_exam` (`EXAMID`),
  ADD KEY `idx_enroll_hallticket` (`HALLTICKET`);

--
-- Indexes for table `examsmaster`
--
ALTER TABLE `examsmaster`
  ADD PRIMARY KEY (`EXID`),
  ADD KEY `idx_examsmaster_exid` (`EXID`);

--
-- Indexes for table `gpas`
--
ALTER TABLE `gpas`
  ADD PRIMARY KEY (`GPAID`);

--
-- Indexes for table `grades`
--
ALTER TABLE `grades`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `HALLTICKET` (`HALLTICKET`);

--
-- Indexes for table `RESULTS`
--
ALTER TABLE `RESULTS`
  ADD PRIMARY KEY (`RHID`),
  ADD KEY `PC_INDEX` (`PAPERCODE`,`SCRIPTCODE`,`HALLTICKET`,`EXAMID`,`SEMESTER`);

--
-- Indexes for table `RESULTS_FLOATATION`
--
ALTER TABLE `RESULTS_FLOATATION`
  ADD PRIMARY KEY (`RHID`),
  ADD KEY `PC_INDEX` (`PAPERCODE`,`SCRIPTCODE`,`HALLTICKET`,`EXAMID`,`SEMESTER`);

--
-- Indexes for table `RESULTS_POSTMOD`
--
ALTER TABLE `RESULTS_POSTMOD`
  ADD PRIMARY KEY (`RHID`),
  ADD KEY `PC_INDEX` (`PAPERCODE`,`SCRIPTCODE`,`HALLTICKET`,`EXAMID`,`SEMESTER`);

--
-- Indexes for table `RESULTS_PREMOD`
--
ALTER TABLE `RESULTS_PREMOD`
  ADD PRIMARY KEY (`RHID`),
  ADD KEY `PC_INDEX` (`PAPERCODE`,`SCRIPTCODE`,`HALLTICKET`,`EXAMID`,`SEMESTER`);

--
-- Indexes for table `RESULTS_STAGE`
--
ALTER TABLE `RESULTS_STAGE`
  ADD PRIMARY KEY (`RHID`),
  ADD KEY `PC_INDEX` (`PAPERCODE`,`SCRIPTCODE`,`HALLTICKET`,`EXAMID`,`SEMESTER`);

--
-- Indexes for table `revaluationenrollments`
--
ALTER TABLE `revaluationenrollments`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`stid`),
  ADD KEY `idx_students_hallticket` (`haltckt`),
  ADD KEY `idx_students_stid` (`stid`);

--
-- Indexes for table `TABULATION_2019`
--
ALTER TABLE `TABULATION_2019`
  ADD PRIMARY KEY (`RHID`),
  ADD KEY `PC_INDEX` (`PAPERCODE`,`SCRIPTCODE`,`HALLTICKET`,`EXAMID`,`SEMESTER`),
  ADD KEY `idx_tabulation_student_sem` (`HALLTICKET`,`SEMESTER`);

--
-- Indexes for table `TABULATION_2020`
--
ALTER TABLE `TABULATION_2020`
  ADD PRIMARY KEY (`RHID`),
  ADD KEY `PC_INDEX` (`PAPERCODE`,`SCRIPTCODE`,`HALLTICKET`,`EXAMID`,`SEMESTER`);

--
-- Indexes for table `TABULATION_2021`
--
ALTER TABLE `TABULATION_2021`
  ADD PRIMARY KEY (`RHID`),
  ADD KEY `PC_INDEX` (`PAPERCODE`,`SCRIPTCODE`,`HALLTICKET`,`EXAMID`,`SEMESTER`),
  ADD KEY `idx_tabulation_student_sem` (`HALLTICKET`,`SEMESTER`);

--
-- Indexes for table `TABULATION_2022`
--
ALTER TABLE `TABULATION_2022`
  ADD PRIMARY KEY (`RHID`),
  ADD KEY `PC_INDEX` (`PAPERCODE`,`SCRIPTCODE`,`HALLTICKET`,`EXAMID`,`SEMESTER`),
  ADD KEY `idx_tabulation_student_sem` (`HALLTICKET`,`SEMESTER`);

--
-- Indexes for table `TABULATION_2023`
--
ALTER TABLE `TABULATION_2023`
  ADD PRIMARY KEY (`RHID`),
  ADD KEY `PC_INDEX` (`PAPERCODE`,`SCRIPTCODE`,`HALLTICKET`,`EXAMID`,`SEMESTER`);

--
-- Indexes for table `TABULATION_2024`
--
ALTER TABLE `TABULATION_2024`
  ADD PRIMARY KEY (`RHID`),
  ADD KEY `PC_INDEX` (`PAPERCODE`,`SCRIPTCODE`,`HALLTICKET`,`EXAMID`,`SEMESTER`);

--
-- Indexes for table `TABULATION_2025`
--
ALTER TABLE `TABULATION_2025`
  ADD PRIMARY KEY (`RHID`),
  ADD KEY `PC_INDEX` (`PAPERCODE`,`SCRIPTCODE`,`HALLTICKET`,`EXAMID`,`SEMESTER`);

--
-- Indexes for table `tabulation_monthyears`
--
ALTER TABLE `tabulation_monthyears`
  ADD PRIMARY KEY (`TAB_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `allpapers`
--
ALTER TABLE `allpapers`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6260;

--
-- AUTO_INCREMENT for table `examenrollments`
--
ALTER TABLE `examenrollments`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1606017;

--
-- AUTO_INCREMENT for table `examsmaster`
--
ALTER TABLE `examsmaster`
  MODIFY `EXID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=344448;

--
-- AUTO_INCREMENT for table `gpas`
--
ALTER TABLE `gpas`
  MODIFY `GPAID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76468;

--
-- AUTO_INCREMENT for table `grades`
--
ALTER TABLE `grades`
  MODIFY `ID` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3635;

--
-- AUTO_INCREMENT for table `RESULTS`
--
ALTER TABLE `RESULTS`
  MODIFY `RHID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44637799;

--
-- AUTO_INCREMENT for table `RESULTS_FLOATATION`
--
ALTER TABLE `RESULTS_FLOATATION`
  MODIFY `RHID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32769;

--
-- AUTO_INCREMENT for table `RESULTS_POSTMOD`
--
ALTER TABLE `RESULTS_POSTMOD`
  MODIFY `RHID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32769;

--
-- AUTO_INCREMENT for table `RESULTS_PREMOD`
--
ALTER TABLE `RESULTS_PREMOD`
  MODIFY `RHID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32769;

--
-- AUTO_INCREMENT for table `RESULTS_STAGE`
--
ALTER TABLE `RESULTS_STAGE`
  MODIFY `RHID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2229;

--
-- AUTO_INCREMENT for table `revaluationenrollments`
--
ALTER TABLE `revaluationenrollments`
  MODIFY `ID` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5880;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `stid` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1004672;

--
-- AUTO_INCREMENT for table `TABULATION_2019`
--
ALTER TABLE `TABULATION_2019`
  MODIFY `RHID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44471858;

--
-- AUTO_INCREMENT for table `TABULATION_2020`
--
ALTER TABLE `TABULATION_2020`
  MODIFY `RHID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44505078;

--
-- AUTO_INCREMENT for table `TABULATION_2021`
--
ALTER TABLE `TABULATION_2021`
  MODIFY `RHID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44501727;

--
-- AUTO_INCREMENT for table `TABULATION_2022`
--
ALTER TABLE `TABULATION_2022`
  MODIFY `RHID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44505076;

--
-- AUTO_INCREMENT for table `TABULATION_2023`
--
ALTER TABLE `TABULATION_2023`
  MODIFY `RHID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44578932;

--
-- AUTO_INCREMENT for table `TABULATION_2024`
--
ALTER TABLE `TABULATION_2024`
  MODIFY `RHID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44568374;

--
-- AUTO_INCREMENT for table `TABULATION_2025`
--
ALTER TABLE `TABULATION_2025`
  MODIFY `RHID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44587067;

--
-- AUTO_INCREMENT for table `tabulation_monthyears`
--
ALTER TABLE `tabulation_monthyears`
  MODIFY `TAB_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

-- --------------------------------------------------------

--
-- Structure for view `esview`
--
DROP TABLE IF EXISTS `esview`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `esview`  AS SELECT `examenrollments`.`ID` AS `ID`, `examenrollments`.`EXAMID` AS `EXAMID`, `examenrollments`.`STUDENTID` AS `STUDENTID`, `examenrollments`.`HALLTICKET` AS `HALLTICKET`, `examenrollments`.`S1` AS `S1`, `examenrollments`.`S2` AS `S2`, `examenrollments`.`S3` AS `S3`, `examenrollments`.`S4` AS `S4`, `examenrollments`.`S5` AS `S5`, `examenrollments`.`S6` AS `S6`, `examenrollments`.`S7` AS `S7`, `examenrollments`.`S8` AS `S8`, `examenrollments`.`S9` AS `S9`, `examenrollments`.`S10` AS `S10`, `examenrollments`.`E1` AS `E1`, `examenrollments`.`E2` AS `E2`, `examenrollments`.`E3` AS `E3`, `examenrollments`.`E4` AS `E4`, `examenrollments`.`E5` AS `E5`, `examenrollments`.`ENROLLEDDATE` AS `ENROLLEDDATE`, `examenrollments`.`CHALLANGENERATED` AS `CHALLANGENERATED`, `examenrollments`.`FEEPAID` AS `FEEPAID`, `examenrollments`.`FEEAMOUNT` AS `FEEAMOUNT`, `examenrollments`.`TXNID` AS `TXNID`, `examenrollments`.`CHALLANSUBMITTEDON` AS `CHALLANSUBMITTEDON`, `examenrollments`.`CHALLANRECBY` AS `CHALLANRECBY`, `examenrollments`.`EXTYPE` AS `EXTYPE`, `students`.`stid` AS `stid`, `students`.`sname` AS `sname`, `students`.`fname` AS `fname`, `students`.`mname` AS `mname`, `students`.`email` AS `email`, `students`.`dob` AS `dob`, `students`.`gender` AS `gender`, `students`.`phone` AS `phone`, `students`.`medium` AS `medium`, `students`.`group` AS `group`, `students`.`haltckt` AS `haltckt`, `students`.`sem` AS `sem`, `students`.`curryear` AS `curryear`, `students`.`aadhar` AS `aadhar`, `students`.`address` AS `address`, `students`.`address2` AS `address2`, `students`.`mandal` AS `mandal`, `students`.`city` AS `city`, `students`.`state` AS `state`, `students`.`pincode` AS `pincode`, `students`.`caste` AS `caste`, `students`.`subcaste` AS `subcaste`, `students`.`dostid` AS `dostid`, `students`.`challenged_quota` AS `challenged_quota` FROM (`examenrollments` left join `students` on(`examenrollments`.`HALLTICKET` = `students`.`haltckt`)) ;
COMMIT;
