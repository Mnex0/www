##
# \\Author: Thibault Napoléon "Imothep"
# \\Company: ISEN Yncréa Ouest
# \\Email: thibault.napoleon@isen-ouest.yncrea.fr
# \\Created Date: 03-May-2022 - 21:02:34
# \\Last Modified: 30-Mar-2025 - 22:35:59
##

#-------------------------------------------------------------------------------
#--- Change database -----------------------------------------------------------
#-------------------------------------------------------------------------------
USE comweb_tp;

#-------------------------------------------------------------------------------
#--- Table cleanup -------------------------------------------------------------
#-------------------------------------------------------------------------------
DROP TABLE IF EXISTS evaluation;

#-------------------------------------------------------------------------------
#--- Table creation ------------------------------------------------------------
#-------------------------------------------------------------------------------
CREATE TABLE evaluation
(
	id VARCHAR(64) NOT NULL,
  name VARCHAR(64) NOT NULL,
	operators VARCHAR(64) NOT NULL DEFAULT '',
	data VARCHAR(64) NOT NULL DEFAULT '',
	result VARCHAR(64) NOT NULL DEFAULT '',
	answer INT NOT NULL DEFAULT 0,
	access TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY(id)
)
engine = innodb;

#-------------------------------------------------------------------------------
#--- Populate table ------------------------------------------------------------
#-------------------------------------------------------------------------------
INSERT INTO evaluation(id, name) VALUES(SHA1('11488716'), 'Owen BURLOT');
INSERT INTO evaluation(id, name) VALUES(SHA1('11536787'), 'Maël CORNIC');
INSERT INTO evaluation(id, name) VALUES(SHA1('11553629'), 'Antoine CORVAISIER');
INSERT INTO evaluation(id, name) VALUES(SHA1('11763298'), 'François DELATTRE');
INSERT INTO evaluation(id, name) VALUES(SHA1('11563985'), 'Jade DOGO');
INSERT INTO evaluation(id, name) VALUES(SHA1('11558566'), 'Evan ETCHEBERRY');
INSERT INTO evaluation(id, name) VALUES(SHA1('14683817'), 'Paul FLATRES');
INSERT INTO evaluation(id, name) VALUES(SHA1('11497935'), 'Caroline FORNER');
INSERT INTO evaluation(id, name) VALUES(SHA1('11755992'), 'Saan Boris GANGUE');
INSERT INTO evaluation(id, name) VALUES(SHA1('11580535'), 'Younes KIKMOUNE');
INSERT INTO evaluation(id, name) VALUES(SHA1('11533900'), 'Julie LARDILLEUX');
INSERT INTO evaluation(id, name) VALUES(SHA1('9461359'), 'Frédéric LE CLAIRE');
INSERT INTO evaluation(id, name) VALUES(SHA1('11548134'), 'Pierre MERDRIGNAC');
INSERT INTO evaluation(id, name) VALUES(SHA1('11711248'), 'Anita PESSEL CARVALHO');
INSERT INTO evaluation(id, name) VALUES(SHA1('11556443'), 'Maelenn PIAT');
INSERT INTO evaluation(id, name) VALUES(SHA1('11550963'), 'Lohann RIOU');
INSERT INTO evaluation(id, name) VALUES(SHA1('11566560'), 'Coraline SAVARY');
INSERT INTO evaluation(id, name) VALUES(SHA1('11686833'), 'Matthieu TETE');
INSERT INTO evaluation(id, name) VALUES(SHA1('11554013'), 'Gaspard VIEUJEAN');
INSERT INTO evaluation(id, name) VALUES(SHA1('9790132'), 'Ahmed YAHA');


SET autocommit = 0;
SET names utf8;
