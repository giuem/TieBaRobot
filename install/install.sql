DROP TABLE IF EXISTS `user`;
DROP TABLE IF EXISTS `robot`;
DROP TABLE IF EXISTS `setting`;
DROP TABLE IF EXISTS `log`;

CREATE TABLE IF NOT EXISTS `user` (
  `un` varchar(24) NOT NULL,
  `upwd` varchar(32) NOT NULL
);
CREATE TABLE IF NOT EXISTS `robot` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `bduss` text NOT NULL,
  PRIMARY KEY (`id`)
);
CREATE TABLE IF NOT EXISTS `setting` (
  `k` varchar(10) NOT NULL DEFAULT 'giuem',
  `api` varchar(24) NOT NULL DEFAULT 'xiaoji',
  `apikey` text,
  `blacklist` text,
  `kwblacklist` text,
  `islike` varchar(1) DEFAULT '0',
  `weiba` text,
  `reply` varchar(24) NOT NULL DEFAULT '0',
  `at` varchar(24) NOT NULL DEFAULT '0'
);
CREATE TABLE IF NOT EXISTS `log` (
  `time` int(10) NOT NULL ,
  `log` text NOT NULL,
  PRIMARY KEY (`time`)
);
INSERT INTO setting SET k='giuem',api='xiaoji',islike='1';
ALTER TABLE  `setting` ADD UNIQUE (
`k`
);