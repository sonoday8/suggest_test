<?php

class Utils {
    //名前、ローマ字変換APIエンドポイント
    const NameToRomanBaseUrl = 'http://jlp.yahooapis.jp/FuriganaService/V1/furigana';
    //ローマ字変換、アプリID
    const NameToRomanAppId = 'dj0zaiZpPU0wNU1xMzd2M1BZZyZzPWNvbnN1bWVyc2VjcmV0Jng9Y2M-';
    // データ保存するファイル名
    const SAVE_FILE = 'data.txt';
    //元となるデータCSVのURL
    const DATA_FEED = 'https://docs.google.com/spreadsheets/d/1gSKgFJ2VklBk6iTzMfYmlPn-_YUqJ_a-nxF7xoeIkII/export?format=csv&gid=0';

    /**
     * csvを配列に変換
     * @param $_sFile 
     * @param $_sDelimiter
     * @return array
     */
    public static function csvToArray($_sFile, $_sDelimiter) { 
      if (($handle = fopen($_sFile, 'r')) !== FALSE) { 
        $i = 0; 
        while (($_aLineArray = fgetcsv($handle, 4000, $_sDelimiter, '"')) !== FALSE) { 
          for ($j = 0; $j < count($_aLineArray); $j++) { 
            $arr[$i][$j] = $_aLineArray[$j]; 
          } 
          $i++; 
        } 
        fclose($handle); 
      } 
      return $arr; 
    } 

    /**
     * 名前をローマ字に変換
     * @param $_sName
     * @return string
     */
    public static function nameToRoman($_sName) {
        $_aData = [
            'appid' => Utils::NameToRomanAppId,
            'sentence' => $_sName,
            'grade' => '1',
        ];
        
        $_aHeader = ['Content-Type: text/xml'];
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, Utils::NameToRomanBaseUrl.'?'.http_build_query($_aData));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($curl, CURLOPT_HTTPHEADER, $_aHeader);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, true);
        
        $response = curl_exec($curl);
        
        $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE); 
        $header = substr($response, 0, $header_size);
        $body = substr($response, $header_size);
        $result = json_decode(json_encode(simplexml_load_string($body)), true); 
        curl_close($curl);
        $_sRoman = '';
        if (isset($result['Result']['WordList']['Word'])) {
            foreach($result['Result']['WordList']['Word'] as $_aVal){
                if(isset($_aVal['Roman'])){
                    $_sRoman .= $_aVal['Roman'];
                }
            }
        }
        return $_sRoman;
    }
    
    /**
     * データを保存します。
     * @param $_sFileName
     * @param $_sData
     */
    public static function saveData($_sFileName,$_sData) {
        if (($handle = fopen(Utils::SAVE_FILE, 'w+')) !== FALSE) {
            fwrite($handle, $_sData);
            fclose($handle);
        }
    }
    
    /**
     * ファイルからデータ取得
     * @param $_sFileName
     */
    public static function getDataFromFile($_sFileName) {
        if (file_exists($_sFileName) !== FALSE 
            && ($_sData = file_get_contents($_sFileName)) !== FALSE) 
        {
            return $_sData;
        } else {
            return '';
        }
    }
    
    /**
     * リモートからデータを取得
     */
    public static function getRemoteData() {
        $_aData = Utils::csvToArray(Utils::DATA_FEED, ',');
        $count = count($_aData) - 1;
        
        $keys = array();
        $labels = array_shift($_aData);  
        foreach ($labels as $label) {
            $keys[] = $label;
        }
        $_aRes = array();
        for ($j = 0; $j < $count; $j++) {
            $d = array_combine($keys, $_aData[$j]);
            if(isset($d['name'])) {
                $d['roman'] = Utils::nameToRoman($d['name']);
            }
            $_aRes[$j] = $d;
        }
        return $_aRes;
    }

}
