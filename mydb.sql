/*
Navicat MySQL Data Transfer

Source Server         : homestead
Source Server Version : 50723
Source Host           : localhost:33060
Source Database       : mydb

Target Server Type    : MYSQL
Target Server Version : 50723
File Encoding         : 65001

Date: 2019-12-11 08:20:07
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for activity
-- ----------------------------
DROP TABLE IF EXISTS `activity`;
CREATE TABLE `activity` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(3) unsigned NOT NULL COMMENT '标识活动类型 1=>考试 2=>投票',
  `activity_id` int(10) unsigned NOT NULL COMMENT '与考试表或者投票表等关联',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '2' COMMENT '活动状态 1=>进行中 2=>未开始 3=>已结束',
  `assoc_id` int(10) unsigned NOT NULL COMMENT '所属社团id',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of activity
-- ----------------------------
INSERT INTO `activity` VALUES ('3', '1', '10', '1', '1', '2019-12-05 18:48:03', '2019-12-05 18:48:06');
INSERT INTO `activity` VALUES ('4', '1', '11', '2', '1', '2019-12-05 18:48:08', '2019-12-05 18:48:10');
INSERT INTO `activity` VALUES ('5', '1', '12', '2', '1', '2019-12-05 18:48:12', '2019-12-05 18:48:14');
INSERT INTO `activity` VALUES ('6', '1', '13', '2', '1', '2019-12-05 18:48:16', '2019-12-05 18:48:18');
INSERT INTO `activity` VALUES ('7', '1', '14', '2', '1', '2019-12-05 18:48:20', '2019-12-05 18:48:22');
INSERT INTO `activity` VALUES ('8', '1', '15', '2', '1', '2019-12-05 18:48:27', '2019-12-05 18:48:29');
INSERT INTO `activity` VALUES ('9', '1', '16', '2', '1', '2019-12-05 18:48:32', '2019-12-05 18:48:33');
INSERT INTO `activity` VALUES ('10', '1', '17', '2', '1', '2019-12-05 18:48:35', '2019-12-05 18:48:36');
INSERT INTO `activity` VALUES ('11', '1', '18', '2', '1', '2019-12-03 18:48:38', '2019-12-05 18:48:39');
INSERT INTO `activity` VALUES ('12', '1', '19', '2', '1', '2019-12-06 08:51:40', '2019-12-06 08:51:40');

-- ----------------------------
-- Table structure for admin
-- ----------------------------
DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `ad_name` varchar(20) NOT NULL DEFAULT '' COMMENT '管理员账户',
  `password` varchar(255) NOT NULL DEFAULT '' COMMENT '管理密码',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理员登陆表';

-- ----------------------------
-- Records of admin
-- ----------------------------
INSERT INTO `admin` VALUES ('1', 'administrator', '$2y$10$ycRZSGQs/1IvGhQWxmQ1T.FXrLDh4fHlkKrHqKbHO.JLWY2ZwIF96');

-- ----------------------------
-- Table structure for assessment_topic
-- ----------------------------
DROP TABLE IF EXISTS `assessment_topic`;
CREATE TABLE `assessment_topic` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `asses_id` int(10) unsigned NOT NULL COMMENT '考试id',
  `topic_id` int(10) unsigned NOT NULL COMMENT '题目id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of assessment_topic
-- ----------------------------
INSERT INTO `assessment_topic` VALUES ('1', '3', '1');
INSERT INTO `assessment_topic` VALUES ('2', '3', '2');
INSERT INTO `assessment_topic` VALUES ('3', '4', '1');
INSERT INTO `assessment_topic` VALUES ('4', '4', '2');
INSERT INTO `assessment_topic` VALUES ('5', '5', '1');
INSERT INTO `assessment_topic` VALUES ('6', '5', '2');
INSERT INTO `assessment_topic` VALUES ('7', '6', '1');
INSERT INTO `assessment_topic` VALUES ('8', '6', '2');
INSERT INTO `assessment_topic` VALUES ('9', '7', '1');
INSERT INTO `assessment_topic` VALUES ('10', '7', '2');
INSERT INTO `assessment_topic` VALUES ('18', '10', '3');
INSERT INTO `assessment_topic` VALUES ('19', '10', '2');
INSERT INTO `assessment_topic` VALUES ('20', '11', '1');
INSERT INTO `assessment_topic` VALUES ('21', '11', '2');
INSERT INTO `assessment_topic` VALUES ('22', '12', '1');
INSERT INTO `assessment_topic` VALUES ('23', '12', '2');
INSERT INTO `assessment_topic` VALUES ('24', '13', '1');
INSERT INTO `assessment_topic` VALUES ('25', '13', '2');
INSERT INTO `assessment_topic` VALUES ('26', '14', '3');
INSERT INTO `assessment_topic` VALUES ('27', '14', '2');
INSERT INTO `assessment_topic` VALUES ('28', '15', '1');
INSERT INTO `assessment_topic` VALUES ('29', '15', '2');
INSERT INTO `assessment_topic` VALUES ('30', '16', '1');
INSERT INTO `assessment_topic` VALUES ('31', '16', '2');
INSERT INTO `assessment_topic` VALUES ('32', '17', '2');
INSERT INTO `assessment_topic` VALUES ('33', '17', '3');
INSERT INTO `assessment_topic` VALUES ('34', '18', '2');
INSERT INTO `assessment_topic` VALUES ('35', '18', '3');
INSERT INTO `assessment_topic` VALUES ('36', '18', '4');
INSERT INTO `assessment_topic` VALUES ('37', '18', '5');
INSERT INTO `assessment_topic` VALUES ('38', '18', '6');
INSERT INTO `assessment_topic` VALUES ('39', '19', '2');
INSERT INTO `assessment_topic` VALUES ('40', '19', '2');

-- ----------------------------
-- Table structure for assessments
-- ----------------------------
DROP TABLE IF EXISTS `assessments`;
CREATE TABLE `assessments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ass_name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '考试标题',
  `study_avi` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '前置学习视频地址',
  `clipping_time` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '限制做题时间',
  `experience` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '经验值',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of assessments
-- ----------------------------
INSERT INTO `assessments` VALUES ('1', '测试2', '', '20', '0');
INSERT INTO `assessments` VALUES ('2', '测试22', '', '20', '0');
INSERT INTO `assessments` VALUES ('3', '测试1', '', '20', '0');
INSERT INTO `assessments` VALUES ('4', '测试1', '', '20', '0');
INSERT INTO `assessments` VALUES ('5', '测试1', '', '20', '0');
INSERT INTO `assessments` VALUES ('6', '测试1', '舞蹈社/15728288216387.sql', '20', '0');
INSERT INTO `assessments` VALUES ('7', '测试1', '舞蹈社/15728354778077.sql', '20', '0');
INSERT INTO `assessments` VALUES ('10', '测试', null, '20', '0');
INSERT INTO `assessments` VALUES ('11', '测试23', '', '20', '0');
INSERT INTO `assessments` VALUES ('12', '测试22', '舞蹈社/测试1/15730870471857.txt', '20', '0');
INSERT INTO `assessments` VALUES ('13', '测试1', '舞蹈社/test/测试1/15730870879969.txt', '20', '0');
INSERT INTO `assessments` VALUES ('14', '测试1', '舞蹈队22/test/测试1/15730915401280.sql', '20', '0');
INSERT INTO `assessments` VALUES ('15', '测试1', '舞蹈队22/test/测试1/15731316245156.mp4', '20', '0');
INSERT INTO `assessments` VALUES ('16', '测试1', '舞蹈队22/test/测试1/15731322163095.mp4', '20', '0');
INSERT INTO `assessments` VALUES ('17', '测试1', '', '20', '0');
INSERT INTO `assessments` VALUES ('18', '测试1', '舞蹈队22/test/测试1/15732674924022.mp4', '20', '30');

-- ----------------------------
-- Table structure for associations
-- ----------------------------
DROP TABLE IF EXISTS `associations`;
CREATE TABLE `associations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ass_name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '社团名称',
  `english_name` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '社团英文名称',
  `number_people` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '社团人数',
  `corporate_slogan` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '社团口号',
  `introduce` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '社团介绍',
  `images` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '说明图',
  `learning_objectives` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '学习目标',
  `department_id` int(10) unsigned NOT NULL COMMENT '所属系id',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of associations
-- ----------------------------
INSERT INTO `associations` VALUES ('1', '舞蹈队223', 'dance team2', '22', '大萨达', 'dsadsadsadsadsa', 'association/head_image/15755940895960.jpg', '打大萨达撒多撒大', '1', null, '2019-12-09 20:53:13');
INSERT INTO `associations` VALUES ('2', '礼仪社', 'sing', '23', '大萨达', '跳到自己', '', '从入门到精通', '1', null, '2019-11-26 15:44:54');
INSERT INTO `associations` VALUES ('4', '琴棋书画社', 'bookshe', '0', '只要读不死，就往死里读', '这是一个只读书的社团', 'association/head_image/15755940895960.jpg', '一天精通琴棋书画', '1', '2019-12-06 10:15:20', '2019-12-06 10:15:20');

-- ----------------------------
-- Table structure for attendances
-- ----------------------------
DROP TABLE IF EXISTS `attendances`;
CREATE TABLE `attendances` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `time` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '考勤日期',
  `assoc_id` int(10) unsigned NOT NULL COMMENT '所属社团id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of attendances
-- ----------------------------
INSERT INTO `attendances` VALUES ('1', '2019-1-1', '1');
INSERT INTO `attendances` VALUES ('2', '2019-2-2', '1');
INSERT INTO `attendances` VALUES ('3', '2019-1-1', '1');
INSERT INTO `attendances` VALUES ('4', '2019-1-7', '2');
INSERT INTO `attendances` VALUES ('5', '2019-1-7', '3');

-- ----------------------------
-- Table structure for chapters
-- ----------------------------
DROP TABLE IF EXISTS `chapters`;
CREATE TABLE `chapters` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cha_name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '章节名称',
  `course_id` int(11) NOT NULL COMMENT '所属课程id',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of chapters
-- ----------------------------
INSERT INTO `chapters` VALUES ('2', '拉丁舞的起源', '1', '2019-11-02 10:03:06', '2019-11-02 10:03:06');
INSERT INTO `chapters` VALUES ('3', '拉丁丁舞的起源', '1', null, null);

-- ----------------------------
-- Table structure for class
-- ----------------------------
DROP TABLE IF EXISTS `class`;
CREATE TABLE `class` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cls_name` varchar(20) NOT NULL DEFAULT '' COMMENT '班级名字',
  `number` varchar(10) NOT NULL DEFAULT '' COMMENT '班级人数',
  `major_id` int(10) unsigned NOT NULL COMMENT '所属专业id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='班级表';

-- ----------------------------
-- Records of class
-- ----------------------------
INSERT INTO `class` VALUES ('1', '163计网522', '56', '1');
INSERT INTO `class` VALUES ('2', '163计网512', '56', '1');
INSERT INTO `class` VALUES ('3', '163环艺502', '56', '2');

-- ----------------------------
-- Table structure for courses
-- ----------------------------
DROP TABLE IF EXISTS `courses`;
CREATE TABLE `courses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cou_name` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '课程名称',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `association_id` int(10) unsigned NOT NULL COMMENT '所属社团id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of courses
-- ----------------------------
INSERT INTO `courses` VALUES ('2', '拉丁舞', '2019-11-02 09:54:35', '2019-11-02 09:54:35', '1');
INSERT INTO `courses` VALUES ('6', '拉丁舞2', '2019-11-07 02:38:26', '2019-11-07 02:38:26', '2');
INSERT INTO `courses` VALUES ('7', '拉丁舞3', '2019-11-07 12:20:08', '2019-11-07 12:20:08', '1');

-- ----------------------------
-- Table structure for departments
-- ----------------------------
DROP TABLE IF EXISTS `departments`;
CREATE TABLE `departments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `department_name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '系名称',
  `type` tinyint(4) NOT NULL COMMENT '类型 1=>系 2=>组织',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of departments
-- ----------------------------
INSERT INTO `departments` VALUES ('1', '计算机应用系', '1', '2019-12-10 20:03:15', '2019-12-10 20:03:17');
INSERT INTO `departments` VALUES ('2', '电气应用系', '1', '2019-12-10 20:03:28', '2019-12-10 20:03:30');
INSERT INTO `departments` VALUES ('3', '学生社团联合会', '2', '2019-12-10 20:06:32', '2019-12-10 20:06:33');

-- ----------------------------
-- Table structure for majors
-- ----------------------------
DROP TABLE IF EXISTS `majors`;
CREATE TABLE `majors` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `major_name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '社团名称',
  `department_id` int(11) NOT NULL COMMENT '所属系id',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of majors
-- ----------------------------
INSERT INTO `majors` VALUES ('1', '计算机网络应用', '1', '2019-12-04 10:55:57', '2019-12-04 10:55:59');
INSERT INTO `majors` VALUES ('2', '环境艺术设计', '1', '2019-12-10 10:01:32', '2019-12-10 10:01:33');

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES ('1', '2019_10_17_070636_edit_student_table_name', '1');
INSERT INTO `migrations` VALUES ('2', '2019_10_17_071146_edit_student_table_name', '2');
INSERT INTO `migrations` VALUES ('7', '2019_10_21_003113_add_class_id_to_students_table', '3');
INSERT INTO `migrations` VALUES ('8', '2019_10_21_011509_edit_password_form_students_table', '4');
INSERT INTO `migrations` VALUES ('9', '2019_10_21_130205_edit_identity_card_length', '5');
INSERT INTO `migrations` VALUES ('10', '2019_10_23_083522_edit_password_length_form_admin_table', '6');
INSERT INTO `migrations` VALUES ('11', '2019_10_23_095902_create_teacher_table', '7');
INSERT INTO `migrations` VALUES ('13', '2019_11_02_073535_rename_class_table_name', '8');
INSERT INTO `migrations` VALUES ('14', '2019_11_02_073853_create_associations_table', '9');
INSERT INTO `migrations` VALUES ('15', '2019_11_02_075617_create_courses_table', '10');
INSERT INTO `migrations` VALUES ('16', '2019_11_02_075904_create_chapters_table', '11');
INSERT INTO `migrations` VALUES ('17', '2019_11_02_080158_create_sections_table', '12');
INSERT INTO `migrations` VALUES ('18', '2019_11_02_080507_create_assessments_table', '13');
INSERT INTO `migrations` VALUES ('19', '2019_11_02_081239_create_topics_table', '14');
INSERT INTO `migrations` VALUES ('20', '2019_11_02_082641_rename_classes_table', '15');
INSERT INTO `migrations` VALUES ('21', '2019_11_03_011020_delete_course_id_and_chapter_id_field_form_assessments_table', '16');
INSERT INTO `migrations` VALUES ('22', '2019_11_03_011326_add_assoc_id_to_assessments_table', '17');
INSERT INTO `migrations` VALUES ('23', '2019_11_03_025022_create_assessment_topic_table', '18');
INSERT INTO `migrations` VALUES ('24', '2019_11_04_013701_create_activity_table', '19');
INSERT INTO `migrations` VALUES ('25', '2019_11_04_022844_delete_assoc_id_from_assessment_table', '20');
INSERT INTO `migrations` VALUES ('26', '2019_11_05_072159_create_student_association_table', '21');
INSERT INTO `migrations` VALUES ('27', '2019_11_06_005836_create_attendance_table', '22');
INSERT INTO `migrations` VALUES ('30', '2019_11_06_012307_create_student_attendance_table', '23');
INSERT INTO `migrations` VALUES ('31', '2019_11_06_012546_add_association_id_field_to_attendance_table', '23');
INSERT INTO `migrations` VALUES ('32', '2019_11_07_114600_add_association_id_field_to_courses_table', '24');
INSERT INTO `migrations` VALUES ('33', '2019_11_27_070344_add_head_image_filed_to_student_table', '25');
INSERT INTO `migrations` VALUES ('34', '2019_12_03_103545_create_student_score_table', '26');
INSERT INTO `migrations` VALUES ('35', '2019_12_03_103938_update_student_score_association_id_form_table', '27');
INSERT INTO `migrations` VALUES ('36', '2019_12_03_141729_add_field_experience_to_student_table', '28');
INSERT INTO `migrations` VALUES ('37', '2019_12_04_015041_add_sex_field_to_student_table', '29');
INSERT INTO `migrations` VALUES ('38', '2019_12_04_025432_create_majors_table', '30');
INSERT INTO `migrations` VALUES ('39', '2019_12_04_025729_add_major_id_field_to_class_table', '31');
INSERT INTO `migrations` VALUES ('40', '2019_12_05_073958_add_experience_field_to_assessments_table', '32');
INSERT INTO `migrations` VALUES ('41', '2019_12_05_104700_add_timestamp_field_to_activity_table', '33');
INSERT INTO `migrations` VALUES ('42', '2019_12_05_222328_create_teacher_association_table', '34');
INSERT INTO `migrations` VALUES ('43', '2019_12_06_100602_add_type_and_status_field_to_teacher_association_table', '35');
INSERT INTO `migrations` VALUES ('44', '2019_12_06_140959_add_password_field_to_teachers_table', '36');
INSERT INTO `migrations` VALUES ('45', '2019_12_06_223718_add_head_image_to_teachers_table', '37');
INSERT INTO `migrations` VALUES ('46', '2019_12_09_150807_add_timestamps_field_to_topics_table', '38');
INSERT INTO `migrations` VALUES ('48', '2019_12_10_200122_create_department_table', '39');

-- ----------------------------
-- Table structure for sections
-- ----------------------------
DROP TABLE IF EXISTS `sections`;
CREATE TABLE `sections` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sec_name` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '小节名称',
  `chapter_id` int(11) NOT NULL COMMENT '所属章节名称',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of sections
-- ----------------------------
INSERT INTO `sections` VALUES ('2', '拉丁舞的创始人', '1', '2019-11-02 10:06:32', '2019-11-02 10:06:32');
INSERT INTO `sections` VALUES ('3', '拉丁舞的始创人', '1', null, null);
INSERT INTO `sections` VALUES ('4', '大萨达撒', '1', '2019-11-07 02:45:59', '2019-11-07 02:45:59');

-- ----------------------------
-- Table structure for student_association
-- ----------------------------
DROP TABLE IF EXISTS `student_association`;
CREATE TABLE `student_association` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` int(10) unsigned NOT NULL COMMENT '学生id',
  `association_id` int(10) unsigned NOT NULL COMMENT '社团id',
  `status` tinyint(3) unsigned NOT NULL COMMENT '状态 0=>等待验证 1=>已通过 2=>未通过',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of student_association
-- ----------------------------
INSERT INTO `student_association` VALUES ('3', '7', '1', '0', '2019-11-05 08:45:00', '2019-11-05 08:45:00');
INSERT INTO `student_association` VALUES ('4', '8', '1', '1', '2019-11-05 08:49:22', '2019-11-05 11:45:37');
INSERT INTO `student_association` VALUES ('7', '9', '1', '1', '2019-12-04 14:50:11', '2019-12-05 14:50:14');
INSERT INTO `student_association` VALUES ('8', '10', '1', '1', '2019-12-03 14:50:15', '2019-12-05 14:50:17');
INSERT INTO `student_association` VALUES ('9', '6', '1', '1', '2019-12-03 14:50:18', '2019-12-05 14:50:19');
INSERT INTO `student_association` VALUES ('10', '6', '2', '1', '2019-11-06 14:50:21', '2019-12-05 14:50:22');
INSERT INTO `student_association` VALUES ('11', '6', '1', '0', '2019-12-08 15:25:45', '2019-12-08 15:25:45');
INSERT INTO `student_association` VALUES ('12', '6', '1', '0', '2019-12-08 15:26:34', '2019-12-08 15:26:34');
INSERT INTO `student_association` VALUES ('13', '6', '1', '0', '2019-12-09 09:36:19', '2019-12-09 09:36:19');

-- ----------------------------
-- Table structure for student_attendance
-- ----------------------------
DROP TABLE IF EXISTS `student_attendance`;
CREATE TABLE `student_attendance` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` int(10) unsigned NOT NULL COMMENT '学生id',
  `attendance_id` int(10) unsigned NOT NULL COMMENT '考勤id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of student_attendance
-- ----------------------------
INSERT INTO `student_attendance` VALUES ('1', '6', '1');
INSERT INTO `student_attendance` VALUES ('2', '7', '1');
INSERT INTO `student_attendance` VALUES ('7', '9', '2');
INSERT INTO `student_attendance` VALUES ('8', '6', '5');
INSERT INTO `student_attendance` VALUES ('9', '7', '5');
INSERT INTO `student_attendance` VALUES ('10', '8', '5');

-- ----------------------------
-- Table structure for student_score
-- ----------------------------
DROP TABLE IF EXISTS `student_score`;
CREATE TABLE `student_score` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` int(10) unsigned NOT NULL COMMENT '学生id',
  `assessment_id` int(10) unsigned NOT NULL COMMENT '社团id',
  `score` int(10) unsigned NOT NULL COMMENT '成绩',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of student_score
-- ----------------------------
INSERT INTO `student_score` VALUES ('1', '6', '18', '70', '2019-12-03 11:07:43', '2019-12-03 11:07:43');
INSERT INTO `student_score` VALUES ('2', '6', '18', '90', '2019-12-03 11:16:49', '2019-12-03 11:16:49');
INSERT INTO `student_score` VALUES ('14', '6', '18', '80', '2019-12-03 14:21:38', '2019-12-03 14:21:38');
INSERT INTO `student_score` VALUES ('15', '6', '18', '20', '2019-12-05 10:17:19', '2019-12-05 10:17:19');
INSERT INTO `student_score` VALUES ('16', '6', '18', '20', '2019-12-05 10:19:12', '2019-12-05 10:19:12');
INSERT INTO `student_score` VALUES ('17', '6', '18', '20', '2019-12-09 11:25:15', '2019-12-09 11:25:15');

-- ----------------------------
-- Table structure for students
-- ----------------------------
DROP TABLE IF EXISTS `students`;
CREATE TABLE `students` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stu_number` varchar(50) NOT NULL DEFAULT '' COMMENT '学号',
  `stu_name` varchar(35) NOT NULL DEFAULT '' COMMENT '学生姓名',
  `password` varchar(255) NOT NULL DEFAULT '' COMMENT '学生密码',
  `class_id` int(10) unsigned NOT NULL COMMENT '班级id',
  `identity_card` varchar(255) NOT NULL DEFAULT '' COMMENT '//身份证',
  `head_image` varchar(50) DEFAULT NULL COMMENT '头像',
  `experience` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '经验值',
  `sex` tinyint(3) unsigned NOT NULL COMMENT '性别',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='学生登陆表';

-- ----------------------------
-- Records of students
-- ----------------------------
INSERT INTO `students` VALUES ('6', '160952206', '世界总是小小小明', '$2y$10$9MOZJGzkqb1UpuDCBw53VucQcNg70K5Wqsy3n7.2Krv2j8MBu5Q0K', '1', '$2y$10$6BcW3/1xsHR7w5pO2.42cO504FNRfR5sw6H3RfqBCtvHCIp4jwUWC', 'user_headimg/15754620841097.png', '73', '2');
INSERT INTO `students` VALUES ('7', '160952207', '小王', '$10$Do2PCpWVg79D6W8sLinpu.i5QizBIPmkYmGpEexN.cOkq1rT4PX2m', '1', '', '', '2', '1');
INSERT INTO `students` VALUES ('8', '160952208', '小花', '$10$Do2PCpWVg79D6W8sLinpu.i5QizBIPmkYmGpEexN.cOkq1rT4PX2m', '1', '', '', '3', '2');
INSERT INTO `students` VALUES ('9', '160952209', '小小明', '$10$Do2PCpWVg79D6W8sLinpu.i5QizBIPmkYmGpEexN.cOkq1rT4PX2m', '1', '', '', '4', '1');
INSERT INTO `students` VALUES ('10', '160952210', '小小王', '$10$Do2PCpWVg79D6W8sLinpu.i5QizBIPmkYmGpEexN.cOkq1rT4PX2m', '1', '', '', '5', '1');
INSERT INTO `students` VALUES ('11', '160952211', '小小花', '$10$Do2PCpWVg79D6W8sLinpu.i5QizBIPmkYmGpEexN.cOkq1rT4PX2m', '1', '', '', '6', '2');

-- ----------------------------
-- Table structure for teacher_association
-- ----------------------------
DROP TABLE IF EXISTS `teacher_association`;
CREATE TABLE `teacher_association` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `teacher_id` int(10) unsigned NOT NULL COMMENT '老师id',
  `association_id` int(10) unsigned NOT NULL COMMENT '社团id',
  `status` tinyint(3) unsigned NOT NULL COMMENT '0=>等待验证 1=>已通过 2=>未通过',
  `is_admin` tinyint(3) unsigned NOT NULL COMMENT '1=>是管理员 0=>不是管理员',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of teacher_association
-- ----------------------------
INSERT INTO `teacher_association` VALUES ('2', '1', '4', '1', '1', '2019-12-06 10:15:20', '2019-12-06 10:15:20');
INSERT INTO `teacher_association` VALUES ('3', '2', '4', '1', '0', '2019-12-06 10:27:14', '2019-12-06 11:11:33');
INSERT INTO `teacher_association` VALUES ('4', '3', '4', '0', '0', '2019-12-06 10:54:37', '2019-12-06 10:54:40');

-- ----------------------------
-- Table structure for teachers
-- ----------------------------
DROP TABLE IF EXISTS `teachers`;
CREATE TABLE `teachers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `te_name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '老师姓名',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '密码',
  `head_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '头像',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of teachers
-- ----------------------------
INSERT INTO `teachers` VALUES ('1', '刘杨', '', '');
INSERT INTO `teachers` VALUES ('2', '绍余', '', '');
INSERT INTO `teachers` VALUES ('3', '兆明', '', '');
INSERT INTO `teachers` VALUES ('4', '松荣', '$2y$10$zG0trgU4.QlULduerB/Jn.6cvgv.i0Rtkko.hx3isWv3L.ipDCIq6', 'user/head_image/15756860067297.jpg');

-- ----------------------------
-- Table structure for topics
-- ----------------------------
DROP TABLE IF EXISTS `topics`;
CREATE TABLE `topics` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '1=>单选2=>多选',
  `top_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '题干',
  `options` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '选项',
  `correct` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '正确答案',
  `top_score` int(11) NOT NULL DEFAULT '0' COMMENT '分值',
  `course_id` int(10) unsigned NOT NULL COMMENT '所属课程id',
  `chapter_id` int(10) unsigned NOT NULL COMMENT '所属章节id',
  `association_id` int(10) unsigned NOT NULL COMMENT '所属社团id',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of topics
-- ----------------------------
INSERT INTO `topics` VALUES ('2', '1', '这题选什么', '{\"A\":\"选啊\",\"B\":\"选吧\",\"C\":\"选成\",\"D\":\"选的\"}', '[\"A\"]', '5', '1', '2', '1', '2019-12-09 15:24:28', '2019-12-09 15:24:30');
INSERT INTO `topics` VALUES ('3', '1', '这题选什么2', '{\"A\":\"选啊2\",\"B\":\"选吧2\",\"C\":\"选成2\",\"D\":\"选的2\"}', '[\"A\"]', '5', '1', '1', '1', '2019-12-09 15:24:32', '2019-12-09 15:24:48');
INSERT INTO `topics` VALUES ('4', '1', '这题选什么', '{\"A\":\"选啊2\",\"B\":\"选吧2\",\"C\":\"选成2\",\"D\":\"选的2\"}', '[\"A\"]', '5', '1', '2', '1', '2019-12-09 15:24:50', '2019-12-09 15:24:51');
INSERT INTO `topics` VALUES ('5', '1', '这题选什么', '{\"A\":\"选啊2\",\"B\":\"选吧2\",\"C\":\"选成2\",\"D\":\"选的2\"}', '[\"A\"]', '5', '1', '2', '1', '2019-12-09 15:26:46', '2019-12-09 15:26:48');
INSERT INTO `topics` VALUES ('6', '1', '这题选什么', '{\"A\":\"选啊2\",\"B\":\"选吧2\",\"C\":\"选成2\",\"D\":\"选的2\"}', '[\"A\"]', '5', '1', '2', '1', '2019-12-09 15:26:50', '2019-12-09 15:26:52');
INSERT INTO `topics` VALUES ('7', '1', '这题选什么', '{\"A\":\"选啊2\",\"B\":\"选吧2\",\"C\":\"选成2\",\"D\":\"选的2\"}', '[\"A\"]', '5', '1', '2', '1', '2019-12-09 15:26:53', '2019-12-09 15:26:55');
INSERT INTO `topics` VALUES ('8', '1', '这题选什么', '[\"东方闪电\",\"发大哥大哥\",\"房贷首付上东凤\"]', '[\"A\"]', '5', '2', '2', '1', '2019-12-09 15:22:54', '2019-12-09 15:22:54');
INSERT INTO `topics` VALUES ('10', '1', '这题选什么', '{\"A\":\"东方闪电\",\"B\":\"发大哥大哥\",\"C\":\"房贷首付上东凤\"}', '[\"A\"]', '5', '2', '2', '1', '2019-12-09 15:23:52', '2019-12-09 15:23:52');
