<?php
/*
 * WPPost
 * Copyright (C) 2012 GIZMO CO.,LTD. All Rights Reserved.
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
 */

// {{{ requires
require_once realpath(dirname(__FILE__)) . '/../require.php';
require_once PLUGIN_UPLOAD_REALDIR . 'WpPost/plg_WpPost_Category_LC_Page.php';

// }}}
// {{{ generate page
$objPage = new LC_Page_WpPost_Category();
$objPage->blocItems = $params['items'];
register_shutdown_function(array($objPage, "destroy"));
$objPage->init();
$objPage->process();
?>