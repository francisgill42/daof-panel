-- phpMyAdmin SQL Dump
-- version 4.7.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 06, 2019 at 06:47 AM
-- Server version: 5.6.40-84.0-log
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `oranger0_firebase`
--

-- --------------------------------------------------------

--
-- Table structure for table `devices`
--

CREATE TABLE `devices` (
  `id` int(11) NOT NULL,
  `device_type` varchar(50) DEFAULT NULL,
  `device_id` text,
  `createdate` datetime DEFAULT NULL,
  `updatedate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `devices`
--

INSERT INTO `devices` (`id`, `device_type`, `device_id`, `createdate`, `updatedate`) VALUES
(3, 'android', 'cuTzwiNgar0:APA91bEQ3stHPmdFRaLUoBf-L5jOiD6gUfI5dyVpQAXTLEEwV8MfmvYmzbrnNBfXMIREhTVjJOgQOjkFh09uelspNj0-e83uC04QMdI03st_1T-k1whyJJjazBjHDGtHyO0ZVGnu8b8m', '2019-05-17 16:53:04', '2019-05-19 13:38:37'),
(4, 'android', 'cazvsfVxfZs:APA91bFnpDIbR8Z11HEhz35mjpVyOjnDtbqPwdTYlIX19EPq4l7dS5cAbtSTXjpp8ieUpdoG8mPH4xo7F763p17zbELnbDUx-yayc7Y_MtsDCA-ZoG9y_rIgxnZ8fituru0WeBOqQ3Qz', '2019-05-17 17:03:37', '2019-05-19 14:33:31'),
(5, 'android', 'eSR4EEO60cs:APA91bGpc8bSqoXhEOuxZcYiS9lgpKS6d2owYwUG2H3xZuRf14gcl80h9lkVL3rTp6vrGJkQ-PJu7o-VBfK_0PQNNHyKu0LayMjPmf0kYFC9serIqQI4dtfly5VRQ1MdN9zhvuw7XWbo', '2019-05-17 20:58:22', '2019-05-18 13:34:33'),
(6, 'android', 'f431h4sOq1E:APA91bEShVCLMlofwTm97aR1AdsQXwMHve9QkVAp1sRmXTn2h9fyhVQKDvwNzqMnx41iI9pvBlg-3QO0CG8zk6Gp3kxMuP7ZY5aR3vdJ8vd1hTx7f-gQvMkOUDfl-HiSa1dy7PMiC0ww', '2019-05-18 17:05:09', '2019-05-18 17:06:58'),
(7, 'android', 'eBF44wuEsOw:APA91bFGzf7J-MTJg3sWX6mcGPr_-Ql9O7HH40p6jAgmJKDlh-4Lr0jpE_Cww9MRGe0IXyZE1SlOGMo693RRVytxvAvoZgIO1KQ7HqBJPgOqICR1EStl3_OVMJIv4jWpmtz3YeJ2bsPl', '2019-05-19 14:11:15', '2019-05-19 14:34:06'),
(8, 'android', 'f2J1NbqBZaw:APA91bG8s2fb_e0TOXGDF2rNGWfOsm1h14aoVnD5oqaj6K-mAmEoaKiqVlwacabUSQFIM_rZUdzURm3iT9MYs3d3TmDn5YxNcQ3b023n8-ne9RZMdlUYCMGnygbrWs4FvoPUvfuZD4pQ', '2019-05-19 14:12:17', '2019-05-19 14:28:06'),
(9, 'android', 'e1mAV4r_yrg:APA91bH3JDPF7ojRKwJaUSOaATR9x_UrIyQO08aC5l5PVJfv432KbjUb-HnAp9Wt3bGSwlQeOvgt-NL678Mnmfvh6kv75UX0zs6Du1i15NKbfX7hZ7UCQLNvS-nac5wOn4pvKO6RZDOh', '2019-05-19 14:56:18', '2019-05-19 14:56:35'),
(10, 'android', 'eX9mBic4EgA:APA91bHcgBTmLSmKm9QwLluMwpiu4LOmBnlgd4xZu8nosWQR0TidyZAks063YauTJTUJPcDx3a9oLlZnzOYhouUZRiz62woew4ll-G03Dl29Os8A3qfGZMxuWOSRZBZCpAMU79jI518W', '2019-05-19 17:35:31', '2019-05-19 17:36:16'),
(11, 'android', 'fin7ZL3EEbY:APA91bGlR_madjPXmPXQJs3c81H0FJqv4OPWI_0t7tWCIZgFw8ayPSqGAmLLS-ON1_K6z4z1gTFhHy0vFDNDwtN7KFdGEg37ZERuFMEnoNcDaYcrBHat7HMz6Lar2hVDfk6NVTklSjLU', '2019-05-19 22:20:18', '2019-09-04 23:42:49'),
(12, 'android', 'dNTgyJJb02o:APA91bH8i6EwK6Dkx0krM97OXDYLgyiDYnllNF4fxnjvtOP0tBmy3UH0PYq8Bsm6lsG7TAgEg8Ga8hYZnCQgdJSbpPMUkYMnm7UY07aY1HvRcdkdNA0U-LsvxoT8yKBujwWk1mBRld_J', '2019-05-20 00:02:43', '2019-05-21 06:13:09'),
(13, 'android', 'dzejobbaz2A:APA91bFGdnQ7rcdVACqhcimfeRoMQH15hZ_VNg4JvZHtakwDmRGaK_momkdGRlpeH8szOSwiGfpZtiod3tdAnB9be7hHd_an1E3l9_wKsomyof2HD_6bkak3HhcR6OZAO9XOvSPO0skb', '2019-05-20 01:08:42', '2019-05-20 01:09:13'),
(14, 'android', 'erCdO9vIqxM:APA91bGU4Np5Y1ChwfulmEmT4EZE3ggYAx21aGbdsrwtt-EVrJa3YB_9OEcZ60h_plcn5Eqty-w8vOlUuTwx1zbG6EovCYPl0cIJ5WIfVFZgPkCUDIPxVUdip9MR0BtNYxcRNut8R06J', '2019-05-20 03:12:40', '2019-09-05 07:18:35'),
(15, 'android', NULL, '2019-08-16 07:40:43', '2019-08-18 08:29:43'),
(16, 'android', 'cgXP8trBxmI:APA91bHYsbFcDXBnTNAD6aRMhV89wZ7mV8rcFdEussoI8Ez869B8r0PQyNr8SV_aKDZOxXgzfKTUSq00r0YR_S93gUbhR7x7ZQFSC6ZRYtRejrgLZQZtN8mPqKp0CM3paYLk86ztBnkt', '2019-08-17 10:24:35', NULL),
(17, 'android', 'ci-wdA019FQ:APA91bEnV0xedJYqsn0JJQyVjYTLV4SJvp-MHQeRa5zKnYwmpafKDVZgLkbaQuA28eGcZsVU1GApLDVQDMBxVhj5rMx6Cnv0azlqZdGMFLLAgam7ZaUrUXYNiT942C_tRSGjW2ro1dQl', '2019-08-17 10:30:43', NULL),
(18, 'android', 'ezLRKrPpqzc:APA91bHE64s8ASEtHdHqalQL74HyJjii_e_0i82neMIaJQc8EP5DJjoU8bLOWqtVL9O5zs_7wj3JylhamCRM97Xcq-3SGtT5ywY9uf6usp42-7e0qarRauQ5MxvuQpqS-Mr9Azf9A9qM', '2019-09-05 01:36:27', '2019-09-05 01:36:51');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `devices`
--
ALTER TABLE `devices`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `devices`
--
ALTER TABLE `devices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
