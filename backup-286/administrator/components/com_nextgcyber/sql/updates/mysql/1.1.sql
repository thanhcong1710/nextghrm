-- -----------------------------------------------------
-- Table `#__nextgcyber_product_content_rel`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__nextgcyber_product_content_rel` (
  `product_id` INT(11) UNSIGNED NOT NULL,
  `content_id` INT(11) UNSIGNED NOT NULL)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;