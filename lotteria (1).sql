-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th6 07, 2026 lúc 04:55 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `lotteria`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chitietdondathang`
--

CREATE TABLE `chitietdondathang` (
  `MaDonDatHang` varchar(10) NOT NULL,
  `MaNguyenLieu` varchar(10) NOT NULL,
  `SoLuongDat` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `chitietdondathang`
--

INSERT INTO `chitietdondathang` (`MaDonDatHang`, `MaNguyenLieu`, `SoLuongDat`) VALUES
('DDH001', 'NL001', 20),
('DDH001', 'NL003', 100),
('DDH001', 'NL004', 30),
('DDH002', 'NL002', 15),
('DDH002', 'NL006', 25),
('DDH003', 'NL005', 10),
('DDH003', 'NL008', 5),
('DDH004', 'NL001', 50),
('DDH004', 'NL002', 1),
('DDH005', 'NL005', 30),
('DDH005', 'NL007', 20),
('DDH005', 'NL008', 15),
('DDH005', 'NL009', 20),
('DDH006', 'NL001', 1),
('DDH007', 'NL001', 1),
('DDH008', 'NL008', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chitietphieuhuy`
--

CREATE TABLE `chitietphieuhuy` (
  `MaPhieuHuy` varchar(10) NOT NULL,
  `MaNguyenLieu` varchar(10) NOT NULL,
  `SoLuongHuy` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `chitietphieuhuy`
--

INSERT INTO `chitietphieuhuy` (`MaPhieuHuy`, `MaNguyenLieu`, `SoLuongHuy`) VALUES
('PH001', 'NL001', 1),
('PH002', 'NL001', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chitietphieukiemkecuoingay`
--

CREATE TABLE `chitietphieukiemkecuoingay` (
  `MaPhieuKiemKe` varchar(10) NOT NULL,
  `MaNguyenLieu` varchar(10) NOT NULL,
  `SoLuongHeThong` int(11) NOT NULL,
  `SoLuongThucTe` int(11) NOT NULL,
  `ChenhLech` int(11) NOT NULL,
  `TinhTrang` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `chitietphieukiemkecuoingay`
--

INSERT INTO `chitietphieukiemkecuoingay` (`MaPhieuKiemKe`, `MaNguyenLieu`, `SoLuongHeThong`, `SoLuongThucTe`, `ChenhLech`, `TinhTrang`) VALUES
('PKK003', 'NL001', 0, 0, 0, 'Ghi nhận'),
('PKK004', 'NL001', 0, 0, 0, 'Ghi nhận'),
('PKK005', 'NL001', 0, 0, 0, 'Ghi nhận'),
('PKK006', 'NL001', 0, 0, 0, 'Ghi nhận'),
('PKK4606', 'NL001', 0, 0, 0, 'Ghi nhận');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chitietphieukiemkedinhky`
--

CREATE TABLE `chitietphieukiemkedinhky` (
  `MaPhieuKiemKe` varchar(10) NOT NULL,
  `MaLoHang` varchar(10) NOT NULL,
  `SoLuongHeThong` int(11) NOT NULL,
  `SoLuongThucTe` int(11) NOT NULL,
  `ChenhLech` int(11) NOT NULL,
  `TinhTrang` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `chitietphieukiemkedinhky`
--

INSERT INTO `chitietphieukiemkedinhky` (`MaPhieuKiemKe`, `MaLoHang`, `SoLuongHeThong`, `SoLuongThucTe`, `ChenhLech`, `TinhTrang`) VALUES
('PKK5327', 'LH001', 13, 13, 0, 'Khớp'),
('PKK5327', 'LH002', 94, 94, 0, 'Khớp'),
('PKK5327', 'LH003', 28, 28, 0, 'Khớp'),
('PKK5327', 'LH004', 12, 12, 0, 'Khớp'),
('PKK5327', 'LH005', 10, 10, 0, 'Khớp'),
('PKK5327', 'LH006', 30, 30, 0, 'Khớp'),
('PKK5327', 'LH007', 20, 20, 0, 'Khớp'),
('PKK5327', 'LH008', 13, 13, 0, 'Khớp'),
('PKK5327', 'LH009', 20, 20, 0, 'Khớp'),
('PKK5327', 'LH010', 0, 0, 0, 'Khớp'),
('PKK5327', 'LH011', 0, 0, 0, 'Khớp'),
('PKK5327', 'LH012', 1, 1, 0, 'Khớp');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chitietphieuxuat`
--

CREATE TABLE `chitietphieuxuat` (
  `MaPhieuXuat` varchar(10) NOT NULL,
  `MaLoHang` varchar(10) NOT NULL,
  `SoLuongXuat` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `chitietphieuxuat`
--

INSERT INTO `chitietphieuxuat` (`MaPhieuXuat`, `MaLoHang`, `SoLuongXuat`) VALUES
('PX001', 'LH001', 2),
('PX001', 'LH002', 5),
('PX002', 'LH003', 2),
('PX003', 'LH004', 1),
('PX004', 'LH001', 1),
('PX005', 'LH001', 1),
('PX006', 'LH001', 1),
('PX007', 'LH001', 1),
('PX008', 'LH002', 1),
('PX009', 'LH001', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `dondathang`
--

CREATE TABLE `dondathang` (
  `MaDonDatHang` varchar(10) NOT NULL,
  `NgayDat` date NOT NULL,
  `TrangThai` varchar(50) NOT NULL,
  `GhiChu` varchar(255) DEFAULT NULL,
  `MaTaiKhoan` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `dondathang`
--

INSERT INTO `dondathang` (`MaDonDatHang`, `NgayDat`, `TrangThai`, `GhiChu`, `MaTaiKhoan`) VALUES
('DDH001', '2026-05-01', 'Đã nhập kho', NULL, 'QL001'),
('DDH002', '2026-05-10', 'Đã nhận hàng', 'Giao sáng', 'QL001'),
('DDH003', '2026-05-20', 'Chờ xử lý', 'Đơn khẩn | Phê duyệt bởi CHT001: Đồng ý', 'QL002'),
('DDH004', '2026-06-06', 'Chờ xử lý', 'Phê duyệt bởi CHT001: Đồng ý', 'QL003'),
('DDH005', '2026-04-15', 'Đã nhập kho', NULL, 'QL001'),
('DDH006', '2026-06-05', 'Đang đổi trả', 'Phê duyệt bởi CHT001: ok | Tạo phiếu đổi trả bởi QL003: Đổi hàng: thiếu hàng', 'QL003'),
('DDH007', '2026-06-06', 'Chờ xử lý', 'Phê duyệt bởi CHT001: Đồng ý', 'QL003'),
('DDH008', '2026-06-07', 'Đã nhận hàng', 'Phê duyệt bởi CHT001: Đồng ý cho đơn hàng DH008', 'QL003');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `LoHang`
--

CREATE TABLE `LoHang` (
  `MaLoHang` varchar(10) NOT NULL,
  `NgaySanXuat` date NOT NULL,
  `HanSuDung` date NOT NULL,
  `SoLuongNhap` int(11) NOT NULL,
  `SoLuongConLai` int(11) NOT NULL,
  `TrangThai` varchar(50) NOT NULL,
  `MaNguyenLieu` varchar(10) NOT NULL,
  `MaPhieuNhan` varchar(10) DEFAULT NULL,
  `MaPhieuDoiTra` varchar(10) DEFAULT NULL,
  `MaPhieuNhap` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `LoHang`
--

INSERT INTO `LoHang` (`MaLoHang`, `NgaySanXuat`, `HanSuDung`, `SoLuongNhap`, `SoLuongConLai`, `TrangThai`, `MaNguyenLieu`, `MaPhieuNhan`, `MaPhieuDoiTra`, `MaPhieuNhap`) VALUES
('LH001', '2026-04-01', '2026-07-01', 20, 13, 'Còn hạn', 'NL001', 'PN001', NULL, 'PNK001'),
('LH002', '2026-03-15', '2026-06-15', 100, 94, 'Sắp hết hạn', 'NL003', 'PN001', NULL, 'PNK001'),
('LH003', '2026-04-10', '2026-06-10', 30, 28, 'Sắp hết hạn', 'NL004', 'PN001', NULL, 'PNK001'),
('LH004', '2026-05-01', '2026-08-01', 15, 12, 'Còn hạn', 'NL002', 'PN002', NULL, 'PNK002'),
('LH005', '2026-02-01', '2026-09-30', 25, 10, 'Còn hạn', 'NL006', 'PN002', 'PDT002', 'PNK002'),
('LH006', '2026-04-10', '2026-10-10', 30, 30, 'Còn hạn', 'NL005', 'PN003', NULL, 'PNK003'),
('LH007', '2026-04-10', '2026-07-10', 20, 20, 'Còn hạn', 'NL007', 'PN003', NULL, 'PNK003'),
('LH008', '2026-04-10', '2027-04-10', 15, 13, 'Còn hạn', 'NL008', 'PN003', NULL, 'PNK003'),
('LH009', '2026-04-10', '2027-04-10', 20, 20, 'Còn hạn', 'NL009', 'PN003', NULL, 'PNK003'),
('LH010', '2026-05-30', '2026-11-30', 0, 0, 'Sắp hết hạn', 'NL001', 'PN004', NULL, NULL),
('LH011', '2026-05-07', '2026-09-04', 0, 0, 'Sắp hết hạn', 'NL001', 'PN005', NULL, NULL),
('LH012', '2026-05-07', '2026-09-09', 1, 1, 'Còn hạn', 'NL008', 'PN006', NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `NguyenLieu`
--

CREATE TABLE `NguyenLieu` (
  `MaNguyenLieu` varchar(10) NOT NULL,
  `TenNguyenLieu` varchar(100) NOT NULL,
  `DonViTinh` varchar(20) NOT NULL,
  `NhomHang` varchar(50) NOT NULL,
  `SoLuongTonKho` int(11) NOT NULL DEFAULT 0,
  `MoTa` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `NguyenLieu`
--

INSERT INTO `NguyenLieu` (`MaNguyenLieu`, `TenNguyenLieu`, `DonViTinh`, `NhomHang`, `SoLuongTonKho`, `MoTa`) VALUES
('NL001', 'Thịt bò patty', 'Kg', 'Hàng đông', 13, 'Thịt bò xay đông'),
('NL002', 'Thịt gà fillet', 'Kg', 'Hàng đông', 12, 'Ức gà phi lê đông lạnh'),
('NL003', 'Bánh mì hamburger', 'Cái', 'Hàng khô', 94, 'Bánh mì tròn'),
('NL004', 'Khoai tây chiên', 'Kg', 'Hàng đông', 28, 'Khoai tây cắt sợi đông lạnh'),
('NL005', 'Bơ sốt mayonnaise', 'Lọ', 'Hàng khô', 30, 'Sốt mayonnaise đóng lọ 500g'),
('NL006', 'Phô mai slice', 'Gói', 'Hàng đông', 0, 'Phô mai lát đóng gói'),
('NL007', 'Xà lách', 'Kg', 'Hàng đông', 20, 'Xà lách tươi đông lạnh'),
('NL008', 'Muối', 'Kg', 'Hàng khô', 16, NULL),
('NL009', 'Gạo', 'Túi', 'Hàng khô', 20, 'Gạo bắc thơm');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phieudoitra`
--

CREATE TABLE `phieudoitra` (
  `MaPhieuDoiTra` varchar(10) NOT NULL,
  `NgayTao` date NOT NULL,
  `LoaiXuLy` varchar(50) NOT NULL,
  `LyDo` varchar(255) NOT NULL,
  `MaTaiKhoan` varchar(10) NOT NULL,
  `MaPhieuNhan` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `phieudoitra`
--

INSERT INTO `phieudoitra` (`MaPhieuDoiTra`, `NgayTao`, `LoaiXuLy`, `LyDo`, `MaTaiKhoan`, `MaPhieuNhan`) VALUES
('PDT001', '2026-05-04', 'Đổi hàng', 'Hàng bị hỏng', 'NV001', 'PN001'),
('PDT002', '2026-05-13', 'Trả hàng', 'Giao thiếu số lượng', 'NV002', 'PN002'),
('PDT003', '2026-06-06', 'Đổi hàng', 'thiếu hàng', 'QL003', 'PN004');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phieugiaitrinh`
--

CREATE TABLE `phieugiaitrinh` (
  `MaPhieuGiaiTrinh` varchar(10) NOT NULL,
  `NgayTao` date NOT NULL,
  `NoiDung` varchar(255) NOT NULL,
  `NguyenNhan` varchar(255) NOT NULL,
  `MaTaiKhoan` varchar(10) NOT NULL,
  `MaPhieuKiemKe` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `phieugiaitrinh`
--

INSERT INTO `phieugiaitrinh` (`MaPhieuGiaiTrinh`, `NgayTao`, `NoiDung`, `NguyenNhan`, `MaTaiKhoan`, `MaPhieuKiemKe`) VALUES
('PGT5325', '2026-06-07', 'Hàng hỏng bất thường [Bằng chứng: BCKK]', 'Sự cố tủ lạnh', 'QL003', 'PKK5327');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phieukiemke`
--

CREATE TABLE `phieukiemke` (
  `MaPhieuKiemKe` varchar(10) NOT NULL,
  `NgayKiemKe` date NOT NULL,
  `LoaiKiemKe` varchar(50) NOT NULL,
  `TrangThai` varchar(50) NOT NULL,
  `GhiChu` varchar(255) DEFAULT NULL,
  `MaTaiKhoan` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `phieukiemke`
--

INSERT INTO `phieukiemke` (`MaPhieuKiemKe`, `NgayKiemKe`, `LoaiKiemKe`, `TrangThai`, `GhiChu`, `MaTaiKhoan`) VALUES
('PKK002', '2026-05-20', 'Cuối kỳ', 'Chờ duyệt', 'Kiểm kỳ T5', 'QL002'),
('PKK003', '2026-06-06', 'Cuối ngày', 'Đã duyệt', NULL, 'NV003'),
('PKK004', '2026-06-06', 'Cuối ngày', 'Chờ duyệt', NULL, 'NV003'),
('PKK005', '2026-06-07', 'Cuối ngày', 'Đã duyệt', NULL, 'NV003'),
('PKK006', '2026-06-07', 'Cuối ngày', 'Đã duyệt', NULL, 'NV003'),
('PKK4605', '2026-06-07', 'Định kỳ', 'Chờ duyệt', NULL, 'NV003'),
('PKK4606', '2026-06-07', 'Cuối ngày', 'Đã duyệt', NULL, 'NV003'),
('PKK5327', '2026-06-07', 'Định kỳ', 'Đã duyệt', NULL, 'NV003');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phieunhanhang`
--

CREATE TABLE `phieunhanhang` (
  `MaPhieuNhan` varchar(10) NOT NULL,
  `NgayNhan` date NOT NULL,
  `GhiChu` varchar(255) DEFAULT NULL,
  `MaTaiKhoan` varchar(10) NOT NULL,
  `MaDonDatHang` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `phieunhanhang`
--

INSERT INTO `phieunhanhang` (`MaPhieuNhan`, `NgayNhan`, `GhiChu`, `MaTaiKhoan`, `MaDonDatHang`) VALUES
('PN001', '2026-05-03', NULL, 'NV001', 'DDH001'),
('PN002', '2026-05-12', 'Thiếu 2 kg', 'NV002', 'DDH002'),
('PN003', '2026-04-16', NULL, 'NV001', 'DDH005'),
('PN004', '2026-06-06', NULL, 'NV003', 'DDH006'),
('PN005', '2026-06-07', NULL, 'NV003', 'DDH007'),
('PN006', '2026-06-07', NULL, 'NV003', 'DDH008');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phieunhapkho`
--

CREATE TABLE `phieunhapkho` (
  `MaPhieuNhap` varchar(10) NOT NULL,
  `NgayNhap` date NOT NULL,
  `GhiChu` varchar(255) DEFAULT NULL,
  `MaTaiKhoan` varchar(10) NOT NULL,
  `MaPhieuNhan` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `phieunhapkho`
--

INSERT INTO `phieunhapkho` (`MaPhieuNhap`, `NgayNhap`, `GhiChu`, `MaTaiKhoan`, `MaPhieuNhan`) VALUES
('PNK001', '2026-05-04', NULL, 'NV001', 'PN001'),
('PNK002', '2026-05-13', 'Nhập bù', 'NV002', 'PN002'),
('PNK003', '2026-04-17', NULL, 'NV001', 'PN003');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phieuxuathuy`
--

CREATE TABLE `phieuxuathuy` (
  `MaPhieuHuy` varchar(10) NOT NULL,
  `NgayTao` date NOT NULL,
  `LyDoHuy` varchar(255) NOT NULL,
  `TrangThai` varchar(50) NOT NULL,
  `MaTaiKhoan` varchar(10) NOT NULL,
  `MaPhieuKiemKe` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `phieuxuathuy`
--

INSERT INTO `phieuxuathuy` (`MaPhieuHuy`, `NgayTao`, `LyDoHuy`, `TrangThai`, `MaTaiKhoan`, `MaPhieuKiemKe`) VALUES
('PH001', '2026-06-06', 'NL001: Hư hỏng do nhiệt độ bếp', 'Đã duyệt', 'NV003', 'PKK003'),
('PH002', '2026-06-07', 'NL001: Hư hỏng do nhiệt độ bếp', 'Đã duyệt', 'NV003', 'PKK005');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phieuxuatkho`
--

CREATE TABLE `phieuxuatkho` (
  `MaPhieuXuat` varchar(10) NOT NULL,
  `NgayXuat` date NOT NULL,
  `TrangThai` varchar(50) NOT NULL,
  `MaTaiKhoan` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `phieuxuatkho`
--

INSERT INTO `phieuxuatkho` (`MaPhieuXuat`, `NgayXuat`, `TrangThai`, `MaTaiKhoan`) VALUES
('PX001', '2026-05-05', 'Hoàn tất', 'NV001'),
('PX002', '2026-05-15', 'Chờ xuất', 'NV003'),
('PX003', '2026-06-04', 'Hoàn tất', 'QL001'),
('PX004', '2026-06-06', 'Hoàn tất', 'QL001'),
('PX005', '2026-06-06', 'Hoàn tất', 'QL003'),
('PX006', '2026-06-06', 'Hoàn tất', 'QL003'),
('PX007', '2026-06-06', 'Hoàn tất', 'QL003'),
('PX008', '2026-06-06', 'Hoàn tất', 'QL003'),
('PX009', '2026-06-07', 'Hoàn tất', 'QL003');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `taikhoan`
--

CREATE TABLE `taikhoan` (
  `MaTaiKhoan` varchar(10) NOT NULL,
  `HoTen` varchar(100) NOT NULL,
  `MatKhau` varchar(255) NOT NULL,
  `SoDienThoai` varchar(10) NOT NULL,
  `VaiTro` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `taikhoan`
--

INSERT INTO `taikhoan` (`MaTaiKhoan`, `HoTen`, `MatKhau`, `SoDienThoai`, `VaiTro`) VALUES
('CHT001', 'Nguyễn Văn An', '$2y$12$KJEmsPKg3QIx7KuCi67Qx.bS6sPcX4bkkyQtszdG0LjjlbZ9YuC9S', '0901234567', 'Cửa hàng trưởng'),
('NV001', 'Phạm Thị Dung', '$2y$12$FGQNkhuNX17kw6BWffK2Yu7NoTMtPxom0dHSM93K2rHoWoMeVOqI.', '0934567890', 'Nhân viên'),
('NV002', 'Hoàng Văn Anh', '$2y$12$6j53fvR3S7Ma2ynebi.CFee2lD/XitYptoz.tmMtYJIHQjYxE0Snq', '0945678901', 'Nhân viên'),
('NV003', 'Đỗ Thị Phương', '$2y$12$qAplDtdK7n65ia1NdNTeluZm/gze68mUpGlvgcXcoOmu3p22S4Dye', '0956789012', 'Nhân viên'),
('NV004', 'Phạm Thị Thu', '$2y$12$QClHINZJp1vVGPIf3uJxDuyuJ9AC20Rd7wI3cpwk6DSdl..iPV9XK', '0345375000', 'Nhân viên'),
('QL001', 'Trần Thị Bình', '$2y$12$MBPM1cgcK3D7rhA5r9s3QuqmXtrGrVqYdkZrbvEe1Ek2PtnyDYgVe', '0912345678', 'Quản lý'),
('QL002', 'Lê Văn Cường', '$2y$12$7h/6uNGWqXG16p/4Lo2TXeEVkJEeZDJ8xtelVJOthUDNHYzuANZrS', '0923456789', 'Quản lý'),
('QL003', 'Tô Thị Phương Anh', '$2y$12$PPXQtDqbWolAhp.SkYVE2.vzK8/tv6BUVyPn0XKfNGbypFgVCgQEa', '0111111111', 'Quản lý');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `chitietdondathang`
--
ALTER TABLE `chitietdondathang`
  ADD PRIMARY KEY (`MaDonDatHang`,`MaNguyenLieu`),
  ADD KEY `MaNguyenLieu` (`MaNguyenLieu`);

--
-- Chỉ mục cho bảng `chitietphieuhuy`
--
ALTER TABLE `chitietphieuhuy`
  ADD PRIMARY KEY (`MaPhieuHuy`,`MaNguyenLieu`),
  ADD KEY `MaNguyenLieu` (`MaNguyenLieu`);

--
-- Chỉ mục cho bảng `chitietphieukiemkecuoingay`
--
ALTER TABLE `chitietphieukiemkecuoingay`
  ADD PRIMARY KEY (`MaPhieuKiemKe`,`MaNguyenLieu`),
  ADD KEY `MaNguyenLieu` (`MaNguyenLieu`);

--
-- Chỉ mục cho bảng `chitietphieukiemkedinhky`
--
ALTER TABLE `chitietphieukiemkedinhky`
  ADD PRIMARY KEY (`MaPhieuKiemKe`,`MaLoHang`),
  ADD KEY `MaLoHang` (`MaLoHang`);

--
-- Chỉ mục cho bảng `chitietphieuxuat`
--
ALTER TABLE `chitietphieuxuat`
  ADD PRIMARY KEY (`MaPhieuXuat`,`MaLoHang`),
  ADD KEY `MaLoHang` (`MaLoHang`);

--
-- Chỉ mục cho bảng `dondathang`
--
ALTER TABLE `dondathang`
  ADD PRIMARY KEY (`MaDonDatHang`),
  ADD KEY `MaTaiKhoan` (`MaTaiKhoan`);

--
-- Chỉ mục cho bảng `LoHang`
--
ALTER TABLE `LoHang`
  ADD PRIMARY KEY (`MaLoHang`),
  ADD KEY `MaNguyenLieu` (`MaNguyenLieu`),
  ADD KEY `MaPhieuNhan` (`MaPhieuNhan`),
  ADD KEY `MaPhieuDoiTra` (`MaPhieuDoiTra`),
  ADD KEY `MaPhieuNhap` (`MaPhieuNhap`);

--
-- Chỉ mục cho bảng `NguyenLieu`
--
ALTER TABLE `NguyenLieu`
  ADD PRIMARY KEY (`MaNguyenLieu`);

--
-- Chỉ mục cho bảng `phieudoitra`
--
ALTER TABLE `phieudoitra`
  ADD PRIMARY KEY (`MaPhieuDoiTra`),
  ADD KEY `MaTaiKhoan` (`MaTaiKhoan`),
  ADD KEY `MaPhieuNhan` (`MaPhieuNhan`);

--
-- Chỉ mục cho bảng `phieugiaitrinh`
--
ALTER TABLE `phieugiaitrinh`
  ADD PRIMARY KEY (`MaPhieuGiaiTrinh`),
  ADD KEY `MaTaiKhoan` (`MaTaiKhoan`),
  ADD KEY `MaPhieuKiemKe` (`MaPhieuKiemKe`);

--
-- Chỉ mục cho bảng `phieukiemke`
--
ALTER TABLE `phieukiemke`
  ADD PRIMARY KEY (`MaPhieuKiemKe`),
  ADD KEY `MaTaiKhoan` (`MaTaiKhoan`);

--
-- Chỉ mục cho bảng `phieunhanhang`
--
ALTER TABLE `phieunhanhang`
  ADD PRIMARY KEY (`MaPhieuNhan`),
  ADD KEY `MaTaiKhoan` (`MaTaiKhoan`),
  ADD KEY `MaDonDatHang` (`MaDonDatHang`);

--
-- Chỉ mục cho bảng `phieunhapkho`
--
ALTER TABLE `phieunhapkho`
  ADD PRIMARY KEY (`MaPhieuNhap`),
  ADD KEY `MaTaiKhoan` (`MaTaiKhoan`),
  ADD KEY `MaPhieuNhan` (`MaPhieuNhan`);

--
-- Chỉ mục cho bảng `phieuxuathuy`
--
ALTER TABLE `phieuxuathuy`
  ADD PRIMARY KEY (`MaPhieuHuy`),
  ADD KEY `MaTaiKhoan` (`MaTaiKhoan`),
  ADD KEY `MaPhieuKiemKe` (`MaPhieuKiemKe`);

--
-- Chỉ mục cho bảng `phieuxuatkho`
--
ALTER TABLE `phieuxuatkho`
  ADD PRIMARY KEY (`MaPhieuXuat`),
  ADD KEY `MaTaiKhoan` (`MaTaiKhoan`);

--
-- Chỉ mục cho bảng `taikhoan`
--
ALTER TABLE `taikhoan`
  ADD PRIMARY KEY (`MaTaiKhoan`),
  ADD UNIQUE KEY `SoDienThoai` (`SoDienThoai`);

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `chitietdondathang`
--
ALTER TABLE `chitietdondathang`
  ADD CONSTRAINT `chitietdondathang_ibfk_1` FOREIGN KEY (`MaDonDatHang`) REFERENCES `dondathang` (`MaDonDatHang`),
  ADD CONSTRAINT `chitietdondathang_ibfk_2` FOREIGN KEY (`MaNguyenLieu`) REFERENCES `NguyenLieu` (`MaNguyenLieu`);

--
-- Các ràng buộc cho bảng `chitietphieuhuy`
--
ALTER TABLE `chitietphieuhuy`
  ADD CONSTRAINT `chitietphieuhuy_ibfk_1` FOREIGN KEY (`MaPhieuHuy`) REFERENCES `phieuxuathuy` (`MaPhieuHuy`),
  ADD CONSTRAINT `chitietphieuhuy_ibfk_2` FOREIGN KEY (`MaNguyenLieu`) REFERENCES `NguyenLieu` (`MaNguyenLieu`);

--
-- Các ràng buộc cho bảng `chitietphieukiemkecuoingay`
--
ALTER TABLE `chitietphieukiemkecuoingay`
  ADD CONSTRAINT `chitietphieukiemkecuoingay_ibfk_1` FOREIGN KEY (`MaPhieuKiemKe`) REFERENCES `phieukiemke` (`MaPhieuKiemKe`),
  ADD CONSTRAINT `chitietphieukiemkecuoingay_ibfk_2` FOREIGN KEY (`MaNguyenLieu`) REFERENCES `NguyenLieu` (`MaNguyenLieu`);

--
-- Các ràng buộc cho bảng `chitietphieukiemkedinhky`
--
ALTER TABLE `chitietphieukiemkedinhky`
  ADD CONSTRAINT `chitietphieukiemkedinhky_ibfk_1` FOREIGN KEY (`MaPhieuKiemKe`) REFERENCES `phieukiemke` (`MaPhieuKiemKe`),
  ADD CONSTRAINT `chitietphieukiemkedinhky_ibfk_2` FOREIGN KEY (`MaLoHang`) REFERENCES `LoHang` (`MaLoHang`);

--
-- Các ràng buộc cho bảng `chitietphieuxuat`
--
ALTER TABLE `chitietphieuxuat`
  ADD CONSTRAINT `chitietphieuxuat_ibfk_1` FOREIGN KEY (`MaPhieuXuat`) REFERENCES `phieuxuatkho` (`MaPhieuXuat`),
  ADD CONSTRAINT `chitietphieuxuat_ibfk_2` FOREIGN KEY (`MaLoHang`) REFERENCES `LoHang` (`MaLoHang`);

--
-- Các ràng buộc cho bảng `dondathang`
--
ALTER TABLE `dondathang`
  ADD CONSTRAINT `dondathang_ibfk_1` FOREIGN KEY (`MaTaiKhoan`) REFERENCES `taikhoan` (`MaTaiKhoan`);

--
-- Các ràng buộc cho bảng `LoHang`
--
ALTER TABLE `LoHang`
  ADD CONSTRAINT `LoHang_ibfk_1` FOREIGN KEY (`MaNguyenLieu`) REFERENCES `NguyenLieu` (`MaNguyenLieu`),
  ADD CONSTRAINT `LoHang_ibfk_2` FOREIGN KEY (`MaPhieuNhan`) REFERENCES `phieunhanhang` (`MaPhieuNhan`),
  ADD CONSTRAINT `LoHang_ibfk_3` FOREIGN KEY (`MaPhieuDoiTra`) REFERENCES `phieudoitra` (`MaPhieuDoiTra`),
  ADD CONSTRAINT `LoHang_ibfk_4` FOREIGN KEY (`MaPhieuNhap`) REFERENCES `phieunhapkho` (`MaPhieuNhap`);

--
-- Các ràng buộc cho bảng `phieudoitra`
--
ALTER TABLE `phieudoitra`
  ADD CONSTRAINT `phieudoitra_ibfk_1` FOREIGN KEY (`MaTaiKhoan`) REFERENCES `taikhoan` (`MaTaiKhoan`),
  ADD CONSTRAINT `phieudoitra_ibfk_2` FOREIGN KEY (`MaPhieuNhan`) REFERENCES `phieunhanhang` (`MaPhieuNhan`);

--
-- Các ràng buộc cho bảng `phieugiaitrinh`
--
ALTER TABLE `phieugiaitrinh`
  ADD CONSTRAINT `phieugiaitrinh_ibfk_1` FOREIGN KEY (`MaTaiKhoan`) REFERENCES `taikhoan` (`MaTaiKhoan`),
  ADD CONSTRAINT `phieugiaitrinh_ibfk_2` FOREIGN KEY (`MaPhieuKiemKe`) REFERENCES `phieukiemke` (`MaPhieuKiemKe`);

--
-- Các ràng buộc cho bảng `phieukiemke`
--
ALTER TABLE `phieukiemke`
  ADD CONSTRAINT `phieukiemke_ibfk_1` FOREIGN KEY (`MaTaiKhoan`) REFERENCES `taikhoan` (`MaTaiKhoan`);

--
-- Các ràng buộc cho bảng `phieunhanhang`
--
ALTER TABLE `phieunhanhang`
  ADD CONSTRAINT `phieunhanhang_ibfk_1` FOREIGN KEY (`MaTaiKhoan`) REFERENCES `taikhoan` (`MaTaiKhoan`),
  ADD CONSTRAINT `phieunhanhang_ibfk_2` FOREIGN KEY (`MaDonDatHang`) REFERENCES `dondathang` (`MaDonDatHang`);

--
-- Các ràng buộc cho bảng `phieunhapkho`
--
ALTER TABLE `phieunhapkho`
  ADD CONSTRAINT `phieunhapkho_ibfk_1` FOREIGN KEY (`MaTaiKhoan`) REFERENCES `taikhoan` (`MaTaiKhoan`),
  ADD CONSTRAINT `phieunhapkho_ibfk_2` FOREIGN KEY (`MaPhieuNhan`) REFERENCES `phieunhanhang` (`MaPhieuNhan`);

--
-- Các ràng buộc cho bảng `phieuxuathuy`
--
ALTER TABLE `phieuxuathuy`
  ADD CONSTRAINT `phieuxuathuy_ibfk_1` FOREIGN KEY (`MaTaiKhoan`) REFERENCES `taikhoan` (`MaTaiKhoan`),
  ADD CONSTRAINT `phieuxuathuy_ibfk_2` FOREIGN KEY (`MaPhieuKiemKe`) REFERENCES `phieukiemke` (`MaPhieuKiemKe`);

--
-- Các ràng buộc cho bảng `phieuxuatkho`
--
ALTER TABLE `phieuxuatkho`
  ADD CONSTRAINT `phieuxuatkho_ibfk_1` FOREIGN KEY (`MaTaiKhoan`) REFERENCES `taikhoan` (`MaTaiKhoan`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
