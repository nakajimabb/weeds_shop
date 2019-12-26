<!--{*
 * WPPost
 * Copyright(c) 2000-2012 GIZMO CO.,LTD. All Rights Reserved.
 * http://www.gizmo.co.jp/
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
 *}-->
<?php
$arrPageLayout = $this->get_template_vars('arrPageLayout');

    switch($arrPageLayout['device_type_id']){
        case 1:
            break;
        case 2:
            break;
        case 10:
            echo('<link rel="stylesheet" href="' . ROOT_URLPATH . 'plugin/WpPost/media/plg_WpPost_common.css" type="text/css" media="screen" />');
            echo('<script type="text/javascript" src="' . ROOT_URLPATH . 'plugin/WpPost/media/plg_WpPost_comment.js"></script>');
            break;
        default:
            switch($_SERVER['PHP_SELF']){
                case '/admin/design/bloc.php':
                    echo('<script type="text/javascript" src="' . ROOT_URLPATH . 'plugin/WpPost/media/plg_WpPost_common.js"></script>');
                    break;
                default:
            }
    }
?>