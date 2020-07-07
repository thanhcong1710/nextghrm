-- -----------------------------------------------------
-- Table `#__discuss_configs`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__discuss_configs` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NULL DEFAULT NULL,
  `params` TEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `#__discuss_posts`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__discuss_posts` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` TEXT NULL DEFAULT NULL,
  `alias` TEXT NOT NULL,
  `created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified` DATETIME NULL DEFAULT '0000-00-00 00:00:00',
  `replied` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `content` LONGTEXT NOT NULL,
  `published` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
  `ordering` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
  `vote` INT(11) UNSIGNED NULL DEFAULT '0',
  `hits` INT(11) UNSIGNED NULL DEFAULT '0',
  `islock` TINYINT(1) UNSIGNED NULL DEFAULT '0',
  `lockdate` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `featured` TINYINT(1) UNSIGNED NULL DEFAULT '0',
  `isresolve` TINYINT(1) UNSIGNED NULL DEFAULT '0',
  `isreport` TINYINT(1) UNSIGNED NULL DEFAULT '0',
  `answered` TINYINT(1) UNSIGNED NULL DEFAULT '0',
  `user_id` BIGINT(20) UNSIGNED NULL DEFAULT '0',
  `parent_id` BIGINT(20) UNSIGNED NULL DEFAULT '0',
  `user_type` VARCHAR(255) NOT NULL,
  `poster_name` VARCHAR(255) NOT NULL,
  `poster_email` VARCHAR(255) NOT NULL,
  `num_likes` INT(11) NULL DEFAULT 0,
  `num_negvote` INT(11) NULL DEFAULT 0,
  `sum_totalvote` INT(11) NULL DEFAULT 0,
  `category_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1,
  `params` TEXT NOT NULL,
  `password` TEXT NULL DEFAULT NULL,
  `legacy` TINYINT(1) NULL DEFAULT 1,
  `address` TEXT NULL DEFAULT NULL,
  `latitude` VARCHAR(255) NOT NULL,
  `longitude` VARCHAR(255) NOT NULL,
  `content_type` VARCHAR(25) NULL DEFAULT NULL,
  `post_status` TINYINT(1) NOT NULL DEFAULT 0,
  `post_type` VARCHAR(255) NOT NULL DEFAULT 0,
  `private` TINYINT(3) NOT NULL DEFAULT 0,
  `language` CHAR(7) NOT NULL DEFAULT '*',
  PRIMARY KEY (`id`),
  INDEX `discuss_post_published` (`published` ASC),
  INDEX `discuss_post_user_id` (`user_id` ASC),
  INDEX `discuss_post_vote` (`vote` ASC),
  INDEX `discuss_post_parentid` (`published` ASC, `parent_id` ASC),
  INDEX `discuss_post_isreport` (`isreport` ASC),
  INDEX `discuss_post_answered` (`answered` ASC),
  INDEX `discuss_post_category` (`category_id` ASC),
  INDEX `discuss_post_query1` (`published` ASC, `parent_id` ASC, `answered` ASC, `id` ASC),
  INDEX `discuss_post_query2` (`published` ASC, `parent_id` ASC, `answered` ASC, `replied` ASC),
  INDEX `discuss_post_query3` (`published` ASC, `parent_id` ASC, `category_id` ASC, `created` ASC),
  INDEX `discuss_post_query4` (`published` ASC, `parent_id` ASC, `category_id` ASC, `id` ASC),
  INDEX `discuss_post_query5` (`published` ASC, `parent_id` ASC, `created` ASC),
  INDEX `discuss_post_query6` (`published` ASC, `parent_id` ASC, `id` ASC),
  INDEX `unread_category_posts` (`published` ASC, `parent_id` ASC, `legacy` ASC, `category_id` ASC, `id` ASC),
  FULLTEXT INDEX `discuss_post_titlecontent` (`title` ASC, `content` ASC),
  INDEX `discuss_post_last_reply` (`parent_id` ASC, `id` ASC),
  INDEX `idx_post_type` (`post_type` ASC),
  INDEX `idx_post_replied` (`replied` ASC),
  INDEX `idx_post_created` (`created` ASC),
  INDEX `idx_post_private` (`private` ASC),
  INDEX `idx_post_search1` (`published` ASC, `parent_id` ASC, `private` ASC, `replied` ASC),
  INDEX `idx_post_search2` (`published` ASC, `private` ASC, `created` ASC),
  INDEX `idx_post_search1a` (`published` ASC, `parent_id` ASC, `private` ASC),
  INDEX `idx_post_search2a` (`published` ASC, `private` ASC),
  INDEX `discuss_posts_language_idx` (`language` ASC))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `#__discuss_comments`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__discuss_comments` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `comment` TEXT NULL DEFAULT NULL,
  `name` VARCHAR(255) NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NULL DEFAULT '',
  `url` VARCHAR(255) NULL DEFAULT '',
  `ip` VARCHAR(255) NULL DEFAULT '',
  `created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified` DATETIME NULL DEFAULT '0000-00-00 00:00:00',
  `published` TINYINT(1) UNSIGNED NULL DEFAULT '0',
  `ordering` TINYINT(1) UNSIGNED NULL DEFAULT '0',
  `post_id` BIGINT(20) UNSIGNED NULL DEFAULT NULL,
  `user_id` INT(11) UNSIGNED NULL DEFAULT '0',
  `parent_id` INT(11) NOT NULL DEFAULT 0,
  `sent` TINYINT(1) NOT NULL DEFAULT 0,
  `lft` INT(11) NOT NULL DEFAULT 0,
  `rgt` INT(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  INDEX `discuss_comment_postid` (`post_id` ASC),
  INDEX `discuss_comment_post_created` (`post_id` ASC, `created` ASC))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `#__discuss_tags`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__discuss_tags` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(100) NOT NULL,
  `alias` VARCHAR(100) NOT NULL,
  `created` DATETIME NOT NULL,
  `published` TINYINT(1) UNSIGNED NULL DEFAULT '0',
  `user_id` INT(11) UNSIGNED NULL DEFAULT NULL,
  `language` CHAR(7) NOT NULL DEFAULT '*',
  PRIMARY KEY (`id`),
  INDEX `discuss_tags_alias` (`alias` ASC),
  INDEX `discuss_tags_user_id` (`user_id` ASC),
  INDEX `discuss_tags_published` (`published` ASC),
  INDEX `discuss_tags_query1` (`published` ASC, `id` ASC),
  FULLTEXT INDEX `discuss_tags_title` (`title` ASC),
  INDEX `discuss_tags_language_idx` (`language` ASC))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `#__discuss_posts_tags`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__discuss_posts_tags` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `post_id` BIGINT(20) UNSIGNED NULL DEFAULT NULL,
  `tag_id` BIGINT(20) UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `post_tag` (`post_id` ASC, `tag_id` ASC),
  INDEX `discuss_posts_tags_tagid` (`tag_id` ASC),
  INDEX `discuss_posts_tags_postid` (`post_id` ASC))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `#__discuss_votes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__discuss_votes` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) UNSIGNED NULL DEFAULT NULL,
  `post_id` BIGINT(20) UNSIGNED NULL DEFAULT NULL,
  `created` DATETIME NULL DEFAULT NULL,
  `ipaddress` VARCHAR(15) NULL DEFAULT NULL,
  `value` TINYINT(2) NULL DEFAULT '0',
  `session_id` VARCHAR(200) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `discuss_user_post` (`user_id` ASC, `post_id` ASC),
  INDEX `discuss_post_id` (`post_id` ASC),
  INDEX `discuss_user_id` (`user_id` ASC),
  INDEX `discuss_session_id` (`session_id` ASC))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `#__discuss_likes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__discuss_likes` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` VARCHAR(20) NOT NULL,
  `content_id` INT(11) NOT NULL,
  `created_by` BIGINT(20) UNSIGNED NULL DEFAULT 0,
  `created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  INDEX `discuss_content_type` (`type` ASC, `content_id` ASC),
  INDEX `discuss_contentid` (`content_id` ASC),
  INDEX `discuss_createdby` (`created_by` ASC))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `#__discuss_reports`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__discuss_reports` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `post_id` INT(11) NOT NULL,
  `reason` TEXT NULL DEFAULT NULL,
  `created_by` BIGINT(20) UNSIGNED NULL DEFAULT 0,
  `created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  INDEX `discuss_reports_post` (`post_id` ASC))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `#__discuss_mailq`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__discuss_mailq` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `mailfrom` VARCHAR(255) NULL DEFAULT NULL,
  `fromname` VARCHAR(255) NULL DEFAULT NULL,
  `recipient` VARCHAR(255) NOT NULL,
  `subject` TEXT NOT NULL,
  `body` TEXT NOT NULL,
  `created` DATETIME NOT NULL,
  `status` TINYINT(1) NOT NULL DEFAULT '0',
  `ashtml` TINYINT(1) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `discuss_mailq_status` (`status` ASC))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `#__discuss_users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__discuss_users` (
  `id` BIGINT(20) UNSIGNED NOT NULL,
  `nickname` VARCHAR(255) NULL DEFAULT NULL,
  `avatar` VARCHAR(255) NULL DEFAULT NULL,
  `description` TEXT NULL DEFAULT NULL,
  `url` VARCHAR(255) NULL DEFAULT NULL,
  `params` TEXT NULL DEFAULT NULL,
  `alias` VARCHAR(255) NULL DEFAULT NULL,
  `points` BIGINT NOT NULL DEFAULT '0',
  `latitude` VARCHAR(255) NULL DEFAULT NULL,
  `longitude` VARCHAR(255) NULL DEFAULT NULL,
  `location` TEXT NOT NULL,
  `signature` TEXT NOT NULL,
  `edited` TEXT NOT NULL,
  `posts_read` TEXT NULL DEFAULT NULL,
  `site` TEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `discuss_users_alias` (`alias` ASC))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `#__discuss_subscription`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__discuss_subscription` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `userid` BIGINT(20) NOT NULL,
  `member` TINYINT(1) NOT NULL DEFAULT '0',
  `type` VARCHAR(100) NOT NULL DEFAULT 'daily',
  `cid` BIGINT(20) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `fullname` VARCHAR(255) NOT NULL,
  `interval` VARCHAR(100) NOT NULL,
  `created` DATETIME NOT NULL,
  `sent_out` DATETIME NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `#__discuss_attachments`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__discuss_attachments` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `uid` INT(11) NOT NULL,
  `title` TEXT NOT NULL,
  `type` VARCHAR(200) NOT NULL,
  `path` TEXT NOT NULL,
  `created` DATETIME NOT NULL,
  `published` TINYINT(3) NOT NULL,
  `mime` TEXT NOT NULL,
  `size` TEXT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `uid` (`uid` ASC),
  INDEX `type` (`type` ASC))
ENGINE = MyISAM
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `#__discuss_category`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__discuss_category` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `created_by` INT(11) NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT NOT NULL DEFAULT '',
  `alias` VARCHAR(255) NULL DEFAULT NULL,
  `created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
  `published` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
  `ordering` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
  `avatar` VARCHAR(255) NULL DEFAULT NULL,
  `parent_id` INT(11) NULL DEFAULT '0',
  `private` INT(11) UNSIGNED NOT NULL DEFAULT '0',
  `default` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
  `level` INT(11) NULL DEFAULT NULL,
  `lft` INT(11) NULL DEFAULT NULL,
  `rgt` INT(11) NULL DEFAULT NULL,
  `params` TEXT NOT NULL,
  `container` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  `language` CHAR(7) NOT NULL DEFAULT '*',
  PRIMARY KEY (`id`),
  INDEX `discuss_cat_published` (`published` ASC),
  INDEX `discuss_cat_parentid` (`parent_id` ASC),
  INDEX `discuss_cat_mod_categories1` (`published` ASC, `private` ASC, `id` ASC),
  INDEX `discuss_cat_mod_categories2` (`published` ASC, `private` ASC, `ordering` ASC),
  INDEX `discuss_cat_acl` (`parent_id` ASC, `published` ASC, `ordering` ASC),
  INDEX `discuss_category_language_idx` (`language` ASC))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `#__discuss_acl`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__discuss_acl` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `action` VARCHAR(255) NOT NULL,
  `default` TINYINT(1) NOT NULL DEFAULT '1',
  `description` TEXT NOT NULL,
  `published` TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
  `ordering` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
  `public` TINYINT(1) UNSIGNED NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  INDEX `discuss_post_acl_action` (`action` ASC))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `#__discuss_acl_group`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__discuss_acl_group` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `content_id` BIGINT(20) UNSIGNED NOT NULL,
  `acl_id` BIGINT(20) UNSIGNED NOT NULL,
  `status` TINYINT(1) NOT NULL,
  `type` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `discuss_post_acl_content_type` (`content_id` ASC, `type` ASC),
  INDEX `discuss_post_acl` (`acl_id` ASC))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `#__discuss_hashkeys`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__discuss_hashkeys` (
  `id` BIGINT(11) NOT NULL AUTO_INCREMENT,
  `uid` BIGINT(11) NOT NULL,
  `type` VARCHAR(255) NOT NULL,
  `key` TEXT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `uid` (`uid` ASC),
  INDEX `type` (`type` ASC))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `#__discuss_notifications`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__discuss_notifications` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `title` TEXT NOT NULL,
  `cid` BIGINT(20) NOT NULL,
  `type` VARCHAR(255) NOT NULL,
  `created` DATETIME NOT NULL,
  `target` BIGINT(20) NOT NULL,
  `author` BIGINT(20) NOT NULL,
  `permalink` TEXT NOT NULL,
  `state` TINYINT(4) NOT NULL,
  `favicon` TEXT NOT NULL,
  `component` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `discuss_notification_created` (`created` ASC),
  INDEX `discuss_notification` (`target` ASC, `state` ASC, `cid` ASC, `created` ASC, `id` ASC))
ENGINE = MyISAM
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `#__discuss_category_acl_item`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__discuss_category_acl_item` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `action` VARCHAR(255) NOT NULL,
  `description` TEXT NULL DEFAULT NULL,
  `published` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
  `default` TINYINT(1) NULL DEFAULT '1',
  PRIMARY KEY (`id`))
ENGINE = MyISAM
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `#__discuss_category_acl_map`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__discuss_category_acl_map` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `category_id` BIGINT(20) NOT NULL,
  `acl_id` BIGINT(20) NOT NULL,
  `type` VARCHAR(25) NOT NULL,
  `content_id` BIGINT(20) NOT NULL,
  `status` TINYINT(1) NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  INDEX `discuss_category_acl` (`category_id` ASC),
  INDEX `discuss_category_acl_id` (`acl_id` ASC),
  INDEX `discuss_content_type` (`content_id` ASC, `type` ASC),
  INDEX `discuss_category_content_type` (`category_id` ASC, `content_id` ASC, `type` ASC))
ENGINE = MyISAM
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `#__discuss_badges`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__discuss_badges` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `rule_id` BIGINT(20) NOT NULL,
  `title` TEXT NOT NULL,
  `alias` VARCHAR(255) NOT NULL,
  `description` TEXT NOT NULL,
  `avatar` TEXT NOT NULL,
  `created` DATETIME NOT NULL,
  `published` TINYINT(3) NOT NULL,
  `rule_limit` BIGINT(20) NOT NULL,
  `language` CHAR(7) NOT NULL DEFAULT '*',
  PRIMARY KEY (`id`),
  INDEX `discuss_badges_alias` (`alias` ASC),
  INDEX `discuss_badges_published` (`published` ASC),
  INDEX `discuss_badges_language_idx` (`language` ASC))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `#__discuss_badges_history`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__discuss_badges_history` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT(20) NOT NULL,
  `title` TEXT NOT NULL,
  `command` TEXT NOT NULL,
  `created` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `user_id` (`user_id` ASC))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `#__discuss_badges_rules`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__discuss_badges_rules` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `command` TEXT NOT NULL,
  `title` TEXT NOT NULL,
  `description` TEXT NOT NULL,
  `callback` TEXT NOT NULL,
  `created` DATETIME NOT NULL,
  `published` TINYINT(3) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `#__discuss_badges_users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__discuss_badges_users` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `badge_id` BIGINT(20) NOT NULL,
  `user_id` BIGINT(20) NOT NULL,
  `created` DATETIME NOT NULL,
  `published` TINYINT(3) NOT NULL,
  `custom` TEXT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `badge_id` (`badge_id` ASC, `user_id` ASC))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `#__discuss_points`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__discuss_points` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `rule_id` BIGINT(20) NOT NULL,
  `title` TEXT NOT NULL,
  `created` DATETIME NOT NULL,
  `published` TINYINT(3) NOT NULL,
  `rule_limit` BIGINT(20) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `discuss_points_rule` (`rule_id` ASC),
  INDEX `discuss_points_published` (`published` ASC))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `#__discuss_rules`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__discuss_rules` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `command` TEXT NOT NULL,
  `title` TEXT NOT NULL,
  `description` TEXT NOT NULL,
  `callback` TEXT NOT NULL,
  `created` DATETIME NOT NULL,
  `published` TINYINT(3) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `discuss_rules_command` (`command`(255) ASC))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `#__discuss_users_history`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__discuss_users_history` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT(20) NOT NULL,
  `title` TEXT NOT NULL,
  `command` TEXT NOT NULL,
  `created` DATETIME NOT NULL,
  `content_id` BIGINT(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `user_id` (`user_id` ASC))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `#__discuss_ranks`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__discuss_ranks` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `title` TEXT NOT NULL,
  `start` BIGINT(20) NOT NULL DEFAULT 0,
  `end` BIGINT(20) NOT NULL DEFAULT 0,
  `language` CHAR(7) NOT NULL DEFAULT '*',
  PRIMARY KEY (`id`),
  INDEX `discuss_ranks_range` (`start` ASC, `end` ASC),
  INDEX `discuss_ranks_language_idx` (`language` ASC))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `#__discuss_oauth`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__discuss_oauth` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `type` VARCHAR(255) NOT NULL,
  `request_token` TEXT NOT NULL,
  `access_token` TEXT NOT NULL,
  `message` TEXT NOT NULL,
  `params` TEXT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `discuss_oauth_type` (`type` ASC))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `#__discuss_oauth_posts`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__discuss_oauth_posts` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `post_id` BIGINT(20) NOT NULL,
  `oauth_id` BIGINT(20) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `#__discuss_migrators`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__discuss_migrators` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `internal_id` BIGINT(20) NOT NULL,
  `external_id` BIGINT(20) NOT NULL,
  `component` TEXT NOT NULL,
  `type` TEXT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_external_id` (`external_id` ASC))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `#__discuss_views`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__discuss_views` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT(20) NOT NULL,
  `hash` VARCHAR(255) NOT NULL,
  `created` DATETIME NOT NULL,
  `ip` VARCHAR(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `user_id` (`user_id` ASC),
  INDEX `hash` (`hash` ASC))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `#__discuss_polls`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__discuss_polls` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `post_id` BIGINT(20) NOT NULL,
  `value` TEXT NOT NULL,
  `count` BIGINT(20) NOT NULL DEFAULT '0',
  `multiple_polls` TINYINT(1) UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `post_id` (`post_id` ASC),
  INDEX `polls_posts` (`post_id` ASC, `id` ASC))
ENGINE = MyISAM
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `#__discuss_polls_users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__discuss_polls_users` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `poll_id` BIGINT(20) NOT NULL,
  `user_id` BIGINT(20) NOT NULL,
  `session_id` VARCHAR(200) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `poll_id` (`poll_id` ASC, `user_id` ASC))
ENGINE = MyISAM
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `#__discuss_posts_references`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__discuss_posts_references` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `post_id` BIGINT(20) NOT NULL,
  `reference_id` BIGINT(20) NOT NULL,
  `extension` TEXT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `post_id` (`post_id` ASC, `reference_id` ASC))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `#__discuss_ranks_users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__discuss_ranks_users` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `rank_id` BIGINT(20) NOT NULL,
  `user_id` BIGINT(20) NOT NULL,
  `created` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `ranks_users` (`rank_id` ASC, `user_id` ASC),
  INDEX `ranks_id` (`rank_id` ASC),
  INDEX `ranks_userid` (`user_id` ASC))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `#__discuss_favourites`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__discuss_favourites` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `created_by` BIGINT(20) NOT NULL,
  `post_id` BIGINT(20) NOT NULL,
  `type` VARCHAR(20) NOT NULL,
  `created` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_fav_postid` (`post_id` ASC))
ENGINE = MyISAM
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `#__discuss_roles`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__discuss_roles` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT NOT NULL,
  `usergroup_id` INT(10) UNSIGNED NOT NULL,
  `colorcode` VARCHAR(255) NOT NULL,
  `published` TINYINT(1) NOT NULL DEFAULT '0',
  `ordering` BIGINT(20) NOT NULL DEFAULT '0',
  `created_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_user_id` INT(11) NOT NULL DEFAULT '0',
  `language` CHAR(7) NOT NULL DEFAULT '*',
  PRIMARY KEY (`id`),
  INDEX `discuss_roles_language_idx` (`language` ASC))
ENGINE = MyISAM
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `#__discuss_polls_question`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__discuss_polls_question` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `post_id` BIGINT(20) NOT NULL,
  `title` TEXT NOT NULL,
  `multiple` TINYINT(1) NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  INDEX `post_id` (`post_id` ASC))
ENGINE = MyISAM
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `#__discuss_customfields`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__discuss_customfields` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` VARCHAR(100) NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `params` TEXT NULL DEFAULT NULL,
  `ordering` BIGINT(20) NOT NULL DEFAULT '0',
  `published` TINYINT(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`))
ENGINE = MyISAM
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `#__discuss_customfields_acl`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__discuss_customfields_acl` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `action` VARCHAR(255) NOT NULL,
  `description` TEXT NOT NULL,
  `acl_published` TINYINT(1) UNSIGNED NOT NULL,
  `default` TINYINT(1) NULL DEFAULT '1',
  PRIMARY KEY (`id`))
ENGINE = MyISAM
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `#__discuss_customfields_rule`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__discuss_customfields_rule` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `field_id` BIGINT(20) UNSIGNED NOT NULL,
  `acl_id` BIGINT(20) NOT NULL,
  `content_id` INT(10) NOT NULL,
  `content_type` VARCHAR(25) NOT NULL,
  `status` TINYINT(1) NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  INDEX `cf_rule_field_id` (`field_id` ASC),
  INDEX `cf_rule_acl_types` (`content_type` ASC, `acl_id` ASC, `content_id` ASC))
ENGINE = MyISAM
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `#__discuss_customfields_value`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__discuss_customfields_value` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `field_id` BIGINT(20) UNSIGNED NOT NULL,
  `value` TEXT NOT NULL,
  `post_id` BIGINT(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `cf_value_field_id` (`field_id` ASC),
  INDEX `cf_value_field_post` (`field_id` ASC, `post_id` ASC))
ENGINE = MyISAM
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `#__discuss_conversations`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__discuss_conversations` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `created` DATETIME NOT NULL,
  `created_by` BIGINT(20) NOT NULL,
  `lastreplied` DATETIME NOT NULL,
  `language` CHAR(7) NOT NULL DEFAULT '*',
  PRIMARY KEY (`id`),
  INDEX `discuss_conversations_language_idx` (`language` ASC))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `#__discuss_conversations_message`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__discuss_conversations_message` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `conversation_id` BIGINT(20) NOT NULL,
  `message` TEXT NULL DEFAULT NULL,
  `created` DATETIME NOT NULL,
  `created_by` BIGINT(20) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `conversation_id` (`conversation_id` ASC),
  INDEX `created_by` (`created_by` ASC))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `#__discuss_conversations_message_maps`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__discuss_conversations_message_maps` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT(20) NOT NULL,
  `conversation_id` BIGINT(20) NOT NULL,
  `message_id` BIGINT(20) NOT NULL,
  `isread` TINYINT(1) NOT NULL DEFAULT '0',
  `state` TINYINT(3) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `node_id` (`user_id` ASC),
  INDEX `conversation_id` (`conversation_id` ASC),
  INDEX `message_id` (`message_id` ASC))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `#__discuss_conversations_participants`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__discuss_conversations_participants` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `conversation_id` BIGINT(20) NOT NULL,
  `user_id` BIGINT(20) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `conversation_id` (`conversation_id` ASC))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `#__discuss_assignment_map`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__discuss_assignment_map` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `post_id` BIGINT(20) UNSIGNED NOT NULL,
  `assignee_id` BIGINT(20) UNSIGNED NOT NULL,
  `assigner_id` BIGINT(20) UNSIGNED NOT NULL,
  `created` DATETIME NOT NULL,
  `description` TEXT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `post_id` (`post_id` ASC))
ENGINE = MyISAM
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `#__discuss_posts_labels`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__discuss_posts_labels` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT NOT NULL,
  `published` TINYINT(1) NOT NULL DEFAULT '0',
  `ordering` BIGINT(20) NOT NULL DEFAULT '0',
  `creator` BIGINT(20) UNSIGNED NOT NULL,
  `created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`))
ENGINE = MyISAM
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `#__discuss_posts_labels_map`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__discuss_posts_labels_map` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `post_id` BIGINT(20) UNSIGNED NOT NULL,
  `post_label_id` BIGINT(20) UNSIGNED NOT NULL,
  `creator_id` BIGINT(20) UNSIGNED NOT NULL,
  `created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  INDEX `post_id` (`post_id` ASC))
ENGINE = MyISAM
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `#__discuss_post_types`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__discuss_post_types` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `suffix` VARCHAR(50) NOT NULL,
  `created` DATETIME NOT NULL,
  `published` TINYINT(3) NOT NULL,
  `alias` VARCHAR(255) NOT NULL,
  `language` CHAR(7) NOT NULL DEFAULT '*',
  PRIMARY KEY (`id`),
  INDEX `idx_alias` (`alias` ASC),
  INDEX `discuss_post_types_language_idx` (`language` ASC))
ENGINE = MyISAM
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `#__discuss_captcha`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__discuss_captcha` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `response` VARCHAR(5) NOT NULL,
  `created` DATETIME NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = MyISAM
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;
