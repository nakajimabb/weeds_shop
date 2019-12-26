<?php
/*
 * TopicPath
 * Copyright (C) 2012 LOCKON CO.,LTD. All Rights Reserved.
 * http://www.lockon.co.jp/
 * 
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

$arrPageLayout = $this->get_template_vars('arrPageLayout');

    switch($arrPageLayout['device_type_id']){
        case 1:
            break;
        case 2:
            break;
        case 10:
            switch($_SERVER['PHP_SELF']){
                case ROOT_URLPATH . 'products/list.php':
                case ROOT_URLPATH . 'products/detail.php':
                    echo('<link rel="stylesheet" href="'. PLUGIN_HTML_URLPATH .  'TopicPath/media/topicPath.css" type="text/css" media="screen" />');
                    break;
                default:
            }
            break;
        default:
            switch($_SERVER['PHP_SELF']){
                case ROOT_URLPATH . ADMIN_DIR . 'design/bloc.php':
                    echo('<script type="text/javascript" src="'. PLUGIN_HTML_URLPATH .  'TopicPath/media/topicPath.js"></script>');
                    break;
                default:
            }
    }
?>
