<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * --------------------------------------------------------------------------
 * 轉換指令
 * --------------------------------------------------------------------------
 */

/**
 * 轉換器名稱
 */
$config['converter']["name"] = "doscopy";

/**
 * 轉換完成的資料類型
 * 
 * @example "text/html" HTML檔案
 * @example NULL 表示跟來源檔案相同類型
 */
$config['converter']["mime"] = "NULL";

/**
 * 轉換手續
 * 
 * [PATH] 檔案的路徑與檔案名稱
 * [DIR] 檔案所在的目錄，包含最後的/
 * [OUTPUT_DIR] 輸出所在的目錄，包含最後的/
 * [FULLNAME] 檔案全名
 * [FILE_NAME] 檔案的名稱
 * [EXT_NAME] 檔案的副檔名
 * 
 * 因為有可能是多個步驟，所以必須以陣列儲存
 * 
 * @example array (
 *  "copy [INPUT_FILE] [OUTPUT_FILE]"
 * )
 */
$config['converter']["script"] = array(
    "copy [PATH] [OUTPUT_DIR][FILE_NAME]-copy.[EXT_NAME]"
);

/**
 * 輸出檔案位置
 * 
 * [PATH] 檔案的路徑與檔案名稱
 * [DIR] 檔案所在的目錄，包含最後的/
 * [OUTPUT_DIR] 輸出所在的目錄，包含最後的/
 * [FULLNAME] 檔案全名
 * [FILE_NAME] 檔案的名稱
 * [EXT_NAME] 檔案的副檔名
 */
$config['converter']["output_path"] = "[OUTPUT_DIR][FILE_NAME]-copy.[EXT_NAME]";

/**
 * 輸出檔案名稱
 * 
 * [PATH] 檔案的路徑與檔案名稱
 * [DIR] 檔案所在的目錄，包含最後的/
 * [OUTPUT_DIR] 輸出所在的目錄，包含最後的/
 * [FULLNAME] 檔案全名
 * [FILE_NAME] 檔案的名稱
 * [EXT_NAME] 檔案的副檔名
 */
$config['converter']["output_name"] = "[ORI_NAME]-copy.[EXT_NAME]";


/**
 * --------------------------------------------------------------------------
 * 轉換檔案的位置
 * --------------------------------------------------------------------------
 */

/**
 * 上傳後的存放位置
 */
$config['convert_files']['uploaded']	= 'convert-files/uploaded';
$config['upload_path']	= $config['convert_files']['uploaded'];

/**
 * 轉換中的存放位置
 */
//$config['convert_files']['converting']	= 'convert-files/converting';

/**
 * 轉換完成的存放位置
 */
$config['convert_files']['completed']	= 'convert-files/completed';

/**
 * --------------------------------------------------------------------------
 * 檔案上傳限制
 * --------------------------------------------------------------------------
 */

/**
 * 保留檔案上限
 * 
 * 當上傳檔案超過這個數字時，更舊的檔案就會刪除
 */
$config['max_reserved_files']	= 3;

/**
 * 檔案大小最大限制
 * 
 * 當上傳檔案的大小超過這個數字時，則禁止轉換。
 * 單位是MB
 * 
 * @example 8 上限為8MB
 * @example 80 上限為80MB
 * @todo 要顯示在上傳表單中
 * PENDING 20131015 尚未實作
 */
$config['max_file_size']	= 80;
$config['max_size'] = $config['max_file_size']; 

/**
 * --------------------------------------------------------------------------
 * 許可IP設定
 * --------------------------------------------------------------------------
 */

/**
 * 許可類型
 * 
 * @example white 白名單
 * @example black 黑名單
 * @example disable 不設定
 * 
 * PENDING 20131015 尚未實作
 */
$config['ip_block']['type']	= 'disable';

/**
 * 許可列表
 * 
 * 必須輸入IP跟子網路遮罩(netmask)
 * 
 * @example 140.119.61.0/24 限定140.119.61.1~140.119.61.254
 * @example 140.119.61.141/32 限定140.119.61.141
 * @example 10.0.0.0/8 只要IP開頭為10都可以連線
 * 
 * PENDING 20131015 尚未實作
 */
$config['ip_block']['list']	= array();

/**
 * --------------------------------------------------------------------------
 * 許可檔案類型設定
 * --------------------------------------------------------------------------
 */

/**
 * 許可類型
 * 
 * @example white 白名單
 * @example black 黑名單
 * @example disable 不設定
 * 
 * PENDING 20131015 尚未實作
 */
$config['mine_block']['type']	= 'disable';

/**
 * 許可列表
 * 
 * 必須輸入IP跟子網路遮罩(netmask)
 * 
 * @example 140.119.61.0/24 限定140.119.61.1~140.119.61.254
 * @example 140.119.61.141/32 限定140.119.61.141
 * @example 10.0.0.0/8 只要IP開頭為10都可以連線
 * 
 * PENDING 20131015 尚未實作
 */
$config['mime_block']['list']	= array();

/**
 * 偵錯等級
 * 
 * 數字越大，顯示的偵錯訊息越多
 */
$config["debug"] = 5;

/**
 * @var int 等待時重讀的時間，單位是秒
 */
$config["wait_reload_interval"] = 3;

/**
 * @var Boolean 是否要保存原始檔案，直到轉換檔案被刪除
 * @example false 預設值，原始檔案在轉換完成之後就會被刪除
 * @example true 原始檔案在轉換檔案被刪除之後才會被刪除
 */
$config["reserve_original"] = true;

/* End of file converter.php */
/* Location: ./application/config/converter.php */
