<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Converter
 * 
 * 
 */
class Converter extends CI_Controller {

        /**
         * 預設首頁，會指到upload
         */
        public function index() {
            $this->do_upload();
        }
    
        /**
         * 處理上傳檔案
         */
        public function do_upload() {
            $config['upload_path'] = $this->config->item("upload_path");

            $config['allowed_types'] = '*';
            $config['max_width'] = 0;
            $config['max_height'] = 0;
            $config['max_size'] = 0;
            $config['encrypt_name'] = TRUE;

            //echo $config['upload_path'];
            $this->load->library('upload', $config);
            $this->upload->do_upload('bitstream');
            
            $upload_data = $this->upload->data();
            $internal_name = $upload_data['file_name'];
            
            // 記錄完畢，header去wait
        }
        
        /**
         * 顯示轉換中的等待訊息
         * @param int $bitream_id
         */
	public function wait($bitream_id) {
            //$this->load->library('javascript');
            //$this->load->library('javascript/jquery');
            //$this->load->view('upload_view');
            
            // 如果轉換完成，header到download
	}
        
        /**
         * 下載檔案
         * @param int $bitstream_id
         */
        public function download($bitstream_id) {
            
            // 如果bs已經被刪除，轉移到錯誤訊息
            
            // 記錄檔案下載
            
            // 輸出檔案
        }
        
        /**
         * 顯示已經被錯誤下載的訊息
         * @param int $bitstream_id
         */
        public function deleted($bitstream_id) {
            // 顯示錯誤訊息
        }
}

/* End of file converter.php */
/* Location: ./application/controllers/converter.php */