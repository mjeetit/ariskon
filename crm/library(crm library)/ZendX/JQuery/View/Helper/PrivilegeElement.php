<?php
class ZendX_JQuery_View_Helper_privilegeElement extends Zend_View_Helper_FormElement {

    protected $htmlstring = '';
    public $checked = NULL;

    public function privilegeElement($name, $privilege_assigned = null, $attribs = null) {
        $DBOBJ = new Zend_Custom();
		?>
		<style type="text/css">
	     div.hidden{
				display:none;
			}
			
			div.hidden ol, ul{ margin:5px 0px 0px 25px;}
			div.show1 ol, ul{ margin:5px 0px 0px 25px; }
			.hideText{display:none;}
		</style>
		<?php

        $privilege_get =$privilege_assigned;
        //print_r($privilege_get);die;
        $privillagelevel1 = $DBOBJ->getModules();

        $htmlstring = '';

        foreach ($privillagelevel1 as $value) { //print_r($value);die;
            $privillagelevel2 = $DBOBJ->getModules($value['module_id']);
            $this->needle = $value['module_id'];
            $this->checkprivilege($privilege_get);

            $opentag = '<ul style="margin-left:0px !important">';
            $closeparenttag = '</ul>';
            $htmlstring .=$opentag . '<input type="checkbox" ' . $this->checked . ' name= tree[] id=chk' . $value['module_id'] . ' value=' . $value['module_id'] . ' onclick="showHide(this.value)" class="look">&nbsp;&nbsp;' . $value['module_name'];
            //$htmlstring .='<div id= div' . $value['module_id'] . (!$this->checked)? ' class="hidden">';

            if ($this->checked)
                $class = ' class = "show1"';
            else
                $class = ' class = "hidden" ';

            $htmlstring .='<div id= div' . $value['module_id'] . $class . '>';

            foreach ($privillagelevel2 as $value2) {
                $privillagelevel3 = $DBOBJ->getModules($value2['module_id']);
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
                    $htmlstring .=$opentag . '<input type="checkbox"  ' . $this->checked . ' name= tree[] id=chk' . $value2['module_id'] . ' value=' . $value2['module_id'] . ' class="look">&nbsp;&nbsp;' . $value2['module_name'];
                } else {
                    $opentag = '<ul>';
                    $closetag = '</ul>';
                    $htmlstring .=$opentag . '<input type="checkbox" ' . $this->checked . ' name= tree[] id=chk' . $value2['module_id'] . ' value=' . $value2['module_id'] . ' onclick="showHide(this.value)" class="look">&nbsp;&nbsp;' . $value2['module_name'];
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

                    $htmlstring .='<ol><input type="checkbox" ' . $this->checked . '  name= tree[] id=chk' . $value3['module_id'] . ' value=' . $value3['module_id'] . ' class="look">&nbsp;&nbsp;' . $value3['module_name'] . '</ol>';
                }
                $htmlstring .='</div>';
                $htmlstring .=$closetag;
            }
            $htmlstring .='</div>';
            $htmlstring .=$closeparenttag;
        }
        return $htmlstring;
    }

    public function checkprivilege($priv = '') {
    //print_r($this->needle);print_r($priv);die;
        if (!empty($priv)) {
            (in_array($this->needle, $priv)) ? $this->checked = 'checked="checked" ' : $this->checked = '';
        }
        return;
    }

}

?>