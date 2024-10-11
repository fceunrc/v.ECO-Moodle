<?php
defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) { // Necesario para evitar errores en la página de inicio de sesión
    $settings = new admin_settingpage('local_page_redirect', get_string('pluginname', 'local_page_redirect'));

    $settings->add(new admin_setting_configtext(
        'local_page_redirect/pagename',
        get_string('pagename', 'local_page_redirect'),
        get_string('pagename_desc', 'local_page_redirect'),
        'Inicio', // Valor por defecto
        PARAM_TEXT
    ));

    $ADMIN->add('localplugins', $settings);
}