<?php
/*
 * MainImage
 * Copyright(c) 2013 DELIGHT Inc. All Rights Reserved.
 *
 * http://www.delight-web.com/
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

/**
 * MainImageプラグイン のSQLクラス.
 *
 * @package MainImage
 * @author DELIGHT CO.,LTD.
 * @version $
 */
class plg_MainImage_PostgreSQL{
    
    /**
     * テーブル情報。
     * テーブル名 => array(フィールド情報1, フィールド情報2 …)
     */
    public static $arrSchema = array(
        'dtb_main_images' => array(
            'id integer NOT NULL PRIMARY KEY',
            'image text',
            'title text',
            'url_pc text',
            'url_sp text',
            'url_mb text',
            'hidden_pc smallint DEFAULT 0 NOT NULL',
            'hidden_mb smallint DEFAULT 0 NOT NULL',
            'hidden_sp smallint DEFAULT 0 NOT NULL',
            'target_blank_pc smallint DEFAULT 0 NOT NULL',
            'target_blank_sp smallint DEFAULT 0 NOT NULL',
            'target_blank_mb smallint DEFAULT 0 NOT NULL'
        )
    );
}