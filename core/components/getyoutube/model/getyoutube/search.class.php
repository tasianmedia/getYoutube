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

class search {
  public function channel($channelUrl,$tpl,$tplAlt,$pageToken,$totalVar){
    global $modx;
    
    $json = file_get_contents($channelUrl);
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
    
    //if (!empty($id)) {
    foreach($videos['items'] as $video) {
      /* SET PLACEHOLDERS */
      $modx->setPlaceholder('id',$video['id']['videoId']);
      $modx->setPlaceholder('url',"https://www.youtube.com/watch?v=" . $video['id']['videoId']);
      $modx->setPlaceholder('title',$video['snippet']['title']);
      $modx->setPlaceholder('description',$video['snippet']['description']);
      $modx->setPlaceholder('publish_date',$video['snippet']['publishedAt']);
      $modx->setPlaceholder('thumbnail_small',$video['snippet']['thumbnails']['default']['url']);
      $modx->setPlaceholder('thumbnail_medium',$video['snippet']['thumbnails']['medium']['url']);
      $modx->setPlaceholder('thumbnail_large',$video['snippet']['thumbnails']['high']['url']);
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
      $modx->setPlaceholder('idx',$idx);
  
      $output .= $modx->getChunk($rowTpl,$video);
    }
    return $output;
  }
}