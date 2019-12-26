<?php

class IntroMovie extends SC_Plugin_Base {

    public function __construct(array $arrSelfInfo) {
        parent::__construct($arrSelfInfo);
    }

    function install($arrPlugin) {

        // logo
        if(copy(PLUGIN_UPLOAD_REALDIR . "IntroMovie/logo.png", PLUGIN_HTML_REALDIR . "IntroMovie/logo.png") === false);

        // html
        if(mkdir(HTML_REALDIR . "contents") === false);
        if(copy(PLUGIN_UPLOAD_REALDIR . "IntroMovie/intro_movie.php", HTML_REALDIR . "contents/intro_movie.php") === false);

        // class
        if(mkdir(CLASS_REALDIR . "pages/contents") === false);
        if(copy(PLUGIN_UPLOAD_REALDIR . "IntroMovie/LC_Page_Movie_List.php", CLASS_REALDIR . "pages/contents/LC_Page_Movie_List.php") === false);

        // template
        if(mkdir(TEMPLATE_REALDIR . "contents") === false);
        if(copy(PLUGIN_UPLOAD_REALDIR . "IntroMovie/templates/default/plg_intro_movie.tpl", TEMPLATE_REALDIR . "contents/plg_intro_movie.tpl") === false);


        if(mkdir(SMARTPHONE_TEMPLATE_REALDIR . "contents") === false);
        if(copy(PLUGIN_UPLOAD_REALDIR . "IntroMovie/templates/sphone/plg_intro_movie.tpl", SMARTPHONE_TEMPLATE_REALDIR . "contents/plg_intro_movie.tpl") === false);

        IntroMovie::registDB($arrPlugin);
    }

    function uninstall($arrPlugin) {
        if(SC_Helper_FileManager_Ex::deleteFile(HTML_REALDIR . "contents/intro_movie.php") === false);
        if(SC_Helper_FileManager_Ex::deleteFile(CLASS_REALDIR . "pages/contents/LC_Page_Movie_List.php") === false);
        if(SC_Helper_FileManager_Ex::deleteFile(TEMPLATE_REALDIR . "contents/plg_intro_movie.tpl") === false);
        if(SC_Helper_FileManager_Ex::deleteFile(SMARTPHONE_TEMPLATE_REALDIR . "contents/plg_intro_movie.tpl") === false);

        IntroMovie::deleteDB($arrPlugin);
    }
    
    function enable($arrPlugin) {
    }

    function disable($arrPlugin) {
    }

    function registDB($arrPlugin) {
       $objQuery =& SC_Query_Ex::getSingletonInstance();

        if (DB_TYPE == 'mysql') {
            $sql = <<<DOC
CREATE TABLE `dtb_movie` (
  `movie_id` int(11) NOT NULL,
  `title` text NOT NULL,
  `list_comment` text,
  `main_comment` text,
  `snapimage` text,
  `url1` text,
  `url2` text,
  `url3` text,
  `status` smallint(6) NOT NULL DEFAULT '1',
  `shownews` smallint(6) NOT NULL DEFAULT '1',
  `rank` int(11) NOT NULL DEFAULT '0',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`movie_id`),
  UNIQUE KEY `movie_id` (`movie_id`)
);
DOC;
        }
        $objQuery->query($sql);
        $objQuery->commit();
    }

    function deleteDB($arrPlugin) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $objQuery->query("DROP TABLE dtb_movie ;");
    }
}
?>