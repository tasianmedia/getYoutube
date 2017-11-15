<?php
/**
 * @package getyoutube
 *
 * Copyright (C) 2017 David Pede. All rights reserved. <dev@tasian.media>
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

class Search {

  /** @var modX $modx */
  private $modx;
  public function __construct(modX &$modx) {
    $this->modx =& $modx;
  }

  /**
   * CURL request and return data.
   *
   * @param string $url The url to fetch.
   * @return mixed $data The data returned.
   */
  public function file_get_contents_curl($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
  }

  public function channel($channelUrl,$tpl,$tplAlt,$toPlaceholder,$pageToken,$totalVar){

    $json = $this->file_get_contents_curl($channelUrl)
      or $this->modx->log(modX::LOG_LEVEL_ERROR, 'getYoutube() - Channel API request not recognised');
    $videos = json_decode($json, TRUE);

    /* SETUP PAGINATION */
    $total = $videos['pageInfo']['totalResults'];
    $this->modx->setPlaceholder($totalVar,$total);
    $nextPageToken = $videos['nextPageToken'];
    if (!empty($nextPageToken) ? $this->modx->setPlaceholder('nextPage',$this->modx->makeUrl($this->modx->resource->get('id'),'','?page='.$nextPageToken,'full')) : '');
    $prevPageToken = $videos['prevPageToken'];
    if (!empty($prevPageToken) ? $this->modx->setPlaceholder('prevPage',$this->modx->makeUrl($this->modx->resource->get('id'),'','?page='.$prevPageToken,'full')) : '');

    $idx = 0; //Starts index at 0
    $total = 0;

    $output = '';

    foreach($videos['items'] as $video) {
      /* SET PLACEHOLDERS */
      $this->modx->setPlaceholder('id',$video['id']['videoId']);
      $this->modx->setPlaceholder('url',"https://www.youtube.com/watch?v=" . $video['id']['videoId']);
      $this->modx->setPlaceholder('embed_url',"https://www.youtube.com/embed/" . $video['id']['videoId']);
      $this->modx->setPlaceholder('title',$video['snippet']['title']);
      $this->modx->setPlaceholder('channel_title',$video['snippet']['channelTitle']);
      $this->modx->setPlaceholder('description',$video['snippet']['description']);
      $this->modx->setPlaceholder('publish_date',$video['snippet']['publishedAt']);
      $this->modx->setPlaceholder('thumbnail_small',$video['snippet']['thumbnails']['default']['url']); //120px wide and 90px tall
      $this->modx->setPlaceholder('thumbnail_medium',$video['snippet']['thumbnails']['medium']['url']); //320px wide and 180px tall
      $this->modx->setPlaceholder('thumbnail_large',$video['snippet']['thumbnails']['high']['url']); //480px wide and 360px tall
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

      $results .= $this->modx->getChunk($rowTpl,$video);
    }
    if(!empty($results)) {
      if (!empty($toPlaceholder)) {
        $output = $this->modx->setPlaceholder($toPlaceholder,$results); //Set '$toPlaceholder' placeholder
      }else{
        $output = $results;
      }
    }
    return $output;
  }
  public function playlist($playlistUrl,$tpl,$tplAlt,$toPlaceholder,$pageToken,$totalVar){

    $json = $this->file_get_contents_curl($playlistUrl)
      or $this->modx->log(modX::LOG_LEVEL_ERROR, 'getYoutube() - Playlist API request not recognised');
    $videos = json_decode($json, TRUE);

    /* SETUP PAGINATION */
    $total = $videos['pageInfo']['totalResults'];
    $this->modx->setPlaceholder($totalVar,$total);
    $nextPageToken = $videos['nextPageToken'];
    if (!empty($nextPageToken) ? $this->modx->setPlaceholder('nextPage',$this->modx->makeUrl($this->modx->resource->get('id'),'','?page='.$nextPageToken,'full')) : '');
    $prevPageToken = $videos['prevPageToken'];
    if (!empty($prevPageToken) ? $this->modx->setPlaceholder('prevPage',$this->modx->makeUrl($this->modx->resource->get('id'),'','?page='.$prevPageToken,'full')) : '');

    $idx = 0; //Starts index at 0
    $total = 0;

    $output = '';

    foreach($videos['items'] as $video) {
      /* SET PLACEHOLDERS */
      $this->modx->setPlaceholder('id',$video['snippet']['resourceId']['videoId']);
      $this->modx->setPlaceholder('playlist_id',$video['snippet']['playlistId']);
      $this->modx->setPlaceholder('url',"https://www.youtube.com/watch?v=" . $video['snippet']['resourceId']['videoId']);
      $this->modx->setPlaceholder('embed_url',"https://www.youtube.com/embed/" . $video['snippet']['resourceId']['videoId']);
      $this->modx->setPlaceholder('title',$video['snippet']['title']);
      $this->modx->setPlaceholder('channel_title',$video['snippet']['channelTitle']);
      $this->modx->setPlaceholder('description',$video['snippet']['description']);
      $this->modx->setPlaceholder('publish_date',$video['snippet']['publishedAt']);
      $this->modx->setPlaceholder('thumbnail_small',$video['snippet']['thumbnails']['default']['url']); //120px wide and 90px tall
      $this->modx->setPlaceholder('thumbnail_medium',$video['snippet']['thumbnails']['medium']['url']); //320px wide and 180px tall
      $this->modx->setPlaceholder('thumbnail_large',$video['snippet']['thumbnails']['high']['url']); //480px wide and 360px tall
      $this->modx->setPlaceholder('thumbnail_standard',$video['snippet']['thumbnails']['standard']['url']); //640px wide and 480px tall
      $this->modx->setPlaceholder('thumbnail_maxres',$video['snippet']['thumbnails']['maxres']['url']); //1280px wide and 720px tall

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

      $results .= $this->modx->getChunk($rowTpl,$video);
    }
    if(!empty($results)) {
      if (!empty($toPlaceholder)) {
        $output = $this->modx->setPlaceholder($toPlaceholder,$results); //Set '$toPlaceholder' placeholder
      }else{
        $output = $results;
      }
    }
    return $output;
  }
  public function video($videoUrl,$tpl,$tplAlt,$toPlaceholder,$totalVar){

    $json = $this->file_get_contents_curl($videoUrl) or $this->modx->log(modX::LOG_LEVEL_ERROR, 'getYoutube() - Video API request not recognised');
    $videos = json_decode($json, TRUE);

    /* SET TOTAL PLACEHOLDERS */
    $total = $videos['pageInfo']['totalResults'];
    $this->modx->setPlaceholder($totalVar,$total);
    $idx = 0; //Starts index at 0
    $total = 0;

    $output = '';

    foreach($videos['items'] as $video) {
      /* SET SNIPPET PLACEHOLDERS */
      $this->modx->setPlaceholder('id',$video['id']);
      $this->modx->setPlaceholder('url',"https://www.youtube.com/watch?v=" . $video['id']);
      $this->modx->setPlaceholder('embed_url',"https://www.youtube.com/embed/" . $video['id']);
      $this->modx->setPlaceholder('title',$video['snippet']['title']);
      $this->modx->setPlaceholder('channel_title',$video['snippet']['channelTitle']);
      $this->modx->setPlaceholder('description',$video['snippet']['description']);
      $this->modx->setPlaceholder('publish_date',$video['snippet']['publishedAt']);
      $this->modx->setPlaceholder('tags',implode(", ", $video['snippet']['tags']));
      /* SET IMAGE PLACEHOLDERS */
      $this->modx->setPlaceholder('thumbnail_small',$video['snippet']['thumbnails']['default']['url']); //120px wide and 90px tall
      $this->modx->setPlaceholder('thumbnail_medium',$video['snippet']['thumbnails']['medium']['url']); //320px wide and 180px tall
      $this->modx->setPlaceholder('thumbnail_large',$video['snippet']['thumbnails']['high']['url']); //480px wide and 360px tall
      $this->modx->setPlaceholder('thumbnail_standard',$video['snippet']['thumbnails']['standard']['url']); //640px wide and 480px tall
      $this->modx->setPlaceholder('thumbnail_maxres',$video['snippet']['thumbnails']['maxres']['url']); //1280px wide and 720px tall
      /* SET CONTENT DETAIL PLACEHOLDERS */
      $this->modx->setPlaceholder('duration',$video['contentDetails']['duration']);
      /* SET STATISTIC PLACEHOLDERS */
      $this->modx->setPlaceholder('viewCount',$video['statistics']['viewCount']);
      $this->modx->setPlaceholder('likeCount',$video['statistics']['likeCount']);
      $this->modx->setPlaceholder('dislikeCount',$video['statistics']['dislikeCount']);
      $this->modx->setPlaceholder('favoriteCount',$video['statistics']['favoriteCount']);
      $this->modx->setPlaceholder('commentCount',$video['statistics']['commentCount']);
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

      $results .= $this->modx->getChunk($rowTpl,$video);
    }
    if(!empty($results)) {
      if (!empty($toPlaceholder)) {
        $output = $this->modx->setPlaceholder($toPlaceholder,$results); //Set '$toPlaceholder' placeholder
      }else{
        $output = $results;
      }
    }
    return $output;
  }
}
