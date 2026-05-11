-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 11, 2026 at 04:50 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `real_property_tax`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) NOT NULL,
  `suffix` varchar(10) DEFAULT NULL,
  `province` varchar(100) NOT NULL,
  `municipality` varchar(100) NOT NULL,
  `barangay` varchar(100) NOT NULL,
  `street` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contact_number` varchar(15) DEFAULT NULL,
  `valid_id` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `first_name`, `middle_name`, `last_name`, `suffix`, `province`, `municipality`, `barangay`, `street`, `email`, `contact_number`, `valid_id`, `password`, `created_at`) VALUES
(3, 'Admin', '', '1', '', '', '', '', '', 'admin@gmail.com', '09123456789', '[\"assets\\/images\\/uploads\\/valid_id\\/sk-titulo-torrens.jpg\"]', '$2y$10$i/L2hNgqOWWZTWEI3hbc2.oyXSGrUVRsdjPK1cqwFTA3teR8PurZi', '2024-10-26 17:02:10'),
(5, 'Juan', 'Dela', 'Cruz', 'Jr.', 'Biliran', 'Naval', 'Santissimo Rosario Pob.', 'Garcia St.', 'admin@gmail.com', '9876543210', '[\"assets\\/images\\/uploads\\/valid_id\\/admin\\/rsbsa-card-v2.png\"]', '$2y$10$UH/d6DQGGWgL4KqDuNqDseDnwNsxS0v9590cp3AF69.G2TOaVGuMi', '2024-11-17 07:32:59');

-- --------------------------------------------------------

--
-- Table structure for table `admin_approved_requests`
--

CREATE TABLE `admin_approved_requests` (
  `id` int NOT NULL,
  `request_id` int NOT NULL,
  `admin_email` varchar(255) NOT NULL,
  `staff_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `or_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `due_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `payment_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `payment_status` enum('Not Paid','Paid') DEFAULT 'Not Paid',
  `status` enum('Approved') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'Approved',
  `approved_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin_approved_requests`
--

INSERT INTO `admin_approved_requests` (`id`, `request_id`, `admin_email`, `staff_email`, `or_number`, `amount`, `due_date`, `payment_date`, `payment_status`, `status`, `approved_at`) VALUES
(19, 107, 'biliran_user@gmail.com', 'staff@gmail.com', '2020-11-17', 56.00, '2024-11-17 00:24:38', '2024-11-17 03:19:27', 'Paid', 'Approved', '2024-11-17 00:24:38'),
(26, 110, 'admin@gmail.com', 'staff@gmail.com', '2020-11-17', 87.00, '2024-11-17 04:39:54', '2024-11-17 04:39:54', 'Paid', 'Approved', '2024-11-17 04:39:54'),
(27, 96, 'staff@gmail.com', 'staff@gmail.com', '2020-11-17', 87.00, '2024-11-17 04:46:39', '2024-11-17 04:46:39', 'Paid', 'Approved', '2024-11-17 04:46:39'),
(28, 107, 'staff@gmail.com', NULL, NULL, NULL, '2024-11-17 07:46:35', '2024-11-17 07:46:35', 'Not Paid', 'Approved', '2024-11-17 07:46:35'),
(29, 97, 'admin@gmail.com', 'staff@gmail.com', '2020-11-17', 900.00, '2024-11-20 20:55:34', '2024-11-20 20:55:34', 'Paid', 'Approved', '2024-11-20 20:55:34'),

-- --------------------------------------------------------

--
-- Table structure for table `admin_rejected_requests`
--

CREATE TABLE `admin_rejected_requests` (
  `id` int NOT NULL,
  `request_id` int NOT NULL,
  `admin_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `rejection_category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `feedback` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `status` enum('Rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'Rejected',
  `rejected_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin_rejected_requests`
--

INSERT INTO `admin_rejected_requests` (`id`, `request_id`, `admin_email`, `rejection_category`, `feedback`, `status`, `rejected_at`) VALUES
(15, 89, 'staff@gmail.com', 'Invalid Data', 'HAHHHAHA', 'Rejected', '2024-11-17 07:59:46');

-- --------------------------------------------------------

--
-- Table structure for table `applicants`
--

CREATE TABLE `applicants` (
  `id` int NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) NOT NULL,
  `suffix` varchar(10) DEFAULT NULL,
  `province` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `municipality` varchar(255) NOT NULL,
  `barangay` varchar(255) NOT NULL,
  `street` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `contact_number` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `valid_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `applicants`
--

INSERT INTO `applicants` (`id`, `first_name`, `middle_name`, `last_name`, `suffix`, `province`, `municipality`, `barangay`, `street`, `email`, `contact_number`, `valid_id`, `password`, `created_at`) VALUES
(8, 'Applicant', '', '2', '', '123 Main St, Brgy. Bonifacio, Naval, Biliran', '', '', NULL, 'applicant2@gmail.com', '09123456789', '[\"assets\\/images\\/uploads\\/valid_id\\/sk-titulo-torrens.jpg\"]', '$2y$10$tl6H82tIVp4euIPQWPAPr.ETb8f/uE109X/xDZ5VK8rxiYYEh4M9q', '2024-10-26 17:07:25'),
(9, 'Applicant', '', '3', '', '123 Main St, Brgy. Bonifacio, Naval, Biliran', '', '', NULL, 'applicant3@gmail.com', '09123456789', '[\"assets\\/images\\/uploads\\/valid_id\\/sk-titulo-torrens.jpg\"]', '$2y$10$vKKW0ZOOYMuJhbWE4xxPv.a.1dZGruvB/QrKSqhrCvphDHqPinRca', '2024-10-26 17:08:06'),
(20, 'Zelenia', 'Keefe Barnett', 'Hansen', 'Sr.', 'Biliran', 'Naval', 'Libertad', 'Dicta sunt blanditii', 'lydevide@mailinator.com', '9876543210', '[\"assets\\/images\\/uploads\\/valid_id\\/462547909_931690648782281_4109153555480916039_n.jpg\"]', '$2y$10$odYEhUA12B8YZACRHms9t.OMJkeld2n8O0s6iqI1IBMDCuhXrwWfG', '2024-11-16 09:53:33'),
(21, 'Clarke', 'Wesley Sellers', 'Montoya', 'Sr.', 'Biliran', 'Naval', 'Calumpang', 'Fugiat sed reprehend', 'muqu@mailinator.com', '9876543210', '[\"assets\\/images\\/uploads\\/valid_id\\/logo.png\"]', '$2y$10$g5dhBul6kuvURb3skOJo9uPJeq3zatRPZhW.XKZi91aeRUllDtpe.', '2024-11-16 10:04:25'),
(25, 'Kiara', 'Sydnee Olsen', 'Holder', 'Sr.', 'Biliran', 'Naval', 'Mabini', 'Temporibus quia quos', 'kennethloto18@gmail.com', '9876543210', '[\"assets\\/images\\/uploads\\/valid_id\\/screenshot-1731255394182.png\"]', '$2y$10$3vVdtpPCeCmnp2pKiRNXAO5AsEpCbGcX3OrX0f3F.hGtxNZbc7sP2', '2024-11-17 02:23:18'),
(27, 'Curran', 'Moana Delaney', 'Reeves', 'Jra.', 'Biliran', 'Naval', 'Catmon', 'Aliquam rerum culpa', 'kennethloto2@gmail.com', '9876543210', '[\"assets\\/images\\/uploads\\/valid_id\\/8d0f2890067875.5e0cccec6943a.png\"]', '$2y$10$I5C20MhMkNjnAvhRcfJfFulR6xYgLrm.5ZEoO7WjjKkG01uyxZhr6', '2025-02-27 09:30:28');

-- --------------------------------------------------------

--
-- Table structure for table `approved_applicants`
--

CREATE TABLE `approved_applicants` (
  `id` int NOT NULL,
  `applicant_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `feedback` text NOT NULL,
  `admin_email` varchar(255) NOT NULL,
  `approved_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `approved_applicants`
--

INSERT INTO `approved_applicants` (`id`, `applicant_email`, `feedback`, `admin_email`, `approved_at`) VALUES
(1, 'applicant1@gmail.com', 'Hello', 'admin1@gmail.com', '2024-11-04 05:44:44'),
(2, 'applicant2@gmail.com', '1234567890\r\n', 'admin1@gmail.com', '2024-11-04 05:50:51'),

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` timestamp NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `email`, `token`, `expires_at`) VALUES
(88, 'applicant1@gmail.com', '51f4d371131ebf7a52c5f3db9915638323069fc40c3cc2a92c32e8fdd7a9fc9f116c42024d6c7b73e95e26cbd00bc464bfa5', '2024-11-03 08:06:00'),
(90, 'applicant1@gmail.com', '7a0a3535cf6f151b68c1a66d2042944cabcd11280592b5cc6f38c70136b98609a7999cf746a58c69bddeb35295e86dc1c0d7', '2024-11-03 08:09:08'),

-- --------------------------------------------------------

--
-- Table structure for table `properties`
--

CREATE TABLE `properties` (
  `id` int NOT NULL,
  `request_id` int DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `lot_number` varchar(50) NOT NULL,
  `coordinates` json NOT NULL,
  `status` enum('Occupied','Available','Pending','Ongoing') DEFAULT 'Pending',
  `payment_status` enum('Paid','Not yet Paid') NOT NULL DEFAULT 'Not yet Paid'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `properties`
--

INSERT INTO `properties` (`id`, `request_id`, `name`, `type`, `lot_number`, `coordinates`, `status`, `payment_status`) VALUES
(199, NULL, NULL, NULL, '', '[[11.560201239292269, 124.39249665274934], [11.56025413609838, 124.39260097638667], [11.560583695505784, 124.3924634971375], [11.560540212589345, 124.39234498914738]]', 'Pending', 'Not yet Paid'),
(200, NULL, NULL, NULL, '', '[[11.560593723168822, 124.3925019026368], [11.56064637618462, 124.3926223916348], [11.560313473078594, 124.39276368448924], [11.560260820000195, 124.39262585894396]]', 'Pending', 'Not yet Paid'),
(201, NULL, NULL, NULL, '', '[[11.56054191938513, 124.39269520513004], [11.560590326199844, 124.39281569412805], [11.560366975390394, 124.39292058023568], [11.560317719294332, 124.3927896893087]]', 'Pending', 'Not yet Paid'),
(202, NULL, NULL, NULL, '', '[[11.560670559118236, 124.39262470975063], [11.560689164077177, 124.39267840621471], [11.560654520359392, 124.39269346741897], [11.56063655694824, 124.39263911611914]]', 'Pending', 'Not yet Paid'),
(203, NULL, NULL, NULL, '', '[[11.560705202833972, 124.39271704147684], [11.560719316939014, 124.3927753217867], [11.560680823922809, 124.39278776365018], [11.560663502064545, 124.39272948334025]]', 'Pending', 'Not yet Paid'),
(204, NULL, NULL, NULL, '', '[[11.560683382063417, 124.39291904557795], [11.560706034090488, 124.3929817527644], [11.560799044668045, 124.39293866346908], [11.560780511196825, 124.39287875883709]]', 'Pending', 'Not yet Paid'),
(205, NULL, NULL, NULL, '', '[[11.560819166266556, 124.393009564367], [11.560802692072585, 124.39295736676472], [11.560710711133908, 124.39299520126856], [11.560730798533156, 124.39304377156884]]', 'Pending', 'Not yet Paid'),
(206, NULL, NULL, NULL, '', '[[11.56085141386518, 124.39308351997062], [11.560868398688244, 124.39313862539922], [11.560690057997787, 124.3932104482073], [11.560666400550431, 124.39315905774964]]', 'Pending', 'Not yet Paid'),
(207, NULL, NULL, NULL, '', '[[11.560687631593169, 124.39307547086128], [11.560707042829549, 124.3931200505354], [11.560660941139844, 124.39314295953544], [11.56064031669601, 124.39309899902425]]', 'Pending', 'Not yet Paid'),
(208, NULL, NULL, NULL, '', '[[11.56065898450342, 124.39292240096978], [11.560702053189218, 124.39304189926224], [11.560600750777963, 124.3930821448002], [11.56055525567082, 124.39296078902112]]', 'Pending', 'Not yet Paid'),
(209, NULL, NULL, NULL, '', '[[11.560827619579698, 124.39301837076532], [11.560844604404195, 124.39306295043951], [11.560728743618183, 124.39311310257348], [11.560711758786638, 124.39307099954664]]', 'Pending', 'Not yet Paid'),
(210, NULL, NULL, NULL, '', '[[11.560567791627662, 124.39308210113], [11.560533821945029, 124.392997275918], [11.560477078402982, 124.3930206701114], [11.560507460906706, 124.3931084102896]]', 'Pending', 'Not yet Paid'),
(211, NULL, NULL, NULL, '', '[[11.560475596330337, 124.3930789114363], [11.56044966004427, 124.39300478611348], [11.560398899021775, 124.3930255865866], [11.560425576349004, 124.3930985773381]]', 'Pending', 'Not yet Paid'),
(212, NULL, NULL, NULL, '', '[[11.560630190355454, 124.39318975752212], [11.560651274291786, 124.39323068494082], [11.560606659174129, 124.39325527982136], [11.560585010485994, 124.39321473669816]]', 'Pending', 'Not yet Paid'),
(213, NULL, NULL, NULL, '', '[[11.56050623819594, 124.39315853638236], [11.560466141044316, 124.39317448462504], [11.560502618694144, 124.39327050169868], [11.56054328058849, 124.39325397701408]]', 'Pending', 'Not yet Paid'),
(214, NULL, NULL, NULL, '', '[[11.560893844771345, 124.3931578851096], [11.560940656970873, 124.3932869792124], [11.560916634922066, 124.39329536194508], [11.56087002803548, 124.39316710611683]]', 'Pending', 'Not yet Paid'),
(215, NULL, NULL, NULL, '', '[[11.560867678967796, 124.39318915985145], [11.560902545322634, 124.39328771223592], [11.5608103811021, 124.3933297712075], [11.560769419216696, 124.39322873012684]]', 'Pending', 'Not yet Paid'),
(216, NULL, NULL, NULL, '', '[[11.560734492766857, 124.39321237265204], [11.560785817950176, 124.39333313141616], [11.560653880313694, 124.39339143895512], [11.560618213645682, 124.39329139860752], [11.560698536135888, 124.39325824914278], [11.560690126923632, 124.39323220313536]]', 'Pending', 'Not yet Paid'),
(217, NULL, NULL, NULL, '', '[[11.560606892408885, 124.39329968165409], [11.560644298916005, 124.3933935064776], [11.56058021489639, 124.3934251760536], [11.560532659325505, 124.3933319431856]]', 'Pending', 'Not yet Paid'),
(218, NULL, NULL, NULL, '', '[[11.560366640795635, 124.39194196434926], [11.560435069650197, 124.39211975352174], [11.560368902480633, 124.39214738238962], [11.560355869551527, 124.39211821858588], [11.560316770760563, 124.3921315213746], [11.560260628896586, 124.39198365576846]]', 'Pending', 'Not yet Paid'),
(219, NULL, NULL, NULL, '', '[[11.560520610871578, 124.39222459748976], [11.56053031594142, 124.39224967673516], [11.560465263511489, 124.39227535464056], [11.56045535317888, 124.39225045485269]]', 'Pending', 'Not yet Paid'),
(220, NULL, NULL, NULL, '', '[[11.5603936763182, 124.39224072356296], [11.560416870351062, 124.39230334587882], [11.560158902734573, 124.3924023405001], [11.560134960484902, 124.3923407364328]]', 'Pending', 'Not yet Paid'),
(221, NULL, NULL, NULL, '', '[[11.56096944061457, 124.39338228606756], [11.561001758330278, 124.3934831873488], [11.560907086422816, 124.3935165623882], [11.56087476869554, 124.39341449686124]]', 'Pending', 'Not yet Paid'),
(222, NULL, NULL, NULL, '', '[[11.56107554839022, 124.39365631696025], [11.56101129317969, 124.39351350283908], [11.560897230639751, 124.39356589389008], [11.560961137161485, 124.3937103440348]]', 'Pending', 'Not yet Paid'),
(223, NULL, NULL, NULL, '', '[[11.5608376837488, 124.39341771722252], [11.560882147800342, 124.39351120447697], [11.56074960339231, 124.3935741992538], [11.560706470578353, 124.39348125552874]]', 'Pending', 'Not yet Paid'),
(224, NULL, NULL, NULL, '', '[[11.561110605029029, 124.39375738844706], [11.561178747934548, 124.39390798256198], [11.560966817192693, 124.3940043372698], [11.56090180005927, 124.39385310504554]]', 'Pending', 'Not yet Paid'),
(225, NULL, NULL, NULL, '', '[[11.560868724197178, 124.39389858551796], [11.560904337431978, 124.39399148177074], [11.560848612102362, 124.39401280799508], [11.560812790595593, 124.39391969916466]]', 'Pending', 'Not yet Paid'),
(226, NULL, NULL, NULL, '', '[[11.560762653862554, 124.39375088328006], [11.560821384479624, 124.39385653416684], [11.560753698483182, 124.39389543579996], [11.56069600917438, 124.39378935975964]]', 'Pending', 'Not yet Paid'),
(227, NULL, NULL, NULL, '', '[[11.55982798603172, 124.39192932203464], [11.559875875376392, 124.39204930229909], [11.559472733907969, 124.39221638592733], [11.55942484449436, 124.39209285069217]]', 'Pending', 'Not yet Paid'),
(228, NULL, NULL, NULL, '', '[[11.55983268680484, 124.39214186047758], [11.559849271191524, 124.39217807798974], [11.559776762703208, 124.39221350816354], [11.55976133536288, 124.39217768432052]]', 'Pending', 'Not yet Paid'),
(229, NULL, NULL, NULL, '', '[[11.559985303919348, 124.39217693078292], [11.560023504301483, 124.39228211663152], [11.559920896286826, 124.39232110793768], [11.55988314008198, 124.39221682886324]]', 'Pending', 'Not yet Paid'),
(230, NULL, NULL, NULL, '', '[[11.559877261453806, 124.39221909404756], [11.55991279670664, 124.39232382651], [11.559863935732253, 124.3923410552261], [11.559827067901637, 124.3922399498635]]', 'Pending', 'Not yet Paid'),
(231, NULL, NULL, NULL, '', '[[11.560036329660022, 124.39230774035144], [11.56005994178672, 124.39235581109858], [11.55996585155303, 124.39240417739391], [11.559942499032871, 124.3923560425166]]', 'Pending', 'Not yet Paid'),
(232, NULL, NULL, NULL, '', '[[11.559951839943608, 124.39241782398892], [11.55996589680089, 124.39245508223604], [11.559894932338509, 124.3924856293699], [11.55987906169014, 124.39244860254088]]', 'Pending', 'Not yet Paid'),
(233, NULL, NULL, NULL, '', '[[11.559828152778168, 124.3923441988664], [11.559842663088617, 124.39238492837823], [11.55977736668629, 124.39240876439892], [11.55976376326744, 124.39236757205111]]', 'Pending', 'Not yet Paid'),
(234, NULL, NULL, NULL, '', '[[11.5598404206238, 124.3926032870432], [11.559892075551474, 124.39275471641872], [11.55980918740586, 124.39279211395676], [11.559744318405023, 124.39264129765644]]', 'Pending', 'Not yet Paid'),
(235, NULL, NULL, NULL, '', '[[11.5599692555284, 124.39255661947806], [11.560006497165377, 124.39268996901268], [11.559978463916764, 124.39270189137284], [11.559985861580373, 124.39274322222292], [11.559925512217916, 124.39276309282212], [11.559912274291818, 124.39271580079418], [11.559898257663122, 124.39271460855804], [11.559851146211416, 124.39259339789407]]', 'Pending', 'Not yet Paid'),
(236, NULL, NULL, NULL, '', '[[11.560135978293474, 124.39260469104444], [11.560157470363691, 124.3926653198755], [11.56003054209006, 124.39271261190488], [11.56000834910364, 124.39265339751536]]', 'Pending', 'Not yet Paid'),
(237, NULL, NULL, NULL, '', '[[11.560175169323202, 124.39270737252748], [11.560192690091966, 124.39277215068527], [11.560075495598523, 124.39282182718546], [11.560044347552193, 124.39275069043588]]', 'Pending', 'Not yet Paid'),
(238, NULL, NULL, NULL, '', '[[11.560015088737003, 124.39274176516216], [11.56004888484624, 124.3928320154331], [11.559981354631304, 124.3928582738726], [11.559946723745668, 124.39276889418306]]', 'Pending', 'Not yet Paid'),
(239, NULL, NULL, NULL, '', '[[11.559933133775772, 124.39279510239516], [11.559954655364194, 124.39284050800144], [11.559834805224568, 124.39288861182854], [11.559813376640731, 124.39283779103494]]', 'Pending', 'Not yet Paid'),
(240, NULL, NULL, NULL, '', '[[11.55993795381704, 124.39286704262184], [11.559970922131455, 124.39295666730813], [11.559879198397796, 124.3929883191464], [11.559851779196975, 124.39289836128488]]', 'Pending', 'Not yet Paid'),
(241, NULL, NULL, NULL, '', '[[11.55992746062634, 124.3929841314764], [11.559950962791945, 124.39303344171128], [11.559888290346748, 124.3930674257935], [11.55986185040409, 124.39301744920328]]', 'Pending', 'Not yet Paid'),
(242, NULL, NULL, NULL, '', '[[11.560001704279912, 124.39303038086746], [11.560029172303146, 124.39308705728472], [11.559938125638624, 124.39313400769896], [11.55990859012067, 124.39306466952974]]', 'Pending', 'Not yet Paid'),
(243, NULL, NULL, NULL, '', '[[11.56005001299576, 124.39291109158285], [11.560110856128889, 124.39306845908284], [11.560044401249996, 124.39309649582076], [11.559986511654287, 124.39293792244018]]', 'Pending', 'Not yet Paid'),
(244, NULL, NULL, NULL, '', '[[11.560191140766308, 124.39280218429565], [11.560209452768618, 124.39286338276906], [11.560071522008556, 124.3929233753575], [11.560037851533847, 124.39285795630305]]', 'Pending', 'Not yet Paid'),
(245, NULL, NULL, NULL, '', '[[11.560244793521576, 124.3928811557862], [11.560284791166808, 124.39298162280188], [11.56013001763759, 124.39306043438404], [11.560075064318356, 124.39295464226105]]', 'Pending', 'Not yet Paid'),
(246, NULL, NULL, NULL, '', '[[11.560326805190286, 124.39307095717356], [11.560359443373883, 124.39313524271694], [11.560222816875765, 124.39320384941324], [11.560192504235218, 124.39314062363484]]', 'Pending', 'Not yet Paid'),
(247, NULL, NULL, NULL, '', '[[11.56038189431952, 124.3931947556323], [11.560426264965344, 124.3933068580768], [11.560286123991162, 124.3933768100008], [11.56023604224707, 124.39325932664008]]', 'Pending', 'Not yet Paid'),
(248, NULL, NULL, NULL, '', '[[11.560431698322375, 124.39333987007984], [11.560474824947647, 124.39347154614808], [11.5603435699809, 124.39352169023113], [11.560297818235725, 124.39340034920416]]', 'Pending', 'Not yet Paid'),
(249, NULL, NULL, NULL, '', '[[11.560173795921656, 124.39312916240289], [11.560242798536905, 124.39330753745844], [11.56020947356383, 124.39332201392654], [11.56013934585124, 124.3931436387877]]', 'Pending', 'Not yet Paid'),
(250, NULL, NULL, NULL, '', '[[11.56012347316829, 124.39314185603422], [11.56021407187589, 124.393348215817], [11.560157819702823, 124.3933796037178], [11.560145069208218, 124.3933520436106], [11.560103817605636, 124.39337079979424], [11.56008220037468, 124.3933274474648], [11.560127952155725, 124.39329720790327], [11.560074700081683, 124.3931628523809]]', 'Pending', 'Not yet Paid'),
(251, NULL, NULL, NULL, '', '[[11.560028093818405, 124.3932116417604], [11.560064157289816, 124.39329021496712], [11.56001903087224, 124.393311322944], [11.559984172807646, 124.39323304741328]]', 'Pending', 'Not yet Paid'),
(252, NULL, NULL, NULL, '', '[[11.560047801329889, 124.39331497120412], [11.560080611825796, 124.39339285779482], [11.56003991268848, 124.39341016687932], [11.56000675042442, 124.39333285296436]]', 'Pending', 'Not yet Paid'),
(253, NULL, NULL, NULL, '', '[[11.560131726112033, 124.39339588525196], [11.56021756096176, 124.39360596914668], [11.56013777082086, 124.39363743545562], [11.56004891359308, 124.3934341384143]]', 'Pending', 'Not yet Paid'),
(254, NULL, NULL, NULL, '', '[[11.5602263485506, 124.39345443413202], [11.560242772297997, 124.3934862853684], [11.560213538028066, 124.39350154043336], [11.560197771228616, 124.39346968919692]]', 'Pending', 'Not yet Paid'),
(255, NULL, NULL, NULL, '', '[[11.560494658726569, 124.39360481710249], [11.56058667624326, 124.39383641576576], [11.560495230263754, 124.393879001919], [11.560387209661954, 124.393650320115]]', 'Pending', 'Not yet Paid'),
(256, NULL, NULL, NULL, '', '[[11.5603635156955, 124.39365075358351], [11.560411747737264, 124.39378808142304], [11.560339145818771, 124.39382383848316], [11.5603635156955, 124.39390778984114], [11.560260959115254, 124.39395857523176], [11.560157894791615, 124.39373263206936]]', 'Pending', 'Not yet Paid'),
(257, NULL, NULL, NULL, '', '[[11.560469278047108, 124.39392710258797], [11.56049389951437, 124.39398278563776], [11.560369826607868, 124.39404881686607], [11.560436707062408, 124.39424034193628], [11.560383601923562, 124.39426645876529], [11.560272563872289, 124.39399050736756], [11.560391809081466, 124.3939343315468], [11.560402912884411, 124.39395847729548]]', 'Pending', 'Not yet Paid'),
(258, NULL, NULL, NULL, '', '[[11.560489262149405, 124.39414709957362], [11.560509993318462, 124.39420064843604], [11.56045964618987, 124.39422094518233], [11.560438491932132, 124.39416955554792]]', 'Pending', 'Not yet Paid'),
(259, NULL, NULL, NULL, '', '[[11.560662044813045, 124.39393650315644], [11.560728645661328, 124.3941001771156], [11.56060848956841, 124.39415199859629], [11.560556027032804, 124.39398746539756]]', 'Pending', 'Not yet Paid'),
(260, NULL, NULL, NULL, '', '[[11.55967929216996, 124.39218820432887], [11.55979787712036, 124.3925139301009], [11.559644584369993, 124.3925739581136], [11.559525999354676, 124.39224528014398]]', 'Pending', 'Not yet Paid'),
(261, 110, 'Jessa  Arguilles', 'Residential', '00-1', '[[11.55992746062634, 124.3929841314764], [11.559950962791945, 124.39303344171128], [11.559888290346748, 124.3930674257935], [11.55986185040409, 124.39301744920328]]', 'Occupied', 'Paid'),
(262, 107, 'Jessa  Arguilles', 'Mixed', '531', '[[11.55967929216996, 124.39218820432887], [11.55979787712036, 124.3925139301009], [11.559644584369993, 124.3925739581136], [11.559525999354676, 124.39224528014398]]', 'Occupied', 'Paid'),
(263, 111, 'John  Gabrielle', 'Agricultural', '175', '[]', 'Occupied', 'Not yet Paid');

-- --------------------------------------------------------

--
-- Table structure for table `rejected_applicants`
--

CREATE TABLE `rejected_applicants` (
  `id` int NOT NULL,
  `applicant_email` varchar(255) NOT NULL,
  `rejection_reason` varchar(255) NOT NULL,
  `feedback` text NOT NULL,
  `admin_email` varchar(255) NOT NULL,
  `rejected_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `rejected_applicants`
--

INSERT INTO `rejected_applicants` (`id`, `applicant_email`, `rejection_reason`, `feedback`, `admin_email`, `rejected_at`) VALUES
(8, 'applicant3@gmail.com', 'Blurred Image', 'Iyak', 'admin1@gmail.com', '2024-11-17 07:12:27'),

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE `requests` (
  `id` int NOT NULL,
  `applicant_email` varchar(255) DEFAULT NULL,
  `td_number` varchar(50) NOT NULL,
  `pin` varchar(50) NOT NULL,
  `province` varchar(255) NOT NULL,
  `municipality` varchar(255) NOT NULL,
  `barangay` varchar(255) NOT NULL,
  `street` varchar(255) NOT NULL,
  `selected_property` varchar(255) NOT NULL,
  `lot_number` varchar(50) NOT NULL,
  `area` varchar(50) NOT NULL,
  `class` enum('Commercial','Residential','Industrial','Agricultural','Mixed') NOT NULL,
  `documents` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `status` enum('Pending','Staff Approved','Staff Rejected','Admin Approved','Admin Rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `requests`
--

INSERT INTO `requests` (`id`, `applicant_email`, `td_number`, `pin`, `province`, `municipality`, `barangay`, `street`, `selected_property`, `lot_number`, `area`, `class`, `documents`, `status`, `created_at`, `updated_at`) VALUES
(84, 'applicant1@gmail.com', '010-027-0001123452', '074-02-0001-015-31-0001654', 'Biliran', 'Naval', 'Padre Inocentes Garcia (Pob.)', 'San Juan', '{\"name\":\"Property 41\",\"type\":\"Residential\",\"coordinates\":[[11.559933133775772,124.39279510239516],[11.559954655364194,124.39284050800143],[11.559834805224568,124.39288861182854],[11.559813376640733,124.39283779103494]]}', '00-2', '2500', 'Residential', '[\"assets\\/images\\/uploads\\/documents\\/id.png\",\"assets\\/images\\/uploads\\/documents\\/Screenshot 2024-10-30 161847.png\"]', 'Admin Approved', '2024-11-02 19:30:25', '2024-11-03 01:12:47'),
(85, 'applicant1@gmail.com', '010-027-0001234567800', '074-02-0001-015-31-000106q255', 'Biliran', 'Naval', 'Libtong', 'St. P.I Garcia', '{\"name\":\"Property 40\",\"type\":\"Commercial\",\"coordinates\":[[11.560015088737003,124.39274176516216],[11.560048884846239,124.3928320154331],[11.559981354631304,124.3928582738726],[11.559946723745668,124.39276889418306]]}', '00-1', '2500', 'Commercial', '[\"assets\\/images\\/uploads\\/documents\\/Screenshot 2024-11-03 102803.png\"]', 'Staff Rejected', '2024-11-02 23:44:04', '2024-11-02 23:45:46'),
(88, 'applicant1@gmail.com', '010-027-0001q ehhr', '074-02-0001-015-31-0006123456782718 8', 'Biliran', 'Naval', 'Calumpang', 'St. P.I Garcia', '{\"name\":\"Property 29\",\"type\":\"Agricultural\",\"coordinates\":[[11.55982798603172,124.39192932203463],[11.559875875376392,124.39204930229909],[11.559472733907967,124.39221638592733],[11.559424844494359,124.39209285069217]]}', '00-1', '2500', 'Commercial', '[\"assets\\/images\\/uploads\\/documents\\/Table with Search Bar and Filters.png\"]', 'Admin Rejected', '2024-11-03 00:37:56', '2024-11-17 02:25:50'),
(89, 'applicant1@gmail.com', '010-027-00016311', '074-02-0001-015-31-000111151', 'Biliran', 'Naval', 'Talustusan', 'St. P.I Garcia', '{\"name\":\"Property 16\",\"type\":\"Commercial\",\"coordinates\":[[11.560893844771343,124.39315788510959],[11.560940656970871,124.3932869792124],[11.560916634922066,124.39329536194509],[11.560870028035481,124.39316710611683]]}', '00-3', '88888888', 'Commercial', '[\"assets\\/images\\/uploads\\/documents\\/Screenshot 2024-10-19 163225.png\",\"assets\\/images\\/uploads\\/documents\\/Screenshot 2024-10-19 170533.png\",\"assets\\/images\\/uploads\\/documents\\/Screenshot 2024-10-20 104930.png\"]', 'Admin Rejected', '2024-11-03 05:38:55', '2024-11-17 07:59:46'),
(90, 'applicant1@gmail.com', '010-027-00011311', '074-02-0001-015-31-00011442', 'Biliran', 'Naval', 'Villa Caneja', 'St. P.I Garcia', '{\"name\":\"Property 57\",\"type\":\"Residential\",\"coordinates\":[[11.560494658726569,124.39360481710247],[11.560586676243261,124.39383641576575],[11.560495230263754,124.393879001919],[11.560387209661954,124.393650320115]]}', '00-6', '200', 'Residential', '[\"assets\\/images\\/uploads\\/documents\\/462562714_1306395987011140_7081563750227595408_n.jpg\",\"assets\\/images\\/uploads\\/documents\\/Screenshot 2024-10-19 163225.png\",\"assets\\/images\\/uploads\\/documents\\/Screenshot 2024-10-19 170533.png\"]', 'Pending', '2024-11-03 06:40:22', '2024-11-16 20:44:39'),
(91, 'applicant1@gmail.com', '010-027-000111444', '074-02-0001-015-31-00013235', 'Biliran', 'Naval', 'Atipolo', 'St. P.I Garcia', '{\"name\":\"Property 36\",\"type\":\"Industrial\",\"coordinates\":[[11.5598404206238,124.39260328704319],[11.559892075551474,124.39275471641872],[11.559809187405861,124.39279211395677],[11.559744318405023,124.39264129765644]]}', '00-1', '2500', 'Industrial', '[\"assets\\/images\\/uploads\\/documents\\/Screenshot 2024-11-01 094434.png\",\"assets\\/images\\/uploads\\/documents\\/screenshot-1729958798203.png\",\"assets\\/images\\/uploads\\/documents\\/screenshot-1729961931677.png\"]', 'Pending', '2024-11-03 06:41:08', '2024-11-16 20:44:50'),
(92, 'applicant1@gmail.com', '010-027-00012134567890', '074-02-0001-015-31-000109876543', 'Biliran', 'Naval', 'San Pablo', 'St. P.I Garcia', '{\"name\":\"Property 57\",\"type\":\"Agricultural\",\"coordinates\":[[11.560494658726569,124.39360481710247],[11.560586676243261,124.39383641576575],[11.560495230263754,124.393879001919],[11.560387209661954,124.393650320115]]}', '00-5', '1500', 'Agricultural', '[\"assets\\/images\\/uploads\\/documents\\/Screenshot 2024-10-30 161847.png\",\"assets\\/images\\/uploads\\/documents\\/Screenshot 2024-11-01 094434.png\",\"assets\\/images\\/uploads\\/documents\\/screenshot-1729958798203.png\"]\r\n', 'Staff Approved', '2024-11-04 01:09:55', '2024-11-04 01:11:22'),
(93, 'applicant1@gmail.com', '010-027-00039876543', '074-02-0001-015-31-0001e67867', 'Biliran', 'Naval', 'Mabini', 'St. Maritana', '{\"name\":\"Property 23\",\"type\":\"Industrial\",\"coordinates\":[[11.56096944061457,124.39338228606755],[11.561001758330278,124.3934831873488],[11.560907086422816,124.39351656238819],[11.560874768695541,124.39341449686123]]}', '00-1', '2500', 'Industrial', '[\"assets\\/images\\/uploads\\/documents\\/Screenshot 2024-10-30 161847.png\",\"assets\\/images\\/uploads\\/documents\\/Screenshot 2024-11-01 094434.png\"]', 'Pending', '2024-11-04 01:12:42', '2024-11-16 20:44:59'),
(94, 'applicant1@gmail.com', '010-027-0003884', '074-02-0001-015-31-00249924', 'Biliran', 'Naval', 'Cabungaan', 'St. P.I Garcia', '{\"name\":\"Property 21\",\"type\":\"Mixed\",\"coordinates\":[[11.560520610871578,124.39222459748976],[11.56053031594142,124.39224967673516],[11.560465263511489,124.39227535464056],[11.560455353178881,124.39225045485267]]}', '00-2', '2500', 'Mixed', '[\"assets\\/images\\/uploads\\/documents\\/Screenshot 2024-10-30 161847.png\",\"assets\\/images\\/uploads\\/documents\\/Screenshot 2024-11-01 094434.png\"]', 'Staff Approved', '2024-11-04 07:23:19', '2024-11-04 07:23:53'),
(95, 'applicant1@gmail.com', '010-027-000134567', '074-02-0001-015-31-0001987654', 'Biliran', 'Cabucgayan', 'Baso', 'St. P.I Garcia', '{\"name\":\"Property 47\",\"type\":\"Commercial\",\"coordinates\":[[11.560244793521576,124.3928811557862],[11.560284791166808,124.39298162280187],[11.56013001763759,124.39306043438404],[11.560075064318355,124.39295464226103]]}', '00-6', '88888888', 'Commercial', '[\"assets\\/images\\/uploads\\/documents\\/N 210 (1).jpg\",\"assets\\/images\\/uploads\\/documents\\/N 210 (2).jpg\",\"assets\\/images\\/uploads\\/documents\\/N 210 (3).jpg\",\"assets\\/images\\/uploads\\/documents\\/N 210 (4).jpg\"]', 'Staff Approved', '2024-11-11 01:45:44', '2024-11-11 03:10:08'),
(96, 'applicant1@gmail.com', '010-027-0008', '074-02-0001-015-31-0007', 'Biliran', 'Naval', 'Lico', 'St. Maritana', '{\"name\":\"Property 49\",\"type\":\"Residential\",\"coordinates\":[[11.56038189431952,124.3931947556323],[11.560426264965344,124.39330685807681],[11.560286123991162,124.3933768100008],[11.56023604224707,124.39325932664008]]}', '00-34', '2500', 'Residential', '[\"assets\\/images\\/uploads\\/documents\\/screenshot-1714182368836.png\"]', 'Admin Approved', '2024-11-11 03:01:35', '2024-11-17 04:46:39'),
(97, 'applicant1@gmail.com', '010-027-00031234567899493', '074-02-0001-015-31-00013219494', 'Biliran', 'Almeria', 'Tamarindo', 'St. P.I Garcia', '{\"name\":\"Property 3\",\"type\":\"Residential\",\"coordinates\":[[11.56054191938513,124.39269520513005],[11.560590326199844,124.39281569412805],[11.560366975390394,124.39292058023568],[11.560317719294332,124.3927896893087]]}', '00-2', '88888888', 'Residential', '[\"assets\\/images\\/uploads\\/documents\\/screenshot-1731255394182.png\"]', 'Admin Approved', '2024-11-11 04:41:58', '2024-11-20 20:55:34'),
(98, 'applicant1@gmail.com', '010-027-0001-00', '074-02-0001-015-31-0001-0', 'Biliran', '', '', '', '{\"name\":\"Property 60\",\"type\":\"Industrial\",\"coordinates\":[[11.560489262149403,124.39414709957362],[11.560509993318462,124.39420064843603],[11.56045964618987,124.39422094518233],[11.560438491932132,124.39416955554793]]}', '00-1', '2500', 'Industrial', '[\"assets\\/images\\/uploads\\/documents\\/images.jpg\",\"assets\\/images\\/uploads\\/documents\\/rsbsa-card-v2.png\",\"assets\\/images\\/uploads\\/documents\\/screenshot-1731379565655.png\",\"assets\\/images\\/uploads\\/documents\\/UMID.webp\"]', 'Pending', '2024-11-12 15:09:07', '2024-11-12 15:09:07'),
(99, 'applicant1@gmail.com', '010-027-0003-00', '074-02-0001-015-31-0001-00', 'Biliran', '087802000', '087802002', '', '{\"name\":\"Property 28\",\"type\":\"Residential\",\"coordinates\":[[11.560762653862554,124.39375088328006],[11.560821384479624,124.39385653416684],[11.560753698483182,124.39389543579995],[11.560696009174379,124.39378935975964]]}', '00-1', '2500', 'Residential', '[\"assets\\/images\\/uploads\\/documents\\/rsbsa-card-v2.png\"]', 'Pending', '2024-11-12 15:11:12', '2024-11-12 15:11:12'),
(100, 'applicant1@gmail.com', '010-027-0001', '074-02-0001-015-31-0001', 'Biliran', 'Almeria', 'Talahid', '', '{\"name\":\"Property 50\",\"type\":\"Commercial\",\"coordinates\":[[11.560431698322375,124.39333987007984],[11.560474824947647,124.39347154614808],[11.560343569980901,124.39352169023113],[11.560297818235725,124.39340034920417]]}', '00-1', '2500', 'Commercial', '[\"assets\\/images\\/uploads\\/documents\\/rsbsa-card-v2.png\"]', 'Pending', '2024-11-12 15:15:16', '2024-11-12 15:15:16'),
(101, 'applicant1@gmail.com', '859', 'Aut in qui ea volupt', 'Biliran', 'Biliran', 'Bato', 'Delectus dolores co', '{\"name\":\"Property 58\",\"type\":\"Agricultural\",\"coordinates\":[[11.5603635156955,124.39365075358353],[11.560411747737263,124.39378808142305],[11.560339145818773,124.39382383848317],[11.5603635156955,124.39390778984114],[11.560260959115254,124.39395857523175],', '378', 'Illum et ut beatae ', 'Agricultural', '[\"assets\\/images\\/uploads\\/documents\\/UMID.webp\"]', 'Pending', '2024-11-13 04:45:50', '2024-11-13 04:45:50'),
(102, 'applicant1@gmail.com', '775', 'Accusamus eum qui do', 'Biliran', 'Cabucgayan', 'Salawad', 'Minima odit omnis pr', '{\"name\":\"Property 62\",\"type\":\"Commercial\",\"coordinates\":[[11.55967929216996,124.39218820432887],[11.55979787712036,124.3925139301009],[11.559644584369991,124.39257395811359],[11.559525999354676,124.39224528014398]]}', '26', 'Elit inventore nihi', 'Commercial', '[\"assets\\/images\\/uploads\\/documents\\/462547909_931690648782281_4109153555480916039_n.jpg\",\"assets\\/images\\/uploads\\/documents\\/462547909_931690648782281_4109153555480916039_n.png\"]', 'Pending', '2024-11-16 05:02:31', '2024-11-16 05:02:31'),
(103, 'applicant1@gmail.com', '559', 'Dolores illo volupta', 'Biliran', 'Naval', '', 'Voluptatem ea iure a', '{\"name\":\"Property 57\",\"type\":\"Agricultural\",\"coordinates\":[[11.560494658726569,124.39360481710247],[11.560586676243261,124.39383641576575],[11.560495230263754,124.393879001919],[11.560387209661954,124.393650320115]]}', '728', 'Eaque qui qui fugit', 'Agricultural', '[\"assets\\/images\\/uploads\\/documents\\/screenshot-1731258072223.png\",\"assets\\/images\\/uploads\\/documents\\/screenshot-1731379565655.png\",\"assets\\/images\\/uploads\\/documents\\/screenshot-1731505636039.png\"]', 'Pending', '2024-11-16 09:57:45', '2024-11-16 09:57:45'),
(104, 'applicant1@gmail.com', '22', 'Sit et minim ration', 'Biliran', 'Naval', '', 'Fugiat est molestiae', '{\"name\":\"Property 56\",\"type\":\"Mixed\",\"coordinates\":[[11.5602263485506,124.39345443413202],[11.560242772297997,124.3934862853684],[11.560213538028066,124.39350154043336],[11.560197771228616,124.39346968919693]]}', '907', 'Necessitatibus illum', 'Mixed', '[\"assets\\/images\\/uploads\\/documents\\/screenshot-1731506652614.png\",\"assets\\/images\\/uploads\\/documents\\/UMID.webp\",\"assets\\/images\\/uploads\\/documents\\/wkbyonbt (1).png\"]', 'Pending', '2024-11-16 09:58:42', '2024-11-16 09:58:42'),
(106, 'applicant1@gmail.com', '607', 'Repellendus Quis la', 'Biliran', 'Naval', 'Agpangi', 'Amet ut harum at ip', '{\"name\":\"Property 59\",\"type\":\"Residential\",\"coordinates\":[[11.560469278047108,124.39392710258795],[11.56049389951437,124.39398278563777],[11.560369826607868,124.39404881686607],[11.560436707062408,124.39424034193627],[11.560383601923562,124.39426645876529', '978', 'Cillum nostrum conse', 'Residential', '[\"assets\\/images\\/uploads\\/documents\\/screenshot-1731379565655.png\",\"assets\\/images\\/uploads\\/documents\\/screenshot-1731505636039.png\",\"assets\\/images\\/uploads\\/documents\\/screenshot-1731506652614.png\"]', 'Pending', '2024-11-16 10:06:07', '2024-11-16 10:06:07'),
(107, 'applicant1@gmail.com', '545', 'Porro accusantium am', 'Biliran', 'Naval', 'Calumpang', 'Qui consequat Incid', '{\"name\":\"Property 62\",\"type\":\"Mixed\",\"coordinates\":[[11.55967929216996,124.39218820432887],[11.55979787712036,124.3925139301009],[11.559644584369991,124.39257395811359],[11.559525999354676,124.39224528014398]]}', '531', 'Et reiciendis sint a', 'Mixed', '[\"assets\\/images\\/uploads\\/documents\\/462547909_931690648782281_4109153555480916039_n.jpg\",\"assets\\/images\\/uploads\\/documents\\/462547909_931690648782281_4109153555480916039_n.png\"]', 'Admin Approved', '2024-11-16 10:06:53', '2024-11-17 07:46:35'),
(110, 'applicant1@gmail.com', '010-027-0099', '074-02-0001-015-31-0099', 'Biliran', 'Naval', 'Santo NiÃ±o', '', '{\"name\":\"Property 43\",\"type\":\"Residential\",\"coordinates\":[[11.55992746062634,124.3929841314764],[11.559950962791945,124.39303344171128],[11.559888290346748,124.3930674257935],[11.55986185040409,124.39301744920328]]}', '00-1', '2500', 'Residential', '[\"assets\\/images\\/uploads\\/documents\\/rsbsa-card-v2.png\"]', 'Admin Approved', '2024-11-16 20:11:10', '2024-11-17 04:39:54'),
(111, 'jonhgabrielle@gmail.com', '87', 'Maxime est aliquip ', 'Biliran', 'Naval', 'Calumpang', 'Earum et suscipit vo', '{\"name\":\"Property 59\",\"type\":\"Agricultural\",\"coordinates\":[[11.560469278047108,124.39392710258795],[11.56049389951437,124.39398278563777],[11.560369826607868,124.39404881686607],[11.560436707062408,124.39424034193627],[11.560383601923562,124.3942664587652', '175', 'Sed corrupti at nem', 'Agricultural', '[\"assets\\/images\\/uploads\\/documents\\/screenshot-1731240056655.png\",\"assets\\/images\\/uploads\\/documents\\/screenshot-1731255394182.png\",\"assets\\/images\\/uploads\\/documents\\/screenshot-1731258072223.png\"]', 'Admin Approved', '2024-11-20 21:43:45', '2024-11-20 21:44:39');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` int NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) NOT NULL,
  `suffix` varchar(10) DEFAULT NULL,
  `province` varchar(100) NOT NULL,
  `municipality` varchar(100) NOT NULL,
  `barangay` varchar(100) NOT NULL,
  `street` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `valid_id` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `first_name`, `middle_name`, `last_name`, `suffix`, `province`, `municipality`, `barangay`, `street`, `email`, `contact_number`, `valid_id`, `password`, `created_at`) VALUES
(3, 'Staff', '', '1', '', '', '', '', '', 'staff1@gmail.com', '09123456789', '[\"assets\\/images\\/uploads\\/valid_id\\/sk-titulo-torrens.jpg\"]', '$2y$10$LZoYWy3N684NBwIQgFuetOK/vUeG4.7lyyhQwKre.Vu.JwxpB8IZ.', '2024-10-26 16:57:29'),
(10, 'Juan ', 'Dela', 'Cruz', 'Jr.', 'Biliran', 'Naval', 'Santissimo Rosario Pob.', 'Garcia St.', 'staff@gmail.com', '9876543210', '[\"assets\\/images\\/uploads\\/valid_id\\/staff\\/rsbsa-card-v2.png\"]', '$2y$10$hIxPg3w4cejOqbmK6YjE3.Sb2McARsgL6SNggIp.Bexr4SqADDzC.', '2024-11-17 07:06:44');

-- --------------------------------------------------------

--
-- Table structure for table `staff_approved_requests`
--

CREATE TABLE `staff_approved_requests` (
  `id` int NOT NULL,
  `request_id` int NOT NULL,
  `market_value` decimal(10,2) NOT NULL,
  `assessment_rate` varchar(10) NOT NULL,
  `assessed_value` decimal(10,2) NOT NULL,
  `basic_tax` decimal(10,2) NOT NULL,
  `sef` decimal(10,2) NOT NULL,
  `tax_due` decimal(10,2) NOT NULL,
  `staff_email` varchar(255) NOT NULL,
  `status` enum('Approved') DEFAULT 'Approved',
  `approved_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `staff_approved_requests`
--

INSERT INTO `staff_approved_requests` (`id`, `request_id`, `market_value`, `assessment_rate`, `assessed_value`, `basic_tax`, `sef`, `tax_due`, `staff_email`, `status`, `approved_at`) VALUES
(38, 84, 1000000.00, '20', 200000.00, 2000.00, 2000.00, 4000.00, 'staff1@gmail.com', 'Approved', '2024-11-03 15:37:24'),
(43, 88, 1000000.00, '18', 180000.00, 1800.00, 1800.00, 3600.00, 'applicant1@gmail.com', 'Approved', '2024-11-03 22:22:02'),
(44, 89, 1000000.00, '13', 130000.00, 1300.00, 1300.00, 2600.00, 'applicant1@gmail.com', 'Approved', '2024-11-03 22:37:47'),
(47, 92, 500000.00, '15', 75000.00, 750.00, 750.00, 1500.00, 'staff1@gmail.com', 'Approved', '2024-11-04 17:11:22'),
(49, 94, 750000.00, '15', 112500.00, 1125.00, 1125.00, 2250.00, 'staff1@gmail.com', 'Approved', '2024-11-04 23:23:53'),
(50, 96, 400000.00, '12', 48000.00, 480.00, 480.00, 960.00, 'staff1@gmail.com', 'Approved', '2024-11-11 19:05:14'),
(51, 95, 500000.00, '12', 60000.00, 600.00, 600.00, 1200.00, 'staff1@gmail.com', 'Approved', '2024-11-11 19:10:08'),
(52, 97, 500000.00, '20', 100000.00, 1000.00, 1000.00, 2000.00, 'staff1@gmail.com', 'Approved', '2024-11-12 17:51:23'),
(53, 110, 1000000.00, '18', 180000.00, 1800.00, 1800.00, 3600.00, 'applicant1@gmail.com', 'Approved', '2024-11-17 12:13:45'),
(54, 107, 1000000.00, '20', 200000.00, 2000.00, 2000.00, 4000.00, 'applicant1@gmail.com', 'Approved', '2024-11-17 15:19:58'),

-- --------------------------------------------------------

--
-- Table structure for table `staff_rejected_requests`
--

CREATE TABLE `staff_rejected_requests` (
  `id` int NOT NULL,
  `request_id` int NOT NULL,
  `rejection_category` enum('Incomplete Documents','Invalid Data','Other') NOT NULL,
  `feedback` text NOT NULL,
  `document_status` enum('Valid','Invalid','Missing') NOT NULL,
  `staff_email` varchar(255) NOT NULL,
  `status` enum('Rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'Rejected',
  `rejected_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `staff_rejected_requests`
--

INSERT INTO `staff_rejected_requests` (`id`, `request_id`, `rejection_category`, `feedback`, `document_status`, `staff_email`, `status`, `rejected_at`) VALUES
(9, 85, 'Invalid Data', '12345678ujn', 'Invalid', 'applicant1@gmail.com', 'Rejected', '2024-11-02 23:45:46');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `admin_approved_requests`
--
ALTER TABLE `admin_approved_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `staff_approved_requests_fk` (`request_id`);

--
-- Indexes for table `admin_rejected_requests`
--
ALTER TABLE `admin_rejected_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_rejected_request_id_fk` (`request_id`);

--
-- Indexes for table `applicants`
--
ALTER TABLE `applicants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `approved_applicants`
--
ALTER TABLE `approved_applicants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `approved_applicant_email_fk` (`applicant_email`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `properties`
--
ALTER TABLE `properties`
  ADD PRIMARY KEY (`id`),
  ADD KEY `property_request_fk` (`request_id`);

--
-- Indexes for table `rejected_applicants`
--
ALTER TABLE `rejected_applicants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rejected_applicant_email_fk` (`applicant_email`);

--
-- Indexes for table `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `td_number` (`td_number`,`pin`),
  ADD KEY `applicant_email_fk` (`applicant_email`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `staff_approved_requests`
--
ALTER TABLE `staff_approved_requests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `request_id` (`request_id`),
  ADD KEY `request_id_fk` (`request_id`);

--
-- Indexes for table `staff_rejected_requests`
--
ALTER TABLE `staff_rejected_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rejected_request_id_fk` (`request_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `admin_approved_requests`
--
ALTER TABLE `admin_approved_requests`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `admin_rejected_requests`
--
ALTER TABLE `admin_rejected_requests`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `applicants`
--
ALTER TABLE `applicants`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `approved_applicants`
--
ALTER TABLE `approved_applicants`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT for table `properties`
--
ALTER TABLE `properties`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=264;

--
-- AUTO_INCREMENT for table `rejected_applicants`
--
ALTER TABLE `rejected_applicants`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `requests`
--
ALTER TABLE `requests`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `staff_approved_requests`
--
ALTER TABLE `staff_approved_requests`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `staff_rejected_requests`
--
ALTER TABLE `staff_rejected_requests`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `approved_applicants`
--
ALTER TABLE `approved_applicants`
  ADD CONSTRAINT `approved_applicant_email_fk` FOREIGN KEY (`applicant_email`) REFERENCES `applicants` (`email`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `properties`
--
ALTER TABLE `properties`
  ADD CONSTRAINT `property_request_fk` FOREIGN KEY (`request_id`) REFERENCES `requests` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `rejected_applicants`
--
ALTER TABLE `rejected_applicants`
  ADD CONSTRAINT `rejected_applicant_email_fk` FOREIGN KEY (`applicant_email`) REFERENCES `applicants` (`email`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `requests`
--
ALTER TABLE `requests`
  ADD CONSTRAINT `applicant_email_fk` FOREIGN KEY (`applicant_email`) REFERENCES `applicants` (`email`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `staff_approved_requests`
--
ALTER TABLE `staff_approved_requests`
  ADD CONSTRAINT `request_id_fk` FOREIGN KEY (`request_id`) REFERENCES `requests` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `staff_rejected_requests`
--
ALTER TABLE `staff_rejected_requests`
  ADD CONSTRAINT `rejected_request_id_fk` FOREIGN KEY (`request_id`) REFERENCES `requests` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
