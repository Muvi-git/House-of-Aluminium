-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 23, 2026 at 12:49 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `house_of_aluminium`
--

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` int(11) NOT NULL,
  `sub_category_id` int(11) DEFAULT NULL,
  `brand_name` varchar(255) NOT NULL,
  `image_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `sub_category_id`, `brand_name`, `image_name`) VALUES
(1, 1, 'Buildbond', 'Buildbond1.jpg'),
(4, 14, 'Aluminium', '1784197351_brand_alu-hinge.jpg'),
(5, 14, 'Bar Hinges', '1784197368_brand_eqe.jpg'),
(6, 14, 'Floor Hinges', '1784197383_brand_FSDGF.jpg'),
(7, 14, 'Glass Hinges', '1784197397_brand_GLASS-HINGE.jpg'),
(8, 14, 'Pivot Hinges', '1784197413_brand_bearing-pivot-hinge-500x500-1.jpg'),
(9, 14, 'Stainless Steel Hinges', '1784197429_brand_SS-1.jpg'),
(10, 16, 'Handles', '1784197479_brand_qewe.jpg'),
(11, 2, 'Cladbond', '1784197076_brand_L-1.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `image_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `image_name`, `created_at`) VALUES
(1, 'Aluminium Composite Panels', 'cat-panels.jpg', '2026-07-14 03:50:35'),
(2, 'Aluminium Extrusions', 'extrusions.jpg', '2026-07-14 03:50:35'),
(3, 'Aluminium Solid Panels', 'solid-panels.jpg', '2026-07-14 03:50:35'),
(4, 'Architectural Hardware', 'hardware.jpg', '2026-07-14 03:50:35'),
(5, 'Door Locks & Handles', 'door-locks.jpg', '2026-07-14 03:50:35'),
(6, 'Doors', 'doors.jpg', '2026-07-14 03:50:35'),
(7, 'Expanded Metal Mesh / Amplimesh', 'metal-mesh.jpg', '2026-07-14 03:50:35'),
(8, 'Mosquito Mesh', 'mosquito-mesh.jpg', '2026-07-14 03:50:35'),
(9, 'Silicone Sealants', 'silicone-sealants.jpg', '2026-07-14 03:50:35'),
(10, 'Tools & Machinery', 'tools-machinery.jpg', '2026-07-14 03:50:35');

-- --------------------------------------------------------

--
-- Table structure for table `contact_submissions`
--

CREATE TABLE `contact_submissions` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `message` text NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_submissions`
--

INSERT INTO `contact_submissions` (`id`, `name`, `email`, `phone`, `message`, `created_at`) VALUES
(1, 'Adithya Ganewaththa', 'adithyaanuhasgo@gmail.com', '1111', '1111111111', '2026-07-15 10:45:33'),
(2, 'Adithya Ganewaththa', 'adithyaanuhasgo@gmail.com', '1111', 'uuuu', '2026-07-16 11:25:02'),
(3, 'Adithya Ganewaththa', 'adithyaanuhasgo@gmail.com', '1111', 'llllllllllllllllllllllllllllll', '2026-07-16 15:32:02');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `whatsapp_number` varchar(20) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` varchar(20) DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `whatsapp_number`, `total_amount`, `status`, `created_at`) VALUES
(2, 1, '077777777', 0.00, 'Pending', '2026-07-16 05:15:44'),
(3, 1, 'eeeeeeeeee', 0.00, 'Pending', '2026-07-16 05:28:48'),
(4, 2, '1234567888', 0.00, 'Pending', '2026-07-16 05:31:52'),
(5, 1, '0111111111111', 0.00, 'Pending', '2026-07-16 10:26:35'),
(6, 3, '23455555555555', 0.00, 'Pending', '2026-07-16 10:28:55'),
(7, 1, '555555555', 0.00, 'Pending', '2026-07-17 09:08:48'),
(8, 1, '5555555555', 0.00, 'Pending', '2026-07-18 04:02:39');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, 377, 2, 0.00),
(2, 1, 378, 2, 0.00),
(3, 1, 379, 377, 0.00),
(4, 2, 377, 1, 0.00),
(5, 3, 172, 1, 0.00),
(6, 3, 174, 1, 0.00),
(7, 4, 172, 1, 0.00),
(8, 4, 174, 1, 0.00),
(9, 5, 172, 1, 0.00),
(10, 5, 174, 1, 0.00),
(11, 6, 172, 1, 0.00),
(12, 6, 174, 1, 0.00),
(13, 7, 172, 1, 0.00),
(14, 7, 174, 1, 0.00),
(15, 8, 172, 1, 0.00),
(16, 8, 174, 1, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `payment_submissions`
--

CREATE TABLE `payment_submissions` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `contact_number` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `reference` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `payment_type` varchar(100) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_slip` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment_submissions`
--

INSERT INTO `payment_submissions` (`id`, `customer_name`, `contact_number`, `email`, `reference`, `address`, `payment_type`, `amount`, `payment_slip`, `status`, `created_at`) VALUES
(1, 'muvi', '1234', 'kaushangamage123@gmail.com', '123', '1234', 'Full Payment', 12222.00, '1784180299_online_slip_h6ltAG7v1m.jpg', 'Pending', '2026-07-16 11:08:19'),
(2, 'muvi', '1234', 'kaushangamage123@gmail.com', '123', '1234', 'Full Payment', 12222.00, '1784181825_online_slip_h6ltAG7v1m.jpg', 'Pending', '2026-07-16 11:33:45');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `sub_category_id` int(11) DEFAULT NULL,
  `brand_id` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `short_desc` text DEFAULT NULL,
  `image_primary` varchar(255) DEFAULT NULL,
  `image_secondary` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `category_id`, `sub_category_id`, `brand_id`, `description`, `price`, `short_desc`, `image_primary`, `image_secondary`, `created_at`) VALUES
(5, 'Buildbond - ACP', 1, 1, 1, '<p>Aluminum composite panel(ACP) is a form of popular wall decorative composite materials that come in different thicknesses, types, and are used for different purposes.</p>\r\n    \r\n    <h4 style=\"margin-top:20px; color:#0f172a; font-size:16px;\">Key Features:</h4>\r\n    <ul style=\"margin-left: 20px; color:#475569; line-height:1.8; margin-bottom:15px;\">\r\n        <li><strong>Color:</strong> Multicolor</li>\r\n        <li><strong>Material:</strong> Aluminium</li>\r\n        <li><strong>Surface Treatment:</strong> PVDF/PE Coating</li>\r\n    </ul>\r\n    \r\n    <h4 style=\"margin-top:20px; color:#0f172a; font-size:16px;\">Available Sizes:</h4>\r\n    <ul style=\"margin-left: 20px; color:#475569; line-height:1.8; margin-bottom:15px;\">\r\n        <li>1220 (Width) * 2440 (Length) * 3mm / 4mm</li>\r\n        <li>1220 (Width) * 3800 (Length) * 4mm</li>\r\n        <li>915 (Width) * Any Length * 3mm / 4mm</li>\r\n    </ul>\r\n    \r\n    <h4 style=\"margin-top:20px; color:#0f172a; font-size:16px;\">Finishes:</h4>\r\n    <ul style=\"margin-left: 20px; color:#475569; line-height:1.8;\">\r\n        <li>PVDF / PE Coating (Single / Both side)</li>\r\n        <li><strong style=\"color:#ff3333;\">Fire Resistance Also Available</strong></li>\r\n    </ul>', 0.00, 'Aluminum composite panel(ACP) is a form of popular wall decorative composite materials that come in different thicknesses, types, and are used for different purposes.', 'comp1.jpg', '1784606403_hover_1-4-660x800.jpg', '2026-07-14 05:12:27'),
(6, 'Cladbond - ACP', 1, 2, 11, '<strong>Aluminum composite panel(ACP)</strong> is a form of popular wall decorative composite materials that come in different thicknesses, types, and are used for different purposes.', 0.00, 'Aluminum composite panel(ACP) is a form of popular wall decorative composite materials that come in different thicknesses, types, and are used for different purposes.', '1784801095_Untitled-2-660x800.jpg', '1784801095_hover_a-1-660x800.jpg', '2026-07-14 07:50:11'),
(16, 'Casement Window - 41mm', 2, 4, NULL, 'Material :  ALUMINIUM\r\n\r\nColour – Natural / Browns', 0.00, '', '1784800896_1-17.jpg', '1784800896_hover_2-13.jpg', '2026-07-14 09:19:53'),
(17, 'Solid Panels - 0602-2', 3, NULL, NULL, '<strong>It is highly resistant to corrosion, making it ideal for indoor and outdoor applications. They are available in a wide variety of designs and colors.</strong>\r\n\r\nMaterial – Aluminium\r\nDimensions – 10.5’ x 4 , 8’ x 4\r\nThickness – 2.0mm , 2.5mm\r\nColours – Red, Blue, White, Silver ,Yellow, Black etc.. (can be customized according to your requirement )\r\nApplications – Exterior and interior wall, Facades, Balcony & Stair Guardrails, Handrail and more……', 0.00, 'Decorative Aluminium Solid Panels', '1784800816_2-4 (1).jpg', '1784800816_hover_2-1 (1).jpg', '2026-07-14 09:19:53'),
(18, 'Solid Panels - CL-0602', 3, NULL, NULL, '<strong>It is highly resistant to corrosion, making it ideal for indoor and outdoor applications. They are available in a wide variety of designs and colors.</strong>\r\n\r\nMaterial – Aluminium\r\nDimensions – 10.5’ x 4 , 8’ x 4\r\nThickness – 2.0mm , 2.5mm\r\nColours – Red, Blue, White, Silver ,Yellow, Black etc.. (can be customized according to your requirement )\r\nApplications – Exterior and interior wall, Facades, Balcony & Stair Guardrails, Handrail and more……', 0.00, 'Decorative Aluminium Solid Panels', '1784800696_4 (1).jpg', '', '2026-07-14 09:19:53'),
(19, 'Solid Panels - CL-0606', 3, NULL, NULL, '<strong>It is highly resistant to corrosion, making it ideal for indoor and outdoor applications. They are available in a wide variety of designs and colors.</strong>\r\n\r\nMaterial – Aluminium\r\nDimensions – 10.5’ x 4 , 8’ x 4\r\nThickness – 2.0mm , 2.5mm\r\nColours – Red, Blue, White, Silver ,Yellow, Black etc.. (can be customized according to your requirement )\r\nApplications – Exterior and interior wall, Facades, Balcony & Stair Guardrails, Handrail and more…..', 0.00, 'Decorative Aluminium Solid Panels', '1784800633_3 (1).jpg', '', '2026-07-14 09:19:53'),
(20, 'Solid Panels - CL-0626', 3, NULL, NULL, '<strong>It is highly resistant to corrosion, making it ideal for indoor and outdoor applications. They are available in a wide variety of designs and colors.</strong>\r\n\r\nMaterial – Aluminium\r\nDimensions – 10.5’ x 4 , 8’ x 4\r\nThickness – 2.0mm , 2.5mm\r\nColours – Red, Blue, White, Silver ,Yellow, Black etc.. (can be customized according to your requirement )\r\nApplications – Exterior and interior wall, Facades, Balcony & Stair Guardrails, Handrail and more……', 0.00, 'Decorative Aluminium Solid Panels', '1784800577_6-1 (1).jpg', '', '2026-07-14 09:19:53'),
(21, 'Solid Panels - CL-0621', 3, NULL, NULL, '<strong>It is highly resistant to corrosion, making it ideal for indoor and outdoor applications. They are available in a wide variety of designs and colors.</strong>\r\n\r\nMaterial – Aluminium\r\nDimensions – 10.5’ x 4 , 8’ x 4\r\nThickness – 2.0mm , 2.5mm\r\nColours – Red, Blue, White, Silver ,Yellow, Black etc.. (can be customized according to your requirement )\r\nApplications – Exterior and interior wall, Facades, Balcony & Stair Guardrails, Handrail and more……', 0.00, 'Decorative Aluminium Solid Panels', '1784800515_5.jpg', '', '2026-07-14 09:19:53'),
(22, 'Solid Panels - CL-0603', 3, NULL, NULL, '<strong>It is highly resistant to corrosion, making it ideal for indoor and outdoor applications. They are available in a wide variety of designs and colors.</strong>\r\n\r\nMaterial – Aluminium\r\nDimensions – 10.5’ x 4 , 8’ x 4\r\nThickness – 2.0mm , 2.5mm\r\nColours – Red, Blue, White, Silver ,Yellow, Black etc.. (can be customized according to your requirement )\r\nApplications – Exterior and interior wall, Facades, Balcony & Stair Guardrails, Handrail and more…..', 0.00, '', '1784800458_7-1 (1).jpg', '', '2026-07-14 09:19:53'),
(144, 'Door Seal - Brush 1', 4, 10, NULL, 'Model : MS/DBS/BRS/BL01M\r\n\r\nSize : 01M\r\n\r\nColour : Black', 0.00, '', '1784800397_BLACK-BRUSH.jpg', '', '2026-07-14 09:34:54'),
(145, 'Door Seal - Rubber 1', 4, 10, NULL, 'Model : MS#DBS/BR\r\n\r\nSize : 01M\r\n\r\nColour : Brown', 0.00, '', '1784800369_BROWN-RUBBER.jpg', '', '2026-07-14 09:34:54'),
(146, 'Door Seal - Rubber 2', 4, 10, NULL, 'Model : MS/DBS/RUB/BL01\r\n\r\nSize : 01M\r\n\r\nColour : Black', 0.00, '', '1784800326_RUBBER-BLACK.jpg', '', '2026-07-14 09:34:54'),
(147, 'Door Closer - ITS96V', 4, 11, NULL, 'Model:ITS96V\r\nType: Door Closer\r\nUsage: Door\r\nBody Dimension: 290*52.5*47\r\nPower Size (EN Standard): 2-4\r\nDoor Width(mm) EN Standard: 850-1100\r\nMounting Plate: Concealed installation\r\nColor: Natural', 0.00, '', '1784800276_Untitled-1.jpg', '1784800287_hover_2.jpg', '2026-07-14 09:34:54'),
(148, 'Door Closer - 98', 4, 11, NULL, 'Model : DCB#108 VVP 98\r\n\r\nMaterial : Stainless Steel 304\r\n\r\nColours : BR / NA', 0.00, '', '1784800230_DC98.jpg', '', '2026-07-14 09:34:54'),
(149, 'Door closer - B 103', 4, 11, NULL, 'Model : KLN/DC/B103/WH\r\n\r\nMaterial : Aluminium\r\n\r\nColour : White\r\n\r\nLocation type :  Non-hold\r\n\r\nDoor width :  850~1,100mm\r\n\r\nMaximum Loading :   65kg\r\n\r\nLife cycle : >500,000 Cycles\r\n\r\nSpeed control :  Two speed section\r\n\r\nSuitable Temperature :  -30°C~50°C', 0.00, '', '1784800194_104.jpg', '1784800194_hover_B103.jpg', '2026-07-14 09:34:54'),
(150, 'Door Closer - B 401', 4, 11, NULL, 'Model :KLN/DC/B401/NA\r\n\r\nMaterial : Aluminium\r\n\r\nColour : Natural /White\r\n\r\nLocation type :  Non-hold\r\n\r\nDoor width : 650~900mm\r\n\r\nMaximum Loading :  45kg\r\n\r\nLife cycle : >300,000 Cycles\r\n\r\nSpeed control :  Two speed section\r\n\r\nSuitable Temperature :  -30°C~50°C', 0.00, '', '1784800135_424.jpg', '1784800135_hover_401.jpg', '2026-07-14 09:34:54'),
(151, 'Door Closer - DC69V', 4, 11, NULL, 'Model:DC69V\r\nType: Door Closer\r\nUsage: Door\r\nCertificate: GMC, CE, ISO9001\\2008\r\nBody Dimension: 220*72*47.5\r\nPower Size (EN Standard): 2-4\r\nDoor Width(mm) EN Standard: 850-1100\r\nMounting Plate: Hinge side\r\nColor: Natural', 0.00, '', '1784800070_dc69v1.jpg', '1784800070_hover_dc69v2.jpg', '2026-07-14 09:34:54'),
(152, 'Door Closer - B415', 4, 11, NULL, 'Model : KLN/DCN/B405\r\n\r\nLocation type : Non-hold\r\n\r\nDoor width : 1,150~1,400mm\r\n\r\nMaximum Bearing : 120kg\r\n\r\nLife cycle : >500,000 Cycles\r\n\r\nSpeed control Two speed section Suitable\r\n\r\nTemperature  : 30°C~50°C\r\n\r\nAvailable Colours  : Silver', 0.00, '', '1784800019_415-2.jpg', '1784800019_hover_415-1.jpg', '2026-07-14 09:34:54'),
(153, 'Door Closer - DC601', 4, 11, NULL, 'Model : ICSA/DC601\r\n\r\nMaterial : Stainless Steel 304\r\n\r\nColours : Black, White, Silver', 0.00, '', '1784799948_dc601-ICSA.jpg', '', '2026-07-14 09:34:54'),
(154, 'Door Closer Bracket - BZJ03', 4, 11, NULL, 'Model : KLN/BKT/BZJ03\r\n\r\nSuitable for door closer :  B103、B401、B701、B703、B802 , B803、B902、B903', 0.00, '', '1784799905_bzj03.jpg', '1784799905_hover_bzj03-2.jpg', '2026-07-14 09:34:54'),
(155, 'Door Coordinator - DC-004', 4, 11, NULL, 'Gravity Door Coordinator  for Double  Doors\r\n\r\nModel : 3H/DC/DC-004\r\n\r\nMaterial – Stainless Steel\r\n\r\nNon-handed Reversible\r\nRoller arm stops active door from closing before the inactive door.\r\nGravity action arm and door bracket are adjustable on the job for ease of installation\r\nUsed with door closer to make sure the doors is closed property and completely\r\nAvailable for Heavy Duty Double Doors of Public Buildings.', 0.00, '', '1784799857_DC-004.jpg', '1784799857_hover_df.jpg', '2026-07-14 09:34:54'),
(156, 'Door Coordinator - DC-002', 4, 11, NULL, 'Universal Door Coordinator for Double Doors\r\n\r\nModel : 3H/DC/DC-002\r\n\r\nMaterial – Stainless Steel 304\r\n\r\nNon-handed\r\nRoller arm stops active door from closing before the inactive door.\r\nUsed with door closer to make sure the doors in closed property and completely', 0.00, '', '1784799793_DC-002-1.jpg', '1784799793_hover_DC-002.jpg', '2026-07-14 09:34:54'),
(157, 'Door Stopper - 085SS', 4, 12, NULL, '<strong>Magnetic Door Stopper</strong>\r\nModel :3H/DS/HHDH-085SS\r\nMaterial: Stainless Steel 304\r\nType: Door Stop\r\nInstallation: Floor Mounted\r\nFinish: Satin/Mirror\r\nControl: Manual\r\nPunching: Without Hole Drilling', 0.00, '', '1784799727_001-ST.jpg', '1784799727_hover_FH.jpg', '2026-07-14 09:34:54'),
(158, 'Door Stopper - DS-001', 4, 12, NULL, '<strong>Half Moon Door Stopper</strong>\r\nModel : 3H/DS/DS-001-SS\r\nMaterial: Stainless Steel 304\r\nType: Door Stop\r\nInstallation: Floor Mounted\r\nFinish: Brushed Steel\r\nControl: Manual\r\nPunching: With Hole Drilling\r\n\r\nIdeal for stopping doors from banging and damaging the wall.\r\nIdeal for glass doors.\r\nHas built in soft rubber Bumper', 0.00, '', '1784799612_001.jpg', '', '2026-07-14 09:34:54'),
(159, 'Door Stopper - DS-039SS', 4, 12, NULL, '<strong>Door Stopper</strong>\r\nModel : 3H/DS/DS039SS/HL\r\nMaterial: Stainless Steel 304\r\nType: Door Stop\r\nFinish: Brushed Steel\r\nControl: Manual\r\nPunching: With Hole Drilling', 0.00, '', '1784799550_039.jpg', '', '2026-07-14 09:34:54'),
(160, 'Door Stopper - DS-043', 4, 12, NULL, '<strong>Step-on Door Stopper</strong>\r\nModel : 3H/DS/DS-043-SS\r\nMaterial: Stainless Steel 304\r\nType: Door Stop\r\nInstallation: Floor Mounted\r\nFinish: Satin/Mirror\r\nControl: Manual\r\nPunching: With Hole Drilling\r\n\r\n<strong>Description</strong>\r\nStep on door stopper is a piece of equipment that help to hold the door open by stepping on the stopper. It is stainless steel construction with chromium layer that protects from rust and corrosion.\r\n\r\nThis is highly recommended for bedroom door to hold the door open at all times.\r\n\r\n<strong>Features</strong>\r\n\r\nThis door stopper has high quality construction\r\nIt can help to hold the door open\r\nInstall: door mount', 0.00, '', '1784799470_043.jpg', '', '2026-07-14 09:34:54'),
(161, 'Door Stopper - DS-045', 4, 12, NULL, '<strong>Door Stopper</strong>\r\nModel : 3H/DS/DS-045-ZA\r\nMaterial: Stainless Steel 304\r\nType: Door Stop\r\nInstallation: Door Mounted\r\nFinish: Brushed Steel\r\nControl: Manual\r\nPunching: With Hole Drilling', 0.00, '', '1784799379_045.jpg', '', '2026-07-14 09:34:54'),
(162, 'Door Stopper - DS-046', 4, 12, NULL, '<strong>Door Stopper</strong>\r\nModel : 3H/DS/DS-046-SS\r\nMaterial: Stainless Steel 304\r\nType: Door Stop\r\nFinish: Satin/Mirror\r\nControl: Manual\r\nPunching: With Hole Drilling', 0.00, '', '1784799328_046.jpg', '1784799328_hover_046-2.jpg', '2026-07-14 09:34:54'),
(163, 'Door Stopper - DS-100', 4, 12, NULL, 'Magnetic Door Stopper\r\n\r\nModel : KLN/DS/DS100E\r\n\r\nMaterial : Stainless Steel 304', 0.00, '', '1784799260_ds100.jpg', '', '2026-07-14 09:34:54'),
(164, 'Door Stopper - MS002', 4, 12, NULL, 'Half Moon Door Stopper\r\n\r\nModel : KLN/DS/MD002E\r\n\r\nMaterial : Stainless Steel 304\r\n\r\nSurface Finish : Satin', 0.00, '', '1784799209_md002.jpg', '', '2026-07-14 09:34:54'),
(165, 'Window Stopper', 4, 12, NULL, 'Model : MS/WINSTOP\r\n\r\nMaterial : Plastic\r\n\r\nColours : Black & White', 0.00, '', '1784799176_WINDOWSTOP.jpg', '', '2026-07-14 09:34:54'),
(166, 'Flush Bolt - BS10', 4, 13, NULL, 'Model : VVP/BS10\r\n\r\nColours : Black , Silver\r\n\r\nRelated product', 0.00, '', '1784799148_bs10.jpg', '', '2026-07-14 09:34:54'),
(167, 'Flush Bolt - DB-009', 4, 13, NULL, 'Model : 3H/BT/SS/DB-009/8', 0.00, '', '1784799115_FLUSH-BOLT-009.jpg', '', '2026-07-14 09:34:54'),
(168, 'Flush Bolt - DB-014', 4, 13, NULL, 'Model : 3H/BT/SS/DB-014\r\n\r\nAvailable Size : 12″ / 8″', 0.00, '', '1784799083_14.jpg', '', '2026-07-14 09:34:54'),
(169, 'Flush Bolt (Auto) - DB-016', 4, 13, NULL, 'Model : 3H/BT/SS/DB-016\r\n\r\nMaterial : Stainless Steel 304', 0.00, '', '1784799049_DB-016.jpg', '', '2026-07-14 09:34:54'),
(170, 'Flush Bolt DB-006', 4, 13, NULL, 'Model:DB-006\r\nUsage: Sliding Door\r\nMaterial: Stainless Steel\r\nSize: Length:  8″\r\nColor: Natural\r\nProcess: Die casting + Surface treatment + Assembling\r\nSurface Treatment: Polishing, Satin', 0.00, '', '1784799022_DB-006_1.jpg', '1784799022_hover_DB-006-2-1.jpg', '2026-07-14 09:34:54'),
(171, 'Alu Hinge - N034', 4, 14, 4, 'Model : HI/AL/3HN034\r\n\r\nMaterial : Aluminium\r\n\r\nColours : Silver & White', 0.00, '', '1784798972_N034.jpg', '', '2026-07-14 09:34:54'),
(172, 'Alu Hinge - N057', 4, 14, 4, 'ALU WINDOW HINGE\r\n\r\nModel :  HI/ALU/3HN057\r\n\r\nMaterial : Aluminium\r\n\r\nColours : Black, White, Silver', 0.00, '', '1784798925_N057.jpg', '', '2026-07-14 09:34:54'),
(173, 'Bar Hinges - HC110', 4, 14, 5, 'Model : KLN/HI/4BA/HC110\r\n\r\nMaterial  : Stainless Steel 304\r\n\r\nSizes : 8″ / 10″ / 12″ / 14″\r\n\r\nUsed for Side-hung Window.', 0.00, '', '1784798827_HC110.jpg', '', '2026-07-14 09:34:54'),
(174, 'Bar Hinges - HC320', 4, 14, 5, 'Model : KLN/HI/4BA/HC320-14\r\n\r\nSize : 14″', 0.00, '', '1784798769_320.jpg', '', '2026-07-14 09:34:54'),
(175, 'Floor Hinges - B 120', 4, 14, 6, 'Floor Hinge with Accessories\r\n\r\nModel – 3H/FLH/B-120ACC\r\n\r\nMaterial – Stainless Steel 304\r\n\r\nDoor Weight – 90kg\r\n\r\nDoor Width – 750-1050mm\r\n\r\nMaximum Opening Angle – 115 Degree', 0.00, '', '1784798740_120-2.jpg', '1784798740_hover_120-3.jpg', '2026-07-14 09:34:54'),
(176, 'Floor Hinges - B-220', 4, 14, 6, 'Floor Hinge with Accessories\r\n\r\nModel – 3H/FLH/B-220ACC\r\n\r\nMaterial – Stainless Steel 304\r\n\r\nDoor Weight – 110kg\r\n\r\nDoor Width – 850-1100mm\r\n\r\nMaximum Opening Angle – 115 Degree', 0.00, '', '1784798668_220-1.jpg', '1784798668_hover_220-2.jpg', '2026-07-14 09:34:54'),
(177, 'Floor Hinges - HD203', 4, 14, 6, 'Model : KLN/FLH/HD203\r\n\r\nMaterial : Stainless Steel 316\r\n\r\nLocation type : 90 °/Non-hold\r\nDoor width : 650~1,050mm\r\nMaximum Loading : 100kg\r\nLife cycle : >1,000,000 Cycles\r\nMain Material : Main body–Gray pig iron;\r\nCover plate : SS304\r\nSpeed control : Two speed section\r\nSuitable Temperature : -30°C~50°C', 0.00, '', '1784798616_HD-203.jpg', '1784798616_hover_203-2.jpg', '2026-07-14 09:34:54'),
(178, 'Floor Spring - BTS65', 4, 14, 6, 'Light Traffic Double Action Doors\r\n\r\nModel : DOM/FLH/BTS65\r\nMaterial : Stainless Steel 304\r\nFeatures\r\n1. Smooth closing operation virtually temperature immune\r\n2. Optional integrated single-point hold-open at 90°\r\n3. Rugged construction for door weights up to 100kg\r\n4. Minimum floor recess dimension', 0.00, '', '1784798553_DOMA-BTS65-1.jpg', '', '2026-07-14 09:34:54'),
(179, 'Floor Spring - BTS84', 4, 14, 6, 'Double action floor spring\r\n\r\nModel : DOM/FLH/BTS84\r\nMaterial : Stainless Steel 304\r\nFeatures\r\n1. Thermo constant smooth operation\r\n2. High mechanical efficiency for easy door opening\r\n3. Proven, robust unit for door weights upto 100kg\r\n4. Concealed unit for invisible installation\r\n5. Minimum floor recess dimension', 0.00, '', '1784798521_BTS84.jpg', '', '2026-07-14 09:34:54'),
(180, 'Glass Hinges - Y1102', 4, 14, 7, 'Model : KLN/HI/SHW/Y1102/PM\r\n\r\n1. Casting Material: CF8\r\n\r\n2. Applicable glass thickness: 8-12mm\r\n\r\n3. Maximum Loading: 65kg/pair\r\n\r\n4. Function: Bidirectional 90 degree and Elastic Reset 25 degree\r\n\r\n5. Selection of high-quality spring, the service life is more than 250,000 times\r\n\r\n6. Finish: Mirror', 0.00, '', '1784798491_y1102.jpg', '', '2026-07-14 09:34:55'),
(181, 'Shower Hinge - SW303', 4, 14, 7, 'Model : VVP/SW303/90PS\r\nMaterial : Stainles Steel 304\r\nSingle Hinge (Wall to Glass) 8mm – 10mm only', 0.00, '', '1784798460_SW303.jpg', '', '2026-07-14 09:34:55'),
(182, 'Shower Hinge - SW304', 4, 14, 7, 'Model : VVP/SW304/180/PS\r\nMaterial : Stainles Steel 304\r\nDouble Hinge (Glass to Glass) 8mm – 10mm only', 0.00, '', '1784798420_SW304.jpg', '', '2026-07-14 09:34:55'),
(183, 'Shower Hinge - SW3049', 4, 14, 7, 'Model : VVP/SW3049/90/PS\r\nMaterial : Stainles Steel 304\r\nDouble Hinge (Glass to Glass) 8mm – 10mm only', 0.00, '', '1784798379_SW3049.jpg', '', '2026-07-14 09:34:55'),
(184, 'Shower Hinge - Y3151', 4, 14, 7, 'Shower Hinge 90 Wall to Glass\r\n\r\nModel : JJ/GF/Y3151/PM\r\n\r\nMaterial : Stainless Steel 304\r\n\r\nSurface Finish : Mirror', 0.00, '', '1784798341_Y3151.jpg', '', '2026-07-14 09:34:55'),
(185, 'Shower Hinge - Y3152', 4, 14, 7, 'Model : JJ/GF/HI/Y3152/PM\r\n\r\nMaterial : Stainless Steel 304\r\n\r\nSurface Finish : Mirror', 0.00, '', '1784798297_Y3152.jpg', '', '2026-07-14 09:34:55'),
(186, 'Handle Hinge - FXZ12', 4, 14, 8, 'Model : KLN/HI/H/FXZ12/BL\r\n\r\nColour : Black', 0.00, '', '1784798266_fxz12.jpg', '', '2026-07-14 09:34:55'),
(187, 'Pivot Hinge - FXZ11', 4, 14, 8, 'Model : KLN/HI/PVT/FXZ11\r\n\r\nColours : Black , White , Silver', 0.00, '', '1784794903_fxz11.jpg', '', '2026-07-14 09:34:55'),
(188, 'Pivot Hinge - FXZ1B', 4, 14, 8, 'Model : KLN/HI/PVT/FXZ1B\r\n\r\nColours : Black , White', 0.00, '', '1784794873_fxz1b.jpg', '', '2026-07-14 09:34:55'),
(189, 'Pivot Hinge - FXZ5B', 4, 14, 8, 'Model : KLN/HI/PVT/FXZ5B\r\n\r\nColours : Black , White ,  Silver', 0.00, '', '1784794847_FXZ5B.jpg', '', '2026-07-14 09:34:55'),
(190, 'Door Hinge - LDL', 4, 14, 9, 'Model : HI#PS022 / HI#PS021\r\n\r\nMaterial : HI#PS022 – 304\r\n\r\nHI#PS021 – 202\r\n\r\nSizes :  3*2 / 4*2 / 4*3 / 4*4 / 5*3 / 5*4\r\n\r\nThickness : 2mm / 2.5mm / 3mm', 0.00, '', '1784794816_LDL.jpg', '', '2026-07-14 09:34:55'),
(191, 'Door Hinge - MJ061', 4, 14, 9, 'Model : KLN/HI/SS/MJ061\r\nMaterial : SS304\r\nSurface Finish : QGT, PVD, & SATIN\r\nSize : 5″x3″x3/4BB\r\nLoad bearing of three hinges 40-60kg', 0.00, '', '1784794781_061.jpg', '', '2026-07-14 09:34:55'),
(192, 'Door Hinge - MJ176', 4, 14, 9, 'Model : KLN/HI/SS/MJ176\r\nMaterial : SS304\r\nSurface Finish QGT, PVD, & SATIN\r\nSize 4″x3″x3/4BB\r\nLoad bearing of three hinges 40-60kg', 0.00, '', '1784794748_176.jpg', '', '2026-07-14 09:34:55'),
(193, 'Door Hinge - MJ022', 4, 14, 9, 'Model : KLN/HI/SS/MJ022/SN\r\nMaterial : SS304\r\nSurface Finish : QGT, PVD, & SATIN\r\nSize : 5″x4″x3″/4BB\r\nLoad bearing of three hinges 40-60kg', 0.00, '', '1784794717_022.jpg', '', '2026-07-14 09:34:55'),
(194, 'Door Hinges - MJ041', 4, 14, 9, 'Model :KLN/HI/SS/MJ041/SN\r\nMaterial : SS304Surface\r\nFinish QGT, PVD, & SATIN\r\nSize 4″x3″x2.5/2BB\r\nLoad bearing of three hinges 30-40kg', 0.00, '', '1784794689_041.jpg', '', '2026-07-14 09:34:55'),
(195, 'Stainless Steel Bearing Hinges', 4, 14, 9, 'Material – Stainless Steel 304\r\n\r\nAvailable Sizes – 4 x 3 x 2.0MM / 4 x 3 x 2.5MM / 4x3x3MMx2BB / 4x3x3MMx4BB / 4x3x3.0MM 2BB / 5x3X3MM\r\n\r\nColor – Natural\r\n\r\nFinish Type – Brushed\r\n\r\nPlug Profile – Door Mount\r\n\r\nCertificate – GMC,CE,ISO9001/2008\r\n\r\nProcess – Stamping + Assembling', 0.00, '', '1784794661_ss (1).jpg', '', '2026-07-14 09:34:55'),
(196, 'Stainless Steel Flag Hinges', 4, 14, 9, 'useful on doors that need removing frequently.\r\n\r\nModel – 3H/HI/FLG4*3.5*2SS\r\n\r\nMaterial – Stainless Steel 304\r\n\r\nAvailable Sizes – 4*3.5*2SS\r\n\r\nFinish Type – Brushed\r\n\r\nPlug Profile – Door Mount', 0.00, '', '1784794629_3-4.jpg', '', '2026-07-14 09:34:55'),
(197, 'Stainless Steel Removable Hinges', 4, 14, 9, 'Useful on doors that need removing frequently.\r\n\r\nModel – 3H/HI/SS4*3*2RMV\r\n\r\nMaterial – Stainless Steel 304\r\n\r\nAvailable Sizes – 4*3*2MM\r\n\r\nFinish Type – Brushed\r\n\r\nPlug Profile – Door Mount', 0.00, '', '1784794597_2-2.jpg', '', '2026-07-14 09:34:55'),
(198, 'Clamp - WB1112', 4, 15, NULL, 'Model : KLN/GF/CLMP/WB1112\r\n\r\nMaterial :  Stainless Steel\r\n\r\n \r\n\r\nApplicable glass thickness: 8-12mm\r\nThe product has variety of the installation types and diverse styles\r\nFinish: Satin or Mirror\r\nThe surface could do other color by PVD plating', 0.00, '', '1784794568_wb1102.jpg', '', '2026-07-14 09:34:55'),
(199, 'Limiter Stay - FZ024', 4, 15, NULL, 'Model : 3H/CS/FZ024', 0.00, '', '1784794517_FZ024.jpg', '1784794517_hover_FZ024-1.jpg', '2026-07-14 09:34:55'),
(200, 'Drawer Handle - 2054', 4, 16, 10, 'Model – H/DRW/GL2054\r\n\r\nMaterial –  Stainless Steel\r\n\r\nAvailable Colour – Matt Black\r\n\r\nAvailable Sizes – (MM)\r\n\r\nBlack –96 / 128 /  224', 0.00, '', '1784794461_GL2054.jpg', '', '2026-07-14 09:34:55'),
(201, 'Drawer Handle - 2062', 4, 16, 10, 'Model – H/DRW/GL2062\r\n\r\nMaterial –  Stainless Steel\r\n\r\nAvailable Colour – Matt Black / Hairline\r\n\r\nAvailable Sizes – (MM)\r\n\r\nBlack – 96 / 160 / 224 / 320\r\n\r\nHairline(HL) – 96 / 128 / 192\r\n\r\n', 0.00, '', '1784794431_2062.jpg', '', '2026-07-14 09:34:55'),
(202, 'Drawer Handle - 616', 4, 16, 10, 'Model : H/GP/616\r\n\r\nMaterial : Stainless Steel\r\n\r\nAvailable Colour – Gold\r\n\r\nAvailable Sizes – Small', 0.00, '', '1784794392_616.jpg', '', '2026-07-14 09:34:55'),
(203, 'Drawer Handle - 617', 4, 16, 10, 'Model : H/SS/617\r\n\r\nMaterial : Stainless Steel\r\n\r\nAvailable Colour – Hairline\r\n\r\nAvailable Sizes – Small & Large', 0.00, '', '1784794360_617.jpg', '', '2026-07-14 09:34:55'),
(204, 'Drawer Handle - 9205', 4, 16, 10, 'Model – H/DRW/09205\r\n\r\nMaterial –  Stainless Steel\r\n\r\nAvailable Colour – Hairline\r\n\r\nAvailable Sizes – (MM)\r\n\r\nHairline(HL) – 128 / 160 / 192 / 224', 0.00, '', '1784794331_eewff.jpg', '1784794331_hover_HTB1wzK4UrPpK1RjSZFFq6y5PpXae-660x800.jpg', '2026-07-14 09:34:55'),
(205, 'Drawer Handle - GL2204', 4, 16, 10, 'Model – H/DRW/GL2204\r\n\r\nMaterial –  Aluminium\r\n\r\nAvailable Colour – Silver\r\n\r\nAvailable Sizes – (MM) –  96 / 128 / 160', 0.00, '', '1784794273_2204.jpg', '', '2026-07-14 09:34:55'),
(206, 'Drawer Handle - GL2211', 4, 16, 10, 'Model – H/DRW/GL2211\r\n\r\nMaterial –  Stainless Steel\r\n\r\nAvailable Colour – Hairline and Black\r\n\r\nAvailable Sizes – (MM) –\r\n\r\nBlack – 96 / 128 / 160  192 / 224 / 288\r\n\r\nHairline – 128', 0.00, '', '1784794244_2211.jpg', '', '2026-07-14 09:34:55'),
(207, 'Drawer Handle - GL2219', 4, 16, 10, 'Model : H/DRW/GL2219\r\n\r\nMaterial – Aluminium\r\n\r\nAvailable Colour – Silver\r\n\r\nAvailable Sizes – (MM) – 96 / 128', 0.00, '', '1784794207_2219.jpg', '', '2026-07-14 09:34:55'),
(208, 'Drawer Handle - H8310', 4, 16, 10, 'Model : H/DRW/H8310\r\n\r\nMaterial – Stainless Steel\r\n\r\nAvailable Colour – Hairline\r\n\r\nAvailable Sizes – (MM) 128 / 160 / 192 / 224', 0.00, '', '1784794169_8310.jpg', '', '2026-07-14 09:34:55'),
(209, 'Drawer Handle - KY209', 4, 16, 10, 'Model – H/DRW/KY209\r\n\r\nMaterial –  Stainless Steel\r\n\r\nAvailable Colour – Hairline\r\n\r\nAvailable Sizes – (MM) – 96 / 128 / 160', 0.00, '', '1784794135_2-7.jpg', '', '2026-07-14 09:34:55'),
(210, 'Drawer Handle - KY917', 4, 16, 10, 'Model : H/DRW/KY917\r\n\r\nMaterial : Stainless Steel\r\n\r\nAvailable Colour – Black\r\n\r\nAvailable Sizes – (MM) 96 / 128', 0.00, '', '1784794103_1-11.jpg', '', '2026-07-14 09:34:55'),
(211, 'Drawer Handle - KY919', 4, 16, 10, 'Model : H/DRW/KY919\r\n\r\nMaterial : Stainless Steel\r\n\r\nAvailable Colour – Black\r\n\r\nAvailable Sizes – (MM) 64/ 96 /128', 0.00, '', '1784794074_KY-919.jpg', '', '2026-07-14 09:34:55'),
(212, 'Drawer Handle - LY8509', 4, 16, 10, 'Model : H/DRW/LY8509\r\n\r\nMaterial : Aluminium\r\n\r\nAvailable Colour – Silver\r\n\r\nAvailable Sizes – (MM) 96 /128', 0.00, '', '1784794043_8509.jpg', '', '2026-07-14 09:34:55'),
(213, 'Drawer Handle - LY8526', 4, 16, 10, 'Model : H/DRW/LY8526\r\n\r\nMaterial – Aluminium\r\n\r\nAvailable Colour – Silver\r\n\r\nAvailable Sizes – (MM) – 96 / 128 / 160', 0.00, '', '1784794013_8526.jpg', '', '2026-07-14 09:34:55'),
(214, 'Drawer Handle - W8107', 4, 16, 10, 'Model : H/DRW/W8107/17\r\n\r\nMaterial : Stainless Steel\r\n\r\nAvailable Colour – Hairline', 0.00, '', '1784793983_8107.jpg', '', '2026-07-14 09:34:55'),
(215, 'Drawer Handle - 8229', 4, 16, 10, 'Model – H/DRW/H8229\r\n\r\nMaterial –  Stainless Steel\r\n\r\nAvailable Colour – Hairline\r\n\r\nAvailable Sizes – (MM)\r\n\r\nHairline(HL) – 96 / 128 /160 /192', 0.00, '', '1784793952_h8229.jpg', '', '2026-07-14 09:34:55'),
(216, 'Drawer Handle - 868', 4, 16, 10, 'Model – H/DRW/868\r\n\r\nMaterial –  Stainless Steel\r\n\r\nAvailable Colour – Hairline\r\n\r\nAvailable Sizes – (MM)\r\n\r\nHairline(HL) – 96 / 128', 0.00, '', '1784793921_868.jpg', '', '2026-07-14 09:34:55'),
(217, 'Drawer Handle - XJL022', 4, 16, 10, 'Model –H/DRW/XJL022\r\n\r\nMaterial –  Stainless Steel\r\n\r\nAvailable Colour – Antique Green\r\n\r\nAvailable Sizes – (MM)\r\n\r\nHairline(HL) – 64 / 128', 0.00, '', '1784793886_xjl022.jpg', '', '2026-07-14 09:34:55'),
(218, 'Drawer Handle - XJL110', 4, 16, 10, 'Model – H/DRW/XJL110\r\n\r\nMaterial –  Stainless Steel\r\n\r\nAvailable Colour – Hairline\r\n\r\nAvailable Sizes – (MM) – 64 / 96 / 128 / 160 / 192 / 224 / 256', 0.00, '', '1784793854_XJL110.jpg', '', '2026-07-14 09:34:55'),
(219, 'Drawer Handle - 8753', 4, 16, 10, 'Model – H/DRW/H8753\r\n\r\nMaterial –  Stainless Steel\r\n\r\nAvailable Colour – Mirror Finish\r\n\r\nAvailable Sizes – (MM)\r\n\r\nSilver – 64 / 96 / 128 / 160', 0.00, '', '1784793824_8753.jpg', '', '2026-07-14 09:34:55'),
(220, 'Drawer Handles - 080C', 4, 16, 10, 'Model – H/DRW/080C/256\r\n\r\nMaterial –  Stainless Steel\r\n\r\nAvailable Colour – Black & Wooden\r\n\r\nAvailable Sizes – 256MM', 0.00, '', '1784793788_080BL.jpg', '1784793788_hover_080WC.jpg', '2026-07-14 09:34:55'),
(221, 'Drawer Handles - 090202', 4, 16, 10, 'Model : H/DRW/09202\r\n\r\nMaterial – Stainless Steel\r\n\r\nAvailable Colour – Hairline\r\n\r\nAvailable Sizes – (MM) – 96 / 128 / 160', 0.00, '', '1784793739_9202.jpg', '', '2026-07-14 09:34:55'),
(222, 'Drawer Handles - 1072', 4, 16, 10, 'Model – H/DRW/1072\r\n\r\nMaterial –  Stainless Steel\r\n\r\nAvailable Colour – Hairline\r\n\r\nAvailable Sizes – (MM)\r\n\r\nHairline(HL) – 96 / 128', 0.00, '', '1784793679_1072.jpg', '', '2026-07-14 09:34:55'),
(223, 'Drawer Handles - 209', 4, 16, 10, 'Model – H/DRW/209\r\n\r\nMaterial –  Stainless Steel\r\n\r\nAvailable Colour – Black & Hairline\r\n\r\nAvailable Sizes – (MM)\r\n\r\nBlack : 64 / 96 / 128 / 160 / 192 / 224\r\n\r\nHairline(HL) – 64 / 96 / 128 / 160 / 192 / 224', 0.00, '', '1784793645_209.jpg', '', '2026-07-14 09:34:55'),
(224, 'Drawer Handles - 613', 4, 16, 10, 'Model : H/GP/613\r\n\r\nMaterial : Stainless Steel\r\n\r\nAvailable Colour – Gold\r\n\r\nAvailable Sizes – Large & Small', 0.00, '', '1784793611_613.jpg', '', '2026-07-14 09:34:55'),
(225, 'Drawer Handles - 618', 4, 16, 10, 'Model : H/GP/618/KG\r\n\r\nMaterial – Stainless Steel\r\n\r\nAvailable Colour – Gold', 0.00, '', '1784793578_618.jpg', '', '2026-07-14 09:34:55'),
(226, 'Drawer Handles - 816', 4, 16, 10, 'Model – H/DRW/JY816\r\n\r\nMaterial –  Stainless Steel\r\n\r\nAvailable Colour – Hairline\r\n\r\nAvailable Sizes – (MM)\r\n\r\nHairline(HL) – 96 / 128 / 160', 0.00, '', '1784793549_816.jpg', '', '2026-07-14 09:34:55'),
(227, 'Drawer Handles - 8537', 4, 16, 10, 'Model – H/DRW/LY8537\r\n\r\nMaterial –  Aluminium\r\n\r\nAvailable Colour – Silver\r\n\r\nAvailable Sizes – (MM)\r\n\r\nSilver – 96 / 128 / 160 / 224', 0.00, '', '1784793511_LY8537.jpg', '', '2026-07-14 09:34:55'),
(228, 'Drawer Handles - HF40*70', 4, 16, 10, 'Model : H/DRW/HF40*70SS\r\n\r\nMaterial : Stainless Steel\r\n\r\nAvailable Colour – Hairline\r\n\r\nAvailable Sizes – (MM) 40*70', 0.00, '', '1784793477_40-70.jpg', '', '2026-07-14 09:34:55'),
(229, 'Drawer Handles - KY923', 4, 16, 10, 'Model : H/DRW/KY923\r\n\r\nMaterial : Stainless Steel\r\n\r\nAvailable Colour – Black\r\n\r\nAvailable Sizes – (MM) 96 /128', 0.00, '', '1784793448_KY923.jpg', '', '2026-07-14 09:34:55'),
(230, 'Drawer Handles - XJL021', 4, 16, 10, 'Model –H/DRW/XJL021\r\n\r\nMaterial –  Stainless Steel\r\n\r\nAvailable Colour – Antique Green\r\n\r\nAvailable Sizes – (MM)\r\n\r\nHairline(HL) – 96 / 128', 0.00, '', '1784793418_XJL021.jpg', '', '2026-07-14 09:34:55'),
(231, 'Drawer Handles - YS037', 4, 16, 10, 'Model – H/DRW/YS037\r\n\r\nMaterial –  Stainless Steel\r\n\r\nAvailable Colour – Matt Black & Hairline\r\n\r\nAvailable Sizes – (MM)\r\n\r\nBlack :  128 / 160 / 192 / 224\r\n\r\nHairline(HL) – 128 / 160 / 192 / 224 / 288', 0.00, '', '1784793383_ys037.jpg', '1784793383_hover_AGSG.jpg', '2026-07-14 09:34:55'),
(232, 'Drawer Handles - 2032', 4, 16, 10, 'Model – H/DRW/2032SS\r\n\r\nMaterial –  Stainless Steel\r\n\r\nAvailable Colour – Hairline\r\n\r\nAvailable Sizes – (MM)\r\n\r\nHairline(HL) – 150 / 200 / 250', 0.00, '', '1784793326_2032.jpg', '1784793326_hover_rwrww.jpg', '2026-07-14 09:34:55'),
(233, 'Patch Fitting - FL50', 4, 17, NULL, 'FAT FIT LOCK\r\n\r\nModel : VVP/FL 50 PS\r\nMaterial : Stainless Steel 304', 0.00, '', '1784793263_FL50.jpg', '', '2026-07-14 09:34:55'),
(234, 'Patch Fitting - FT10', 4, 17, NULL, 'BOTTOM GLASS FITTING\r\n\r\nModel : VVP/FT 10 PS\r\nMaterial : Stainless Steel 304', 0.00, '', '1784793220_FL10.jpg', '', '2026-07-14 09:34:55'),
(235, 'Patch Fitting - FT20', 4, 17, NULL, 'TOP GLASS FITTING\r\n\r\nModel : VVP/FT 20 PS\r\nMaterial : Stainless Steel 304', 0.00, '', '1784793188_FL20.jpg', '', '2026-07-14 09:34:55'),
(236, 'Patch Fitting - FT30', 4, 17, NULL, 'OVER – PANEL FITTING\r\n\r\nModel : VVP/FT 30 PS\r\nMaterial : Stainless Steel 304', 0.00, '', '1784793153_FL30.jpg', '', '2026-07-14 09:34:55'),
(237, 'Patch Fitting - FT40', 4, 17, NULL, 'SIDE PANEL TO OVER PANEL FITTING\r\n\r\nModel : VVP/FT 40 PS\r\nMaterial : Stainless Steel 304', 0.00, '', '1784793118_FL40.jpg', '', '2026-07-14 09:34:55'),
(238, 'Patch Fitting - FT41', 4, 17, NULL, 'SIDE PANEL TO OVER PANEL FITTING\r\n\r\nModel : VVP/FT 41PS\r\nMaterial : Stainless Steel 304', 0.00, '', '1784793081_FL41.jpg', '', '2026-07-14 09:34:55'),
(239, 'Patch Fitting - M201', 4, 17, NULL, '<strong>Upper Patch fitting</strong>\r\nModel : KLN/GF/PT/M201\r\nMain Material : Main body- High strength die-casting aluminium alloy\r\nDecorative cover : SS304/SS316\r\nSurface Finish : Hairline\r\nMaximum Bearing : 100kg\r\nApplication Scope : glass thickness is 10-12mm\r\nDoor leaf dimension : Door height≤2,600mm,Door width≤1,000mm', 0.00, '', '1784793048_333.jpg', '1784793048_hover_2-9.jpg', '2026-07-14 09:34:55'),
(240, 'Patch Fitting - M301', 4, 17, NULL, '<strong>Top Patch fitting</strong>\r\nModel: KLN/GF/PT/M301HL\r\nMain Material Main body- High strength die-casting aluminium alloy\r\nDecorative cover SS304/SS316\r\nSurface Finish Mirror /Satin\r\nMaximum Bearing 100kg\r\nApplication Scope glass thickness is 10-12mm\r\nDoor leaf dimension Door height≤2,600mm,Door width≤1,000mm', 0.00, '', '1784792970_jo.jpg', '1784792970_hover_2-10.jpg', '2026-07-14 09:34:55'),
(241, 'Patch Fitting - M401', 4, 17, NULL, '<strong>Bending Patch fitting</strong>\r\nModel: KLN/GF/PT/M401HL\r\nMain Material Main body- High strength die-casting aluminium alloy\r\nDecorative cover SS304/SS316\r\nSurface Finish Mirror /Satin\r\nMaximum Bearing 100kg\r\nApplication Scope glass thickness is 10-12mm\r\nDoor leaf dimension Door height≤2,600mm,Door width≤1,000mm', 0.00, '', '1784792887_1-14.jpg', '1784792887_hover_2-11.jpg', '2026-07-14 09:34:55'),
(242, 'Patch Fitting - PF-10', 4, 17, NULL, 'Model:PF-10-SS\r\nUsage: Door\r\nColor: SS\r\nMaterial: Stainless steel 304\r\nSuitable for glass 8mm—12mm\r\nProcess: Die casting + Machining + Surface Treatment\r\nSurface Treatment: Powder coating + Brushed Stainless Steel + Electroplating', 0.00, '', '1784792801_10 (1).jpg', '1784792801_hover_patch-fittings-pf-10-ss-02.jpg', '2026-07-14 09:34:55'),
(243, 'Patch Fitting - S101', 4, 17, NULL, 'Model : KLN/L/GLD/S101\r\n\r\nMaterial :  Stainless Steel 316', 0.00, '', '1784792735_s101.jpg', '', '2026-07-14 09:34:55'),
(244, 'Patch Fitting - PF-20', 4, 17, NULL, 'Model:PF-20-SS\r\nUsage: Door\r\nColor: SS\r\nMaterial: Stainless steel 304\r\nSuitable for glass 8mm—12mm\r\nProcess: Die casting + Machining + Surface Treatment\r\nSurface Treatment: Powder coating + Brushed Stainless Steel + Electroplating', 0.00, '', '1784792701_20 (1).jpg', '1784792701_hover_patch-fittings-pf-20-ss-02.jpg', '2026-07-14 09:34:55'),
(245, 'Patch Fitting - M101', 4, 17, NULL, '<strong>Bottom Patch fittings</strong>\r\nModel : KLN/GF/PT/M101\r\nMain Material : Main body- High strength die-casting aluminium alloy\r\nDecorative cover : SS304\r\nSurface Finish : Hairline\r\nMaximum Bearing :100kg\r\nApplication Scope : glass thickness is 10-12mm\r\nDoor leaf dimension : Door height≤2,600mm,Door width≤1,000mm', 0.00, '', '1784792638_1-12.jpg', '1784792638_hover_2-8.jpg', '2026-07-14 09:34:55'),
(246, 'Patch Fitting - PF-30', 4, 17, NULL, 'Model:PF-30-SS\r\nUsage: Door\r\nColor: SS\r\nMaterial: Stainless steel 304\r\nSuitable for glass 8mm—12mm\r\nProcess: Die casting + Machining + Surface Treatment\r\nSurface Treatment: Powder coating + Brushed Stainless Steel + Electroplating', 0.00, '', '1784792569_30.jpg', '1784792569_hover_30-1.jpg', '2026-07-14 09:34:55'),
(247, 'Shower Fittings - CG201', 4, 18, NULL, 'Model : KLN/GF/CG201/PM\r\n\r\nMaterial : Stainless Steel\r\n\r\nSurface Finish : Mirror Finish\r\n\r\n \r\n\r\n1. Applicable pipe diameter : φ19mm, φ25.4mm\r\n\r\n2. Applicable glass thickness: 8-12mm\r\n\r\n3. The product is easy to install, and its glass clip angle can be adjusted', 0.00, '', '1784792521_CG201.jpg', '', '2026-07-14 09:34:55'),
(248, 'Shower Fittings - CG202', 4, 18, NULL, 'Model : KLN/GF/CG202/PM\r\n\r\nMaterial : Stainless Steel\r\n\r\nSurface Finish : Mirror Finish\r\n\r\n1. Applicable pipe diameter : φ19mm, φ25.4mm\r\n\r\n2. Applicable glass thickness: 8-12mm\r\n\r\n3. The product is easy to install, and its glass clip angle can be adjusted', 0.00, '', '1784792490_CG202.jpg', '', '2026-07-14 09:34:55'),
(249, 'Shower Fittings - CG203', 4, 18, NULL, 'Model : KLN/GF/CG202/PM\r\n\r\nMaterial : Stainless Steel\r\n\r\nSurface Finish : Mirror Finish\r\n\r\n1. Applicable pipe diameter : φ19mm, φ25.4mm\r\n\r\n2. Applicable glass thickness: 8-12mm\r\n\r\n3. The product is easy to install, and its glass clip angle can be adjusted', 0.00, '', '1784792453_CG203.jpg', '', '2026-07-14 09:34:55'),
(250, 'Routel - TF-31', 4, 19, NULL, 'Model : KLN/GF/TF31/PM\r\n\r\nMataerial : Stainless Steel 316\r\n\r\nSurface Finish :  Mirror', 0.00, '', '1784792418_TF-31.jpg', '', '2026-07-14 09:34:55'),
(251, 'Spider - SD111', 4, 19, NULL, 'Model : VVP/SD 111/RH04/ PM\r\nMaterial : Stainless Steel 304\r\nSurface Finish : Mirror', 0.00, '', '1784792377_SD111-1.jpg', '', '2026-07-14 09:34:55'),
(252, 'Spider - SD444', 4, 19, NULL, 'Model :VVP/SD444/RH04 PS\r\nMaterial : Stainless Steel 304\r\nSurface Finish : Mirror', 0.00, '', '1784792347_SD444.jpg', '', '2026-07-14 09:34:55'),
(253, 'Spider - SD666', 4, 19, NULL, 'Model : VVP/SD666/RH04/PM\r\nMaterial : Stainless Steel 304\r\nSurface Finish : Mirror', 0.00, '', '1784792317_SD666.jpg', '', '2026-07-14 09:34:55'),
(254, 'Spider - SDC202', 4, 19, NULL, 'Model :VVP/SDC202/RH04 PS\r\nMaterial : Stainless Steel 304\r\nSurface Finish : Mirror', 0.00, '', '1784792281_SDC202.jpg', '', '2026-07-14 09:34:55'),
(255, 'Spider - SDC222', 4, 19, NULL, 'Model :VVP/SDC222/RH04 PM\r\nMaterial : Stainless Steel 304\r\nSurface Finish : Mirror', 0.00, '', '1784792248_SD222.jpg', '', '2026-07-14 09:34:55'),
(256, 'Spider - SDC404', 4, 19, NULL, 'Model :VVP/SDC404/FH03\r\nMaterial : Stainless Steel 304\r\nSurface Finish : Mirror', 0.00, '', '1784792216_SD404.jpg', '', '2026-07-14 09:34:55'),
(257, 'Spider Fittings - 200A21', 4, 19, NULL, 'Model : KLN/GF/200A21/PM\r\n\r\nMaterial : Stainless Steel 304\r\n\r\nSurface Finish : Mirror\r\n\r\nArms : 2 arms\r\n\r\nApply : Curtain Wall Glass', 0.00, '', '1784792182_200A21.jpg', '', '2026-07-14 09:34:55'),
(258, 'Spider Fittings - 200A4', 4, 19, NULL, 'Model : KLN/GF/200A4/PM\r\n\r\nMaterial : Stainless Steel 304\r\n\r\nSurface Finish : Mirror\r\n\r\nArms : 4 arms\r\n\r\nApply : Curtain Wall Glass', 0.00, '', '1784792144_200A4.jpg', '', '2026-07-14 09:34:55'),
(259, 'Dust Cap - DP-002', 4, 20, NULL, 'Floor mortise socket with automatic dust cover\r\n\r\nModel :3H/DPS/DP-002\r\n\r\nMaterial – Stainless Steel 304', 0.00, '', '1784792114_DP-002.jpg', '1784792114_hover_DP-002-1.jpg', '2026-07-14 09:34:55'),
(260, 'Tower Bolt - SS304', 4, 20, NULL, 'Material : SS304 /SS202\r\nAvailable Size : 12″ , 16″ , 18″ , 24″ , 32″ , 40″\r\nColor :  SS', 0.00, '', '1784792050_SS.jpg', '', '2026-07-14 09:34:55'),
(261, 'Tower Bolt H - Aluminium', 4, 20, NULL, 'Material : ALUMINIUM POWDER COATED\r\nAvailable Size : 2″ , 3″, 4″  , 6″ , 8″ , 10″  , 12″ , 18″ , 24″ , 30″\r\nColor : BLACK , WHITE , NATURAL ', 0.00, '', '1784791998_H.jpg', '', '2026-07-14 09:34:55'),
(262, 'Tower Bolt HHQ - Aluminium', 4, 20, NULL, 'Material : ALUMINIUM POWDER COATED\r\nAvailable Size : 2″ , 3″, 4″  , 6″ , 8″ , 10″  , 12″ , 18″ , 24″ , 30″\r\nColor : BLACK , WHITE , NATURAL ', 0.00, '', '1784791956_HHQ.jpg', '', '2026-07-14 09:34:55'),
(263, 'Transmission Rod - CHJ01B', 4, 21, NULL, '<strong>Multi-point Transmission Device</strong>\r\nModel:CHJ01B\r\nType: Multi-point Transmission Device\r\nUsage: Window & Door\r\nMaterial: Aluminum alloy\r\nColor: Sliver- white\r\nLength:1200, 1400 ,1800\r\nSurface Treatment: Oxidating', 0.00, '', '1784791913_chj01b-1.jpg', '', '2026-07-14 09:34:55'),
(264, 'Transmission Rod - CHJ03B', 4, 21, NULL, '<strong>Multi-point Transmission Device</strong>\r\nModel:CHJ03B\r\nType: Multi-point Transmission Device\r\nUsage: Window & Door\r\nMaterial: Aluminum alloy\r\nColor: Sliver- white oxidation\r\nLength: 1200, 1400\r\nSurface Treatment: Oxidating', 0.00, '', '1784791832_chj03b-1.jpg', '1784791832_hover_chj03b-2.jpg', '2026-07-14 09:34:55'),
(265, 'Alu Handle - 3HE001', 5, 22, NULL, 'Model : H/ALU/3HE001\r\n\r\nMaterial : Aluminium Alloy\r\n\r\nColours : Black, White, Silver', 0.00, '', '1784791746_3HE001.jpg', '', '2026-07-14 09:49:51'),
(266, 'Aluminium Handle - 048', 5, 22, NULL, 'Model : H/048/8″\r\n\r\nMaterial : Aluminium\r\n\r\nSurface finish : Powder Coated\r\n\r\nColours : BLACK/WHITE/SILVER', 0.00, '', '1784791712_048.jpg', '', '2026-07-14 09:49:51'),
(267, 'Aluminium Sliding Door Lock - 3HB03', 5, 22, NULL, 'Material – ALUMINIUM\r\n\r\nAvailable Colours – BLACK / WHITE / SILVER', 0.00, '', '1784790383_3.jpg', '', '2026-07-14 09:49:51'),
(268, 'Aluminium Sliding Door Lock - 3HB03A', 5, 22, NULL, 'Material – ALUMINIUM\r\n\r\nAvailable Colours – BLACK / WHITE / SILVER', 0.00, '', '1784790348_3A.jpg', '', '2026-07-14 09:49:51'),
(269, 'Aluminium Sliding Door Lock - 3HB05', 5, 22, NULL, 'Material – ALUMINIUM\r\n\r\nAvailable Colours – BLACK / WHITE / SILVER', 0.00, '', '1784790314_005.jpg', '', '2026-07-14 09:49:51'),
(270, 'Aluminium Sliding Door Lock - 3HB05A', 5, 22, NULL, 'Material – ALUMINIUM\r\n\r\nAvailable Colours – BLACK / WHITE / SILVER', 0.00, '', '1784790276_5A.jpg', '', '2026-07-14 09:49:51'),
(271, 'Aluminium Sliding Door Lock - 3HB06', 5, 22, NULL, 'Material – ALUMINIUM\r\n\r\nAvailable Colours – WHITE', 0.00, '', '1784790245_6.jpg', '', '2026-07-14 09:49:51'),
(272, 'Aluminium Sliding Door Lock - 3HB06A', 5, 22, NULL, 'Material – ALUMINIUM\r\n\r\nAvailable Colours – WHITE', 0.00, '', '1784790209_6A.jpg', '', '2026-07-14 09:49:51'),
(273, 'Aluminium Sliding Door Lock - 3HB07', 5, 22, NULL, 'Material – ALUMINIUM\r\n\r\nAvailable Colours – BLACK', 0.00, '', '1784790166_7.jpg', '', '2026-07-14 09:49:51'),
(274, 'Aluminium Sliding Door Lock - 3HB07A', 5, 22, NULL, 'Material – ALUMINIUM\r\n\r\nAvailable Colours – BLACK AND WHITE', 0.00, '', '1784790117_7A.jpg', '', '2026-07-14 09:49:51'),
(275, 'Aluminium Swing Door Lock - C002', 5, 22, NULL, 'Material – ALUMINIUM\r\n\r\nAvailable Colours – BLACK / WHITE', 0.00, '', '1784790082_002.jpg', '', '2026-07-14 09:49:51'),
(276, 'Aluminium Swing Door Lock - HXM007', 5, 22, NULL, 'Material – ALUMINIUM\r\n\r\nAvailable Colours – BLACK / WHITE', 0.00, '', '1784790046_007.jpg', '1784790046_hover_007-2.jpg', '2026-07-14 09:49:51'),
(277, 'Aluminium Swing Door Lock - TZ7030', 5, 22, NULL, 'Material – ALUMINIUM\r\n\r\nAvailable Colours – BLACK / WHITE / SILVER', 0.00, '', '1784789971_1-7.jpg', '1784789971_hover_2-5.jpg', '2026-07-14 09:49:51'),
(278, 'Casement Handle - 3HG002', 5, 22, NULL, 'Model : L/CAS/MON/3HG002SL\r\n\r\nMaterial : Zinc Alloy\r\n\r\nColour : Silver', 0.00, '', '1784789888_3HG002.jpg', '', '2026-07-14 09:49:51'),
(279, 'Casement Handle - 3HG003', 5, 22, NULL, 'Model : L/CAS/MON/3HG003\r\n\r\nMaterial : Zinc Alloy\r\n\r\nColours : White, Black, Silver', 0.00, '', '1784789859_3HG003.jpg', '', '2026-07-14 09:49:51'),
(280, 'Casement Handle - CZS70', 5, 22, NULL, 'Model : KLN/L/CAS/CZS70\r\nHandle with Hook\r\nAvaialble Colours : Silver , White\r\nCan Choose R/H or L/H', 0.00, '', '1784789824_CZS70.jpg', '', '2026-07-14 09:49:51'),
(281, 'Casement Handle - MQ03', 5, 22, NULL, 'Model : KLN/L/CAS/MQ03/SL\r\n\r\nMaterial : Zinc Alloy\r\n\r\nColour : Silver\r\n\r\n1.Brief Appearance\r\n\r\n2.Irrespective of right or left, apply to MSA, MSC two big series door lock .\r\n\r\n3.Insertion depth of square bar is 15-20mm.', 0.00, '', '1784789745_MQ03.jpg', '', '2026-07-14 09:49:51'),
(282, 'Casement Handle - T28C', 5, 22, NULL, 'Model : KLN/H/T28C\r\n\r\nMaterial : Zinc Alloy\r\n\r\nColour : Black & White\r\n\r\n1. More delicate appearance, pursue the best.\r\n\r\n2. A full range of products for customers to choose.\r\n\r\n3. More humanization design to meet our operation habit.', 0.00, '', '1784789713_13-1.jpg', '', '2026-07-14 09:49:51'),
(283, 'Casement Handles - ZY15', 5, 22, NULL, 'Aluminum Single Point Lock Casement Window Awning Window Handle\r\n\r\nModel:ZY15\r\nType: Door & Window Handles\r\nUsage: Door, Window\r\nMaterial: Alloy, Zinc alloy/ Aluminum\r\nColor: Black & White\r\nProcess: Die casting + machining + Powder Coating\r\nBe suitable for: Aluminum external/outward opening window door\r\nSurface Treatment: Powder Coating', 0.00, '', '1784789679_ZY15-1.jpg', '', '2026-07-14 09:49:51'),
(284, 'Casement Handles - ZY12', 5, 22, NULL, 'Aluminum Casement Window & Top-hung Window Handle\r\n\r\nModel:ZY12\r\nType: Window Handle\r\nUsage: Window\r\nMaterial: Alloy, Zinc Alloy, alloy/Aluminum\r\nColor: Black & White\r\nProcess: Die casting + Machining + Powder coating\r\nBe suitable for: Casement window, top-hung window whose diagonal is ≤700mm', 0.00, '', '1784789639_ZY12-1.jpg', '', '2026-07-14 09:49:51'),
(285, 'Crescent Lock - 3HI003', 5, 22, NULL, 'Model :  L/CRES/3H1003\r\n\r\nMaterial : Zinc Alloy\r\n\r\nColours : Black , White , Silver', 0.00, '', '1784789604_3H1003.jpg', '', '2026-07-14 09:49:51'),
(286, 'Crescent Lock - SB08', 5, 22, NULL, 'Model : 3H/L/CRE/SB08\r\n\r\nMaterial : Material: aluminum or zinc', 0.00, '', '1784789040_SB08.jpg', '1784789040_hover_SB08-1.jpg', '2026-07-14 09:49:51'),
(287, 'Crescent Lock - SB09', 5, 22, NULL, 'Model : 3H/L/CRE/SB09\r\n\r\nMaterial : Material: aluminum or zinc\r\n\r\nAvailable Colour : White', 0.00, '', '1784788984_SB09.jpg', '1784788984_hover_SB09-1.jpg', '2026-07-14 09:49:51'),
(288, 'Crescent Lock - 3HI1008', 5, 22, NULL, 'Crescent Lock  with Key\r\n\r\nModel : L/CRE/3H1008\r\n\r\nMaterial : Zinc Alloy\r\n\r\nColours : Black , White , Silver', 0.00, '', '1784788924_3H1008.jpg', '', '2026-07-14 09:49:51'),
(289, 'Crescent Lock - Y-04', 5, 22, NULL, 'The appearance combines mellow and angular to create special beauty, the spring restoration structure creates touch feedback. The crescent lock is made of high quality aluminum alloy die casting and stainless steel 304 punching.\r\n\r\nModel : KLN/L/CRE/YO4\r\n\r\nMaterial : Zinc Alloy\r\n\r\nAvailable Colours : Black & Silver', 0.00, '', '1784788857_y04.jpg', '', '2026-07-14 09:49:51'),
(291, 'Door Handle - CZM02', 5, 22, NULL, 'Model : 3H/H/CZM02\r\n\r\nMaterial : Zinc Alloy\r\n\r\nAvailable Colours : Black & White', 0.00, '', '1784788734_fsf.jpg', '', '2026-07-14 09:49:51'),
(292, 'Door Handle - CZM08', 5, 22, NULL, 'Model : 3H/H/CZM08/WH\r\n\r\nAvailable Colours : White', 0.00, '', '1784788696_dsaf.jpg', '', '2026-07-14 09:49:51'),
(293, 'Door Handle - MZS20', 5, 22, NULL, 'Model : KLN/H/MZS20/SL\r\n\r\nMaterial :  Zinc Alloy\r\n\r\nColour : Natural\r\n\r\n \r\n\r\n1.Brief Appearance\r\n\r\n2.Irrespective of right or left, apply to MSA, MSC two big series door lock .\r\n\r\n3.Insertion depth of square bar is 15-20mm.', 0.00, '', '1784788664_eqrqafrw.jpg', '', '2026-07-14 09:49:51'),
(294, 'Lock Body - CDQ20', 5, 22, NULL, 'Model : KLN/L/BODY/CDQ20', 0.00, '', '1784788631_CDQ20.jpg', '1784788631_hover_CDQ20-2.jpg', '2026-07-14 09:49:51'),
(295, 'Lock Handle - CZH21', 5, 22, NULL, 'Model : 3H/H/MPL/CZH21\r\n\r\nMaterial : Zinc Alloy\r\n\r\nAvailable Colours : Black and White', 0.00, '', '1784788572_CZH21.jpg', '1784788572_hover_CZH21-1.jpg', '2026-07-14 09:49:51'),
(296, 'Sliding Lock - STG23A', 5, 22, NULL, 'Model – 3H/L/STG23A/BL\r\n\r\nMaterial – Alloy, Zinc Alloy, alloy/Aluminum\r\n\r\nColor – Black\r\n\r\nType – Sliding Latch Lock\r\n\r\nUsage – Door, Window\r\n\r\nCertificate – GMC,CE,ISO9001/2008\r\n\r\nProcess – Die casting + Machining + Powder Coating\r\n\r\nBe suitable for – sliding door, sliding window\r\n\r\nSurface Treatment – Powder coating', 0.00, '', '1784788512_stg23a.jpg', '1784788512_hover_2-3.jpg', '2026-07-14 09:49:51'),
(297, 'Sliding Door Handle - CZM06A', 5, 22, NULL, 'Model : 3H/LH/CZM06A/WH', 0.00, '', '1784788456_CZM06A.jpg', '', '2026-07-14 09:49:51'),
(298, 'Sliding Lock - STG13', 5, 22, NULL, 'Model – 33H/L/STG13\r\n\r\nMaterial – Alloy, Zinc Alloy, alloy/Aluminum\r\n\r\nColor – Black and White\r\n\r\nType – Sliding Latch Lock\r\n\r\nUsage – Door, Window\r\n\r\nCertificate – GMC,CE,ISO9001/2008\r\n\r\nProcess – Die casting + Machining + Powder Coating\r\n\r\nBe suitable for – sliding door, sliding window\r\n\r\nSurface Treatment – Powder coating', 0.00, '', '1784788422_1-6.jpg', '1784788422_hover_2-4.jpg', '2026-07-14 09:49:51'),
(299, 'Concealed Handles - FH030', 5, 23, NULL, 'Model : 3H/H/FH030/HL\r\n\r\nMaterial : Stainless Steel\r\n\r\nSize : 102*51MM', 0.00, '', '1784788365_FH-30.jpg', '1784788365_hover_FH-30-1.jpg', '2026-07-14 09:49:51'),
(300, 'Concealed Handles - FH032', 5, 23, NULL, 'Model : 3H/H/FH032/HL\r\n\r\nMaterial : Stainless Steel\r\n\r\nSize : 30*30MM', 0.00, '', '1784788311_FH-032-1.jpg', '', '2026-07-14 09:49:51'),
(301, 'Concealed Handles - FH007', 5, 23, NULL, 'Model:FH-007\r\n\r\nBrand Name: 3H\r\n\r\nType: Concealed Handle\r\n\r\nUsage: Sliding Door\r\n\r\nMaterial: Stainless Steel\r\n\r\nSize: 40mm\r\n\r\nColor: Natural\r\n\r\nCertificate: GMC,CE,ISO9001/2008\r\n\r\nProcess: Die casting + Surface treatment', 0.00, '', '1784788275_sf.jpg', '', '2026-07-14 09:49:51'),
(302, 'Glass Sliding Door Handle - PH202', 5, 23, NULL, 'Model : 3H/H/SS/PH202\r\n\r\nMaterial :Stainless Steel', 0.00, '', '1784788227_PH-202.jpg', '1784788227_hover_PH-202-1.jpg', '2026-07-14 09:49:51'),
(303, 'Glass Sliding Door Handle - PH204', 5, 23, NULL, 'Model : 3H/H/SS/PH204\r\n\r\nMaterial :Stainless Steel\r\n\r\n', 0.00, '', '1784787923_PH-204.jpg', '1784787923_hover_PH-204-1.jpg', '2026-07-14 09:49:51'),
(304, 'SS Door Handle - 8759', 5, 23, NULL, 'Material – Stainless Steel + Soli wood\r\nSurface Finish  – Mirror + wood [Ends are Mirror & middle is wood]\r\nAvailable Size – 45*25*650*800*1.0M\r\nShape – H Shape\r\nIdeal For – Exterior Door', 0.00, '', '1784787868_8759.jpg', '', '2026-07-14 09:49:51'),
(305, 'SS Door Handle - 8801', 5, 23, NULL, 'Material – Stainless Steel\r\nSurface Finish  – Mirror, Mirror + Hairline [Ends are Mirror (including supporter)+middle is Hairline\r\nAvailable Size – 38*900*1200 / 38*450*600 / 38*550*800\r\nShape – H Shape\r\nIdeal For – Exterior Door', 0.00, '', '1784787826_8801.jpg', '', '2026-07-14 09:49:51'),
(306, 'SS Door Handle - 8874', 5, 23, NULL, 'Model – YF/H/8874D\r\nMaterial – Stainless Steel 304\r\nSurface Finish  – Hairline\r\nAvailable Size – 20*300*320mm / 25*400*425mm', 0.00, '', '1784787797_yf-8874.jpg', '', '2026-07-14 09:49:51'),
(307, 'SS Door Handle - 8961', 5, 23, NULL, 'Model – YF/H/8961\r\nMaterial – Stainless Steel 304\r\nSurface Finish  – Mirror or Hairline\r\nAvailable Size – 25*275*300MM\r\nShape – Curved\r\nIdeal For – Exterior Door', 0.00, '', '1784787761_YF-8961.jpg', '', '2026-07-14 09:49:51');
INSERT INTO `products` (`id`, `name`, `category_id`, `sub_category_id`, `brand_id`, `description`, `price`, `short_desc`, `image_primary`, `image_secondary`, `created_at`) VALUES
(308, 'SS Door Handle - 8968', 5, 23, NULL, 'Model – YF/H/8968D\r\nMaterial – Stainless Steel 304\r\nSurface Finish  – Mirror\r\nAvailable Size – 25*275CC 425CC*1.0MM  /  25*275CC*475CC*1.0MM', 0.00, '', '1784787728_YF-8968.jpg', '', '2026-07-14 09:49:51'),
(309, 'SS Door Handle - 8974', 5, 23, NULL, 'Model – YF/H/8974D/475*25HL\r\nMaterial – Stainless Steel 304\r\nSurface Finish  – Mirror\r\nAvailable Size – 25*450*475MM', 0.00, '', '1784787696_YF-8974.jpg', '', '2026-07-14 09:49:51'),
(310, 'SS Door Handle - HD101', 5, 23, NULL, 'Model : VVP/HD101\r\n\r\nMaterial : 304\r\n\r\nSurface Finish : Mirror finish\r\n\r\nSizes : 25X300MM / 25X305MM / 32*450MM', 0.00, '', '1784787663_VVP-101-2.jpg', '1784787663_hover_VVP-HD101.jpg', '2026-07-14 09:49:51'),
(311, 'SS Door Handle - HD110', 5, 23, NULL, 'Model : VVP/HD110\r\n\r\nMaterial : 304\r\n\r\nSurface Finish : Mirror finish\r\n\r\nSizes : 25X305X450MM', 0.00, '', '1784787607_VVP-110.jpg', '1784787607_hover_VVP-110-2.jpg', '2026-07-14 09:49:51'),
(312, 'SS Door Handle - HD117', 5, 23, NULL, 'Model : VVP/HD117\r\n\r\nMaterial : 304\r\n\r\nSurface Finish : Mirror finish\r\n\r\nSizes : 25X305MM  /  25X450MM', 0.00, '', '1784787550_VVP-HD117.jpg', '1784787550_hover_VVP-117-2.jpg', '2026-07-14 09:49:51'),
(313, 'SS Door Handle - HD134', 5, 23, NULL, 'Model : VVP/HD134\r\n\r\nMaterial : 304\r\n\r\nSurface Finish : Mirror finish\r\n\r\nSizes : 25X450MM / 25X305MM', 0.00, '', '1784787477_VVP134.jpg', '1784787477_hover_VVP-134-2.jpg', '2026-07-14 09:49:51'),
(314, 'SS Door Handle - 8964', 5, 23, NULL, 'Material – Stainless Steel 201 and 304\r\nSurface Finish  – Mirror and Hairline\r\nAvailable Size – 25x275x300x1.0mm\r\nIdeal For – Exterior Door', 0.00, '', '1784787417_8964.jpg', '', '2026-07-14 09:49:51'),
(315, 'SS Door Handle - 8966', 5, 23, NULL, 'Material – Stainless Steel  304\r\nSurface Finish  – Mirror\r\nAvailable Size – 25 x 275CC x 300 x 1.0MM\r\nIdeal For – Exterior Door', 0.00, '', '1784787378_8966.jpg', '', '2026-07-14 09:49:51'),
(316, 'Glass Lock - BS71', 5, 24, NULL, 'Glass to Wall Door Lock\r\n\r\nModel : KLN/L/GLD/BS71PM\r\n\r\nMaterial : Stainless Steel 304\r\n\r\nSurface Finish : Mirror\r\n\r\nGlass Thickness : 10~12mm\r\n\r\nA kind of glass door locker without glass drilling', 0.00, '', '1784787324_71.jpg', '1784787324_hover_bs71-1.jpg', '2026-07-14 09:49:51'),
(317, 'Glass Lock - BS72', 5, 24, NULL, 'Glass to Glass Door Lock\r\n\r\nModel : KLN/L/GLD/BS72\r\n\r\nMaterial : Stainless Steel 304\r\n\r\nSurface Finish : Mirror or Hairline\r\n\r\nGlass Thickness : 10~12mm\r\n\r\nA kind of glass door locker without glass drilling', 0.00, '', '1784787261_72.jpg', '1784787261_hover_bs72-1.jpg', '2026-07-14 09:49:51'),
(318, 'Glass Lock - BS74', 5, 24, NULL, 'Glass to Glass Door Lock\r\n\r\nModel : KLN/L/GLD/BS74HL\r\n\r\nMaterial : Stainless Steel 304\r\n\r\nSurface Finish : Hairline\r\n\r\nGlass Thickness : 10~12mm/15mm\r\n\r\nA kind of glass door locker without glass drilling', 0.00, '', '1784787177_BS74.jpg', '1784787177_hover_74.jpg', '2026-07-14 09:49:51'),
(319, 'Glass Lock - GL10SS', 5, 24, NULL, '<strong>Glass to Glass Door Lock</strong>\r\nModel : 3H/L/GLD/GL010SS/HL\r\n\r\nMaterial : Stainless Steel 304\r\n\r\nColour : Natural', 0.00, '', '1784787113_gl.jpg', '1784787113_hover_gdgg.jpg', '2026-07-14 09:49:51'),
(320, 'Glass Lock - YMS32', 5, 24, NULL, 'Model : KLN/GLD/YMS32/PM\r\n\r\nMaterial : Stainless Steel 304', 0.00, '', '1784786989_yms32.jpg', '', '2026-07-14 09:49:51'),
(321, 'Economic Type Bathroom Door', 6, NULL, NULL, 'Door Size : 27” x75”\r\nMaterial : Aluminium\r\nMore durable\r\nMore attractive and eye catching finishes\r\nNo damages with water compared to timber doors', 0.00, '', '1784786953_1-18.jpg', '', '2026-07-14 09:55:24'),
(322, 'Full Panel Bathroom Door', 6, NULL, NULL, 'Door Size :  27” x75”\r\nMaterial : Aluminium\r\nMore durable\r\nMore attractive and eye catching finishes\r\nNo damages with water compared to timber doors', 0.00, '', '1784786916_2-14.jpg', '', '2026-07-14 09:55:24'),
(323, 'General Type Bathroom Door', 6, NULL, NULL, 'Door Size :  27” x75”\r\nMaterial : Aluminium\r\nMore durable\r\nMore attractive and eye catching finishes\r\nNo damages with water compared to timber doors', 0.00, '', '1784786877_3-13.jpg', '', '2026-07-14 09:55:24'),
(324, 'Plywood Door', 6, NULL, NULL, 'Material : Plywood\r\n\r\nSize : 2055X838MM\r\n\r\nThickness : 1 1/5inch', 0.00, 'Get this Plywood  Door which comes in Standard Size. Perfect for your home.', '1784786814_1 (1).jpg', '', '2026-07-14 09:55:24'),
(325, 'Expanded Metal Mesh - 602', 7, NULL, NULL, 'Expanded metal is a type of sheet metal which has been cut and stretched to form a regular pattern (often diamond-shaped) of metal mesh-like material. It is commonly used for fences and grates, and as metallic lath to support plaster or stucco.\r\n\r\nModel – EXM/852520/602/12X32\r\n\r\nMaterial – Aluminium\r\n\r\nAvailable Size – 1220X3200\r\n\r\nThickness – 2MM\r\n\r\nColour – Flat White', 0.00, 'Expanded Metal is a popular material for todays building and industrial design', '1784786757_1-5.jpg', '', '2026-07-14 09:58:41'),
(326, 'Expanded Metal Mesh - 621', 7, NULL, NULL, 'Expanded metal is a type of sheet metal which has been cut and stretched to form a regular pattern (often diamond-shaped) of metal mesh-like material. It is commonly used for fences and grates, and as metallic lath to support plaster or stucco.\r\n\r\nModel – EXM/853030/621/12X32\r\n\r\nMaterial – Aluminium\r\n\r\nAvailable Size – 1220X3200\r\n\r\nThickness – 2MM\r\n\r\nColour – Black / White', 0.00, 'Expanded Metal is a popular material for todays building and industrial design.', '1784786722_3-1.jpg', '', '2026-07-14 09:58:41'),
(327, 'Expanded Metal Mesh - 626', 7, NULL, NULL, 'Expanded metal is a type of sheet metal which has been cut and stretched to form a regular pattern (often diamond-shaped) of metal mesh-like material. It is commonly used for fences and grates, and as metallic lath to support plaster or stucco.\r\n\r\nModel – EXM/852520/626/12X32\r\n\r\nMaterial – Aluminium\r\n\r\nAvailable Size – 1220X3200\r\n\r\nThickness – 2MM\r\n\r\nColour – RED', 0.00, 'Expanded Metal is a popular material for todays building and industrial design.', '1784786674_2-1.jpg', '', '2026-07-14 09:58:41'),
(328, 'Mosquito Mesh - Aluminium', 8, NULL, NULL, 'Material – Aluminium\r\n\r\nAvailable Sizes – 4ft\r\n\r\nAvailable Colour – aluminium', 0.00, '', '1784786614_Aluminium-Mosquito-Net.jpg', '', '2026-07-14 10:02:11'),
(329, 'Mosquito Mesh - Fiber', 8, NULL, NULL, 'Material – Fiber\r\n\r\nAvailable Sizes – 3ft / 4ft / 5ft\r\n\r\nAvailable Colour – Black and Grey', 0.00, '', '1784786576_1-3.jpg', '1784786576_hover_4.jpg', '2026-07-14 10:02:11'),
(330, 'DOWSIL 688 Glazing and Cladding Sealant', 9, NULL, NULL, 'DOWSIL™ High-performance Silicone & Silicone-based products. DOWSIL™ is the world leading brand in silicone adhesives & sealants and silicon-based technology for electronics. It offers solutions to improve industrial performance.', 0.00, '', '1784786512_9-660x800.jpg', '', '2026-07-14 10:05:25'),
(331, 'DOWSIL 789 Silicone Weather Proofing Sealant', 9, NULL, NULL, 'DOWSIL 789 Silicone Weatherproofing Sealant is a one-part, neutral- cure, architectural\r\ngrade sealant. It easily extrudes in any weather and cures at ambient temperature by\r\nreaction with moisture in the air to form a durable, flexible silicone rubber seal.', 0.00, '', '1784786479_6-660x800.jpg', '', '2026-07-14 10:05:25'),
(332, 'DOWSIL 791 Silicon Weatherproofing Sealant', 9, NULL, NULL, 'A one-part, medium-modulus, elastomeric sealant designed for general weathersealing. It is a silicone formulation that cures to a flexible and curable silicone rubber building joint seal.\r\n\r\n<strong>Benefits:</strong>\r\nEase of application – ready to use as supplied\r\nExcellent rheology, low string upon gunning\r\nExcellent weatherability, virtually unaffected by sunlight, rain, snow, ozone\r\nNeutral cure\r\nLow odor\r\n<strong>Uses:</strong>\r\nGeneral glazing and weather sealing in curtain wall and building facades', 0.00, '', '1784786440_8-660x800.jpg', '', '2026-07-14 10:05:25'),
(333, 'DOWSIL 995 Silicone Structural Sealant', 9, NULL, NULL, 'A one-component, self-priming, shelf-stable, neutral-cure, elastomeric adhesive specifically formulated for silicone structural glazing and exhibits excellent unprimed adhesion to most building substrates. This product has superior unprimed adhesion for structural glazing applications for hurricane or impact rated windows and doors. It has a movement capability of +/- 50%.\r\n\r\n<strong>Benefits:</strong>\r\nOdorless, non-corrosive cure system\r\nCures to form an extremely tough elastomeric rubber ensuring a durable, flexible, watertight bond\r\nExcellent weatherability and high resistance to ultraviolet radiation, heat and humidity, ozone and temperature extremes\r\nExcellent mechanical properties\r\nSuccessfully tested for use in protective glazing applications\r\nExcellent unprimed adhesion to wide range of substrates including coated, enameled, and reflective glasses; anodized and polyester coated or painted aluminum profiles including most fluoropolymer-based paints\r\nMeets global standards for structural glazing (American, Chinese, European)\r\n<strong>Uses:</strong>\r\nStructural joints of small and medium-sized buildings\r\nProtective glass sealant', 0.00, '', '1784786353_10-660x800 (1).jpg', '', '2026-07-14 10:05:25'),
(335, 'DOWSIL Glass & Metal Silicone Sealant', 9, NULL, NULL, 'Silicone sealants are commonly used to bind surfaces such as plastic, metal, and glass together. For example, aquariums are often sealed with silicone. Windows are often sealed to frames with silicone adhesive since it is weather resistant.\r\n\r\nAvailable Colours :<strong>Clear</strong>', 0.00, '', '1784786182_1-660x800.jpg', '', '2026-07-14 10:05:25'),
(336, 'DOWSIL Glass Silicone Sealant', 9, NULL, NULL, 'Silicone sealants are commonly used to bind surfaces such as plastic, metal, and glass together. For example, aquariums are often sealed with silicone. Windows are often sealed to frames with silicone adhesive since it is weather resistant\r\n\r\n \r\n\r\nAvailable Colours : <strong>Clear</strong>', 0.00, '', '1784786124_5-660x800.jpg', '', '2026-07-14 10:05:25'),
(337, 'DOWSIL GP Silicone Sealant', 9, NULL, NULL, 'DOWSIL™ General Purpose Silicone Sealant is a one-part, acetoxy cure silicone sealant for general purpose applications. It provides a flexible bond and will not harden or crack.\r\nDOWSIL General Purpose Silicone Sealant is a high-performance sealant, with ±25% movement capability when properly applied.\r\n\r\nAvailable Colour : <strong>Clear / Black / White / Grey</strong>', 0.00, '', '1784786052_2-660x800.jpg', '', '2026-07-14 10:05:25'),
(338, 'DOWSIL Neutral Plus Silicone Sealant', 9, NULL, NULL, 'DOWSIL Neutral Plus Silicone Sealant is a cost effective, multipurpose, neutral cure silicone sealant offering long term durability in a range of general sealing, general glazing, weatherproofing and\r\nProfessional Trade Applications.\r\n\r\nDOWSIL Neutral Plus Silicone Sealant will bond to form a strong, weatherproof seal on most common building materials, like Glass, Aluminum, Brick, Concrete, Steel, Ceramic, Selected Plastics, etc.\r\n\r\nAvailable Colours : <strong>Black / Clear / White</strong>', 0.00, '', '1784785993_3-660x800.jpg', '', '2026-07-14 10:05:25'),
(339, 'DOWSIL Sanitary & Tile Silicone Sealant', 9, NULL, NULL, '', 0.00, '', '1784785859_4-660x800.jpg', '', '2026-07-14 10:05:25'),
(340, 'ADJUSTABLE WRENCH 6\"', 10, NULL, NULL, 'Model  : TO/WRN/WYN2914/200', 0.00, '', '1784785784_8-1.jpg', '', '2026-07-14 10:14:32'),
(341, 'CHALK LINE MARKER - W0605', 10, NULL, NULL, 'Model : TO/CIM/WYN/W0605', 0.00, '', '1784785746_3-6.jpg', '', '2026-07-14 10:14:32'),
(342, 'Chalk Marker - W0271', 10, NULL, NULL, '<strong>WYNN’S W0271 CHALK LINE 3 PCS SET 30M</strong>\r\nModel : TO/CIM/WYN/W0271\r\n\r\nType : Hand Tools\r\n\r\nCable length: 30m\r\n\r\n<strong>Product Description</strong>\r\n\r\nA chalk line or chalk box is a tool for marking long, straight lines on relatively flat surfaces, much farther than is practical by hand or with a straightedge. They may be used to lay out straight lines between two points, or vertical lines by using the weight of the line reel as a plumb line.\r\n\r\nTechnical data\r\nClear ink color\r\nEasy to get to the beginning and end\r\nConvenient for work\r\nKeep the toner cartridge in a cool place', 0.00, '', '1784785706_2-6.jpg', '1784785706_hover_2-1-1.jpg', '2026-07-14 10:14:32'),
(343, 'CLOW HAMMER - W2752C', 10, NULL, NULL, '<strong>CLOW HAMMER WOOD HANDLE 0.5 (W2752C)</strong>\r\n\r\nModel : TO/HMR/WYN/0.5CLW', 0.00, '', '1784785556_19.jpg', '', '2026-07-14 10:14:32'),
(344, 'COMBINATION PLIER - AB208', 10, NULL, NULL, 'Model : TO/PLR/WYN/AB208', 0.00, '', '1784785497_275.jpg', '', '2026-07-14 10:14:32'),
(345, 'COMBINATION WRENCH - 8PCS', 10, NULL, NULL, 'Model : TO/SPN/8PCS/W0327A', 0.00, '', '1784785421_1-10.jpg', '', '2026-07-14 10:14:32'),
(346, 'COMBINATION WRENCH - W0304C', 10, NULL, NULL, 'Model : TO/WRN/W0304C/10\r\n\r\nSize : 10″ / 12″ / 14″ / 17″ / 16″ – 12MM\r\n\r\n', 0.00, '', '1784785381_5-2.jpg', '', '2026-07-14 10:14:32'),
(347, 'DI LASER LEVEL - W0639', 10, NULL, NULL, 'Model : TO/DIGI/WIN/W0639\r\n\r\nColour : GREEN LIGHT', 0.00, '', '1784785340_16.jpg', '', '2026-07-14 10:14:32'),
(348, 'DIAMOND SAW BLADE - W114C', 10, NULL, NULL, 'Model : TO/DSB/W114C', 0.00, '', '1784785306_10-1.jpg', '', '2026-07-14 10:14:32'),
(349, 'DIGITAL CALIPER - W0579B', 10, NULL, NULL, 'Model : TO/CALI/W0579B\r\n\r\n', 0.00, '', '1784785268_15.jpg', '', '2026-07-14 10:14:32'),
(350, 'DOUBLE OPEN WRENCH 14x17', 10, NULL, NULL, 'Model : TO/WRN/WS0010/14X17', 0.00, '', '1784785226_7-2.jpg', '', '2026-07-14 10:14:32'),
(351, 'FLAP WHEEL W2776A 4\"', 10, NULL, NULL, 'Model : TO/WHL/2726A/100', 0.00, '', '1784785189_3-7.jpg', '', '2026-07-14 10:14:32'),
(352, 'FLAT FILE - W0086', 10, NULL, NULL, 'Model : TO/FF/WYN/W0086/6″\r\n\r\nMaterial : Metal\r\n\r\nSize : 6″\r\n\r\nHandle : Plastic Handle for Better Grip\r\n\r\nDouble Sided Coarse & Fine Surface', 0.00, '', '1784785142_5-1.jpg', '', '2026-07-14 10:14:32'),
(353, 'FOLDABLE KNIFE - DNZ-028', 10, NULL, NULL, 'Model : TO/NIFE/DNZ028', 0.00, '', '1784785087_234.jpg', '', '2026-07-14 10:14:32'),
(354, 'FOLDING ALLN SET - W174A', 10, NULL, NULL, '<strong>WYNS FOLDING 8PCS ALLN SET</strong>\r\n\r\nModel : TO/FTS/WYN/W174A', 0.00, '', '1784784740_17.jpg', '1784784740_hover_17-1.jpg', '2026-07-14 10:14:32'),
(356, 'GLASS HOLDER - BT-VP4 PIN', 10, NULL, NULL, 'Model : TO/GH/BT-VP4', 0.00, '', '1784784498_18.jpg', '', '2026-07-14 10:14:32'),
(357, 'GRINDING WHEEL - W2450', 10, NULL, NULL, 'Model : TO/WHL/W2450/100', 0.00, '', '1784784464_28.jpg', '', '2026-07-14 10:14:32'),
(358, 'GRINDING WHEEL - W2449', 10, NULL, NULL, 'Model ; TO/WHL/W2449/100', 0.00, '', '1784784422_4-2.jpg', '', '2026-07-14 10:14:32'),
(359, 'HACKSAW BLADE - W0496B', 10, NULL, NULL, 'Model : TO/HHCY/W0496B/24T', 0.00, '', '1784784388_6-1.jpg', '', '2026-07-14 10:14:32'),
(360, 'HACKSAW FRAME - WS02B', 10, NULL, NULL, 'Model : TO/HSF/WYN/WS02B', 0.00, '', '1784784351_21.jpg', '', '2026-07-14 10:14:32'),
(361, 'HAND SAW - WS0339C', 10, NULL, NULL, 'Model :TO/HSW/WYN/WS0339C\r\n\r\n<strong>Product Description</strong>\r\nThe teeth of Wynn’s Jet Cut saws have three precision ground cutting edges.\r\nThis ensures that Jet cut saws cut faster and more smoothly than conventional designs.\r\nThe panel saw also features hardpoint teeth for long life.\r\nIt features an ergonomically designed handle with a soft grip inlay\r\nSet was Sharpened\r\nCome with Saw Teeth Cover', 0.00, '', '1784783724_7-1.jpg', '', '2026-07-14 10:14:32'),
(362, 'HOLE SAW SET - W0610', 10, NULL, NULL, 'Model : TO/HOLESAW/W0610', 0.00, '', '1784783634_11.jpg', '', '2026-07-14 10:14:32'),
(363, 'LONG NOSE PLIER - F508A', 10, NULL, NULL, 'Model : TO/PLR/WYN/F508A', 0.00, '', '1784783594_29.jpg', '', '2026-07-14 10:14:32'),
(364, 'Measuring Tape', 10, NULL, NULL, 'Model : TO#100/10M/RG/WYN\r\n\r\nAvailable Sizes : 7.5M,10M,5M', 0.00, '', '1784783513_1-8.jpg', '', '2026-07-14 10:14:32'),
(365, 'NT CUTTER - W0455', 10, NULL, NULL, 'Model : TO/NTC/WYNS/W0455', 0.00, '', '1784783480_24.jpg', '', '2026-07-14 10:14:32'),
(366, 'PRUNING AW - W0387', 10, NULL, NULL, 'Model :TO/PSW/WYN/W0387', 0.00, '', '1784783442_8.jpg', '', '2026-07-14 10:14:32'),
(367, 'RIVET GUN - W0078', 10, NULL, NULL, 'Model : TO/RG/WYN/W0078', 0.00, '', '1784783403_256.jpg', '', '2026-07-14 10:14:32'),
(368, 'RIVET GUN - W203B', 10, NULL, NULL, 'Model : TO/RG/WYN/W203B', 0.00, '', '1784783369_322.jpg', '', '2026-07-14 10:14:32'),
(369, 'RIVET GUN NIPPLE', 10, NULL, NULL, 'Model : TO/RG/NIPPLE', 0.00, '', '1784783334_25 (1).jpg', '', '2026-07-14 10:14:32'),
(370, 'RUBBER HAMMER - W0165A', 10, NULL, NULL, 'Model :TO/HMR/WYN/W0165A', 0.00, '', '1784783246_20.jpg', '', '2026-07-14 10:14:32'),
(371, 'SAFETY BELT - W2773', 10, NULL, NULL, 'Model : TO/SBELT/W2773\r\n\r\n<strong>SAFETY BELT SINGLE W2773</strong>\r\n<strong>SAFETY BELT DOUBLE W2774</strong>', 0.00, '', '1784783157_26.jpg', '', '2026-07-14 10:14:32'),
(372, 'SAW BLADE – ACO14', 10, NULL, NULL, 'Model : TO/SBLD/ACO14/120T', 0.00, '', '1784783061_27.jpg', '', '2026-07-14 10:14:32'),
(373, 'SCREW DRIVER', 10, NULL, NULL, 'Model : TO/SD/WYN/0397/8PH\r\n\r\nAvailable Colours :\r\n\r\nSCREW DRIVER 0397 ( Yellow)\r\nSCREW DRIVER 0398 (Green)\r\n\r\nSize : 8″ PH / 10 FH / 12FH / 8″ PH /6″ FH / 10″ PH /8″ PH / 4″ FH / 12″ PH / 4″ PH / 8FH / 6PH\r\n\r\nRelated products', 0.00, '', '1784782940_9.jpg', '', '2026-07-14 10:14:32'),
(374, 'SILICONE GUN', 10, NULL, NULL, 'Model : TO/SG/DTJQ/BL', 0.00, '', '1784782884_1-9.jpg', '', '2026-07-14 10:14:32'),
(375, 'SILICONE GUN - BT-G1030', 10, NULL, NULL, 'Model : TO/SG/BT-G1030/OR', 0.00, '', '1784782841_10.jpg', '', '2026-07-14 10:14:32'),
(376, 'STEEL KEEL CLMP - W0303', 10, NULL, NULL, 'Model : STEEL KEEL CLMP W0303', 0.00, '', '1784196991_222.jpg', '', '2026-07-14 10:14:32'),
(377, 'TOOL BAG - W41909', 10, NULL, NULL, 'Model : TO/BAG/WYN/41909', 0.00, '', '1784196964_13.jpg', '', '2026-07-14 10:14:32'),
(378, 'TOOL BOX - W021', 10, NULL, NULL, 'Model : TO/W021\r\n\r\nTOOL BOX 21PCS WYN\r\nTOOL BOX 10PCS WYNS\r\nTOOL BOX 18PCS WYNS\r\nTOOL BOX 65PCS WYNS\r\nTOOL KIT 12PCS WYNNS', 0.00, '', '1784196919_14-1.jpg', '', '2026-07-14 10:14:32'),
(379, 'TOOL JACKET 991121', 10, NULL, NULL, 'Model : TOL JAKTS 991121', 0.00, '', '1784196859_9-1.jpg', '', '2026-07-14 10:14:32');

-- --------------------------------------------------------

--
-- Table structure for table `product_gallery`
--

CREATE TABLE `product_gallery` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `image_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_gallery`
--

INSERT INTO `product_gallery` (`id`, `product_id`, `image_name`) VALUES
(2, 5, '1784606403_gal_1-4-660x660.jpg'),
(3, 5, '1784606403_gal_2-5-660x660.jpg'),
(4, 5, '1784606403_gal_3-1-660x660.jpg'),
(5, 5, '1784606403_gal_4-1-660x660.jpg'),
(6, 5, '1784606403_gal_5-1-660x660.jpg'),
(7, 277, '1784789971_gal_3-5.jpg'),
(8, 276, '1784790046_gal_007-3.jpg'),
(9, 259, '1784792114_gal_6-Stainless-Steel-Door-Latch-Sliding-Lock-Barrel-Bolt-with-Ground-Plug-Hole-Dust-Cover-Safety.jpg_q50-660x660.jpg'),
(10, 244, '1784792701_gal_patch-fittings-pf-20-ss-2.jpg'),
(11, 242, '1784792801_gal_patch-fittings-pf-10-ss-1.jpg'),
(12, 241, '1784792887_gal_3-11.jpg'),
(13, 240, '1784792970_gal_3-10.jpg'),
(14, 239, '1784793048_gal_3-9.jpg'),
(15, 232, '1784793326_gal_fsff.jpg'),
(16, 177, '1784798616_gal_203-1.jpg'),
(17, 175, '1784798740_gal_120-4.jpg'),
(18, 156, '1784799793_gal_aa.jpg'),
(19, 152, '1784800019_gal_415-3.jpg'),
(20, 150, '1784800135_gal_gerh.jpg'),
(21, 149, '1784800194_gal_WWEFFE.jpg'),
(22, 17, '1784800816_gal_2-3 (1).jpg'),
(23, 16, '1784800896_gal_5-3.jpg'),
(24, 16, '1784800896_gal_4-4.jpg'),
(25, 16, '1784800896_gal_3-12.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `review` text NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `product_id`, `rating`, `review`, `name`, `email`, `created_at`) VALUES
(1, 172, 5, 'nnnnn', 'nnnn', 'adithyaanuhasgo@gmail.com', '2026-07-16 05:55:30');

-- --------------------------------------------------------

--
-- Table structure for table `sub_categories`
--

CREATE TABLE `sub_categories` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `sub_name` varchar(255) NOT NULL,
  `image_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sub_categories`
--

INSERT INTO `sub_categories` (`id`, `category_id`, `sub_name`, `image_name`) VALUES
(1, 1, 'Indoor', 'Indoor.jpg'),
(2, 1, 'Outdoor', 'outdoor1.jpg'),
(4, 2, 'Swisstek Aluminium', '1784801932_sub_sw.jpg'),
(10, 4, 'Door Bottom Seals', '1784801980_sub_BO-1.jpg'),
(11, 4, 'Door Closer', '1784802026_sub_313.jpg'),
(12, 4, 'Door Stopper', '1784802040_sub_2-12.jpg'),
(13, 4, 'Flush Bolts', '1784802059_sub_15-1.jpg'),
(14, 4, 'Hinges', '1784802078_sub_H (1).jpg'),
(15, 4, 'Others', '1784802089_sub_qrwrfwfw.jpg'),
(16, 4, 'Pantry Cupboard Acc.', '1784802105_sub_12.jpg'),
(17, 4, 'Patch Fittings', '1784802123_sub_patch.jpg'),
(18, 4, 'Shower Cubicle Systems', '1784802137_sub_SH.jpg'),
(19, 4, 'Spider Fittings', '1784802150_sub_18-1.jpg'),
(20, 4, 'Tower Bolts', '1784802163_sub_17-2.jpg'),
(21, 4, 'Transmission Rod', '1784802187_sub_aafsg.jpg'),
(22, 5, 'Aluminium Lock & Handles', '1784802213_sub_adsfs.jpg'),
(23, 5, 'Door Handle', '1784802229_sub_hand.jpg'),
(24, 5, 'Glass Locks', '1784802243_sub_189.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'user',
  `username` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `role`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'Admin Manager', 'admin', 'admin', 'admin@houseofalu.com', '$2y$10$ankKtbYx1vVyw8BRn4rbwe2NTzLdC24IloHaapyh16YjT6WM1oxb2', '2026-07-15 09:48:57'),
(2, 'kaushan', 'user', 'kaushan', 'kaushangamage123@gmail.com', '$2y$10$drxSpvhMtFO3kb2xo8dHgu0QHxL9FmxXFqe5QXbkD5vhZCDu2SF4m', '2026-07-16 05:30:56'),
(3, 'muv', 'user', 'muv', 'muv123@gmail.com', '$2y$10$tH1RZfB3gxAB8BYqc4UwQOJkm/INwoXuMJUWh0YtwXDbHRvspAh/O', '2026-07-16 10:28:19');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL,
  `token` varchar(50) NOT NULL,
  `product_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`id`, `token`, `product_id`, `created_at`) VALUES
(5, 'f1b7680a2b515af4', 323, '2026-07-15 09:10:17'),
(6, 'fb370fc55d1fa183', 323, '2026-07-15 09:10:22'),
(10, 'a8819823dc8ce001', 172, '2026-07-15 11:23:26'),
(12, 'a8819823dc8ce001', 174, '2026-07-16 10:58:29');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sub_category_id` (`sub_category_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_submissions`
--
ALTER TABLE `contact_submissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_submissions`
--
ALTER TABLE `payment_submissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `sub_category_id` (`sub_category_id`),
  ADD KEY `brand_id` (`brand_id`);

--
-- Indexes for table `product_gallery`
--
ALTER TABLE `product_gallery`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sub_categories`
--
ALTER TABLE `sub_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `contact_submissions`
--
ALTER TABLE `contact_submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `payment_submissions`
--
ALTER TABLE `payment_submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=380;

--
-- AUTO_INCREMENT for table `product_gallery`
--
ALTER TABLE `product_gallery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sub_categories`
--
ALTER TABLE `sub_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `brands`
--
ALTER TABLE `brands`
  ADD CONSTRAINT `brands_ibfk_1` FOREIGN KEY (`sub_category_id`) REFERENCES `sub_categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `prod_brand_fk` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `prod_cat_fk` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `prod_sub_fk` FOREIGN KEY (`sub_category_id`) REFERENCES `sub_categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_gallery`
--
ALTER TABLE `product_gallery`
  ADD CONSTRAINT `product_gallery_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sub_categories`
--
ALTER TABLE `sub_categories`
  ADD CONSTRAINT `sub_categories_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
