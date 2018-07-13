<?php

class Zend_Form_Decorator_SimpleTable extends Zend_Form_Decorator_Abstract {

    public function render($content) {
        $element = $this->getElement();
        $view = $element->getView();
        if (null === $view) {
            return $content;
        }

        $columns = $this->getOption('columns');

        $isrow = $this->search($columns, 'Row', 'tr');
        $row = '';
        if (!empty($isrow)) {
            $row = array_shift($columns);
        }


        $class = $this->getOption('class');
        $id = $this->getOption('id');

        $columns_html = '';
        $td = '';
        $align = '';
        $label = '';
        $opentr = '';
        $closetr = '';

        if (!empty($row)) {
            $opentr = '<' . $row['Row'] . '>';
            $closetr = '</' . $row['Row'] . '>';
            $columns_html .= $opentr;
        }
        foreach ($columns as $current_column_name) {
            $element = '';
            if (is_array($current_column_name)) {
                if (array_key_exists('CreateElement', $current_column_name)) {

                    $labelname = '';
                    $label = $current_column_name['Label'];
                    unset($current_column_name['Label']);
                    $tempelement = array_pop($current_column_name);
                    $tdelement = $this->createElement($current_column_name);
                    $labelname = $this->createElement($tempelement);
                    $labelname = $label . $labelname;

                    if (array_key_exists('span', $current_column_name)) {
                        $labelname .= $this->createSpan($current_column_name['span']);
                    }
                } elseif (array_key_exists('anchor', $current_column_name)) {
                    $anchor = $this->createAnchor($current_column_name['anchor']);
                } else {
                    $labelname = $current_column_name['Label'];
                    unset($current_column_name['Label']);
                    $tdelement = $this->createElement($current_column_name);
                }
                $columns_html .= $tdelement . $labelname . $anchor;
                $anchor = '';
                $labelname = '';
            }
        }
        $columns_html .=$closetr;
        $result = $columns_html;
        return $result;
    }

    public function createSpan($arr) {
        $ele = '<br><span';
        //$msg = array_pop($arr);
        if(array_key_exists('message',$arr))
                $msg = array_pop($arr);
        
        foreach ($arr as $key => $value) {
            $ele .= ' ' . $key . '="' . $value . '"';
        }
        $ele .= '>';
        $ele .= (!empty($msg)) ? $msg:'';
        $ele .= '</span>';
        return $ele;
    }

    public function createAnchor($arr) {
        $ele = '<a ';
        if(array_key_exists('message',$arr))
                $msg = array_pop($arr);
				
        foreach ($arr as $key => $value) {
            $ele .= ' ' . $key . '="' . $value . '"';
        }
        $ele .= '>';
        $ele .= (!empty($msg)) ? $msg : NULL;
        $ele .= '</a>';
        return $ele;
    }

    /**
      function to create radio button,checkbox etc.
     * */
    public function createElement($arr) {
        $ele = '';
        foreach ($arr as $key => $value) {//echo '<pre>'.$key;print_r($value);echo '</pre>';
            $ele .= $this->html_element($key, $value);
        }
        $ele .= $this->close_html(!empty($arr['type']) ? $arr['type'] : ($arr['tag']) ? $arr['tag'] : $arr['img']);
        return $ele;
    }

    public function search($array, $key, $value) {
        $results = array();

        $this->search_r($array, $key, $value, $results);

        return $results;
    }

    public function search_r($array, $key, $value, &$results) {
        if (!is_array($array))
            return;

        if ($array[$key] == $value)
            $results[] = $array;

        foreach ($array as $subarray)
            $this->search_r($subarray, $key, $value, $results);
    }

    public function html_element($key, $value) {//echo $key.'<br>';
        switch ($key) {
            case 'type':
                $val = '<input type = "' . $value . '"';
                break;
            case 'name':
                $val = ' name = "' . $value . '"';
                break;
            case 'id':
                $val = ' id = "' . $value . '"';
                break;
            case 'size':
                $val = ' size = "' . $value . '"';
                break;
            case 'class':
                $val = ' class = "' . $value . '"';
                break;
            case 'style':
                $val = ' style = "' . $value . '"';
                break;
            case 'value':
                $val = ' value = "' . $value . '"';
                break;
            case 'maxlength':
                $val = ' maxlength = "' . $value . '"';
                break;
            case 'title':
                $val = ' title = "' . $value . '"';//
                break;
            case 'onclick':
                $val = ' onclick = "' . $value . '"';
                break;
            case 'onblur':
                $val = ' onblur = "' . $value . '"';
                break;
            case 'onsubmit':
                $val = ' onsubmit = "' . $value . '"';
                break;
            case 'onkeypress':
                $val = ' onkeypress = "' . $value . '"';
                break;
            case 'requiredPrefix':
                $val = ' requiredPrefix = "' . $value . '"';
                break;
            case 'disabled':
                $val = ' disabled = "' . $value . '"';
                break;
            case 'select':
                $val = '<' . $value;
                break;
            case 'href':
                $val = '< a href ="' . $value . '"';
                break;
            case 'colspan':
                $val = ' colspan ="' . $value . '"';
                break;
            case 'width':
                $val = ' width ="' . $value . '"';
                break;
            case 'valign':
                $val = ' valign ="' . $value . '"';
                break;
            case 'tag':
                $val = '<' . $value;
                break;
            case 'align':
                $val = ' align ="' . $value . '"';
                break;
            case 'message':
                $val = ' message ="' . $value . '"';
                break;
            case 'Label':
                $val = $value;
                break;
            case 'img':
                $val = '<img src =';
                break;
            case 'src':
                $val = '"' . $value . '"';
                break;
            case 'option':
                $val = '>"' . $value . '"';
                break;
            case 'span':
                $val = ' span ="' . $value . '"';//print_r($val);die;
                break;
            case 'style':
                $val = '>"' . $value . '"';
                break;
			case 'onChange':
                $val = ' onChange = '. $value . '';
                break;
			case 'onkeypress':
                $val = ' onkeypress = '. $value . '';
                break;
        }

        return $val;
    }

    public function close_html($type) {
        switch ($type) {
            case 'select':
                $close_ele = '</select>';
                break;
            case 'td':
                $close_ele = '</td>';
                break;
            case 'img':
                $close_ele = '/>'; 
                break;
            default:
                $close_ele = '/>';
                break;
        }
        return $close_ele;
    }

}