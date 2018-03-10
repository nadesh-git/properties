<?php

/**
 *  File: calendar.php | (c) dynarch.com 2004
 *  Distributed as part of "The Coolest DHTML Calendar"
 *  under the same terms.
 *  -----------------------------------------------------------------
 *  This file implements a simple PHP wrapper for the calendar.  It
 *  allows you to easily include all the calendar files and setup the
 *  calendar by instantiating and calling a PHP object.
 */

class DHTML_Calendar
{
    public $calendar_lib_path;

    public $calendar_file;
    public $calendar_lang_file;
    public $calendar_setup_file;
    public $calendar_theme_file;
    public $calendar_options = array();

    function __construct($calendar_lib_path = '/calendar/',
                            $lang              = 'en',
                            $theme             = 'calendar-win2k-1',
                            $stripped          = true) {
        if ($stripped) {
            $this->calendar_file = 'calendar_stripped.js';
            $this->calendar_setup_file = 'calendar-setup_stripped.js';
        } else {
            $this->calendar_file = 'calendar.js';
            $this->calendar_setup_file = 'calendar-setup.js';
        }
        $this->calendar_lang_file = 'lang/calendar-' . $lang . '.js';
        $this->calendar_theme_file = $theme.'.css';
        $this->calendar_lib_path = preg_replace('/\/+$/', '/', $calendar_lib_path);
        $this->calendar_options = array('ifFormat' => '%Y/%m/%d',
                                        'daFormat' => '%Y/%m/%d');
    }

    function set_option($name, $value)
    {
        $this->calendar_options[$name] = $value;
    }

    function get_options()
    {
        return $this->calendar_options;
    }

    function _make_calendar($other_options = array())
    {
        $js_options = $this->_make_js_hash(array_merge($this->calendar_options, $other_options));
        return $js_options;
    }

    function make_links()
    {
        $id = $this->_gen_id();
        return array('id' => $this->_field_id($id),
                     'trigger' => $this->_trigger_id($id),
                     );
    }
    function make_script($link=null)
    {
//    echo var_dump($link);exit;
        $options = $this->get_options();
        if (isset($link)) {
            $options = array_merge($options,
                                   array('inputField' => $link['id'],
                                         'button'     => $link['trigger']));
        }
        return $this->_make_calendar($options);
    }

    function make_input_field($cal_options = array(), $field_attributes = array())
    {
        $id = $this->_gen_id();
        $attrstr = $this->_make_html_attr(array_merge($field_attributes,
                                                      array('id'   => $this->_field_id($id),
                                                            'type' => 'text')));
        $string = '<input ' . $attrstr .'/>';
        $string .= '<a href="#" id="'. $this->_trigger_id($id) . '">' .
            '<img align="middle" border="0" src="' . $this->calendar_lib_path . 'img.gif" alt=""/></a>';

        $options = array_merge($cal_options,
                               array('inputField' => $this->_field_id($id),
                                     'button'     => $this->_trigger_id($id)));
        $string .= $this->_make_calendar($options);
        return $string;
    }

    /// PRIVATE SECTION

    function _field_id($id)
    { return 'f-calendar-field-' . $id; }
    function _trigger_id($id)
    { return 'f-calendar-trigger-' . $id; }
    function _gen_id()
    { static $id = 0; return ++$id; }

    function _make_js_hash($array)
    {
        $jstr = '';
        reset($array);
        foreach ($array as $key => $val) {
            if (is_bool($val))
                $val = $val ? 'true' : 'false';
            else if (!is_numeric($val))
                $val = '"'.$val.'"';
            if ($jstr) $jstr .= ',';
            $jstr .= '"' . $key . '":' . $val;
        }
        return $jstr;
    }

    function _make_html_attr($array)
    {
        $attrstr = '';
        reset($array);
        foreach ($array as $key => $val) {
            $attrstr .= $key . '="' . $val . '" ';
        }
        return $attrstr;
    }
};

?>