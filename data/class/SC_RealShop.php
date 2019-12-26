<?php 

class SC_RealShop {

    // 店舗情報一覧を取得
    function GetRealShopNameList($only_valid = false) {

        $objQuery =& SC_Query_Ex::getSingletonInstance();

        $objQuery->setOrder('shop_id');

        $where = 'del_flg = 0';
        if($only_valid) $where .= ' and valid = 1';
        $result = $objQuery->select('*', 'dtb_real_shop', $where);

        foreach($result as $key=>$val) {
            $arrShop[$val['shop_id']] = $val['name'];
        }
        
        return $arrShop;
    }

    // 店舗情報一覧を取得(表示用)
    function GetDispList($arrPref = NULL) {
        
        if(!isset($arrPref)) {
            $masterData   = new SC_DB_MasterData_Ex();
            $arrPref      = $masterData->getMasterData('mtb_pref');
        }

        $objQuery =& SC_Query_Ex::getSingletonInstance();

        $objQuery->setOrder('shop_id');
        $result = $objQuery->select('*', 'dtb_real_shop', 'del_flg = 0');

        $cnt = 0;
        foreach($result as $key=>$val) {
            $value =& $result[$cnt];
            $value['zip']  = $value['zip01'] . '-' . $value['zip02'];
            $value['addr'] = $arrPref[$value['pref']] . $value['addr01'] . $value['addr02'];
            $value['tel']  = $value['tel01'] . '-' . $value['tel02'] . '-' . $value['tel03'];
            $value['fax']  = $value['fax01'] . '-' . $value['fax02'] . '-' . $value['fax03'];
        
            $cnt++;
        }
        
        return $result;
    }

}