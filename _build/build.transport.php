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

$mtime = microtime();
$mtime = explode(" ", $mtime);
$mtime = $mtime[1] + $mtime[0];
$tstart = $mtime;
set_time_limit(0);

/* define package names */
define('PKG_NAME','getYoutube');
define('PKG_NAME_LOWER','getyoutube');
define('PKG_VERSION','1.0.0');
define('PKG_RELEASE','pl');

/* define sources */
$root = dirname(dirname(__FILE__)).'/';
$sources = array(
  'root' => $root,
  'build' => $root . '_build/',
  'data' => $root . '_build/data/', //Files used to fetch Snippets, Chunks etc from elements folder 
  'resolvers' => $root . '_build/resolvers/',
  'chunks' => $root.'core/components/'.PKG_NAME_LOWER.'/elements/chunks/',
  'snippets' => $root.'core/components/'.PKG_NAME_LOWER.'/elements/snippets/',
  'lexicon' => $root . 'core/components/'.PKG_NAME_LOWER.'/lexicon/',
  'docs' => $root.'core/components/'.PKG_NAME_LOWER.'/docs/',
  'elements' => $root.'core/components/'.PKG_NAME_LOWER.'/elements/',
  'source_assets' => $root.'assets/components/'.PKG_NAME_LOWER,
  'source_core' => $root.'core/components/'.PKG_NAME_LOWER,
);

unset($root);

/* instantiate MODx */
require_once $sources['build'].'build.config.php';
require_once MODX_CORE_PATH . 'model/modx/modx.class.php';
$modx= new modX();
$modx->initialize('mgr');
echo '<pre>'; //used for nice formatting of log messages
$modx->setLogLevel(modX::LOG_LEVEL_INFO);
$modx->setLogTarget('ECHO');

/* load builder */
$modx->loadClass('transport.modPackageBuilder','',false, true);
$builder = new modPackageBuilder($modx);
$builder->createPackage(PKG_NAME_LOWER,PKG_VERSION,PKG_RELEASE);
$builder->registerNamespace(PKG_NAME_LOWER,false,true,'{core_path}components/'.PKG_NAME_LOWER.'/');

/* create category */
$category= $modx->newObject('modCategory');
$category->set('id',1);
$category->set('category',PKG_NAME);

/* add snippets */
$snippets = include $sources['data'].'transport.snippets.php';
if (!is_array($snippets)) {
  $modx->log(modX::LOG_LEVEL_ERROR,'Could not package in snippets.');
} else {
  $category->addMany($snippets);
  $modx->log(modX::LOG_LEVEL_INFO,'Packaged in '.count($snippets).' snippets.');
}

/* add chunks */
$chunks = include $sources['data'].'transport.chunks.php';
if (!is_array($chunks)) {
  $modx->log(modX::LOG_LEVEL_ERROR,'Could not package in chunks.');
} else {
  $category->addMany($chunks);
  $modx->log(modX::LOG_LEVEL_INFO,'Packaged in '.count($chunks).' chunks.');
}

/* create category vehicle */
$attr = array(
  xPDOTransport::UNIQUE_KEY => 'category',
  xPDOTransport::PRESERVE_KEYS => false,
  xPDOTransport::UPDATE_OBJECT => true,
  xPDOTransport::RELATED_OBJECTS => true,
  xPDOTransport::RELATED_OBJECT_ATTRIBUTES => array (
    'Snippets' => array(
      xPDOTransport::PRESERVE_KEYS => false,
      xPDOTransport::UPDATE_OBJECT => true,
      xPDOTransport::UNIQUE_KEY => 'name',
    ),
    'Chunks' => array(
      xPDOTransport::PRESERVE_KEYS => false,
      xPDOTransport::UPDATE_OBJECT => true,
      xPDOTransport::UNIQUE_KEY => 'name',
    ),
  ),
);
$vehicle = $builder->createVehicle($category,$attr);

/* add the file resolvers */
$modx->log(modX::LOG_LEVEL_INFO,'Adding file resolvers to category...');
$vehicle->resolve('file',array(
  'source' => $sources['source_core'],
  'target' => "return MODX_CORE_PATH . 'components/';",
));
/*$vehicle->resolve('file',array(
  'source' => $sources['source_assets'],
  'target' => "return MODX_ASSETS_PATH . 'components/';",
));*/

$builder->putVehicle($vehicle);

/* load system settings */
$settings = include $sources['data'].'transport.settings.php';
if (!is_array($settings)) {
    $modx->log(modX::LOG_LEVEL_ERROR,'Could not package in settings.');
} else {
    $attributes= array(
        xPDOTransport::UNIQUE_KEY => 'key',
        xPDOTransport::PRESERVE_KEYS => true,
        xPDOTransport::UPDATE_OBJECT => false,
    );
    foreach ($settings as $setting) {
        $vehicle = $builder->createVehicle($setting,$attributes);
        $builder->putVehicle($vehicle);
    }
    $modx->log(modX::LOG_LEVEL_INFO,'Packaged in '.count($settings).' System Settings.');
}
unset($settings,$setting,$attributes);

/* pack in the license file, readme and changelog */
$builder->setPackageAttributes(array(
  'license' => file_get_contents($sources['docs'] . 'license.txt'),
  'readme' => file_get_contents($sources['docs'] . 'readme.txt'),
  'changelog' => file_get_contents($sources['docs'] . 'changelog.txt'),
));

/* zip up package */
$modx->log(modX::LOG_LEVEL_INFO,'Packing up transport package zip...');
$builder->pack();

$mtime= microtime();
$mtime= explode(" ", $mtime);
$mtime= $mtime[1] + $mtime[0];
$tend= $mtime;
$totalTime= ($tend - $tstart);
$totalTime= sprintf("%2.4f s", $totalTime);

$modx->log(modX::LOG_LEVEL_INFO,"\n<br />Package Built.<br />\nExecution time: {$totalTime}\n");

session_write_close();
exit();