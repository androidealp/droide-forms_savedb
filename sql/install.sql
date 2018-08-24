CREATE TABLE IF NOT EXISTS `#__droide_forms_register` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `form_id` VARCHAR(50) NOT NULL,
  `form_name` VARCHAR(100) NOT NULL,
  `dt_send` DATETIME NOT NULL,
  `fileds` TEXT NOT NULL,
  `status_custom` VARCHAR(70) NOT NULL,
  `return_custom_1` TEXT NOT NULL,
  `return_custom_2` TEXT NOT NULL,
  `return_custom_3` TEXT NOT NULL,
  `return_custom_4` TEXT NOT NULL,
  `return_custom_5` TEXT NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB