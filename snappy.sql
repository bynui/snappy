-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 02, 2025 at 07:42 AM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `snappy`
--

-- --------------------------------------------------------

--
-- Table structure for table `example`
--

CREATE TABLE `example` (
  `id` int(10) UNSIGNED NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'NULL',
  `last_name` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `email` varchar(30) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `status` varchar(8) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'ACTIVE'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `example`
--

INSERT INTO `example` (`id`, `first_name`, `last_name`, `email`, `status`) VALUES
(1, 'Alberto', 'Boice', 'aboice0@twitter.com', 'ACTIVE'),
(2, 'Timmy', 'Jalland', 'tjalland1@wix.com', 'INACTIVE'),
(3, 'Niels', 'Mandrier', 'nmandrier2@oracle.com', 'ACTIVE'),
(4, 'Tommi', 'Francklin', 'tfrancklin3@topsy.com', 'INACTIVE'),
(5, 'Ariella', 'Stock', 'astock4@typepad.com', 'ACTIVE'),
(6, 'Guillermo', 'Yate', 'gyate5@vinaora.com', 'INACTIVE'),
(7, 'Jacki', 'Yurchenko', 'jyurchenko6@who.int', 'ACTIVE'),
(8, 'Carlynn', 'Robet', 'crobet7@craigslist.org', 'INACTIVE'),
(9, 'Alon', 'D\'Aulby', 'adaulby8@europa.eu', 'ACTIVE'),
(10, 'Misha', 'Geroldi', 'mgeroldi9@google.co.uk', 'INACTIVE'),
(11, 'Aurlie', 'Skinn', 'askinna@fotki.com', 'ACTIVE'),
(12, 'Breena', 'Filippone', 'bfilipponeb@ovh.net', 'INACTIVE'),
(13, 'Dennis', 'Lefort', 'dlefortc@amazonaws.com', 'ACTIVE'),
(14, 'Taylor', 'Willford', 'twillfordd@earthlink.net', 'INACTIVE'),
(15, 'Kristel', 'Cutridge', 'kcutridgee@blogger.com', 'ACTIVE'),
(16, 'Shayla', 'Olorenshaw', 'solorenshawf@netscape.com', 'INACTIVE'),
(17, 'Ingelbert', 'Jeckells', 'ijeckellsg@tripadvisor.com', 'ACTIVE'),
(18, 'Tybie', 'Count', 'tcounth@bloglovin.com', 'INACTIVE'),
(19, 'Joshuah', 'Foss', 'jfossi@typepad.com', 'ACTIVE'),
(20, 'Zea', 'Shinfield', 'mac@localhost.com', 'INACTIVE'),
(21, 'Robbert', 'Arntzen', 'rarntzenk@abc.net.au', 'ACTIVE'),
(22, 'Davide', 'Byrd', 'dbyrdl@miitbeian.gov.cn', 'INACTIVE'),
(23, 'Quintina', 'Whife', 'qwhifem@yale.edu', 'ACTIVE'),
(24, 'Heidie', 'Smith', 'hsmithn@si.edu', 'INACTIVE'),
(25, 'Nessi', 'Eard', 'neardo@last.fm', 'ACTIVE'),
(26, 'Libbi', 'Larmett', 'llarmettp@4shared.com', 'INACTIVE'),
(27, 'Hershel', 'Curness', 'hcurnessq@wikia.com', 'ACTIVE'),
(28, 'Avie', 'Godart', 'agodartr@examiner.com', 'INACTIVE'),
(29, 'Ailey', 'Corneliussen', 'acorneliussens@yahoo.co.jp', 'ACTIVE'),
(30, 'Fee', 'Kinton', 'fkintont@paginegialle.it', 'INACTIVE'),
(31, 'Chantalle', 'Sheers', 'csheersu@seattletimes.com', 'ACTIVE'),
(32, 'Serena', 'Engelbrecht', 'sengelbrechtv@ibm.com', 'INACTIVE'),
(33, 'Pippo', 'Jaszczak', 'pjaszczakw@princeton.edu', 'ACTIVE'),
(34, 'Constantin', 'Klimpt', 'cklimptx@ox.ac.uk', 'INACTIVE'),
(35, 'Amye', 'Grishenkov', 'agrishenkovy@mit.edu', 'ACTIVE'),
(36, 'Selene', 'Standall', 'sstandallz@census.gov', 'INACTIVE'),
(37, 'Elsbeth', 'Ducham', 'educham10@ning.com', 'ACTIVE'),
(38, 'Lorianne', 'Guenther', 'lguenther11@who.int', 'INACTIVE'),
(39, 'Guthry', 'Liffey', 'gliffey12@flickr.com', 'ACTIVE'),
(40, 'Cos', 'Gornal', 'cgornal13@freewebs.com', 'INACTIVE'),
(41, 'Wheeler', 'Baversor', 'wbaversor14@illinois.edu', 'ACTIVE'),
(42, 'Dionis', 'Possek', 'dpossek15@timesonline.co.uk', 'INACTIVE'),
(43, 'Fransisco', 'Mairs', 'fmairs16@google.es', 'ACTIVE'),
(44, 'Daile', 'Tourry', 'dtourry17@baidu.com', 'INACTIVE'),
(45, 'Tawnya', 'Boissieux', 'tboissieux18@google.com.br', 'ACTIVE'),
(46, 'Cherice', 'Gallyhaock', 'cgallyhaock19@wikia.com', 'INACTIVE'),
(47, 'Caterina', 'Piegrome', 'cpiegrome1a@xinhuanet.com', 'ACTIVE'),
(48, 'Carena', 'Durran', 'cdurran1b@bing.com', 'INACTIVE'),
(49, 'Jacquelynn', 'Fawthorpe', 'jfawthorpe1c@netlog.com', 'ACTIVE'),
(50, 'Hogan', 'Trenear', 'htrenear1d@newyorker.com', 'INACTIVE'),
(51, 'Vassili', 'Beacham', 'vbeacham1e@google.co.uk', 'ACTIVE'),
(52, 'Merilyn', 'Gaylor', 'mgaylor1f@typepad.com', 'INACTIVE'),
(53, 'Lauretta', 'Manwell', 'lmanwell1g@hostgator.com', 'ACTIVE'),
(54, 'Helaina', 'Marquiss', 'hmarquiss1h@disqus.com', 'INACTIVE'),
(55, 'Andriana', 'Oxlade', 'aoxlade1i@cbslocal.com', 'ACTIVE'),
(56, 'Owen', 'Stamp', 'ostamp1j@taobao.com', 'INACTIVE'),
(57, 'Adham', 'Wickardt', 'awickardt1k@webmd.com', 'ACTIVE'),
(58, 'Collete', 'Crevagh', 'ccrevagh1l@scribd.com', 'INACTIVE'),
(59, 'Alys', 'Wortt', 'awortt1m@prweb.com', 'ACTIVE'),
(60, 'Leola', 'Genders', 'lgenders1n@apple.com', 'INACTIVE'),
(61, 'Kippy', 'Bowne', 'kbowne1o@wordpress.com', 'ACTIVE'),
(62, 'Aile', 'Wake', 'awake1p@timesonline.co.uk', 'INACTIVE'),
(63, 'Fallon', 'Dibbe', 'fdibbe1q@buzzfeed.com', 'ACTIVE'),
(64, 'Bekki', 'Keene', 'bkeene1r@oakley.com', 'INACTIVE'),
(65, 'Nealon', 'Hache', 'nhache1s@typepad.com', 'ACTIVE'),
(66, 'Smith', 'Smurfit', 'ssmurfit1t@booking.com', 'INACTIVE'),
(67, 'Leanora', 'Hansberry', 'lhansberry1u@apple.com', 'ACTIVE'),
(68, 'Otes', 'Message', 'omessage1v@a8.net', 'INACTIVE'),
(69, 'Olvan', 'Levay', 'olevay1w@google.ru', 'ACTIVE'),
(70, 'Mareah', 'Smouten', 'msmouten1x@mozilla.com', 'INACTIVE'),
(71, 'Duffie', 'Pettegre', 'dpettegre1y@themeforest.net', 'ACTIVE'),
(72, 'Malina', 'Baxandall', 'mbaxandall1z@zimbio.com', 'INACTIVE'),
(73, 'Basil', 'Matthewson', 'bmatthewson20@free.fr', 'ACTIVE'),
(74, 'Barry', 'Duffett', 'bduffett21@skype.com', 'INACTIVE'),
(75, 'Josefa', 'McCrostie', 'jmccrostie22@flavors.me', 'ACTIVE'),
(76, 'Zitella', 'Gunny', 'zgunny23@time.com', 'INACTIVE'),
(77, 'Esta', 'Bew', 'ebew24@webs.com', 'ACTIVE'),
(78, 'Star', 'Carlick', 'scarlick25@fc2.com', 'INACTIVE'),
(79, 'Burnaby', 'Giacomelli', 'bgiacomelli26@army.mil', 'ACTIVE'),
(80, 'Windham', 'Gutowska', 'wgutowska27@loc.gov', 'INACTIVE'),
(81, 'Maryann', 'Jorgesen', 'mjorgesen28@google.com.br', 'ACTIVE'),
(82, 'Cammi', 'Passy', 'cpassy29@usa.gov', 'INACTIVE'),
(83, 'Maddy', 'Vouls', 'mvouls2a@skyrock.com', 'ACTIVE'),
(84, 'Rosene', 'Beedham', 'rbeedham2b@umich.edu', 'INACTIVE'),
(85, 'Ricard', 'von Hagt', 'rvonhagt2c@lycos.com', 'ACTIVE'),
(86, 'Muffin', 'Buttner', 'mbuttner2d@ucoz.com', 'INACTIVE'),
(87, 'Cristabel', 'Doddemeade', 'cdoddemeade2e@bing.com', 'ACTIVE'),
(88, 'Ainslee', 'Richardot', 'arichardot2f@flavors.me', 'INACTIVE'),
(89, 'Carmelina', 'Statersfield', 'cstatersfield2g@vk.com', 'ACTIVE'),
(90, 'Esmeralda', 'Dullard', 'edullard2h@usa.gov', 'INACTIVE'),
(91, 'Patric', 'Mirfield', 'pmirfield2i@slideshare.net', 'ACTIVE'),
(92, 'Zsazsa', 'Corbridge', 'zcorbridge2j@list-manage.com', 'INACTIVE'),
(93, 'Vi', 'Collingridge', 'vcollingridge2k@mac.com', 'ACTIVE'),
(94, 'Annadiana', 'Bore', 'abore2l@google.co.jp', 'INACTIVE'),
(95, 'Madalena', 'Challoner', 'mchalloner2m@discovery.com', 'ACTIVE'),
(96, 'Tom', 'Ingleson', 'tingleson2n@last.fm', 'INACTIVE'),
(97, 'Leela', 'Colecrough', 'lcolecrough2o@un.org', 'ACTIVE'),
(98, 'Brocky', 'Causey', 'bcausey2p@artisteer.com', 'INACTIVE'),
(99, 'Isidoro', 'Akister', 'iakister2q@usda.gov', 'ACTIVE'),
(100, 'Lilli', 'Siggs', 'lsiggs2r@ovh.net', 'INACTIVE'),
(107, 'first name edited', 'last name edited', 'email edited', 'ACTIVE'),
(108, 'Foo\r\n', 'Bar\r\n', 'foo@bar.com\r\n', 'ACTIVE');

-- --------------------------------------------------------

--
-- Table structure for table `log`
--

CREATE TABLE `log` (
  `id` int(11) NOT NULL,
  `route` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `endpoint` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `createdat` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Indexes for table `example`
--
ALTER TABLE `example`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `example`
--
ALTER TABLE `example`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT for table `log`
--
ALTER TABLE `log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
