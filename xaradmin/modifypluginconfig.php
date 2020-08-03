<?php
/**
 * Admin Plugin Configuration function
 *
 * @package modules
 * @license GPL {@link http://www.gnu.org/licenses/gpl.html}
 *
 * @subpackage xartinymce module
 * @copyright (C) 2011 2skies.com
 * @link http://xarigami.com/projects/xartinymce
 * @author Xarigami's team
 */

/**
 * A standard function to modify the configuration parameters of tinymce
 *
 */
function tinymce_admin_modifypluginconfig()
{
    if (!xarSecurityCheck('AdminTinyMCE')) return;
    
    if (!xarVarFetch('plugin', 'str', $plugin,  NULL,XARVAR_NOT_REQUIRED)) return;
    if (empty($plugin)) throw new BadParameterException();
    
    // @TODO: find a way to validate the plugin.
    
    $settings = xarModAPIFunc('tinymce', 'user', 'getpluginconfig', array('plugin' => $plugin));
    
    $data = array();
    
    //common admin menu
    $data['menulinks'] = xarModAPIFunc('tinymce','admin','getmenulinks');
    $data['plugin'] = $plugin;
    $data['url'] = xarModURL('tinymce', 'admin', 'updatepluginconfig', array('plugin' => $plugin));
    
    $data['config'] = $settings['config'];
    $data['mapping'] = $settings['mapping'];
    $data['style'] = $settings['style'];
    $data['thumbs'] = $settings['thumbs'];
    
    $data['authid'] = xarSecGenAuthKey();
    // Specify some labels and values for display
    $data['updatebutton'] = xarVarPrepForDisplay(xarML('Update Configuration'));
    
    return $data;
}

?>