<?php
/**
 * xarTinyMCE initialization
 *
 * @package modules
 * @copyright (C) 2004-2013 2skies.com
 * @license GPL {@link http://www.gnu.org/licenses/gpl.html}
 * @link http://xarigami.com/project/xartinymce
 *
 * @subpackage xartinymce module
 * @author Jo Dalle Nogare <icedlava@2skies.com>
 */

$modversion['name']         = 'tinymce';
$modversion['directory']    = 'tinymce';
$modversion['id']           = '63';
$modversion['version']      = '3.4.0';
$modversion['displayname']  = 'XarTinyMCE';
$modversion['description']  = 'Integration of TinyMCE, a fast and configurable wysiwyg editor for Xarigami';
$modversion['credits']      = 'xardocs/credits.txt';
$modversion['help']         = 'xardocs/readme.txt';
$modversion['changelog']    = 'xardocs/changelog.txt';
$modversion['license']      = 'xardocs/license.txt';
$modversion['official']     = 1;
$modversion['author']       = 'Jo Dalle Nogare (jojodee)';
$modversion['contact']      = 'http://xarigami.com';
$modversion['homepage']     = 'http://xarigami.com/project/xartinymce';
$modversion['admin']        = 1;
$modversion['user']         = 0;
$modversion['class']        = 'Complete';
$modversion['category']     = 'Utilities';
$modversion['dependency']   = array(779); /* Dependency on html module */
$modversion['dependencyinfo']   = array(
                                    0 => array(
                                            'name' => 'core',
                                            'version_ge' => '1.4.0'
                                         ),
                                    779 => array(
                                            'name' => 'html',
                                            'version_ge' => '1.5.0'
                                        )
                                );
if (false) { //Load and translate once
    xarML('xarTinymce');
    xarML('Integration of TinyMCE, a fast and configurable wysiwyg editor for Xaraya');
}
?>
