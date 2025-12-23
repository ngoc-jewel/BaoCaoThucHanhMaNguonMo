-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1:3306
-- Thời gian đã tạo: Th12 12, 2025 lúc 03:28 PM
-- Phiên bản máy phục vụ: 9.1.0
-- Phiên bản PHP: 8.3.14
-- Cấu trúc bảng cho bảng `adminuser`
DROP TABLE IF EXISTS `adminuser`;
CREATE TABLE IF NOT EXISTS `adminuser` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `role` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT 'admin',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- Cấu trúc bảng cho bảng `chitietdonhang`
DROP TABLE IF EXISTS `chitietdonhang`;
CREATE TABLE IF NOT EXISTS `chitietdonhang` (
  `id` int NOT NULL AUTO_INCREMENT,
  `donhang_id` int NOT NULL,
  `monan_id` int NOT NULL,
  `loai` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `so_luong` int NOT NULL,
  `gia` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `donhang_id` (`donhang_id`),
  KEY `monan_id` (`monan_id`)
) ENGINE=MyISAM AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `combo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `TenCombo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `GiaGoc` int NOT NULL,
  `GiamGia` int NOT NULL,
  `GiaCuoi` int NOT NULL,
  `HinhAnh` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ChiTiet` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `combo_mon` (
  `id` int NOT NULL AUTO_INCREMENT,
  `combo_id` int NOT NULL,
  `mon_id` int NOT NULL,
  `loai` enum('MonAn','MonNuoc','TrangMieng') COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `combo_id` (`combo_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Cấu trúc bảng cho bảng `diachigiaohang`

DROP TABLE IF EXISTS `diachigiaohang`;
CREATE TABLE IF NOT EXISTS `diachigiaohang` (
  `dia_chi_id` int NOT NULL AUTO_INCREMENT,
  `khach_hang_id` int DEFAULT NULL,
  `dia_chi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `thanh_pho` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quan_huyen` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phuong_xa` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ghi_chu` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`dia_chi_id`),
  KEY `khach_hang_id` (`khach_hang_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- Cấu trúc bảng cho bảng `donhang`
--

DROP TABLE IF EXISTS `donhang`;
CREATE TABLE IF NOT EXISTS `donhang` (
  `don_hang_id` int NOT NULL AUTO_INCREMENT,
  `khach_hang_id` int DEFAULT NULL,
  `dia_chi_id` int DEFAULT NULL,
  `phuong_thuc_id` int DEFAULT NULL,
  `tong_tien` decimal(12,2) DEFAULT NULL,
  `ngay_dat` datetime DEFAULT NULL,
  `trang_thai` enum('cho_duyet','dang_xu_ly','dang_giao','hoan_thanh','huy') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ghi_chu` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`don_hang_id`),
  KEY `khach_hang_id` (`khach_hang_id`),
  KEY `dia_chi_id` (`dia_chi_id`),
  KEY `phuong_thuc_id` (`phuong_thuc_id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- Cấu trúc bảng cho bảng `donhangdahuy`
--

DROP TABLE IF EXISTS `donhangdahuy`;
CREATE TABLE IF NOT EXISTS `donhangdahuy` (
  `don_hang_id` int NOT NULL,
  `khach_hang_id` int NOT NULL,
  `ngay_dat` datetime DEFAULT NULL,
  `tong_tien` decimal(10,2) DEFAULT NULL,
  `trang_thai` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ly_do` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ngay_huy` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`don_hang_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--

-- Cấu trúc bảng cho bảng `giaohang`
--

DROP TABLE IF EXISTS `giaohang`;
CREATE TABLE IF NOT EXISTS `giaohang` (
  `id` int NOT NULL AUTO_INCREMENT,
  `donhang_id` int DEFAULT NULL,
  `dia_chi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sdt` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trang_thai` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ngay_giao_du_kien` date DEFAULT NULL,
  `ngay_giao_thuc_te` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `donhang_id` (`donhang_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- Cấu trúc bảng cho bảng `khachhang`
--

DROP TABLE IF EXISTS `khachhang`;
CREATE TABLE IF NOT EXISTS `khachhang` (
  `id` int NOT NULL AUTO_INCREMENT,
  `Ten` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `MatKhau` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `NgayTao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Email` (`Email`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- Cấu trúc bảng cho bảng `khuyenmai`
--

DROP TABLE IF EXISTS `khuyenmai`;
CREATE TABLE IF NOT EXISTS `khuyenmai` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ten_km` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mo_ta` text COLLATE utf8mb4_unicode_ci,
  `giam_gia` int DEFAULT NULL,
  `ngay_bat_dau` date DEFAULT NULL,
  `ngay_ket_thuc` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-
-- Cấu trúc bảng cho bảng `monan`

CREATE TABLE IF NOT EXISTS `monan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `TenMon` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Gia` decimal(10,2) DEFAULT NULL,
  `HinhAnh` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ChiTiet` text COLLATE utf8mb4_unicode_ci,
  `NgayTao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `LoaiMonAn` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Cấu trúc bảng cho bảng `monnoibat`
--


CREATE TABLE IF NOT EXISTS `monnoibat` (
  `id` int NOT NULL AUTO_INCREMENT,
  `mon_id` int NOT NULL,
  `loai` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- Cấu trúc bảng cho bảng `monnuoc`
--

DROP TABLE IF EXISTS `monnuoc`;
CREATE TABLE IF NOT EXISTS `monnuoc` (
  `id` int NOT NULL AUTO_INCREMENT,
  `TenNuoc` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Gia` int NOT NULL,
  `HinhAnh` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ChiTiet` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



DROP TABLE IF EXISTS `phuongthucgiaohang`;
CREATE TABLE IF NOT EXISTS `phuongthucgiaohang` (
  `phuong_thuc_id` int NOT NULL AUTO_INCREMENT,
  `ten_phuong_thuc` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mo_ta` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`phuong_thuc_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `thanhtoan`
--

DROP TABLE IF EXISTS `thanhtoan`;
CREATE TABLE IF NOT EXISTS `thanhtoan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `donhang_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `phuong_thuc` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ho_ten` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `so_dien_thoai` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dia_chi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `tong_tien` decimal(12,2) NOT NULL,
  `ngay_tao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `trang_thai` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'Chờ xử lý',
  PRIMARY KEY (`id`),
  KEY `donhang_id` (`donhang_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--

-- Cấu trúc bảng cho bảng `trangmieng`
--

DROP TABLE IF EXISTS `trangmieng`;
CREATE TABLE IF NOT EXISTS `trangmieng` (
  `id` int NOT NULL AUTO_INCREMENT,
  `TenTM` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Gia` int NOT NULL,
  `HinhAnh` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ChiTiet` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- Cấu trúc bảng cho bảng `trang_thai_don_hang`
--

DROP TABLE IF EXISTS `trang_thai_don_hang`;
CREATE TABLE IF NOT EXISTS `trang_thai_don_hang` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ten_trang_thai` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE taixe (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hoten VARCHAR(100) NOT NULL,
    sdt VARCHAR(15) NOT NULL UNIQUE,
    cccd VARCHAR(20) UNIQUE,
    diachi VARCHAR(255),
    bien_so VARCHAR(20),
    trangthai ENUM('Rảnh', 'Đang giao', 'Nghỉ') DEFAULT 'Rảnh',
    ngay_tao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
