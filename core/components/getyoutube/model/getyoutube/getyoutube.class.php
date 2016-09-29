<?php
/**
 * @package getyoutube
 *
 * Copyright (C) 2016 David Pede. All rights reserved. <dev@tasian.media>
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

class getYoutube {
    public $modx;
    public $config = array();
    function __construct(modX &$modx,array $config = array()) {
        $this->modx =& $modx;
 
        $corePath = $this->modx->getOption('getyoutube.core_path',$config,$this->modx->getOption('core_path').'components/getyoutube/');
        //$assetsUrl = $this->modx->getOption('getyoutube.assets_url',$config,$this->modx->getOption('assets_url').'components/getyoutube/');
        $this->config = array_merge(array(
            'basePath' => $corePath,
            'corePath' => $corePath,
            'modelPath' => $corePath.'model/getyoutube/',
            //'processorsPath' => $corePath.'processors/',
            //'templatesPath' => $corePath.'templates/',
            'chunksPath' => $corePath.'elements/chunks/',
            //'jsUrl' => $assetsUrl.'js/',
            //'cssUrl' => $assetsUrl.'css/',
            //'assetsUrl' => $assetsUrl,
            //'connectorUrl' => $assetsUrl.'connector.php',
        ),$config);
 
    }
}