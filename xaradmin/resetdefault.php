<?php
/**
 * Admin Configuration function
 *
 * @package modules
 * @license GPL {@link http://www.gnu.org/licenses/gpl.html}
 *
 * @subpackage xartinymce module
 * @copyright (C) 2008-2011 2skies.com
 * @link http://xarigami.com/projects/xartinymce
 * @author Jo Dalle Nogare <icedlava@2skies.com>
 */
/**
 * Reset default
 *
 * @param str    $args['resettype']  default - configs
 * @return array Array of issues, or false on failure
 */
function tinymce_admin_resetdefault($args)
{
    // Get arguments from argument array
    extract($args);

    if (!xarSecurityCheck('AdminTinyMCE', 1)) {
        return;
    }
    if (!xarVarFetch('resettype',   'strlist:,:pre:lower:trim:passthru:enum:default:configs:formmode', $resettype, 'configs', XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('returnurl',   'str:0:255', $returnurl, '', XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('useadvanced',  'checkbox',  $useadvanced,         false, XARVAR_DONT_SET)) return;
    if (!xarVarFetch('id',     'int',  $id,         NULL, XARVAR_DONT_SET)) return;

    if (!isset($resettype) || empty($resettype))  {
        $msg = xarML('Invalid #(1)  function #(2) in module #(4)',
                    'resettype','user', 'reset', 'tinymce');
        xarErrorSet(XAR_USER_EXCEPTION, 'BAD_PARAM',
                       new SystemException($msg));
        return false;
    }
    $args['resettype'] = $resettype;
    $args['useadvanced'] = $useadvanced;
    $args['id'] = $id;

    xarModAPIFunc('tinymce','admin','resetdefault',$args);
    if (!isset($returnurl) || empty($returnurl)) {
        $returnurl = xarModURL('tinymce','admin','manageconfigs',array('action'=>'modify','id'=>1));
    }
        $msg = xarML('TinyMCE settings now reset back to those at install.');
    xarTplSetMessage($msg,'status');
    xarResponseRedirect($returnurl);
    return;
}

?>