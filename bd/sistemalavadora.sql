/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 100422
 Source Host           : localhost:3306
 Source Schema         : sistemalavadora

 Target Server Type    : MySQL
 Target Server Version : 100422
 File Encoding         : 65001

 Date: 30/06/2022 03:21:22
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for pedido
-- ----------------------------
DROP TABLE IF EXISTS `pedido`;
CREATE TABLE `pedido`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idCliente` int(11) NOT NULL,
  `idResponsable` int(11) NULL DEFAULT NULL,
  `observacion` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish_ci NULL DEFAULT NULL,
  `subtotal` double(11, 2) NULL DEFAULT NULL,
  `iva` double(11, 2) NULL DEFAULT NULL,
  `descuento` double(11, 2) NULL DEFAULT NULL,
  `total` double(11, 2) NULL DEFAULT NULL,
  `fecha` date NULL DEFAULT NULL,
  `estado` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci NULL DEFAULT NULL,
  `hora` time(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `fkCliente`(`idCliente`) USING BTREE,
  INDEX `fkResponsable`(`idResponsable`) USING BTREE,
  CONSTRAINT `fkCliente` FOREIGN KEY (`idCliente`) REFERENCES `usuario` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fkResponsable` FOREIGN KEY (`idResponsable`) REFERENCES `persona` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8 COLLATE = utf8_spanish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of pedido
-- ----------------------------
INSERT INTO `pedido` VALUES (4, 1, 0, NULL, 1.76, 0.24, NULL, 2.00, '2022-06-30', 'GENERADO', '10:30:00');
INSERT INTO `pedido` VALUES (5, 1, 0, NULL, 117.92, 16.08, NULL, 134.00, '2022-06-30', 'GENERADO', '09:00:00');
INSERT INTO `pedido` VALUES (6, 1, 0, NULL, 117.92, 16.08, NULL, 134.00, '2022-06-30', 'GENERADO', '10:30:00');
INSERT INTO `pedido` VALUES (7, 1, 0, NULL, 1.76, 0.24, NULL, 2.00, '2022-06-30', 'GENERADO', '10:30:00');

-- ----------------------------
-- Table structure for pedidodt
-- ----------------------------
DROP TABLE IF EXISTS `pedidodt`;
CREATE TABLE `pedidodt`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idPedido` int(11) NULL DEFAULT NULL,
  `idProducto` int(11) NULL DEFAULT NULL,
  `cantidad` int(11) NULL DEFAULT NULL,
  `subtotal` double(11, 2) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `fkPedido`(`idPedido`) USING BTREE,
  INDEX `fkProducto`(`idProducto`) USING BTREE,
  CONSTRAINT `fkPedido` FOREIGN KEY (`idPedido`) REFERENCES `pedido` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fkProducto` FOREIGN KEY (`idProducto`) REFERENCES `producto` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8 COLLATE = utf8_spanish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pedidodt
-- ----------------------------
INSERT INTO `pedidodt` VALUES (4, 4, 1, 1, 2.00);
INSERT INTO `pedidodt` VALUES (5, 5, 2, 1, 134.00);
INSERT INTO `pedidodt` VALUES (6, 6, 2, 1, 134.00);
INSERT INTO `pedidodt` VALUES (7, 7, 1, 1, 2.00);

-- ----------------------------
-- Table structure for persona
-- ----------------------------
DROP TABLE IF EXISTS `persona`;
CREATE TABLE `persona`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idUsuario` int(11) NOT NULL,
  `nombres` varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `apellidos` varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `telefono` varchar(25) CHARACTER SET utf8 COLLATE utf8_spanish_ci NULL DEFAULT NULL,
  `celular` varchar(25) CHARACTER SET utf8 COLLATE utf8_spanish_ci NULL DEFAULT NULL,
  `direccion` varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `latitud` double(11, 2) NOT NULL,
  `longitud` double(11, 2) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `fkUsuario`(`idUsuario`) USING BTREE,
  CONSTRAINT `fkUsuario` FOREIGN KEY (`idUsuario`) REFERENCES `usuario` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8 COLLATE = utf8_spanish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of persona
-- ----------------------------
INSERT INTO `persona` VALUES (0, 0, 'APP', 'ANDROID', '9999999999', '9999999999', '99999999999', 0.00, 0.00);
INSERT INTO `persona` VALUES (1, 1, 'ESTEBAN Luis', 'RUIZ ROJAS', '0222607038', '0998286811', 'Quitojdjdjd', 0.00, 0.00);
INSERT INTO `persona` VALUES (6, 12, 'hola', 'dasdasdas', '345346364', '12312312', 'asdasdasd', 0.00, 0.00);
INSERT INTO `persona` VALUES (9, 15, 'jdjdjd', 'jdjdjd', '64646464', '94946464', 'jsjsjsjs', 0.00, 0.00);

-- ----------------------------
-- Table structure for producto
-- ----------------------------
DROP TABLE IF EXISTS `producto`;
CREATE TABLE `producto`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `producto` varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish_ci NULL DEFAULT NULL,
  `descripcion` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish_ci NULL DEFAULT NULL,
  `cantidad` int(11) NULL DEFAULT NULL,
  `precioCosto` double(11, 2) NULL DEFAULT NULL,
  `pvp` double(11, 2) NULL DEFAULT NULL,
  `estado` varchar(25) CHARACTER SET utf8 COLLATE utf8_spanish_ci NULL DEFAULT NULL,
  `imagen` varchar(500) CHARACTER SET utf8 COLLATE utf8_spanish_ci NULL DEFAULT NULL,
  `tipo` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_spanish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of producto
-- ----------------------------
INSERT INTO `producto` VALUES (1, 'Lavado', 'ewtwetwet', 12, 1.00, 2.00, 'ACTIVO', 'cumbre.jpg', 1);
INSERT INTO `producto` VALUES (2, 'Aceite', 'tyityityi', 99999, 12.00, 134.00, 'ACTIVO', '12-image_web.jpg', 2);

-- ----------------------------
-- Table structure for rol
-- ----------------------------
DROP TABLE IF EXISTS `rol`;
CREATE TABLE `rol`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rol` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_spanish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of rol
-- ----------------------------
INSERT INTO `rol` VALUES (1, 'ADMINISTRADOR', 'Usuario con acceso a todo el sistema');
INSERT INTO `rol` VALUES (2, 'EMPLEADO', 'Usuario con acceso limitado al sistema');
INSERT INTO `rol` VALUES (3, 'CLIENTE', 'Usuario con acceso a realizar pedidos');

-- ----------------------------
-- Table structure for usuario
-- ----------------------------
DROP TABLE IF EXISTS `usuario`;
CREATE TABLE `usuario`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `clave` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `idRol` int(11) NOT NULL,
  `estado` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `fkRol`(`idRol`) USING BTREE,
  CONSTRAINT `fkRol` FOREIGN KEY (`idRol`) REFERENCES `rol` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 17 CHARACTER SET = utf8 COLLATE = utf8_spanish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of usuario
-- ----------------------------
INSERT INTO `usuario` VALUES (0, '9999999999', '999999', 1, 'ACTIVO');
INSERT INTO `usuario` VALUES (1, '1719513457', '123', 1, 'ACTIVO');
INSERT INTO `usuario` VALUES (12, '1709863946', 'carwash1234', 3, 'ACTIVO');
INSERT INTO `usuario` VALUES (15, '1103144448', '456', 3, 'ACTIVO');

SET FOREIGN_KEY_CHECKS = 1;
