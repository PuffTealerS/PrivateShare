-- phpMyAdmin SQL Dump
-- version 4.0.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 15, 2013 at 09:06 PM
-- Server version: 5.5.33
-- PHP Version: 5.5.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `privateshare`
--

-- --------------------------------------------------------

--
-- Table structure for table `changelog`
--

CREATE TABLE `changelog` (
  `version` text NOT NULL,
  `datelog` datetime NOT NULL,
  `commentaire` text NOT NULL,
  PRIMARY KEY (`datelog`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE `config` (
  `lastid` int(10) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `list`
--

CREATE TABLE `list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fichier` text NOT NULL,
  `name` text NOT NULL,
  `folder` int(1) NOT NULL,
  `size` varchar(255) NOT NULL,
  `cat` varchar(255) NOT NULL,
  `logtime` datetime NOT NULL,
  `datetime` datetime DEFAULT NULL,
  `baseurl` int(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL,
  `username` varchar(30) NOT NULL,
  `action` text NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `ip` varchar(39) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`id`, `uid`, `username`, `action`, `date`, `time`, `ip`) VALUES
(1, 2, 'PuffTealerS', 'Log out', '2013-12-15', '20:57:59', '::1'),
(2, 1, 'admin', 'Log in', '2013-12-15', '20:58:41', '::1'),
(3, 1, 'admin', 'Log out', '2013-12-15', '20:59:57', '::1'),
(4, 2, 'admin', 'Log in', '2013-12-15', '21:00:18', '::1');

-- --------------------------------------------------------

--
-- Table structure for table `request`
--

CREATE TABLE `request` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL,
  `tid` int(10) NOT NULL,
  `message` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `request_title`
--

CREATE TABLE `request_title` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL,
  `tid` int(10) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `status` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rank` int(1) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `avatar` varchar(255) NOT NULL DEFAULT '/img/avatar/defaut.jpg',
  `code_activation` varchar(255) NOT NULL,
  `actived` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `rank`, `username`, `password`, `email`, `avatar`, `code_activation`, `actived`) VALUES
(2, 1, 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 'admin@PrivateShare.fr', '/img/avatar/defaut.jpg', '', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
