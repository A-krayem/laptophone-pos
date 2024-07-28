-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 24, 2023 at 06:09 PM
-- Server version: 5.6.20
-- PHP Version: 5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `empty_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `areas`
--

CREATE TABLE IF NOT EXISTS `areas` (
`id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `areas`
--

INSERT INTO `areas` (`id`, `name`, `country_id`) VALUES
(1, 'North Lebanon', 122),
(2, 'South Lebanon', 122),
(3, 'Bekaa', 122),
(4, 'Mount Lebanon', 122),
(5, 'Beirut', 122);

-- --------------------------------------------------------

--
-- Table structure for table `authorized_devices`
--

CREATE TABLE IF NOT EXISTS `authorized_devices` (
`id` int(11) NOT NULL,
  `operator_id` int(11) DEFAULT NULL,
  `authorized_key` varchar(500) DEFAULT NULL,
  `accepted` tinyint(1) NOT NULL DEFAULT '0',
  `creation_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `banks`
--

CREATE TABLE IF NOT EXISTS `banks` (
`id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `barcode_params`
--

CREATE TABLE IF NOT EXISTS `barcode_params` (
`id` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `value` varchar(45) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  `paper_id` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=75 ;

--
-- Dumping data for table `barcode_params`
--

INSERT INTO `barcode_params` (`id`, `name`, `value`, `description`, `paper_id`) VALUES
(1, 'w', '140', 'image width', 1),
(2, 'h', '40', 'image heigth', 1),
(3, 'th', '15', 'text height', 1),
(4, 'ts', '3', 'text size', 1),
(5, 'p', '5', 'padding', 1),
(6, 'type', 'ean128', NULL, 1),
(7, 'wq', '1', NULL, 1),
(8, 'wm', '1', NULL, 1),
(9, 'ww', '3', NULL, 1),
(10, 'wn', '1', NULL, 1),
(11, 'w4', '1', NULL, 1),
(12, 'w5', '1', NULL, 1),
(13, 'w6', '1', NULL, 1),
(14, 'w7', '1', NULL, 1),
(15, 'w8', '1', NULL, 1),
(16, 'w9', '1', NULL, 1),
(17, 'pt', '5', 'padding top', 1),
(18, 'price_font_size', '14', NULL, 1),
(19, 'price_x', '5', NULL, 1),
(20, 'price_y', '35', NULL, 1),
(21, 'id_font_size', '4', NULL, 1),
(22, 'id_x', '15', NULL, 1),
(23, 'id_y', '75', NULL, 1),
(24, 'pb', '15', 'padding botton', 1),
(28, 'pl', '1', 'padding left', 1),
(29, 'pr', '1', 'padding right', 1),
(30, 'price_enable', '1', NULL, 1),
(31, 'id_enable', '0', NULL, 1),
(32, 'description_enable', '1', NULL, 1),
(33, 'description_size', '14', NULL, 1),
(34, 'description_x', '5', NULL, 1),
(35, 'description_y', '20', NULL, 1),
(36, 'store_name_enable', '1', NULL, 1),
(37, 'store_name_x', '5', NULL, 1),
(38, 'store_name_y', '0', NULL, 1),
(39, 'store_name_font_size', '16', NULL, 1),
(40, 'description_max_size', '50', NULL, 1),
(41, 'description_max_width_on_paper', '480', NULL, 1),
(42, 'description_multiline_space', '65', NULL, 1),
(43, 'discount_enable', '0', NULL, 1),
(44, 'discount_font_size', '10', NULL, 1),
(45, 'discount_x', '0', NULL, 1),
(46, 'discount_y', '75', NULL, 1),
(47, 'price_after_discount_size', '45', NULL, 1),
(48, 'price_after_discount_x', '0', NULL, 1),
(49, 'price_after_discount_y', '93', NULL, 1),
(53, 'barcode_position_x', '5', NULL, 1),
(54, 'barcode_position_y', '50', NULL, 1),
(55, 'barcode_nb_x', '20', NULL, 1),
(56, 'barcode_nb_y', '285', NULL, 1),
(57, 'barcode_nb_font_size', '30', NULL, 1),
(58, 'barcode_dpi', '203', NULL, 1),
(59, 'barcode_paper_width_in_pixels', '203', NULL, 1),
(60, 'barcode_paper_height_in_pixels', '203', NULL, 1),
(61, 'barcode_enable', '1', NULL, 1),
(62, 'price_after_discount_enable', '0', NULL, 1),
(63, 'size_enable', '0', NULL, 1),
(64, 'color_enable', '0', NULL, 1),
(65, 'size_x', '0', NULL, 1),
(66, 'size_y', '0', NULL, 1),
(67, 'color_x', '0', NULL, 1),
(68, 'color_y', '0', NULL, 1),
(69, 'size_font_size', '10', NULL, 1),
(70, 'color_font_size', '10', NULL, 1),
(71, 'enable_sku', '0', NULL, 1),
(72, 'sku_x', '0', NULL, 1),
(73, 'sku_y', '0', NULL, 1),
(74, 'sku_font_size', '12', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `bonuses_and_penalties`
--

CREATE TABLE IF NOT EXISTS `bonuses_and_penalties` (
`id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `creation_date` datetime DEFAULT NULL,
  `type` int(11) DEFAULT NULL COMMENT '1->Bonus\n2->Penalties',
  `amount` decimal(10,2) DEFAULT NULL,
  `reason` text,
  `deleted` tinyint(1) DEFAULT '0',
  `payroll_id` int(11) NOT NULL DEFAULT '0',
  `currency_id` int(11) NOT NULL DEFAULT '2'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cashback`
--

CREATE TABLE IF NOT EXISTS `cashback` (
`id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT '0',
  `cashbox_id` int(11) DEFAULT '0',
  `creation_date` datetime DEFAULT NULL,
  `currency_id` int(11) DEFAULT NULL,
  `cashback_value` decimal(20,5) DEFAULT '0.00000',
  `deleted` tinyint(1) DEFAULT '0',
  `by_user_id` int(11) NOT NULL DEFAULT '0',
  `deleted_by_user_id` tinyint(1) NOT NULL DEFAULT '0',
  `deleted_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cashbox`
--

CREATE TABLE IF NOT EXISTS `cashbox` (
`id` int(11) NOT NULL,
  `store_id` int(11) DEFAULT NULL,
  `vendor_id` int(11) DEFAULT NULL,
  `starting_cashbox_date` datetime DEFAULT NULL,
  `cash` decimal(20,5) DEFAULT NULL,
  `closed` tinyint(1) DEFAULT '0',
  `cash_on_close` decimal(20,5) DEFAULT '0.00000',
  `current_cash_box_value` decimal(20,5) DEFAULT '0.00000',
  `ending_cashbox_date` datetime DEFAULT NULL,
  `manual_updated` tinyint(1) NOT NULL DEFAULT '0',
  `fixed_info` text,
  `cashbox_lbp` decimal(20,5) NOT NULL DEFAULT '0.00000'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cashbox_changes_info`
--

CREATE TABLE IF NOT EXISTS `cashbox_changes_info` (
`id` int(11) NOT NULL,
  `invoice_id` int(11) DEFAULT NULL,
  `return_value` decimal(20,5) DEFAULT NULL,
  `added_value` decimal(20,5) DEFAULT NULL,
  `change_date` datetime DEFAULT NULL,
  `cashbox_id` int(11) DEFAULT NULL,
  `old_cashbox_id` int(11) DEFAULT NULL,
  `cash_usd_to_return` decimal(20,5) NOT NULL DEFAULT '0.00000',
  `cash_lbp_to_return` decimal(20,5) NOT NULL DEFAULT '0.00000',
  `returned_cash_lbp` decimal(20,5) NOT NULL DEFAULT '0.00000',
  `returned_cash_usd` decimal(20,5) NOT NULL DEFAULT '0.00000',
  `cash_lbp_in` decimal(20,5) NOT NULL DEFAULT '0.00000',
  `cash_usd_in` decimal(20,5) NOT NULL DEFAULT '0.00000',
  `rate` decimal(20,5) NOT NULL DEFAULT '0.00000',
  `only_return` tinyint(1) NOT NULL DEFAULT '0',
  `cash_usd_r` int(11) NOT NULL DEFAULT '0',
  `cash_lbp_r` int(11) NOT NULL DEFAULT '0',
  `invoice_item_id` int(11) NOT NULL DEFAULT '0',
  `invoice_item_return_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cashbox_transactions`
--

CREATE TABLE IF NOT EXISTS `cashbox_transactions` (
`id` int(11) NOT NULL,
  `transaction_type` int(11) DEFAULT NULL COMMENT '1-> in\n2-> out\n3->transfer',
  `amount_usd` decimal(20,5) DEFAULT '0.00000',
  `from_cashbox_id` int(11) DEFAULT '0',
  `to_cashbox_id` int(11) DEFAULT '0',
  `creation_date` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `accepted_by_receiver` tinyint(1) DEFAULT '0',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `note` text,
  `amount_lbp` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cash_details`
--

CREATE TABLE IF NOT EXISTS `cash_details` (
`id` int(11) NOT NULL,
  `cash_usd` decimal(20,5) DEFAULT '0.00000',
  `cash_lbp` decimal(20,5) DEFAULT '0.00000',
  `invoice_id` int(11) DEFAULT NULL,
  `base_usd_amount` decimal(20,5) DEFAULT NULL,
  `rate` decimal(20,5) DEFAULT NULL,
  `cashbox_id` int(11) DEFAULT NULL,
  `must_return_cash_usd` decimal(20,5) NOT NULL DEFAULT '0.00000',
  `must_return_cash_lbp` decimal(20,5) NOT NULL DEFAULT '0.00000',
  `returned_cash_usd` decimal(20,5) NOT NULL DEFAULT '0.00000',
  `returned_cash_lbp` decimal(20,5) NOT NULL DEFAULT '0.00000'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cash_details_logs`
--

CREATE TABLE IF NOT EXISTS `cash_details_logs` (
`id` int(11) NOT NULL,
  `creation_date` datetime DEFAULT NULL,
  `cash_usd` decimal(20,5) DEFAULT NULL,
  `cash_lbp` decimal(20,5) DEFAULT NULL,
  `returned_cash_usd` decimal(20,5) DEFAULT NULL,
  `returned_cash_lbp` decimal(20,5) DEFAULT NULL,
  `must_return_cash_usd` decimal(20,5) DEFAULT NULL,
  `must_return_cash_lbp` decimal(20,5) DEFAULT NULL,
  `cashbox_id` int(11) DEFAULT NULL,
  `rate` decimal(20,5) DEFAULT NULL,
  `transaction_id` int(11) DEFAULT NULL,
  `transaction_type` int(11) DEFAULT NULL COMMENT '1-> invoices',
  `cash_details_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cash_in_out`
--

CREATE TABLE IF NOT EXISTS `cash_in_out` (
`id` int(11) NOT NULL,
  `creation_date` datetime DEFAULT NULL,
  `type_id` int(11) DEFAULT NULL,
  `cash_value` decimal(20,10) DEFAULT NULL,
  `cashbox_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `note` varchar(200) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT NULL,
  `currency_id` int(11) DEFAULT NULL,
  `currency_rate` decimal(20,10) DEFAULT NULL,
  `cash_in_out` int(11) DEFAULT NULL,
  `amount_lbp` decimal(20,10) NOT NULL DEFAULT '0.0000000000',
  `amount_usd` decimal(20,10) NOT NULL DEFAULT '0.0000000000',
  `operation_reference` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cash_in_out_starting`
--

CREATE TABLE IF NOT EXISTS `cash_in_out_starting` (
`id` int(11) NOT NULL,
  `usd_amount` decimal(15,5) DEFAULT NULL,
  `lbp_amount` decimal(15,5) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `shift_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cash_in_out_types`
--

CREATE TABLE IF NOT EXISTS `cash_in_out_types` (
`id` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT NULL,
  `in_out` int(11) NOT NULL DEFAULT '0',
  `group` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `cash_in_out_types`
--

INSERT INTO `cash_in_out_types` (`id`, `name`, `deleted`, `in_out`, `group`) VALUES
(1, 'Internal Transfer', 0, 0, 1),
(2, 'External Transfer', 0, 0, 1),
(3, 'Services', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE IF NOT EXISTS `cities` (
`id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `district_id` int(11) DEFAULT NULL,
  `delivery_points` int(11) NOT NULL DEFAULT '0',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `default_selected` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `collected_customers`
--

CREATE TABLE IF NOT EXISTS `collected_customers` (
`id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `phone` varchar(20) DEFAULT NULL,
  `store_id` int(11) DEFAULT '0',
  `source_customers_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `complex_items`
--

CREATE TABLE IF NOT EXISTS `complex_items` (
`id` int(11) NOT NULL,
  `complex_items_type` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `creation_date` datetime NOT NULL,
  `sub_total` decimal(20,5) DEFAULT '0.00000',
  `cost` decimal(20,5) DEFAULT '0.00000',
  `discount` decimal(20,5) DEFAULT '0.00000',
  `total` decimal(20,5) DEFAULT '0.00000',
  `profit` decimal(20,5) DEFAULT '0.00000',
  `deleted` tinyint(1) DEFAULT '0',
  `note` text,
  `name` text,
  `barcode` varchar(100) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `complex_item_details`
--

CREATE TABLE IF NOT EXISTS `complex_item_details` (
`id` int(11) NOT NULL,
  `complex_item_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `additional_description` text NOT NULL,
  `buying_cost` decimal(20,5) DEFAULT '0.00000',
  `qty` int(11) NOT NULL,
  `selling_price` decimal(20,5) DEFAULT '0.00000',
  `final_price` decimal(20,5) DEFAULT '0.00000',
  `final_cost` decimal(20,5) DEFAULT '0.00000',
  `profit` decimal(20,5) DEFAULT '0.00000',
  `deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `complex_item_log`
--

CREATE TABLE IF NOT EXISTS `complex_item_log` (
`id` int(11) NOT NULL,
  `complex_item_id` int(11) NOT NULL,
  `creation_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `data` text NOT NULL,
  `log_type` int(11) DEFAULT NULL COMMENT '1: new, 2 : item update, 3: Ci Update'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE IF NOT EXISTS `countries` (
`id` int(11) NOT NULL,
  `country_code` varchar(2) NOT NULL,
  `country_name` varchar(100) NOT NULL,
  `disabled` tinyint(1) NOT NULL DEFAULT '0',
  `default_selection` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=247 ;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `country_code`, `country_name`, `disabled`, `default_selection`) VALUES
(1, 'AF', 'Afghanistan', 0, 0),
(2, 'AL', 'Albania', 0, 0),
(3, 'DZ', 'Algeria', 0, 0),
(4, 'DS', 'American Samoa', 0, 0),
(5, 'AD', 'Andorra', 0, 0),
(6, 'AO', 'Angola', 0, 0),
(7, 'AI', 'Anguilla', 0, 0),
(8, 'AQ', 'Antarctica', 0, 0),
(9, 'AG', 'Antigua and Barbuda', 0, 0),
(10, 'AR', 'Argentina', 0, 0),
(11, 'AM', 'Armenia', 0, 0),
(12, 'AW', 'Aruba', 0, 0),
(13, 'AU', 'Australia', 0, 0),
(14, 'AT', 'Austria', 0, 0),
(15, 'AZ', 'Azerbaijan', 0, 0),
(16, 'BS', 'Bahamas', 0, 0),
(17, 'BH', 'Bahrain', 0, 0),
(18, 'BD', 'Bangladesh', 0, 0),
(19, 'BB', 'Barbados', 0, 0),
(20, 'BY', 'Belarus', 0, 0),
(21, 'BE', 'Belgium', 0, 0),
(22, 'BZ', 'Belize', 0, 0),
(23, 'BJ', 'Benin', 0, 0),
(24, 'BM', 'Bermuda', 0, 0),
(25, 'BT', 'Bhutan', 0, 0),
(26, 'BO', 'Bolivia', 0, 0),
(27, 'BA', 'Bosnia and Herzegovina', 0, 0),
(28, 'BW', 'Botswana', 0, 0),
(29, 'BV', 'Bouvet Island', 0, 0),
(30, 'BR', 'Brazil', 0, 0),
(31, 'IO', 'British Indian Ocean Territory', 0, 0),
(32, 'BN', 'Brunei Darussalam', 0, 0),
(33, 'BG', 'Bulgaria', 0, 0),
(34, 'BF', 'Burkina Faso', 0, 0),
(35, 'BI', 'Burundi', 0, 0),
(36, 'KH', 'Cambodia', 0, 0),
(37, 'CM', 'Cameroon', 0, 0),
(38, 'CA', 'Canada', 0, 0),
(39, 'CV', 'Cape Verde', 0, 0),
(40, 'KY', 'Cayman Islands', 0, 0),
(41, 'CF', 'Central African Republic', 0, 0),
(42, 'TD', 'Chad', 0, 0),
(43, 'CL', 'Chile', 0, 0),
(44, 'CN', 'China', 0, 0),
(45, 'CX', 'Christmas Island', 0, 0),
(46, 'CC', 'Cocos (Keeling) Islands', 0, 0),
(47, 'CO', 'Colombia', 0, 0),
(48, 'KM', 'Comoros', 0, 0),
(49, 'CG', 'Congo', 0, 0),
(50, 'CK', 'Cook Islands', 0, 0),
(51, 'CR', 'Costa Rica', 0, 0),
(52, 'HR', 'Croatia (Hrvatska)', 0, 0),
(53, 'CU', 'Cuba', 0, 0),
(54, 'CY', 'Cyprus', 0, 0),
(55, 'CZ', 'Czech Republic', 0, 0),
(56, 'DK', 'Denmark', 0, 0),
(57, 'DJ', 'Djibouti', 0, 0),
(58, 'DM', 'Dominica', 0, 0),
(59, 'DO', 'Dominican Republic', 0, 0),
(60, 'TP', 'East Timor', 0, 0),
(61, 'EC', 'Ecuador', 0, 0),
(62, 'EG', 'Egypt', 0, 0),
(63, 'SV', 'El Salvador', 0, 0),
(64, 'GQ', 'Equatorial Guinea', 0, 0),
(65, 'ER', 'Eritrea', 0, 0),
(66, 'EE', 'Estonia', 0, 0),
(67, 'ET', 'Ethiopia', 0, 0),
(68, 'FK', 'Falkland Islands (Malvinas)', 0, 0),
(69, 'FO', 'Faroe Islands', 0, 0),
(70, 'FJ', 'Fiji', 0, 0),
(71, 'FI', 'Finland', 0, 0),
(72, 'FR', 'France', 0, 0),
(73, 'FX', 'France, Metropolitan', 0, 0),
(74, 'GF', 'French Guiana', 0, 0),
(75, 'PF', 'French Polynesia', 0, 0),
(76, 'TF', 'French Southern Territories', 0, 0),
(77, 'GA', 'Gabon', 0, 0),
(78, 'GM', 'Gambia', 0, 0),
(79, 'GE', 'Georgia', 0, 0),
(80, 'DE', 'Germany', 0, 0),
(81, 'GH', 'Ghana', 0, 0),
(82, 'GI', 'Gibraltar', 0, 0),
(83, 'GK', 'Guernsey', 0, 0),
(84, 'GR', 'Greece', 0, 0),
(85, 'GL', 'Greenland', 0, 0),
(86, 'GD', 'Grenada', 0, 0),
(87, 'GP', 'Guadeloupe', 0, 0),
(88, 'GU', 'Guam', 0, 0),
(89, 'GT', 'Guatemala', 0, 0),
(90, 'GN', 'Guinea', 0, 0),
(91, 'GW', 'Guinea-Bissau', 0, 0),
(92, 'GY', 'Guyana', 0, 0),
(93, 'HT', 'Haiti', 0, 0),
(94, 'HM', 'Heard and Mc Donald Islands', 0, 0),
(95, 'HN', 'Honduras', 0, 0),
(96, 'HK', 'Hong Kong', 0, 0),
(97, 'HU', 'Hungary', 0, 0),
(98, 'IS', 'Iceland', 0, 0),
(99, 'IN', 'India', 0, 0),
(100, 'IM', 'Isle of Man', 0, 0),
(101, 'ID', 'Indonesia', 0, 0),
(102, 'IR', 'Iran', 0, 0),
(103, 'IQ', 'Iraq', 0, 0),
(104, 'IE', 'Ireland', 0, 0),
(105, 'IL', 'Israel', 0, 0),
(106, 'IT', 'Italy', 0, 0),
(107, 'CI', 'Ivory Coast', 0, 0),
(108, 'JE', 'Jersey', 0, 0),
(109, 'JM', 'Jamaica', 0, 0),
(110, 'JP', 'Japan', 0, 0),
(111, 'JO', 'Jordan', 0, 0),
(112, 'KZ', 'Kazakhstan', 0, 0),
(113, 'KE', 'Kenya', 0, 0),
(114, 'KI', 'Kiribati', 0, 0),
(115, 'KP', 'Korea, Democratic People''s Republic of', 0, 0),
(116, 'KR', 'Korea, Republic of', 0, 0),
(117, 'XK', 'Kosovo', 0, 0),
(118, 'KW', 'Kuwait', 0, 0),
(119, 'KG', 'Kyrgyzstan', 0, 0),
(120, 'LA', 'Lao People''s Democratic Republic', 0, 0),
(121, 'LV', 'Latvia', 0, 0),
(122, 'LB', 'Lebanon', 0, 1),
(123, 'LS', 'Lesotho', 0, 0),
(124, 'LR', 'Liberia', 0, 0),
(125, 'LY', 'Libya', 0, 0),
(126, 'LI', 'Liechtenstein', 0, 0),
(127, 'LT', 'Lithuania', 0, 0),
(128, 'LU', 'Luxembourg', 0, 0),
(129, 'MO', 'Macau', 0, 0),
(130, 'MK', 'Macedonia', 0, 0),
(131, 'MG', 'Madagascar', 0, 0),
(132, 'MW', 'Malawi', 0, 0),
(133, 'MY', 'Malaysia', 0, 0),
(134, 'MV', 'Maldives', 0, 0),
(135, 'ML', 'Mali', 0, 0),
(136, 'MT', 'Malta', 0, 0),
(137, 'MH', 'Marshall Islands', 0, 0),
(138, 'MQ', 'Martinique', 0, 0),
(139, 'MR', 'Mauritania', 0, 0),
(140, 'MU', 'Mauritius', 0, 0),
(141, 'TY', 'Mayotte', 0, 0),
(142, 'MX', 'Mexico', 0, 0),
(143, 'FM', 'Micronesia, Federated States of', 0, 0),
(144, 'MD', 'Moldova, Republic of', 0, 0),
(145, 'MC', 'Monaco', 0, 0),
(146, 'MN', 'Mongolia', 0, 0),
(147, 'ME', 'Montenegro', 0, 0),
(148, 'MS', 'Montserrat', 0, 0),
(149, 'MA', 'Morocco', 0, 0),
(150, 'MZ', 'Mozambique', 0, 0),
(151, 'MM', 'Myanmar', 0, 0),
(152, 'NA', 'Namibia', 0, 0),
(153, 'NR', 'Nauru', 0, 0),
(154, 'NP', 'Nepal', 0, 0),
(155, 'NL', 'Netherlands', 0, 0),
(156, 'AN', 'Netherlands Antilles', 0, 0),
(157, 'NC', 'New Caledonia', 0, 0),
(158, 'NZ', 'New Zealand', 0, 0),
(159, 'NI', 'Nicaragua', 0, 0),
(160, 'NE', 'Niger', 0, 0),
(161, 'NG', 'Nigeria', 0, 0),
(162, 'NU', 'Niue', 0, 0),
(163, 'NF', 'Norfolk Island', 0, 0),
(164, 'MP', 'Northern Mariana Islands', 0, 0),
(165, 'NO', 'Norway', 0, 0),
(166, 'OM', 'Oman', 0, 0),
(167, 'PK', 'Pakistan', 0, 0),
(168, 'PW', 'Palau', 0, 0),
(169, 'PS', 'Palestine', 0, 0),
(170, 'PA', 'Panama', 0, 0),
(171, 'PG', 'Papua New Guinea', 0, 0),
(172, 'PY', 'Paraguay', 0, 0),
(173, 'PE', 'Peru', 0, 0),
(174, 'PH', 'Philippines', 0, 0),
(175, 'PN', 'Pitcairn', 0, 0),
(176, 'PL', 'Poland', 0, 0),
(177, 'PT', 'Portugal', 0, 0),
(178, 'PR', 'Puerto Rico', 0, 0),
(179, 'QA', 'Qatar', 0, 0),
(180, 'RE', 'Reunion', 0, 0),
(181, 'RO', 'Romania', 0, 0),
(182, 'RU', 'Russia', 0, 0),
(183, 'RW', 'Rwanda', 0, 0),
(184, 'KN', 'Saint Kitts and Nevis', 0, 0),
(185, 'LC', 'Saint Lucia', 0, 0),
(186, 'VC', 'Saint Vincent and the Grenadines', 0, 0),
(187, 'WS', 'Samoa', 0, 0),
(188, 'SM', 'San Marino', 0, 0),
(189, 'ST', 'Sao Tome and Principe', 0, 0),
(190, 'SA', 'Saudi Arabia', 0, 0),
(191, 'SN', 'Senegal', 0, 0),
(192, 'RS', 'Serbia', 0, 0),
(193, 'SC', 'Seychelles', 0, 0),
(194, 'SL', 'Sierra Leone', 0, 0),
(195, 'SG', 'Singapore', 0, 0),
(196, 'SK', 'Slovakia', 0, 0),
(197, 'SI', 'Slovenia', 0, 0),
(198, 'SB', 'Solomon Islands', 0, 0),
(199, 'SO', 'Somalia', 0, 0),
(200, 'ZA', 'South Africa', 0, 0),
(201, 'GS', 'South Georgia South Sandwich Islands', 0, 0),
(202, 'ES', 'Spain', 0, 0),
(203, 'LK', 'Sri Lanka', 0, 0),
(204, 'SH', 'St. Helena', 0, 0),
(205, 'PM', 'St. Pierre and Miquelon', 0, 0),
(206, 'SD', 'Sudan', 0, 0),
(207, 'SR', 'Suriname', 0, 0),
(208, 'SJ', 'Svalbard and Jan Mayen Islands', 0, 0),
(209, 'SZ', 'Swaziland', 0, 0),
(210, 'SE', 'Sweden', 0, 0),
(211, 'CH', 'Switzerland', 0, 0),
(212, 'SY', 'Syria', 0, 0),
(213, 'TW', 'Taiwan', 0, 0),
(214, 'TJ', 'Tajikistan', 0, 0),
(215, 'TZ', 'Tanzania, United Republic of', 0, 0),
(216, 'TH', 'Thailand', 0, 0),
(217, 'TG', 'Togo', 0, 0),
(218, 'TK', 'Tokelau', 0, 0),
(219, 'TO', 'Tonga', 0, 0),
(220, 'TT', 'Trinidad and Tobago', 0, 0),
(221, 'TN', 'Tunisia', 0, 0),
(222, 'TR', 'Turkey', 0, 0),
(223, 'TM', 'Turkmenistan', 0, 0),
(224, 'TC', 'Turks and Caicos Islands', 0, 0),
(225, 'TV', 'Tuvalu', 0, 0),
(226, 'UG', 'Uganda', 0, 0),
(227, 'UA', 'Ukraine', 0, 0),
(228, 'AE', 'United Arab Emirates', 0, 0),
(229, 'GB', 'United Kingdom', 0, 0),
(230, 'US', 'United States', 0, 0),
(231, 'UM', 'United States minor outlying islands', 0, 0),
(232, 'UY', 'Uruguay', 0, 0),
(233, 'UZ', 'Uzbekistan', 0, 0),
(234, 'VU', 'Vanuatu', 0, 0),
(235, 'VA', 'Vatican City State', 0, 0),
(236, 'VE', 'Venezuela', 0, 0),
(237, 'VN', 'Vietnam', 0, 0),
(238, 'VG', 'Virgin Islands (British)', 0, 0),
(239, 'VI', 'Virgin Islands (U.S.)', 0, 0),
(240, 'WF', 'Wallis and Futuna Islands', 0, 0),
(241, 'EH', 'Western Sahara', 0, 0),
(242, 'YE', 'Yemen', 0, 0),
(243, 'ZR', 'Zaire', 0, 0),
(244, 'ZM', 'Zambia', 0, 0),
(245, 'ZW', 'Zimbabwe', 0, 0),
(246, '', 'Palestinian Territories (Gaza Strip and West Bank)', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `credit_notes`
--

CREATE TABLE IF NOT EXISTS `credit_notes` (
`id` int(11) NOT NULL,
  `creation_date` datetime DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `credit_payment_method` int(11) DEFAULT NULL,
  `credit_value` decimal(20,5) DEFAULT '0.00000',
  `deleted` tinyint(1) DEFAULT '0',
  `store_id` int(11) DEFAULT NULL,
  `note` text,
  `bank_id` int(11) NOT NULL DEFAULT '0',
  `reference` varchar(100) DEFAULT NULL,
  `payment_owner` varchar(100) DEFAULT NULL,
  `currency_rate` decimal(20,10) DEFAULT '1.0000000000',
  `payment_currency` int(11) NOT NULL DEFAULT '2',
  `s_invoice` int(11) NOT NULL DEFAULT '0',
  `created_by` int(11) NOT NULL DEFAULT '0',
  `cr_rate_to_lbp` decimal(10,5) NOT NULL DEFAULT '0.00000',
  `auto_sum` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `credit_notes_details`
--

CREATE TABLE IF NOT EXISTS `credit_notes_details` (
`id` int(11) NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT '0',
  `price` decimal(20,10) DEFAULT '0.0000000000',
  `deleted` tinyint(1) DEFAULT '0',
  `credit_note_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE IF NOT EXISTS `currencies` (
`id` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `symbole` varchar(10) DEFAULT NULL,
  `disabled` tinyint(1) DEFAULT NULL,
  `system_default` tinyint(1) DEFAULT NULL,
  `rate_to_system_default` decimal(20,10) DEFAULT NULL,
  `default_vat` tinyint(1) NOT NULL DEFAULT '0',
  `pi_decimal` int(11) NOT NULL DEFAULT '0',
  `second_currency` tinyint(1) NOT NULL DEFAULT '0',
  `sales_invoice_round` int(11) NOT NULL DEFAULT '0',
  `sales_invoice_decimal` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`id`, `name`, `symbole`, `disabled`, `system_default`, `rate_to_system_default`, `default_vat`, `pi_decimal`, `second_currency`, `sales_invoice_round`, `sales_invoice_decimal`) VALUES
(1, 'US Dollar', 'USD', 0, 1, '1500.0000000000', 0, 2, 0, 0, 0),
(2, 'Lebanese Pound', 'LBP', 1, 0, '1.0000000000', 1, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE IF NOT EXISTS `customers` (
`id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `phone` varchar(100) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `balance` decimal(10,2) DEFAULT '0.00',
  `address` varchar(300) DEFAULT NULL,
  `customer_type` int(11) NOT NULL DEFAULT '1',
  `starting_balance` decimal(20,5) NOT NULL DEFAULT '0.00000',
  `mof` varchar(50) NOT NULL DEFAULT '-',
  `discount` decimal(20,5) NOT NULL DEFAULT '0.00000',
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `city_id` int(11) NOT NULL DEFAULT '0',
  `dob` datetime DEFAULT NULL,
  `id_type` int(11) NOT NULL DEFAULT '0',
  `id_expiry` datetime DEFAULT NULL,
  `id_nb` varchar(100) DEFAULT NULL,
  `cob` int(11) NOT NULL DEFAULT '0',
  `identity_pic_1` varchar(200) NOT NULL DEFAULT '0',
  `identity_pic_2` varchar(200) NOT NULL DEFAULT '0',
  `coi` int(11) NOT NULL DEFAULT '0',
  `created_by` int(11) NOT NULL DEFAULT '0',
  `account_nb` varchar(50) NOT NULL DEFAULT '0',
  `note` varchar(500) NOT NULL DEFAULT '',
  `reference_id` varchar(50) DEFAULT '0',
  `synced` tinyint(1) NOT NULL DEFAULT '0',
  `omt_account` tinyint(1) NOT NULL DEFAULT '0',
  `address_area` varchar(500) DEFAULT NULL,
  `address_city` varchar(500) DEFAULT NULL,
  `address_street` varchar(500) DEFAULT NULL,
  `address_floor` varchar(500) DEFAULT NULL,
  `address_note` varchar(500) DEFAULT NULL,
  `address_building` varchar(500) DEFAULT NULL,
  `omt_bal_usd` decimal(20,5) NOT NULL DEFAULT '0.00000',
  `omt_bal_lbp` decimal(20,5) NOT NULL DEFAULT '0.00000',
  `omt_bal_need_update` tinyint(1) NOT NULL DEFAULT '0',
  `email` varchar(200) DEFAULT NULL,
  `company` varchar(200) DEFAULT NULL,
  `connected_to_supplier` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `customers_logs`
--

CREATE TABLE IF NOT EXISTS `customers_logs` (
`id` int(11) NOT NULL,
  `action_type` varchar(10) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `description` text,
  `action_date` datetime DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `customers_types`
--

CREATE TABLE IF NOT EXISTS `customers_types` (
`id` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `customers_types`
--

INSERT INTO `customers_types` (`id`, `name`, `enabled`) VALUES
(1, 'Retail', 1),
(2, 'Wholesale', 1),
(3, 'Second Wholesale', 1);

-- --------------------------------------------------------

--
-- Table structure for table `customer_balance`
--

CREATE TABLE IF NOT EXISTS `customer_balance` (
`id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `vendor_id` int(11) DEFAULT NULL,
  `store_id` int(11) DEFAULT NULL,
  `balance` decimal(10,2) DEFAULT NULL,
  `balance_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `cashbox_id` int(11) NOT NULL DEFAULT '0',
  `payment_method` int(11) NOT NULL DEFAULT '1',
  `value_date` datetime DEFAULT NULL,
  `currency_id` int(11) DEFAULT NULL,
  `rate` decimal(20,10) NOT NULL DEFAULT '1.0000000000',
  `note` text,
  `bank_id` int(11) NOT NULL DEFAULT '0',
  `reference_nb` varchar(100) DEFAULT NULL,
  `owner` varchar(100) DEFAULT NULL,
  `picture` varchar(200) DEFAULT NULL,
  `voucher` varchar(100) DEFAULT NULL,
  `payment_done` tinyint(1) NOT NULL DEFAULT '0',
  `usd_to_lbp` decimal(10,5) NOT NULL DEFAULT '0.00000',
  `cash_in_usd` decimal(20,5) NOT NULL DEFAULT '0.00000',
  `cash_in_lbp` decimal(20,5) NOT NULL DEFAULT '0.00000',
  `returned_usd` decimal(20,5) NOT NULL DEFAULT '0.00000',
  `returned_lbp` decimal(20,5) NOT NULL DEFAULT '0.00000',
  `to_returned_usd` decimal(20,5) NOT NULL DEFAULT '0.00000',
  `to_returned_lbp` decimal(20,5) NOT NULL DEFAULT '0.00000',
  `p_rate` decimal(20,5) NOT NULL DEFAULT '0.00000'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `debit_notes`
--

CREATE TABLE IF NOT EXISTS `debit_notes` (
`id` int(11) NOT NULL,
  `creation_date` datetime DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `debit_payment_method` int(11) DEFAULT NULL,
  `debit_value` decimal(20,5) DEFAULT '0.00000',
  `deleted` tinyint(1) DEFAULT '0',
  `store_id` int(11) DEFAULT NULL,
  `note` text,
  `bank_id` int(11) NOT NULL DEFAULT '0',
  `reference` varchar(100) DEFAULT NULL,
  `payment_owner` varchar(100) DEFAULT NULL,
  `currency_rate` decimal(20,5) DEFAULT NULL,
  `payment_currency` int(11) NOT NULL DEFAULT '2',
  `p_invoice` int(11) NOT NULL DEFAULT '0',
  `created_by` int(11) NOT NULL DEFAULT '0',
  `on_the_fly` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `debit_notes_details`
--

CREATE TABLE IF NOT EXISTS `debit_notes_details` (
`id` int(11) NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT '0',
  `price` decimal(20,10) DEFAULT '0.0000000000',
  `deleted` tinyint(1) DEFAULT '0',
  `debit_note_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `delivery_companies`
--

CREATE TABLE IF NOT EXISTS `delivery_companies` (
`id` int(11) NOT NULL,
  `name` text,
  `phone` text,
  `starting_balance` decimal(20,5) NOT NULL DEFAULT '0.00000',
  `contact_name` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `delivery_logs`
--

CREATE TABLE IF NOT EXISTS `delivery_logs` (
`id` int(11) NOT NULL,
  `old_status` int(11) NOT NULL,
  `new_status` int(11) NOT NULL,
  `description` text NOT NULL,
  `creation_date` datetime DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '0',
  `delivery_order_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `delivery_orders`
--

CREATE TABLE IF NOT EXISTS `delivery_orders` (
`id` int(11) NOT NULL,
  `creation_date` datetime DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '0',
  `invoice_id` int(11) NOT NULL DEFAULT '0',
  `delivery_company_id` int(11) NOT NULL DEFAULT '0',
  `status_id` int(11) NOT NULL DEFAULT '1',
  `delivery_fees` decimal(20,5) NOT NULL DEFAULT '0.00000',
  `received_date` datetime DEFAULT NULL,
  `comment` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `delivery_payments`
--

CREATE TABLE IF NOT EXISTS `delivery_payments` (
`id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL DEFAULT '0',
  `amount` decimal(20,5) NOT NULL DEFAULT '0.00000',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `note` text CHARACTER SET utf8,
  `created_by` int(11) NOT NULL DEFAULT '0',
  `creation_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `delivery_status`
--

CREATE TABLE IF NOT EXISTS `delivery_status` (
`id` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `discounts`
--

CREATE TABLE IF NOT EXISTS `discounts` (
`id` int(11) NOT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `disabled` tinyint(1) DEFAULT '0',
  `creation_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `discount_value` decimal(20,5) DEFAULT '0.00000',
  `category_parent_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `discount_name` varchar(200) DEFAULT NULL,
  `group_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `discounts_details`
--

CREATE TABLE IF NOT EXISTS `discounts_details` (
`id` int(11) NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `store_id` int(11) DEFAULT NULL,
  `discount_id` int(11) DEFAULT NULL,
  `discount_value` decimal(20,5) DEFAULT '0.00000'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `districts`
--

CREATE TABLE IF NOT EXISTS `districts` (
`id` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `area_id` int(11) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=28 ;

--
-- Dumping data for table `districts`
--

INSERT INTO `districts` (`id`, `name`, `area_id`) VALUES
(1, 'Koura', 1),
(2, 'Nabatiyeh', 2),
(3, 'Danniyeh', 1),
(4, 'Baalbeck', 3),
(5, 'Sour', 2),
(6, 'Akkar', 1),
(7, 'Batroun', 1),
(8, 'Zahleh', 3),
(9, 'Saida', 2),
(10, 'Zgharta', 1),
(11, 'Kesrwan', 4),
(12, 'Beirut', 5),
(13, 'Zahrani', 2),
(14, 'Jbeil', 4),
(15, 'Aley', 4),
(16, 'Rachaya', 3),
(17, 'Metn', 4),
(18, 'Chouf', 4),
(19, 'Baabda', 4),
(20, 'Bekaa West', 3),
(21, 'Bint Jbeil', 2),
(22, 'Jezzine', 2),
(23, 'Tripoli', 1),
(24, 'Hermel', 3),
(25, 'Marjeyoun', 2),
(26, 'Becharreh', 1),
(27, 'Hasbaya', 2);

-- --------------------------------------------------------

--
-- Table structure for table `email_smtp`
--

CREATE TABLE IF NOT EXISTS `email_smtp` (
`id` int(11) NOT NULL,
  `email_send_from` varchar(200) DEFAULT NULL,
  `email_reply_to` varchar(200) DEFAULT NULL,
  `subject` varchar(200) DEFAULT NULL,
  `hostname` varchar(200) DEFAULT NULL,
  `port` int(11) DEFAULT NULL,
  `username` varchar(200) DEFAULT NULL,
  `password` varchar(200) DEFAULT NULL,
  `active` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE IF NOT EXISTS `employees` (
`id` int(11) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `phone_number` varchar(50) DEFAULT NULL,
  `deleted_emp` tinyint(1) DEFAULT '0',
  `start_date` datetime DEFAULT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `note` text,
  `basic_salary` decimal(20,5) NOT NULL DEFAULT '0.00000',
  `paycut_per_hour` decimal(20,5) NOT NULL DEFAULT '0.00000',
  `overtime_per_hour` decimal(20,5) NOT NULL DEFAULT '0.00000',
  `hours_per_day` decimal(20,2) NOT NULL DEFAULT '0.00',
  `also_customer_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `first_name`, `last_name`, `address`, `phone_number`, `deleted_emp`, `start_date`, `middle_name`, `email`, `note`, `basic_salary`, `paycut_per_hour`, `overtime_per_hour`, `hours_per_day`, `also_customer_id`) VALUES
(1, 'mustafa', 'nasif', '', '', 1, '2022-10-02 00:00:00', '', '', '', '200.00000', '0.00000', '0.00000', '12.00', 9);

-- --------------------------------------------------------

--
-- Table structure for table `employees_attendance`
--

CREATE TABLE IF NOT EXISTS `employees_attendance` (
`id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `start_date_time` datetime DEFAULT NULL,
  `end_date_time` datetime DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE IF NOT EXISTS `expenses` (
`id` int(11) NOT NULL,
  `type_id` int(11) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `value` decimal(10,2) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `store_id` int(11) DEFAULT NULL,
  `vendor_id` int(11) DEFAULT NULL,
  `cashbox_id` int(11) DEFAULT NULL,
  `cash_usd_to_return` decimal(10,0) NOT NULL DEFAULT '0',
  `cash_lbp_to_return` decimal(10,0) NOT NULL DEFAULT '0',
  `returned_cash_lbp` decimal(10,0) NOT NULL DEFAULT '0',
  `returned_cash_usd` decimal(10,0) NOT NULL DEFAULT '0',
  `cash_lbp_in` decimal(10,0) NOT NULL DEFAULT '0',
  `cash_usd_in` decimal(10,0) NOT NULL DEFAULT '0',
  `rate` decimal(10,0) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `expenses_types`
--

CREATE TABLE IF NOT EXISTS `expenses_types` (
`id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `expenses_types`
--

INSERT INTO `expenses_types` (`id`, `name`, `deleted`) VALUES
(1, 'locked', 0);

-- --------------------------------------------------------

--
-- Table structure for table `garage_clients_cards`
--

CREATE TABLE IF NOT EXISTS `garage_clients_cards` (
`id` int(11) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `date_time_in` datetime DEFAULT NULL,
  `code` varchar(100) DEFAULT NULL,
  `company` varchar(200) DEFAULT NULL,
  `car_type` varchar(50) DEFAULT NULL,
  `model` varchar(50) DEFAULT NULL,
  `color` int(11) DEFAULT NULL,
  `odometer` varchar(200) DEFAULT NULL,
  `car` varchar(200) DEFAULT NULL,
  `date_time_out` datetime DEFAULT NULL,
  `problem_description` varchar(500) DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `creation_date` datetime DEFAULT NULL,
  `invoice_id` int(11) NOT NULL DEFAULT '0',
  `oil_changed_date` datetime DEFAULT NULL,
  `oil_next_change_date` datetime DEFAULT NULL,
  `oil_note` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `global_logs`
--

CREATE TABLE IF NOT EXISTS `global_logs` (
`id` int(11) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `related_to_item_id` int(11) DEFAULT NULL,
  `description` text,
  `log_type` int(11) DEFAULT NULL COMMENT '1 --> items',
  `other_info` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `history_of_store_items`
--

CREATE TABLE IF NOT EXISTS `history_of_store_items` (
`id` int(11) NOT NULL,
  `action` varchar(20) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `store_id` int(11) DEFAULT NULL,
  `warehouse_id` int(11) DEFAULT NULL,
  `date_of_action` datetime DEFAULT NULL,
  `qty` decimal(20,5) DEFAULT NULL,
  `cost` decimal(20,2) DEFAULT NULL,
  `vat` int(11) DEFAULT NULL,
  `price` decimal(20,2) DEFAULT NULL,
  `discount` int(11) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `item_category` int(11) DEFAULT NULL,
  `supplier_reference` int(11) DEFAULT NULL,
  `barcode` varchar(100) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `history_prices`
--

CREATE TABLE IF NOT EXISTS `history_prices` (
`id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `old_cost` decimal(20,5) DEFAULT NULL,
  `new_cost` decimal(20,5) DEFAULT NULL,
  `old_qty` decimal(20,5) DEFAULT NULL,
  `added_qty` decimal(20,5) DEFAULT NULL,
  `source` varchar(10) DEFAULT NULL,
  `receive_stock_id` varchar(10) DEFAULT NULL,
  `free_qty` decimal(20,5) NOT NULL DEFAULT '0.00000'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `history_quantities`
--

CREATE TABLE IF NOT EXISTS `history_quantities` (
`id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `qty` decimal(20,5) DEFAULT NULL,
  `store_id` int(11) DEFAULT NULL,
  `qty_afer_action` decimal(20,5) DEFAULT NULL,
  `source` varchar(20) NOT NULL DEFAULT 'manual',
  `is_pos_transfer` tinyint(1) NOT NULL DEFAULT '0',
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `identities_type`
--

CREATE TABLE IF NOT EXISTS `identities_type` (
`id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `expired_required` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `international_calls_balance`
--

CREATE TABLE IF NOT EXISTS `international_calls_balance` (
`id` int(11) NOT NULL,
  `value` decimal(20,5) DEFAULT '0.00000',
  `date` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `description` varchar(400) DEFAULT NULL,
  `rate` decimal(10,2) NOT NULL DEFAULT '0.00',
  `current_balance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `current_rate` decimal(10,5) NOT NULL DEFAULT '0.00000'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE IF NOT EXISTS `invoices` (
`id` int(11) NOT NULL,
  `creation_date` datetime DEFAULT NULL,
  `closed` tinyint(1) DEFAULT '0',
  `customer_id` int(11) DEFAULT NULL,
  `store_id` int(11) DEFAULT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `total_value` decimal(20,5) DEFAULT NULL,
  `invoice_discount` decimal(20,5) DEFAULT '0.00000',
  `closed_date` datetime DEFAULT NULL,
  `auto_closed` tinyint(1) DEFAULT '0',
  `total_profit` decimal(20,5) DEFAULT '0.00000',
  `profit_after_discount` decimal(20,5) DEFAULT NULL,
  `synced` tinyint(1) DEFAULT '0',
  `cashbox_id` int(11) DEFAULT NULL,
  `payment_method` int(11) DEFAULT NULL,
  `total_profit_limited` decimal(20,5) DEFAULT '0.00000',
  `total_value_limited` decimal(20,5) DEFAULT '0.00000',
  `due_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `discount_note` text,
  `payment_note` varchar(500) DEFAULT '',
  `sales_person` int(11) NOT NULL DEFAULT '0',
  `total_vat_value` decimal(20,5) NOT NULL DEFAULT '0.00000',
  `vat_value` decimal(20,5) NOT NULL DEFAULT '0.00000',
  `official` tinyint(1) NOT NULL DEFAULT '1',
  `invoice_nb_official` int(11) NOT NULL DEFAULT '0',
  `delivery` tinyint(1) DEFAULT '0',
  `delivery_cost` decimal(20,5) NOT NULL DEFAULT '0.00000',
  `delivery_ref` varchar(50) NOT NULL DEFAULT '0',
  `delivery_done` tinyint(1) NOT NULL DEFAULT '0',
  `other_branche` int(11) NOT NULL DEFAULT '0',
  `invoice_id` int(11) NOT NULL DEFAULT '0',
  `invoice_customer_referrer` int(11) NOT NULL DEFAULT '0',
  `cashback_value` decimal(20,5) NOT NULL DEFAULT '0.00000',
  `currency_id` int(11) NOT NULL DEFAULT '0',
  `to_curreny_id` int(11) NOT NULL DEFAULT '0',
  `rate` decimal(20,5) NOT NULL DEFAULT '0.00000',
  `recurring` int(11) NOT NULL DEFAULT '0',
  `recurring_parent_id` int(11) NOT NULL DEFAULT '0',
  `sent_to_telegram` tinyint(1) NOT NULL DEFAULT '0',
  `sent_by_email` tinyint(1) NOT NULL DEFAULT '0',
  `tax` decimal(20,5) NOT NULL DEFAULT '0.00000',
  `freight` decimal(20,5) NOT NULL DEFAULT '0.00000',
  `cashbox_info` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_items`
--

CREATE TABLE IF NOT EXISTS `invoice_items` (
`id` int(11) NOT NULL,
  `invoice_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `qty` decimal(20,5) DEFAULT NULL,
  `buying_cost` decimal(20,5) DEFAULT NULL,
  `vat` tinyint(1) DEFAULT NULL,
  `selling_price` decimal(20,5) DEFAULT NULL,
  `discount` decimal(20,5) DEFAULT NULL,
  `final_cost_vat_qty` decimal(20,5) DEFAULT NULL,
  `final_price_disc_qty` decimal(20,5) DEFAULT NULL,
  `profit` decimal(20,5) DEFAULT NULL,
  `vat_value` decimal(20,5) DEFAULT NULL,
  `description` text,
  `mobile_transfer_credits` int(11) DEFAULT NULL,
  `custom_item` tinyint(1) DEFAULT '0',
  `price_after_manual_discount` decimal(20,2) DEFAULT '0.00',
  `synced` tinyint(1) DEFAULT '0',
  `user_role` int(11) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `official` tinyint(1) DEFAULT '1',
  `item_change_cashbox` int(11) NOT NULL DEFAULT '0',
  `item_change_date` datetime DEFAULT NULL,
  `additional_description` varchar(200) DEFAULT NULL,
  `pos_discounted` tinyint(1) NOT NULL DEFAULT '0',
  `international_calls` tinyint(1) NOT NULL DEFAULT '0',
  `base_usd_price` decimal(10,5) NOT NULL DEFAULT '0.00000',
  `average_rate` decimal(10,5) NOT NULL DEFAULT '0.00000',
  `added_new` tinyint(1) NOT NULL DEFAULT '0',
  `rate_on_sale` decimal(20,5) NOT NULL DEFAULT '0.00000',
  `fixed_rate` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE IF NOT EXISTS `items` (
`id` int(11) NOT NULL,
  `description` varchar(500) DEFAULT NULL,
  `item_category` int(11) DEFAULT NULL,
  `buying_cost` decimal(20,5) DEFAULT NULL,
  `selling_price` decimal(20,5) DEFAULT NULL,
  `barcode` varchar(100) DEFAULT NULL,
  `supplier_reference` int(11) DEFAULT NULL,
  `discount` decimal(20,5) DEFAULT '0.00000' COMMENT 'By Percentage',
  `vat` tinyint(1) DEFAULT '0',
  `lack_warning` int(11) DEFAULT '0',
  `vendor_quantity_access` tinyint(1) DEFAULT '0',
  `instant_report` int(11) DEFAULT '0',
  `unit_measure_id` int(11) DEFAULT '0',
  `color_id` varchar(20) DEFAULT '0',
  `size_id` int(11) DEFAULT '0',
  `deleted` tinyint(1) DEFAULT '0',
  `item_alias` varchar(100) DEFAULT NULL,
  `is_composite` tinyint(1) DEFAULT '0',
  `wholesale_price` decimal(20,5) DEFAULT '0.00000',
  `supplier_ref` varchar(100) DEFAULT NULL,
  `is_official` tinyint(1) DEFAULT '1',
  `color_text_id` int(11) NOT NULL DEFAULT '1',
  `creation_date` datetime DEFAULT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `sku_code` varchar(45) DEFAULT NULL,
  `second_barcode` varchar(100) DEFAULT NULL,
  `material_id` int(11) NOT NULL DEFAULT '1',
  `vat_on_sale` tinyint(1) NOT NULL DEFAULT '0',
  `upsilon_id` varchar(200) NOT NULL DEFAULT '-',
  `item_group` int(11) NOT NULL DEFAULT '0',
  `another_description` varchar(500) DEFAULT NULL,
  `show_on_pos` tinyint(1) NOT NULL DEFAULT '1',
  `depend_on_var_price` tinyint(1) NOT NULL DEFAULT '0',
  `weight` decimal(20,5) NOT NULL DEFAULT '0.00000',
  `fixed_price` tinyint(1) NOT NULL DEFAULT '0',
  `fixed_price_value` decimal(20,5) NOT NULL DEFAULT '0.00000',
  `second_wholesale_price` decimal(20,5) NOT NULL DEFAULT '0.00000',
  `complex_item_id` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `items_categories`
--

CREATE TABLE IF NOT EXISTS `items_categories` (
`id` int(11) NOT NULL,
  `description` varchar(500) DEFAULT NULL,
  `parent` int(11) DEFAULT '1',
  `deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `items_categories`
--

INSERT INTO `items_categories` (`id`, `description`, `parent`, `deleted`) VALUES
(1, 'General', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `items_categories_parents`
--

CREATE TABLE IF NOT EXISTS `items_categories_parents` (
`id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `deny_delete` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `items_categories_parents`
--

INSERT INTO `items_categories_parents` (`id`, `name`, `deleted`, `deny_delete`) VALUES
(1, 'General', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `items_composite`
--

CREATE TABLE IF NOT EXISTS `items_composite` (
`id` int(11) NOT NULL,
  `composite_item_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `qty` decimal(20,5) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `items_composite`
--

INSERT INTO `items_composite` (`id`, `composite_item_id`, `item_id`, `qty`) VALUES
(2, 4, 3, '12.00000');

-- --------------------------------------------------------

--
-- Table structure for table `items_images`
--

CREATE TABLE IF NOT EXISTS `items_images` (
`id` int(11) NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `creation_date` datetime DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `items_parents`
--

CREATE TABLE IF NOT EXISTS `items_parents` (
`id` int(11) NOT NULL,
  `item_name` varchar(100) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `login_history`
--

CREATE TABLE IF NOT EXISTS `login_history` (
`id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `login_out` int(11) DEFAULT NULL COMMENT '1 -> in\n2 -> out',
  `closed_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `materials`
--

CREATE TABLE IF NOT EXISTS `materials` (
`id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `materials`
--

INSERT INTO `materials` (`id`, `name`, `deleted`) VALUES
(1, 'None', 0);

-- --------------------------------------------------------

--
-- Table structure for table `mobile_credits_history`
--

CREATE TABLE IF NOT EXISTS `mobile_credits_history` (
`id` int(11) NOT NULL,
  `invoice_item_id` int(11) DEFAULT NULL,
  `device_id` int(11) DEFAULT NULL,
  `qty` decimal(20,2) DEFAULT NULL,
  `sms_fees` decimal(20,5) DEFAULT '0.00000',
  `additional_fees` decimal(20,5) NOT NULL DEFAULT '0.00000',
  `returned` tinyint(1) NOT NULL DEFAULT '0',
  `returned_date` datetime DEFAULT NULL,
  `returned_by` int(11) NOT NULL DEFAULT '0',
  `created_by` int(11) NOT NULL DEFAULT '0',
  `creation_date` datetime DEFAULT NULL,
  `returned_fees` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mobile_devices`
--

CREATE TABLE IF NOT EXISTS `mobile_devices` (
`id` int(11) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  `balance` decimal(10,2) DEFAULT NULL,
  `operator_id` int(11) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `store_id` int(11) DEFAULT NULL,
  `expiry_date` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `mobile_devices`
--

INSERT INTO `mobile_devices` (`id`, `description`, `balance`, `operator_id`, `deleted`, `store_id`, `expiry_date`) VALUES
(1, '79123408 alfa balance', '90.51', 1, 0, 1, '2022-10-13 00:00:00'),
(2, '03858160 mtc number balance', '8.82', 2, 0, 1, '2022-11-11 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `mobile_dollars`
--

CREATE TABLE IF NOT EXISTS `mobile_dollars` (
`id` int(11) NOT NULL,
  `qty` decimal(20,2) DEFAULT '0.00',
  `price` decimal(10,2) DEFAULT NULL,
  `operator_id` int(11) DEFAULT NULL,
  `deleted` int(11) DEFAULT '0',
  `sms_cost` decimal(10,2) DEFAULT '0.00',
  `credit_cost` decimal(10,2) DEFAULT '0.00',
  `days` int(11) DEFAULT '0' COMMENT 'days == 0 ; => charge dollars\ndays == 1 ; => charge days',
  `return_credits` decimal(10,2) DEFAULT '0.00',
  `description` varchar(500) DEFAULT NULL,
  `type` int(11) DEFAULT '0',
  `item_related` int(11) DEFAULT '0' COMMENT 'item related in order to reduce the number from stock',
  `no_sms_fees` tinyint(1) NOT NULL DEFAULT '0',
  `alias` varchar(500) DEFAULT NULL,
  `store_recharge` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `mobile_dollars`
--

INSERT INTO `mobile_dollars` (`id`, `qty`, `price`, `operator_id`, `deleted`, `sms_cost`, `credit_cost`, `days`, `return_credits`, `description`, `type`, `item_related`, `no_sms_fees`, `alias`, `store_recharge`) VALUES
(1, '1.00', '1.25', 1, 0, '0.14', '0.80', 0, '0.00', '', 0, 0, 0, '', 0),
(2, '1.00', '1.25', 2, 0, '0.14', '0.80', 0, '0.00', '', 0, 0, 0, '', 0),
(3, '1.30', '2.50', 1, 0, '0.00', '1.00', 30, '6.00', 'Alfa 1 Month', 0, 291, 0, NULL, 0),
(4, '1.30', '2.50', 2, 0, '0.00', '1.00', 30, '6.00', 'touch mtc 1 month', 0, 289, 0, NULL, 0),
(5, '1.00', '15.00', 1, 0, '0.00', '2.00', 360, '72.50', 'ALFA 1 year days', 0, 293, 0, NULL, 0),
(6, '10.00', '12.00', 1, 0, '0.14', '8.50', 0, '0.00', 'alfa 10$', 0, 0, 0, 'alfa 10$', 0),
(7, '10.00', '12.00', 2, 0, '0.14', '8.00', 0, '0.00', 'Touch  10$', 0, 0, 0, 'Touch  10$', 0);

-- --------------------------------------------------------

--
-- Table structure for table `mobile_international_calls`
--

CREATE TABLE IF NOT EXISTS `mobile_international_calls` (
`id` int(11) NOT NULL,
  `country_id` int(11) DEFAULT NULL,
  `rate` decimal(20,5) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mobile_line_recharge`
--

CREATE TABLE IF NOT EXISTS `mobile_line_recharge` (
`id` int(11) NOT NULL,
  `device_id` int(11) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `cashbox_id` int(11) DEFAULT '0',
  `operator_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT NULL,
  `package_id` int(11) DEFAULT NULL,
  `cost` int(11) NOT NULL DEFAULT '0',
  `from_date` datetime DEFAULT NULL,
  `to_date` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `mobile_line_recharge`
--

INSERT INTO `mobile_line_recharge` (`id`, `device_id`, `create_date`, `cashbox_id`, `operator_id`, `item_id`, `deleted`, `package_id`, `cost`, `from_date`, `to_date`) VALUES
(1, 1, '2022-10-01 23:24:42', 9, 7, 291, 1, 3, 4, '2022-10-13 00:00:00', '2022-11-12 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `mobile_operators`
--

CREATE TABLE IF NOT EXISTS `mobile_operators` (
`id` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `disabled` tinyint(1) DEFAULT '0',
  `base_color` varchar(20) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `mobile_operators`
--

INSERT INTO `mobile_operators` (`id`, `name`, `disabled`, `base_color`) VALUES
(1, 'Alfa', 0, '#ee3124'),
(2, 'Touch', 0, '#009fb5');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE IF NOT EXISTS `payments` (
`id` int(11) NOT NULL,
  `invoice_id` int(11) DEFAULT NULL,
  `date_of_pay` datetime DEFAULT NULL,
  `value` decimal(10,2) DEFAULT NULL,
  `vendor_id` int(11) DEFAULT NULL,
  `store_id` int(11) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE IF NOT EXISTS `payment_methods` (
`id` int(11) NOT NULL,
  `method_name` varchar(45) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`id`, `method_name`, `deleted`) VALUES
(1, 'Cash', 0),
(2, 'Cheque', 0),
(3, 'Credit Card', 0),
(4, 'Not Paid', 0);

-- --------------------------------------------------------

--
-- Table structure for table `payment_status`
--

CREATE TABLE IF NOT EXISTS `payment_status` (
`id` int(11) NOT NULL,
  `status_name` varchar(45) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `payment_status`
--

INSERT INTO `payment_status` (`id`, `status_name`, `deleted`) VALUES
(1, 'Paid', 0),
(2, 'Not Paid', 0),
(3, 'Partial Payment', 0);

-- --------------------------------------------------------

--
-- Table structure for table `payroll_attendance_details`
--

CREATE TABLE IF NOT EXISTS `payroll_attendance_details` (
`id` int(11) NOT NULL,
  `dtime` datetime DEFAULT NULL,
  `extra_hours` decimal(20,2) DEFAULT '0.00',
  `minus_hours` decimal(20,2) DEFAULT '0.00',
  `overtime` decimal(20,2) DEFAULT '0.00',
  `paycut` decimal(20,2) DEFAULT '0.00',
  `is_official_holliday` tinyint(1) DEFAULT '0',
  `is_custom_holliday` tinyint(1) DEFAULT '0',
  `working_hours_per_day` decimal(10,2) DEFAULT '0.00',
  `payroll_details_id` int(11) NOT NULL DEFAULT '0',
  `lock_auto_edit` tinyint(1) NOT NULL DEFAULT '0',
  `over_time` decimal(10,2) NOT NULL DEFAULT '0.00',
  `pay_cut` decimal(10,2) NOT NULL DEFAULT '0.00',
  `salary_per_hour` decimal(10,5) NOT NULL DEFAULT '0.00000',
  `hours_per_day` decimal(10,1) NOT NULL DEFAULT '0.0',
  `net_to_pay` decimal(10,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `payroll_details`
--

CREATE TABLE IF NOT EXISTS `payroll_details` (
`id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `basic_salary` decimal(20,2) DEFAULT NULL,
  `total_overtime` decimal(20,2) DEFAULT NULL,
  `total_paycut` decimal(20,2) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `submit_to_balance` tinyint(1) NOT NULL DEFAULT '0',
  `submit_to_balance_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `pending_invoices`
--

CREATE TABLE IF NOT EXISTS `pending_invoices` (
`id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `creation_date` datetime NOT NULL,
  `deleted` tinyint(1) NOT NULL,
  `data` text NOT NULL,
  `note` text,
  `location` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `phones`
--

CREATE TABLE IF NOT EXISTS `phones` (
`id` int(11) NOT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `phone_type` varchar(10) DEFAULT NULL,
  `phone_number` varchar(45) DEFAULT NULL,
  `country_code` varchar(45) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `phones`
--

INSERT INTO `phones` (`id`, `supplier_id`, `phone_type`, `phone_number`, `country_code`) VALUES
(1, 6, 'phone', '', 'ccc'),
(2, 7, 'phone', '', 'ccc'),
(3, 8, 'phone', '', 'ccc'),
(4, 9, 'phone', '', 'ccc'),
(5, 10, 'phone', '', 'ccc'),
(6, 11, 'phone', '', 'ccc'),
(7, 12, 'phone', '', 'ccc'),
(8, 13, 'phone', '', 'ccc');

-- --------------------------------------------------------

--
-- Table structure for table `plugin_deliveries`
--

CREATE TABLE IF NOT EXISTS `plugin_deliveries` (
`id` int(11) NOT NULL,
  `creation_date` datetime DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `plugin_delivery_details`
--

CREATE TABLE IF NOT EXISTS `plugin_delivery_details` (
`id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `sending_date` datetime DEFAULT NULL,
  `wb_number` varchar(20) DEFAULT NULL,
  `collection_value` decimal(20,5) DEFAULT NULL,
  `delivery_charge` decimal(20,5) DEFAULT NULL,
  `net_amout` decimal(20,5) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `delivery_id` int(11) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `paid_supplier` tinyint(1) NOT NULL DEFAULT '0',
  `paid_date` datetime DEFAULT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `pickapp_share` decimal(20,5) NOT NULL DEFAULT '0.00000',
  `our_share` decimal(20,5) NOT NULL DEFAULT '0.00000',
  `customer_name` varchar(200) DEFAULT NULL,
  `customer_address` varchar(200) DEFAULT NULL,
  `customer_phone` varchar(50) DEFAULT NULL,
  `print_group` int(11) NOT NULL DEFAULT '-1',
  `note` varchar(100) DEFAULT NULL,
  `hide` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `pos_monitor`
--

CREATE TABLE IF NOT EXISTS `pos_monitor` (
`id` int(11) NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `cashbox_id` int(11) NOT NULL DEFAULT '0',
  `qty` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `queries`
--

CREATE TABLE IF NOT EXISTS `queries` (
`id` int(11) NOT NULL,
  `qry` text,
  `transaction_date` datetime NOT NULL,
  `target_store` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `queries_synced`
--

CREATE TABLE IF NOT EXISTS `queries_synced` (
`id` int(11) NOT NULL,
  `qry_id` int(11) DEFAULT NULL,
  `store_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `quotations`
--

CREATE TABLE IF NOT EXISTS `quotations` (
`id` int(11) NOT NULL,
  `creation_date` datetime DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `store_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `note` text,
  `sub_total` decimal(20,5) NOT NULL,
  `discount` decimal(20,5) NOT NULL,
  `vat` decimal(20,5) NOT NULL,
  `total` decimal(20,5) NOT NULL,
  `profit` decimal(20,5) DEFAULT NULL,
  `rate` decimal(20,5) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `expiery_date` datetime DEFAULT NULL,
  `converted_to_invoice` tinyint(1) DEFAULT '0',
  `invoice_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `quotations_log`
--

CREATE TABLE IF NOT EXISTS `quotations_log` (
`id` int(11) NOT NULL,
  `creation_date` datetime NOT NULL,
  `log` text NOT NULL,
  `quotation_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `quotation_details`
--

CREATE TABLE IF NOT EXISTS `quotation_details` (
`id` int(11) NOT NULL,
  `quotation_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `additional_description` text CHARACTER SET utf8,
  `buying_cost` decimal(20,5) NOT NULL,
  `qty` decimal(20,5) DEFAULT NULL,
  `selling_price` decimal(20,5) NOT NULL,
  `discount` decimal(20,5) DEFAULT NULL,
  `vat` int(11) NOT NULL,
  `vat_value` decimal(20,5) DEFAULT NULL,
  `final_price` decimal(20,5) NOT NULL,
  `final_cost` decimal(20,5) NOT NULL,
  `profit` decimal(20,5) NOT NULL,
  `deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `receive_stock`
--

CREATE TABLE IF NOT EXISTS `receive_stock` (
`id` int(11) NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `location_id` int(11) DEFAULT NULL,
  `qty` decimal(20,5) DEFAULT NULL,
  `cost` decimal(20,5) DEFAULT NULL,
  `receive_stock_invoice_id` int(11) DEFAULT NULL,
  `vat` tinyint(1) DEFAULT '0',
  `supplier_ref` varchar(100) DEFAULT NULL,
  `discount_percentage` decimal(20,5) DEFAULT '0.00000',
  `returned_debit` decimal(20,5) NOT NULL DEFAULT '0.00000',
  `discount_after_vat` decimal(20,5) NOT NULL DEFAULT '0.00000',
  `discount_percentage_2` decimal(20,5) NOT NULL DEFAULT '0.00000',
  `pi_more_value` decimal(10,5) NOT NULL DEFAULT '0.00000',
  `fqty` decimal(20,5) NOT NULL DEFAULT '0.00000',
  `expiry_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `receive_stock_invoices`
--

CREATE TABLE IF NOT EXISTS `receive_stock_invoices` (
`id` int(11) NOT NULL,
  `creation_date` datetime DEFAULT NULL,
  `receive_invoice_date` datetime DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `delivery_date` datetime DEFAULT NULL,
  `subtotal` decimal(20,5) DEFAULT NULL,
  `discount` decimal(20,5) DEFAULT NULL,
  `total` decimal(20,5) DEFAULT NULL,
  `paid_status` tinyint(1) DEFAULT '0',
  `invoice_tax` decimal(20,5) DEFAULT NULL,
  `moved_to_stock` tinyint(1) DEFAULT '0',
  `invoice_reference` varchar(100) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `auto_filled` tinyint(1) DEFAULT '0',
  `currency_id` int(11) NOT NULL DEFAULT '1',
  `cur_rate` decimal(20,10) NOT NULL DEFAULT '1.0000000000',
  `pi_picture_name` varchar(100) DEFAULT NULL,
  `payment_id` int(11) NOT NULL DEFAULT '0',
  `vat` decimal(10,2) NOT NULL DEFAULT '1.11',
  `transferred` tinyint(1) NOT NULL DEFAULT '0',
  `created_by` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `receive_stock_invoice_fees`
--

CREATE TABLE IF NOT EXISTS `receive_stock_invoice_fees` (
`id` int(11) NOT NULL,
  `value` decimal(20,10) DEFAULT NULL,
  `type_id` int(11) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT NULL,
  `note` varchar(100) DEFAULT '',
  `pi_id` int(11) NOT NULL DEFAULT '0',
  `apply_to_pi` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `receive_stock_invoice_fees_types`
--

CREATE TABLE IF NOT EXISTS `receive_stock_invoice_fees_types` (
`id` int(11) NOT NULL,
  `description` varchar(50) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT NULL,
  `discount_fees` int(11) DEFAULT '1' COMMENT '2 discount 1 fees'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `receive_stock_invoice_fees_types`
--

INSERT INTO `receive_stock_invoice_fees_types` (`id`, `description`, `deleted`, `discount_fees`) VALUES
(1, 'DISCOUNT', 0, 2),
(2, 'VAT', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `returned_purchases`
--

CREATE TABLE IF NOT EXISTS `returned_purchases` (
`id` int(11) NOT NULL,
  `invoice_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `returned_by_vendor_id` int(11) DEFAULT NULL,
  `returned_to_store_id` int(11) DEFAULT NULL,
  `return_date` datetime DEFAULT NULL,
  `qty` decimal(20,5) DEFAULT NULL,
  `custom_item` tinyint(1) DEFAULT '0',
  `mobile_transfer_credits_id` int(11) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  `buying_cost` decimal(20,2) DEFAULT NULL,
  `vat` tinyint(1) DEFAULT NULL,
  `selling_price` decimal(20,2) DEFAULT NULL,
  `discount` decimal(20,2) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `cashbox_id` int(11) DEFAULT NULL,
  `old_cashbox_id` int(11) DEFAULT NULL,
  `vat_value` decimal(20,2) NOT NULL DEFAULT '0.00',
  `only_return` tinyint(1) NOT NULL DEFAULT '1',
  `on_account_id` int(11) NOT NULL DEFAULT '0',
  `invoice_item_id` int(11) NOT NULL DEFAULT '0',
  `invoice_deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(32) NOT NULL,
  `access` int(10) unsigned DEFAULT NULL,
  `data` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
`id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `value` text
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=384 ;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `name`, `value`) VALUES
(1, 'vat', '0'),
(2, 'payment_full', '1'),
(3, 'payment_credit_card', '1'),
(4, 'payment_later', '1'),
(5, 'cash_box', '50000'),
(6, 'default_currency_symbol', 'USD'),
(7, 'auto_print', '2'),
(8, 'show_expiry_date_alert_before_days', '2592000'),
(9, 'mobile_shop', '1'),
(10, 'enable_debts', '1'),
(11, 'shop_name', 'Store'),
(12, 'pos_path', 'upsilon-pos_8'),
(13, 'printer_name', NULL),
(14, 'language', 'en'),
(15, 'activation_code', 'Dt7esr6v1W4fdKMtzqA/N7C9rQDRzSgjn05XgTUM1lrOPbyOgNu3vJhBzYsUH377m+qkFT6CpPGRMZZGc++CsbskQd1lsLcncpNSvMfhUE5XDkKO1dJYG6tWvleaRoYJ|BXegCi3uuDtYMDuQ/SapCrPhmxTlUvNwjRkTk0knrzI='),
(16, 'serial_number', ''),
(17, 'url_request', 'http://license.u-psilon.com/lic/license_request.php'),
(18, 'customer_id', ''),
(19, 'quick_access_col', '0'),
(20, 'plu_prefix', '27'),
(21, 'dump_path', 'C:\\\\xampplite\\\\mysql\\\\bin\\\\mysqldump.exe'),
(22, 'database_name', 'sales_and_stock_usd'),
(23, 'backup_path', 'C:\\backup'),
(24, 'phone_nb', 'Phone'),
(25, 'address', 'Address'),
(26, 'main_drive', 'c'),
(27, 'enable_wholasale', '1'),
(28, 'barcode_prefix', 'prefix'),
(29, 'encode_and_online_backup', '1'),
(30, 'leave_encoded_backups_nb', '20'),
(31, 'enable_customer_display', '0'),
(32, 'customer_display_name', 'com3'),
(33, 'available_in_stock_include_plu', '0'),
(34, 'printer_barcode_name', 'ub'),
(35, 'barcode_page_size_name', '1.57*0.90'),
(36, 'invoice_footer', 'Thank You'),
(37, 'end_of_day_report', '1'),
(38, 'return_report', '1'),
(39, 'show_arabic', '0'),
(40, 'auto_update_items_qty_in_admin', '1'),
(41, 'ask_print_for_gift', '0'),
(42, 'version', '1.0.183'),
(43, 'enable_discount_password', '0'),
(44, 'discount_password', '123'),
(45, 'enable_invoice_discount', '1'),
(46, 'logo_to_print_name', ''),
(47, 'hide_shop_name_on_invoice', '0'),
(48, 'a4_printer', '0'),
(49, 'invoice_pdf_address', '-'),
(50, 'invoice_pdf_phones', '-'),
(51, 'invoice_pdf_MOF', NULL),
(52, 'payment_cheque', '0'),
(53, 'invoice_pdf_show_shopname', '1'),
(54, 'expiry_interval_days', '30'),
(55, 'pos_all_items_hide_on_add_to_invoice', '0'),
(56, 'sound_play', '1'),
(57, 'invoice_logo', ''),
(58, 'upsilon_contact_info', '<b>UPSILON POS Software Support:</b><br/>Youssef +961 3 520 699'),
(62, 'barcode_paper_id', '1'),
(63, 'inventory_items_hide_col', '9,11'),
(64, 'number_of_decimal_points', '2'),
(65, 'global_admin_is_local', '1'),
(66, 'garage_car_plugin', '0'),
(67, 'oil_change_date_interval_by_day', '90'),
(68, 'invoice_receipt_format', '1'),
(69, 'delivery_items_plugin', '0'),
(70, 'show_size_and_color_on_pos', '0'),
(71, 'round_val', '2'),
(73, 'enable_only_return_password', '0'),
(74, 'only_return_password', '123'),
(75, 'set_official', '0'),
(76, 'apply_vat_sales_item', '0'),
(77, 'enable_edit_invoice_password', '0'),
(78, 'edit_invoice_password', '1235'),
(79, 'ptype', '0'),
(80, 'footer_direction', 'rtl'),
(81, 'footer_text', 'Text'),
(82, 'international_call_rate', '1500'),
(83, 'enable_edit_invoice_even_new_item_is_added', '1'),
(84, 'enable_edit_for_another_shift', '1'),
(85, 'set_password_for_cashbox_and_report_pos', '-1'),
(86, 'show_currency_in_report', '0'),
(89, 'enable_advanced_customer_info', '0'),
(90, 'advanced_customer_info_img_width', '200'),
(92, 'phone_number_format', '00 000 000'),
(93, 'identity_number_format', 'AAAA AAAA AAAA AAAA AAAA AAAA'),
(94, 'row_discounted_color_in_report', '#c1e4fc'),
(95, 'barcode_link_enable', '0'),
(96, 'show_barcode_receipt', '0'),
(97, 'show_currency_on_receipt', '1'),
(98, 'item_another_description_lang', '0'),
(99, 'enable_delete_customer_on_pos', '0'),
(109, 'force_select_sales_persion_on_pos', '0'),
(110, 'daily_report_type', '1'),
(111, 'force_price_equal_cost', '0'),
(112, 'cash_from_client_as_first_payment', '0'),
(117, 'hide_zero_invoices', '0'),
(118, 'enable_cashin_out', '0'),
(120, 'sms_username', ''),
(121, 'sms_password', ''),
(122, 'sender_id', ''),
(123, 'sms_provider', 'https://www.smscenter.marketing/send.php'),
(124, 'a4_print_style', '4'),
(125, 'vat_included', '1'),
(126, 'sms_balance', '0'),
(127, 'pos_sales_person_boxes', '0'),
(128, 'pos_manual_print', '1'),
(129, 'plu_first_part_start', '0'),
(130, 'plu_first_part_end', '7'),
(131, 'plu_second_part_start', '7'),
(132, 'plu_second_part_end', '12'),
(175, 'printer_receipt_copies', '1'),
(177, 'enable_delivery_pos', '0'),
(178, 'invoice_show_only_for_sold_pos', '0'),
(180, 'report_sales_hide_colums', '13,14'),
(181, 'disable_international_calls', '0'),
(183, 'last_backup', '2023-01-18 17:58:17'),
(184, 'backup_diffrence', '18000'),
(234, 'add_vat_on_invoice_only_on_receipt', '0'),
(236, 'enable_qz_print', '0'),
(237, 'enable_phd', 'C4CA4238A0B923820DCC509A6FB'),
(238, 'enable_keyboard_open_cashdrawer', '1'),
(239, 'print_barcode_in_browser', '1'),
(240, 'print_barcode_in_browser_paper_width', '47'),
(241, 'print_barcode_in_browser_paper_height', '25'),
(245, 'enable_customers_referrer', '0'),
(246, 'cashback_discount_limit', '0'),
(247, 'cashback_discounted_percentage', '0'),
(248, 'cashback_not_discounted_percentage', '0'),
(249, 'enable_change_invoice_date', '0'),
(275, 'barcode_type', 'CODE128C'),
(276, 'barcode_auto_increment', '1'),
(277, 'pos_print_as_lbp_if_usd', '0'),
(278, 'enable_omt_url', 'http://localhost/UPSILON-Wu/index.php?r=dashboard'),
(279, 'enable_omt', '0'),
(281, 'payment_receipt_style', 'a5'),
(282, 'enable_operations', '1'),
(283, 'base_price_rate_to_usd', '1500'),
(284, 'new_price_rate_to_lbp', '1560'),
(285, 'price_var_round', '1'),
(286, 'enable_price_var', '0'),
(287, 'print_invoice_lbp', '0'),
(288, 'attendance_marge_minus_in_minutes', '0'),
(289, 'attendance_marge_plus_in_minutes', '0'),
(290, 'all_invoices_hide_col', ''),
(292, 'enable_keyboard_open_cashdrawer_keyboard', '88'),
(293, 'pos_hide_cash_payment_if_customer_is_selected', '0'),
(294, 'enable_sales_person', '1'),
(295, 'show_var_price', '0'),
(296, 'enable_delete_or_return_if_base_operator_is_closed', '0'),
(297, 'disable_edit_invoice_in_pos', '0'),
(298, 'international_calls_source_rate', '1516'),
(299, 'international_calls_balance', '0'),
(300, 'auto_sync', '0'),
(301, 'additional_credit_transfer_sms_cost', '0.05'),
(302, 'sync_only_cost', '0'),
(303, 'suppliers_complex_stmt', '1'),
(305, 'pos_detect_cost', '0'),
(306, 'plu_weight', '0'),
(307, 'print_invoice_lbp_format', 'print_invoice_8cm'),
(308, 'enable_percentage_price_round', '0'),
(309, 'hide_rate', '0'),
(310, 'usd_but_show_lbp_priority', '1'),
(311, 'usdlbp_rate', '63000'),
(313, 'telegram_enable', '0'),
(314, 'telegram_token', NULL),
(315, 'telegram_chatid', NULL),
(316, 'email_invoice_enable', '0'),
(317, 'last_payment_date', '2023-01-01'),
(319, 'vat_nb', ''),
(320, 'time_zone', 'Asia/Beirut'),
(321, 'pos_all_items_ajax', '0'),
(322, 'disable_edit_and_delete_invoice_older_than', '99999999999'),
(344, 'edit_invoice_block_return_money', '0'),
(345, 'show_usd_in_invoice', '0'),
(366, 'pos_payment_default_zero_values', '1'),
(367, 'pos_auto_discount', '0'),
(368, 'enable_change_invoice_client_cash_debts', '0'),
(369, 'switch_to_new_telegram', '0'),
(370, 'enable_cashbox_transactions', '1'),
(371, 'pos_on_edit_invoice_disable_new_item_input', '0'),
(372, 'pos_branches_transfers', '0'),
(373, 'enable_authorization_code', '0'),
(377, 'print_a4_pdf_version', '0'),
(378, 'all_quotations_hide_col', ''),
(379, 'pos_force_money_in_equal_total_amount', '0'),
(380, 'default_print_paper', '0'),
(381, 'alfa_sms_fees', '0.14'),
(382, 'touch_sms_fees', '0.16'),
(383, 'is_demo_version', '0');

-- --------------------------------------------------------

--
-- Table structure for table `settings_log`
--

CREATE TABLE IF NOT EXISTS `settings_log` (
`id` int(11) NOT NULL,
  `settings_id` int(11) NOT NULL,
  `settings_name` text NOT NULL,
  `created_by` int(11) NOT NULL,
  `creation_date` datetime NOT NULL,
  `old_value` text NOT NULL,
  `new_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `shortcuts`
--

CREATE TABLE IF NOT EXISTS `shortcuts` (
`id` int(11) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `creation_date` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT '0',
  `derived_from_group` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `shortcuts_details`
--

CREATE TABLE IF NOT EXISTS `shortcuts_details` (
`id` int(11) NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `shortcut_id` int(11) DEFAULT NULL,
  `qty` decimal(20,2) DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `shrinkages`
--

CREATE TABLE IF NOT EXISTS `shrinkages` (
`id` int(11) NOT NULL,
  `creation_date` datetime DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `store_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `shrinkages_details`
--

CREATE TABLE IF NOT EXISTS `shrinkages_details` (
`id` int(11) NOT NULL,
  `shrinkages_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `old_stock_qty` decimal(20,5) DEFAULT NULL,
  `new_stock_qty` decimal(20,5) DEFAULT NULL,
  `checked_date` datetime DEFAULT NULL,
  `avg_cost` decimal(20,5) NOT NULL DEFAULT '0.00000',
  `excluded` tinyint(1) NOT NULL DEFAULT '0',
  `scanner_qty` decimal(20,5) NOT NULL DEFAULT '0.00000'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `shrinkage_failed`
--

CREATE TABLE IF NOT EXISTS `shrinkage_failed` (
`id` int(11) NOT NULL,
  `shrinkage_id` int(11) DEFAULT NULL,
  `item_barcode` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sms`
--

CREATE TABLE IF NOT EXISTS `sms` (
`id` int(11) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `body` text,
  `deleted` tinyint(1) DEFAULT '0',
  `start_date` datetime DEFAULT NULL,
  `pause` tinyint(1) DEFAULT '0',
  `creation_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sms_details`
--

CREATE TABLE IF NOT EXISTS `sms_details` (
`id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `status_id` int(11) DEFAULT '0',
  `sent_date` datetime DEFAULT NULL,
  `sms_id` int(11) DEFAULT NULL,
  `excluded` tinyint(1) NOT NULL DEFAULT '0',
  `sms_price` decimal(20,5) NOT NULL DEFAULT '0.00000'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sms_status`
--

CREATE TABLE IF NOT EXISTS `sms_status` (
`id` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `sms_status`
--

INSERT INTO `sms_status` (`id`, `name`) VALUES
(1, 'pending'),
(2, 'sent'),
(3, 'failed');

-- --------------------------------------------------------

--
-- Table structure for table `sms_test`
--

CREATE TABLE IF NOT EXISTS `sms_test` (
`id` int(11) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `sms_price` decimal(20,5) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `sms_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `stock_movement`
--

CREATE TABLE IF NOT EXISTS `stock_movement` (
`id` int(11) NOT NULL,
  `stock_date` datetime DEFAULT NULL,
  `store_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `stock_movement_details`
--

CREATE TABLE IF NOT EXISTS `stock_movement_details` (
`id` int(11) NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `qty` decimal(20,5) DEFAULT NULL,
  `stock_movement_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `store`
--

CREATE TABLE IF NOT EXISTS `store` (
`id` int(11) NOT NULL,
  `location` varchar(200) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `db` varchar(45) DEFAULT NULL,
  `username` varchar(45) NOT NULL,
  `password` varchar(45) NOT NULL,
  `primary_db` tinyint(1) NOT NULL DEFAULT '0',
  `ip_address` varchar(50) NOT NULL DEFAULT 'localhost',
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  `warehouse` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `store`
--

INSERT INTO `store` (`id`, `location`, `name`, `db`, `username`, `password`, `primary_db`, `ip_address`, `visible`, `warehouse`) VALUES
(1, 'Store', 'Store', '', 'ucef', '123456', 0, 'localhost', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `store_items`
--

CREATE TABLE IF NOT EXISTS `store_items` (
`id` int(11) NOT NULL,
  `store_id` int(11) DEFAULT '1',
  `item_id` int(11) DEFAULT NULL,
  `quantity` decimal(20,5) DEFAULT NULL,
  `on_pos_interface` tinyint(1) DEFAULT '0',
  `expiry_date` datetime DEFAULT NULL,
  `pos_order` int(11) NOT NULL DEFAULT '0',
  `pos_col_users` varchar(100) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE IF NOT EXISTS `suppliers` (
`id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `contact_name` varchar(50) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `starting_balance` decimal(20,5) NOT NULL DEFAULT '0.00000',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `email` varchar(50) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `debit_credit` int(11) NOT NULL DEFAULT '0',
  `usd_starting_balance` decimal(20,5) NOT NULL DEFAULT '0.00000',
  `lbp_starting_balance` decimal(20,5) NOT NULL DEFAULT '0.00000'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `country_id`, `contact_name`, `address`, `user_id`, `starting_balance`, `deleted`, `email`, `creation_date`, `debit_credit`, `usd_starting_balance`, `lbp_starting_balance`) VALUES
(1, 'None', 1, NULL, NULL, NULL, '0.00000', 0, NULL, '2020-05-31 23:57:34', 0, '0.00000', '0.00000');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers_payments`
--

CREATE TABLE IF NOT EXISTS `suppliers_payments` (
`id` int(11) NOT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `payment_value` decimal(20,5) DEFAULT NULL,
  `payment_date` datetime DEFAULT NULL,
  `payment_method` int(11) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `payment_note` text,
  `invoice_order_id` int(11) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `bank_id` int(11) NOT NULL DEFAULT '0',
  `reference` varchar(100) DEFAULT NULL,
  `payment_owner` varchar(100) DEFAULT NULL,
  `currency_rate` decimal(20,10) NOT NULL DEFAULT '1.0000000000',
  `payment_currency` int(11) NOT NULL DEFAULT '2',
  `payment_picture` varchar(100) DEFAULT NULL,
  `voucher` varchar(100) DEFAULT NULL,
  `payment_done` tinyint(1) NOT NULL DEFAULT '0',
  `cashbox_id` int(11) NOT NULL DEFAULT '0',
  `usd_to_lbp` decimal(10,5) DEFAULT '0.00000',
  `cash_in_usd` decimal(20,5) NOT NULL DEFAULT '0.00000',
  `cash_in_lbp` decimal(20,5) NOT NULL DEFAULT '0.00000',
  `returned_usd` decimal(20,5) NOT NULL DEFAULT '0.00000',
  `returned_lbp` decimal(20,5) NOT NULL DEFAULT '0.00000',
  `to_returned_usd` decimal(20,5) NOT NULL DEFAULT '0.00000',
  `to_returned_lbp` decimal(20,5) NOT NULL DEFAULT '0.00000'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tasks_daily`
--

CREATE TABLE IF NOT EXISTS `tasks_daily` (
`id` int(11) NOT NULL,
  `description` varchar(5000) DEFAULT NULL,
  `due_date` datetime DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `status` int(11) DEFAULT '0',
  `customer_id` int(11) DEFAULT '0',
  `created_by` int(11) DEFAULT '0',
  `deleted` tinyint(1) DEFAULT '0',
  `remind_before` int(11) NOT NULL DEFAULT '1',
  `note_to` int(11) NOT NULL DEFAULT '0',
  `leaved_note` varchar(200) DEFAULT NULL,
  `shift_id` int(11) NOT NULL DEFAULT '0',
  `set_done_shift_id` int(11) NOT NULL DEFAULT '0',
  `fav` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `tasks_daily`
--

INSERT INTO `tasks_daily` (`id`, `description`, `due_date`, `creation_date`, `status`, `customer_id`, `created_by`, `deleted`, `remind_before`, `note_to`, `leaved_note`, `shift_id`, `set_done_shift_id`, `fav`) VALUES
(1, 'please bring papers for printer', '2020-07-01 00:00:00', '2020-07-01 15:12:09', 2, 0, 1, 0, 0, 2, 'okay done', 0, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `telegram`
--

CREATE TABLE IF NOT EXISTS `telegram` (
`id` int(11) NOT NULL,
  `message` text,
  `creation_date` datetime DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0',
  `telegram_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `telegram_accounts`
--

CREATE TABLE IF NOT EXISTS `telegram_accounts` (
`id` int(11) NOT NULL,
  `chat_id` varchar(100) DEFAULT NULL,
  `token` varchar(200) DEFAULT NULL,
  `enabled` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `transfers`
--

CREATE TABLE IF NOT EXISTS `transfers` (
`id` int(11) NOT NULL,
  `description` varchar(200) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `to_store_id` int(11) DEFAULT NULL,
  `from_store_id` int(11) DEFAULT NULL,
  `submit_transfer` tinyint(1) NOT NULL DEFAULT '0',
  `synced_destination` tinyint(1) NOT NULL DEFAULT '0',
  `synced_source` tinyint(1) NOT NULL DEFAULT '0',
  `pricing_type` int(11) NOT NULL DEFAULT '1',
  `confirmed_by_receiver_id` int(11) NOT NULL DEFAULT '0',
  `confirmed_by_receiver_id_date` datetime DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `transfers_details`
--

CREATE TABLE IF NOT EXISTS `transfers_details` (
`id` int(11) NOT NULL,
  `transfer_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `added_date` datetime DEFAULT NULL,
  `qty` decimal(20,5) DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `unit_price` decimal(20,5) NOT NULL DEFAULT '0.00000',
  `unit_cost` decimal(20,5) NOT NULL DEFAULT '0.00000'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `transfers_new`
--

CREATE TABLE IF NOT EXISTS `transfers_new` (
`id` int(11) NOT NULL,
  `status` int(11) DEFAULT '0' COMMENT '0-> pending\n1-> in progress\n3-> executed\n4-> failed',
  `creation_date` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `from_store_id` int(11) DEFAULT '0',
  `to_store_id` varchar(45) DEFAULT '0',
  `item_id` varchar(45) DEFAULT NULL,
  `transfer_qty` varchar(45) DEFAULT NULL,
  `confirmed_by` int(11) NOT NULL DEFAULT '0',
  `cancelled_by` int(11) NOT NULL DEFAULT '0',
  `confirmed_date` datetime DEFAULT NULL,
  `cancelled_date` datetime DEFAULT NULL,
  `unit_price` decimal(20,5) NOT NULL DEFAULT '0.00000',
  `unit_cost` decimal(20,5) NOT NULL DEFAULT '0.00000'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `unique_items`
--

CREATE TABLE IF NOT EXISTS `unique_items` (
`id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `code1` text NOT NULL,
  `code2` text NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `creation_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `is_defined` tinyint(1) NOT NULL,
  `deleted` tinyint(1) NOT NULL,
  `note` text,
  `invoice_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `unique_items_history`
--

CREATE TABLE IF NOT EXISTS `unique_items_history` (
`unique_id` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `code1` text NOT NULL,
  `code2` text NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `creation_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `is_defined` tinyint(1) NOT NULL,
  `deleted` tinyint(1) NOT NULL,
  `note` text,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `unit_color`
--

CREATE TABLE IF NOT EXISTS `unit_color` (
`id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8214 ;

--
-- Dumping data for table `unit_color`
--

INSERT INTO `unit_color` (`id`, `name`, `deleted`) VALUES
(1, 'Unknown', 0),
(3408, 'BLACK', 0),
(3409, 'BEIGEKHAKI', 0),
(3410, 'NAVY', 0),
(3411, 'RUSTCOPPER', 0),
(3412, 'GRAY', 0),
(3425, 'BEIGE', 0),
(3426, 'NATURAL', 0),
(3427, 'TURQ/AQUA', 0),
(3429, 'DARK RED', 0),
(3430, 'LT/PAS PUR', 0),
(3433, 'LT/PASBLUE', 0),
(3435, 'RED', 0),
(3437, 'ORANGE', 0),
(3440, 'LT/PASPINK', 0),
(3442, 'LT BEIGE', 0),
(3445, 'ASSORTED', 0),
(3449, 'WHITE', 0),
(3450, 'DARK BROWN', 0),
(3457, 'BROWN', 0),
(3458, 'BLUE', 0),
(3465, 'DARK BEIGE', 0),
(3467, 'MED BEIGE', 0),
(3469, 'MEDIUM RED', 0),
(3472, 'CHARCOAL', 0),
(3478, 'SILVER', 0),
(3492, 'DARK GRAY', 0),
(3494, 'MED BROWN', 0),
(3496, 'LT/PAS BWN', 0),
(3503, 'LT/PAS GRY', 0),
(3534, 'PURPLE', 0),
(3536, '', 0),
(3542, 'MED PINK', 0),
(3554, 'MED GRAY', 0),
(3568, 'DARK PINK', 0),
(3569, 'LT/PAS YEL', 0),
(3579, 'BRIGHT PUR', 0),
(3586, 'BRIGHTBLUE', 0),
(3591, 'GOLD', 0),
(3606, 'YELLOW', 0),
(3614, 'PINK', 0),
(3635, 'BRIGHT RED', 0),
(3649, 'MED YELLOW', 0),
(3650, 'LT/PAS GRN', 0),
(3687, 'MED PURPLE', 0),
(3688, 'BUCK', 0),
(3722, 'BRNOVERFLW', 0),
(3726, 'GREEN', 0),
(3741, 'DARK BLUE', 0),
(3758, 'REDOVERFLW', 0),
(3759, 'DARKORANGE', 0),
(3768, 'WINE', 0),
(3775, 'BRGHT PINK', 0),
(3783, 'MED BLUE', 0),
(3803, 'DARK GREEN', 0),
(3845, 'BGEOVERFLW', 0),
(3856, 'DARKPURPLE', 0),
(3871, 'MED ORANGE', 0),
(3905, 'BRGHTORANG', 0),
(3928, 'TAUPE', 0),
(3930, 'MEDIUN RED', 0),
(3984, 'LT/PAS RED', 0),
(4036, 'BRIGHT GRN', 0),
(4039, 'NO COLOR', 0),
(4578, 'MED GREEN', 0),
(4706, 'PINKOVERFL', 0),
(4730, 'LT/PAS ORG', 0),
(5040, 'BRGHT YELL', 0),
(5338, 'DARKYELLOW', 0),
(6283, 'ESPRESSO', 0),
(6775, 'HONEY', 0),
(6908, 'KHAKI', 0),
(7486, 'PINKOVERPL', 0),
(8213, 'SADDLE', 0);

-- --------------------------------------------------------

--
-- Table structure for table `unit_measure`
--

CREATE TABLE IF NOT EXISTS `unit_measure` (
`id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `unit_measure`
--

INSERT INTO `unit_measure` (`id`, `name`, `deleted`) VALUES
(1, 'Unit', 0),
(2, 'Metre', 0),
(3, 'Pound', 0),
(4, 'Kg', 0),
(5, 'Sack', 0),
(6, NULL, 0),
(7, 'wh', 0);

-- --------------------------------------------------------

--
-- Table structure for table `unit_size`
--

CREATE TABLE IF NOT EXISTS `unit_size` (
`id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7814 ;

--
-- Dumping data for table `unit_size`
--

INSERT INTO `unit_size` (`id`, `name`, `deleted`) VALUES
(1, 'NO SIZE', 0),
(17, 'OSFA', 0),
(33, 'S M', 0),
(42, 'OSFA REG', 0),
(43, '', 0),
(55, 'XL', 0),
(85, 'M', 0),
(86, 'LARGE', 0),
(103, '34X16', 0),
(104, 'M/L', 0),
(105, 'S/M', 0),
(147, 'XL M', 0),
(181, 'S', 0),
(272, '2XL', 0),
(630, 'XS', 0),
(631, 'MED', 0),
(635, '18MOS', 0),
(637, '24MOS', 0),
(642, '6-12 MOS', 0),
(646, 'SML', 0),
(648, '12MOS', 0),
(650, '6-9MOS', 0),
(664, '3-6MOS', 0),
(673, '4-6(SMALL)', 0),
(688, '0-6 MOS', 0),
(690, '5', 0),
(692, '2', 0),
(694, '3', 0),
(703, '4', 0),
(709, '6', 0),
(713, '7', 0),
(741, '6X', 0),
(742, '4T REG', 0),
(743, '2T REG', 0),
(744, '3T REG', 0),
(748, '2T', 0),
(801, 'XLRG', 0),
(819, 'LRG', 0),
(824, 'MEDIUM S/S', 0),
(825, 'SMALL S/S', 0),
(845, 'XLARGE S/S', 0),
(963, '36 A', 0),
(966, 'NEWBORN', 0),
(985, '9MOS', 0),
(986, '6MOS', 0),
(1063, '3MOS', 0),
(1070, '8', 0),
(1104, 'MEDUIM', 0),
(1111, 'XSML', 0),
(1186, '3T SLIM', 0),
(1204, '18', 0),
(1207, '9-11', 0),
(1255, '7-8', 0),
(1276, '4T SLIM', 0),
(1327, '28', 0),
(1328, '24', 0),
(1331, 'N/A', 0),
(1350, '20', 0),
(1403, '4AVGREGMED', 0),
(1561, '14', 0),
(1628, '1 M', 0),
(1646, '12', 0),
(1703, '20 REG/MED', 0),
(1714, '0-9 MOS', 0),
(1722, '12-14(LRG)', 0),
(1909, '7 REG', 0),
(1918, '8 REG/MED', 0),
(1945, '18 REG/MED', 0),
(1971, '16', 0),
(1993, '1', 0),
(2034, '14 REG', 0),
(2052, '2AVGREGMED', 0),
(2072, 'L', 0),
(2162, '14 REG/MED', 0),
(2163, '16 REG/MED', 0),
(2170, '10 REG/MED', 0),
(2173, '10 SLIM', 0),
(2183, '10', 0),
(2249, '2 1/2 M', 0),
(2356, '10 REG', 0),
(2380, '12 REG', 0),
(2462, '4 1/2 W', 0),
(2467, '13 M', 0),
(2536, '10 HUSKY', 0),
(2537, '14 HUSKY', 0),
(2538, '18 HUSKY', 0),
(2615, '12 M', 0),
(2622, '7AVGREGMED', 0),
(2650, '11 M', 0),
(2696, '6 REG/MED', 0),
(2702, '16 REG', 0),
(2703, '8 REG', 0),
(2758, '2 M', 0),
(2759, '8 M', 0),
(2842, '16 SLIM', 0),
(2846, '12 HUSKY', 0),
(2847, '12 SLIM', 0),
(2876, '3 M', 0),
(2904, '4 M', 0),
(2931, '12 PRETEEN', 0),
(2955, '9 M', 0),
(2979, '16 PRETEEN', 0),
(2987, '13.5 M', 0),
(3026, '10AV/MD/RG', 0),
(3027, '10 M', 0),
(3091, '2 1/2 W', 0),
(3130, '16 HUSKY', 0),
(3147, '5.5 M', 0),
(3148, '7 M', 0),
(3168, '12-18 MOS', 0),
(3196, '13', 0),
(3198, '18-24 MOS', 0),
(3210, '6.5 M', 0),
(3219, '8 PRETEEN', 0),
(3303, '5 M', 0),
(3307, '12.5 W', 0),
(3353, '3 W', 0),
(3379, '12 REG/MED', 0),
(3410, 'LARGE S/S', 0),
(3431, 'XXLRG S/S', 0),
(3441, 'MED MEDREG', 0),
(3451, 'SML 29-30', 0),
(3453, 'XLRG 29-30', 0),
(3455, 'MED 29-30', 0),
(3456, 'LRG 29-30', 0),
(3524, '3XLRG S/S', 0),
(3535, '48 BIG', 0),
(3540, '4XLRGTL/LG', 0),
(3573, '2XLRGTL/LG', 0),
(3575, 'L-SHT LEG', 0),
(3623, '22X37"', 0),
(3664, 'XSMLMEDREG', 0),
(3712, 'XLRG TL/LG', 0),
(3729, '2XLRG M/R', 0),
(3741, 'XLRGMEDREG', 0),
(3792, '15.5X34-35', 0),
(3793, '15.5X32-33', 0),
(3825, '34 REG', 0),
(3837, '34X30', 0),
(3839, '14.5X32-33', 0),
(3968, '18.5X34-35', 0),
(3971, '16.5X34-35', 0),
(4044, '30 REG', 0),
(4103, '17.5X32-33', 0),
(4107, '40X30', 0),
(4108, '30X30', 0),
(4130, '36X32', 0),
(4142, '34X34', 0),
(4152, '10 1/2', 0),
(4156, '15X34-35"', 0),
(4170, '36X34', 0),
(4174, '30X32', 0),
(4176, '33X30', 0),
(4180, '48X32', 0),
(4181, '48X34', 0),
(4188, '18X34-35"', 0),
(4192, '17X34-35"', 0),
(4194, '16X34-35"', 0),
(4200, '16X32-33"', 0),
(4218, 'SML MEDREG', 0),
(4255, '42X30', 0),
(4269, '32X30', 0),
(4274, '14X32-33"', 0),
(4303, 'LRG MEDREG', 0),
(4307, '3XLRGTL/LG', 0),
(4323, '42X36', 0),
(4393, '40 REG', 0),
(4417, '33 REG', 0),
(4482, '38X30', 0),
(4514, '32X32', 0),
(4517, '33X32', 0),
(4565, '38 R/M37.5', 0),
(4599, '42 R/M37.5', 0),
(4639, '46X32', 0),
(4652, 'LRG TL/LG', 0),
(4707, '54 REG', 0),
(4715, '40 R/M37.5', 0),
(4726, '9', 0),
(4730, '8.5 M', 0),
(4732, '11.5 M', 0),
(4736, '7.5 M', 0),
(4738, '7 W', 0),
(4759, '10.5 M', 0),
(4769, '9.5 W', 0),
(4793, '10 D', 0),
(4799, '10.5 D', 0),
(4802, '7.5', 0),
(4804, '15 M', 0),
(4816, '9.5 3E', 0),
(4817, '9 D', 0),
(4823, '10 W', 0),
(4824, '11 W', 0),
(4839, '13 C', 0),
(4848, '11', 0),
(4859, '8 D', 0),
(4860, '12 D', 0),
(4862, '8.5 D', 0),
(4864, '7.5 D', 0),
(4866, '7.5 2E', 0),
(4938, 'SQUARE 18', 0),
(4999, 'PET/MED', 0),
(5216, '10 T/L', 0),
(5218, '2 S', 0),
(5226, '18 S', 0),
(5228, '8 S', 0),
(5241, '6 AV/MD/RG', 0),
(5242, '2 AV/MD/RG', 0),
(5243, '14AV/MD/RG', 0),
(5278, 'PET/LRG', 0),
(5292, '12 T/L', 0),
(5335, 'PET/SMALL', 0),
(5361, '12AV/MD/RG', 0),
(5362, '18AV/MD/RG', 0),
(5546, 'XL T/L', 0),
(5600, 'B OR MED', 0),
(5660, 'A OR SMALL', 0),
(5669, '1X', 0),
(5718, 'XL SM', 0),
(5753, 'S SM', 0),
(5937, '6 S', 0),
(6086, '14 S', 0),
(6103, '2 T/L', 0),
(6105, '8 AV/MD/RG', 0),
(6539, '3X', 0),
(6550, 'X LARGE', 0),
(6626, 'SQUARE 30', 0),
(6685, '20W', 0),
(6750, 'XS NO CUP', 0),
(6752, '24W', 0),
(6753, '18W', 0),
(6854, '22W', 0),
(6889, '16AV/MD/RG', 0),
(6893, '4 AV/MD/RG', 0),
(6894, '4 SS', 0),
(6910, '12 P', 0),
(6923, 'S P', 0),
(6936, '18W AVER', 0),
(6945, '0', 0),
(6960, '14 P', 0),
(7187, '14W AVER', 0),
(7188, '20W AVER', 0),
(7201, '28AV/MD/RG', 0),
(7291, '8 P', 0),
(7367, '10 P', 0),
(7398, 'M-T', 0),
(7442, '0 P', 0),
(7495, '20 T/L', 0),
(7499, '14 T/L', 0),
(7557, 'A', 0),
(7558, 'D', 0),
(7559, 'B', 0),
(7562, '1X-2X', 0),
(7563, '3X-4X', 0),
(7571, 'A-B', 0),
(7575, 'E-F', 0),
(7580, 'TALL', 0),
(7592, 'C', 0),
(7658, 'E', 0),
(7676, 'L T/L', 0),
(7677, 'M T/L', 0),
(7679, 'S T/L', 0),
(7697, '6 M', 0),
(7739, '8.5 B', 0),
(7742, '6.5 B', 0),
(7747, '8.5', 0),
(7755, '5.5', 0),
(7757, '6.5', 0),
(7783, '9 B', 0),
(7813, '7 B', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
`id` int(11) NOT NULL,
  `username` varchar(45) DEFAULT NULL,
  `password` varchar(45) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `store_id` int(11) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `name` varchar(200) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `check_key` varchar(100) DEFAULT NULL,
  `demo` tinyint(1) NOT NULL DEFAULT '0',
  `hide_critical_data` tinyint(1) NOT NULL DEFAULT '0',
  `is_safe` tinyint(1) NOT NULL DEFAULT '0',
  `operator_is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `authorization_required` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role_id`, `store_id`, `deleted`, `name`, `creation_date`, `check_key`, `demo`, `hide_critical_data`, `is_safe`, `operator_is_admin`, `authorization_required`) VALUES
(1, 'admin', '123456', 1, 1, 0, NULL, NULL, 'X3RL1PlgQef25aVeclSN', 0, 0, 0, 0, 0),
(2, 'pos', '123456', 2, 1, 0, NULL, NULL, 'f95ZK5Z4hOU2a485iRgM', 0, 0, 0, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users_role`
--

CREATE TABLE IF NOT EXISTS `users_role` (
`id` int(11) NOT NULL,
  `role_description` varchar(45) DEFAULT NULL,
  `delivery` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `users_role`
--

INSERT INTO `users_role` (`id`, `role_description`, `delivery`) VALUES
(1, 'super admin', 0),
(2, 'pos user', 0),
(3, 'fake admin', 0),
(4, 'fake pos', 0),
(5, 'Admin', 1);

-- --------------------------------------------------------

--
-- Table structure for table `warehouse`
--

CREATE TABLE IF NOT EXISTS `warehouse` (
`id` int(11) NOT NULL,
  `location` varchar(200) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `warehouse`
--

INSERT INTO `warehouse` (`id`, `location`, `deleted`) VALUES
(1, 'none', 0);

-- --------------------------------------------------------

--
-- Table structure for table `warehouse_items`
--

CREATE TABLE IF NOT EXISTS `warehouse_items` (
`id` int(11) NOT NULL,
  `warehouse_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `added_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `wasting`
--

CREATE TABLE IF NOT EXISTS `wasting` (
`id` int(11) NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `cost` decimal(20,10) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `note` varchar(200) DEFAULT NULL,
  `cashbox_id` int(11) NOT NULL DEFAULT '0',
  `qty` int(11) NOT NULL DEFAULT '0',
  `price` int(11) NOT NULL DEFAULT '0',
  `clear` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `wasting_types`
--

CREATE TABLE IF NOT EXISTS `wasting_types` (
`id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `wasting_types`
--

INSERT INTO `wasting_types` (`id`, `name`, `deleted`) VALUES
(1, 'general', 0),
(2, 'general', 0),
(3, 'general', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `areas`
--
ALTER TABLE `areas`
 ADD PRIMARY KEY (`id`), ADD KEY `country_area_idx` (`country_id`);

--
-- Indexes for table `authorized_devices`
--
ALTER TABLE `authorized_devices`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `banks`
--
ALTER TABLE `banks`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `barcode_params`
--
ALTER TABLE `barcode_params`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bonuses_and_penalties`
--
ALTER TABLE `bonuses_and_penalties`
 ADD PRIMARY KEY (`id`,`created_by`);

--
-- Indexes for table `cashback`
--
ALTER TABLE `cashback`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cashbox`
--
ALTER TABLE `cashbox`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cashbox_changes_info`
--
ALTER TABLE `cashbox_changes_info`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cashbox_transactions`
--
ALTER TABLE `cashbox_transactions`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cash_details`
--
ALTER TABLE `cash_details`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cash_details_logs`
--
ALTER TABLE `cash_details_logs`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cash_in_out`
--
ALTER TABLE `cash_in_out`
 ADD PRIMARY KEY (`id`), ADD KEY `type_id_cash_rel_idx` (`type_id`);

--
-- Indexes for table `cash_in_out_starting`
--
ALTER TABLE `cash_in_out_starting`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cash_in_out_types`
--
ALTER TABLE `cash_in_out_types`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
 ADD PRIMARY KEY (`id`), ADD KEY `district_city_idx` (`district_id`);

--
-- Indexes for table `collected_customers`
--
ALTER TABLE `collected_customers`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `phone` (`phone`);

--
-- Indexes for table `complex_items`
--
ALTER TABLE `complex_items`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `complex_item_details`
--
ALTER TABLE `complex_item_details`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `complex_item_log`
--
ALTER TABLE `complex_item_log`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `credit_notes`
--
ALTER TABLE `credit_notes`
 ADD PRIMARY KEY (`id`), ADD KEY `customer_is_rel_idx` (`customer_id`), ADD KEY `payment_id_cn_rel_idx` (`credit_payment_method`), ADD KEY `store_id_cn_rel_idx` (`store_id`);

--
-- Indexes for table `credit_notes_details`
--
ALTER TABLE `credit_notes_details`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
 ADD PRIMARY KEY (`id`), ADD KEY `customer_type_rel` (`customer_type`);

--
-- Indexes for table `customers_logs`
--
ALTER TABLE `customers_logs`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers_types`
--
ALTER TABLE `customers_types`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_balance`
--
ALTER TABLE `customer_balance`
 ADD PRIMARY KEY (`id`), ADD KEY `customer_balance_id_idx` (`customer_id`), ADD KEY `customer_balance_vendor_id_idx` (`vendor_id`), ADD KEY `customer_balance_store_id_idx` (`store_id`);

--
-- Indexes for table `debit_notes`
--
ALTER TABLE `debit_notes`
 ADD PRIMARY KEY (`id`), ADD KEY `payment_id_cn_rel_idx` (`debit_payment_method`), ADD KEY `store_id_cn_rel_idx` (`store_id`), ADD KEY `supplier_id_cn_rel0_idx` (`supplier_id`);

--
-- Indexes for table `debit_notes_details`
--
ALTER TABLE `debit_notes_details`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `delivery_companies`
--
ALTER TABLE `delivery_companies`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `delivery_logs`
--
ALTER TABLE `delivery_logs`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `delivery_orders`
--
ALTER TABLE `delivery_orders`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `delivery_payments`
--
ALTER TABLE `delivery_payments`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `delivery_status`
--
ALTER TABLE `delivery_status`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `discounts`
--
ALTER TABLE `discounts`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `discounts_details`
--
ALTER TABLE `discounts_details`
 ADD PRIMARY KEY (`id`), ADD KEY `discount_item_id_idx` (`item_id`), ADD KEY `discount_store_id_idx` (`store_id`), ADD KEY `discount_discount_id_idx` (`discount_id`);

--
-- Indexes for table `districts`
--
ALTER TABLE `districts`
 ADD PRIMARY KEY (`id`), ADD KEY `area_district_idx` (`area_id`);

--
-- Indexes for table `email_smtp`
--
ALTER TABLE `email_smtp`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employees_attendance`
--
ALTER TABLE `employees_attendance`
 ADD PRIMARY KEY (`id`), ADD KEY `employee_id_idx` (`employee_id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
 ADD PRIMARY KEY (`id`), ADD KEY `expenses_types_id_idx` (`type_id`), ADD KEY `expenses_store_id_idx` (`store_id`), ADD KEY `expenses_vendor_id_idx` (`vendor_id`);

--
-- Indexes for table `expenses_types`
--
ALTER TABLE `expenses_types`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `garage_clients_cards`
--
ALTER TABLE `garage_clients_cards`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `global_logs`
--
ALTER TABLE `global_logs`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `history_of_store_items`
--
ALTER TABLE `history_of_store_items`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `history_prices`
--
ALTER TABLE `history_prices`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `history_quantities`
--
ALTER TABLE `history_quantities`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `identities_type`
--
ALTER TABLE `identities_type`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `international_calls_balance`
--
ALTER TABLE `international_calls_balance`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
 ADD PRIMARY KEY (`id`), ADD KEY `invoices_customer_id_idx` (`customer_id`), ADD KEY `invoices_store_id_idx` (`store_id`);

--
-- Indexes for table `invoice_items`
--
ALTER TABLE `invoice_items`
 ADD PRIMARY KEY (`id`), ADD KEY `invoice_items_invoice_id_idx` (`invoice_id`), ADD KEY `invoice_items_item_id_idx` (`item_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
 ADD PRIMARY KEY (`id`), ADD KEY `items_groups_relation_idx` (`item_category`), ADD KEY `suppliers_id_rel_idx` (`supplier_reference`);

--
-- Indexes for table `items_categories`
--
ALTER TABLE `items_categories`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `items_categories_parents`
--
ALTER TABLE `items_categories_parents`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `items_composite`
--
ALTER TABLE `items_composite`
 ADD PRIMARY KEY (`id`), ADD KEY `item_composite_id_idx` (`composite_item_id`), ADD KEY `item_id_idx` (`item_id`);

--
-- Indexes for table `items_images`
--
ALTER TABLE `items_images`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `items_parents`
--
ALTER TABLE `items_parents`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login_history`
--
ALTER TABLE `login_history`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `materials`
--
ALTER TABLE `materials`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mobile_credits_history`
--
ALTER TABLE `mobile_credits_history`
 ADD PRIMARY KEY (`id`), ADD KEY `mobile_credits_history_device_id_idx` (`device_id`);

--
-- Indexes for table `mobile_devices`
--
ALTER TABLE `mobile_devices`
 ADD PRIMARY KEY (`id`), ADD KEY `mobile_devices_operator_id_idx` (`operator_id`), ADD KEY `mobile_devices_store_id_idx` (`store_id`);

--
-- Indexes for table `mobile_dollars`
--
ALTER TABLE `mobile_dollars`
 ADD PRIMARY KEY (`id`), ADD KEY `mobile_dollars_operator_type_idx` (`operator_id`);

--
-- Indexes for table `mobile_international_calls`
--
ALTER TABLE `mobile_international_calls`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mobile_line_recharge`
--
ALTER TABLE `mobile_line_recharge`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mobile_operators`
--
ALTER TABLE `mobile_operators`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
 ADD PRIMARY KEY (`id`), ADD KEY `payments_invoice_id_idx` (`invoice_id`);

--
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_status`
--
ALTER TABLE `payment_status`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payroll_attendance_details`
--
ALTER TABLE `payroll_attendance_details`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payroll_details`
--
ALTER TABLE `payroll_details`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pending_invoices`
--
ALTER TABLE `pending_invoices`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `phones`
--
ALTER TABLE `phones`
 ADD PRIMARY KEY (`id`), ADD KEY `supplier_rel_id_idx` (`supplier_id`);

--
-- Indexes for table `plugin_deliveries`
--
ALTER TABLE `plugin_deliveries`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `plugin_delivery_details`
--
ALTER TABLE `plugin_delivery_details`
 ADD PRIMARY KEY (`id`), ADD KEY `delivery_items_id_idx` (`delivery_id`);

--
-- Indexes for table `pos_monitor`
--
ALTER TABLE `pos_monitor`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `queries`
--
ALTER TABLE `queries`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `queries_synced`
--
ALTER TABLE `queries_synced`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `quotations`
--
ALTER TABLE `quotations`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `quotations_log`
--
ALTER TABLE `quotations_log`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `quotation_details`
--
ALTER TABLE `quotation_details`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `receive_stock`
--
ALTER TABLE `receive_stock`
 ADD PRIMARY KEY (`id`), ADD KEY `receive_stock_invoice_id_idx` (`receive_stock_invoice_id`);

--
-- Indexes for table `receive_stock_invoices`
--
ALTER TABLE `receive_stock_invoices`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `receive_stock_invoice_fees`
--
ALTER TABLE `receive_stock_invoice_fees`
 ADD PRIMARY KEY (`id`), ADD KEY `pi_more_type_idx` (`type_id`);

--
-- Indexes for table `receive_stock_invoice_fees_types`
--
ALTER TABLE `receive_stock_invoice_fees_types`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `returned_purchases`
--
ALTER TABLE `returned_purchases`
 ADD PRIMARY KEY (`id`), ADD KEY `returned_purchases_item_id_idx` (`item_id`), ADD KEY `returned_purchases_invoice_id_idx` (`invoice_id`), ADD KEY `returned_purchases_vendor_id_idx` (`returned_by_vendor_id`), ADD KEY `returned_purchases_store_id_idx` (`returned_to_store_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `name_UNIQUE` (`name`);

--
-- Indexes for table `settings_log`
--
ALTER TABLE `settings_log`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shortcuts`
--
ALTER TABLE `shortcuts`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shortcuts_details`
--
ALTER TABLE `shortcuts_details`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shrinkages`
--
ALTER TABLE `shrinkages`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shrinkages_details`
--
ALTER TABLE `shrinkages_details`
 ADD PRIMARY KEY (`id`), ADD KEY `shrinkages_id_idx` (`shrinkages_id`);

--
-- Indexes for table `shrinkage_failed`
--
ALTER TABLE `shrinkage_failed`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sms`
--
ALTER TABLE `sms`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sms_details`
--
ALTER TABLE `sms_details`
 ADD PRIMARY KEY (`id`), ADD KEY `sms_status_idx` (`status_id`), ADD KEY `sms_id_rel_idx` (`sms_id`);

--
-- Indexes for table `sms_status`
--
ALTER TABLE `sms_status`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sms_test`
--
ALTER TABLE `sms_test`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_movement`
--
ALTER TABLE `stock_movement`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_movement_details`
--
ALTER TABLE `stock_movement_details`
 ADD PRIMARY KEY (`id`), ADD KEY `stock_mv_rel_idx` (`stock_movement_id`);

--
-- Indexes for table `store`
--
ALTER TABLE `store`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `store_items`
--
ALTER TABLE `store_items`
 ADD PRIMARY KEY (`id`), ADD KEY `store_items_id_rel_idx` (`item_id`), ADD KEY `store_items_store_id_relation_idx` (`store_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
 ADD PRIMARY KEY (`id`), ADD KEY `country_code_rel_idx` (`country_id`);

--
-- Indexes for table `suppliers_payments`
--
ALTER TABLE `suppliers_payments`
 ADD PRIMARY KEY (`id`), ADD KEY `rel_supplier_in_id_idx` (`supplier_id`), ADD KEY `rel_invoice_order_id_idx` (`invoice_order_id`);

--
-- Indexes for table `tasks_daily`
--
ALTER TABLE `tasks_daily`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `telegram`
--
ALTER TABLE `telegram`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `telegram_accounts`
--
ALTER TABLE `telegram_accounts`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transfers`
--
ALTER TABLE `transfers`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transfers_details`
--
ALTER TABLE `transfers_details`
 ADD PRIMARY KEY (`id`), ADD KEY `transfer_id_rel_idx` (`transfer_id`);

--
-- Indexes for table `transfers_new`
--
ALTER TABLE `transfers_new`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `unique_items`
--
ALTER TABLE `unique_items`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `unique_items_history`
--
ALTER TABLE `unique_items_history`
 ADD PRIMARY KEY (`unique_id`);

--
-- Indexes for table `unit_color`
--
ALTER TABLE `unit_color`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `unit_measure`
--
ALTER TABLE `unit_measure`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `unit_size`
--
ALTER TABLE `unit_size`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`id`), ADD KEY `users_role_id_idx` (`role_id`), ADD KEY `users_store_id_idx` (`store_id`);

--
-- Indexes for table `users_role`
--
ALTER TABLE `users_role`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `warehouse`
--
ALTER TABLE `warehouse`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `warehouse_items`
--
ALTER TABLE `warehouse_items`
 ADD PRIMARY KEY (`id`), ADD KEY `warehouse_items_id_relation_idx` (`item_id`), ADD KEY `warehouse_items_warehouse_id_relation_idx` (`warehouse_id`);

--
-- Indexes for table `wasting`
--
ALTER TABLE `wasting`
 ADD PRIMARY KEY (`id`), ADD KEY `wasting_rel_type_idx` (`type`);

--
-- Indexes for table `wasting_types`
--
ALTER TABLE `wasting_types`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `areas`
--
ALTER TABLE `areas`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `authorized_devices`
--
ALTER TABLE `authorized_devices`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `banks`
--
ALTER TABLE `banks`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `barcode_params`
--
ALTER TABLE `barcode_params`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=75;
--
-- AUTO_INCREMENT for table `bonuses_and_penalties`
--
ALTER TABLE `bonuses_and_penalties`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `cashback`
--
ALTER TABLE `cashback`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `cashbox`
--
ALTER TABLE `cashbox`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `cashbox_changes_info`
--
ALTER TABLE `cashbox_changes_info`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `cashbox_transactions`
--
ALTER TABLE `cashbox_transactions`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `cash_details`
--
ALTER TABLE `cash_details`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `cash_details_logs`
--
ALTER TABLE `cash_details_logs`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `cash_in_out`
--
ALTER TABLE `cash_in_out`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `cash_in_out_starting`
--
ALTER TABLE `cash_in_out_starting`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `cash_in_out_types`
--
ALTER TABLE `cash_in_out_types`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `collected_customers`
--
ALTER TABLE `collected_customers`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `complex_items`
--
ALTER TABLE `complex_items`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `complex_item_details`
--
ALTER TABLE `complex_item_details`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `complex_item_log`
--
ALTER TABLE `complex_item_log`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=247;
--
-- AUTO_INCREMENT for table `credit_notes`
--
ALTER TABLE `credit_notes`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `credit_notes_details`
--
ALTER TABLE `credit_notes_details`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `currencies`
--
ALTER TABLE `currencies`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `customers_logs`
--
ALTER TABLE `customers_logs`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `customers_types`
--
ALTER TABLE `customers_types`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `customer_balance`
--
ALTER TABLE `customer_balance`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `debit_notes`
--
ALTER TABLE `debit_notes`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `debit_notes_details`
--
ALTER TABLE `debit_notes_details`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `delivery_companies`
--
ALTER TABLE `delivery_companies`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `delivery_logs`
--
ALTER TABLE `delivery_logs`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `delivery_orders`
--
ALTER TABLE `delivery_orders`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `delivery_payments`
--
ALTER TABLE `delivery_payments`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `delivery_status`
--
ALTER TABLE `delivery_status`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `discounts`
--
ALTER TABLE `discounts`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `discounts_details`
--
ALTER TABLE `discounts_details`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `districts`
--
ALTER TABLE `districts`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=28;
--
-- AUTO_INCREMENT for table `email_smtp`
--
ALTER TABLE `email_smtp`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `employees_attendance`
--
ALTER TABLE `employees_attendance`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `expenses_types`
--
ALTER TABLE `expenses_types`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `garage_clients_cards`
--
ALTER TABLE `garage_clients_cards`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `global_logs`
--
ALTER TABLE `global_logs`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `history_of_store_items`
--
ALTER TABLE `history_of_store_items`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `history_prices`
--
ALTER TABLE `history_prices`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `history_quantities`
--
ALTER TABLE `history_quantities`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `identities_type`
--
ALTER TABLE `identities_type`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `international_calls_balance`
--
ALTER TABLE `international_calls_balance`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `items_categories`
--
ALTER TABLE `items_categories`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `items_categories_parents`
--
ALTER TABLE `items_categories_parents`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `items_composite`
--
ALTER TABLE `items_composite`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `items_images`
--
ALTER TABLE `items_images`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `items_parents`
--
ALTER TABLE `items_parents`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `login_history`
--
ALTER TABLE `login_history`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `materials`
--
ALTER TABLE `materials`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `mobile_credits_history`
--
ALTER TABLE `mobile_credits_history`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `mobile_devices`
--
ALTER TABLE `mobile_devices`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `mobile_dollars`
--
ALTER TABLE `mobile_dollars`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `mobile_international_calls`
--
ALTER TABLE `mobile_international_calls`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `mobile_line_recharge`
--
ALTER TABLE `mobile_line_recharge`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `mobile_operators`
--
ALTER TABLE `mobile_operators`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `payment_status`
--
ALTER TABLE `payment_status`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `payroll_attendance_details`
--
ALTER TABLE `payroll_attendance_details`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `payroll_details`
--
ALTER TABLE `payroll_details`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pending_invoices`
--
ALTER TABLE `pending_invoices`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `phones`
--
ALTER TABLE `phones`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `plugin_deliveries`
--
ALTER TABLE `plugin_deliveries`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `plugin_delivery_details`
--
ALTER TABLE `plugin_delivery_details`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pos_monitor`
--
ALTER TABLE `pos_monitor`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `queries`
--
ALTER TABLE `queries`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `queries_synced`
--
ALTER TABLE `queries_synced`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `quotations`
--
ALTER TABLE `quotations`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `quotations_log`
--
ALTER TABLE `quotations_log`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `quotation_details`
--
ALTER TABLE `quotation_details`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `receive_stock`
--
ALTER TABLE `receive_stock`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `receive_stock_invoices`
--
ALTER TABLE `receive_stock_invoices`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `receive_stock_invoice_fees`
--
ALTER TABLE `receive_stock_invoice_fees`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `receive_stock_invoice_fees_types`
--
ALTER TABLE `receive_stock_invoice_fees_types`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `returned_purchases`
--
ALTER TABLE `returned_purchases`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=384;
--
-- AUTO_INCREMENT for table `settings_log`
--
ALTER TABLE `settings_log`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `shortcuts`
--
ALTER TABLE `shortcuts`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `shortcuts_details`
--
ALTER TABLE `shortcuts_details`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `shrinkages`
--
ALTER TABLE `shrinkages`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `shrinkages_details`
--
ALTER TABLE `shrinkages_details`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `shrinkage_failed`
--
ALTER TABLE `shrinkage_failed`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sms`
--
ALTER TABLE `sms`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sms_details`
--
ALTER TABLE `sms_details`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sms_status`
--
ALTER TABLE `sms_status`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `sms_test`
--
ALTER TABLE `sms_test`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `stock_movement`
--
ALTER TABLE `stock_movement`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `stock_movement_details`
--
ALTER TABLE `stock_movement_details`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `store`
--
ALTER TABLE `store`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `store_items`
--
ALTER TABLE `store_items`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `suppliers_payments`
--
ALTER TABLE `suppliers_payments`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tasks_daily`
--
ALTER TABLE `tasks_daily`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `telegram`
--
ALTER TABLE `telegram`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `telegram_accounts`
--
ALTER TABLE `telegram_accounts`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `transfers`
--
ALTER TABLE `transfers`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `transfers_details`
--
ALTER TABLE `transfers_details`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `transfers_new`
--
ALTER TABLE `transfers_new`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `unique_items`
--
ALTER TABLE `unique_items`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `unique_items_history`
--
ALTER TABLE `unique_items_history`
MODIFY `unique_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `unit_color`
--
ALTER TABLE `unit_color`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8214;
--
-- AUTO_INCREMENT for table `unit_measure`
--
ALTER TABLE `unit_measure`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `unit_size`
--
ALTER TABLE `unit_size`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7814;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `users_role`
--
ALTER TABLE `users_role`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `warehouse`
--
ALTER TABLE `warehouse`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `warehouse_items`
--
ALTER TABLE `warehouse_items`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `wasting`
--
ALTER TABLE `wasting`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `wasting_types`
--
ALTER TABLE `wasting_types`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `areas`
--
ALTER TABLE `areas`
ADD CONSTRAINT `country_area` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `cash_in_out`
--
ALTER TABLE `cash_in_out`
ADD CONSTRAINT `type_id_cash_rel` FOREIGN KEY (`type_id`) REFERENCES `cash_in_out_types` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `cities`
--
ALTER TABLE `cities`
ADD CONSTRAINT `district_city` FOREIGN KEY (`district_id`) REFERENCES `districts` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `credit_notes`
--
ALTER TABLE `credit_notes`
ADD CONSTRAINT `customer_id_cn_rel` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `payment_id_cn_rel` FOREIGN KEY (`credit_payment_method`) REFERENCES `payment_methods` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `store_id_cn_rel` FOREIGN KEY (`store_id`) REFERENCES `store` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `customers`
--
ALTER TABLE `customers`
ADD CONSTRAINT `customer_type_rel` FOREIGN KEY (`customer_type`) REFERENCES `customers_types` (`id`);

--
-- Constraints for table `customer_balance`
--
ALTER TABLE `customer_balance`
ADD CONSTRAINT `customer_balance_id` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `customer_balance_store_id` FOREIGN KEY (`store_id`) REFERENCES `store` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `customer_balance_vendor_id` FOREIGN KEY (`vendor_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `debit_notes`
--
ALTER TABLE `debit_notes`
ADD CONSTRAINT `payment_id_cn_rel0` FOREIGN KEY (`debit_payment_method`) REFERENCES `payment_methods` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `store_id_cn_rel0` FOREIGN KEY (`store_id`) REFERENCES `store` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `supplier_id_cn_rel0` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `discounts_details`
--
ALTER TABLE `discounts_details`
ADD CONSTRAINT `discount_discount_id` FOREIGN KEY (`discount_id`) REFERENCES `discounts` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `discount_item_id` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `discount_store_id` FOREIGN KEY (`store_id`) REFERENCES `store` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `districts`
--
ALTER TABLE `districts`
ADD CONSTRAINT `area_district` FOREIGN KEY (`area_id`) REFERENCES `areas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `employees_attendance`
--
ALTER TABLE `employees_attendance`
ADD CONSTRAINT `employee_id` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `plugin_delivery_details`
--
ALTER TABLE `plugin_delivery_details`
ADD CONSTRAINT `delivery_items_id` FOREIGN KEY (`delivery_id`) REFERENCES `plugin_deliveries` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `receive_stock_invoice_fees`
--
ALTER TABLE `receive_stock_invoice_fees`
ADD CONSTRAINT `pi_more_type` FOREIGN KEY (`type_id`) REFERENCES `receive_stock_invoice_fees_types` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `shrinkages_details`
--
ALTER TABLE `shrinkages_details`
ADD CONSTRAINT `shrinkages_id` FOREIGN KEY (`shrinkages_id`) REFERENCES `shrinkages` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `sms_details`
--
ALTER TABLE `sms_details`
ADD CONSTRAINT `sms_id_rel` FOREIGN KEY (`sms_id`) REFERENCES `sms` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `sms_status` FOREIGN KEY (`status_id`) REFERENCES `sms_status` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `stock_movement_details`
--
ALTER TABLE `stock_movement_details`
ADD CONSTRAINT `stock_mv_rel` FOREIGN KEY (`stock_movement_id`) REFERENCES `stock_movement` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `transfers_details`
--
ALTER TABLE `transfers_details`
ADD CONSTRAINT `transfer_id_rel` FOREIGN KEY (`transfer_id`) REFERENCES `transfers` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `wasting`
--
ALTER TABLE `wasting`
ADD CONSTRAINT `wasting_rel_type` FOREIGN KEY (`type`) REFERENCES `wasting_types` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
