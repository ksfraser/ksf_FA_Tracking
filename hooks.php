<?php
$module_id = 'Tracking'; $module_version = '1.0.0'; $module_name = 'Visitor Tracking'; $module_description = 'Website visitor tracking';
$module_tables = ['fa_tracking_events', 'fa_tracking_visitors']; $module_capabilities = ['SA_TRACKINGVIEW'=>'View Tracking'];
function tracking_install():bool{return install_module_sql('Tracking');}function tracking_enable():bool{return enable_module('Tracking');}function tracking_disable():bool{return disable_module('Tracking');}function tracking_remove():bool{return remove_module_sql('Tracking');}
add_module($module_name,$module_version,$module_description);