-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 13, 2023 at 05:42 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shawapp`
--

-- --------------------------------------------------------

--
-- Table structure for table `appcenter`
--

CREATE TABLE `appcenter` (
  `ID` int(11) NOT NULL,
  `DocType` varchar(8) NOT NULL COMMENT 'ประเภทการอนุมัติเอกสาร',
  `LvApp` int(1) NOT NULL DEFAULT 0 COMMENT 'ลำดับการอนุมัติ (0 = สถานะเดียว  / > 0 ให้ยืดเลขที่มากกว่า)',
  `AppCode` varchar(8) NOT NULL COMMENT 'รหัสการอนุมัติ',
  `AppName` varchar(32) NOT NULL COMMENT 'ชื่อการอนุมัติ',
  `AppUser` varchar(16) NOT NULL COMMENT 'LvClass / LvCode ของผู้มีสิทธิ์อนุมัติ',
  `StepApprove` int(11) NOT NULL COMMENT 'ลำดับการอนุมัติ',
  `AppType` enum('A','B','C','D','E') NOT NULL COMMENT 'ประเภทการอนุมัติ (อ่านหมายเหตุด้านล่าง)',
  `AppCond` varchar(32) NOT NULL DEFAULT 'N' COMMENT 'เงื่อนไขการอนุมัติ (N = ไม่มีเงื่อนไข)',
  `TeamCode` varchar(8) DEFAULT NULL COMMENT 'รหัสทีมขาย / ฝ่าย',
  `RowStatus` enum('A','I') NOT NULL DEFAULT 'A' COMMENT 'สถานะของ Record (A = Active / I = Inactive)',
  `uKeyCreate` varchar(32) NOT NULL COMMENT 'uKey ผู้สร้าง Record นี้',
  `DateCreate` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'วันที่สร้าง Record นี้',
  `uKeyUpdate` varchar(32) DEFAULT NULL COMMENT 'uKey ผู้อัพเดต Record นี้',
  `DateUpdate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'วันที่อัพเดต Record นี้'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `config_general`
--

CREATE TABLE `config_general` (
  `ConfigID` int(11) NOT NULL,
  `ConfigGroup` varchar(64) NOT NULL,
  `ConfigName` varchar(512) NOT NULL COMMENT 'หัวข้อการตั้งค่า',
  `ConfigValue` varchar(128) NOT NULL COMMENT 'ค่าที่กำหนด',
  `UkeyUpdate` varchar(32) DEFAULT NULL COMMENT 'Ukey ผู้บันทึกข้อมูลล่าสุด',
  `ssidUpdate` varchar(48) DEFAULT NULL COMMENT 'SSID ของผู้สร้าง Record นี้',
  `DateUpdate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'วันที่บันทึกข้อมูลล่าสุด'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `config_general`
--

INSERT INTO `config_general` (`ConfigID`, `ConfigGroup`, `ConfigName`, `ConfigValue`, `UkeyUpdate`, `ssidUpdate`, `DateUpdate`) VALUES
(1, 'sale_ar', 'มูลค่ายอดขายท้ายบิลขั้นต่ำที่จะไม่ต้องให้ผู้จัดการขายอนุมัติ (บาท) <small class=\"text-muted\">(ราคาหลังหักส่วนลด และรวม VAT แล้ว)</small>', '6000', '200ceb26807d6bf99fd6f4f0d1ca54d4', 'dg8kj0ugco6u7b797q6b7asf43::1700127512', '2023-12-06 04:41:30');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `DeptCode` varchar(8) NOT NULL COMMENT 'รหัสประจำฝ่าย',
  `DeptName` varchar(32) NOT NULL COMMENT 'ชื่อฝ่าย',
  `uKeyCreate` varchar(32) NOT NULL COMMENT 'uKey ผู้สร้าง Record นี้',
  `DateCreate` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'วันที่สร้าง Record นี้',
  `uKeyUpdate` varchar(32) DEFAULT NULL COMMENT 'uKey ผู้อัพเดต Record นี้',
  `DateUpdate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'วันที่อัพเดต Record นี้'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`DeptCode`, `DeptName`, `uKeyCreate`, `DateCreate`, `uKeyUpdate`, `DateUpdate`) VALUES
('DP000', 'IT', 'start_record', '2023-10-19 09:37:48', NULL, NULL),
('DP001', 'Management', 'start_record', '2023-10-19 09:37:48', NULL, NULL),
('DP002', 'Marketing', 'start_record', '2023-10-19 09:37:48', NULL, NULL),
('DP003', 'Sale', 'start_record', '2023-10-19 09:37:48', NULL, NULL),
('DP004', 'Account', 'start_record', '2023-10-19 09:37:48', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `menulists`
--

CREATE TABLE `menulists` (
  `MenuKey` varchar(32) NOT NULL,
  `HeadMenuKey` varchar(32) DEFAULT NULL COMMENT 'MenuKey ที่เป็นเมนูหลักของ Record นี้',
  `MenuLevel` int(2) NOT NULL COMMENT 'ระดับของเมนู (0 = เมนูหลัก / 1 = เมนูรอง)',
  `MenuName` varchar(64) NOT NULL COMMENT 'ชื่อของเมนู',
  `MenuIcon` varchar(128) DEFAULT NULL COMMENT 'ไอคอน (<i class="faX fa-XXXX fa-fw fa-1x"></i>)',
  `MenuCase` varchar(64) DEFAULT NULL COMMENT 'Menu Case ประจำ File Path',
  `MenuLink` varchar(24) DEFAULT NULL COMMENT 'Url Path',
  `MenuClass` varchar(256) NOT NULL COMMENT 'Permission กำหนดสิทธิ์ของเมนูนั้น ๆ (ตำแหน่งของ String จะกำหนดจาก LvClass ประจำตำแหน่งของพนักงาน / (1 = เข้าได้ / 0 = เข้าไม่ได้))',
  `MenuType` enum('A','D','L') DEFAULT NULL COMMENT 'ประเภทการกำหนด Permission ของเมนูนั้น ๆ (A = เข้าถึงได้ทั้งหมด / D = กำหนดจากฝ่าย / L = Lv Code)',
  `MenuSort` int(11) NOT NULL COMMENT 'ลำดับการจัดเรียงเมนู',
  `MenuStatus` enum('A','I') NOT NULL DEFAULT 'A' COMMENT 'สถานะของเมนู (A = Active / I  = Inactive)',
  `uKeyCreate` varchar(32) NOT NULL COMMENT 'uKey ผู้สร้าง Record นี้',
  `DateCreate` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'วันที่สร้าง Record นี้',
  `uKeyUpdate` varchar(32) DEFAULT NULL COMMENT 'uKey ผู้อัพเดต Record นี้',
  `DateUpdate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'วันที่อัพเดต Record นี้'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `menulists`
--

INSERT INTO `menulists` (`MenuKey`, `HeadMenuKey`, `MenuLevel`, `MenuName`, `MenuIcon`, `MenuCase`, `MenuLink`, `MenuClass`, `MenuType`, `MenuSort`, `MenuStatus`, `uKeyCreate`, `DateCreate`, `uKeyUpdate`, `DateUpdate`) VALUES
('106a6c241b8797f52e1e77317b96a201', NULL, 0, 'หน้าแรก', '<i class=\"fas fa-th-large fa-fw fa-1x\"></i>', 'home', 'home', '1111111111111', 'A', 0, 'A', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-10-23 15:25:00', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-10-31 02:12:59'),
('2e5d8aa3dfa8ef34ca5131d20f9dad51', NULL, 0, 'ตั้งค่า', '<i class=\"fas fa-cogs fa-fw fa-1x\"></i>', 'settings', 'settings', '0000000000000', 'L', 998, 'A', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-10-25 04:22:52', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-10-30 16:11:10'),
('3e14d191ea1049f93df37e4bfcfa85f0', NULL, 0, 'ระบบงานขาย', '<i class=\"fas fa-file-invoice-dollar fa-fw fa-1x\"></i>', 'sale_ar', 'sale_ar', '0010010010010', 'D', 1, 'A', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-10-25 04:03:32', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-10-30 18:28:49'),
('63d21ebc529b806e33535f1ff064d792', NULL, 0, 'จัดการราคา', '<i class=\"fas fa-funnel-dollar fa-fw fa-1x\"></i>', 'pricelist', 'pricelist', '1100110100100', 'L', 2, 'A', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-10-25 04:17:35', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-11-07 07:44:56'),
('7d97481b1fe66f4b51db90da7e794d9f', '2e5d8aa3dfa8ef34ca5131d20f9dad51', 1, 'จัดการโปรไฟล์', '<i class=\"fas fa-user-cog fa-fw fa-1x\"></i>', 'settings', 'profile', '0000000000000', 'L', 1, 'A', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-10-25 04:25:33', NULL, '2023-10-30 16:11:08'),
('958153f1b8b96ec4c4eb2147429105d9', '2e5d8aa3dfa8ef34ca5131d20f9dad51', 1, 'ตั้งค่าทั่วไป', '<i class=\"fas fa-cog fa-fw fa-1x\"></i>', 'settings', 'general', '0000000000000', 'L', 0, 'A', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-10-25 04:24:29', NULL, '2023-10-30 16:11:07'),
('9b7406fea2e90add7a8659fa1d9346c7', '3e14d191ea1049f93df37e4bfcfa85f0', 1, 'เปิดคำสั่งขาย', '<i class=\"fas fa-plus fa-fw fa-1x\"></i>', 'sale_ar', 'salesorders', '0000100000110', 'L', 0, 'A', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-10-25 04:08:09', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-10-31 01:36:18'),
('9e8032991efe87c3f125a66df657fea2', NULL, 0, 'ข้อมูลลูกค้า', '<i class=\"fas fa-address-card fa-fw fa-1x\"></i>', 'bpmaster', 'bpmaster', '1111111111111', 'A', 3, 'A', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-10-25 04:18:20', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-10-30 17:52:11'),
('af9710c891f126c1651e647f7206da99', '2e5d8aa3dfa8ef34ca5131d20f9dad51', 1, 'จัดการผู้ใช้งาน', '<i class=\"fas fa-users-cog fa-fw fa-1x\"></i>', 'settings', 'userlist', '0000000000000', 'L', 2, 'A', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-10-25 04:26:21', NULL, '2023-10-30 14:56:16'),
('b30a5aec96fdf1031e3d282fac33f7bd', '2e5d8aa3dfa8ef34ca5131d20f9dad51', 1, 'จัดการเมนู', '<i class=\"fas fa-th-list fa-fw fa-1x\"></i>', 'settings', 'menulist', '0000000000000', 'L', 3, 'A', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-10-25 04:27:22', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-10-30 14:56:16'),
('e99cbc1013babba4f1e6bf8c79e3f5bb', '3e14d191ea1049f93df37e4bfcfa85f0', 1, 'อนุมัติคำสั่งขาย', '<i class=\"fas fa-tasks fa-fw fa-1x\"></i>', 'sale_ar', 'salesapprove', '0100000000100', 'L', 1, 'A', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-10-25 04:16:25', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-10-31 02:12:41');

-- --------------------------------------------------------

--
-- Table structure for table `menupages`
--

CREATE TABLE `menupages` (
  `MenuID` int(11) NOT NULL,
  `MenuCase` varchar(64) DEFAULT NULL COMMENT 'Menu Case ประจำ File Path',
  `FilePath` varchar(256) NOT NULL COMMENT 'File Path',
  `CaseStatus` enum('A','I') NOT NULL DEFAULT 'A' COMMENT 'สถานะของไฟล์ (A = Active / I = Inactive)',
  `CreateFile` enum('Y','N') NOT NULL DEFAULT 'Y' COMMENT 'สถานะของการสร้างไฟล์ (Y = Yes / N = No)',
  `uKeyCreate` varchar(32) NOT NULL COMMENT 'uKey ผู้สร้าง Record นี้',
  `DateCreate` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'วันที่สร้าง Record นี้',
  `uKeyUpdate` varchar(32) DEFAULT NULL COMMENT 'uKey ผู้อัพเดต Record นี้',
  `DateUpdate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'วันที่อัพเดต Record นี้'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `order_appdue`
--

CREATE TABLE `order_appdue` (
  `RefID` int(11) NOT NULL,
  `DocEntry` int(11) NOT NULL COMMENT 'PK ของ order_header',
  `BillType` enum('OINV','ORIN') NOT NULL COMMENT 'ประเภทของบิล',
  `BillEntry` int(11) NOT NULL COMMENT 'DocEntry ของบิล SAP',
  `DocNum` varchar(24) NOT NULL COMMENT 'DocNum ของบิล',
  `DocDate` date NOT NULL COMMENT 'วันที่เอกสาร',
  `DocDueDate` date NOT NULL COMMENT 'วันที่กำหนดชำระ',
  `DocTotal` decimal(20,6) NOT NULL DEFAULT 0.000000 COMMENT 'มูลค่าท้ายบิลทั้งหมด',
  `PaidtoDate` decimal(20,6) NOT NULL DEFAULT 0.000000 COMMENT 'มูลค่าที่ชำระมาแล้ว',
  `CardCode` varchar(12) DEFAULT NULL COMMENT 'CardCode ของบิล',
  `CardName` varchar(255) DEFAULT NULL COMMENT 'CardName ของบิล',
  `uKeyCreate` varchar(32) NOT NULL,
  `DateCreate` timestamp NOT NULL DEFAULT current_timestamp(),
  `ssidCreate` varchar(48) NOT NULL,
  `uKeyUpdate` varchar(32) DEFAULT NULL,
  `DateUpdate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `ssidUpdate` varchar(48) DEFAULT NULL,
  `RefStatus` enum('A','I') NOT NULL DEFAULT 'A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `order_appdue`
--

INSERT INTO `order_appdue` (`RefID`, `DocEntry`, `BillType`, `BillEntry`, `DocNum`, `DocDate`, `DocDueDate`, `DocTotal`, `PaidtoDate`, `CardCode`, `CardName`, `uKeyCreate`, `DateCreate`, `ssidCreate`, `uKeyUpdate`, `DateUpdate`, `ssidUpdate`, `RefStatus`) VALUES
(1, 1, 'OINV', 27360, 'IV-660824055', '2023-08-24', '2023-10-23', '930.000000', '0.000000', 'C-00918', 'เรืองแสงไทย(SM TT PC)', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-12-11 10:46:54', '2n9q8p7ipa50chj9b5d738emul::1702291425', NULL, '2023-12-12 09:09:20', NULL, 'A'),
(2, 1, 'OINV', 27510, 'IV-660826012', '2023-08-26', '2023-10-25', '33619.040000', '0.000000', 'C-00918', 'เรืองแสงไทย(SM TT PC)', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-12-11 10:46:54', '2n9q8p7ipa50chj9b5d738emul::1702291425', NULL, '2023-12-12 09:09:24', NULL, 'A'),
(3, 1, 'OINV', 27614, 'IV-660826075', '2023-08-26', '2023-10-25', '3000.000000', '0.000000', 'C-00918', 'เรืองแสงไทย(SM TT PC)', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-12-11 10:46:54', '2n9q8p7ipa50chj9b5d738emul::1702291425', NULL, '2023-12-12 09:09:26', NULL, 'A'),
(4, 1, 'OINV', 29709, 'IV-660920089', '2023-09-20', '2023-11-19', '2022.000000', '0.000000', 'C-00918', 'เรืองแสงไทย(SM TT PC)', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-12-11 10:46:54', '2n9q8p7ipa50chj9b5d738emul::1702291425', NULL, '2023-12-12 09:09:30', NULL, 'A'),
(5, 1, 'OINV', 30344, 'IV-660927085', '2023-09-27', '2023-11-26', '22241.870000', '0.000000', 'C-00918', 'เรืองแสงไทย(SM TT PC)', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-12-11 10:46:54', '2n9q8p7ipa50chj9b5d738emul::1702291425', NULL, '2023-12-12 09:09:32', NULL, 'A'),
(6, 1, 'ORIN', 1589, 'SR-661110074', '2023-11-10', '2023-11-10', '-5000.000000', '0.000000', 'C-00918', 'เรืองแสงไทย(SM TT PC)', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-12-11 10:46:54', '2n9q8p7ipa50chj9b5d738emul::1702291425', NULL, '2023-12-12 09:09:34', NULL, 'A'),
(7, 2, 'OINV', 27360, 'IV-660824055', '2023-08-24', '2023-10-23', '930.000000', '0.000000', 'C-00918', 'เรืองแสงไทย(SM TT PC)', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-12-11 10:49:43', '2n9q8p7ipa50chj9b5d738emul::1702291425', NULL, '2023-12-12 09:09:42', NULL, 'A'),
(8, 2, 'OINV', 27510, 'IV-660826012', '2023-08-26', '2023-10-25', '33619.040000', '0.000000', 'C-00918', 'เรืองแสงไทย(SM TT PC)', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-12-11 10:49:43', '2n9q8p7ipa50chj9b5d738emul::1702291425', NULL, '2023-12-12 09:09:44', NULL, 'A'),
(9, 2, 'OINV', 27614, 'IV-660826075', '2023-08-26', '2023-10-25', '3000.000000', '0.000000', 'C-00918', 'เรืองแสงไทย(SM TT PC)', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-12-11 10:49:43', '2n9q8p7ipa50chj9b5d738emul::1702291425', NULL, '2023-12-12 09:09:45', NULL, 'A'),
(10, 2, 'OINV', 29709, 'IV-660920089', '2023-09-20', '2023-11-19', '2022.000000', '0.000000', 'C-00918', 'เรืองแสงไทย(SM TT PC)', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-12-11 10:49:43', '2n9q8p7ipa50chj9b5d738emul::1702291425', NULL, '2023-12-12 09:09:48', NULL, 'A'),
(11, 2, 'OINV', 30344, 'IV-660927085', '2023-09-27', '2023-11-26', '22241.870000', '0.000000', 'C-00918', 'เรืองแสงไทย(SM TT PC)', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-12-11 10:49:43', '2n9q8p7ipa50chj9b5d738emul::1702291425', NULL, '2023-12-12 09:09:51', NULL, 'A'),
(12, 2, 'ORIN', 1589, 'SR-661110074', '2023-11-10', '2023-11-10', '-5000.000000', '0.000000', 'C-00918', 'เรืองแสงไทย(SM TT PC)', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-12-11 10:49:43', '2n9q8p7ipa50chj9b5d738emul::1702291425', NULL, '2023-12-12 09:09:52', NULL, 'A');

-- --------------------------------------------------------

--
-- Table structure for table `order_approve`
--

CREATE TABLE `order_approve` (
  `AppID` int(11) NOT NULL,
  `DocEntry` int(11) NOT NULL,
  `LvApp` int(11) DEFAULT 0,
  `StepApprove` int(11) DEFAULT 0,
  `APP1` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT 'ออเดอร์นี้ต้องหนี้เกินกำหนดหรือไม่ (Y = Yes / N = No)',
  `APP2` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT 'ออเดอร์นี้ต้องเช็คเด้งหรือไม่ (Y = Yes / N = No)',
  `APP3` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT 'ออเดอร์นี้ต้องตรวจสอบราคาพิเศษหรือไม่ (Y = Yes / N = No)',
  `APP4` enum('Y','N') DEFAULT 'N' COMMENT 'ออเดอร์นี้ต้องตรวจสอบมูลค่าท้ายบิลหรือไม่',
  `LvClassReq` int(11) NOT NULL COMMENT 'LvClass ของผู้มีสิทธิ์อนุมัติ',
  `AppType` enum('A','B','C','D','E') DEFAULT 'D' COMMENT 'ประเภทการอนุมัติ [A (ถ้าไม่อนุมัติ ให้จบกระบวนการอนุมัติ || ถ้าอนุมัติ ให้ส่งลำดับถัดไปพิจารณา)] [B (ถ้าไม่อนุมัติ ให้ส่งลำดับถัดไปพิจารณา || ถ้าอนุมัติ ให้ส่งลำดับถัดไปพิจารณา)] [C (ถ้าไม่อนุมัติ ให้ส่งลำดับถัดไปพิจารณา || ถ้าอนุมัติ ให้จบกระบวนการอนุมัติ)] [D (ผู้อนุมัติลำดับสุดท้าย)] [E (ตรวจสอบเงื่อนไขว่าจะจบกระบวนการอนุมัติ || หรือส่งลำดับถัดไปพิจารณา)]',
  `AppCond` varchar(32) NOT NULL DEFAULT 'N' COMMENT 'เงื่อนไขการอนุมัติ (N = ไม่มีเงื่อนไข)',
  `AppResult` enum('0','Y','N') NOT NULL DEFAULT '0' COMMENT 'สถานะการอนุมัติ (0 = รออนุมัติ / Y = Yes / N = No)',
  `AppRemark` varchar(255) DEFAULT NULL COMMENT 'หมายเหตุ หรือความคิดเห็นในการพิจารณา',
  `uKeyApproved` varchar(32) DEFAULT NULL COMMENT 'uKey ของผู้อนุมัติ',
  `DateApproved` timestamp NULL DEFAULT NULL COMMENT 'วันที่อนุมัติ',
  `ssidApproved` varchar(48) DEFAULT NULL COMMENT 'SSID ของผู้อนุมัติ',
  `uKeyCreate` varchar(32) NOT NULL COMMENT 'uKey ผู้สร้าง Record นี้',
  `DateCreate` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'วันที่สร้าง Record นี้',
  `uKeyUpdate` varchar(32) DEFAULT NULL COMMENT 'uKey ผู้อัพเดต Record นี้',
  `DateUpdate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'วันที่อัพเดต Record นี้'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `order_approve`
--

INSERT INTO `order_approve` (`AppID`, `DocEntry`, `LvApp`, `StepApprove`, `APP1`, `APP2`, `APP3`, `APP4`, `LvClassReq`, `AppType`, `AppCond`, `AppResult`, `AppRemark`, `uKeyApproved`, `DateApproved`, `ssidApproved`, `uKeyCreate`, `DateCreate`, `uKeyUpdate`, `DateUpdate`) VALUES
(1, 1, 0, 0, 'Y', 'N', 'N', 'N', 3, 'D', 'N', 'N', 'ทดสอบไม่อนุมัติ', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-12-13 02:42:13', NULL, '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-12-11 10:46:54', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-12-13 02:42:13'),
(2, 2, 0, 0, 'Y', 'N', 'Y', 'N', 3, 'D', 'N', '0', NULL, NULL, NULL, NULL, '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-12-11 10:49:43', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-12-13 02:38:07'),
(3, 3, 0, 0, 'N', 'N', 'Y', 'N', 3, 'D', 'N', '0', NULL, NULL, NULL, NULL, '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-12-12 13:50:23', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-12-13 02:38:10');

-- --------------------------------------------------------

--
-- Table structure for table `order_attach`
--

CREATE TABLE `order_attach` (
  `AttachID` int(11) NOT NULL,
  `DocEntry` int(11) NOT NULL COMMENT 'DocEntry ของ order_header',
  `VisOrder` int(11) NOT NULL COMMENT 'ลำดับที่',
  `FileOriName` varchar(255) DEFAULT NULL COMMENT 'ชื่อไฟล์ต้นฉบับ (ไม่เกิน 255 ตัวอักษร)',
  `FileDirName` varchar(255) DEFAULT NULL COMMENT 'ชื่อไฟล์ที่จัดเก็บ (ไม่เกิน 255 ตัวอักษร)',
  `FileExt` varchar(8) DEFAULT NULL COMMENT 'นามสกุลไฟล์',
  `FileStatus` enum('A','I') NOT NULL DEFAULT 'A' COMMENT 'สถานะไฟล์แนบ (A = Active / I = Inactive)',
  `uKeyCreate` varchar(32) NOT NULL COMMENT 'uKey ผู้สร้าง Record นี้',
  `DateCreate` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'วันที่สร้าง Record นี้',
  `uKeyUpdate` varchar(32) DEFAULT NULL COMMENT 'uKey ผู้อัพเดต Record นี้',
  `DateUpdate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'วันที่อัพเดต Record นี้'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `order_attach`
--

INSERT INTO `order_attach` (`AttachID`, `DocEntry`, `VisOrder`, `FileOriName`, `FileDirName`, `FileExt`, `FileStatus`, `uKeyCreate`, `DateCreate`, `uKeyUpdate`, `DateUpdate`) VALUES
(1, 1, 0, 'eye-color-of-siberian-husky', 'SO-661200001-0', 'jpg', 'A', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-12-11 10:46:54', NULL, NULL),
(2, 2, 0, 'Screenshot 2023-07-19 203558', 'SO-661200002-0', 'png', 'A', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-12-11 12:12:31', NULL, NULL),
(3, 1, 1, 'Screenshot 2023-07-02 153915', 'SO-661200001-1', 'png', 'A', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-12-11 12:13:12', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_cnclstate`
--

CREATE TABLE `order_cnclstate` (
  `CnclType` int(11) NOT NULL,
  `CnclTypeName` varchar(32) NOT NULL COMMENT 'ชื่อสาเหตุการยกเลิกคำสั่งขาย'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `order_detail`
--

CREATE TABLE `order_detail` (
  `TransID` int(11) NOT NULL,
  `DocEntry` int(11) NOT NULL COMMENT 'DocEntry ของ order_header',
  `VisOrder` int(11) NOT NULL COMMENT 'ลำดับที่',
  `ItemCode` varchar(32) NOT NULL COMMENT 'รหัสสินค้า',
  `CodeBars` varchar(32) DEFAULT NULL COMMENT 'บาร์โค้ด',
  `ItemName` varchar(255) NOT NULL COMMENT 'ชื่อสินค้า',
  `WhsCode` varchar(8) NOT NULL COMMENT 'รหัสคลังสินค้า',
  `Quantity` int(9) NOT NULL DEFAULT 0 COMMENT 'จำนวน',
  `UnitMsr` varchar(128) DEFAULT NULL COMMENT 'หน่วยสินค้า',
  `PromoCode` varchar(32) DEFAULT NULL COMMENT 'รหัสโปรโมชั่น',
  `GrandPrice` decimal(20,6) NOT NULL DEFAULT 0.000000 COMMENT 'ราคาตั้ง (ก่อน VAT)',
  `Line_Disc0` decimal(10,2) DEFAULT NULL COMMENT 'ส่วนลด (จำนวนเงิน)',
  `Line_Disc1` decimal(10,2) DEFAULT NULL COMMENT 'ส่วนลด Step 1',
  `Line_Disc2` decimal(10,2) DEFAULT NULL COMMENT 'ส่วนลด Step 2',
  `Line_Disc3` decimal(10,2) DEFAULT NULL COMMENT 'ส่วนลด Step 3',
  `Line_Disc4` decimal(10,2) DEFAULT NULL COMMENT 'ส่วนลด Step 4',
  `Line_Disc5` decimal(10,2) DEFAULT NULL COMMENT 'ส่วนลด Step 5',
  `UnitPrice` decimal(20,6) NOT NULL DEFAULT 0.000000 COMMENT 'ราคาต่อหน่วย (ก่อน VAT)',
  `UnitVat` decimal(20,6) NOT NULL DEFAULT 0.000000 COMMENT 'ภาษีต่อหน่วย',
  `LineTotal` decimal(20,6) NOT NULL DEFAULT 0.000000 COMMENT 'ราคารวม (ก่อน VAT)',
  `LineVatSum` decimal(20,6) NOT NULL DEFAULT 0.000000 COMMENT 'ภาษีรวม',
  `LineProfit` decimal(20,6) NOT NULL DEFAULT 0.000000 COMMENT 'กำไรรวม (ราคาขาย (NO VAT)-ต้นทุน (NO VAT))',
  `LineStatus` enum('O','C','I') NOT NULL DEFAULT 'O' COMMENT 'สถานะบรรทัด (O = Open / C=Closed / I=Inactive)',
  `Line_SP` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT 'ขอราคาพิเศษหรือไม่',
  `uKeyCreate` varchar(32) NOT NULL COMMENT 'uKey ผู้สร้าง Record นี้',
  `ssidCreate` varchar(48) NOT NULL,
  `DateCreate` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'วันที่สร้าง Record นี้',
  `uKeyUpdate` varchar(32) DEFAULT NULL COMMENT 'uKey ผู้อัพเดต Record นี้',
  `ssidUpdate` varchar(48) DEFAULT NULL,
  `DateUpdate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'วันที่อัพเดต Record นี้'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `order_detail`
--

INSERT INTO `order_detail` (`TransID`, `DocEntry`, `VisOrder`, `ItemCode`, `CodeBars`, `ItemName`, `WhsCode`, `Quantity`, `UnitMsr`, `PromoCode`, `GrandPrice`, `Line_Disc0`, `Line_Disc1`, `Line_Disc2`, `Line_Disc3`, `Line_Disc4`, `Line_Disc5`, `UnitPrice`, `UnitVat`, `LineTotal`, `LineVatSum`, `LineProfit`, `LineStatus`, `Line_SP`, `uKeyCreate`, `ssidCreate`, `DateCreate`, `uKeyUpdate`, `ssidUpdate`, `DateUpdate`) VALUES
(1, 1, 0, '02-065-060', '8855852002564', 'ปืน ST64 RedKING', 'KSY', 20, 'PCS', NULL, '500.000000', NULL, NULL, NULL, NULL, NULL, NULL, '500.000000', '35.000000', '10000.000000', '245.000000', '-7887.820000', 'O', 'N', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2n9q8p7ipa50chj9b5d738emul::1702291425', '2023-12-11 10:46:54', NULL, NULL, NULL),
(2, 2, 0, '02-065-010', '8855852002502', 'ปืน F30 RedKINGXP', 'KSY', 20, 'PCS', NULL, '500.000000', NULL, '20.00', '10.00', NULL, NULL, NULL, '360.000000', '25.200000', '7200.000000', '176.400000', '294.400000', 'O', 'Y', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2n9q8p7ipa50chj9b5d738emul::1702291425', '2023-12-11 10:49:43', NULL, NULL, NULL),
(3, 3, 0, '30-117-010', '8859007708059', 'สว่านไร้สาย 12V. PITA 2A (กล่องชุด)', 'KSY', 20, 'ตัว', NULL, '738.310000', NULL, '0.00', NULL, NULL, NULL, NULL, '738.310000', '51.681700', '14766.200000', '361.771900', '2061.980000', 'I', 'N', '200ceb26807d6bf99fd6f4f0d1ca54d4', 'lgoekneu0fiblp8viaim18l6vh::1702383043', '2023-12-12 13:49:21', '200ceb26807d6bf99fd6f4f0d1ca54d4', 'lgoekneu0fiblp8viaim18l6vh::1702383043', '2023-12-12 13:50:23'),
(4, 3, 1, '05-001-950', '8859007709346', 'สว่านกระแทกโรตารี่ 2-26DFR PITA', 'KSY', 10, 'PCS', NULL, '1343.930000', NULL, '0.00', NULL, NULL, NULL, NULL, '1343.930000', '94.075100', '13439.300000', '658.525700', '3981.300000', 'I', 'N', '200ceb26807d6bf99fd6f4f0d1ca54d4', 'lgoekneu0fiblp8viaim18l6vh::1702383043', '2023-12-12 13:49:21', '200ceb26807d6bf99fd6f4f0d1ca54d4', 'lgoekneu0fiblp8viaim18l6vh::1702383043', '2023-12-12 13:50:23'),
(5, 3, 2, '30-119-500', '8859007711042', 'สว่านกระแทกไร้สาย 21V. PITA 2B (กล่องชุด)', 'KSY', 20, 'PCS', NULL, '1261.680000', NULL, '0.00', NULL, NULL, NULL, NULL, '1261.680000', '88.317600', '25233.600000', '618.223200', '5813.040000', 'I', 'N', '200ceb26807d6bf99fd6f4f0d1ca54d4', 'lgoekneu0fiblp8viaim18l6vh::1702383043', '2023-12-12 13:49:21', '200ceb26807d6bf99fd6f4f0d1ca54d4', 'lgoekneu0fiblp8viaim18l6vh::1702383043', '2023-12-12 13:50:23'),
(6, 3, 3, '38-001-002', '8859007712803', 'เจียร์ไร้สาย EUROX 21V Black edition (เอวตรง) กล่องชุด', 'KSY', 10, 'SET', NULL, '1755.140000', NULL, '0.00', NULL, NULL, NULL, NULL, '1755.140000', '122.859800', '17551.400000', '860.018600', '5869.160000', 'I', 'N', '200ceb26807d6bf99fd6f4f0d1ca54d4', 'lgoekneu0fiblp8viaim18l6vh::1702383043', '2023-12-12 13:49:21', '200ceb26807d6bf99fd6f4f0d1ca54d4', 'lgoekneu0fiblp8viaim18l6vh::1702383043', '2023-12-13 01:17:13'),
(7, 3, 0, '30-117-010', '8859007708059', 'สว่านไร้สาย 12V. PITA 2A (กล่องชุด)', 'KSY', 20, 'ตัว', NULL, '738.310000', NULL, '0.00', NULL, NULL, NULL, NULL, '738.310000', '51.681700', '14766.200000', '361.771900', '2061.980000', 'I', 'N', '200ceb26807d6bf99fd6f4f0d1ca54d4', 'lgoekneu0fiblp8viaim18l6vh::1702383043', '2023-12-12 13:49:45', '200ceb26807d6bf99fd6f4f0d1ca54d4', 'lgoekneu0fiblp8viaim18l6vh::1702383043', '2023-12-12 13:50:23'),
(8, 3, 1, '05-001-950', '8859007709346', 'สว่านกระแทกโรตารี่ 2-26DFR PITA', 'KSY', 10, 'PCS', NULL, '1343.930000', NULL, '0.00', NULL, NULL, NULL, NULL, '1343.930000', '94.075100', '13439.300000', '658.525700', '3981.300000', 'I', 'N', '200ceb26807d6bf99fd6f4f0d1ca54d4', 'lgoekneu0fiblp8viaim18l6vh::1702383043', '2023-12-12 13:49:45', '200ceb26807d6bf99fd6f4f0d1ca54d4', 'lgoekneu0fiblp8viaim18l6vh::1702383043', '2023-12-12 13:50:23'),
(9, 3, 2, '30-119-500', '8859007711042', 'สว่านกระแทกไร้สาย 21V. PITA 2B (กล่องชุด)', 'KSY', 20, 'PCS', NULL, '1261.680000', NULL, '0.00', NULL, NULL, NULL, NULL, '1261.680000', '88.317600', '25233.600000', '618.223200', '5813.040000', 'I', 'N', '200ceb26807d6bf99fd6f4f0d1ca54d4', 'lgoekneu0fiblp8viaim18l6vh::1702383043', '2023-12-12 13:49:45', '200ceb26807d6bf99fd6f4f0d1ca54d4', 'lgoekneu0fiblp8viaim18l6vh::1702383043', '2023-12-12 13:50:23'),
(10, 3, 3, '38-001-002', '8859007712803', 'เจียร์ไร้สาย EUROX 21V Black edition (เอวตรง) กล่องชุด', 'KSY', 10, 'SET', NULL, '1755.140000', NULL, NULL, NULL, NULL, NULL, NULL, '1755.140000', '122.859800', '17551.400000', '860.018600', '5869.160000', 'I', 'N', '200ceb26807d6bf99fd6f4f0d1ca54d4', 'lgoekneu0fiblp8viaim18l6vh::1702383043', '2023-12-12 13:49:45', '200ceb26807d6bf99fd6f4f0d1ca54d4', 'lgoekneu0fiblp8viaim18l6vh::1702383043', '2023-12-12 13:50:23'),
(11, 3, 0, '30-117-010', '8859007708059', 'สว่านไร้สาย 12V. PITA 2A (กล่องชุด)', 'KSY', 20, 'ตัว', NULL, '738.310000', NULL, '2.00', NULL, NULL, NULL, NULL, '723.544000', '50.648080', '14470.880000', '354.536560', '1766.660000', 'O', 'Y', '200ceb26807d6bf99fd6f4f0d1ca54d4', 'lgoekneu0fiblp8viaim18l6vh::1702383043', '2023-12-12 13:50:23', NULL, NULL, NULL),
(12, 3, 1, '05-001-950', '8859007709346', 'สว่านกระแทกโรตารี่ 2-26DFR PITA', 'KSY', 10, 'PCS', NULL, '1343.930000', NULL, '0.00', NULL, NULL, NULL, NULL, '1343.930000', '94.075100', '13439.300000', '658.525700', '3981.300000', 'O', 'N', '200ceb26807d6bf99fd6f4f0d1ca54d4', 'lgoekneu0fiblp8viaim18l6vh::1702383043', '2023-12-12 13:50:23', NULL, NULL, NULL),
(13, 3, 2, '30-119-500', '8859007711042', 'สว่านกระแทกไร้สาย 21V. PITA 2B (กล่องชุด)', 'KSY', 20, 'PCS', NULL, '1261.680000', NULL, '0.00', NULL, NULL, NULL, NULL, '1261.680000', '88.317600', '25233.600000', '618.223200', '5813.040000', 'O', 'N', '200ceb26807d6bf99fd6f4f0d1ca54d4', 'lgoekneu0fiblp8viaim18l6vh::1702383043', '2023-12-12 13:50:23', NULL, NULL, NULL),
(14, 3, 3, '38-001-002', '8859007712803', 'เจียร์ไร้สาย EUROX 21V Black edition (เอวตรง) กล่องชุด', 'KSY', 10, 'SET', NULL, '1755.140000', NULL, '0.00', NULL, NULL, NULL, NULL, '1755.140000', '122.859800', '17551.400000', '860.018600', '5869.160000', 'O', 'N', '200ceb26807d6bf99fd6f4f0d1ca54d4', 'lgoekneu0fiblp8viaim18l6vh::1702383043', '2023-12-12 13:50:23', NULL, NULL, '2023-12-13 01:17:09');

-- --------------------------------------------------------

--
-- Table structure for table `order_docstate`
--

CREATE TABLE `order_docstate` (
  `IntStatus` int(11) NOT NULL COMMENT 'สถานะเอกสาร [0 คำสั่งขายถูกยกเลิก] [1 บันทึกร่างคำสั่งขาย] [2 คำสั่งขายรออนุมัติ] [3 คำสั่งขายได้รับการอนุมัติ] [4 คำสั่งขายไม่ได้รับการอนุมัติ] [5 คำสั่งขายที่ Import เข้า SAP เรียบร้อย]',
  `IntStatusName` varchar(32) NOT NULL COMMENT 'ชื่อสถานะเอกสารคำสั่งขาย',
  `IntStatusIcon` varchar(128) DEFAULT NULL COMMENT 'ไอคอนสถานะคำสั่งขาย'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `order_docstate`
--

INSERT INTO `order_docstate` (`IntStatus`, `IntStatusName`, `IntStatusIcon`) VALUES
(0, 'ยกเลิกเอกสาร', '<i class=\"fas fa-ban fa-fw fa-1x\"></i>'),
(1, 'บันทึกร่าง', '<i class=\"far fa-file-alt fa-fw fa-1x\"></i>'),
(2, 'รออนุมัติ', '<i class=\"far fa-clock fa-fw fa-1x\"></i>'),
(3, 'อนุมัติ', '<i class=\"far fa-check-circle fa-fw fa-1x\"></i>'),
(4, 'ไม่อนุมัติ', '<i class=\"far fa-times-circle fa-fw fa-1x\"></i>'),
(5, 'เสร็จสมบูรณ์', '<i class=\"fas fa-check-circle fa-fw fa-1x\"></i>');

-- --------------------------------------------------------

--
-- Table structure for table `order_header`
--

CREATE TABLE `order_header` (
  `DocEntry` int(11) NOT NULL,
  `DocNum` int(9) NOT NULL COMMENT 'เลขที่เอกสาร YYMMTXXXX (Y = Year, M = Month, T = Doc Type, XXX = Running No.)',
  `DocType` enum('SO','SD') NOT NULL COMMENT 'ประเภทเอกสาร (SO = ในประเทศ // SD = ต่างประเทศ)',
  `ObjType` int(11) NOT NULL DEFAULT 17 COMMENT 'Object Type in SAP',
  `CANCELED` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT 'สถานะการยกเลิกเอกสาร (Y = Yes / N = No)',
  `DraftStatus` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT 'สถานะบันทึกร่างเอกสาร (Y = Yes / N = No)',
  `DocStatus` enum('O','P','C') NOT NULL DEFAULT 'C' COMMENT 'สถานะเอกสาร (O = Open / P = Pending / C = Closed)',
  `AppStatus` enum('P','Y','N','B') NOT NULL DEFAULT 'Y' COMMENT 'สถานะการอนุมัติเอกสาร (B = Bypass / P = Pending / Y = Yes / N = No)',
  `Printed` enum('Y','N') NOT NULL DEFAULT 'Y' COMMENT 'สถานะการพิมพ์เอกสาร (Y = Yes / N = No)',
  `DocDate` date NOT NULL COMMENT 'วันที่เอกสาร',
  `DocDueDate` date NOT NULL COMMENT 'วันที่กำหนดส่ง',
  `CardCode` varchar(12) NOT NULL COMMENT 'รหัสลูกค้า in SAP',
  `CardName` varchar(255) NOT NULL COMMENT 'ชื่อลูกค้า',
  `LicTradeNum` varchar(16) DEFAULT NULL COMMENT 'เลขประจำตัวผู้เสียภาษี (13 หลัก) (ถ้ามี)',
  `SlpCode` int(8) NOT NULL COMMENT 'SlpCode in SAP',
  `GroupNum` int(6) NOT NULL COMMENT 'GroupNum in SAP (Payment Term Code)',
  `TaxType` enum('S07','X0') NOT NULL DEFAULT 'X0' COMMENT 'ประเภทภาษี (S07 = VAT นอก / X0 = ไม่มี VAT)',
  `BilltoCode` varchar(255) DEFAULT NULL COMMENT 'Value ที่อยู่เปิดบิล',
  `BilltoAddress` text DEFAULT NULL COMMENT 'ที่อยู่เปิดบิล',
  `ShiptoCode` varchar(255) DEFAULT NULL COMMENT 'Value ที่อยู่จัดส่งสินค้า',
  `ShiptoAddress` text DEFAULT NULL COMMENT 'ที่อยู่จัดส่งสินค้า',
  `DiscPcnt` decimal(20,6) NOT NULL DEFAULT 0.000000 COMMENT '% ส่วนลดท้ายบิล',
  `DiscTotal` decimal(20,6) NOT NULL DEFAULT 0.000000 COMMENT 'มูลค่ารวมส่วนลดท้ายบิล',
  `DocTotal` decimal(20,6) NOT NULL DEFAULT 0.000000 COMMENT 'มูลค่าสุทธิ (รวม VAT)',
  `VatSum` decimal(20,6) NOT NULL DEFAULT 0.000000 COMMENT 'ภาษีมูลค่าเพิ่ม (VAT)',
  `GrossProfit` decimal(20,6) NOT NULL DEFAULT 0.000000 COMMENT 'กำไร (มูลค่าสุทธิ (VAT) - ต้นทุน (VAT))',
  `U_PONo` varchar(255) DEFAULT NULL COMMENT 'เลขที่ P/O ของลูกค้า',
  `ShippingType` varchar(255) DEFAULT NULL COMMENT 'วิธีการจัดส่งสินค้า',
  `ShipCostType` enum('PRE','PST','COD','NULL') DEFAULT 'NULL' COMMENT 'รูปแบบการจัดส่ง (PRE = ชำระค่าขนส่งต้นทาง / PST = ชำระค่าขนส่งปลายทาง / COD = ชำระค่าสินค้าและขนส่งปลายทาง / NULL = ไม่มีค่าขนส่ง)',
  `ShipComment` varchar(255) DEFAULT NULL COMMENT 'หมายเหตุการจัดส่งสินค้า',
  `Comments` varchar(255) DEFAULT NULL COMMENT 'หมายเหตุท้ายเอกสาร',
  `uKeyCreate` varchar(32) NOT NULL COMMENT 'uKey ผู้สร้าง Record นี้',
  `DateCreate` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'วันที่สร้าง Record นี้',
  `ssidCreate` varchar(48) NOT NULL COMMENT 'SSID ของผู้สร้าง Record นี้',
  `uKeyUpdate` varchar(32) DEFAULT NULL COMMENT 'uKey ผู้อัพเดต Record นี้',
  `ssidUpdate` varchar(48) DEFAULT NULL,
  `DateUpdate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'วันที่อัพเดต Record นี้',
  `uKeyCancel` varchar(32) DEFAULT NULL COMMENT 'uKey ผู้ยกเลิก Record นี้',
  `ssidCancel` varchar(48) DEFAULT NULL,
  `DateCancel` timestamp NULL DEFAULT NULL COMMENT 'วันที่ยกเลิก Record นี้',
  `CnclType` varchar(4) DEFAULT NULL COMMENT 'สาเหตุการยกเลิกเอกสาร',
  `uKeyImport` varchar(32) DEFAULT NULL COMMENT 'uKey ผู้ Import to SAP',
  `ssidImport` varchar(48) DEFAULT NULL,
  `DateImport` timestamp NULL DEFAULT NULL COMMENT 'วันที่ Import to SAP',
  `ImportEntry` int(11) NOT NULL COMMENT 'Imported DocEntry [SAP]',
  `IntStatus` int(2) NOT NULL DEFAULT 1 COMMENT 'สถานะเอกสาร (0 = ยกเลิก / 1 = บันทึกร่าง / 2 = รออนุมัติ / 3 = อนุมัติ / 4 = ไม่อนุมัติ / 5 = เสร็จสมบูรณ์)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `order_header`
--

INSERT INTO `order_header` (`DocEntry`, `DocNum`, `DocType`, `ObjType`, `CANCELED`, `DraftStatus`, `DocStatus`, `AppStatus`, `Printed`, `DocDate`, `DocDueDate`, `CardCode`, `CardName`, `LicTradeNum`, `SlpCode`, `GroupNum`, `TaxType`, `BilltoCode`, `BilltoAddress`, `ShiptoCode`, `ShiptoAddress`, `DiscPcnt`, `DiscTotal`, `DocTotal`, `VatSum`, `GrossProfit`, `U_PONo`, `ShippingType`, `ShipCostType`, `ShipComment`, `Comments`, `uKeyCreate`, `DateCreate`, `ssidCreate`, `uKeyUpdate`, `ssidUpdate`, `DateUpdate`, `uKeyCancel`, `ssidCancel`, `DateCancel`, `CnclType`, `uKeyImport`, `ssidImport`, `DateImport`, `ImportEntry`, `IntStatus`) VALUES
(1, 661200001, 'SO', 17, 'N', 'N', 'C', 'N', 'Y', '2023-12-11', '2023-12-11', 'C-00918', 'เรืองแสงไทย(SM TT PC)', '0315564000517', 343, 16, 'S07', 'บริษัท เรืองแสงไทย จำกัด', 'บริษัท เรืองแสงไทย จำกัด 114 หมู่ที่ 14 ถนนบุรีรัมย์-พุทไธสง ตำบล ชุมเห็ด อำเภอ เมืองบุรีรัมย์ จังหวัด บุรีรัมย์ 31000', 'บริษัท เรืองแสงไทย จำกัด สาขาที่ 00001', 'บริษัท เรืองแสงไทย จำกัด สาขาที่ 00001 114 หมู่ที่ 14 ถนนบุรีรัมย์-พุทไธสง ตำบลชุมเห็ด อำเภอเมืองบุรีรัมย์ จังหวัดบุรีรัมย์ 31000', '0.000000', '0.000000', '10700.000000', '700.000000', '-7887.820000', '2023_12_11_1745', NULL, 'NULL', NULL, '', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-12-11 10:46:54', '2n9q8p7ipa50chj9b5d738emul::1702291425', '200ceb26807d6bf99fd6f4f0d1ca54d4', NULL, '2023-12-13 02:42:13', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 4),
(2, 661200002, 'SO', 17, 'N', 'N', 'P', 'P', 'Y', '2023-12-11', '2023-12-11', 'C-00918', 'เรืองแสงไทย(SM TT PC)', '0315564000517', 343, 16, 'S07', 'บริษัท เรืองแสงไทย จำกัด', 'บริษัท เรืองแสงไทย จำกัด 114 หมู่ที่ 14 ถนนบุรีรัมย์-พุทไธสง ตำบล ชุมเห็ด อำเภอ เมืองบุรีรัมย์ จังหวัด บุรีรัมย์ 31000', 'บริษัท เรืองแสงไทย จำกัด สาขาที่ 00001', 'บริษัท เรืองแสงไทย จำกัด สาขาที่ 00001 114 หมู่ที่ 14 ถนนบุรีรัมย์-พุทไธสง ตำบลชุมเห็ด อำเภอเมืองบุรีรัมย์ จังหวัดบุรีรัมย์ 31000', '0.000000', '0.000000', '7704.000000', '504.000000', '294.400000', '2023_12_11_1748', NULL, 'NULL', NULL, '', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-12-11 10:49:43', '2n9q8p7ipa50chj9b5d738emul::1702291425', '200ceb26807d6bf99fd6f4f0d1ca54d4', NULL, '2023-12-13 02:38:58', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 2),
(3, 661200003, 'SO', 17, 'N', 'N', 'P', 'P', 'Y', '2023-12-12', '2023-12-12', 'C-03817', 'เจริญสุขโฮมพลัส(SM TT PC)', '0315557000604', 343, 16, 'S07', 'บริษัท เจริญสุขโฮมพลัส จำกัด', 'บริษัท เจริญสุขโฮมพลัส จำกัด 57 ถนน นางรอง-ปะคำ ตำบล นางรอง อำเภอ นางรอง จังหวัด บุรีรัมย์ 31110', 'บริษัท เจริญสุขโฮมพลัส จำกัด (สำนักงานใหญ่)', 'บริษัท เจริญสุขโฮมพลัส จำกัด (สำนักงานใหญ่) 57 ถนน นางรอง-ปะคำ ตำบล นางรอง อำเภอ นางรอง จังหวัด บุรีรัมย์ 31110', '2.000000', '1413.900000', '74130.970000', '4849.690000', '16016.260000', '2023_12_12_20_46', NULL, 'NULL', NULL, '', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-12-12 13:49:21', 'lgoekneu0fiblp8viaim18l6vh::1702383043', '200ceb26807d6bf99fd6f4f0d1ca54d4', NULL, '2023-12-13 02:38:59', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 2);

-- --------------------------------------------------------

--
-- Table structure for table `oslp`
--

CREATE TABLE `oslp` (
  `SlpCode` int(11) NOT NULL,
  `SlpName` varchar(256) NOT NULL COMMENT 'ชื่อพนักงาน',
  `SlpUkey` varchar(32) DEFAULT NULL COMMENT 'uKey ของพนักงาน',
  `uKeyCreate` varchar(32) NOT NULL COMMENT 'uKey ผู้สร้าง Record นี้',
  `ssidCreate` varchar(48) NOT NULL COMMENT 'SSID ของผู้สร้าง Record นี้',
  `DateCreate` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'วันที่สร้าง Record นี้',
  `uKeyUpdate` varchar(32) DEFAULT NULL COMMENT 'uKey ผู้อัพเดต Record นี้',
  `ssidUpdate` varchar(48) DEFAULT NULL COMMENT 'SSID ของผู้อัพเดต Record นี้',
  `DateUpdate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'วันที่อัพเดต Record นี้',
  `SlpStatus` enum('A','I') NOT NULL DEFAULT 'A' COMMENT 'สถานะของพนักงานขาย'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `positions`
--

CREATE TABLE `positions` (
  `LvCode` varchar(8) NOT NULL COMMENT 'รหัสประจำตำแหน่งของพนักงาน',
  `LvName` varchar(64) NOT NULL COMMENT 'ชื่อตำแหน่ง',
  `LvClass` int(11) NOT NULL COMMENT 'Class ของตำแหน่ง (จะสัมพันธ์กับตำแหน่งของ String ในการกำหนด Permission เข้าถึงแต่ละเมนู)',
  `DeptCode` varchar(8) NOT NULL COMMENT 'รหัสประจำฝ่าย',
  `uKeyCreate` varchar(32) NOT NULL COMMENT 'uKey ผู้สร้าง Record นี้',
  `DateCreate` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'วันที่สร้าง Record นี้',
  `uKeyUpdate` varchar(32) DEFAULT NULL COMMENT 'uKey ผู้อัพเดต Record นี้',
  `DateUpdate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'วันที่อัพเดต Record นี้'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `positions`
--

INSERT INTO `positions` (`LvCode`, `LvName`, `LvClass`, `DeptCode`, `uKeyCreate`, `DateCreate`, `uKeyUpdate`, `DateUpdate`) VALUES
('A0001', 'ผู้ช่วยผู้จัดการแผนกการตลาด', 5, 'DP002', 'start_record', '2023-10-19 09:37:43', NULL, NULL),
('A0002', 'ผู้ช่วยผู้จัดการแผนกขาย', 6, 'DP003', 'start_record', '2023-10-19 09:37:43', NULL, NULL),
('A0003', 'ผู้ช่วยผู้จัดการแผนกบัญชี', 7, 'DP004', 'start_record', '2023-10-19 09:37:43', NULL, NULL),
('C0001', 'ประธานกรรมการบริหาร', 1, 'DP001', 'start_record', '2023-10-19 09:37:43', NULL, NULL),
('C0002', 'รองประธานกรรมการบริหาร', 1, 'DP001', 'start_record', '2023-10-19 09:37:43', NULL, NULL),
('I0001', 'Administrator', 0, 'DP000', 'start_record', '2023-10-19 09:37:43', NULL, NULL),
('M0001', 'ผู้จัดการแผนกการตลาด', 2, 'DP002', 'start_record', '2023-10-19 09:37:43', NULL, NULL),
('M0002', 'ผู้จัดการแผนกการขาย', 3, 'DP003', 'start_record', '2023-10-19 09:37:43', NULL, NULL),
('M0003', 'ผู้จัดการแผนกบัญชี', 4, 'DP004', 'start_record', '2023-10-19 09:37:43', NULL, NULL),
('O0001', 'พนักงานฝ่ายการตลาด', 11, 'DP002', 'start_record', '2023-10-19 09:37:43', NULL, NULL),
('O0002', 'พนักงานฝ่ายการขาย', 12, 'DP003', 'start_record', '2023-10-19 09:37:43', NULL, NULL),
('O0003', 'พนักงานฝ่ายบัญชี', 13, 'DP004', 'start_record', '2023-10-19 09:37:43', NULL, NULL),
('S0001', 'หัวหน้างานฝ่ายการตลาด', 8, 'DP002', 'start_record', '2023-10-19 09:37:43', NULL, NULL),
('S0002', 'หัวหน้างานฝ่ายการขาย', 9, 'DP003', 'start_record', '2023-10-19 09:37:43', NULL, NULL),
('S0003', 'หัวหน้างานฝ่ายบัญชี', 10, 'DP004', 'start_record', '2023-10-19 09:37:43', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `price_detail`
--

CREATE TABLE `price_detail` (
  `PriceID` int(11) NOT NULL,
  `PriceType` varchar(12) DEFAULT NULL COMMENT 'ประเภทราคา [STD = ราคามาตรฐาน // GRPYYXXX = กลุ่มราคา // PROYYXXX = โปรโมชั่น]',
  `ItemCode` varchar(32) NOT NULL COMMENT 'รหัสสินค้า',
  `Prc_Grand` decimal(20,6) DEFAULT NULL COMMENT 'ราคาตั้ง (ราคาก่อนส่วนลด)',
  `Prc_Retail` decimal(20,6) DEFAULT NULL COMMENT 'ราคาขายปลีกแนะนำ',
  `Prc_Wholesale` decimal(20,6) DEFAULT NULL COMMENT 'ราคาขายส่งแนะนำ',
  `Prc_Step1` decimal(20,6) DEFAULT NULL COMMENT 'ราคาขายส่ง Step 1',
  `Prc_Step2` decimal(20,6) DEFAULT NULL COMMENT 'ราคาขายส่ง Step 2',
  `Prc_Step3` decimal(20,6) DEFAULT NULL COMMENT 'ราคาขายส่ง Step 3',
  `Prc_Step4` decimal(20,6) DEFAULT NULL COMMENT 'ราคาขายส่ง Step 4',
  `Qty_Step1` int(7) DEFAULT NULL COMMENT 'จำนวนขายส่ง Step 1 (ขั้นต่ำ)',
  `Qty_Step2` int(7) DEFAULT NULL COMMENT 'จำนวนขายส่ง Step 2 (ขั้นต่ำ)',
  `Qty_Step3` int(7) DEFAULT NULL COMMENT 'จำนวนขายส่ง Step 3 (ขั้นต่ำ)',
  `Qty_Step4` int(7) DEFAULT NULL COMMENT 'จำนวนขายส่ง Step 4 (ขั้นต่ำ)',
  `StartDate` date DEFAULT NULL COMMENT 'วันที่เริ่มต้น Active ราคา (NULL = ไม่กำหนดระยะเวลา) / ถ้า StartDate = NULL , EndedDate = NULL ด้วย',
  `EndedDate` date DEFAULT NULL COMMENT 'วันที่สิ้นสุด Active ราคา (NULL = ไม่กำหนดระยะเวลา) / ถ้า EndedDate = NULL , StartDate = NULL ด้วย',
  `uKeyCreate` varchar(32) NOT NULL COMMENT 'uKey ผู้สร้าง Record นี้',
  `DateCreate` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'วันที่สร้าง Record นี้',
  `ssidCreate` varchar(48) NOT NULL COMMENT 'SSID ของผู้สร้าง Record นี้',
  `uKeyUpdate` varchar(32) DEFAULT NULL COMMENT 'uKey ผู้อัพเดต Record นี้',
  `DateUpdate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'วันที่อัพเดต Record นี้',
  `PriceStatus` enum('A','I') NOT NULL DEFAULT 'A' COMMENT 'สถานะใบราคา (A = Active / I = Inactive)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `price_header`
--

CREATE TABLE `price_header` (
  `ListID` int(11) NOT NULL,
  `GroupCode` varchar(12) NOT NULL COMMENT 'รหัสกลุ่มราคา',
  `CardCode` varchar(12) DEFAULT NULL COMMENT 'รหัสลูกค้า',
  `QryProperties` varchar(255) NOT NULL DEFAULT '0' COMMENT 'รหัส Properties ของลูกค้า',
  `uKeyCreate` varchar(32) NOT NULL COMMENT 'uKey ผู้สร้าง Record นี้',
  `DateCreate` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'วันที่สร้าง Record นี้',
  `ssidCreate` varchar(48) NOT NULL COMMENT 'SSID ของผู้สร้าง Record นี้',
  `uKeyUpdate` varchar(32) DEFAULT NULL COMMENT 'uKey ผู้อัพเดต Record นี้',
  `DateUpdate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'วันที่อัพเดต Record นี้',
  `GroupPriceStatus` enum('A','I') NOT NULL DEFAULT 'A' COMMENT 'สถานะใบราคา (A = Active / I = Inactive)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `userlogs`
--

CREATE TABLE `userlogs` (
  `ID` int(11) NOT NULL,
  `SSID` varchar(64) NOT NULL COMMENT 'SESSION ID [session_id()]::[time()];',
  `IPAddress` varchar(64) NOT NULL COMMENT 'Ip Address ที่ Login เข้าระบบ (IPv4 or IPv6 Supported)',
  `CompName` varchar(64) DEFAULT NULL COMMENT 'Computer Name [gethostbyaddr($_SERVER[''REMOTE_ADDR''])]',
  `LogText` varchar(255) NOT NULL COMMENT 'คำอธิบาย',
  `LogType` enum('I','E','O') NOT NULL COMMENT 'สถานะลงชื่อเข้า - ออกระบบ (I = In / E = Error / O = Out)',
  `LoguKey` varchar(32) DEFAULT NULL COMMENT 'uKey ผู้เข้า - ออกระบบ',
  `LogDate` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'วันและเวลาเข้า - ออกระบบ'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `userlogs`
--

INSERT INTO `userlogs` (`ID`, `SSID`, `IPAddress`, `CompName`, `LogText`, `LogType`, `LoguKey`, `LogDate`) VALUES
(1, 'bb8h0qaaap3151u5eaq16ca682::1702103279', '192.168.1.66', '192.168.1.66', '\'admin\' เข้าสู่ระบบสำเร็จ', 'I', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-12-09 06:28:04'),
(2, 'bb8h0qaaap3151u5eaq16ca682::1702103279', '192.168.1.66', '192.168.1.66', '\'admin\' ออกจากระบบสำเร็จ', 'O', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-12-09 06:40:53'),
(3, 'qidoslb34r5d460g3doh4a9gki::1702265253', '192.168.1.66', '192.168.1.66', '\'admin\' เข้าสู่ระบบสำเร็จ', 'I', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-12-11 03:27:37'),
(4, 'qidoslb34r5d460g3doh4a9gki::1702265253', '192.168.1.66', '192.168.1.66', '\'admin\' ออกจากระบบสำเร็จ', 'O', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-12-11 03:28:40'),
(5, 'qidoslb34r5d460g3doh4a9gki::1702265543', '192.168.1.66', '192.168.1.66', '\'admin\' เข้าสู่ระบบสำเร็จ', 'I', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-12-11 03:32:27'),
(6, 'k56di417ks0l03d8t5ckve59ds::1702270730', '192.168.1.2', '192.168.1.2', '\'admin\' เข้าสู่ระบบสำเร็จ', 'I', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-12-11 04:58:55'),
(7, 'k56di417ks0l03d8t5ckve59ds::1702275699', '192.168.1.2', '192.168.1.2', '\'admin\' เข้าสู่ระบบสำเร็จ', 'I', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-12-11 06:21:44'),
(8, '4ebvkkgjodjcc0h1ntjrulqktk::1702279440', '192.168.1.169', 'DESKTOP-HB4UHLG', '\'admin\' เข้าสู่ระบบสำเร็จ', 'I', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-12-11 07:24:00'),
(9, 'qidoslb34r5d460g3doh4a9gki::1702279476', '192.168.1.66', '192.168.1.66', '\'admin\' เข้าสู่ระบบสำเร็จ', 'I', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-12-11 07:24:41'),
(10, 'qidoslb34r5d460g3doh4a9gki::1702285417', '192.168.1.66', '192.168.1.66', '\'admin\' เข้าสู่ระบบสำเร็จ', 'I', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-12-11 09:03:42'),
(11, 'k56di417ks0l03d8t5ckve59ds::1702286680', '192.168.1.2', '192.168.1.2', '\'admin\' เข้าสู่ระบบสำเร็จ', 'I', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-12-11 09:24:45'),
(12, '4ebvkkgjodjcc0h1ntjrulqktk::1702287174', '192.168.1.169', 'DESKTOP-HB4UHLG', '\'admin\' ออกจากระบบสำเร็จ', 'O', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-12-11 09:47:30'),
(13, '4ebvkkgjodjcc0h1ntjrulqktk::1702288684', '192.168.1.169', 'DESKTOP-HB4UHLG', '\'admin\' เข้าสู่ระบบสำเร็จ', 'I', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-12-11 09:58:04'),
(14, '2n9q8p7ipa50chj9b5d738emul::1702291425', '192.168.1.2', '192.168.1.2', '\'admin\' เข้าสู่ระบบสำเร็จ', 'I', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-12-11 10:43:50'),
(15, 'avvghrevaevf0r1e5o87bd7q18::1702296582', '192.168.1.2', '192.168.1.2', '\'admin\' รหัสผ่านไม่ถูกต้อง', 'I', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-12-11 12:09:47'),
(16, 'avvghrevaevf0r1e5o87bd7q18::1702296606', '192.168.1.2', '192.168.1.2', '\'admin\' เข้าสู่ระบบสำเร็จ', 'I', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-12-11 12:10:11'),
(17, 'i4rv25cpn2hhqtef7rrv13gv2v::1702313012', '192.168.1.2', '192.168.1.2', '\'admin\' เข้าสู่ระบบสำเร็จ', 'I', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-12-11 16:43:37'),
(18, '72p7gjkrdi64uu5s2v6gl0se98::1702344663', '192.168.1.169', 'DESKTOP-HB4UHLG', '\'admin\' รหัสผ่านไม่ถูกต้อง', 'I', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-12-12 01:31:03'),
(19, '72p7gjkrdi64uu5s2v6gl0se98::1702344671', '192.168.1.169', 'DESKTOP-HB4UHLG', '\'admin\' เข้าสู่ระบบสำเร็จ', 'I', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-12-12 01:31:11'),
(20, '72p7gjkrdi64uu5s2v6gl0se98::1702344671', '192.168.1.169', 'DESKTOP-HB4UHLG', '\'admin\' ออกจากระบบสำเร็จ', 'O', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-12-12 01:31:20'),
(21, '72p7gjkrdi64uu5s2v6gl0se98::1702344685', '192.168.1.169', 'DESKTOP-HB4UHLG', '\'admin\' เข้าสู่ระบบสำเร็จ', 'I', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-12-12 01:31:25'),
(22, 'ob1n3llk04ho5oq78h2oq7nkf6::1702344693', '192.168.1.66', '192.168.1.66', '\'admin\' เข้าสู่ระบบสำเร็จ', 'I', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-12-12 01:31:38'),
(23, '72p7gjkrdi64uu5s2v6gl0se98::1702364400', '192.168.1.169', 'DESKTOP-HB4UHLG', '\'admin\' เข้าสู่ระบบสำเร็จ', 'I', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-12-12 07:00:00'),
(24, '72p7gjkrdi64uu5s2v6gl0se98::1702369264', '192.168.1.169', 'DESKTOP-HB4UHLG', '\'admin\' เข้าสู่ระบบสำเร็จ', 'I', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-12-12 08:21:04'),
(25, 'h2qe375uj8di2fdodkqll91elv::1702371800', '192.168.1.66', '192.168.1.66', '\'admin\' เข้าสู่ระบบสำเร็จ', 'I', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-12-12 09:03:25'),
(26, 'h2qe375uj8di2fdodkqll91elv::1702375761', '192.168.1.66', '192.168.1.66', '\'admin\' เข้าสู่ระบบสำเร็จ', 'I', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-12-12 10:09:25'),
(27, 'lgoekneu0fiblp8viaim18l6vh::1702383043', '192.168.1.2', '192.168.1.2', '\'admin\' เข้าสู่ระบบสำเร็จ', 'I', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-12-12 12:10:47'),
(28, '0vkb4p7a53h3sadnqagaba6i9v::1702400276', '192.168.1.2', '192.168.1.2', '\'admin\' เข้าสู่ระบบสำเร็จ', 'I', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-12-12 16:58:01'),
(29, '9g2vthkienqc3hn7no4ou8e5v9::1702430110', '192.168.1.66', '192.168.1.66', '\'admin\' เข้าสู่ระบบสำเร็จ', 'I', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-12-13 01:15:14'),
(30, '9g2vthkienqc3hn7no4ou8e5v9::1702433792', '192.168.1.66', '192.168.1.66', '\'admin\' เข้าสู่ระบบสำเร็จ', 'I', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-12-13 02:16:37'),
(31, 'it189sfgr6lolgehm2pldfc9sp::1702435827', '192.168.1.169', 'DESKTOP-HB4UHLG', '\'admin\' เข้าสู่ระบบสำเร็จ', 'I', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-12-13 02:50:27'),
(32, '9g2vthkienqc3hn7no4ou8e5v9::1702438275', '192.168.1.66', '192.168.1.66', '\'admin\' เข้าสู่ระบบสำเร็จ', 'I', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-12-13 03:31:20'),
(33, 'v9jeeh8i13ufqpg1vfmcg815ku::1702440900', '192.168.1.12', 'DESKTOP-JSMPO1S', '\'admin\' เข้าสู่ระบบสำเร็จ', 'I', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-12-13 04:15:00'),
(34, 'v9jeeh8i13ufqpg1vfmcg815ku::1702440972', '192.168.1.12', 'DESKTOP-JSMPO1S', '\'admin\' เข้าสู่ระบบสำเร็จ', 'I', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-12-13 04:16:12'),
(35, 'v9jeeh8i13ufqpg1vfmcg815ku::1702441424', '192.168.1.12', 'DESKTOP-JSMPO1S', '\'admin\' เข้าสู่ระบบสำเร็จ', 'I', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-12-13 04:23:44');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `uKey` varchar(32) NOT NULL,
  `EmpCode` varchar(24) DEFAULT NULL COMMENT 'รหัสประจำตัวพนักงาน',
  `OwnerCode` int(11) NOT NULL DEFAULT -1,
  `TH_uFirstName` varchar(64) NOT NULL COMMENT 'ชื่อพนักงาน (ภาษาไทย)',
  `TH_uLastName` varchar(128) NOT NULL COMMENT 'นามสกุลพนักงาน (ภาษาไทย)',
  `EN_uFirstName` varchar(64) NOT NULL COMMENT 'ชื่อพนักงาน (ภาษาอังกฤษ)',
  `EN_uLastName` varchar(128) NOT NULL COMMENT 'นามสกุลพนักงาน (ภาษาอังกฤษ)',
  `uNickName` varchar(32) DEFAULT NULL COMMENT 'ชื่อเล่นพนักงาน (ถ้ามี)',
  `uGender` enum('M','F','O') DEFAULT NULL COMMENT 'เพศของพนักงาน (M = ชาย / F = หญิง / O = ไม่ระบุ)',
  `uBirthdate` date DEFAULT NULL COMMENT 'วันเกิดของพนักงาน (บังคับใส่เนื่องจากมีผลตอนกำหนด Default Password)',
  `uWorkStartDate` date DEFAULT NULL COMMENT 'วันที่เริ่มงานของพนักงาน',
  `uWorkResignDate` date DEFAULT NULL COMMENT 'วันที่ลาออกของพนักงาน (ถ้ามี)',
  `uMobileNo` varchar(32) DEFAULT NULL COMMENT 'หมายเลขโทรศัพท์ของพนักงาน (ถ้ามี)',
  `uEmailAddr` varchar(128) DEFAULT NULL COMMENT 'อีเมลล์ของพนักงาน (ถ้ามี)',
  `uLineID` varchar(32) DEFAULT NULL COMMENT 'LINE ID ของพนักงาน (ถ้ามี)',
  `UserName` varchar(32) NOT NULL COMMENT 'ชื่อผู้ใช้งาน (Username)',
  `UserPswd` varchar(128) NOT NULL COMMENT 'รหัสผ่านของผู้ใช้งาน',
  `LvCode` varchar(8) NOT NULL COMMENT 'รหัสตำแหน่งของพนักงาน',
  `LineToken` varchar(64) DEFAULT NULL COMMENT 'Token LINE (สำหรับทำแจ้งเตือน Line Notify)',
  `uKeyCreate` varchar(32) NOT NULL COMMENT 'uKey ผู้สร้าง Record นี้',
  `DateCreate` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'วันที่สร้าง Record นี้',
  `uKeyUpdate` varchar(32) DEFAULT NULL COMMENT 'uKey ผู้อัพเดต Record นี้',
  `DateUpdate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'วันที่อัพเดต Record นี้',
  `UserStatus` enum('A','I') NOT NULL DEFAULT 'A' COMMENT 'สถานะของพนักงาน (A = Active / I = Inactive)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`uKey`, `EmpCode`, `OwnerCode`, `TH_uFirstName`, `TH_uLastName`, `EN_uFirstName`, `EN_uLastName`, `uNickName`, `uGender`, `uBirthdate`, `uWorkStartDate`, `uWorkResignDate`, `uMobileNo`, `uEmailAddr`, `uLineID`, `UserName`, `UserPswd`, `LvCode`, `LineToken`, `uKeyCreate`, `DateCreate`, `uKeyUpdate`, `DateUpdate`, `UserStatus`) VALUES
('200ceb26807d6bf99fd6f4f0d1ca54d4', 'YYMMDDD', -1, 'Admin', '', '', '', NULL, 'M', '2023-01-01', '2023-01-01', NULL, NULL, NULL, NULL, 'admin', '$2y$10$wVE7MdjmY0XssWElxvuvIOfxPcHym1nL82HQWr/lmyccDareL6PuO', 'I0001', NULL, 'start_record', '2023-10-19 14:57:04', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2023-11-07 08:11:23', 'A');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appcenter`
--
ALTER TABLE `appcenter`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `DocType` (`DocType`);

--
-- Indexes for table `config_general`
--
ALTER TABLE `config_general`
  ADD PRIMARY KEY (`ConfigID`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`DeptCode`);

--
-- Indexes for table `menulists`
--
ALTER TABLE `menulists`
  ADD PRIMARY KEY (`MenuKey`),
  ADD UNIQUE KEY `MenuLink` (`MenuLink`);

--
-- Indexes for table `menupages`
--
ALTER TABLE `menupages`
  ADD PRIMARY KEY (`MenuID`),
  ADD UNIQUE KEY `MenuCase` (`MenuCase`);

--
-- Indexes for table `order_appdue`
--
ALTER TABLE `order_appdue`
  ADD PRIMARY KEY (`RefID`),
  ADD KEY `DocEntry` (`DocEntry`);

--
-- Indexes for table `order_approve`
--
ALTER TABLE `order_approve`
  ADD PRIMARY KEY (`AppID`),
  ADD KEY `DocEntry` (`DocEntry`);

--
-- Indexes for table `order_attach`
--
ALTER TABLE `order_attach`
  ADD PRIMARY KEY (`AttachID`),
  ADD KEY `DocEntry` (`DocEntry`);

--
-- Indexes for table `order_cnclstate`
--
ALTER TABLE `order_cnclstate`
  ADD PRIMARY KEY (`CnclType`);

--
-- Indexes for table `order_detail`
--
ALTER TABLE `order_detail`
  ADD PRIMARY KEY (`TransID`),
  ADD KEY `DocEntry` (`DocEntry`),
  ADD KEY `ItemCode` (`ItemCode`),
  ADD KEY `WhsCode` (`WhsCode`);

--
-- Indexes for table `order_docstate`
--
ALTER TABLE `order_docstate`
  ADD PRIMARY KEY (`IntStatus`);

--
-- Indexes for table `order_header`
--
ALTER TABLE `order_header`
  ADD PRIMARY KEY (`DocEntry`,`CardCode`,`SlpCode`,`ImportEntry`),
  ADD UNIQUE KEY `DocNum` (`DocNum`);

--
-- Indexes for table `oslp`
--
ALTER TABLE `oslp`
  ADD PRIMARY KEY (`SlpCode`),
  ADD KEY `SlpUkey` (`SlpUkey`);

--
-- Indexes for table `positions`
--
ALTER TABLE `positions`
  ADD PRIMARY KEY (`LvCode`);

--
-- Indexes for table `price_detail`
--
ALTER TABLE `price_detail`
  ADD PRIMARY KEY (`PriceID`),
  ADD KEY `PriceType` (`PriceType`),
  ADD KEY `ItemCode` (`ItemCode`);

--
-- Indexes for table `price_header`
--
ALTER TABLE `price_header`
  ADD PRIMARY KEY (`ListID`);

--
-- Indexes for table `userlogs`
--
ALTER TABLE `userlogs`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `IPAddress` (`IPAddress`),
  ADD KEY `CompName` (`CompName`),
  ADD KEY `LoguKey` (`LoguKey`),
  ADD KEY `SSID` (`SSID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`uKey`),
  ADD UNIQUE KEY `UserName` (`UserName`),
  ADD KEY `EmpCode` (`EmpCode`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `config_general`
--
ALTER TABLE `config_general`
  MODIFY `ConfigID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `menupages`
--
ALTER TABLE `menupages`
  MODIFY `MenuID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_appdue`
--
ALTER TABLE `order_appdue`
  MODIFY `RefID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `order_approve`
--
ALTER TABLE `order_approve`
  MODIFY `AppID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `order_attach`
--
ALTER TABLE `order_attach`
  MODIFY `AttachID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `order_detail`
--
ALTER TABLE `order_detail`
  MODIFY `TransID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `order_header`
--
ALTER TABLE `order_header`
  MODIFY `DocEntry` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `price_detail`
--
ALTER TABLE `price_detail`
  MODIFY `PriceID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `price_header`
--
ALTER TABLE `price_header`
  MODIFY `ListID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `userlogs`
--
ALTER TABLE `userlogs`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
