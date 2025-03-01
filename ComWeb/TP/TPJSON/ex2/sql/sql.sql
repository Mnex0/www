##
# \\Author: Thibault Napoléon "Imothep"
# \\Company: ISEN Yncréa Ouest
# \\Email: thibault.napoleon@isen-ouest.yncrea.fr
# \\Created Date: 08-Sep-2021 - 16:53:12
# \\Last Modified: 23-Jan-2024 - 14:34:54
##

#-------------------------------------------------------------------------------
#--- Change database -----------------------------------------------------------
#-------------------------------------------------------------------------------
USE comweb_tp;

#-------------------------------------------------------------------------------
#--- Database cleanup ----------------------------------------------------------
#-------------------------------------------------------------------------------
DROP TABLE IF EXISTS polls;

#-------------------------------------------------------------------------------
#--- Database creation ---------------------------------------------------------
#-------------------------------------------------------------------------------
CREATE TABLE polls
(
	id INT NOT NULL auto_increment,
	title VARCHAR(100) NOT NULL,
	option1 VARCHAR(100) NOT NULL,
	option1score INT NOT NULL,
	option2 VARCHAR(100) NOT NULL,
	option2score INT NOT NULL,
	option3 VARCHAR(100) NOT NULL,
	option3score INT NOT NULL,
	participants INT NOT NULL,
	PRIMARY KEY(id)
)
engine = innodb;

#-------------------------------------------------------------------------------
#--- Populate databases --------------------------------------------------------
#-------------------------------------------------------------------------------
INSERT INTO polls( title, option1, option1score, option2, option2score, option3,
  option3score, participants) VALUES('Qu''elle est votre série préférée ?',
  'Battlestar Galactica', 32, 'Mr Robot', 105, 'Stranger Things', 45, 183);
INSERT INTO polls(title, option1, option1score, option2, option2score, option3,
  option3score, participants) VALUES(
  'Qu''elle est votre restaurant américain préféré ?', 'Mc Donalds', 48, 'KFC',
  45, 'Burger King', 72, 165);
INSERT INTO polls(title, option1, option1score, option2, option2score, option3,
  option3score, participants) VALUES('Qu''elle est votre langage préféré ?',
  'C++', 123, 'Java', 82, 'Python', 32, 237);

SET autocommit = 0;
SET names utf8;
