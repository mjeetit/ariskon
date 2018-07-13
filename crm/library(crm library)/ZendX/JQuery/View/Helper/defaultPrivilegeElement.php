<?php
class ZendX_JQuery_View_Helper_defaultPrivilegeElement extends Zend_View_Helper_FormElement {

    protected $htmlstring = '';
    public $checked = NULL;

    public function defaultPrivilegeElement($name, $privilege_assigned = null, $attribs = null) {
        $DBOBJ = new Zend_Custom();

        $privilege_get =$privilege_assigned;
        $privillagelevel1 = $DBOBJ->treeSubModules();

        $htmlstring = '';

        foreach ($privillagelevel1 as $value) { //print_r($value);die;
            $privillagelevel2 = $DBOBJ->treeSubModules($value['module_id']);
            $this->needle = $value['module_id'];
            $this->checkprivilege($privilege_get);

            $opentag = '<ul style="margin-left:0px !important">';
            $closeparenttag = '</ul>';
            $htmlstring .=$opentag . '<span id="'.$value['module_id'].'"><input type="checkbox" ' . $this->checked . ' name= tree[] id=chk' . $value['module_id'] . ' value=' . $value['module_id'] . ' onclick="showHide(this.value)" class="checkbox">&nbsp;&nbsp;' . $value['module_name'].'</span>';

            if ($this->checked)
                $class = ' class = "show1"';
            else
                $class = ' class = "hidden" ';

            $htmlstring .='<div id= div' . $value['module_id'] . $class . '>';

            foreach ($privillagelevel2 as $value2) {
                $privillagelevel3 = $DBOBJ->treeSubModules($value2['module_id']);
                $this->checked = '';
                $this->needle = $value2['module_id'];
                $this->checkprivilege($privilege_get);

                if ($this->checked)
                    $class = ' class = "show1"';
                else
                    $class = ' class = "hidden" ';

                if (empty($privillagelevel3)) {
                    $opentag = '<ol>';
                    $closetag = '</ol>';
                    $htmlstring .=$opentag . '<span id="'.$value2['module_id'].'"><input type="checkbox"  ' . $this->checked . ' name= tree[] id=chk' . $value2['module_id'] . ' value=' . $value2['module_id'] . ' class="checkbox">&nbsp;&nbsp;' . $value2['module_name'].'</span>';
                } else {
                    $opentag = '<ul>';
                    $closetag = '</ul>';
                    $htmlstring .=$opentag . '<span id="'.$value2['module_id'].'"><input type="checkbox" ' . $this->checked . ' name= tree[] id=chk' . $value2['module_id'] . ' value=' . $value2['module_id'] . ' onclick="showHide(this.value)" class="checkbox">&nbsp;&nbsp;' . $value2['module_name'].'</span>';
                }

                $htmlstring .='<div id= div' . $value2['module_id'] . $class . '>';

                foreach ($privillagelevel3 as $value3) {
                    $this->checked = '';
                    $this->needle = $value3['module_id'];
                    $this->checkprivilege($privilege_get);

                    if ($this->checked)
                        $class = ' class = "show1"';
                    else
                        $class = ' class = "hidden" ';

                    $htmlstring .='<ol><span id="'.$value2['module_id'].'"><input type="checkbox" ' . $this->checked . '  name= tree[] id=chk' . $value3['module_id'] . ' value=' . $value3['module_id'] . ' class="checkbox">&nbsp;&nbsp;' . $value3['module_name'] . '</span></ol>';
                }
                $htmlstring .='</div>';
                $htmlstring .=$closetag;
            }
            $htmlstring .='</div>';
            $htmlstring .=$closeparenttag;
        }
        return $htmlstring;
    }

    public function checkprivilege($priv = '') {//print_r($this->needle);print_r($priv);die;
        if (!empty($priv)) {
            (in_array($this->needle, $priv)) ? $this->checked = 'checked="checked" ' : $this->checked = '';
        }
        return;
    }

}

?>