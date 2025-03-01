##
# \\Author: Thibault Napoléon "Imothep"
# \\Company: ISEN Yncréa Ouest
# \\Email: thibault.napoleon@isen-ouest.yncrea.fr
# \\Created Date: 08-Sep-2021 - 16:53:12
# \\Last Modified: 23-Jan-2024 - 14:35:04
##

#-------------------------------------------------------------------------------
#--- Create database and add user ----------------------------------------------
#-------------------------------------------------------------------------------
CREATE DATABASE comweb_tp DEFAULT CHARACTER SET utf8 DEFAULT COLLATE
  utf8_general_ci;
USE comweb_tp;
CREATE USER 'comweb_tp'@'localhost' IDENTIFIED BY 'pt_bewmoc_isen29';
GRANT ALL PRIVILEGES ON comweb_tp.* TO 'comweb_tp'@'localhost'
  WITH GRANT OPTION;
