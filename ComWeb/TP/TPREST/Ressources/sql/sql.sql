##
# \\Author: Thibault Napoléon "Imothep"
# \\Company: ISEN Yncréa Ouest
# \\Email: thibault.napoleon@isen-ouest.yncrea.fr
# \\Created Date: 08-Sep-2021 - 16:53:12
# \\Last Modified: 12-May-2023 - 08:31:46
##

#-------------------------------------------------------------------------------
#--- Change database -----------------------------------------------------------
#-------------------------------------------------------------------------------
USE comweb_tp;

#-------------------------------------------------------------------------------
#--- Database cleanup ----------------------------------------------------------
#-------------------------------------------------------------------------------
DROP TABLE IF EXISTS tweets;

#-------------------------------------------------------------------------------
#--- Database creation ---------------------------------------------------------
#-------------------------------------------------------------------------------
CREATE TABLE tweets
(
	id INT NOT NULL auto_increment,
	text VARCHAR(80) NOT NULL,
	login VARCHAR(20) NOT NULL,
	PRIMARY KEY(id)
)
engine = innodb;

#-------------------------------------------------------------------------------
#--- Populate databases --------------------------------------------------------
#-------------------------------------------------------------------------------
INSERT INTO tweets(text, login) VALUES('Un tweet des CIR1 !!', 'cir1');
INSERT INTO tweets(text, login) VALUES('Un tweet des CIR2 !!', 'cir2');
INSERT INTO tweets(text, login) VALUES('Un tweet des CIR3 !!', 'cir3');
INSERT INTO tweets(text, login) VALUES('Un tweet des M1 !!', 'm1');
INSERT INTO tweets(text, login) VALUES('Un tweet des M2 !!', 'm2');

SET autocommit = 0;
SET names utf8;
