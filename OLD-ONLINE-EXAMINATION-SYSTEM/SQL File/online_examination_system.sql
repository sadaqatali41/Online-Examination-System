-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 29, 2018 at 06:52 AM
-- Server version: 5.5.8
-- PHP Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `online_examination_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `adminlogin`
--

CREATE TABLE IF NOT EXISTS `adminlogin` (
  `id` varchar(10) NOT NULL,
  `password` varchar(20) NOT NULL,
  `name` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `adminlogin`
--

INSERT INTO `adminlogin` (`id`, `password`, `name`) VALUES
('saadat40', 'Saadat1@', 'Saadat Karim'),
('sadaqat1', 'abcdef1!A', 'Sadaqat Ali'),
('shadab07', 'Shadab@4127', 'Shadab Alam');

-- --------------------------------------------------------

--
-- Table structure for table `alreadyloggedin`
--

CREATE TABLE IF NOT EXISTS `alreadyloggedin` (
  `fname` varchar(15) NOT NULL,
  `lname` varchar(15) NOT NULL,
  `course` varchar(15) NOT NULL,
  `center` varchar(15) NOT NULL,
  `stuid` varchar(10) NOT NULL,
  `phone` varchar(35) NOT NULL,
  `email` varchar(25) NOT NULL,
  `country` varchar(15) NOT NULL,
  `state` varchar(20) NOT NULL,
  `district` varchar(20) NOT NULL,
  `address` varchar(60) NOT NULL,
  `gender` varchar(9) NOT NULL,
  `dob` date NOT NULL,
  `datetime` datetime NOT NULL,
  PRIMARY KEY (`stuid`),
  UNIQUE KEY `phone` (`phone`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `alreadyloggedin`
--

INSERT INTO `alreadyloggedin` (`fname`, `lname`, `course`, `center`, `stuid`, `phone`, `email`, `country`, `state`, `district`, `address`, `gender`, `dob`, `datetime`) VALUES
('sadaqat', 'ali', 'B-Tech', 'Lucknow', '2411461289', '7893941364', 'sadaqatali890@gmail.com', 'India', 'UP', 'Mau', 'Pahar Pura Maunath Bhanjan 275101', 'Male', '1996-10-02', '2018-03-08 02:32:24'),
('savej', 'saifi', 'M-Tech', 'Delhi', '6247851018', '8143106248', 'savejsaifi125@gmail.com', 'India', 'UP', 'Meerut', 'Ahmad Nagar Meerut UP 250002', 'Male', '1997-05-01', '2018-03-26 21:17:57');

-- --------------------------------------------------------

--
-- Table structure for table `center`
--

CREATE TABLE IF NOT EXISTS `center` (
  `center_id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(15) NOT NULL,
  `center_code` int(11) NOT NULL,
  `value` varchar(15) NOT NULL,
  `address` varchar(255) NOT NULL,
  `datetime` datetime NOT NULL,
  PRIMARY KEY (`center_id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `value` (`value`),
  UNIQUE KEY `center_code` (`center_code`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `center`
--

INSERT INTO `center` (`center_id`, `name`, `center_code`, `value`, `address`, `datetime`) VALUES
(1, 'Lucknow', 456, 'Lucknow', 'Amiruddaula Islamia Degree College, Lal Bagh, Lucknow, U.P. – 226 001', '2018-01-09 22:49:15'),
(2, 'Delhi', 546, 'Delhi', 'Department of Urdu, Jamia Millia Islamia, Maulana Mohammad Ali Jauhar Marg, New Delhi 110025', '2018-01-09 22:50:02'),
(3, 'Patna', 234, 'Patna', 'College of Commerce, Opp Rajdendra Nagar Terminal, Kankarbagh, Patna, Bihar – 800 020.', '2018-01-10 22:50:19'),
(4, 'Hyderabad', 321, 'Hyderabad', 'Govt. Girls High School, Near Police Station, Golconda, Hyderabad, Telangan – 500 008.', '2018-01-10 22:50:43'),
(5, 'Bhopal', 897, 'Bhopal', 'M.D.Junior College, #25, Jinsi Road, Jahangirabad, Bhopal, M.P. – 462 008.', '2018-01-24 22:50:56'),
(6, 'Darbhanga', 678, 'Darbhanga', 'Al-Hira Public School, Raham Khan, Darbhanga, Bihar – 846 004', '2018-01-24 22:51:10'),
(7, 'Varansi', 786, 'Varansi', 'Al-Jamia Tus Salafia, Reori Talab,Varanasi – 221 010. UP', '2018-03-22 22:37:03');

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE IF NOT EXISTS `course` (
  `c_c_id` int(11) NOT NULL,
  `course_id` int(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(10) CHARACTER SET utf8 NOT NULL,
  `value` varchar(10) CHARACTER SET utf8 NOT NULL,
  `datetime` datetime NOT NULL,
  PRIMARY KEY (`course_id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `value` (`value`),
  KEY `c_c_id` (`c_c_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`c_c_id`, `course_id`, `name`, `value`, `datetime`) VALUES
(1, 1, 'B-Tech', 'B-Tech', '2018-03-08 02:01:35'),
(2, 2, 'MCA', 'MCA', '2018-03-08 02:03:15'),
(2, 3, 'M-Tech', 'M-Tech', '2018-03-08 02:04:05');

-- --------------------------------------------------------

--
-- Table structure for table `course_category`
--

CREATE TABLE IF NOT EXISTS `course_category` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `cname` varchar(20) NOT NULL,
  PRIMARY KEY (`category_id`),
  UNIQUE KEY `cname` (`cname`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `course_category`
--

INSERT INTO `course_category` (`category_id`, `cname`) VALUES
(2, 'Post Graduation'),
(1, 'Under Graduation');

-- --------------------------------------------------------

--
-- Table structure for table `exam`
--

CREATE TABLE IF NOT EXISTS `exam` (
  `stuid` varchar(15) NOT NULL,
  `Ques_id` int(11) NOT NULL,
  `chooseoption` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `exam`
--

INSERT INTO `exam` (`stuid`, `Ques_id`, `chooseoption`) VALUES
('6247851018', 31, 'Algorithm'),
('6247851018', 32, 'Abstract Data Type');

-- --------------------------------------------------------

--
-- Table structure for table `examschedule`
--

CREATE TABLE IF NOT EXISTS `examschedule` (
  `course_id` int(11) NOT NULL,
  `course_name` varchar(15) NOT NULL,
  `regis_last_date` date NOT NULL,
  `exam_date` date NOT NULL,
  `exam_time` varchar(20) NOT NULL,
  `exam_duration` varchar(10) NOT NULL,
  UNIQUE KEY `course_id` (`course_id`),
  UNIQUE KEY `course_name` (`course_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `examschedule`
--

INSERT INTO `examschedule` (`course_id`, `course_name`, `regis_last_date`, `exam_date`, `exam_time`, `exam_duration`) VALUES
(1, 'B-Tech', '2018-03-24', '2018-04-25', '11:00AM To 02:00PM', '3:00 Hours'),
(2, 'MCA', '2018-03-24', '2018-04-28', '11:00AM To 02:00PM', '3:00 Hours'),
(3, 'M-Tech', '2018-03-24', '2018-04-30', '11:00AM To 02:00PM', '3:00 Hours');

-- --------------------------------------------------------

--
-- Table structure for table `graduation`
--

CREATE TABLE IF NOT EXISTS `graduation` (
  `student_id` varchar(15) NOT NULL,
  `fname` varchar(15) NOT NULL,
  `lname` varchar(15) NOT NULL,
  `course_name` varchar(30) NOT NULL,
  `enroll_no` varchar(12) NOT NULL,
  `branch_name` varchar(20) NOT NULL,
  `institute_name` varchar(70) NOT NULL,
  `yop` int(4) NOT NULL,
  `aggregate_percent` int(2) NOT NULL,
  `datetime` datetime NOT NULL,
  UNIQUE KEY `enroll_no` (`enroll_no`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `graduation`
--

INSERT INTO `graduation` (`student_id`, `fname`, `lname`, `course_name`, `enroll_no`, `branch_name`, `institute_name`, `yop`, `aggregate_percent`, `datetime`) VALUES
('6247851018', 'savej', 'saifi', 'B-Tech', '1407010442', 'CS', 'MANUU, Hyderabad,500032', 2017, 74, '2018-03-26 21:52:11'),
('2123087033', 'enayat', 'ali', 'b-tech', '1407010447', 'cs', 'manuu, hyderabad, telangana, 500032', 2018, 70, '2018-04-22 23:46:54'),
('6295926021', 'saif', 'ali', 'BCA', '3456789098', 'CS', 'Allahabad University, UP 240560', 2015, 67, '2018-03-25 00:37:22');

-- --------------------------------------------------------

--
-- Table structure for table `highschool`
--

CREATE TABLE IF NOT EXISTS `highschool` (
  `student_id` varchar(15) NOT NULL,
  `fname` varchar(15) NOT NULL,
  `lname` varchar(15) NOT NULL,
  `roll_no` int(7) NOT NULL,
  `college_name` varchar(70) NOT NULL,
  `borad_name` varchar(20) NOT NULL,
  `yop` int(4) NOT NULL,
  `percent` int(2) NOT NULL,
  `datetime` datetime NOT NULL,
  UNIQUE KEY `roll_no` (`roll_no`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `highschool`
--

INSERT INTO `highschool` (`student_id`, `fname`, `lname`, `roll_no`, `college_name`, `borad_name`, `yop`, `percent`, `datetime`) VALUES
('2123087033', 'enayat', 'ali', 34567, 'lm high school, annad poor, 846001', 'bihar', 2007, 70, '2018-04-22 23:46:54'),
('6247851018', 'savej', 'saifi', 536866, 'NBIC Sheikhpur meerut\r\n250002', 'UP Board', 2012, 73, '2018-03-26 21:52:11'),
('6295926021', 'saif', 'ali', 5679896, 'Darul Uloom Boys High School Maunath Bhanjan UP 275101', 'UP Board', 2010, 78, '2018-03-25 00:37:21'),
('2411461289', 'sadaqat', 'ali', 7889541, 'Darul Uloom Boys High School Maunath Bhanjan UP 275101', 'UP Board', 2010, 82, '2018-03-24 23:54:13');

-- --------------------------------------------------------

--
-- Table structure for table `intermediate`
--

CREATE TABLE IF NOT EXISTS `intermediate` (
  `student_id` varchar(15) NOT NULL,
  `fname` varchar(15) NOT NULL,
  `lname` varchar(15) NOT NULL,
  `roll_no` int(7) NOT NULL,
  `college_name` varchar(70) NOT NULL,
  `borad_name` varchar(20) NOT NULL,
  `yop` int(4) NOT NULL,
  `percent` int(2) NOT NULL,
  `datetime` datetime NOT NULL,
  UNIQUE KEY `roll_no` (`roll_no`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `intermediate`
--

INSERT INTO `intermediate` (`student_id`, `fname`, `lname`, `roll_no`, `college_name`, `borad_name`, `yop`, `percent`, `datetime`) VALUES
('2123087033', 'enayat', 'ali', 34879, 'millat college darbhanga, bihar, 846005', 'bihar', 2009, 75, '2018-04-22 23:46:54'),
('6295926021', 'saif', 'ali', 5467861, 'Musim Inter College Maunath Bhanjan UP 275101', 'UP Board', 2012, 79, '2018-03-25 00:37:21'),
('2411461289', 'sadaqat', 'ali', 6576890, 'Muslim Inter College Maunath Bhanjan UP 275101', 'UP Board', 2012, 68, '2018-03-24 23:54:13'),
('6247851018', 'savej', 'saifi', 6800032, 'NBIC Sheikhpur meerut\r\n250002', 'UP Board', 2014, 61, '2018-03-26 21:52:11');

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE IF NOT EXISTS `questions` (
  `question_id` int(5) NOT NULL AUTO_INCREMENT,
  `course_id` int(5) NOT NULL,
  `name` varchar(255) NOT NULL,
  `optionA` varchar(30) NOT NULL,
  `optionB` varchar(30) NOT NULL,
  `optionC` varchar(30) NOT NULL,
  `optionD` varchar(30) NOT NULL,
  `correct_option` varchar(30) NOT NULL,
  `datetime` datetime NOT NULL,
  PRIMARY KEY (`question_id`),
  KEY `course_id` (`course_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=33 ;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`question_id`, `course_id`, `name`, `optionA`, `optionB`, `optionC`, `optionD`, `correct_option`, `datetime`) VALUES
(1, 1, 'What is H<sub>2</sub>SO<sub>4</sub> ?', 'Base', 'Acid', 'Not a Acid', 'Not a Base', 'Acid', '2018-03-08 02:50:45'),
(2, 1, 'Na<sub>2</sub>S Stands For?', 'Sodium Sulphaide', 'Sodium Sulphaite', 'Sodium Sulphate', 'Sodium Sulphonate', 'Sodium Sulphaide', '2018-03-11 22:08:10'),
(3, 1, 'If x+x/(x-2)-x/(x-2)-2=0\r\nthen x=?', '2', 'No Solution', 'Infinite Solution', 'None of These', 'No Solution', '2018-03-11 22:11:10'),
(4, 1, 'if a-b=10 and a+b=4 then a<sup>2</sup>-b<sup>2</sup>=?', '40', '6', '10', 'None', '40', '2018-03-11 22:18:49'),
(5, 1, 'Na<sub>2</sub>S + S ----->X + H<sub>2</sub>O. Then What is X?', 'Hypo(Na2S2O3)', 'Mohr Salt', 'POP', 'Bliching Powder', 'Hypo(Na2S2O3)', '2018-03-11 22:23:41'),
(6, 1, 'What is Ca(ClO)<sub>2</sub> ?', 'Suhaga', 'Nausadar', 'Bleaching Powder', 'Mohr Salt', 'Bleaching Powder', '2018-03-11 22:34:30'),
(7, 1, 'What is Ca(OH)<sub>2</sub> ?', 'Acid', 'Salt', 'Base', 'None', 'Base', '2018-03-11 22:37:07'),
(8, 1, 'Which instrument is used to determine the intensity of colours ?', 'Cathetometer', 'Chronometer', 'Colorimeter', 'Commutator', 'Colorimeter', '2018-03-11 22:52:27'),
(9, 1, 'K. Macmillan invented ?', 'Bicycle', 'Barometer', 'Calculating Machine', 'Centigrade Scale', 'Bicycle', '2018-03-11 22:53:44'),
(10, 1, 'Alfred Nobel invented ?', 'X ray', 'Diesel Engine', 'Dynamite', 'Dynamo', 'Dynamite', '2018-03-11 22:54:58'),
(11, 1, 'Which of the following is a subset of  {b, c, d} ?', '{ }', '{a}', '{ 1,2,3}', '{ a,b,c}', '{ }', '2018-03-11 22:59:06'),
(12, 1, '3x - 4(x + 6) = ?', 'x+6', '-x-24', '7x+6', '-7x - 24', '-x-24', '2018-03-11 23:00:41'),
(13, 1, 'Which of the following is the Highest Common Factor of 18, 24 and 36 ?', '6', '18', '36', '72', '6', '2018-03-11 23:01:59'),
(14, 1, 'Given that  a  and  b  are integers, which of the following is not necessarily an integer ?', '2a - 5b', 'a<sup>7</sup>', 'b<sup>a</sup>', 'ab', 'b<sup>a</sup>', '2018-03-11 23:04:11'),
(15, 1, 'Items bought by a trader for $80 are sold for $100. The profit expressed as a percentage of cost price is ?', '2.5%', '20%', '25%', '50%', '25%', '2018-03-11 23:05:25'),
(16, 2, 'A collection of data designed to be used by different people is called a/an.', 'Organization', 'Database', 'Relationship', 'Schema', 'Database', '2018-03-16 00:19:26'),
(17, 2, 'Which of the following is the oldest database model?', 'Relational', 'Deductive', 'Physical', 'Network', 'Network', '2018-03-16 00:20:51'),
(18, 2, 'Which of the following is an attribute that can uniquely identify a row in a table?', 'Secondry Key', 'Foreign Key', 'Candidate Key', 'Alternate Key', 'Candidate Key', '2018-03-16 00:23:08'),
(19, 2, 'Every C program consists  of ______ function(s).', 'Only one', 'Only two', 'One or Two', 'One or Many', 'One or Many', '2018-03-16 00:26:30'),
(20, 2, 'What is the only function all C programs must contain?', 'Start()', 'System()', 'Main()', 'Program()', 'Main()', '2018-03-16 00:28:12'),
(21, 2, 'Which of the following is not a correct variable type in C?', 'Float', 'Real', 'Int', 'Double', 'Real', '2018-03-16 00:30:15'),
(22, 2, 'Which of the following is a not a keyword in C language?', 'void', 'sizeof', 'getchar', 'volatile', 'getchar', '2018-03-16 00:32:45'),
(23, 2, 'HTTP (Hyper Text Transfer Protocol) has similarities to both of the following protocols.', 'FTP; SMTP ', 'FTP; SNMP', 'FTP; MTV', 'FTP; URL', 'FTP; SMTP ', '2018-03-16 00:35:40'),
(24, 2, 'Which category of AAL of ATM is intended to support connection-oriented data services?', 'AAL1', 'AAL2', 'AAL3', 'AAL4', 'AAL4', '2018-03-16 00:37:04'),
(25, 2, 'Which of the following application protocol is a framework for managing devices in an internet using the TCP/IP protocol suite.', 'SMTP', 'SNMP', 'HTTP', 'FTP', 'SNMP', '2018-03-16 00:39:42'),
(26, 2, 'A distributed system is a collection of processors that do not share_______.', 'CPU', 'Memory', 'I/O Devices', 'Network', 'Memory', '2018-03-16 00:41:50'),
(27, 2, 'How many solutions are there to the equation x1 + x2 +x3 + x4 = 17', 'C(20,17)', 'C(20,3)', '1140', 'All', 'All', '2018-03-16 00:44:36'),
(28, 2, 'Which access specifier is used for describing Applet Class?', 'Private', 'Public', 'Protected', 'A or B', 'Public', '2018-03-16 00:48:45'),
(29, 2, 'AWT stands for?', 'Advanced windowing tool kit', 'Abstract Windowing Term Kit', 'Abstract Windowing Tool Kit', 'Applet Window Tool Kit', 'Abstract Windowing Tool Kit', '2018-03-16 00:51:40'),
(30, 2, 'The final form of testing COTS software is _________ testing.', 'Unit', 'Integration', 'Alpha', 'Beta', 'Beta', '2018-03-16 00:54:02'),
(31, 3, 'A mathematical-model with a collection of operations defined on that model is called ?', 'Data Structure', 'Abstract Data Type', 'Primitive Data Type', 'Algorithm', 'Abstract Data Type', '2018-03-22 22:04:26'),
(32, 3, 'Representation of data structure in memory is known as:', 'Recursive', 'Abstract Data Type', 'Storage Structure', 'File Structure', 'Abstract Data Type', '2018-03-22 22:19:46');

-- --------------------------------------------------------

--
-- Table structure for table `results`
--

CREATE TABLE IF NOT EXISTS `results` (
  `student_id` varchar(15) NOT NULL,
  `fname` varchar(15) NOT NULL,
  `lname` varchar(15) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(25) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `course` varchar(10) NOT NULL,
  `dob` date NOT NULL,
  `center` varchar(15) NOT NULL,
  `no_of_questions` int(11) NOT NULL,
  `marks` int(11) NOT NULL,
  `percentage` float NOT NULL,
  `status` varchar(15) NOT NULL,
  PRIMARY KEY (`student_id`),
  UNIQUE KEY `phone` (`phone`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `results`
--

INSERT INTO `results` (`student_id`, `fname`, `lname`, `phone`, `email`, `gender`, `course`, `dob`, `center`, `no_of_questions`, `marks`, `percentage`, `status`) VALUES
('2411461289', 'sadaqat', 'ali', '7893941364', 'sadaqatali890@gmail.com', 'Male', 'B-Tech', '1996-02-10', 'Varansi', 15, 15, 100, 'Selected'),
('6247851018', 'savej', 'saifi', '8143106248', 'savejsaifi125@gmail.com', 'Male', 'M-Tech', '1997-05-01', 'Delhi', 2, 2, 100, 'Selected');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE IF NOT EXISTS `students` (
  `reg_id` varchar(20) NOT NULL,
  `fname` varchar(15) NOT NULL,
  `lname` varchar(15) NOT NULL,
  `course` varchar(15) NOT NULL,
  `center` varchar(15) NOT NULL,
  `stuid` varchar(15) NOT NULL,
  `phone` varchar(35) NOT NULL,
  `password` varchar(20) NOT NULL,
  `email` varchar(30) NOT NULL,
  `country` varchar(15) NOT NULL,
  `state` varchar(20) NOT NULL,
  `district` varchar(20) NOT NULL,
  `address` varchar(60) NOT NULL,
  `gender` varchar(9) NOT NULL,
  `dob` date NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `datetime` datetime NOT NULL,
  PRIMARY KEY (`stuid`),
  UNIQUE KEY `phone` (`phone`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`reg_id`, `fname`, `lname`, `course`, `center`, `stuid`, `phone`, `password`, `email`, `country`, `state`, `district`, `address`, `gender`, `dob`, `status`, `datetime`) VALUES
('0722411272', 'saadat', 'karim', 'B-Tech', 'Darbhanga', '1127252250', '7352107224', 'Saadat1!', 'saadatk740@gmail.com', 'India', 'Bihar', 'Araria', 'Azad Nagar Araria 854311', 'Male', '1996-12-09', 1, '2018-03-24 22:32:37'),
('4733221230', 'enayat', 'ali', 'M-Tech', 'Darbhanga', '2123087033', '8919047332', 'Anayatali2@', 'enayetali265@gmail.com', 'india', 'bihar', 'darbhanga', 'chandan patti bihar 846002', 'Male', '1993-01-15', 1, '2018-04-22 23:35:32'),
('6224021649', 'ali', 'khan', 'MCA', 'Delhi', '2164934819', '8960962240', 'Sadaqat1!', 'sadaqatali0441@gmail.com', 'india', 'up', 'mau', 'pahar pura maunath bhanjan 275101', 'Male', '1997-02-10', 1, '2018-04-12 10:49:08'),
('4136424114', 'sadaqat', 'ali', 'B-Tech', 'Varansi', '2411461289', '7893941364', 'Sadaqat123!', 'sadaqatali890@gmail.com', 'India', 'Uttar Pradesh', 'Mau', 'Pahar Pura Maunath Bhanjan 275101', 'Male', '1996-02-10', 1, '2018-03-24 15:10:59'),
('9281326368', 'zoha', 'salman', 'M-Tech', 'Patna', '2636894375', '8886992813', 'Kashkudi12@', 'zoha.cool12345@gmail.com', 'india', 'bihar', 'patna', 'phulwari sharif patna 801505', 'Male', '1995-09-23', 1, '2018-04-11 23:16:07'),
('1426551765', 'md', 'shahnawaz', 'MCA', 'Patna', '5176521964', '7691914265', 'Shahnawaz1@', 'shahnawazjaan321@gmail.com', 'india', 'bihar', 'aurangabad', 'kaasi poora bihar 824101', 'Male', '1995-03-10', 0, '2018-04-24 02:15:04'),
('0624862478', 'savej', 'saifi', 'M-Tech', 'Delhi', '6247851018', '8143106248', 'Savej1@', 'savejsaifi125@gmail.com', 'India', 'UP', 'Meerut', 'Ahmad Nagar Meerut UP 250002', 'Male', '1997-05-01', 1, '2018-03-26 21:17:57');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `course`
--
ALTER TABLE `course`
  ADD CONSTRAINT `course_ibfk_1` FOREIGN KEY (`c_c_id`) REFERENCES `course_category` (`category_id`);

--
-- Constraints for table `examschedule`
--
ALTER TABLE `examschedule`
  ADD CONSTRAINT `examschedule_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `course` (`course_id`);

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `course` (`course_id`);
