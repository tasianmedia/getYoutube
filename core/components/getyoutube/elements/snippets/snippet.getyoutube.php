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
 *getYoutube is free software; you can redistribute it and/or modify it under the
 *terms of the GNU General Public License as published by the Free Software
 *Foundation; either version 3 of the License, or any later version.

 *getYoutube is distributed in the hope that it will be useful, but WITHOUT ANY
 *WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 *A PARTICULAR PURPOSE. See the GNU General Public License for more details.

 *You should have received a copy of the GNU General Public License along with
 *getYoutube; if not, write to the Free Software Foundation, Inc., 59 Temple
 *Place, Suite 330, Boston, MA 02111-1307 USA
 */

/* set default properties */
$id = !empty($id) ? $id : '';
$outerTpl = !empty($outerTpl) ? $outerTpl : ''; // Blank default makes '&outerTpl' optional
$rowTpl = !empty($rowTpl) ? $rowTpl : 'videoRowTpl';
$rowTplOdd = !empty($rowTplOdd) ? $rowTplOdd : 'videoRowTpl';
$toPlaceholder = !empty($toPlaceholder) ? $toPlaceholder : ''; // Blank default makes '&toPlaceholder' optional
$i = 1; //Starts row count at 1

$feedURL = "https://gdata.youtube.com/feeds/api/videos?q=$id&v=2";
$videos = simplexml_load_file($feedURL);

$output = '';

if (!empty($id)) {
  foreach($videos->entry as $video) { //'entry' is the xml node for each video

    if($i % 2 == 0){ // Checks if row count can be divided by 2 (even)
      $tpl = $rowTpl;
    }else{
      $tpl = $rowTplOdd;
    }

    $media = $video->children('http://search.yahoo.com/mrss/'); //Access 'media:group' node
    $yt = $video->children('http://gdata.youtube.com/schemas/2007'); //Access 'yt' node
    //$yt = $media->children('http://gdata.youtube.com/schemas/2007'); //Access 'yt' node within 'media:group' node

    $modx->setPlaceholder('thumbnail_small',$media->group->thumbnail[0]->attributes()->url);
    $modx->setPlaceholder('url',$video->link[0]->attributes()->href);
    $modx->setPlaceholder('upload_date',$upload_date = $video->published);
    $modx->setPlaceholder('description',$video->content);

    $array = (array) $video; //Convert to an Array
    $rowOutput .= $modx->getChunk($tpl,$array);
    $i++; //Increases row count by +1
  }
  if(!empty($rowOutput)) {
    if (!empty($outerTpl)) {
      $rows = array('rows' => $rowOutput); //Convert '$rowOutput' to an array and set as 'rows' placeholder
      $output = $modx->getChunk($outerTpl,$rows); //Pass 'rows' placeholder to the '$outerTpl' chunk
    }else{
      $output = $rowOutput;
    }
    if (!empty($toPlaceholder)) {
      $modx->setPlaceholder($toPlaceholder,$output); //Pass '$outerTpl' chunk to the '$toPlaceholder' placeholder 
      return ''; // Stops double output
    }
  }
}

return $output;

//print_r ($videos);

//echo "<pre>";
//print_r($videos);
//echo "</pre>";