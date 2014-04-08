<?php
/**
 * A simple video retrieval Snippet for MODX Revolution.
 *
 * @author David Pede <dev@tasianmedia.com> <https://twitter.com/davepede>
 * @version 1.0.0-beta1
 * @released February 25, 2014
 * @since February 25, 2014
 * @package getyoutube
 *
 * Copyright (C) 2014 David Pede. All rights reserved. <dev@tasianmedia.com>
 *
 * getYoutube is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or any later version.

 * getYoutube is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License along with
 * getYoutube; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 */

$getyoutube = $modx->getService('getyoutube','getYoutube',$modx->getOption('getyoutube.core_path',null,$modx->getOption('core_path').'components/getyoutube/').'model/getyoutube/',$scriptProperties);
if (!($getyoutube instanceof getYoutube)) return '';
 
/* set default properties */
$apiKey = $modx->getOption('apiKey',$scriptProperties);
$channel = !empty($channel) ? "&channelId=" . $channel : '';
//$id = !empty($id) ? $id : '';
$tpl = !empty($tpl) ? $tpl : 'videoRowTpl';
$tplAlt = !empty($tplAlt) ? $tplAlt : '';
//$tplWrapper = !empty($tplWrapper) ? $tplWrapper : ''; //Blank default makes '&tplWrapper' optional
//$toPlaceholder = !empty($toPlaceholder) ? $toPlaceholder : ''; //Blank default makes '&toPlaceholder' optional
$sortby = !empty($sortby) ? $sortby : 'date'; //Acceptable values are: date, rating, title, viewCount
$safeSearch = !empty($safeSearch) ? $safeSearch : 'none'; //Acceptable values are: none, moderate, strict
$videoDefinition = !empty($videoDefinition) ? $videoDefinition : 'any'; //Acceptable values are: any, standard, high

$limit = !empty($limit) ? $limit : '50';
$pageToken = preg_replace('/[^-a-zA-Z0-9_]/','',$_GET['page']); //For pagination
$totalVar = !empty($totalVar) ? $totalVar : 'total';

require ($getyoutube->config['modelPath'] . 'search.class.php');
$query = new search();
$channelUrl = "https://www.googleapis.com/youtube/v3/search?part=id,snippet$channel&type=video&safeSearch=$safeSearch&videoDefinition=$videoDefinition&maxResults=$limit&order=$sortby&pageToken=$pageToken&key=$apiKey";
$output = $query->channel($channelUrl,$tpl,$tplAlt,$pageToken,$totalVar);

return $output;