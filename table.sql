--
-- Table structure for table `datasource`
--

CREATE TABLE IF NOT EXISTS `datasource` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `description` varchar(40) NOT NULL,
  `active` int(11) NOT NULL,
  `created` date NOT NULL,
  `updated` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `datasource`
--

INSERT INTO `datasource` (`id`, `name`, `description`, `active`, `created`, `updated`) VALUES
(1, 'ABC', 'some more text', 1, '2016-03-02', '2016-03-03'),
(2, 'PQR', 'some more text here too', 0, '2016-02-02', '2016-02-06'),
(3, 'MNP', 'test data 3', 1, '2016-01-01', '2016-01-06'),
(4, 'EFG', 'test data 4', 1, '2016-03-01', '2016-03-06');

-- --------------------------------------------------------