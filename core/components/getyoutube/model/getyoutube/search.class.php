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

class search {
  public function channel($channelUrl,$tpl,$tplAlt,$toPlaceholder,$pageToken,$totalVar){
    global $modx;
    
    $json = file_get_contents($channelUrl)
    or $modx->log(modX::LOG_LEVEL_ERROR, 'getYoutube() - Channel API request not recognised');
    $videos = json_decode($json, TRUE);

    /* SETUP PAGINATION */
    $total = $videos['pageInfo']['totalResults'];
    $modx->setPlaceholder($totalVar,$total);
    $nextPageToken = $videos['nextPageToken'];
    if (!empty($nextPageToken) ? $modx->setPlaceholder('nextPage',$modx->makeUrl($modx->resource->get('id'),'','?page='.$nextPageToken,'full')) : '');
    $prevPageToken = $videos['prevPageToken'];
    if (!empty($prevPageToken) ? $modx->setPlaceholder('prevPage',$modx->makeUrl($modx->resource->get('id'),'','?page='.$prevPageToken,'full')) : '');
    
    $idx = 0; //Starts index at 0
    $total = 0;
    
    $output = '';
    
    foreach($videos['items'] as $video) {
      /* SET PLACEHOLDERS */
      $modx->setPlaceholder('id',$video['id']['videoId']);
      $modx->setPlaceholder('url',"https://www.youtube.com/watch?v=" . $video['id']['videoId']);
      $modx->setPlaceholder('embed_url',"https://www.youtube.com/embed/" . $video['id']['videoId']);
      $modx->setPlaceholder('title',$video['snippet']['title']);
      $modx->setPlaceholder('description',$video['snippet']['description']);
      $modx->setPlaceholder('publish_date',$video['snippet']['publishedAt']);
      $modx->setPlaceholder('thumbnail_small',$video['snippet']['thumbnails']['default']['url']); //120px wide and 90px tall
      $modx->setPlaceholder('thumbnail_medium',$video['snippet']['thumbnails']['medium']['url']); //320px wide and 180px tall
      $modx->setPlaceholder('thumbnail_large',$video['snippet']['thumbnails']['high']['url']); //480px wide and 360px tall
      $modx->setPlaceholder('channel_title',$video['snippet']['channelTitle']);
      /* SET TEMPLATES */
      if (!empty($tplAlt)) {
        if($idx % 2 == 0) { // Checks if index can be divided by 2 (alt)
          $rowTpl = $tpl;
        }else{
          $rowTpl = $tplAlt;
        }
      }else{
        $rowTpl = $tpl;
      }
      $idx++; //Increases index by +1
  
      $results .= $modx->getChunk($rowTpl,$video);
    }
    if(!empty($results)) {
      if (!empty($toPlaceholder)) {
        $output = $modx->setPlaceholder($toPlaceholder,$results); //Set '$toPlaceholder' placeholder
      }else{
        $output = $results;
      }
    }
    return $output;
  }
  public function playlist($playlistUrl,$tpl,$tplAlt,$toPlaceholder,$pageToken,$totalVar){
    global $modx;
    
    $json = file_get_contents($playlistUrl)
    or $modx->log(modX::LOG_LEVEL_ERROR, 'getYoutube() - Playlist API request not recognised');
    $videos = json_decode($json, TRUE);

    /* SETUP PAGINATION */
    $total = $videos['pageInfo']['totalResults'];
    $modx->setPlaceholder($totalVar,$total);
    $nextPageToken = $videos['nextPageToken'];
    if (!empty($nextPageToken) ? $modx->setPlaceholder('nextPage',$modx->makeUrl($modx->resource->get('id'),'','?page='.$nextPageToken,'full')) : '');
    $prevPageToken = $videos['prevPageToken'];
    if (!empty($prevPageToken) ? $modx->setPlaceholder('prevPage',$modx->makeUrl($modx->resource->get('id'),'','?page='.$prevPageToken,'full')) : '');
    
    $idx = 0; //Starts index at 0
    $total = 0;
    
    $output = '';
    
    foreach($videos['items'] as $video) {
      /* SET PLACEHOLDERS */
      $modx->setPlaceholder('id',$video['snippet']['resourceId']['videoId']);
      $modx->setPlaceholder('url',"https://www.youtube.com/watch?v=" . $video['snippet']['resourceId']['videoId']);
      $modx->setPlaceholder('title',$video['snippet']['title']);
      $modx->setPlaceholder('description',$video['snippet']['description']);
      $modx->setPlaceholder('publish_date',$video['snippet']['publishedAt']);
      $modx->setPlaceholder('thumbnail_small',$video['snippet']['thumbnails']['default']['url']); //120px wide and 90px tall
      $modx->setPlaceholder('thumbnail_medium',$video['snippet']['thumbnails']['medium']['url']); //320px wide and 180px tall
      $modx->setPlaceholder('thumbnail_large',$video['snippet']['thumbnails']['high']['url']); //480px wide and 360px tall
      $modx->setPlaceholder('channel_title',$video['snippet']['channelTitle']);
      $modx->setPlaceholder('playlist_id',$video['snippet']['playlistId']);
      /* SET TEMPLATES */
      if (!empty($tplAlt)) {
        if($idx % 2 == 0) { // Checks if index can be divided by 2 (alt)
          $rowTpl = $tpl;
        }else{
          $rowTpl = $tplAlt;
        }
      }else{
        $rowTpl = $tpl;
      }
      $idx++; //Increases index by +1
  
      $results .= $modx->getChunk($rowTpl,$video);
    }
    if(!empty($results)) {
      if (!empty($toPlaceholder)) {
        $output = $modx->setPlaceholder($toPlaceholder,$results); //Set '$toPlaceholder' placeholder
      }else{
        $output = $results;
      }
    }
    return $output;
  }
  public function video($videoUrl,$tpl,$tplAlt,$toPlaceholder,$totalVar){
    global $modx;
    
    $json = file_get_contents($videoUrl)
    or $modx->log(modX::LOG_LEVEL_ERROR, 'getYoutube() - Video API request not recognised');
    $videos = json_decode($json, TRUE);
    
    /* SET TOTAL PLACEHOLDERS */
    $total = $videos['pageInfo']['totalResults'];
    $modx->setPlaceholder($totalVar,$total);
    
    $idx = 0; //Starts index at 0
    $total = 0;
    
    $output = '';
    
    foreach($videos['items'] as $video) {
      /* SET SNIPPET PLACEHOLDERS */
      $modx->setPlaceholder('id',$video['id']);
      $modx->setPlaceholder('url',"https://www.youtube.com/watch?v=" . $video['id']);
      $modx->setPlaceholder('embed_url',"https://www.youtube.com/embed/" . $video['id']);
      $modx->setPlaceholder('title',$video['snippet']['title']);
      $modx->setPlaceholder('description',$video['snippet']['description']);
      $modx->setPlaceholder('publish_date',$video['snippet']['publishedAt']);
      $modx->setPlaceholder('thumbnail_small',$video['snippet']['thumbnails']['default']['url']); //120px wide and 90px tall
      $modx->setPlaceholder('thumbnail_medium',$video['snippet']['thumbnails']['medium']['url']); //320px wide and 180px tall
      $modx->setPlaceholder('thumbnail_large',$video['snippet']['thumbnails']['high']['url']); //480px wide and 360px tall
      $modx->setPlaceholder('channel_title',$video['snippet']['channelTitle']);
      /* SET CONTENT DETAIL PLACEHOLDERS */
      $modx->setPlaceholder('duration',$video['contentDetails']['duration']);
      /* SET STATISTIC PLACEHOLDERS */
      $modx->setPlaceholder('viewCount',$video['statistics']['viewCount']);
      $modx->setPlaceholder('likeCount',$video['statistics']['likeCount']);
      $modx->setPlaceholder('dislikeCount',$video['statistics']['dislikeCount']);
      $modx->setPlaceholder('favoriteCount',$video['statistics']['favoriteCount']);
      $modx->setPlaceholder('commentCount',$video['statistics']['commentCount']);
      /* SET TEMPLATES */
      if (!empty($tplAlt)) {
        if($idx % 2 == 0) { // Checks if index can be divided by 2 (alt)
          $rowTpl = $tpl;
        }else{
          $rowTpl = $tplAlt;
        }
      }else{
        $rowTpl = $tpl;
      }
      $idx++; //Increases index by +1
  
      $results .= $modx->getChunk($rowTpl,$video);
    }
    if(!empty($results)) {
      if (!empty($toPlaceholder)) {
        $output = $modx->setPlaceholder($toPlaceholder,$results); //Set '$toPlaceholder' placeholder
      }else{
        $output = $results;
      }
    }
    return $output;
  }
}
