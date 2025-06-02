-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 01 Jun 2025 pada 19.51
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `seagames_fb`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `nama` varchar(50) NOT NULL,
  `domisili` varchar(10) NOT NULL,
  `tgl_bergabung` varchar(20) NOT NULL,
  `jenis_kelamin` varchar(10) NOT NULL,
  `umur` varchar(5) NOT NULL,
  `profil` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id_admin`, `email`, `password`, `nama`, `domisili`, `tgl_bergabung`, `jenis_kelamin`, `umur`, `profil`) VALUES
(1001, 'admin1@gmail.com', '123', 'Diandra Mayliza', 'Yogyakarta', '1 Mei 2023', 'Wanita', '19', 'https://assets.pikiran-rakyat.com/crop/47x290:673x985/x/photo/2022/09/13/674528463.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `klasemen`
--

CREATE TABLE `klasemen` (
  `id` int(11) NOT NULL,
  `grup` varchar(1) NOT NULL,
  `id_negara` int(11) NOT NULL,
  `main` int(11) DEFAULT 0,
  `menang` int(11) DEFAULT 0,
  `seri` int(11) DEFAULT 0,
  `kalah` int(11) DEFAULT 0,
  `goal_menang` int(11) DEFAULT 0,
  `goal_kalah` int(11) DEFAULT 0,
  `poin` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `klasemen`
--

INSERT INTO `klasemen` (`id`, `grup`, `id_negara`, `main`, `menang`, `seri`, `kalah`, `goal_menang`, `goal_kalah`, `poin`) VALUES
(1, 'A', 1, 4, 4, 0, 0, 13, 0, 12),
(2, 'A', 8, 4, 3, 0, 1, 4, 5, 9),
(3, 'A', 5, 4, 1, 1, 2, 6, 5, 4),
(4, 'A', 9, 4, 1, 0, 3, 3, 8, 3),
(5, 'A', 7, 4, 0, 1, 3, 1, 8, 1),
(6, 'B', 2, 4, 3, 1, 0, 10, 3, 10),
(7, 'B', 3, 4, 3, 1, 0, 8, 3, 10),
(8, 'B', 4, 4, 2, 0, 2, 13, 5, 6),
(9, 'B', 10, 4, 0, 1, 3, 2, 11, 1),
(10, 'B', 6, 4, 0, 1, 3, 2, 13, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `last_match`
--

CREATE TABLE `last_match` (
  `id` int(11) NOT NULL,
  `judul` varchar(20) NOT NULL,
  `negara1` varchar(100) DEFAULT NULL,
  `negara2` varchar(100) DEFAULT NULL,
  `skor1` int(11) DEFAULT NULL,
  `skor2` int(11) DEFAULT NULL,
  `stadion` varchar(100) DEFAULT NULL,
  `tanggal` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `last_match`
--

INSERT INTO `last_match` (`id`, `judul`, `negara1`, `negara2`, `skor1`, `skor2`, `stadion`, `tanggal`) VALUES
(1, 'FINAL SEA GAMES 2023', 'Indonesia', 'Thailand', 5, 2, 'Stadion Apakek', '2023-05-27');

-- --------------------------------------------------------

--
-- Struktur dari tabel `negara`
--

CREATE TABLE `negara` (
  `id_negara` int(11) NOT NULL,
  `nama_negara` varchar(100) DEFAULT NULL,
  `pelatih` varchar(100) DEFAULT NULL,
  `nama_stadion` varchar(100) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `bendera` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `negara`
--

INSERT INTO `negara` (`id_negara`, `nama_negara`, `pelatih`, `nama_stadion`, `deskripsi`, `bendera`) VALUES
(1, 'Indonesia', 'Indra Sjafri', 'Stadion Utama Gelora Bung Karno', 'Indonesia mulai berkompetisi pada 1977 dan menjadi tuan rumah empat kali. Sering menempati posisi puncak perolehan medali SEA Games.', 'https://upload.wikimedia.org/wikipedia/commons/0/0b/Flag_of_Indonesia.png'),
(2, 'Thailand', 'Issara Sritaro', 'Rajamangala Stadium', 'Thailand adalah pendiri SEAGF dan selalu masuk tiga besar SEA Games. Jadi tuan rumah pertama untuk nama baru SEA Games.', 'https://upload.wikimedia.org/wikipedia/commons/a/a9/Flag_of_Thailand.svg'),
(3, 'Vietnam', 'Philippe Troussier', 'M? D?nh National Stadium', 'Vietnam mulai ikut SEA Games sejak 1989. Menjadi juara umum pada 2003, 2021, dan 2023 serta tuan rumah dua kali.', 'https://upload.wikimedia.org/wikipedia/commons/2/21/Flag_of_Vietnam.svg'),
(4, 'Malaysia', 'E. Elavarasan', 'Bukit Jalil National Stadium', 'Malaysia adalah pendiri SEAGF dan mengusulkan perluasan anggota SEA Games, menjadi tuan rumah SEA Games 1977.', 'https://upload.wikimedia.org/wikipedia/commons/6/66/Flag_of_Malaysia.svg'),
(5, 'Cambodia', 'Keisuke Honda', 'Morodok Techo National Stadium', 'Kamboja adalah pendiri SEAP Games, tetapi tidak ikut dalam edisi pertama.', 'https://upload.wikimedia.org/wikipedia/commons/8/83/Flag_of_Cambodia.svg'),
(6, 'Singapore', 'Nazri Nasir', 'National Stadium Singapore', 'Singapura adalah peserta SEA Games sejak 1959 dan telah menjadi tuan rumah empat kali. Total medali yang diraih melebihi 3.000.', 'https://upload.wikimedia.org/wikipedia/commons/4/48/Flag_of_Singapore.svg'),
(7, 'Philippines', 'Rob Gier', 'Rizal Memorial Stadium', 'Filipina mulai berpartisipasi tahun 1977 dan menjadi tuan rumah SEA Games empat kali, dengan prestasi puncak saat menjadi tuan rumah.', 'https://upload.wikimedia.org/wikipedia/commons/9/99/Flag_of_the_Philippines.svg'),
(8, 'Myanmar', 'Michael Feichtenbeiner', 'Thuwunna Stadium', 'Myanmar, sebelumnya Burma, adalah pendiri SEAP Games dan menjadi tuan rumah terakhir kali pada 2013.', 'https://upload.wikimedia.org/wikipedia/commons/8/8c/Flag_of_Myanmar.svg'),
(9, 'Timor-Leste', 'Park Soon-tae', 'Dili Municipal Stadium', 'Timor Leste berpartisipasi pertama kali pada 2003 dan meraih medali emas pertamanya pada 2011.', 'https://upload.wikimedia.org/wikipedia/commons/2/26/Flag_of_East_Timor.svg'),
(10, 'Laos', 'Michael Weiss', 'New Laos National Stadium', 'Laos ikut SEA Games 2023 dengan 576 atlet dan bertanding di 32 cabang olahraga.', 'https://upload.wikimedia.org/wikipedia/commons/5/56/Flag_of_Laos.svg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `notifications`
--

CREATE TABLE `notifications` (
  `id_notifikasi` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_negara` int(11) DEFAULT NULL,
  `judul` varchar(255) DEFAULT NULL,
  `pesan` text DEFAULT NULL,
  `waktu_dikirim` datetime DEFAULT current_timestamp(),
  `dibaca` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `notifikasi`
--

CREATE TABLE `notifikasi` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_pertandingan` int(11) DEFAULT NULL,
  `judul` varchar(255) NOT NULL,
  `pesan` text NOT NULL,
  `dibaca` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `notifikasi`
--

INSERT INTO `notifikasi` (`id`, `id_user`, `id_pertandingan`, `judul`, `pesan`, `dibaca`, `created_at`) VALUES
(1, 1, 20, 'hai', 'halo', 1, '2025-06-01 09:55:48'),
(2, 1, 20, 'FINAL : Indonesia VS Thailand', 'jangan lupa yaaa', 1, '2025-06-01 09:58:11'),
(3, 1, 4, '!!', 'FINAL INDO VS THAILAND JAM 19.00', 1, '2025-06-01 10:04:14'),
(6, 1, 9, 'hai', 'jangn lupa besok indo final cuy', 1, '2025-06-01 14:09:55');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pemain`
--

CREATE TABLE `pemain` (
  `id_pemain` int(11) NOT NULL,
  `nama_pemain` varchar(100) DEFAULT NULL,
  `posisi` varchar(50) DEFAULT NULL,
  `no_punggung` int(11) DEFAULT NULL,
  `id_negara` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pemain`
--

INSERT INTO `pemain` (`id_pemain`, `nama_pemain`, `posisi`, `no_punggung`, `id_negara`) VALUES
(1, 'Adi Satryo', 'Goalkeeper', 1, 1),
(2, 'Bagas Kaffa', 'Defender', 2, 1),
(3, 'Rio Fahmi', 'Defender', 3, 1),
(4, 'Komang Teguh', 'Defender', 4, 1),
(5, 'Rizky Ridho', 'Defender', 5, 1),
(6, 'Ananda Raehan', 'Midfielder', 6, 1),
(7, 'Marselino Ferdinan', 'Midfielder', 7, 1),
(8, 'Witan Sulaeman', 'Forward', 8, 1),
(9, 'Ramadhan Sananta', 'Forward', 9, 1),
(10, 'Beckham Putra', 'Midfielder', 10, 1),
(11, 'Jeam Kelly Sroyer', 'Forward', 11, 1),
(12, 'Pratama Arhan', 'Defender', 12, 1),
(13, 'Haykal Alhafiz', 'Defender', 13, 1),
(14, 'Fajar Fathur Rahman', 'Midfielder', 14, 1),
(15, 'Taufany Muslihuddin', 'Midfielder', 15, 1),
(16, 'Muhammad Ferarri', 'Defender', 16, 1),
(17, 'Irfan Jauhari', 'Forward', 17, 1),
(18, 'Titan Agung', 'Forward', 18, 1),
(19, 'Alfeandra Dewangga', 'Defender', 19, 1),
(20, 'Ernando Ari', 'Goalkeeper', 20, 1),
(21, 'Soponwit Rakyart', 'Goalkeeper', 1, 2),
(22, 'Bukkoree Lemdee', 'Defender', 2, 2),
(23, 'Chatmongkol Rueangthanarot', 'Defender', 3, 2),
(24, 'Jonathan Khemdee', 'Defender', 4, 2),
(25, 'Songchai Thongcham', 'Defender', 5, 2),
(26, 'Airfan Doloh', 'Midfielder', 6, 2),
(27, 'Channarong Promsrikaew', 'Midfielder', 7, 2),
(28, 'Teerasak Poeiphimai', 'Forward', 8, 2),
(29, 'Yotsakorn Burapha', 'Forward', 9, 2),
(30, 'Achitpol Keereerom', 'Forward', 10, 2),
(31, 'Anan Yodsangwal', 'Forward', 11, 2),
(32, 'Apisit Saenseekammuan', 'Defender', 12, 2),
(33, 'Pongsakorn Trisat', 'Defender', 13, 2),
(34, 'Purachet Thodsanit', 'Midfielder', 14, 2),
(35, 'Jakkapong Sanmahung', 'Defender', 15, 2),
(36, 'Leon James', 'Midfielder', 16, 2),
(37, 'Settasit Suvannaseat', 'Midfielder', 17, 2),
(38, 'Thirapak Prueangna', 'Midfielder', 18, 2),
(39, 'Chayapipat Supunpasuch', 'Midfielder', 19, 2),
(40, 'Thirawut Sraunson', 'Goalkeeper', 20, 2),
(41, 'Quan Van Chu?n', 'Goalkeeper', 1, 3),
(42, 'Phan Tu?n T?i', 'Defender', 2, 3),
(43, 'Luong Duy Cuong', 'Defender', 3, 3),
(44, 'Tr?n Quang Th?nh', 'Defender', 4, 3),
(45, 'Nguy?n Ng?c Th?ng', 'Defender', 5, 3),
(46, 'Vu Ti?n Long', 'Defender', 6, 3),
(47, 'L? Van D?', 'Midfielder', 7, 3),
(48, 'Khu?t Van Khang', 'Midfielder', 8, 3),
(49, 'Nguy?n Van T?ng', 'Forward', 9, 3),
(50, 'Dinh Xu?n Ti?n', 'Midfielder', 10, 3),
(51, 'Nguy?n Thanh Nh?n', 'Forward', 11, 3),
(52, 'Nguy?n Th?i Son', 'Midfielder', 12, 3),
(53, 'H? Van Cu?ng', 'Defender', 13, 3),
(54, 'Nguy?n Van Tru?ng', 'Midfielder', 14, 3),
(55, 'Hu?nh C?ng D?n', 'Midfielder', 15, 3),
(56, 'L? Qu?c Nh?t Nam', 'Midfielder', 16, 3),
(57, 'Vo Minh Tr?ng', 'Defender', 17, 3),
(58, 'Nguy?n D?c Ph?', 'Midfielder', 18, 3),
(59, 'Nguy?n Qu?c Vi?t', 'Forward', 19, 3),
(60, 'Do?n Huy Ho?ng', 'Goalkeeper', 20, 3),
(61, 'Azri Ghani', 'Goalkeeper', 1, 4),
(62, 'Asri Mardzuki', 'Defender', 2, 4),
(63, 'Harith Haiqal', 'Defender', 3, 4),
(64, 'Syamer Kutty Abba', 'Midfielder', 4, 4),
(65, 'Mukhairi Ajmal', 'Midfielder', 5, 4),
(66, 'Luqman Hakim', 'Forward', 6, 4),
(67, 'Hadi Fayyadh', 'Forward', 7, 4),
(68, 'Daniel Ting', 'Defender', 8, 4),
(69, 'Shahrul Saad', 'Defender', 9, 4),
(70, 'Nazmi Faiz', 'Midfielder', 10, 4),
(71, 'Safawi Rasid', 'Forward', 11, 4),
(72, 'Syafiq Ahmad', 'Forward', 12, 4),
(73, 'Brendan Gan', 'Midfielder', 13, 4),
(74, 'Matthew Davies', 'Defender', 14, 4),
(75, 'Junior Eldst?l', 'Defender', 15, 4),
(76, 'Irfan Zakaria', 'Defender', 16, 4),
(77, 'Akhyar Rashid', 'Forward', 17, 4),
(78, 'Faisal Halim', 'Forward', 18, 4),
(79, 'Farizal Marlias', 'Goalkeeper', 19, 4),
(80, 'Khairul Fahmi', 'Goalkeeper', 20, 4),
(81, 'Soeuth Nava', 'Defender', 1, 5),
(82, 'Tang Bun Chhay', 'Defender', 2, 5),
(83, 'Keut Pich', 'Defender', 3, 5),
(84, 'Chea Sok Meng', 'Defender', 4, 5),
(85, 'Phat Sokha', 'Defender', 5, 5),
(86, 'Ny Sokvy', 'Defender', 6, 5),
(87, 'Chan Sara Pich', 'Defender', 7, 5),
(88, 'Leng Makara', 'Midfielder', 8, 5),
(89, 'Sok Samnang', 'Midfielder', 9, 5),
(90, 'Chhin Chhoeun', 'Forward', 10, 5),
(91, 'Prak Mony Udom', 'Forward', 11, 5),
(92, 'Chan Vathanaka', 'Forward', 12, 5),
(93, 'Kouch Dani', 'Midfielder', 13, 5),
(94, 'Sos Suhana', 'Midfielder', 14, 5),
(95, 'Thierry Chantha Bin', 'Midfielder', 15, 5),
(96, 'Keo Sokpheng', 'Forward', 16, 5),
(97, 'Noun Borey', 'Forward', 17, 5),
(98, 'Hul Kimhuy', 'Goalkeeper', 18, 5),
(99, 'Um Vichet', 'Goalkeeper', 19, 5),
(100, 'Srey Veasna', 'Midfielder', 20, 5),
(101, 'Wayne Chew', 'Goalkeeper', 1, 6),
(102, 'Muhammad Aizil Mohamed Yazid', 'Goalkeeper', 2, 6),
(103, 'Adam Reefdy Muhammad Hasyim', 'Defender', 3, 6),
(104, 'Muhammad Ryaan Sanizal', 'Defender', 4, 6),
(105, 'Muhammad Aqil Mohamed Yazid', 'Defender', 5, 6),
(106, 'Bah Bill Abuzar Mamadou', 'Defender', 6, 6),
(107, 'Muhammad Nur Adam Abdullah', 'Defender', 7, 6),
(108, 'Kieran Teo Jia Jun', 'Defender', 8, 6),
(109, 'Mohamed Ilhan Mohamed Noor', 'Defender', 9, 6),
(110, 'Muhammad Fathullah Rahmat', 'Midfielder', 10, 6),
(111, 'Joel Chew', 'Midfielder', 11, 6),
(112, 'Jacob Mahler', 'Midfielder', 12, 6),
(113, 'Saifullah Akbar', 'Midfielder', 13, 6),
(114, 'Hami Syahin', 'Midfielder', 14, 6),
(115, 'Shah Shahiran', 'Midfielder', 15, 6),
(116, 'Ikhsan Fandi', 'Forward', 16, 6),
(117, 'Ilhan Fandi', 'Forward', 17, 6),
(118, 'Amy Recha', 'Forward', 18, 6),
(119, 'Shawal Anuar', 'Forward', 19, 6),
(120, 'Zulfahmi Arifin', 'Midfielder', 20, 6),
(121, 'Nicolas John', 'Goalkeeper', 1, 7),
(122, 'I?igo Villanueva', 'Defender', 2, 7),
(123, 'Emilio Joseph Santos', 'Defender', 3, 7),
(124, 'Michael Sato', 'Defender', 4, 7),
(125, 'Caius M. Rodriguez', 'Defender', 5, 7),
(126, 'Victor M. Lozada', 'Midfielder', 6, 7),
(127, 'Santiago B. De Los Santos', 'Midfielder', 7, 7),
(128, 'Rico C. Salazar', 'Midfielder', 8, 7),
(129, 'Marcelo M. Reyna', 'Forward', 9, 7),
(130, 'Jos? Luis Ma?osca', 'Forward', 10, 7),
(131, 'James Boado', 'Forward', 11, 7),
(132, 'Matthew A. Gonzales', 'Midfielder', 12, 7),
(133, 'Erickson A. Gallegos', 'Forward', 13, 7),
(134, 'Raymond Timonera', 'Defender', 14, 7),
(135, 'Marco R. Dizon', 'Midfielder', 15, 7),
(136, 'Bernardo S. Tan', 'Forward', 16, 7),
(137, 'Claudio Salazar', 'Forward', 17, 7),
(138, 'Ivan J. Angeles', 'Defender', 18, 7),
(139, 'Vito B. Isidro', 'Midfielder', 19, 7),
(140, 'Alexander E. Garcia', 'Goalkeeper', 20, 7),
(141, 'Min Thu Aung', 'Goalkeeper', 1, 8),
(142, 'Zaw Min Tun', 'Defender', 2, 8),
(143, 'Aung Aung', 'Defender', 3, 8),
(144, 'Ko Ko Naing', 'Defender', 4, 8),
(145, 'Soe Win', 'Defender', 5, 8),
(146, 'Yan Paing', 'Midfielder', 6, 8),
(147, 'Thet Lwin Aung', 'Midfielder', 7, 8),
(148, 'Pyae Phyo Thu', 'Midfielder', 8, 8),
(149, 'Aung Kyi', 'Forward', 9, 8),
(150, 'Hlaing Zaw', 'Forward', 10, 8),
(151, 'Nanda Kyaw', 'Forward', 11, 8),
(152, 'Zaw Thiha', 'Midfielder', 12, 8),
(153, 'Htun Zaw', 'Midfielder', 13, 8),
(154, 'Mya Khin', 'Defender', 14, 8),
(155, 'Nwe Kyi', 'Defender', 15, 8),
(156, 'Ko Zaw', 'Midfielder', 16, 8),
(157, 'Yee Kyaw', 'Forward', 17, 8),
(158, 'Myo Zaw', 'Forward', 18, 8),
(159, 'Myo Than', 'Goalkeeper', 19, 8),
(160, 'Nanda Zaw', 'Goalkeeper', 20, 8),
(161, 'Vongphachanh Sisavath', 'Goalkeeper', 1, 9),
(162, 'Khampheng Phommachanh', 'Defender', 2, 9),
(163, 'Thongkeo Khouman', 'Defender', 3, 9),
(164, 'Sithong Chanthavong', 'Defender', 4, 9),
(165, 'Kittikhoun Khammany', 'Defender', 5, 9),
(166, 'Khamphay Khounla', 'Midfielder', 6, 9),
(167, 'Vongkeo Bounxou', 'Midfielder', 7, 9),
(168, 'Phoukhamsa Oudom', 'Midfielder', 8, 9),
(169, 'Sonesay Khamphao', 'Forward', 9, 9),
(170, 'Chaleune Phimmasone', 'Forward', 10, 9),
(171, 'Thongsaen Khamphay', 'Forward', 11, 9),
(172, 'Phouphachanh Lao', 'Midfielder', 12, 9),
(173, 'Souksavanh Sounthala', 'Midfielder', 13, 9),
(174, 'Khamsone Boua', 'Defender', 14, 9),
(175, 'Phouttharat Chanthavong', 'Defender', 15, 9),
(176, 'Sengchao Thiravong', 'Midfielder', 16, 9),
(177, 'Khamphanh Lathpheng', 'Forward', 17, 9),
(178, 'Soukphachanh Sonthavong', 'Forward', 18, 9),
(179, 'Vathana Chounthavy', 'Goalkeeper', 19, 9),
(180, 'Phonxay Sisavath', 'Goalkeeper', 20, 9);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pertandingan`
--

CREATE TABLE `pertandingan` (
  `id` int(11) NOT NULL,
  `team1` varchar(50) NOT NULL,
  `team2` varchar(50) NOT NULL,
  `tanggal` date NOT NULL,
  `lokasi` varchar(100) NOT NULL,
  `skor1` int(11) DEFAULT NULL,
  `skor2` int(11) DEFAULT NULL,
  `status` enum('belum_dimulai','berlangsung','selesai') DEFAULT 'belum_dimulai',
  `notifikasi` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pertandingan`
--

INSERT INTO `pertandingan` (`id`, `team1`, `team2`, `tanggal`, `lokasi`, `skor1`, `skor2`, `status`, `notifikasi`) VALUES
(1, 'Indonesia', 'Philippines', '2023-04-29', 'Stadion Olimpiade', 3, 4, 'selesai', 0),
(2, 'Cambodia', 'Timor-Leste', '2023-04-29', 'Stadion Vinsakha', 1, 2, 'selesai', 0),
(3, 'Thailand', 'Singapore', '2023-04-30', 'Stadion Vinsakha', 2, 2, 'selesai', 0),
(4, 'Vietnam', 'Laos', '2023-04-30', 'Stadion Vinsakha', NULL, NULL, 'belum_dimulai', 0),
(5, 'Myanmar', 'Timor-Leste', '2023-05-02', 'Stadion Olimpiade', NULL, NULL, 'belum_dimulai', 0),
(6, 'Philippines', 'Cambodia', '2023-05-02', 'Stadion Olimpiade', NULL, NULL, 'belum_dimulai', 0),
(7, 'Singapore', 'Vietnam', '2023-05-03', 'Stadion Vinsakha', NULL, NULL, 'belum_dimulai', 0),
(8, 'Malaysia', 'Laos', '2023-05-03', 'Stadion Vinsakha', NULL, NULL, 'belum_dimulai', 0),
(9, 'Indonesia', 'Myanmar', '2023-05-04', 'Stadion Olimpiade', NULL, NULL, 'belum_dimulai', 0),
(10, 'Timor-Leste', 'Philippines', '2023-05-04', 'Stadion Olimpiade', NULL, NULL, 'belum_dimulai', 0),
(11, 'Thailand', 'Malaysia', '2023-05-06', 'Stadion Vinsakha', NULL, NULL, 'belum_dimulai', 0),
(12, 'Laos', 'Singapore', '2023-05-06', 'Stadion Vinsakha', NULL, NULL, 'belum_dimulai', 0),
(13, 'Timor-Leste', 'Indonesia', '2023-05-07', 'Stadion Olimpiade', NULL, NULL, 'belum_dimulai', 0),
(14, 'Myanmar', 'Cambodia', '2023-05-07', 'Stadion Olimpiade', NULL, NULL, 'belum_dimulai', 0),
(15, 'Laos', 'Thailand', '2023-05-08', 'Stadion Vinsakha', NULL, NULL, 'belum_dimulai', 0),
(16, 'Malaysia', 'Vietnam', '2023-05-08', 'Stadion Vinsakha', NULL, NULL, 'belum_dimulai', 0),
(17, 'Philippines', 'Myanmar', '2023-05-10', 'Stadion Olimpiade', NULL, NULL, 'belum_dimulai', 0),
(18, 'Cambodia', 'Indonesia', '2023-05-10', 'Stadion Olimpiade', NULL, NULL, 'belum_dimulai', 0),
(19, 'Singapore', 'Malaysia', '2023-05-11', 'Stadion Vinsakha', NULL, NULL, 'belum_dimulai', 0),
(20, 'Vietnam', 'Thailand', '2023-05-11', 'Stadion Vinsakha', NULL, NULL, 'belum_dimulai', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `previews`
--

CREATE TABLE `previews` (
  `id` int(10) NOT NULL,
  `judul` varchar(50) NOT NULL,
  `deskripsi` varchar(100) NOT NULL,
  `link_youtube` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `previews`
--

INSERT INTO `previews` (`id`, `judul`, `deskripsi`, `link_youtube`) VALUES
(1, '[FINAL] Indonesia (5) VS (2) Thailand | SEA GAMES ', 'Indonesia menang 5-2 atas Thailand, raih emas SEA Games!\r\n', 'https://www.youtube.com/embed/VCRUS0dWu5U'),
(2, 'template judul (2)dua', 'template deskripsi (2)', 'template link youtube (2)'),
(3, 'template judul (3)', 'template deskripsi (3)', 'template link youtube (3)'),
(4, 'template judul (4)', 'template deskripsi (4)', 'template link youtube (4)');

-- --------------------------------------------------------

--
-- Struktur dari tabel `statistik_pertandingan`
--

CREATE TABLE `statistik_pertandingan` (
  `pertandingan_id` int(11) NOT NULL,
  `team_number` int(11) NOT NULL,
  `id_negara` int(11) DEFAULT NULL,
  `judul` varchar(20) NOT NULL,
  `skor` int(11) DEFAULT 0,
  `penguasaan_bola` int(11) DEFAULT 0,
  `tembakan` int(11) DEFAULT 0,
  `tembakan_tepat` int(11) DEFAULT 0,
  `operan_tepat_sasaran` int(11) DEFAULT 0,
  `akurasi_operan` float DEFAULT 0,
  `peluang_tercipta` int(11) DEFAULT 0,
  `umpan_silang` int(11) DEFAULT 0,
  `tackles` int(11) DEFAULT 0,
  `intercepts` int(11) DEFAULT 0,
  `clearances` int(11) DEFAULT 0,
  `stadion` varchar(20) NOT NULL,
  `tanggal` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `statistik_pertandingan`
--

INSERT INTO `statistik_pertandingan` (`pertandingan_id`, `team_number`, `id_negara`, `judul`, `skor`, `penguasaan_bola`, `tembakan`, `tembakan_tepat`, `operan_tepat_sasaran`, `akurasi_operan`, `peluang_tercipta`, `umpan_silang`, `tackles`, `intercepts`, `clearances`, `stadion`, `tanggal`) VALUES
(1, 1, 3, 'Final Sea Games 2025', 106, 10, 0, 0, 0, 10, 0, 80, 0, 0, 0, 'Stadion Utama', '2025-05-31'),
(1, 2, 1, 'Final Sea Games 2025', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 'Stadion Utama', '2025-05-31'),
(2, 2, 10, '', 1, 20, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `notifikasi` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id_user`, `nama`, `email`, `password`, `notifikasi`) VALUES
(1, 'Warid Akbar', 'user1@gmail.com', '111', 1),
(4, 'Diandra Mayliza', 'user2@gmail.com', '098', 0),
(5, 'dian', 'sisfo@gmail.com', '1010', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_preferences`
--

CREATE TABLE `user_preferences` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_negara` int(11) NOT NULL,
  `notifikasi_aktif` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indeks untuk tabel `klasemen`
--
ALTER TABLE `klasemen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_negara` (`id_negara`);

--
-- Indeks untuk tabel `last_match`
--
ALTER TABLE `last_match`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `negara`
--
ALTER TABLE `negara`
  ADD PRIMARY KEY (`id_negara`);

--
-- Indeks untuk tabel `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id_notifikasi`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_negara` (`id_negara`);

--
-- Indeks untuk tabel `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_pertandingan` (`id_pertandingan`);

--
-- Indeks untuk tabel `pemain`
--
ALTER TABLE `pemain`
  ADD PRIMARY KEY (`id_pemain`),
  ADD KEY `id_negara` (`id_negara`);

--
-- Indeks untuk tabel `pertandingan`
--
ALTER TABLE `pertandingan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `statistik_pertandingan`
--
ALTER TABLE `statistik_pertandingan`
  ADD PRIMARY KEY (`pertandingan_id`,`team_number`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indeks untuk tabel `user_preferences`
--
ALTER TABLE `user_preferences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_negara` (`id_negara`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `klasemen`
--
ALTER TABLE `klasemen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `last_match`
--
ALTER TABLE `last_match`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `negara`
--
ALTER TABLE `negara`
  MODIFY `id_negara` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT untuk tabel `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id_notifikasi` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `notifikasi`
--
ALTER TABLE `notifikasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `pemain`
--
ALTER TABLE `pemain`
  MODIFY `id_pemain` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=189;

--
-- AUTO_INCREMENT untuk tabel `pertandingan`
--
ALTER TABLE `pertandingan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `user_preferences`
--
ALTER TABLE `user_preferences`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `klasemen`
--
ALTER TABLE `klasemen`
  ADD CONSTRAINT `klasemen_ibfk_1` FOREIGN KEY (`id_negara`) REFERENCES `negara` (`id_negara`);

--
-- Ketidakleluasaan untuk tabel `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`),
  ADD CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`id_negara`) REFERENCES `negara` (`id_negara`);

--
-- Ketidakleluasaan untuk tabel `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD CONSTRAINT `notifikasi_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`),
  ADD CONSTRAINT `notifikasi_ibfk_2` FOREIGN KEY (`id_pertandingan`) REFERENCES `pertandingan` (`id`);

--
-- Ketidakleluasaan untuk tabel `pemain`
--
ALTER TABLE `pemain`
  ADD CONSTRAINT `pemain_ibfk_1` FOREIGN KEY (`id_negara`) REFERENCES `negara` (`id_negara`);

--
-- Ketidakleluasaan untuk tabel `user_preferences`
--
ALTER TABLE `user_preferences`
  ADD CONSTRAINT `user_preferences_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`),
  ADD CONSTRAINT `user_preferences_ibfk_2` FOREIGN KEY (`id_negara`) REFERENCES `negara` (`id_negara`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
