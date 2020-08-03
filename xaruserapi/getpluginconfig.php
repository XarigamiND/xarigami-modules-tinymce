<?php
/**
 * Get Plugin Config
 *
 * @package modules
 * @copyright (C) 2010-2011 2skies.com
 * @license GPL {@link http://www.gnu.org/licenses/gpl.html}
 * @link http://xarigami.com/projects/xartinymce
 *
 * @subpackage xartinymce module
 * @copyright (C) 2011 2skies.com
 * @link http://xarigami.com/projects/xartinymce
 * @author Xarigami's team
/**
 * Get the array corresponding to the plugin config
 *
 * @param int  $args['plugin']    the plugin to use
 * @param int $args['prepare']    prepare the data to be used in the plugin.
 * @raise BAD_PARAM, DATABASE_ERROR, NO_PERMISSION
 */
function tinymce_userapi_getpluginconfig($args)
{
    extract($args);
    
    if (empty($plugin)) throw new BadParameterException();
    if (!isset($prepare) || $prepare !== TRUE) $prepare = FALSE;
    
    // Security check
    if (!xarSecurityCheck('ReadTinyMCE', 0)) {
        $msg = xarML('No plugin authorization');
        xarResponseForbidden($msg);
    }
    
    // First, let's try to get the config array from the modvar:
    $settings = xarModGetVar('tinymce','config.'.$plugin);
    if (empty($settings)) {
        $settings = array();
    } else {
        $settings = unserialize($settings);
    }
    
    if (count($settings) === 0 || array_key_exists('config', $settings) && count($settings['config']) === 0 
        && array_key_exists('mapping', $settings) && count($settings['mapping'] === 0)) {
        // We create here default initialization settings for various plugins
        switch ($plugin) {
            case 'imanager':
            case 'ibrowser':
                $settings['thumbs'] = array('*|*|false', '400|*|false', '400|*|true', '120|*|false', '75|*|false'); 
                $settings['config'] = array('ilibs_dir' => 'var/uploads/images/', 'ilibs_dir_show' => TRUE, 'ilibs_inc' => 'scripts/rdirs.php',
                    'list' => TRUE, 'valid' => 'gif|jpg|jpeg|png', 'lang'=>'en', 'upload' => TRUE, 'umax' => 1, 'create' => TRUE,
                    'delete' => TRUE, 'rename' => TRUE, 'attrib' => FALSE, 'furl' => TRUE, 'random' => '&w=150&h=150&zc=1');
                $settings['mapping'] = array ('var/uploads/' => 'Stock Images', 'var/images/Image/' => 'Image Library');
                $settings['style'] = array ('left' => 'align left', 'right' => 'align right', 'capDivRightBrd' => 'align right, border',
                                'capDivRight' => 'align right', 'capDivLeftBrd' => 'align left, border', 'capDivLeft' => 'align left');
                break;
            default:
                break;
        }
    }
    
    if ($prepare) {
        // Prepare the settings array to be used in the plugins
        switch ($plugin) {
            case 'imanager':
            case 'ibrowser':
                
                $arr = $settings['config'];
                $arr['style'] = $settings['style'];
                foreach($settings['mapping'] as $value => $key) {
                    $arr['ilibs'][] = array('value' => $key, 'text' => $value);
                }
                foreach($settings['thumbs'] as $value) {
                    $arrval = explode('|', $value);
                    if (count($arrval) > 2) {
                        $b = stripos($arrval[2], 'true') !== FALSE ? TRUE : FALSE;
                        $arr['thumbs'][] = array('size' => $arrval[0], 'ext' => $arrval[1], 'crop' => $b);
                    } 
                }
                if (!isset($arr['ilibs_inc'])) $arr['ilibs_inc'] = '';
                $arr['ilibs_dir'] = array($arr['ilibs_dir']);
                $arr['ilibs_dir_show'] = $arr['ilibs_dir_show'] === TRUE || intval($arr['ilibs_dir_show']) === 1;
                $arr['list'] = $arr['list'] === TRUE || intval($arr['list']) === 1;
                
                $arr['upload'] = $arr['upload'] === TRUE || intval($arr['upload']) === 1;
                $arr['create'] = $arr['create'] === TRUE || intval($arr['create']) === 1;
                $arr['delete'] = $arr['delete'] === TRUE || intval($arr['delete']) === 1;
                $arr['rename'] = $arr['rename'] === TRUE || intval($arr['rename']) === 1;
                $arr['attrib'] = $arr['attrib'] === TRUE || intval($arr['attrib']) === 1;
                
                $allowedSubmit = xarSecurityCheck('SubmitTinyMCE', 0);
                $arr['upload'] = $arr['upload'] && $allowedSubmit;
                
                $allowedEdit = xarSecurityCheck('EditTinyMCE', 0);
                $arr['create'] = $arr['create'] && $allowedEdit;
                $arr['delete'] = $arr['delete'] && $allowedEdit;
                $arr['rename'] = $arr['rename'] && $allowedEdit;
                $arr['attrib'] = $arr['attrib'] && $allowedEdit;
                
                $arr['furl'] = $arr['furl'] === TRUE || intval($arr['furl']) === 1;
                $arr['valid'] = explode('|', $arr['valid']);
                $arr['root_dir'] = sys::$pathWebRoot->getAbs();
                $arr['ver'] = $plugin.' external version (unset)';
                $arr['base_url'] = xarServerGetProtocol() . '://' . xarServerGetHost();
                $mainDir = sys::$pathCode->append('modules/'.xarModGetDirFromName('tinymce', 'module').'/xarincludes/plugins/'.$plugin);
                $pathMain = xarPath::make($mainDir);
                if (!$pathMain->isExist()) throw new DirectoryNotFoundException($arr['main_dir']);
                $arr['main_dir'] = $pathMain->getAbs();
                $pathScripts = $pathMain->getSubPath('scripts');
                $arr['scripts'] = $pathScripts->getAbs();
                $arr['scripts_url'] = '/'.$pathScripts->gap(xarPath::FROM_WEBROOT);
                $arr['fonts'] = $pathMain->append('fonts');
                $arr['mask']= $pathMain->append('masks');
                $arr['olay'] = $pathMain->append('olays');
                $arr['mark'] = $pathMain->append('wmarks');
                $arr['langs'] = $pathMain->append('langs');
                $arr['pop_url'] = $arr['scripts_url'] . 'popup.php';
                
                $arr['temp']  = sys::varpath(). '/cache/templates/';
                if (xarModIsAvailable('images')) {
                    $_temp_ = xarModGetVar('images', 'path.derivative-store');
                    if ($_temp_ !== NULL) {
                        $_path_ = xarPath::makeFromWeb($_temp_);
                        if ($_path_->isExist() && is_writable($_path_->getAbs())) $arr['temp'] = $_path_->getAbs();
                    }
                }
                
                $arr2['cache_directory'] = $arr['temp'];
                $arr2['document_root'] = $arr['root_dir'];

                $settings = array('cfg' => $arr, 'PHPTHUMB_CONFIG' => $arr2);
                
                break;
            default:
                break;
        }
    }
    
    return $settings;
    }
?>
