/*
 Navicat Premium Data Transfer

 Source Server         : flive
 Source Server Type    : MySQL
 Source Server Version : 50724
 Source Host           : localhost:3306
 Source Schema         : mafia_game

 Target Server Type    : MySQL
 Target Server Version : 50724
 File Encoding         : 65001

 Date: 18/04/2024 16:34:10
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for game
-- ----------------------------
DROP TABLE IF EXISTS `game`;
CREATE TABLE `game`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `day` int(11) NULL DEFAULT 1,
  `eliminate_user_id` int(11) NULL DEFAULT NULL,
  `protect_user_id` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `eliminate_user_id`(`eliminate_user_id`) USING BTREE,
  INDEX `protect_user_id`(`protect_user_id`) USING BTREE,
  CONSTRAINT `game_ibfk_1` FOREIGN KEY (`eliminate_user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `game_ibfk_2` FOREIGN KEY (`protect_user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 45 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of game
-- ----------------------------

-- ----------------------------
-- Table structure for game_user
-- ----------------------------
DROP TABLE IF EXISTS `game_user`;
CREATE TABLE `game_user`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `game_id` int(11) NULL DEFAULT NULL,
  `user_id` int(11) NULL DEFAULT NULL,
  `role_id` int(11) NULL DEFAULT NULL,
  `text` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `is_alive` tinyint(1) NULL DEFAULT 1,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_id`(`user_id`) USING BTREE,
  INDEX `role_id`(`role_id`) USING BTREE,
  INDEX `game_id`(`game_id`) USING BTREE,
  CONSTRAINT `game_user_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `game_user_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `game_user_ibfk_3` FOREIGN KEY (`game_id`) REFERENCES `game` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 371 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of game_user
-- ----------------------------

-- ----------------------------
-- Table structure for game_user_history
-- ----------------------------
DROP TABLE IF EXISTS `game_user_history`;
CREATE TABLE `game_user_history`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `game_id` int(11) NULL DEFAULT NULL,
  `actor_id` int(11) NULL DEFAULT NULL,
  `recipient_id` int(11) NULL DEFAULT NULL,
  `action` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `actor_id`(`actor_id`) USING BTREE,
  INDEX `recipient_id`(`recipient_id`) USING BTREE,
  INDEX `game_id`(`game_id`) USING BTREE,
  CONSTRAINT `game_user_history_ibfk_1` FOREIGN KEY (`actor_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `game_user_history_ibfk_2` FOREIGN KEY (`recipient_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `game_user_history_ibfk_3` FOREIGN KEY (`game_id`) REFERENCES `game` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 865 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of game_user_history
-- ----------------------------

-- ----------------------------
-- Table structure for role
-- ----------------------------
DROP TABLE IF EXISTS `role`;
CREATE TABLE `role`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `name`(`name`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of role
-- ----------------------------
INSERT INTO `role` VALUES (4, 'Detective');
INSERT INTO `role` VALUES (3, 'Doctor');
INSERT INTO `role` VALUES (1, 'Mafia');
INSERT INTO `role` VALUES (2, 'Villager');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `is_pc_player` tinyint(1) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 16 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'Player_1', '$2y$10$rEw/BtcC1.gyj5Z5gI.0ROyEEKv73xFrZ1Kae5FMjWvN30nfsmc.u', 1);
INSERT INTO `users` VALUES (2, 'Player_2', '$2y$10$rEw/BtcC1.gyj5Z5gI.0ROyEEKv73xFrZ1Kae5FMjWvN30nfsmc.u', 1);
INSERT INTO `users` VALUES (3, 'Player_3', '$2y$10$rEw/BtcC1.gyj5Z5gI.0ROyEEKv73xFrZ1Kae5FMjWvN30nfsmc.u', 1);
INSERT INTO `users` VALUES (4, 'Player_4', '$2y$10$rEw/BtcC1.gyj5Z5gI.0ROyEEKv73xFrZ1Kae5FMjWvN30nfsmc.u', 1);
INSERT INTO `users` VALUES (5, 'Player_5', '$2y$10$rEw/BtcC1.gyj5Z5gI.0ROyEEKv73xFrZ1Kae5FMjWvN30nfsmc.u', 1);
INSERT INTO `users` VALUES (6, 'Player_6', '$2y$10$rEw/BtcC1.gyj5Z5gI.0ROyEEKv73xFrZ1Kae5FMjWvN30nfsmc.u', 1);
INSERT INTO `users` VALUES (7, 'Player_7', '$2y$10$rEw/BtcC1.gyj5Z5gI.0ROyEEKv73xFrZ1Kae5FMjWvN30nfsmc.u', 1);
INSERT INTO `users` VALUES (8, 'Player_8', '$2y$10$rEw/BtcC1.gyj5Z5gI.0ROyEEKv73xFrZ1Kae5FMjWvN30nfsmc.u', 1);
INSERT INTO `users` VALUES (9, 'Player_9', '$2y$10$rEw/BtcC1.gyj5Z5gI.0ROyEEKv73xFrZ1Kae5FMjWvN30nfsmc.u', 1);

SET FOREIGN_KEY_CHECKS = 1;
