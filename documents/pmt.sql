SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `pmt` DEFAULT CHARACTER SET utf8 ;
USE `pmt` ;

-- -----------------------------------------------------
-- Table `pmt`.`attachments`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `pmt`.`attachments` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NULL DEFAULT NULL ,
  `created_on` TIMESTAMP NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `pmt`.`users`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `pmt`.`users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NULL DEFAULT NULL ,
  `email` VARCHAR(255) NULL DEFAULT NULL ,
  `password` VARCHAR(32) NULL DEFAULT NULL ,
  `role` VARCHAR(45) NULL DEFAULT NULL ,
  `tieline` VARCHAR(45) NULL DEFAULT NULL ,
  `cellphone` VARCHAR(45) NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 6
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `pmt`.`attachment_files`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `pmt`.`attachment_files` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `attachment_id` INT(11) NOT NULL ,
  `owner_id` INT(11) NOT NULL ,
  `file_url` VARCHAR(255) NOT NULL ,
  `created_on` TIMESTAMP NULL DEFAULT NULL ,
  `description` TEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_attachment_files_1` (`attachment_id` ASC) ,
  INDEX `fk_attachment_files_2` (`owner_id` ASC) ,
  CONSTRAINT `fk_attachment_files_1`
    FOREIGN KEY (`attachment_id` )
    REFERENCES `pmt`.`attachments` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_attachment_files_2`
    FOREIGN KEY (`owner_id` )
    REFERENCES `pmt`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `pmt`.`vendors`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `pmt`.`vendors` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `pmt`.`vendor_contacts`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `pmt`.`vendor_contacts` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NULL DEFAULT NULL ,
  `office_phone` VARCHAR(45) NULL DEFAULT NULL ,
  `cellphone` VARCHAR(45) NULL DEFAULT NULL ,
  `email` VARCHAR(255) NOT NULL ,
  `address` VARCHAR(255) NULL DEFAULT NULL ,
  `description` TEXT NULL DEFAULT NULL ,
  `vendor_id` INT(11) NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_vendor_contacts_1` (`vendor_id` ASC) ,
  CONSTRAINT `fk_vendor_contacts_1`
    FOREIGN KEY (`vendor_id` )
    REFERENCES `pmt`.`vendors` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 10
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `pmt`.`candidates`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `pmt`.`candidates` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `pin` VARCHAR(45) NOT NULL ,
  `name` VARCHAR(45) NOT NULL ,
  `gender` ENUM('M','F') NOT NULL ,
  `cellphone` VARCHAR(45) NOT NULL ,
  `email` VARCHAR(255) NOT NULL ,
  `start_working_year` DATE NOT NULL ,
  `college` VARCHAR(45) NULL DEFAULT NULL ,
  `degree` VARCHAR(45) NULL DEFAULT NULL ,
  `current_company` VARCHAR(45) NULL DEFAULT NULL ,
  `resume` TEXT NULL DEFAULT NULL ,
  `vendor_contact_id` INT(11) NOT NULL ,
  `comments` TEXT NULL DEFAULT NULL ,
  `english_certification` VARCHAR(45) NULL DEFAULT NULL ,
  `created_on` TIMESTAMP NULL DEFAULT NULL ,
  `status` TINYINT(4) NULL DEFAULT NULL COMMENT '0: 可面试\\n1: 决定录用\\n2: 已录用\\n3: 面试失败\\n' ,
  `onboard_date` DATE NULL DEFAULT NULL ,
  `available_onboard_date` DATE NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `id_num_UNIQUE` (`pin` ASC) ,
  INDEX `fk_candidates_1` (`vendor_contact_id` ASC) ,
  CONSTRAINT `fk_candidates_1`
    FOREIGN KEY (`vendor_contact_id` )
    REFERENCES `pmt`.`vendor_contacts` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `pmt`.`sponsors`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `pmt`.`sponsors` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  `owner_name` VARCHAR(45) NULL DEFAULT NULL ,
  `description` TEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 6
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `pmt`.`projects`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `pmt`.`projects` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  `description` TEXT NULL DEFAULT NULL ,
  `sponsor_id` INT(11) NOT NULL ,
  `owner_id` INT(11) NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_projects_1` (`sponsor_id` ASC) ,
  INDEX `fk_projects_2` (`owner_id` ASC) ,
  CONSTRAINT `fk_projects_1`
    FOREIGN KEY (`sponsor_id` )
    REFERENCES `pmt`.`sponsors` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_projects_2`
    FOREIGN KEY (`owner_id` )
    REFERENCES `pmt`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `pmt`.`positions`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `pmt`.`positions` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NOT NULL ,
  `sponsor_id` INT(11) NOT NULL ,
  `project_id` INT(11) NOT NULL ,
  `requirments` TEXT NULL DEFAULT NULL ,
  `description` TEXT NULL DEFAULT NULL ,
  `working_experience` INT(11) NOT NULL ,
  `english_level` TINYINT(4) NOT NULL ,
  `head_count` INT(11) NOT NULL ,
  `effort_percentage` INT(11) NOT NULL ,
  `client_interview` ENUM('Y','N') NOT NULL ,
  `priority` TINYINT(4) NOT NULL ,
  `end_date` DATE NULL DEFAULT NULL ,
  `status` TINYINT(4) NOT NULL COMMENT '0:completed\\n1: opening\\n' ,
  `created_on` TIMESTAMP NULL DEFAULT NULL ,
  `hr_contact_id` INT(11) NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_positions_1` (`sponsor_id` ASC) ,
  INDEX `fk_positions_2` (`project_id` ASC) ,
  INDEX `fk_positions_3` (`hr_contact_id` ASC) ,
  CONSTRAINT `fk_positions_1`
    FOREIGN KEY (`sponsor_id` )
    REFERENCES `pmt`.`sponsors` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_positions_2`
    FOREIGN KEY (`project_id` )
    REFERENCES `pmt`.`projects` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_positions_3`
    FOREIGN KEY (`hr_contact_id` )
    REFERENCES `pmt`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `pmt`.`interviews`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `pmt`.`interviews` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `round` TINYINT(4) NOT NULL ,
  `scheduled_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
  `duration` INT(11) NOT NULL ,
  `candidate_id` INT(11) NOT NULL ,
  `location` VARCHAR(255) NULL DEFAULT NULL ,
  `type` ENUM('T','F') NULL DEFAULT NULL COMMENT 'T: 电话面试\\nF: 面对面\\n' ,
  `owned_by` INT(11) NULL DEFAULT NULL ,
  `hr_contact_id` INT(11) NULL DEFAULT NULL ,
  `position_id` INT(11) NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_interviews_1` (`candidate_id` ASC) ,
  INDEX `fk_interviews_2` (`owned_by` ASC) ,
  INDEX `fk_interviews_3` (`hr_contact_id` ASC) ,
  INDEX `fk_interviews_4` (`position_id` ASC) ,
  CONSTRAINT `fk_interviews_1`
    FOREIGN KEY (`candidate_id` )
    REFERENCES `pmt`.`candidates` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_interviews_2`
    FOREIGN KEY (`owned_by` )
    REFERENCES `pmt`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_interviews_3`
    FOREIGN KEY (`hr_contact_id` )
    REFERENCES `pmt`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_interviews_4`
    FOREIGN KEY (`position_id` )
    REFERENCES `pmt`.`positions` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `pmt`.`attachment_to_interview`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `pmt`.`attachment_to_interview` (
  `attachment_id` INT(11) NOT NULL ,
  `interview_id` INT(11) NOT NULL ,
  PRIMARY KEY (`attachment_id`, `interview_id`) ,
  INDEX `fk_attachment_to_interview_1` (`attachment_id` ASC) ,
  INDEX `fk_attachment_to_interview_2` (`interview_id` ASC) ,
  CONSTRAINT `fk_attachment_to_interview_1`
    FOREIGN KEY (`attachment_id` )
    REFERENCES `pmt`.`attachments` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_attachment_to_interview_2`
    FOREIGN KEY (`interview_id` )
    REFERENCES `pmt`.`interviews` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `pmt`.`skills`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `pmt`.`skills` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  `description` TEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `name_UNIQUE` (`name` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `pmt`.`candidate_to_skill`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `pmt`.`candidate_to_skill` (
  `candidate_id` INT(11) NOT NULL ,
  `skill_id` INT(11) NOT NULL ,
  PRIMARY KEY (`candidate_id`, `skill_id`) ,
  INDEX `fk_candidate_to_skill_1` (`candidate_id` ASC) ,
  INDEX `fk_candidate_to_skill_2` (`skill_id` ASC) ,
  CONSTRAINT `fk_candidate_to_skill_1`
    FOREIGN KEY (`candidate_id` )
    REFERENCES `pmt`.`candidates` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_candidate_to_skill_2`
    FOREIGN KEY (`skill_id` )
    REFERENCES `pmt`.`skills` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `pmt`.`feedback`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `pmt`.`feedback` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `interviewer_id` INT(11) NOT NULL ,
  `interview_id` INT(11) NOT NULL ,
  `skill_level` TINYINT(4) NULL DEFAULT NULL ,
  `english_level` TINYINT(4) NULL DEFAULT NULL ,
  `attitude_level` TINYINT(4) NULL DEFAULT NULL ,
  `comments` TEXT NULL DEFAULT NULL ,
  `decision` ENUM('N','Y') NULL DEFAULT NULL COMMENT 'Y: Pass\\nN: Failed' ,
  `updated_on` TIMESTAMP NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_feedback_1` (`interview_id` ASC) ,
  INDEX `fk_feedback_2` (`interviewer_id` ASC) ,
  CONSTRAINT `fk_feedback_1`
    FOREIGN KEY (`interview_id` )
    REFERENCES `pmt`.`interviews` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_feedback_2`
    FOREIGN KEY (`interviewer_id` )
    REFERENCES `pmt`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `pmt`.`position_to_skill`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `pmt`.`position_to_skill` (
  `position_id` INT(11) NOT NULL ,
  `skill_id` INT(11) NOT NULL ,
  `type` ENUM('R','A') NULL DEFAULT NULL COMMENT 'R: required\\nA: additional' ,
  PRIMARY KEY (`position_id`, `skill_id`) ,
  INDEX `fk_position_to_skill_1` (`skill_id` ASC) ,
  INDEX `fk_position_to_skill_2` (`position_id` ASC) ,
  CONSTRAINT `fk_position_to_skill_1`
    FOREIGN KEY (`skill_id` )
    REFERENCES `pmt`.`skills` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_position_to_skill_2`
    FOREIGN KEY (`position_id` )
    REFERENCES `pmt`.`positions` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `pmt`.`sponsor_contacts`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `pmt`.`sponsor_contacts` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  `email` VARCHAR(255) NOT NULL ,
  `description` TEXT NULL DEFAULT NULL ,
  `sponsor_id` INT(11) NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_sponsor_contacts_1` (`sponsor_id` ASC) ,
  CONSTRAINT `fk_sponsor_contacts_1`
    FOREIGN KEY (`sponsor_id` )
    REFERENCES `pmt`.`sponsors` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 7
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Placeholder table for view `pmt`.`candidate_to_skill_view`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pmt`.`candidate_to_skill_view` (`candidate_id` INT, `skill_id` INT, `candidate_name` INT, `skill_name` INT);

-- -----------------------------------------------------
-- Placeholder table for view `pmt`.`candidates_view`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pmt`.`candidates_view` (`id` INT, `pin` INT, `name` INT, `gender` INT, `cellphone` INT, `email` INT, `start_working_year` INT, `college` INT, `degree` INT, `current_company` INT, `resume` INT, `vendor_contact_id` INT, `comments` INT, `english_certification` INT, `created_on` INT, `status` INT, `onboard_date` INT, `available_onboard_date` INT, `vendor_contact_name` INT);

-- -----------------------------------------------------
-- Placeholder table for view `pmt`.`feedback_view`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pmt`.`feedback_view` (`id` INT, `interviewer_id` INT, `interview_id` INT, `skill_level` INT, `english_level` INT, `attitude_level` INT, `comments` INT, `decision` INT, `updated_on` INT, `interviewer_name` INT);

-- -----------------------------------------------------
-- Placeholder table for view `pmt`.`interviews_view`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pmt`.`interviews_view` (`id` INT, `round` INT, `scheduled_time` INT, `duration` INT, `candidate_id` INT, `location` INT, `type` INT, `owned_by` INT, `hr_contact_id` INT, `position_id` INT, `candidate_name` INT, `owned_by_name` INT, `hr_contact_name` INT, `position_name` INT);

-- -----------------------------------------------------
-- Placeholder table for view `pmt`.`position_to_skill_view`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pmt`.`position_to_skill_view` (`position_id` INT, `skill_id` INT, `type` INT, `position_name` INT, `skill_name` INT);

-- -----------------------------------------------------
-- Placeholder table for view `pmt`.`positions_view`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pmt`.`positions_view` (`id` INT, `name` INT, `sponsor_id` INT, `project_id` INT, `requirments` INT, `description` INT, `working_experience` INT, `english_level` INT, `head_count` INT, `effort_percentage` INT, `client_interview` INT, `priority` INT, `end_date` INT, `status` INT, `created_on` INT, `hr_contact_id` INT, `sponsor_name` INT, `project_name` INT, `hr_contact_name` INT);

-- -----------------------------------------------------
-- Placeholder table for view `pmt`.`projects_view`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pmt`.`projects_view` (`id` INT, `name` INT, `description` INT, `sponsor_id` INT, `owner_id` INT, `sponsor_name` INT, `owner_name` INT);

-- -----------------------------------------------------
-- Placeholder table for view `pmt`.`sponsor_contacts_view`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pmt`.`sponsor_contacts_view` (`id` INT, `name` INT, `email` INT, `description` INT, `sponsor_id` INT, `sponsor_name` INT);

-- -----------------------------------------------------
-- Placeholder table for view `pmt`.`vendor_contacts_view`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pmt`.`vendor_contacts_view` (`id` INT, `name` INT, `office_phone` INT, `cellphone` INT, `email` INT, `address` INT, `description` INT, `vendor_id` INT, `vendor_name` INT);

-- -----------------------------------------------------
-- View `pmt`.`candidate_to_skill_view`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pmt`.`candidate_to_skill_view`;
USE `pmt`;
CREATE  OR REPLACE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `pmt`.`candidate_to_skill_view` AS select `cts`.`candidate_id` AS `candidate_id`,`cts`.`skill_id` AS `skill_id`,`ca`.`name` AS `candidate_name`,`sk`.`name` AS `skill_name` from ((`pmt`.`candidate_to_skill` `cts` join `pmt`.`candidates` `ca` on((`cts`.`candidate_id` = `ca`.`id`))) join `pmt`.`skills` `sk` on((`cts`.`skill_id` = `sk`.`id`)));

-- -----------------------------------------------------
-- View `pmt`.`candidates_view`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pmt`.`candidates_view`;
USE `pmt`;
CREATE  OR REPLACE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `pmt`.`candidates_view` AS select `ca`.`id` AS `id`,`ca`.`pin` AS `pin`,`ca`.`name` AS `name`,`ca`.`gender` AS `gender`,`ca`.`cellphone` AS `cellphone`,`ca`.`email` AS `email`,`ca`.`start_working_year` AS `start_working_year`,`ca`.`college` AS `college`,`ca`.`degree` AS `degree`,`ca`.`current_company` AS `current_company`,`ca`.`resume` AS `resume`,`ca`.`vendor_contact_id` AS `vendor_contact_id`,`ca`.`comments` AS `comments`,`ca`.`english_certification` AS `english_certification`,`ca`.`created_on` AS `created_on`,`ca`.`status` AS `status`,`ca`.`onboard_date` AS `onboard_date`,`ca`.`available_onboard_date` AS `available_onboard_date`,`vc`.`name` AS `vendor_contact_name` from (`pmt`.`candidates` `ca` join `pmt`.`vendor_contacts` `vc` on((`ca`.`vendor_contact_id` = `vc`.`id`)));

-- -----------------------------------------------------
-- View `pmt`.`feedback_view`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pmt`.`feedback_view`;
USE `pmt`;
CREATE  OR REPLACE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `pmt`.`feedback_view` AS select `fb`.`id` AS `id`,`fb`.`interviewer_id` AS `interviewer_id`,`fb`.`interview_id` AS `interview_id`,`fb`.`skill_level` AS `skill_level`,`fb`.`english_level` AS `english_level`,`fb`.`attitude_level` AS `attitude_level`,`fb`.`comments` AS `comments`,`fb`.`decision` AS `decision`,`fb`.`updated_on` AS `updated_on`,`us`.`name` AS `interviewer_name` from ((`pmt`.`feedback` `fb` join `pmt`.`users` `us` on((`fb`.`interviewer_id` = `us`.`id`))) join `pmt`.`interviews` `iv` on((`fb`.`interview_id` = `iv`.`id`)));

-- -----------------------------------------------------
-- View `pmt`.`interviews_view`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pmt`.`interviews_view`;
USE `pmt`;
CREATE  OR REPLACE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `pmt`.`interviews_view` AS select `iv`.`id` AS `id`,`iv`.`round` AS `round`,`iv`.`scheduled_time` AS `scheduled_time`,`iv`.`duration` AS `duration`,`iv`.`candidate_id` AS `candidate_id`,`iv`.`location` AS `location`,`iv`.`type` AS `type`,`iv`.`owned_by` AS `owned_by`,`iv`.`hr_contact_id` AS `hr_contact_id`,`iv`.`position_id` AS `position_id`,`ca`.`name` AS `candidate_name`,`us`.`name` AS `owned_by_name`,`us2`.`name` AS `hr_contact_name`,`po`.`name` AS `position_name` from ((((`pmt`.`interviews` `iv` join `pmt`.`candidates` `ca` on((`iv`.`candidate_id` = `ca`.`id`))) left join `pmt`.`users` `us` on((`iv`.`owned_by` = `us`.`id`))) left join `pmt`.`users` `us2` on((`iv`.`hr_contact_id` = `us2`.`id`))) left join `pmt`.`positions` `po` on((`iv`.`position_id` = `po`.`id`)));

-- -----------------------------------------------------
-- View `pmt`.`position_to_skill_view`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pmt`.`position_to_skill_view`;
USE `pmt`;
CREATE  OR REPLACE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `pmt`.`position_to_skill_view` AS select `pts`.`position_id` AS `position_id`,`pts`.`skill_id` AS `skill_id`,`pts`.`type` AS `type`,`po`.`name` AS `position_name`,`sk`.`name` AS `skill_name` from ((`pmt`.`position_to_skill` `pts` join `pmt`.`positions` `po` on((`pts`.`position_id` = `po`.`id`))) join `pmt`.`skills` `sk` on((`pts`.`skill_id` = `sk`.`id`)));

-- -----------------------------------------------------
-- View `pmt`.`positions_view`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pmt`.`positions_view`;
USE `pmt`;
CREATE  OR REPLACE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `pmt`.`positions_view` AS select `po`.`id` AS `id`,`po`.`name` AS `name`,`po`.`sponsor_id` AS `sponsor_id`,`po`.`project_id` AS `project_id`,`po`.`requirments` AS `requirments`,`po`.`description` AS `description`,`po`.`working_experience` AS `working_experience`,`po`.`english_level` AS `english_level`,`po`.`head_count` AS `head_count`,`po`.`effort_percentage` AS `effort_percentage`,`po`.`client_interview` AS `client_interview`,`po`.`priority` AS `priority`,`po`.`end_date` AS `end_date`,`po`.`status` AS `status`,`po`.`created_on` AS `created_on`,`po`.`hr_contact_id` AS `hr_contact_id`,`sp`.`name` AS `sponsor_name`,`pr`.`name` AS `project_name`,`us`.`name` AS `hr_contact_name` from (((`pmt`.`positions` `po` join `pmt`.`sponsors` `sp` on((`po`.`sponsor_id` = `sp`.`id`))) join `pmt`.`projects` `pr` on((`po`.`project_id` = `pr`.`id`))) left join `pmt`.`users` `us` on((`po`.`hr_contact_id` = `us`.`id`)));

-- -----------------------------------------------------
-- View `pmt`.`projects_view`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pmt`.`projects_view`;
USE `pmt`;
CREATE  OR REPLACE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `pmt`.`projects_view` AS select `pr`.`id` AS `id`,`pr`.`name` AS `name`,`pr`.`description` AS `description`,`pr`.`sponsor_id` AS `sponsor_id`,`pr`.`owner_id` AS `owner_id`,`sp`.`name` AS `sponsor_name`,`us`.`name` AS `owner_name` from ((`pmt`.`projects` `pr` join `pmt`.`users` `us` on((`pr`.`owner_id` = `us`.`id`))) join `pmt`.`sponsors` `sp` on((`pr`.`sponsor_id` = `sp`.`id`)));

-- -----------------------------------------------------
-- View `pmt`.`sponsor_contacts_view`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pmt`.`sponsor_contacts_view`;
USE `pmt`;
CREATE  OR REPLACE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `pmt`.`sponsor_contacts_view` AS select `spc`.`id` AS `id`,`spc`.`name` AS `name`,`spc`.`email` AS `email`,`spc`.`description` AS `description`,`spc`.`sponsor_id` AS `sponsor_id`,`sp`.`name` AS `sponsor_name` from (`pmt`.`sponsor_contacts` `spc` join `pmt`.`sponsors` `sp` on((`sp`.`id` = `spc`.`sponsor_id`)));

-- -----------------------------------------------------
-- View `pmt`.`vendor_contacts_view`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pmt`.`vendor_contacts_view`;
USE `pmt`;
CREATE  OR REPLACE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `pmt`.`vendor_contacts_view` AS select `vc`.`id` AS `id`,`vc`.`name` AS `name`,`vc`.`office_phone` AS `office_phone`,`vc`.`cellphone` AS `cellphone`,`vc`.`email` AS `email`,`vc`.`address` AS `address`,`vc`.`description` AS `description`,`vc`.`vendor_id` AS `vendor_id`,`vs`.`name` AS `vendor_name` from (`pmt`.`vendor_contacts` `vc` join `pmt`.`vendors` `vs` on((`vc`.`vendor_id` = `vs`.`id`)));


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
