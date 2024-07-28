INSERT INTO `settings` (`id`, `name`, `value`) VALUES (NULL, 'woocommerce_update_merge_category_items', '0');
ALTER TABLE `items_categories_parents` ADD `is_synced` INT NOT NULL DEFAULT '0' AFTER `deny_delete`;
ALTER TABLE `items_categories` ADD `is_synced` INT NOT NULL DEFAULT '0' AFTER `deleted`;
INSERT INTO `settings` (`id`, `name`, `value`) VALUES (NULL, 'woocommerce_url', 'https://tekpluslb.com');


-- CMS : https://tekpluslb.com/tekplus-admin/
-- Username : ucef
-- assword : Ucef@Madi2023


-- ?r=woocommerce&f=delete_all_products

