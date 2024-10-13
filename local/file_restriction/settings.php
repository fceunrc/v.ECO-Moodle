<?php
// settings.php

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    // Creamos una nueva página de ajustes para el plugin.
    $settings = new admin_settingpage('local_file_restriction', get_string('pluginname', 'local_file_restriction'));

    // Añadimos la página de ajustes al árbol de administración.
    $ADMIN->add('localplugins', $settings);

    // Extensiones permitidas.
    $settings->add(new admin_setting_configtextarea(
        'local_file_restriction/extensiones_permitidas',
        get_string('extensiones_permitidas', 'local_file_restriction'),
        get_string('extensiones_permitidas_desc', 'local_file_restriction'),
        'png, jpg, jpeg'
    ));

    // Extensiones prohibidas.
    $settings->add(new admin_setting_configtextarea(
        'local_file_restriction/extensiones_prohibidas',
        get_string('extensiones_prohibidas', 'local_file_restriction'),
        get_string('extensiones_prohibidas_desc', 'local_file_restriction'),
        'pdf, docx, xlsx, pptx, csv'
    ));
}