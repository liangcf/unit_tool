/*
Navicat MySQL Data Transfer

Source Server         : 本地MySql
Source Server Version : 50714
Source Host           : localhost:3306
Source Database       : test

Target Server Type    : MYSQL
Target Server Version : 50714
File Encoding         : 65001

Date: 2016-08-08 10:31:19
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `data` json DEFAULT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('1', '{\"mail\": \"jiangchengyao@gmail.com\", \"name\": \"David\", \"address\": \"Shangahai\"}');
INSERT INTO `user` VALUES ('2', '{\"mail\": \"amy@gmail.com\", \"name\": \"Amy\"}');
INSERT INTO `user` VALUES ('3', '{\"age\": \"10\", \"name\": \"梁朝富\"}');
INSERT INTO `user` VALUES ('4', '{\"age\": \"10\", \"name\": \"梁朝富\"}');
INSERT INTO `user` VALUES ('5', '{\"age\": \"24\", \"name\": \"梁朝伟\"}');
INSERT INTO `user` VALUES ('6', '{\"openid\": \"oOPChs5tikOoyZUXrtphNz1IBdvA\", \"meet_id\": 931, \"page_size\": 1000, \"page_index\": 1}');
INSERT INTO `user` VALUES ('7', '{\"code\": 0, \"data\": [{\"hb_id\": \"30940afae4e4c3aea59c2f9c2527a966\", \"money\": 2032, \"status\": 1, \"meet_id\": 931, \"open_id\": \"oOPChs5tikOoyZUXrtphNz1IBdvA\", \"start_time\": \"2016-08-04 02:36:58\"}, {\"hb_id\": \"0a9c0682b768dfd7438e29541086709e\", \"money\": 0, \"status\": 0, \"meet_id\": 931, \"open_id\": \"oOPChs5tikOoyZUXrtphNz1IBdvA\", \"start_time\": \"2016-08-04 02:16:00\"}], \"total_money\": 2032}');
SET FOREIGN_KEY_CHECKS=1;

/*

-- http://blog.csdn.net/yin767833376/article/details/52032927

-- 表达式 ： json列->'$.键'
-- select data ->'$.name' from user;

-- 等价于 ：JSON_EXTRACT(json列 , '$.键')
-- select json_extract(data, '$.name') from user;

-- select json_extract(data, '$.data') from user;
-- select json_extract(data, '$.data') from user where uid=7;

-- select json_extract(data, '$[0]') from user where uid=7;

-- select json_extract(data, '$[0].data') from user where uid=7;
--<-- 上下两句等价 -->
-- select json_extract(data, '$.data') from user where uid=7;

-- select json_extract(data, '$[0].data[0]') from user where uid=7;

-- select json_extract(data, '$[0].data[0].hb_id') from user where uid=7;

-- select json_extract(data, '$.name') from user;
-- <-- 上下两句等价 -->
-- select json_extract(data, '$[0].name') from user;

-- update user set json_extract(data, '$[0].name')='张学友' where uid=1;

-- select json_extract(data, '$.name'),json_extract(data,'$.address') from user where json_extract(data, '$.name') = 'David';

-- select json_extract(data, '$.name') from user where json_extract(data, '$.name') like '%a%';

-- select json_extract(data, '$.name') from user where json_extract(data, '$.name') like '%朝%';
*/
