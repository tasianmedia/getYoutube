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
 
$properties = array(
  array(
    'name' => 'mode',
    'desc' => 'Select the search mode. [OPTIONS: channel or video] [REQUIRED]',
    'type' => 'list',
    'options' => array(
      array('text' => 'channel','value' => 'channel'),
      array('text' => 'video','value' => 'video'),
      ),
    'value' => 'video',
  ),
  array(
    'name' => 'channel',
    'desc' => 'The numeric ID of a YouTube Channel to search. All videos within the channel will be returned.',
    'type' => 'textfield',
    'options' => '',
    'value' => '',
  ),
  array(
    'name' => 'video',
    'desc' => 'A comma-separated list of numeric video IDs to return.',
    'type' => 'textfield',
    'options' => '',
    'value' => '',
  ),
  array(
    'name' => 'tpl',
    'desc' => 'Name of a chunk serving as a template. [REQUIRED]',
    'type' => 'textfield',
    'options' => '',
    'value' => 'videoTpl',
  ),
  array(
    'name' => 'tplAlt',
    'desc' => 'Name of a chunk serving as a template for every other Video.',
    'type' => 'textfield',
    'options' => '',
    'value' => '',
  ),
  array(
    'name' => 'sortby',
    'desc' => 'A placeholder name to sort by. [OPTIONS: date, rating, title, viewCount]',
    'type' => 'list',
    'options' => array(
      array('text' => 'date','value' => 'date'),
      array('text' => 'rating','value' => 'rating'),
      array('text' => 'title','value' => 'title'),
      array('text' => 'viewCount','value' => 'viewCount'),
      ),
    'value' => 'date',
  ),
  array(
    'name' => 'toPlaceholder',
    'desc' => 'If set, will assign the output to this placeholder instead of outputting it directly.',
    'type' => 'textfield',
    'options' => '',
    'value' => '',
  ),
  array(
    'name' => 'limit',
    'desc' => 'Limits the number of Videos returned. [NOTE: Acceptable values are 0 to 50, inclusive. Please see pagination docs for more details]',
    'type' => 'textfield',
    'options' => '',
    'value' => '50',
  ),
  array(
    'name' => 'totalVar',
    'desc' => 'Define the key of a placeholder set by getYoutube indicating the total number of Videos returned.',
    'type' => 'textfield',
    'options' => '',
    'value' => 'total',
  ),
  array(
    'name' => 'safeSearch',
    'desc' => 'Select whether the results should include restricted content as well as standard content. [OPTIONS: none, moderate, strict]',
    'type' => 'list',
    'options' => array(
      array('text' => 'none','value' => 'none'),
      array('text' => 'moderate','value' => 'moderate'),
      array('text' => 'strict','value' => 'strict'),
      ),
    'value' => 'none',
  ),
);
return $properties;