/*
Navicat MySQL Data Transfer

Source Server         : 10.254.1.68-linux
Source Server Version : 50714
Source Host           : 10.254.1.68:3306
Source Database       : fr_test

Target Server Type    : MYSQL
Target Server Version : 50714
File Encoding         : 65001

Date: 2016-09-22 17:56:03
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` char(32) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `sex` char(1) DEFAULT NULL,
  `sort_order` int(11) DEFAULT NULL,
  `microsecond` char(10) DEFAULT NULL COMMENT '时间 秒'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('f49871b4f678098a16720e0539354f6f', '边扈', '0', '50699', '0.86784200');
INSERT INTO `users` VALUES ('4928382e75799aeb0e8cbc4214b24d83', '濮阳淳', '1', '36436', '0.87036300');
INSERT INTO `users` VALUES ('f66a3dd434f2fc326483383f7ed3e121', '霍虞万', '1', '44440', '0.87279300');
INSERT INTO `users` VALUES ('bdd4ce6a0fcbcd115f44c94ffd430713', '钮龚程嵇', '0', '71164', '0.87522500');
INSERT INTO `users` VALUES ('20c2c174f84c17db0fb930d570b2fb4f', '芮羿储靳', '0', '23258', '0.87714900');
INSERT INTO `users` VALUES ('587d3d80b77907d527c1aed65c17a3ad', '公良拓拔', '1', '36305', '0.87926100');
INSERT INTO `users` VALUES ('52cf7b097e31b21373f354da8dab1dea', '巩厍', '1', '28330', '0.88179000');
INSERT INTO `users` VALUES ('c34cb6f20040ae0edfabeb2f02bbcf8c', '百里', '0', '49031', '0.88405200');
INSERT INTO `users` VALUES ('f9af4985755618c7a0e8f95b184828ee', '徐丘骆', '1', '6511', '0.88643600');
INSERT INTO `users` VALUES ('a804bedc208d3dd2cbd2844494ca6e28', '岑薛雷贺', '0', '41165', '0.88983900');
INSERT INTO `users` VALUES ('97e2cfc54bad676d03cd2367c762552b', '舒屈', '1', '56688', '0.89177700');
INSERT INTO `users` VALUES ('b29aad8ae75640c9836e85ea1f5299dc', '鲍史', '0', '48987', '0.89433200');
INSERT INTO `users` VALUES ('e0e6f8c2a76109848b9a8abcc31cd2b5', '廉岑薛', '1', '1456', '0.89657700');
INSERT INTO `users` VALUES ('76125807bda1ff47cef62e72bbc5fef8', '宗丁宣', '0', '97995', '0.89903800');
INSERT INTO `users` VALUES ('de781957b60f00e707a31945460b07ca', '甫尉迟公', '0', '69410', '0.90101300');
INSERT INTO `users` VALUES ('2555657c000c443dd255d0e45d3c37fb', '康伍余', '0', '43118', '0.90315600');
INSERT INTO `users` VALUES ('e98650eaeb089ed843f05d829981a75a', '薛雷', '0', '24725', '0.90577100');
INSERT INTO `users` VALUES ('e185d56c80f9927b9e5f20f3bea0ad42', '丌官司寇', '1', '7730', '0.90790700');
INSERT INTO `users` VALUES ('975ef1a8aed72cba445069b44a06ec1b', '倪汤滕', '1', '24611', '0.91099900');
INSERT INTO `users` VALUES ('ac33e4fd740128a84415195b887c940d', '戈廖庾终', '1', '73197', '0.91304900');
INSERT INTO `users` VALUES ('fae6b1f96855166dbb7088f9176e5a4a', '符刘景', '1', '76731', '0.91505200');
INSERT INTO `users` VALUES ('3fe43af7f872203f90c5ae9122344c7a', '东郭', '1', '69844', '0.91875000');
INSERT INTO `users` VALUES ('b9bef4223c44969c12fe3d701b05fe60', '钱孙', '0', '16841', '0.92094700');
INSERT INTO `users` VALUES ('8b81550f09bc670bb7452d8878086a26', '都耿满', '1', '74982', '0.92350500');
INSERT INTO `users` VALUES ('d163df464938be99474fb8f4e9dd4004', '盖益', '1', '57805', '0.92576100');
INSERT INTO `users` VALUES ('13f1506099c98d0829c3db5b6ac024fc', '琴梁丘', '1', '9767', '0.92781800');
INSERT INTO `users` VALUES ('fb989a053121bd54bc90226412498aea', '雷贺', '0', '73685', '0.92969800');
INSERT INTO `users` VALUES ('d46c1dd28d4f062f1ce76d29f25efc41', '和穆', '0', '62699', '0.93238300');
INSERT INTO `users` VALUES ('c41a3ac6765e78435559afd6b9322964', '邰从', '0', '41676', '0.93494700');
INSERT INTO `users` VALUES ('e7322875f4a8254474bb5ac3d61b82da', '支柯', '0', '30181', '0.93696700');
INSERT INTO `users` VALUES ('c3a6909432ba86606d8f81200cfc3914', '罗毕郝', '0', '76231', '0.93948700');
INSERT INTO `users` VALUES ('4b993aa97f546cdd8e93d1e068e179c3', '陶姜', '1', '78340', '0.96105800');
INSERT INTO `users` VALUES ('355e55a592a14743a4b7349229ba80c9', '柳酆鲍', '0', '7741', '0.96350700');
INSERT INTO `users` VALUES ('db76f29c0c93f08df99af86e221e8242', '倪汤滕殷', '0', '25614', '0.96584400');
INSERT INTO `users` VALUES ('b1663e476bf2f5a34c217db19cbbaecf', '徐丘骆', '1', '44862', '0.96790700');
INSERT INTO `users` VALUES ('291bf77b08aadeb1321d97c7243f18cd', '申扶堵', '0', '2692', '0.97022600');
INSERT INTO `users` VALUES ('a0065c969ba4f7639d8f29f21cd06992', '阙东欧', '0', '93673', '0.97315600');
INSERT INTO `users` VALUES ('b414c270d5e0df436daf761c275cb2ce', '充慕连', '0', '72365', '0.97517800');
INSERT INTO `users` VALUES ('d65aef693aa5df60400c46e612abb56c', '韩杨朱秦', '0', '9138', '0.97755800');
INSERT INTO `users` VALUES ('da70fc0db797a2e72a4b31df4ae38639', '盖益桓公', '1', '83432', '0.97975000');
INSERT INTO `users` VALUES ('e7ce19a261daaa5ad1158e5474d803d2', '蒲邰', '1', '61250', '0.98185000');
INSERT INTO `users` VALUES ('bb99e8c85868c586a9a3f64b3f2f6c50', '庞熊', '1', '48943', '0.98402900');
INSERT INTO `users` VALUES ('737c8169d30e58cad9c62acf1471ef11', '宰郦雍郤', '1', '8441', '0.98710600');
INSERT INTO `users` VALUES ('a0aeea6c1add954c8c4f51d5f189f8d3', '霍虞万支', '0', '93066', '0.99014100');
INSERT INTO `users` VALUES ('3484a2b03d452cdaeb2754dec14cde35', '武符刘', '1', '4516', '0.99280300');
INSERT INTO `users` VALUES ('ec6f7c4c8883535f33978d05d88597da', '陶姜', '1', '9059', '0.99533900');
INSERT INTO `users` VALUES ('f6d1e89c371c355039026ecc9d00e035', '冶宗', '0', '51333', '0.99783800');
INSERT INTO `users` VALUES ('bd7d43c730bd13ba1a604b85ed9393f7', '祝董', '0', '71017', '0.99985200');
INSERT INTO `users` VALUES ('455b13c717a6846ac53618d9489824c0', '贾路', '0', '46609', '0.00184100');
INSERT INTO `users` VALUES ('7e34df610094f1ac793816520c06b4fa', '卢莫', '1', '84197', '0.00388300');
INSERT INTO `users` VALUES ('caad013d62343fdeac7af5ad12a9d048', '王冯陈楮', '1', '30642', '0.02766600');
INSERT INTO `users` VALUES ('8ce1795ec8338ef283f33f0ad7a6a249', '端木', '0', '32092', '0.02998300');
INSERT INTO `users` VALUES ('c18f7d11bcd9e95586abb89c74e667be', '劳逄', '0', '58670', '0.03258000');
INSERT INTO `users` VALUES ('40167e07f0cbaea8bffe414577f358fd', '狄米', '1', '97518', '0.03486300');
INSERT INTO `users` VALUES ('f63f87241e6030623a3ad43a30e87ba7', '龚程嵇邢', '0', '57255', '0.03757500');
INSERT INTO `users` VALUES ('b3439d417f76d23294181aaead4053c5', '仉督子车', '0', '10906', '0.04028600');
INSERT INTO `users` VALUES ('1d44f7af554bdd7edd2909578cdbde95', '柏水窦', '1', '37181', '0.04271900');
INSERT INTO `users` VALUES ('7afbb3c83d99e8e7ecfc204ebcf0ac56', '连皇甫', '0', '77968', '0.04524200');
INSERT INTO `users` VALUES ('008bd49aebb452a5e3b6a40c8e445c91', '储靳', '0', '25158', '0.04761000');
INSERT INTO `users` VALUES ('21163454d9e46a168f73208b6a6e6f15', '汤滕', '1', '66067', '0.05052500');
INSERT INTO `users` VALUES ('484a8a30cbe9506f78849d8282e682ce', '弓牧隗山', '0', '59814', '0.05261300');
INSERT INTO `users` VALUES ('b10c1c52d2150477317439f5c0bff160', '王冯陈楮', '1', '36319', '0.05509800');
INSERT INTO `users` VALUES ('f19bb9c90a56eefca923579a2aed2e41', '公万', '1', '59000', '0.05720000');
INSERT INTO `users` VALUES ('51a669845cdf94dbf5df5986867aa548', '漆雕', '1', '94356', '0.05946300');
INSERT INTO `users` VALUES ('4efe65058025b1b7aa51469627ad493c', '于单', '0', '56061', '0.06203400');
INSERT INTO `users` VALUES ('7cbaed4318826131645faa9d3aa29865', '葛闻人', '0', '13923', '0.06416500');
INSERT INTO `users` VALUES ('6f6871d67eb76fa6cc117abece058fea', '文寇广', '0', '76724', '0.06675300');
INSERT INTO `users` VALUES ('f5798f37bba816903b575cc862e1f526', '红游竺权', '0', '12731', '0.06890600');
INSERT INTO `users` VALUES ('fc40bc3ece830caa8885808cb139251b', '司徒司', '1', '85546', '0.07141900');
INSERT INTO `users` VALUES ('72755da431cc21b98e049a8ecc12542b', '冀郏', '0', '30394', '0.07395000');
INSERT INTO `users` VALUES ('8106016d8393a45d0b2947d9ac256082', '左石', '1', '21860', '0.07620100');
INSERT INTO `users` VALUES ('8ea74f58abee961d3f6bc38f80e3ed39', '石崔吉', '0', '13678', '0.09140500');
INSERT INTO `users` VALUES ('51362a43d5ffc062b6ef0dd4e11aafdd', '令狐锺', '1', '57220', '0.09377800');
INSERT INTO `users` VALUES ('ed60da6803f1d5233efc245355ff4717', '门商', '1', '61604', '0.09593500');
INSERT INTO `users` VALUES ('dbf855cd75f9cde17f7a1a1ea00f6ff2', '伊宫', '0', '15375', '0.09858600');
INSERT INTO `users` VALUES ('531d4e7f174416ceebcb55a556568543', '李周吴郑', '0', '84754', '0.10128200');
INSERT INTO `users` VALUES ('a97d9c2680ca3bd3069e59e92e69c879', '叶幸司韶', '1', '3648', '0.10376600');
INSERT INTO `users` VALUES ('93c7d4011c383a4af7f6d80395a453a1', '郭梅盛林', '0', '59589', '0.10639200');
INSERT INTO `users` VALUES ('a9269dd8bac17ec9c5d229631136a84b', '曹严', '1', '38896', '0.10911200');
INSERT INTO `users` VALUES ('45a83fad61878b97302bbcf08f337949', '羊澹台公', '1', '59467', '0.11164600');
INSERT INTO `users` VALUES ('3e0f7adb6a0a21e8633e791762f893dc', '郗班', '0', '40093', '0.11376600');
INSERT INTO `users` VALUES ('586d0fc91b4465c12368cdd3a7f0529f', '茅庞熊纪', '0', '64499', '0.11800800');
INSERT INTO `users` VALUES ('a2a53d50eb3c61c3d8d1201764ee2564', '郭南门', '1', '93617', '0.12079800');
INSERT INTO `users` VALUES ('df7fec640946b0de07c7ef424bc00f09', '湛汪', '1', '64798', '0.12322800');
INSERT INTO `users` VALUES ('5242f4aff627b0586a840a5b03d9a6c4', '韩杨朱', '1', '29161', '0.12566400');
INSERT INTO `users` VALUES ('8f6894ef1ea32ebb9ebc84c8561d1f50', '富巫乌焦', '1', '44447', '0.12795700');
INSERT INTO `users` VALUES ('932c6a0e4173e0a442af1e89fcd241f0', '计伏成戴', '1', '97668', '0.13003600');
INSERT INTO `users` VALUES ('f56a6e7f59f0c6b597e6dbfae2a6c06e', '国文', '1', '6290', '0.13243500');
INSERT INTO `users` VALUES ('08f0602d69e65d4fd5041a8007035a72', '贡劳逄姬', '0', '74320', '0.13517800');
INSERT INTO `users` VALUES ('5dda81afec8c7acbe239ee184d4b6d48', '柴瞿阎', '0', '51713', '0.13746800');
INSERT INTO `users` VALUES ('1b42e54725c799983e53930e508f6c85', '丘司', '0', '3256', '0.14153800');
INSERT INTO `users` VALUES ('a6f289f66338034aabb5359e84a745c9', '纪舒', '1', '25307', '0.14390300');
INSERT INTO `users` VALUES ('e7de25f5f9cdb830faf26c3251c2142c', '相查后', '1', '11215', '0.14624300');
INSERT INTO `users` VALUES ('35d89982c87224917a1c6f0f13fb82e6', '诸左石', '1', '53920', '0.14879200');
INSERT INTO `users` VALUES ('9516f4659d59c0268eebf8b8a9773923', '郎鲁韦', '1', '72290', '0.15088400');
INSERT INTO `users` VALUES ('6e4301fe2780c0a9ccfdaf49f8ac4307', '乜养', '1', '23158', '0.15358400');
INSERT INTO `users` VALUES ('40a6841da4f930df1b44dd580c437fbd', '屠公', '1', '39205', '0.15617000');
INSERT INTO `users` VALUES ('8bb3a46b7208ca446dfb416cbdfaa438', '訾辛阚', '1', '52153', '0.15869400');
INSERT INTO `users` VALUES ('5a8cb8af468faba53a51249feab7f7da', '苍双闻', '1', '73666', '0.16067500');
INSERT INTO `users` VALUES ('ba00cc3e7427fdd3219964297361c317', '温别', '1', '29329', '0.16309500');
SET FOREIGN_KEY_CHECKS=1;
