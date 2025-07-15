/*
SQLyog Enterprise
MySQL - 8.0.30 : Database - esms_v2
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`esms_v2` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `esms_v2`;

/*Table structure for table `cache` */

DROP TABLE IF EXISTS `cache`;

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `cache_locks` */

DROP TABLE IF EXISTS `cache_locks`;

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `disposisi_isis` */

DROP TABLE IF EXISTS `disposisi_isis`;

CREATE TABLE `disposisi_isis` (
  `id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `entrysurat_id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kodeklasifikasi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kepada` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hal` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tgl_disposisi` date DEFAULT NULL,
  `tgl_remitten` date DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `isi` text COLLATE utf8mb4_unicode_ci,
  `tindakan` text COLLATE utf8mb4_unicode_ci,
  `userid_pembuat` bigint unsigned NOT NULL,
  `satkerid_pembuat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `terdisposisi` tinyint DEFAULT NULL,
  `mig_nourut` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mig_satkerasalid` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mig_satkertujuanid` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mig_terbaca` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mig_nourutasal` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `disposisi_isis_entrysurat_id_foreign` (`entrysurat_id`),
  KEY `disposisi_isis_userid_pembuat_foreign` (`userid_pembuat`),
  CONSTRAINT `disposisi_isis_entrysurat_id_foreign` FOREIGN KEY (`entrysurat_id`) REFERENCES `entry_surat_isis` (`id`) ON DELETE CASCADE,
  CONSTRAINT `disposisi_isis_userid_pembuat_foreign` FOREIGN KEY (`userid_pembuat`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `entry_surat_isis` */

DROP TABLE IF EXISTS `entry_surat_isis`;

CREATE TABLE `entry_surat_isis` (
  `id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT 'ini table dari master_jenissurat_join',
  `nomor_surat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kode_klasifikasi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hal` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kepada` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dari` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tgl_surat` date NOT NULL,
  `tgl_diterima` date DEFAULT NULL,
  `tgl_diarahkan` date DEFAULT NULL,
  `sifat` tinyint NOT NULL DEFAULT '0',
  `isi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tembusan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `isfinal` tinyint DEFAULT NULL COMMENT '0=draft, 1=selesai',
  `created_by` bigint unsigned NOT NULL,
  `satkerid_pembuat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jumlah_lampiran` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referensi_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ini dari id table entry_surat_isis ini sendiri',
  `noagenda` int DEFAULT NULL,
  `tgl_update` date DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `satkerid_update` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `terdisposisi` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `entry_surat_isis_created_by_foreign` (`created_by`),
  KEY `entry_surat_isis_updated_by_foreign` (`updated_by`),
  CONSTRAINT `entry_surat_isis_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `entry_surat_isis_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `entry_surat_lampirans` */

DROP TABLE IF EXISTS `entry_surat_lampirans`;

CREATE TABLE `entry_surat_lampirans` (
  `lampiran_id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `entrysurat_id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_lampiran` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tgl_upload` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`lampiran_id`),
  KEY `entry_surat_lampirans_entrysurat_id_foreign` (`entrysurat_id`),
  CONSTRAINT `entry_surat_lampirans_entrysurat_id_foreign` FOREIGN KEY (`entrysurat_id`) REFERENCES `entry_surat_isis` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `entry_surat_scans` */

DROP TABLE IF EXISTS `entry_surat_scans`;

CREATE TABLE `entry_surat_scans` (
  `id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `entrysurat_id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nourut` int DEFAULT NULL,
  `nama_scan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` double DEFAULT NULL,
  `tgl_upload` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `entry_surat_scans_entrysurat_id_foreign` (`entrysurat_id`),
  CONSTRAINT `entry_surat_scans_entrysurat_id_foreign` FOREIGN KEY (`entrysurat_id`) REFERENCES `entry_surat_isis` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `entry_surat_tujuans` */

DROP TABLE IF EXISTS `entry_surat_tujuans`;

CREATE TABLE `entry_surat_tujuans` (
  `id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `satkerid_tujuan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dibaca` tinyint(1) NOT NULL DEFAULT '0',
  `is_tembusan` tinyint(1) NOT NULL DEFAULT '0',
  `entrysurat_id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `userid_tujuan` int unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `entry_surat_tujuans_entrysurat_id_foreign` (`entrysurat_id`),
  CONSTRAINT `entry_surat_tujuans_entrysurat_id_foreign` FOREIGN KEY (`entrysurat_id`) REFERENCES `entry_surat_isis` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `failed_jobs` */

DROP TABLE IF EXISTS `failed_jobs`;

CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `job_batches` */

DROP TABLE IF EXISTS `job_batches`;

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `jobs` */

DROP TABLE IF EXISTS `jobs`;

CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `master_instansis` */

DROP TABLE IF EXISTS `master_instansis`;

CREATE TABLE `master_instansis` (
  `id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_id` int DEFAULT NULL,
  `instansi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kepala` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kota` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telp` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `master_instansis_last_id_unique` (`last_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `master_jenis_surats` */

DROP TABLE IF EXISTS `master_jenis_surats`;

CREATE TABLE `master_jenis_surats` (
  `id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_id` int DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `master_jenis_surats_last_id_unique` (`last_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `master_klasifikasis` */

DROP TABLE IF EXISTS `master_klasifikasis`;

CREATE TABLE `master_klasifikasis` (
  `id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kodeklasifikasi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `klasifikasi` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `retensi_aktif` tinyint NOT NULL,
  `retensi_inaktif` tinyint NOT NULL,
  `keterangan` enum('1','2','3') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '1 dinilai kembali, 2 musnah, 3 permanen',
  `retensi` int DEFAULT NULL,
  `parent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `master_satkers` */

DROP TABLE IF EXISTS `master_satkers`;

CREATE TABLE `master_satkers` (
  `id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `satkerid` longtext COLLATE utf8mb4_unicode_ci,
  `kodesatker` longtext COLLATE utf8mb4_unicode_ci,
  `satker` longtext COLLATE utf8mb4_unicode_ci,
  `eselon` int DEFAULT NULL,
  `userid` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `master_satkers_userid_foreign` (`userid`),
  CONSTRAINT `master_satkers_userid_foreign` FOREIGN KEY (`userid`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `master_tindakan_disposisis` */

DROP TABLE IF EXISTS `master_tindakan_disposisis`;

CREATE TABLE `master_tindakan_disposisis` (
  `id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tindakan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `satkerid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `password_reset_tokens` */

DROP TABLE IF EXISTS `password_reset_tokens`;

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `sessions` */

DROP TABLE IF EXISTS `sessions`;

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `surat_keluar_isis` */

DROP TABLE IF EXISTS `surat_keluar_isis`;

CREATE TABLE `surat_keluar_isis` (
  `id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `suratkeluar_id` int DEFAULT NULL,
  `revisi_id` bigint unsigned DEFAULT NULL,
  `revisi_data_id` char(26) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tgl_revisi` datetime DEFAULT NULL,
  `jenis_id` tinyint unsigned DEFAULT NULL,
  `no_generate` int unsigned DEFAULT NULL,
  `nosurat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kodeklasifikasi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tgl_surat` datetime DEFAULT NULL,
  `hal` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jml_lampiran` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sifat` tinyint unsigned DEFAULT NULL,
  `kepada` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `isi` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `tembusan` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenisref_id` tinyint unsigned DEFAULT NULL,
  `referensi_id` int unsigned DEFAULT NULL,
  `entry_surat_isi_id` char(26) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ttd_nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ttd_id` int unsigned DEFAULT NULL,
  `user_ttd_id` bigint unsigned DEFAULT NULL,
  `isfinal` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '0 = draft, 1 = posisi terakhir',
  `userid_pembuat` int unsigned DEFAULT NULL,
  `user_id_pembuat` bigint unsigned NOT NULL,
  `satkerid_pembuat` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `userid_tujuan` int unsigned DEFAULT NULL,
  `user_id_tujuan` bigint unsigned NOT NULL,
  `satkerid_tujuan` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '1 = konsep, 2 = kirim acc, 3 = kirim revisi, 4 = final, 5 = cetak',
  `dibaca` tinyint unsigned NOT NULL DEFAULT '0',
  `last_sent` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '1 = yg terakhir dikirim',
  `userid_final` int unsigned DEFAULT NULL,
  `user_id_final` bigint unsigned DEFAULT NULL,
  `satkerid_final` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `surat_keluar_isis_suratkeluar_id_unique` (`suratkeluar_id`),
  KEY `surat_keluar_isis_revisi_data_id_foreign` (`revisi_data_id`),
  KEY `surat_keluar_isis_entry_surat_isi_id_foreign` (`entry_surat_isi_id`),
  KEY `surat_keluar_isis_user_ttd_id_foreign` (`user_ttd_id`),
  KEY `surat_keluar_isis_user_id_pembuat_foreign` (`user_id_pembuat`),
  KEY `surat_keluar_isis_user_id_tujuan_foreign` (`user_id_tujuan`),
  KEY `surat_keluar_isis_user_id_final_foreign` (`user_id_final`),
  CONSTRAINT `surat_keluar_isis_entry_surat_isi_id_foreign` FOREIGN KEY (`entry_surat_isi_id`) REFERENCES `entry_surat_isis` (`id`),
  CONSTRAINT `surat_keluar_isis_revisi_data_id_foreign` FOREIGN KEY (`revisi_data_id`) REFERENCES `surat_keluar_isis` (`id`),
  CONSTRAINT `surat_keluar_isis_user_id_final_foreign` FOREIGN KEY (`user_id_final`) REFERENCES `users` (`id`),
  CONSTRAINT `surat_keluar_isis_user_id_pembuat_foreign` FOREIGN KEY (`user_id_pembuat`) REFERENCES `users` (`id`),
  CONSTRAINT `surat_keluar_isis_user_id_tujuan_foreign` FOREIGN KEY (`user_id_tujuan`) REFERENCES `users` (`id`),
  CONSTRAINT `surat_keluar_isis_user_ttd_id_foreign` FOREIGN KEY (`user_ttd_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `surat_keluar_lampirans` */

DROP TABLE IF EXISTS `surat_keluar_lampirans`;

CREATE TABLE `surat_keluar_lampirans` (
  `id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lampiran_id` int DEFAULT NULL,
  `surat_keluar_id` int DEFAULT NULL,
  `surat_keluar_isi_id` char(26) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `revisi_id` int DEFAULT NULL,
  `revisi_data_id` char(26) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_lapiran` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_file` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_upload` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `surat_keluar_lampirans_lampiran_id_unique` (`lampiran_id`),
  KEY `surat_keluar_lampirans_surat_keluar_isi_id_foreign` (`surat_keluar_isi_id`),
  KEY `surat_keluar_lampirans_revisi_data_id_foreign` (`revisi_data_id`),
  CONSTRAINT `surat_keluar_lampirans_revisi_data_id_foreign` FOREIGN KEY (`revisi_data_id`) REFERENCES `surat_keluar_isis` (`id`),
  CONSTRAINT `surat_keluar_lampirans_surat_keluar_isi_id_foreign` FOREIGN KEY (`surat_keluar_isi_id`) REFERENCES `surat_keluar_isis` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `surat_keluar_nota_dinas` */

DROP TABLE IF EXISTS `surat_keluar_nota_dinas`;

CREATE TABLE `surat_keluar_nota_dinas` (
  `id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `suratkeluar_id` int DEFAULT NULL,
  `surat_keluar_isi_id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revisi_id` int NOT NULL,
  `revisi_data_id` char(26) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nourut_riw` int NOT NULL,
  `nourut_kirim` int NOT NULL DEFAULT '1',
  `userid_pembuat` int NOT NULL,
  `user_id_pembuat` bigint unsigned NOT NULL,
  `satkerid_pembuat` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `userid_tujuan` int DEFAULT NULL,
  `user_id_tujuan` bigint unsigned NOT NULL,
  `satkerid_tujuan` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `userid_final` int DEFAULT NULL,
  `user_id_final` bigint unsigned NOT NULL,
  `satkerid_final` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `dibaca` tinyint NOT NULL DEFAULT '0',
  `last_sent` tinyint NOT NULL DEFAULT '0',
  `isfinal` tinyint NOT NULL DEFAULT '1',
  `status` tinyint NOT NULL DEFAULT '1',
  `tgl_update` datetime DEFAULT NULL,
  `tgl_final` datetime DEFAULT NULL,
  `status_lama` tinyint NOT NULL DEFAULT '2',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `surat_keluar_nota_dinas_suratkeluar_id_unique` (`suratkeluar_id`),
  KEY `surat_keluar_nota_dinas_surat_keluar_isi_id_foreign` (`surat_keluar_isi_id`),
  KEY `surat_keluar_nota_dinas_revisi_data_id_foreign` (`revisi_data_id`),
  KEY `surat_keluar_nota_dinas_user_id_pembuat_foreign` (`user_id_pembuat`),
  KEY `surat_keluar_nota_dinas_user_id_tujuan_foreign` (`user_id_tujuan`),
  KEY `surat_keluar_nota_dinas_user_id_final_foreign` (`user_id_final`),
  CONSTRAINT `surat_keluar_nota_dinas_revisi_data_id_foreign` FOREIGN KEY (`revisi_data_id`) REFERENCES `surat_keluar_isis` (`id`),
  CONSTRAINT `surat_keluar_nota_dinas_surat_keluar_isi_id_foreign` FOREIGN KEY (`surat_keluar_isi_id`) REFERENCES `surat_keluar_isis` (`id`),
  CONSTRAINT `surat_keluar_nota_dinas_user_id_final_foreign` FOREIGN KEY (`user_id_final`) REFERENCES `users` (`id`),
  CONSTRAINT `surat_keluar_nota_dinas_user_id_pembuat_foreign` FOREIGN KEY (`user_id_pembuat`) REFERENCES `users` (`id`),
  CONSTRAINT `surat_keluar_nota_dinas_user_id_tujuan_foreign` FOREIGN KEY (`user_id_tujuan`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `surat_keluar_riwayats` */

DROP TABLE IF EXISTS `surat_keluar_riwayats`;

CREATE TABLE `surat_keluar_riwayats` (
  `id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `suratkeluar_id` int DEFAULT NULL,
  `surat_keluar_isi_id` char(26) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `revisi_id` int NOT NULL,
  `revisi_data_id` char(26) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nourut_riw` int NOT NULL,
  `nourut_kirim` int NOT NULL DEFAULT '1',
  `userid_pembuat` int NOT NULL,
  `user_id_pembuat` bigint unsigned DEFAULT NULL,
  `satkerid_pembuat` longtext COLLATE utf8mb4_unicode_ci,
  `userid_tujuan` int DEFAULT NULL,
  `user_id_tujuan` bigint unsigned DEFAULT NULL,
  `satkerid_tujuan` longtext COLLATE utf8mb4_unicode_ci,
  `userid_final` int DEFAULT NULL,
  `user_id_final` bigint unsigned DEFAULT NULL,
  `satkerid_final` longtext COLLATE utf8mb4_unicode_ci,
  `dibaca` tinyint NOT NULL DEFAULT '0',
  `last_sent` tinyint NOT NULL DEFAULT '0',
  `isfinal` tinyint NOT NULL DEFAULT '1',
  `status` tinyint NOT NULL DEFAULT '1',
  `tgl_update` datetime DEFAULT NULL,
  `tgl_final` datetime DEFAULT NULL,
  `status_lama` tinyint NOT NULL DEFAULT '2',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `surat_keluar_riwayats_suratkeluar_id_unique` (`suratkeluar_id`),
  KEY `surat_keluar_riwayats_surat_keluar_isi_id_foreign` (`surat_keluar_isi_id`),
  KEY `surat_keluar_riwayats_revisi_data_id_foreign` (`revisi_data_id`),
  KEY `surat_keluar_riwayats_user_id_pembuat_foreign` (`user_id_pembuat`),
  KEY `surat_keluar_riwayats_user_id_tujuan_foreign` (`user_id_tujuan`),
  KEY `surat_keluar_riwayats_user_id_final_foreign` (`user_id_final`),
  CONSTRAINT `surat_keluar_riwayats_revisi_data_id_foreign` FOREIGN KEY (`revisi_data_id`) REFERENCES `surat_keluar_isis` (`id`),
  CONSTRAINT `surat_keluar_riwayats_surat_keluar_isi_id_foreign` FOREIGN KEY (`surat_keluar_isi_id`) REFERENCES `surat_keluar_isis` (`id`),
  CONSTRAINT `surat_keluar_riwayats_user_id_final_foreign` FOREIGN KEY (`user_id_final`) REFERENCES `users` (`id`),
  CONSTRAINT `surat_keluar_riwayats_user_id_pembuat_foreign` FOREIGN KEY (`user_id_pembuat`) REFERENCES `users` (`id`),
  CONSTRAINT `surat_keluar_riwayats_user_id_tujuan_foreign` FOREIGN KEY (`user_id_tujuan`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fullname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jabatan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `satkerid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nip` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '-',
  `usergroupid` int DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `last_notif` date DEFAULT NULL,
  `pangkat` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=886 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
