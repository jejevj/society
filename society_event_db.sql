/*
 Navicat Premium Dump SQL

 Source Server         : mysql local
 Source Server Type    : MySQL
 Source Server Version : 80030 (8.0.30)
 Source Host           : localhost:3306
 Source Schema         : society_event_db

 Target Server Type    : MySQL
 Target Server Version : 80030 (8.0.30)
 File Encoding         : 65001

 Date: 29/05/2026 17:00:56
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for app_email
-- ----------------------------
DROP TABLE IF EXISTS `app_email`;
CREATE TABLE `app_email`  (
  `id_app_email` int NOT NULL AUTO_INCREMENT,
  `smtp_host` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `smtp_port` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `smtp_encryption` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `smtp_username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `smtp_password` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `smtp_from_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `smtp_from_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id_app_email`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of app_email
-- ----------------------------
INSERT INTO `app_email` VALUES (1, 'smtp.gmail.com', '587', 'tls', 'work.basrilhafi@gmail.com', 'fmwvtodportjulmx', 'work.basrilhafi@gmail.com', 'Satu Data Pertahanan');

-- ----------------------------
-- Table structure for app_log_aktivitas
-- ----------------------------
DROP TABLE IF EXISTS `app_log_aktivitas`;
CREATE TABLE `app_log_aktivitas`  (
  `id_log` bigint NOT NULL AUTO_INCREMENT,
  `ip_log` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `user_id` int NULL DEFAULT NULL,
  `user_log` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `fungsi_log` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `deskripsi_log` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `data_lama_log` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `data_baru_log` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  PRIMARY KEY (`id_log`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 38 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of app_log_aktivitas
-- ----------------------------
INSERT INTO `app_log_aktivitas` VALUES (1, '127.0.0.1', 7, 'admin aplikasi', 'verifyOtpAdminPanelAction', 'Berhasil verifikasi kode OTP', '2026-05-28 23:36:51', NULL, '{\"email\":\"business.basrilhafi@gmail.com\",\"nama\":\"admin aplikasi\",\"otp\":\"\"}', '');
INSERT INTO `app_log_aktivitas` VALUES (2, '127.0.0.1', 7, 'admin aplikasi', 'verifyOtpAdminPanelAction', 'Gagal mengirimkan kode OTP', '2026-05-28 23:38:15', NULL, '{\"email\":\"business.basrilhafi@gmail.com\",\"nama\":\"admin aplikasi\",\"otp\":\"\"}', '');
INSERT INTO `app_log_aktivitas` VALUES (3, '127.0.0.1', 7, 'admin aplikasi', 'verifyOtpAdminPanelAction', 'Gagal mengirimkan kode OTP', '2026-05-28 23:38:23', NULL, '{\"email\":\"business.basrilhafi@gmail.com\",\"nama\":\"admin aplikasi\",\"otp\":\"\"}', '');
INSERT INTO `app_log_aktivitas` VALUES (4, '127.0.0.1', 7, 'admin aplikasi', 'verifyOtpAdminPanelAction', 'Gagal mengirimkan kode OTP', '2026-05-28 23:40:01', NULL, '{\"email\":\"business.basrilhafi@gmail.com\",\"nama\":\"admin aplikasi\",\"otp\":\"\"}', '');
INSERT INTO `app_log_aktivitas` VALUES (5, '127.0.0.1', 7, 'admin aplikasi', 'verifyOtpAdminPanelAction', 'Berhasil verifikasi kode OTP', '2026-05-28 23:45:56', NULL, '{\"email\":\"business.basrilhafi@gmail.com\",\"nama\":\"admin aplikasi\",\"otp\":\"\"}', '');
INSERT INTO `app_log_aktivitas` VALUES (6, '127.0.0.1', 7, 'admin aplikasi', 'logoutBackendAction', 'Logout Aplikasi', '2026-05-28 23:55:17', NULL, '{\"username\":\"business.basrilhafi@gmail.com\",\"nama\":\"admin aplikasi\"}', '');
INSERT INTO `app_log_aktivitas` VALUES (7, '127.0.0.1', 7, 'admin aplikasi', 'verifyOtpAdminPanelAction', 'Berhasil verifikasi kode OTP', '2026-05-28 23:55:21', NULL, '{\"email\":\"business.basrilhafi@gmail.com\",\"nama\":\"admin aplikasi\",\"otp\":\"\"}', '');
INSERT INTO `app_log_aktivitas` VALUES (8, '127.0.0.1', 7, 'admin aplikasi', 'deleteRoleAction', 'Berhasil hapus data role', '2026-05-29 02:06:30', NULL, '{\"id_role\":3,\"nama_role\":\"Organisasi\",\"kode_role\":\"ORG\",\"deskripsi_role\":\"-\",\"created_at\":\"2025-08-31 08:42:59\",\"updated_at\":null,\"all_data_role\":\"N\"}', '');
INSERT INTO `app_log_aktivitas` VALUES (9, '127.0.0.1', 7, 'admin aplikasi', 'updateRoleAction', 'Berhasil ubah data role', '2026-05-29 02:06:43', NULL, '{\"id_role\":4,\"nama_role\":\"User Publik\",\"kode_role\":\"PUB\",\"deskripsi_role\":\"-\",\"created_at\":\"2025-09-01 02:51:24\",\"updated_at\":null,\"all_data_role\":\"N\"}', '{\"nama_role\":\"Public\",\"kode_role\":\"PUB\",\"deskripsi_role\":\"-\",\"all_data_role\":\"N\",\"updated_at\":\"2026-05-29T02:06:43.894031Z\"}');
INSERT INTO `app_log_aktivitas` VALUES (10, '127.0.0.1', 7, 'admin aplikasi', 'addSponsorAction', 'Berhasil tambah data sponsor', '2026-05-29 02:54:40', NULL, '', '{\"nama\":\"INTELLEGENT SCIENCE\",\"urutan\":\"1\",\"logo\":\"sponsor\\/1780023279_sponsor1.png\",\"created_at\":\"2026-05-29T02:54:40.010040Z\"}');
INSERT INTO `app_log_aktivitas` VALUES (11, '127.0.0.1', 7, 'admin aplikasi', 'addSponsorAction', 'Berhasil tambah data sponsor', '2026-05-29 02:57:32', NULL, '', '{\"nama\":\"BioNexus\",\"urutan\":\"2\",\"logo\":\"sponsor\\/1780023452_sponsor2.png\",\"created_at\":\"2026-05-29T02:57:32.009021Z\"}');
INSERT INTO `app_log_aktivitas` VALUES (12, '127.0.0.1', 7, 'admin aplikasi', 'updateSponsorAction', 'Successfully changed sponsor data', '2026-05-29 03:32:56', NULL, '{\"id_sponsor\":2,\"nama\":\"BioNexus\",\"logo\":\"sponsor\\/1780023452_sponsor2.png\",\"urutan\":2,\"created_at\":\"2026-05-29 02:57:32\",\"updated_at\":null}', '{\"nama\":\"BioNexus\",\"urutan\":\"2\",\"updated_at\":\"2026-05-29T03:32:56.378620Z\",\"logo\":\"sponsor\\/1780025576_erd.png\"}');
INSERT INTO `app_log_aktivitas` VALUES (13, '127.0.0.1', 7, 'admin aplikasi', 'updateSponsorAction', 'Successfully changed sponsor data', '2026-05-29 03:33:21', NULL, '{\"id_sponsor\":2,\"nama\":\"BioNexus\",\"logo\":\"sponsor\\/1780025576_erd.png\",\"urutan\":2,\"created_at\":\"2026-05-29 10:32:56\",\"updated_at\":\"2026-05-29 03:32:56\"}', '{\"nama\":\"BioNexus\",\"urutan\":\"2\",\"updated_at\":\"2026-05-29T03:33:21.585318Z\",\"logo\":\"sponsor\\/1780025601_sponsor2.png\"}');
INSERT INTO `app_log_aktivitas` VALUES (14, '127.0.0.1', 7, 'admin aplikasi', 'updateSponsorAction', 'Successfully changed sponsor data', '2026-05-29 03:34:11', NULL, '{\"id_sponsor\":2,\"nama\":\"BioNexus\",\"logo\":\"sponsor\\/1780025601_sponsor2.png\",\"urutan\":2,\"created_at\":\"2026-05-29 10:33:21\",\"updated_at\":\"2026-05-29 03:33:21\"}', '{\"nama\":\"BioNexus\",\"urutan\":\"2\",\"updated_at\":\"2026-05-29T03:34:11.515852Z\"}');
INSERT INTO `app_log_aktivitas` VALUES (15, '127.0.0.1', 7, 'admin aplikasi', 'addSponsorAction', 'Berhasil tambah data sponsor', '2026-05-29 03:34:32', NULL, '', '{\"nama\":\"sdaa\",\"urutan\":\"3\",\"logo\":\"sponsor\\/1780025672_erd.png\",\"created_at\":\"2026-05-29T03:34:32.759455Z\"}');
INSERT INTO `app_log_aktivitas` VALUES (16, '127.0.0.1', 7, 'admin aplikasi', 'deleteTopikAction', 'Berhasil hapus data topik', '2026-05-29 03:34:37', NULL, '{\"id_topik\":3,\"nama_topik\":\"Kekuatan\",\"kode_topik\":\"KUAT\",\"urutan_topik\":2,\"gambar_topik\":\"topik\\/1756719386_kekuatan (1).png\",\"created_at\":\"2025-09-01 07:00:00\",\"updated_at\":\"2025-09-01 09:36:26\",\"deskripsi_topik\":null,\"status_topik\":1}', '');
INSERT INTO `app_log_aktivitas` VALUES (17, '127.0.0.1', 7, 'admin aplikasi', 'deleteSponsorAction', 'Successfully deleted sponsor data', '2026-05-29 03:36:17', NULL, '{\"id_sponsor\":3,\"nama\":\"sdaa\",\"logo\":\"sponsor\\/1780025672_erd.png\",\"urutan\":3,\"created_at\":\"2026-05-29 03:34:32\",\"updated_at\":null}', '');
INSERT INTO `app_log_aktivitas` VALUES (18, '127.0.0.1', 7, 'admin aplikasi', 'logoutBackendAction', 'Logout Aplikasi', '2026-05-29 03:50:42', NULL, '{\"username\":\"business.basrilhafi@gmail.com\",\"nama\":\"admin aplikasi\"}', '');
INSERT INTO `app_log_aktivitas` VALUES (19, '127.0.0.1', 7, 'admin aplikasi', 'verifyOtpAdminPanelAction', 'Login Successfully', '2026-05-29 04:01:13', NULL, '', '');
INSERT INTO `app_log_aktivitas` VALUES (20, '127.0.0.1', 7, 'admin aplikasi', 'deleteUserAction', 'Berhasil hapus data pengguna', '2026-05-29 04:09:48', NULL, '{\"id_user\":6,\"role_id\":4,\"nama_user\":\"Basril\",\"username_user\":\"work2.basrilhafi@gmail.com\",\"password_user\":\"$2y$12$1BEhvF4ANA\\/rgVNp0iiWLudtiohStMandKXYiA03.nee86DNUtKWq\",\"foto_user\":\"organisasi\\/1757586735_search (1).png\",\"status_user\":1,\"created_at\":\"2025-09-02 03:49:02\",\"updated_at\":\"2026-04-30 04:48:07\",\"identitas_user\":\"1234567890123456\",\"file_identitas_user\":\"organisasi\\/1757586781_customer-service (1).png\",\"telepon_user\":\"02144829122\",\"pekerjaan_user\":null,\"alamat_user\":\"Jl. Tipar Cakung No. 101\",\"organisasi_user\":null,\"verify_token\":\"\",\"otp_user\":\"\",\"is_otp\":\"N\"}', '');
INSERT INTO `app_log_aktivitas` VALUES (21, '127.0.0.1', 7, 'admin aplikasi', 'addUserAction', 'Successfully added user data', '2026-05-29 04:16:15', NULL, '', '{\"nama_user\":\"Basril\",\"role_id\":\"1\",\"username_user\":\"work.basrilhafi@gmail.com\",\"password_user\":\"$2y$12$NusUTYh9GpBq.kE0Jvb\\/9uV63NAdeP9UFiSNSoapL08rMVNrfuKWy\",\"foto_user\":\"user\\/1780028175_sponsor1.png\",\"status_user\":1,\"created_at\":\"2026-05-29T04:16:15.167180Z\"}');
INSERT INTO `app_log_aktivitas` VALUES (22, '127.0.0.1', 7, 'admin aplikasi', 'updateUserAction', 'Berhasil ubah data pengguna', '2026-05-29 04:24:48', NULL, '{\"id_user\":17,\"role_id\":1,\"nama_user\":\"Basril\",\"username_user\":\"work.basrilhafi@gmail.com\",\"password_user\":\"$2y$12$NusUTYh9GpBq.kE0Jvb\\/9uV63NAdeP9UFiSNSoapL08rMVNrfuKWy\",\"foto_user\":\"user\\/1780028175_sponsor1.png\",\"status_user\":1,\"created_at\":\"2026-05-29 04:16:15\",\"updated_at\":null,\"identitas_user\":null,\"file_identitas_user\":null,\"telepon_user\":null,\"pekerjaan_user\":null,\"alamat_user\":null,\"organisasi_user\":null,\"verify_token\":null,\"otp_user\":null,\"is_otp\":\"Y\"}', '{\"nama_user\":\"Basril2\",\"username_user\":\"work.basrilhafi@gmail.com\",\"role_id\":\"1\",\"updated_at\":\"2026-05-29T04:24:48.478240Z\",\"foto_user\":\"organisasi\\/1780028688_erd.png\",\"password_user\":\"$2y$12$JNVRp.mbeXmHMhyt8GA2feEtdzOSUcZVN\\/9f5X\\/1cOhhC.0bYBS5q\"}');
INSERT INTO `app_log_aktivitas` VALUES (23, '127.0.0.1', 7, 'admin aplikasi', 'deleteUserAction', 'Successfully deleted user data', '2026-05-29 04:25:03', NULL, '{\"id_user\":17,\"role_id\":1,\"nama_user\":\"Basril2\",\"username_user\":\"work.basrilhafi@gmail.com\",\"password_user\":\"$2y$12$JNVRp.mbeXmHMhyt8GA2feEtdzOSUcZVN\\/9f5X\\/1cOhhC.0bYBS5q\",\"foto_user\":\"organisasi\\/1780028688_erd.png\",\"status_user\":1,\"created_at\":\"2026-05-29 04:16:15\",\"updated_at\":\"2026-05-29 04:24:48\",\"identitas_user\":null,\"file_identitas_user\":null,\"telepon_user\":null,\"pekerjaan_user\":null,\"alamat_user\":null,\"organisasi_user\":null,\"verify_token\":null,\"otp_user\":null,\"is_otp\":\"Y\"}', '');
INSERT INTO `app_log_aktivitas` VALUES (24, '127.0.0.1', 7, 'admin aplikasi', 'deleteTopikAction', 'Berhasil hapus data topik', '2026-05-29 04:32:29', NULL, '{\"id_topik\":1,\"nama_topik\":\"Diklat\",\"kode_topik\":\"DK\",\"urutan_topik\":6,\"gambar_topik\":\"topik\\/1756719417_diklat (1).png\",\"created_at\":\"2025-09-01 04:16:38\",\"updated_at\":\"2025-09-01 09:36:57\",\"deskripsi_topik\":null,\"status_topik\":1}', '');
INSERT INTO `app_log_aktivitas` VALUES (25, '127.0.0.1', 7, 'admin aplikasi', 'deleteTopikAction', 'Berhasil hapus data topik', '2026-05-29 04:35:08', NULL, '{\"id_topik\":6,\"kode_topik\":null,\"nama_topik\":\"Permenhan Tentang Kepegawaian Baru\",\"urutan_topik\":4,\"created_at\":\"2025-09-01 09:37:59\",\"updated_at\":null,\"deskripsi_topik\":null,\"status_topik\":1}', '');
INSERT INTO `app_log_aktivitas` VALUES (26, '127.0.0.1', 7, 'admin aplikasi', 'deleteTopikAction', 'Berhasil hapus data topik', '2026-05-29 04:35:12', NULL, '{\"id_topik\":7,\"kode_topik\":null,\"nama_topik\":\"Giat Umum\",\"urutan_topik\":5,\"created_at\":\"2025-09-01 09:38:21\",\"updated_at\":\"2026-05-12 04:08:28\",\"deskripsi_topik\":null,\"status_topik\":1}', '');
INSERT INTO `app_log_aktivitas` VALUES (27, '127.0.0.1', 7, 'admin aplikasi', 'addMenuAction', 'Successfully added tag data', '2026-05-29 04:41:46', NULL, '', '{\"nama_topik\":\"Riset\",\"kode_topik\":\"TG260529044146\",\"urutan_topik\":\"1\",\"created_at\":\"2026-05-29T04:41:46.732642Z\"}');
INSERT INTO `app_log_aktivitas` VALUES (28, '127.0.0.1', 7, 'admin aplikasi', 'updateTopikAction', 'Tag updated successfully', '2026-05-29 04:46:31', NULL, '{\"id_topik\":8,\"kode_topik\":\"TG260529044146\",\"nama_topik\":\"Riset\",\"urutan_topik\":1,\"created_at\":\"2026-05-29 04:41:46\",\"updated_at\":null,\"deskripsi_topik\":null,\"status_topik\":1}', '{\"nama_topik\":\"Riset2\",\"urutan_topik\":\"1\",\"updated_at\":\"2026-05-29T04:46:31.099520Z\"}');
INSERT INTO `app_log_aktivitas` VALUES (29, '127.0.0.1', 7, 'admin aplikasi', 'updateTopikAction', 'Tag updated successfully', '2026-05-29 04:46:36', NULL, '{\"id_topik\":8,\"kode_topik\":\"TG260529044146\",\"nama_topik\":\"Riset2\",\"urutan_topik\":1,\"created_at\":\"2026-05-29 04:41:46\",\"updated_at\":\"2026-05-29 04:46:31\",\"deskripsi_topik\":null,\"status_topik\":1}', '{\"nama_topik\":\"Riset2\",\"urutan_topik\":\"12\",\"updated_at\":\"2026-05-29T04:46:36.424265Z\"}');
INSERT INTO `app_log_aktivitas` VALUES (30, '127.0.0.1', 7, 'admin aplikasi', 'deleteTopikAction', 'Successfully deleted tag data', '2026-05-29 04:47:27', NULL, '{\"id_topik\":4,\"kode_topik\":\"PERC\",\"nama_topik\":\"Perencana\",\"urutan_topik\":1,\"created_at\":\"2025-09-01 07:00:24\",\"updated_at\":\"2025-09-01 09:36:37\",\"deskripsi_topik\":null,\"status_topik\":1}', '');
INSERT INTO `app_log_aktivitas` VALUES (31, '127.0.0.1', 7, 'admin aplikasi', 'deleteTopikAction', 'Successfully deleted tag data', '2026-05-29 04:47:32', NULL, '{\"id_topik\":5,\"kode_topik\":\"ASSES\",\"nama_topik\":\"Assesment dan Deployment Center\",\"urutan_topik\":3,\"created_at\":\"2025-09-01 09:37:29\",\"updated_at\":null,\"deskripsi_topik\":null,\"status_topik\":1}', '');
INSERT INTO `app_log_aktivitas` VALUES (32, '127.0.0.1', 7, 'admin aplikasi', 'updateProfilAction', 'Profile updated successfully', '2026-05-29 06:17:02', NULL, '{\"id_user\":7,\"role_id\":1,\"nama_user\":\"admin aplikasi\",\"username_user\":\"business.basrilhafi@gmail.com\",\"password_user\":\"$2y$12$aNjzdFqGHoCj\\/DNIRaxXROFdhUGXfuhhcsZ2SVXnHKryuQr\\/0dpZ6\",\"foto_user\":\"organisasi\\/1756960375_profile.png\",\"status_user\":1,\"created_at\":\"2025-08-31 08:42:01\",\"updated_at\":\"2026-05-08 09:03:54\",\"identitas_user\":null,\"file_identitas_user\":null,\"telepon_user\":null,\"pekerjaan_user\":null,\"alamat_user\":null,\"organisasi_user\":null,\"verify_token\":null,\"otp_user\":\"1\",\"is_otp\":\"N\"}', '{\"nama_user\":\"admin aplikasi2\",\"username_user\":\"business.basrilhafi@gmail.com2\",\"updated_at\":\"2026-05-29T06:17:02.759791Z\"}');
INSERT INTO `app_log_aktivitas` VALUES (33, '127.0.0.1', 7, 'admin aplikasi', 'updateProfilAction', 'Profile updated successfully', '2026-05-29 06:17:17', NULL, '{\"id_user\":7,\"role_id\":1,\"nama_user\":\"admin aplikasi2\",\"username_user\":\"business.basrilhafi@gmail.com2\",\"password_user\":\"$2y$12$aNjzdFqGHoCj\\/DNIRaxXROFdhUGXfuhhcsZ2SVXnHKryuQr\\/0dpZ6\",\"foto_user\":\"organisasi\\/1756960375_profile.png\",\"status_user\":1,\"created_at\":\"2025-08-31 08:42:01\",\"updated_at\":\"2026-05-29 06:17:02\",\"identitas_user\":null,\"file_identitas_user\":null,\"telepon_user\":null,\"pekerjaan_user\":null,\"alamat_user\":null,\"organisasi_user\":null,\"verify_token\":null,\"otp_user\":\"1\",\"is_otp\":\"N\"}', '{\"nama_user\":\"admin aplikasi\",\"username_user\":\"business.basrilhafi@gmail.com\",\"updated_at\":\"2026-05-29T06:17:17.081051Z\",\"foto_user\":\"organisasi\\/1780035437_sponsor1.png\"}');
INSERT INTO `app_log_aktivitas` VALUES (34, '127.0.0.1', 7, 'admin aplikasi', 'updatePasswordAction', 'Successfully changed password', '2026-05-29 06:23:45', NULL, '{\"id_user\":7,\"role_id\":1,\"nama_user\":\"admin aplikasi\",\"username_user\":\"business.basrilhafi@gmail.com\",\"password_user\":\"$2y$12$aNjzdFqGHoCj\\/DNIRaxXROFdhUGXfuhhcsZ2SVXnHKryuQr\\/0dpZ6\",\"foto_user\":\"organisasi\\/1780035437_sponsor1.png\",\"status_user\":1,\"created_at\":\"2025-08-31 08:42:01\",\"updated_at\":\"2026-05-29 06:17:17\",\"identitas_user\":null,\"file_identitas_user\":null,\"telepon_user\":null,\"pekerjaan_user\":null,\"alamat_user\":null,\"organisasi_user\":null,\"verify_token\":null,\"otp_user\":\"1\",\"is_otp\":\"N\"}', '{\"password_user\":\"$2y$12$7nw1MfDTGZs1owp8pq1eEOmDzQHOMVmEkbs8STBdRce8YAJqh\\/UV.\",\"updated_at\":\"2026-05-29T06:23:44.998568Z\"}');
INSERT INTO `app_log_aktivitas` VALUES (35, '127.0.0.1', 7, 'admin aplikasi', 'updatePasswordAction', 'Successfully changed password', '2026-05-29 06:24:09', NULL, '{\"id_user\":7,\"role_id\":1,\"nama_user\":\"admin aplikasi\",\"username_user\":\"business.basrilhafi@gmail.com\",\"password_user\":\"$2y$12$7nw1MfDTGZs1owp8pq1eEOmDzQHOMVmEkbs8STBdRce8YAJqh\\/UV.\",\"foto_user\":\"organisasi\\/1780035437_sponsor1.png\",\"status_user\":1,\"created_at\":\"2025-08-31 08:42:01\",\"updated_at\":\"2026-05-29 06:23:44\",\"identitas_user\":null,\"file_identitas_user\":null,\"telepon_user\":null,\"pekerjaan_user\":null,\"alamat_user\":null,\"organisasi_user\":null,\"verify_token\":null,\"otp_user\":\"1\",\"is_otp\":\"N\"}', '{\"password_user\":\"$2y$12$Nxp8U6YjrVcC1AFfT27AsuGqotBhs3LTgQd8jMtFo2bxdv6MEqUTi\",\"updated_at\":\"2026-05-29T06:24:09.716636Z\"}');
INSERT INTO `app_log_aktivitas` VALUES (36, '127.0.0.1', 7, 'admin aplikasi', 'logoutBackendAction', 'Logout Aplikasi', '2026-05-29 06:26:26', NULL, '{\"username\":\"business.basrilhafi@gmail.com\",\"nama\":\"admin aplikasi\"}', '');
INSERT INTO `app_log_aktivitas` VALUES (37, '127.0.0.1', 7, 'admin aplikasi', 'verifyOtpAdminPanelAction', 'Login Successfully', '2026-05-29 06:27:57', NULL, '', '');

-- ----------------------------
-- Table structure for app_setting
-- ----------------------------
DROP TABLE IF EXISTS `app_setting`;
CREATE TABLE `app_setting`  (
  `id_setting` bigint NOT NULL AUTO_INCREMENT,
  `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `gambar_dashboard` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `gambar_topik` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `deskripsi_topik` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `gambar_organisasi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `deskripsi_organisasi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `gambar_permohonan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `deskripsi_permohonan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `gambar2_permohonan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `gambar_hubungi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `deskripsi_hubungi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `gambar2_hubungi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `gambar_tentang` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `deskripsi_tentang` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `gambar2_tentang` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `gambar_login` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `deskripsi_login` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `gambar2_login` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `url_facebook` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `url_twitter` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `url_instagram` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `url_youtube` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `url_linkedin` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `kode` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `cek_antivirus` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'Y',
  `url_antivirus` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `url_chatbot` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id_setting`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of app_setting
-- ----------------------------
INSERT INTO `app_setting` VALUES (1, 'image_setting/1757579765_logo-kemhan (1).png', 'image_setting/1757579782_statistic2 (1).png', 'image_setting/1777353459_ChatGPT Image 28 Apr 2026, 12.17.24.png', 'Temukan berbagai dataset dan infografis dari seluruh satuan dan unit kerja di lingkungan Kementrian Pertahanan Republik Indonesia.', 'image_setting/1778221639_bg-monitoring.png', 'Daftar dataset dan infografis pada masing-masing organisasi Kementerian Pertahanan', 'image_setting/1777514843_bg-monitoring.png', 'Silahkan isi data dibawah ini terlebih dahulu agar anda dapat cek status permohonan informasi anda', 'image_setting/1777515140_side-monitoring.png', 'image_setting/1777517889_bg-monitoring.png', 'Silahkan isi data dibawah ini beserta pesan yang anda tujukan kepada kami', 'image_setting/1777518026_side-hubungi.png', 'image_setting/1777518725_bg-monitoring.png', 'Portal Satu Data Pertahanan adalah Portal Data Terpadu Kementerian Pertahanan Republik Indonesia yang menyajikan data-data dari seluruh Satuan dan Unit Kerja. <br>\r\nPortal Satu Data Pertahanan adalah Portal Data Terpadu Kementerian Pertahanan Republik Indonesia yang menyajikan data-data dari seluruh Satuan dan Unit Kerja. Portal Satu Data Pertahanan adalah Portal Data Terpadu Kementerian Pertahanan Republik Indonesia yang menyajikan data-data dari seluruh Satuan dan Unit Kerja.', 'image_setting/1777519330_side-tentang.png', 'image_setting/1777520836_bg-monitoring.png', 'Silahkan masukkan data dibawah ini untuk mengakses akun', 'image_setting/1778131965_side-login.png', '2025-09-10 00:00:00', '2026-05-13 04:42:21', 'https://www.facebook.com/', 'https://www.x.com', 'https://www.instagram.com', 'https://www.youtube.com', 'https://www.linkedin.com', 'SETT', 'N', 'http://10.1.100.131:13320/api/v1/scan', 'https://apps.syscloud.my.id/chatbot/');

-- ----------------------------
-- Table structure for app_slider
-- ----------------------------
DROP TABLE IF EXISTS `app_slider`;
CREATE TABLE `app_slider`  (
  `id_slider` bigint NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id_slider`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of app_slider
-- ----------------------------

-- ----------------------------
-- Table structure for app_user
-- ----------------------------
DROP TABLE IF EXISTS `app_user`;
CREATE TABLE `app_user`  (
  `id_user` bigint NOT NULL AUTO_INCREMENT,
  `role_id` int NULL DEFAULT NULL,
  `nama_user` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `username_user` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `password_user` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `foto_user` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `status_user` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `identitas_user` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `file_identitas_user` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `telepon_user` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `pekerjaan_user` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `alamat_user` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `organisasi_user` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `verify_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `otp_user` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `is_otp` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'Y',
  PRIMARY KEY (`id_user`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 18 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of app_user
-- ----------------------------
INSERT INTO `app_user` VALUES (1, 1, 'admin aplikasi', 'admin', '$2y$12$DqH4QqdV1lsz72WeV99i0.8mMk/hP2YTVascrFs272K73qZ.gi37G', 'organisasi/1756960375_profile.png', 1, '2025-08-31 08:42:01', '2025-09-04 04:47:46', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'N');
INSERT INTO `app_user` VALUES (2, 3, 'organisasi pusdatin2', 'basrilhf@gmail.com', '$2y$12$pWB4sP7Y/buPwt0hgdJzCu5l/tEuQiPXx4r7iZKp0WPjx.qZdV6Jq', 'organisasi/1756874039_profile.png', 1, '2025-08-31 08:43:33', '2025-09-03 04:33:59', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', 'N');
INSERT INTO `app_user` VALUES (7, 1, 'admin aplikasi', 'business.basrilhafi@gmail.com', '$2y$12$Nxp8U6YjrVcC1AFfT27AsuGqotBhs3LTgQd8jMtFo2bxdv6MEqUTi', 'organisasi/1780035437_sponsor1.png', 1, '2025-08-31 08:42:01', '2026-05-29 06:24:09', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', 'N');

-- ----------------------------
-- Table structure for cache
-- ----------------------------
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache`  (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cache
-- ----------------------------

-- ----------------------------
-- Table structure for cache_locks
-- ----------------------------
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks`  (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cache_locks
-- ----------------------------

-- ----------------------------
-- Table structure for failed_jobs
-- ----------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `failed_jobs_uuid_unique`(`uuid` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of failed_jobs
-- ----------------------------

-- ----------------------------
-- Table structure for job_batches
-- ----------------------------
DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches`  (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `cancelled_at` int NULL DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of job_batches
-- ----------------------------

-- ----------------------------
-- Table structure for jobs
-- ----------------------------
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED NULL DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `jobs_queue_index`(`queue` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of jobs
-- ----------------------------

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES (1, '0001_01_01_000000_create_users_table', 1);
INSERT INTO `migrations` VALUES (2, '0001_01_01_000001_create_cache_table', 1);
INSERT INTO `migrations` VALUES (3, '0001_01_01_000002_create_jobs_table', 1);
INSERT INTO `migrations` VALUES (4, '2025_08_08_095341_create_reff_orgainisasis_table', 1);

-- ----------------------------
-- Table structure for password_reset_tokens
-- ----------------------------
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens`  (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of password_reset_tokens
-- ----------------------------

-- ----------------------------
-- Table structure for reff_akses_menu
-- ----------------------------
DROP TABLE IF EXISTS `reff_akses_menu`;
CREATE TABLE `reff_akses_menu`  (
  `id_akses_menu` bigint NOT NULL AUTO_INCREMENT,
  `role_id` int NULL DEFAULT NULL,
  `menu_id` int NULL DEFAULT NULL,
  `permit_r` tinyint(1) NOT NULL DEFAULT 1,
  `permit_c` tinyint(1) NOT NULL DEFAULT 1,
  `permit_u` tinyint(1) NOT NULL DEFAULT 1,
  `permit_d` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_akses_menu`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 45 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of reff_akses_menu
-- ----------------------------
INSERT INTO `reff_akses_menu` VALUES (4, 1, 1, 1, 1, 1, 1, '2025-08-29 09:11:50', NULL);
INSERT INTO `reff_akses_menu` VALUES (5, 1, 2, 1, 1, 1, 1, '2025-08-29 09:12:02', NULL);
INSERT INTO `reff_akses_menu` VALUES (9, 1, 8, 1, 1, 1, 1, '2025-08-29 09:12:02', NULL);
INSERT INTO `reff_akses_menu` VALUES (11, 1, 10, 1, 1, 1, 1, '2025-08-29 09:12:02', NULL);
INSERT INTO `reff_akses_menu` VALUES (14, 1, 13, 1, 1, 1, 1, '2025-08-29 09:12:02', NULL);
INSERT INTO `reff_akses_menu` VALUES (17, 1, 16, 1, 1, 1, 1, '2025-08-29 09:12:02', NULL);
INSERT INTO `reff_akses_menu` VALUES (18, 1, 17, 1, 1, 1, 1, '2025-08-29 09:12:02', NULL);
INSERT INTO `reff_akses_menu` VALUES (19, 1, 18, 1, 1, 1, 1, '2025-08-29 09:12:02', NULL);
INSERT INTO `reff_akses_menu` VALUES (44, 1, 26, 1, 1, 1, 1, '2026-05-19 04:53:36', NULL);

-- ----------------------------
-- Table structure for reff_menu
-- ----------------------------
DROP TABLE IF EXISTS `reff_menu`;
CREATE TABLE `reff_menu`  (
  `id_menu` bigint NOT NULL AUTO_INCREMENT,
  `nama_menu` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `jenis_menu` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `kode_menu` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `icon_menu` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `parent_menu` int NULL DEFAULT NULL,
  `urutan_menu` int NULL DEFAULT NULL,
  `deskripsi_menu` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_menu`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 27 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of reff_menu
-- ----------------------------
INSERT INTO `reff_menu` VALUES (1, 'Dashboards', 'S', 'dashboard', 'fa fa-dashboard', 0, 1, 'menu dashboard', '2025-08-28 08:29:05', NULL);
INSERT INTO `reff_menu` VALUES (8, 'Reference', 'M', 'referensi', NULL, 0, 5, NULL, '2025-08-28 08:29:54', NULL);
INSERT INTO `reff_menu` VALUES (10, 'Users', 'D', 'ref-pengguna', 'bullet', 8, 1, NULL, '2025-08-28 08:30:51', NULL);
INSERT INTO `reff_menu` VALUES (13, 'Tags', 'D', 'ref-topik', 'bullet', 8, 3, NULL, '2025-08-28 08:30:51', NULL);
INSERT INTO `reff_menu` VALUES (16, 'Content Web', 'M', 'konten', NULL, 0, 8, NULL, '2025-08-28 08:29:54', NULL);
INSERT INTO `reff_menu` VALUES (17, 'Settings', 'D', 'setting', 'bullet', 16, 1, NULL, '2025-08-28 08:30:51', NULL);
INSERT INTO `reff_menu` VALUES (18, 'Link', 'D', 'tautan', 'bullet', 16, 2, NULL, '2025-08-28 08:30:51', NULL);
INSERT INTO `reff_menu` VALUES (26, 'Sponsor', 'D', 'ref-sponsor', 'bullet', 8, 2, NULL, NULL, NULL);

-- ----------------------------
-- Table structure for reff_organisasi
-- ----------------------------
DROP TABLE IF EXISTS `reff_organisasi`;
CREATE TABLE `reff_organisasi`  (
  `id_organisasi` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `kode_organisasi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `nama_organisasi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `singkatan_organisasi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `web_organisasi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `foto_organisasi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `tmp_foto_organisasi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_organisasi`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of reff_organisasi
-- ----------------------------

-- ----------------------------
-- Table structure for reff_role
-- ----------------------------
DROP TABLE IF EXISTS `reff_role`;
CREATE TABLE `reff_role`  (
  `id_role` bigint NOT NULL AUTO_INCREMENT,
  `nama_role` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `kode_role` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `deskripsi_role` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `all_data_role` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'N',
  PRIMARY KEY (`id_role`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of reff_role
-- ----------------------------
INSERT INTO `reff_role` VALUES (1, 'Super Admin', 'SADM', 'role untuk super admin', '2025-08-28 07:22:00', '2026-03-31 02:57:47', 'Y');
INSERT INTO `reff_role` VALUES (4, 'Public', 'PUB', '-', '2025-09-01 02:51:24', '2026-05-29 02:06:43', 'N');

-- ----------------------------
-- Table structure for reff_status
-- ----------------------------
DROP TABLE IF EXISTS `reff_status`;
CREATE TABLE `reff_status`  (
  `id_status` bigint NOT NULL AUTO_INCREMENT,
  `kode_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `keterangan_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `deskripsi_status` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `jenis_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `urutan_status` int NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_status`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 24 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of reff_status
-- ----------------------------
INSERT INTO `reff_status` VALUES (1, 'M', 'Master Menu', '', 'menu', 1, NULL, NULL);
INSERT INTO `reff_status` VALUES (2, 'S', 'Single Menu', '', 'menu', 2, NULL, NULL);
INSERT INTO `reff_status` VALUES (3, 'D', 'Sub Menu', '', 'menu', 3, NULL, NULL);
INSERT INTO `reff_status` VALUES (4, 'DT', 'Dataset', '', 'tipe_data', 1, NULL, NULL);
INSERT INTO `reff_status` VALUES (5, 'IG', 'Infografis', '', 'tipe_data', 2, NULL, NULL);
INSERT INTO `reff_status` VALUES (7, 'ST', 'Statistik', '', 'kategori_data', 1, NULL, NULL);
INSERT INTO `reff_status` VALUES (8, 'THN', 'Tahunan', '', 'frekuensi_data', 1, NULL, NULL);
INSERT INTO `reff_status` VALUES (9, 'BLN', 'Bulanan', '', 'frekuensi_data', 2, NULL, NULL);
INSERT INTO `reff_status` VALUES (10, 'HRN', 'Harian', '', 'frekuensi_data', 3, NULL, NULL);
INSERT INTO `reff_status` VALUES (11, 'V', 'Verifikasi', '', 'status_data', 1, NULL, NULL);
INSERT INTO `reff_status` VALUES (12, 'Y', 'Disetujui', '', 'status_data', 2, NULL, NULL);
INSERT INTO `reff_status` VALUES (13, 'N', 'Ditolak', '', 'status_data', 3, NULL, NULL);
INSERT INTO `reff_status` VALUES (14, 'P', 'Proses', '', 'status_pengaduan', 1, NULL, NULL);
INSERT INTO `reff_status` VALUES (15, 'Y', 'Diterima', '', 'status_pengaduan', 2, NULL, NULL);
INSERT INTO `reff_status` VALUES (16, 'N', 'Ditolak', '', 'status_pengaduan', 3, NULL, NULL);
INSERT INTO `reff_status` VALUES (17, 'P', 'Proses', '', 'status_permohonan', 1, NULL, NULL);
INSERT INTO `reff_status` VALUES (18, 'Y', 'Diterima', '', 'status_permohonan', 2, NULL, NULL);
INSERT INTO `reff_status` VALUES (19, 'N', 'Ditolak', '', 'status_permohonan', 3, NULL, NULL);
INSERT INTO `reff_status` VALUES (20, 'KU', 'Keuangan', NULL, 'kategori_data', 2, NULL, NULL);
INSERT INTO `reff_status` VALUES (21, 'SP', 'Spasial', NULL, 'kategori_data', 3, NULL, NULL);
INSERT INTO `reff_status` VALUES (22, 'SM1', '-01-01||-06-30', 'Semester 1', 'semester_report', 1, NULL, NULL);
INSERT INTO `reff_status` VALUES (23, 'SM2', '-07-01||-12-31', 'Semester 2', 'semester_report', 2, NULL, NULL);

-- ----------------------------
-- Table structure for reff_topik
-- ----------------------------
DROP TABLE IF EXISTS `reff_topik`;
CREATE TABLE `reff_topik`  (
  `id_topik` bigint NOT NULL AUTO_INCREMENT,
  `kode_topik` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `nama_topik` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `urutan_topik` int NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deskripsi_topik` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `status_topik` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_topik`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of reff_topik
-- ----------------------------
INSERT INTO `reff_topik` VALUES (8, 'TG260529044146', 'Riset2', 12, '2026-05-29 04:41:46', '2026-05-29 04:46:36', NULL, 1);

-- ----------------------------
-- Table structure for sessions
-- ----------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions`  (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NULL DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `sessions_user_id_index`(`user_id` ASC) USING BTREE,
  INDEX `sessions_last_activity_index`(`last_activity` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sessions
-- ----------------------------
INSERT INTO `sessions` VALUES ('XWuQDnXey1c7iFhQnJqQjXgpJPTRA2J6zM7V5qUZ', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'YTo5OntzOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjY6Il90b2tlbiI7czo0MDoiOUNOS0dEQTFiVmg0b29nSjN4R2pmNzhhaEx6VWlNN2lGUms0VU5TWSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zb2NpZXR5LWV2ZW50L2Fib3V0Ijt9czoyOiJpZCI7aTo3O3M6NDoibmFtYSI7czoxNDoiYWRtaW4gYXBsaWthc2kiO3M6ODoidXNlcm5hbWUiO3M6Mjk6ImJ1c2luZXNzLmJhc3JpbGhhZmlAZ21haWwuY29tIjtzOjg6ImFsbF9kYXRhIjtzOjE6IlkiO3M6OToia29kZV9yb2xlIjtzOjQ6IlNBRE0iO3M6NzoiaWRfcm9sZSI7aToxO30=', 1780048839);

-- ----------------------------
-- Table structure for t_event
-- ----------------------------
DROP TABLE IF EXISTS `t_event`;
CREATE TABLE `t_event`  (
  `id_event` int NOT NULL AUTO_INCREMENT,
  `kode_event` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `judul_event` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `sub_judul_event` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `keterangan_event` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `lokasi_event` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `tanggal_awal_event` date NULL DEFAULT NULL,
  `tanggal_akhir_event` date NULL DEFAULT NULL,
  `status_event` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT 'Y',
  `background_event` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `created_by_event` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `created_at_event` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at_event` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id_event`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of t_event
-- ----------------------------
INSERT INTO `t_event` VALUES (1, 'EV260529145400', 'ScienceBank Society', 'Inagural President & Summit', 'is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since 1966, when designers at Letraset and James Mosley, the librarian at St Bride Printing Library, took a 1914 Cicero translation and scrambled it to make dummy text for Letraset\'s Body Type sheets. It has survived not only many decades, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised thanks to these sheets and more recently with desktop publishing software including versions of Lorem Ipsum', 'Bali Beach Convention Center', '2026-07-01', '2026-07-04', 'Y', 'event/bg-scbank.jpeg', NULL, '2026-05-29 15:01:10', NULL);
INSERT INTO `t_event` VALUES (2, 'EV260529145401', 'ScienceBank Society2', 'Inagural President & Summit', 'is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since 1966, when designers at Letraset and James Mosley, the librarian at St Bride Printing Library, took a 1914 Cicero translation and scrambled it to make dummy text for Letraset\'s Body Type sheets. It has survived not only many decades, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised thanks to these sheets and more recently with desktop publishing software including versions of Lorem Ipsum', 'Bali Beach Convention Center', '2026-07-01', '2026-07-04', 'Y', 'event/bg-scbank.jpeg', NULL, '2026-05-29 15:26:42', NULL);

-- ----------------------------
-- Table structure for t_event_kolaborasi
-- ----------------------------
DROP TABLE IF EXISTS `t_event_kolaborasi`;
CREATE TABLE `t_event_kolaborasi`  (
  `id_event_kolaborasi` int NOT NULL AUTO_INCREMENT,
  `kode_kolaborasi` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `event_kode_kolaborasi` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `nama_kolaborasi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `gambar_kolaborasi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `keterangan_kolaborasi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `created_at_kolaborasi` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at_kolaborasi` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id_event_kolaborasi`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of t_event_kolaborasi
-- ----------------------------
INSERT INTO `t_event_kolaborasi` VALUES (1, 'EV260529145401001', 'EV260529145400', 'In Collaboration with Indonesia BPOM', NULL, NULL, '2026-05-29 16:32:52', NULL);
INSERT INTO `t_event_kolaborasi` VALUES (2, 'EV260529145401002', 'EV260529145400', 'Ied by Prof Taruna Ikrar', NULL, NULL, '2026-05-29 16:33:24', NULL);

-- ----------------------------
-- Table structure for t_event_paket
-- ----------------------------
DROP TABLE IF EXISTS `t_event_paket`;
CREATE TABLE `t_event_paket`  (
  `id_event_paket` int NOT NULL AUTO_INCREMENT,
  `kode_paket` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `event_kode_paket` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `judul_paket` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `sub_judul_paket` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `keterangan_paket` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `gambar_paket` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `icon_paket` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `lokasi_paket` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `created_at_paket` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at_paket` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id_event_paket`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of t_event_paket
-- ----------------------------
INSERT INTO `t_event_paket` VALUES (1, 'EV260529145401001', 'EV260529145401', 'Golf Experience', 'World-class course in scenic surroundings.', 'making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. ', 'event/golf.png', 'event/golf-icon.png', 'Bali National Golf Club', '2026-05-29 16:04:09', NULL);
INSERT INTO `t_event_paket` VALUES (2, 'EV260529145400002', 'EV260529145400', 'Beach Activities', 'Water Sports and beachside relaxion.', 'making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. ', 'event/beach.png', 'event/beach-icon.png', 'Nusa Dua Beach', '2026-05-29 15:13:52', NULL);
INSERT INTO `t_event_paket` VALUES (3, 'EV260529145400003', 'EV260529145400', 'Spa & Traditional Massage', 'Traditional Balinese wellness treatment.', 'making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. ', 'event/spa.png', 'event/spa-icon.png', 'Balinese Wellnese', '2026-05-29 15:52:11', NULL);
INSERT INTO `t_event_paket` VALUES (4, 'EV260529145400004', 'EV260529145400', 'Diving Adventure', 'Reef exploration and marine adventures', 'making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. ', 'event/diving.png', 'event/diving-icon.png', 'Tanjung Benoa', '2026-05-29 15:10:12', NULL);
INSERT INTO `t_event_paket` VALUES (5, 'EV260529145400005', 'EV260529145400', 'Bali Cultural Tour', 'Sunset tour and cultural discovery.', 'making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. ', 'event/cultural.png', 'event/cultural-icon.png', 'Uluwatu Temple', '2026-05-29 15:10:12', NULL);

-- ----------------------------
-- Table structure for t_event_paket_detail
-- ----------------------------
DROP TABLE IF EXISTS `t_event_paket_detail`;
CREATE TABLE `t_event_paket_detail`  (
  `id_event_paket_detail` int NOT NULL AUTO_INCREMENT,
  `kode_event_paket_detail` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `event_paket_kode` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `event_kode` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `jenis_paket_detail` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `nama_paket_detail` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `gambar_paket_detail` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `created_at_paket_detail` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at_paket_detail` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id_event_paket_detail`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of t_event_paket_detail
-- ----------------------------

-- ----------------------------
-- Table structure for t_event_program
-- ----------------------------
DROP TABLE IF EXISTS `t_event_program`;
CREATE TABLE `t_event_program`  (
  `id_event_program` int NOT NULL AUTO_INCREMENT,
  `kode_event_program` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `event_kode_program` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `hari_program` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `tanggal_program` date NULL DEFAULT NULL,
  `created_at_program` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at_program` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id_event_program`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of t_event_program
-- ----------------------------

-- ----------------------------
-- Table structure for t_event_program_detail
-- ----------------------------
DROP TABLE IF EXISTS `t_event_program_detail`;
CREATE TABLE `t_event_program_detail`  (
  `id_event_program_detail` int NOT NULL AUTO_INCREMENT,
  `kode_event_program_detail` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `event_program_kode` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `event_kode` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `awal_program_detail` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `akhir_program_detail` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `sesi_program_detail` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `keterangan_program_detail` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `created_at_program_detail` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at_program_detail` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id_event_program_detail`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of t_event_program_detail
-- ----------------------------

-- ----------------------------
-- Table structure for t_sponsor
-- ----------------------------
DROP TABLE IF EXISTS `t_sponsor`;
CREATE TABLE `t_sponsor`  (
  `id_sponsor` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `urutan` int NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id_sponsor`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of t_sponsor
-- ----------------------------
INSERT INTO `t_sponsor` VALUES (1, 'INTELLEGENT SCIENCE', 'sponsor/1780023279_sponsor1.png', 1, '2026-05-29 02:54:40', NULL);
INSERT INTO `t_sponsor` VALUES (2, 'BioNexus', 'sponsor/1780025601_sponsor2.png', 2, '2026-05-29 10:34:11', '2026-05-29 03:34:11');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `users_email_unique`(`email` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------

SET FOREIGN_KEY_CHECKS = 1;
