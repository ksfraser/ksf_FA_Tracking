<?php
/**
 * FA_Tracking Module Hooks for FrontAccounting
 */

define('SS_TRACKING', 139 << 8);

class hooks_fa_tracking extends hooks {
    var $module_name = 'fa_tracking';

    function install_options($app) {
        global $path_to_root;

        switch($app->id) {
            case 'CRM':
                $app->add_lapp_function(0, _("Visitor Tracking"),
                    $path_to_root."/modules/".$this->module_name."/visitors.php", 'SA_TRACKINGVIEW', MENU_INQUIRY);
                $app->add_lapp_function(1, _("Tracking Events"),
                    $path_to_root."/modules/".$this->module_name."/events.php", 'SA_TRACKINGVIEW', MENU_INQUIRY);
                break;
        }
    }

    function install_access() {
        $security_sections[SS_TRACKING] = _("Visitor Tracking");
        $security_areas['SA_TRACKINGVIEW'] = array(SS_TRACKING | 1, _("View Tracking"));
        return array($security_areas, $security_sections);
    }

    function activate_extension($company, $check_only=true) {
        $updates = array('sql/update.sql' => array($this->module_name));
        $ok = $this->update_databases($company, $updates, $check_only);
        if ($check_only || !$ok) {
            return $ok;
        }
        $this->ensure_tracking_schema();
        return $ok;
    }

    private function table_exists($table) {
        $sql = "SHOW TABLES LIKE " . db_escape($table);
        $res = db_query($sql, 'Failed checking table existence');
        return db_num_rows($res) > 0;
    }

    private function ensure_tracking_schema() {
        $tables = array(
            TB_PREF . "fa_tracking_visitors" => "
                CREATE TABLE IF NOT EXISTS `" . TB_PREF . "fa_tracking_visitors` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT,
                    `visitor_id` VARCHAR(100) NOT NULL,
                    `ip_address` VARCHAR(45) DEFAULT NULL,
                    `user_agent` TEXT,
                    `first_visit` DATETIME NOT NULL,
                    `last_visit` DATETIME NOT NULL,
                    `visit_count` INT(11) DEFAULT 1,
                    `debtor_no` VARCHAR(20) DEFAULT NULL,
                    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `idx_visitor_id` (`visitor_id`),
                    KEY `idx_debtor` (`debtor_no`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

            TB_PREF . "fa_tracking_events" => "
                CREATE TABLE IF NOT EXISTS `" . TB_PREF . "fa_tracking_events` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT,
                    `visitor_id` VARCHAR(100) NOT NULL,
                    `event_type` VARCHAR(50) NOT NULL,
                    `event_data` TEXT,
                    `page_url` VARCHAR(500) DEFAULT NULL,
                    `referrer` VARCHAR(500) DEFAULT NULL,
                    `event_time` DATETIME NOT NULL,
                    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY (`id`),
                    KEY `idx_visitor` (`visitor_id`),
                    KEY `idx_event_type` (`event_type`),
                    KEY `idx_event_time` (`event_time`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
        );

        foreach ($tables as $table_name => $sql) {
            db_query($sql, "Could not create Tracking table: $table_name");
        }
    }

    function db_prevoid($trans_type, $trans_no) {
        // Handle voiding if needed
    }
}
?>
