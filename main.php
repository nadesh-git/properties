<?php
/**
 * Address Property
 * @package math
 * @copyright (C) 2010 Netspan AG
 * @license GPL {@link http://www.gnu.org/licenses/gpl.html}
 * @author Marc Lutolf <mfl@netspan.ch>
 */

sys::import('modules.base.xarproperties.textbox');

class AddressProperty extends TextBoxProperty
{
    public $id         = 30033;
    public $name       = 'address';
    public $desc       = 'Address';
    public $reqmodules = array();

    public $display_show_city         = true;
    public $display_show_province     = true;
    public $display_show_postal_code  = true;
    public $display_show_country      = true;
    public $display_rows              = 2;
    public $display_labels            = array();

    function __construct(ObjectDescriptor $descriptor)
    {
        parent::__construct($descriptor);
        $this->tplmodule = 'auto';
        $this->template =  'address';
        $this->filepath   = 'auto';
    }

    public function checkInput($name = '', $value = null)
    {
        $name = empty($name) ? 'dd_'.$this->id : $name;
        // store the fieldname for validations who need them (e.g. file uploads)
        $this->fieldname = $name;
        if (!isset($value)) {
            $invalid = array();
            $validity = true;
            $value = array();
            $textbox = DataPropertyMaster::getProperty(array('name' => 'textbox'));
            $textbox->validation_min_length = 3;

            $streetvalidity = false;
            for ($i=1;$i<=$this->display_rows;$i++) {
                $isvalid = $textbox->checkInput($name . '_line_' . $i);
                if ($isvalid) {
                    $value['line_' . $i] = $textbox->value;
                }
                $streetvalidity = $streetvalidity || $isvalid;
            }
            $validity = $validity && $streetvalidity;
            if (!$streetvalidity) $invalid[] = 'line_1';

            if ($this->display_show_city) {
                $isvalid = $textbox->checkInput($name . '_city');
                if ($isvalid) {
                    $value['city'] = $textbox->value;
                } else {
                    $invalid[] = 'city';
                }
                $validity = $validity && $isvalid;
            }

            if ($this->display_show_province) {
                $province = DataPropertyMaster::getProperty(array('name' => 'statelisting'));
                $isvalid = $province->checkInput($name . '_province');
                if ($isvalid) {
                    $value['province'] = $province->value;
                } else {
                    $invalid[] = 'province';
                }
                $validity = $validity && $isvalid;
            }

            if ($this->display_show_postal_code) {
                list($isvalid, $value['postal_code']) = $this->fetchValue($name . '_postal_code');
                $validity = $validity && $isvalid;
            }
            
            if ($this->display_show_country) {
                $country = DataPropertyMaster::getProperty(array('name' => 'countrylisting'));
                $isvalid = $country->checkInput($name . '_country');
                if ($isvalid) {
                    $value['country'] = $country->value;
                } else {
                    $invalid[] = 'country';
                }
                $validity = $validity && $isvalid;
            }
            
        }
        if (!empty($invalid)) $this->invalid = implode(',',$invalid);
        $this->setValue($value);
        return $validity;
    }

    public function getValue()
    {
        try {
            $valuearray = unserialize($this->value); 
        } catch (Exception $e) {
            $valuearray = array(); 
        }
        for ($i=1;$i<=$this->display_rows;$i++)
            $valuearray['line_' . $i] = !empty($valuearray['line_' . $i]) ? $valuearray['line_' . $i] : '';
        $valuearray['city'] = !empty($valuearray['city']) ? $valuearray['city'] : '';
        $valuearray['province'] = !empty($valuearray['province']) ? $valuearray['province'] : '';
        $valuearray['postal_code'] = !empty($valuearray['postal_code']) ? $valuearray['postal_code'] : '';
        $valuearray['country'] = !empty($valuearray['country']) ? $valuearray['country'] : '';
        return $valuearray;
    }
    
    public function setValue($value=null) 
    {
        if (empty($value)) $value = array();
        $this->value = serialize($value);
    }

    public function getValueArray() 
    {
        return $this->getValue();
    }

    public function showInput(Array $data = array())
    {
        $data = $this->assemble_address($data);
        return DataProperty::showInput($data);
    }
    public function showOutput(Array $data = array())
    {
        $data = $this->assemble_address($data);
        return DataProperty::showOutput($data);
    }

    private function assemble_address(Array $data = array())
    {
        if (isset($data['rows'])) $this->display_rows = $data['rows'];
        if (!isset($data['labels'])) $data['labels'] = $this->display_labels;
        if (!isset($data['show_city'])) $data['show_city'] = $this->display_show_city;
        if (!isset($data['show_province'])) $data['show_province'] = $this->display_show_province;
        if (!isset($data['show_postal_code'])) $data['show_postal_code'] = $this->display_show_postal_code;
        if (!isset($data['show_country'])) $data['show_country'] = $this->display_show_country;
        if (empty($data['value'])) $data['value'] = $this->getValue();
        return $data;
    }
}

?>