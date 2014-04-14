<?php
/**
 * @package getyoutube
 *
 * Copyright (C) 2014 David Pede. All rights reserved. <dev@tasianmedia.com>
 *
 * getYoutube is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or any later version.
 *
 * getYoutube is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * getYoutube; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 */
/**
 * @subpackage build
 */

$settings = array();

$settings['getyoutube.api_key']= $modx->newObject('modSystemSetting');
$settings['getyoutube.api_key']->fromArray(array(
    'key' => 'getyoutube.api_key',
    'value' => '',
    'xtype' => 'textfield',
    'namespace' => 'getyoutube',
    'area' => 'api',
),'',true,true);

return $settings;