<?php

class application_moysklad {

    const IS_CACHE = false;
    const CACHE = '/application_moysklad.tmp/';
    const LOGIN = 'admin@poisondrop';
    const PASSWORD = 'fashionstory';
    const STATUS_URL = 'https://online.moysklad.ru/exchange/rest/ms/xml/Workflow/list';
    const ORDER_URL = 'https://online.moysklad.ru/exchange/rest/ms/xml/CustomerOrder/list?filter=';

    public static function getData($url) {
        $cacheId = __DIR__ . self::CACHE . sha1($url) . '.text';
        if (self::CACHE and is_file($cacheId)) {
            $curl_response = file_get_contents($cacheId);
        } else {
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($curl, CURLOPT_USERPWD, self::LOGIN . ":" . self::PASSWORD); //Your credentials goes here
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); //IMP if the url has https and you don't want to verify source certificate
            $curl_response = curl_exec($curl);
            curl_close($curl);
            if (self::CACHE) {
                file_put_contents($cacheId, $curl_response);
            }
        }
        return $curl_response;
    }

    public static function statusId($type) {
        static $mStatus = false;
        if ($mStatus === false) {
            $mStatus = array();
            foreach (simplexml_load_string(self::getData(self::STATUS_URL))->workflow->state as $data) {
                $code = preg_replace('~^(\[([A-Z])\].+)$~', '$2', (string) $data->attributes()->name);
                $mStatus[(string) $data->uuid] = $code;
            }
        }
        if (isset($mStatus[$type])) {
            return $mStatus[$type];
        } else {
            return false;
        }
    }

    public static function isNewOrder($mOrderList) {
        $mFilter = array();
        foreach ($mOrderList as $value) {
            $mFilter[] = 'name%3D' . $value;
        }
        $url = self::ORDER_URL . implode(';', $mFilter);
        $mOrder = array();
        $data = simplexml_load_string(self::getData($url));
        if ($data) {
            foreach ($data->customerOrder as $item) {
                $mOrder[(string) $item->code] = (string) $item->code;
            }
            return $mOrder;
        } else {
            return null;
        }
    }

    public static function order($count = 0) {
//        $count++;
        if (!CModule::IncludeModule('iblock') or ! CModule::IncludeModule('catalog')or ! CModule::IncludeModule('sale')) {
            return 'application_moysklad::order(' . $count . ');';
        }
        ignore_user_abort(true);
        set_time_limit(0);
//        preMemory();
//        error_reporting(E_ALL);
        $dbOrderList = CSaleOrder::GetList(array('ID' => 'ASC'), array('STATUS_ID' => array('D', 'E', 'N'), 'CANCELED' => 'N'), false, array('nTopCount' => 500), array('ID', 'STATUS_ID'));
        $mFilter = $mStatus = array();
        while ($arOrder = $dbOrderList->Fetch()) {
            $mFilter[$arOrder['ID']] = 'name%3D' . $arOrder['ID'];
            $mStatus[$arOrder['ID']] = $arOrder['STATUS_ID'];
        }

        $url = self::ORDER_URL . implode(';', $mFilter);
        $content = self::getData($url);
//        pre($url, $mFilter, $content);
        $data = simplexml_load_string($content);
        if ($data) {
            foreach ($data->customerOrder as $item) {
                $status = (string) $item->attributes()->stateUuid;
                $status = self::statusId($status);
                $ID = (string) $item->code;
//                pre($ID, $status, $mStatus[$ID]);
                if ($status and isset($mStatus[$ID]) and $mStatus[$ID] != $status) {
                    $arFields = array(
                        "UPDATED_1C" => 'Y',
                        "STATUS_ID" => $status
                    );
                    $count = $ID;
                    CSaleOrder::Update($ID, $arFields);
                    pre($ID, $arFields);
                }
            }
        }
        return 'application_moysklad::order(' . $count . ');';
    }

    public static function update1c() {
        if (!CModule::IncludeModule('iblock') or ! CModule::IncludeModule('catalog') or ! CModule::IncludeModule('sale')) {
            return '';
        }
        ignore_user_abort(true);
        set_time_limit(0);
        preMemory();
        error_reporting(E_ALL);
        $dbOrderList = CSaleOrder::GetList(array('ID' => 'ASC'), array('CANCELED' => 'N', 'UPDATED_1C' => 'N'), false, array('nTopCount' => 600), array('ID', 'STATUS_ID'));
        $mFilter = $mStatus = array();
        while ($arOrder = $dbOrderList->Fetch()) {
            $mFilter[$arOrder['ID']] = 'name%3D' . $arOrder['ID'];
            $mStatus[$arOrder['ID']] = $arOrder['STATUS_ID'];
        }

        $url = self::ORDER_URL . implode(';', $mFilter);
        $content = self::getData($url);
        pre($url, $content);
        $data = simplexml_load_string($content);
        if ($data) {
            foreach ($data->customerOrder as $item) {
                $status = (string) $item->attributes()->stateUuid;
                $status = self::statusId($status);
                $ID = (string) $item->code;
                if ($status and isset($mStatus[$ID])) {
                    $arFields = array(
                             "UPDATED_1C" => 'Y',
                            "STATUS_ID" => $status
                    );
//                    pre($ID, $arFields);
//                    exit;
                    CSaleOrder::Update($ID, $arFields);
                }
            }
        }
        return '';
    }

}

?>