<?php
/**
 * Dynamic data tinymce WYSIWYG GUI property

 * @package modules
 * @copyright (C) 2002-2006 The Digital Development Foundation
 * @license GPL {@link http://www.gnu.org/licenses/gpl.html}
 *
 * @subpackage xartinymce module
 * @copyright (C) 2004-2011 2skies.com
 * @link http://xarigami.com/projects/xartinymce
 * @author Jo Dalle Nogare <icedlava@2skies.com>
 */
/**
 * Handle tinymce wysiwyg textarea property
 * Utilizes JavaScript based WYSIWYG Editor, TinyMCE
 *
 * @author jojodee
 * @package dynamicdata
 */
sys::import ('modules.base.xarproperties.Dynamic_TextArea_Property');

class Dynamic_TinyMCE_Property extends  Dynamic_TextArea_Property
{
    public $xv_rows = 8;
    public $xv_cols = 35;
    public $xv_wrap = 'soft';
    public $xv_classname = NULL; // passed in from GUI
    public $class = NULL; //from template
    public $defaultclass='mceEditor';

  function __construct($args)
  {
        parent::__construct($args);
        $this->tplmodule = 'tinymce';
        $this->template = 'tinymce';

        $this->filepath   = 'modules/tinymce/xarproperties';
    }

   function showInput(Array $data = array())
    {
        extract($data);

        if (empty($name)) {
             if (isset($this->anonymous) && $this->anonymous == true)
            {
                $name = $this->name;
            } else {
                $name = 'dd_'.$this->id;
            }
        }
        if (empty($id)) {
            $id = $name;
        }
        if (!isset($classname)) {
            $classname = $this->xv_classname;
        }
        $data = array();

        $xarbaseurl=xarServerGetBaseURL();
        $editorpath = "'.$xarbaseurl.'modules/tinymce/xartemplates/includes/tinymce/jscripts/tiny_mce";
         //initialize
        $data['jsstring'] = '';
        $data['gzstring'] = '';
        $config['options'] = array();
        $data['usebutton'] = '';

        $data['name']     = $name;
        $data['id']       = $id;
        $data['value']    = isset($value) ? xarVarPrepForDisplay($value) : xarVarPrepForDisplay($this->value);
        $data['tabindex'] = !empty($tabindex) ? $tabindex : 0;
        $data['invalid']  = !empty($this->invalid) ? xarML('Invalid #(1)', $this->invalid) :'';
        $data['rows']     = !empty($rows) ? $rows : $this->xv_rows;
        $data['cols']     = !empty($cols) ? $cols : $this->xv_cols;

        //we allow GUI to override a template class
        $data['defaultconfig'] = xarModGetVar('tinymce','default');
        //we could have more than one name in the class from the template so it gets tricky
        $classarray = '';
        $config = $data['defaultconfig']; //by default
        if (!empty($classname)) { //gui classname takes precedence
            $test = xarModAPIFunc('tinymce','user','getall',array('name'=>$classname)); //passed in from GUI
            $test= @current($test);
            if (is_array($test) && !empty($test)) {
                $config = $classname;
                $class = $classname;
            }

        }elseif (empty($classname) && isset($class) && !empty($class)) { // we might have something from template
            $classarray = explode(' ',$class);
            foreach ($classarray as $templateclass) {
                $test =  xarModAPIFunc('tinymce','user','getall',array('name'=>trim($templateclass)));
                $test = current($test);
                 if (is_array($test) && !empty($test)) { //take the first match for a config
                    $config =$templateclass;
                    $classname = $templateclass;
                    $class = $templateclass;
                    break;
                }
            }
        } else { //both classname and class are empty
            //get the id for default
             $defconfig= xarModAPIFunc('tinymce','user','getall',array('name'=> $data['defaultconfig']));
             $defconfig = current($defconfig);
             $id =  $defconfig['id'];
             if (xarSecurityCheck('ReadTinyMCE', 0, 'instance', "$id")) {
                    $config = $data['defaultconfig'];
              }else {
                    $config ='';
              }
        }

        //we can always have a config for DD, the default, but not a classname.
        if (!empty($config)) {
            $configs = xarModAPIFunc('tinymce','user','getall',array('name'=>$config));
        }
        if (isset($config) && count($config)>0) {
            $config = @current($configs);
        } else {
            //we forgot to check something - show some error
        }

        $options = unserialize($config['options']);
        $data['usebutton'] = $options['useswitch'];
        $data['usegzp'] = $options['usegzp'];
        $data['isactive'] = $options['active'];
        $data['configname'] = $config['name'];
        $data['autoload'] = $options['autoload'];
        $data['tinyloadmode'] = xarModGetVar('tinymce','tinymodeload');
        $data['editor_selector'] = isset($options['editor_selector'])?$options['editor_selector']:$this->defaultclass;
        $data['editor_deselector'] = isset($options['editor_deselector'])?$options['editor_deselector']:'mceNoEditor';

        if (empty($classname)) $classname = $data['editor_selector'];
        if (empty($class)) $class = $classname;

        $data['usingdd'] = TRUE;
        $browsers = explode(',',$options['browsers']);
        //work out whether we need to show the editor - better to do it here instead of the template
        $useragent = xarServerGetVar('HTTP_USER_AGENT');
        $usewysiwyg = 0;
        //we might have had a non-empty classname or class but not match for a config - perhaps it is irrelevant OR a deselector

        if ($classname == $data['editor_deselector']) {//no wysiwyg
            $usewysiwyg = 0;
            $data['jsstring'] = '';
            $data['gzstring']  = '';
            $data['editor_selector'] = $classname; //ie the editor deselector
        }elseif (xarModGetVar('tinymce','activetinymce')  && xarModIsAvailable('tinymce') && $data['isactive']) {
             foreach($browsers as $browsername) {
                 if (preg_match('/'.$browsername.'/i',$useragent)) {
                    $usewysiwyg = 1;
                 }
             }
            $data['jsstring'] = $config['jsstring'];
            $data['gzstring'] = $config['gzstring'];
        }else {
            $usewysiwyg = 0;
            $data['jsstring'] = '';
            $data['gzstring'] = '';
        }

        $data['classname']    = $classname;

        $data['usewysiwyg'] = $usewysiwyg;
        $data['template'] = (isset($template) && !empty($template)) ? $template : $this->template;
                $evts = '';
        $evtlist = array('onblur','onchange','onfocus','onreset','onselect','onsubmit','onabort','onkeydown','onkeypress',
                         'onkeyup','onclick','ondblclick','onmousedown','onmousemove','onmouseout','onmouseover','onmouseup');
        foreach ($evtlist as $eattr)
        {
            if (!empty($args[$eattr])) {
                $evts .=" $eattr=\"$args[$eattr]\"";
            }
        }
        $data['evts'] = $evts;

        $data['label'] = isset($label) ? $label :$this->label;
        $data['autocomplete'] = isset($args['autocomplete']) && ($args['autocomplete'] == TRUE) ? 'off':'';

        $data['disabled'] = (isset($args['disabled']) && !empty($args['disabled']) && $args['disabled'] == TRUE ) ? 'disabled':'';

        $data['required'] = (isset($args['required']) && !empty($args['required']) && $args['required'] == TRUE ) ? 'required':'';
        $html5tags = array('required','aria-required','aria-describedby','placeholder','aria-valuemin','aria-valuemax',
                           'contenteditable','accesskey','contextmenu','dir','draggable','dropzone','spellcheck','tel','search','url',
                           'email','datetime','date','month','week','time','datetime-local','mumber','range','color','autocomplete',
                           'autofocus','form','height','width','list','step','multiple','novalidate','pattern',
                           'readonly','formnovalidate','formtarget','accept');
        $html5= '';
        foreach ($html5tags as $attr)
        {
            if (!empty($args[$attr])) {
                $html5 .=" $attr=\"$args[$attr]\"";
            }
        }
        $data['html5'] = $html5;
         return xarTplProperty('tinymce', $data['template'], 'showinput', $data);
    }

     function getBasePropertyInfo()
     {
        $args = array();

        $validation = $this->getBaseValidationInfo();
        $validation = serialize($validation);
        $args = array();

        $baseInfo =   array(
                            'id'            => 205,
                            'name'          => 'xartinymce',
                            'label'         => 'WYSIWYG Editor-TinyMCE',
                            'format'        => '5',
                            'validation'    => $validation,
                            'source'        => '',
                            'dependancies'  => '',
                            'requiresmodule'=> 'tinymce',
                            'filepath'      => 'modules/tinymce/xarproperties',
                            'aliases'       => '',
                            'args'          => serialize( $args )
                            );

        return $baseInfo;
     }
}

?>
