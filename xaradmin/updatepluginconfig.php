<?php
/**
 * Update of plugin configuration
 *
 * @package modules
 * @copyright (C) 2011 2skies.com
 * @license GPL {@link http://www.gnu.org/licenses/gpl.html}
 * @link http://xarigami.com/projects/xartinymce
 *
 * @subpackage xartinymce module
 * @author Xarigami's team
 */
/**
 * Update plugin configuration
 *
 */
function tinymce_admin_updatepluginconfig()
{
    if (!xarSecConfirmAuthKey()) return;

    if (!xarVarFetch('plugin', 'str', $plugin, NULL,XARVAR_NOT_REQUIRED)) return;
    if (empty($plugin)) throw new BadParameterException();

    if (!xarVarFetch('config_key', 'array', $config_key, array(), XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('config_value', 'array', $config_value, array(), XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('mapping_key', 'array', $mapping_key, array(), XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('mapping_value', 'array', $mapping_value, array(), XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('styles_key', 'array', $style_key, array(), XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('styles_value', 'array', $style_value, array(), XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('thumbs_value', 'array', $thumbs_value, array(), XARVAR_NOT_REQUIRED)) return;

    $config_value = array_values($config_value);
    $config_key = array_values($config_key);
    $mapping_value = array_values($mapping_value);
    $mapping_key = array_values($mapping_key);
    $style_value = array_values($style_value);
    $style_key = array_values($style_key);

    $config = array();
    $mapping = array();
    $style = array();
    $thumbs = array();

    $c1 = count($config_key); $c2 = count($config_value);
    if ($c1 !== $c2) throw new BadParameterException();
    for ($i=0; $i !== $c1; $i++) {
        if (!empty($config_key[$i])) $config[$config_key[$i]] = $config_value[$i][0];
    }

    $c1 = count($mapping_key); $c2 = count($mapping_value);
    if ($c1 !== $c2) throw new BadParameterException();
    for ($i=0; $i !== $c1; $i++) {
        if (!empty($mapping_key[$i])) $mapping[$mapping_key[$i]] = $mapping_value[$i][0];
    }

    $c1 = count($style_key); $c2 = count($style_value);
    if ($c1 !== $c2) throw new BadParameterException();
    for ($i=0; $i !== $c1; $i++) {
        if (!empty($style_key[$i])) $style[$style_key[$i]] = $style_value[$i][0];
    }

    $c = count($thumbs_value);
    for ($i=0; $i !== $c; $i++) {
        if (!empty($thumbs_value[$i][0])) $thumbs[] = $thumbs_value[$i][0];
    }


    xarModSetVar('tinymce','config.'.$plugin, serialize(array('config' => $config, 'mapping' => $mapping, 'style' => $style, 'thumbs' => $thumbs)));

    $msg = xarML('Plugin configuration settings updated successfully.');
    xarTplSetMessage($msg,'status');
    xarResponseRedirect(xarModURL('tinymce', 'admin', 'modifypluginconfig', array('plugin'=>$plugin)));

    return TRUE;
}

?>