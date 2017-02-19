<?php
/**
 * DateTime Property
 *
 * @package properties
 * @subpackage datetime property
 * @category Third Party Xaraya Property
 * @version 1.0.0
 * @copyright (C) 2011 Netspan AG
 * @license GPL {@link http://www.gnu.org/licenses/gpl.html}
 * @author Marc Lutolf <mfl@netspan.ch>
 */

function datetime_formats()
{
    $formats[1] = array('id' => 1, 'name' => 'Universal - short', 'format' => 'Y-m-d H:i', 'useoffset' => 0);
    $formats[2] = array('id' => 2, 'name' => 'Universal - long', 'format' => 'Y-m-d H:i O', 'useoffset' => 0);
    $formats[3] = array('id' => 3, 'name' => 'US - short', 'format' => 'm-d-Y h:i A', 'useoffset' => 0);
    $formats[4] = array('id' => 4, 'name' => 'US - long', 'format' => 'm-d-Y h:i A O', 'useoffset' => 0);
    $formats[5] = array('id' => 5, 'name' => 'Euro - short', 'format' => 'd.m.Y H:i', 'useoffset' => 0);
    $formats[6] = array('id' => 6, 'name' => 'Euro - long', 'format' => 'd.m.Y H:i O', 'useoffset' => 0);
    $formats[7] = array('id' => 6, 'name' => 'Internet time', 'format' => 'B', 'useoffset' => 0);

    return $formats;
}

?>