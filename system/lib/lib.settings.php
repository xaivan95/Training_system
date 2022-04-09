<?php

/**
 * Set data to settings table.
 *
 * @param $p_name string setting name
 * @param $p_value
 *
 * @return bool TRUE on success or FALSE on failure
 */
function setting_set($p_name, $p_value)
{
    $t_name = db_escape_string($p_name);
    $t_value = db_prepare_string($p_value);

    $query = 'UPDATE ' . DB_TABLE_SETTINGS . ' SET `' . $t_name . '`=' . $t_value;
    db_query($query);

    return (db_last_error() == '');
}

/**
 * Get data from settings table.
 *
 * @return array settings array
 */
function settings_get()
{
    $settings = db_extract(DB_TABLE_SETTINGS);

    return (isset($settings[0]) ? $settings[0] : NULL);
}

