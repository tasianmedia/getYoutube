<?php
/**
 * A simple video retrieval Snippet for MODX Revolution.
 *
 * @author David Pede <dev@tasian.media> <https://twitter.com/davepede>
 * @version 1.1.1-pl
 * @released March 13, 2017
 * @since February 25, 2014
 * @package getyoutube
 *
 * Copyright (C) 2017 David Pede. All rights reserved. <dev@tasian.media>
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
$apiKey = $modx->getOption('getyoutube.api_key',$scriptProperties);
$mode = !empty($mode) ? $mode : ''; //Acceptable values are: channel, video
$channel = !empty($channel) ? $channel : '';
$playlist = !empty($playlist) ? $playlist : '';
$video = !empty($video) ? $video : '';
$tpl = !empty($tpl) ? $tpl : '';
$tplAlt = !empty($tplAlt) ? $tplAlt : '';
$toPlaceholder = !empty($toPlaceholder) ? $toPlaceholder : ''; //Blank default makes '&toPlaceholder' optional
$sortby = !empty($sortby) ? $sortby : ''; //Acceptable values are: date, rating, title, viewCount
$safeSearch = !empty($safeSearch) ? $safeSearch : ''; //Acceptable values are: none, moderate, strict

$limit = !empty($limit) ? $limit : '';
$pageToken = preg_replace('/[^-a-zA-Z0-9_]/','',$_GET['page']); //For pagination
$totalVar = !empty($totalVar) ? $totalVar : '';

include_once ($getyoutube->config['modelPath'] . 'search.class.php');

switch ($mode) {
  case "channel":
    if (!empty($channel)) {
      $query = new search();
      $channelUrl = "https://www.googleapis.com/youtube/v3/search?part=id,snippet&channelId=$channel&type=video&safeSearch=$safeSearch&maxResults=$limit&order=$sortby&pageToken=$pageToken&key=$apiKey";
      $output = $query->channel($channelUrl,$tpl,$tplAlt,$toPlaceholder,$pageToken,$totalVar);
    }else{
      $modx->log(modX::LOG_LEVEL_ERROR, 'getYoutube() - &channel is required');
    }
    break;
  case "playlist":
    if (!empty($playlist)) {
      $query = new search();
      $playlistUrl = "https://www.googleapis.com/youtube/v3/playlistItems?part=id,snippet&playlistId=$playlist&type=video&safeSearch=$safeSearch&maxResults=$limit&order=$sortby&pageToken=$pageToken&key=$apiKey";
      $output = $query->playlist($playlistUrl,$tpl,$tplAlt,$toPlaceholder,$pageToken,$totalVar);
    }else{
      $modx->log(modX::LOG_LEVEL_ERROR, 'getYoutube() - &playlist is required');
    }
    break;
  case "video":
    if (!empty($video)) {
      $query = new search();
      $videoUrl = "https://www.googleapis.com/youtube/v3/videos?part=id,snippet,contentDetails,statistics&id=$video&key=$apiKey";
      $output = $query->video($videoUrl,$tpl,$tplAlt,$toPlaceholder,$totalVar);
    }else{
      $modx->log(modX::LOG_LEVEL_ERROR, 'getYoutube() - &video is required');
    }
    break;
  default: $modx->log(modX::LOG_LEVEL_ERROR, 'getYoutube() - &mode is required'); break;
};

return $output;
