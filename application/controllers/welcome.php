<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
            $converter = $this->config->item("converter");
            $parameters = NULL;
            if (isset($converter["params"])) {
                $parameters = $converter["params"];
            }
            
            $chmod_messages = $this->_check_directories_mode();
            $readme = file_get_contents(get_root_path("README.md"));
            $license = file_get_contents(get_root_path("LICENSE"));
            
            $this->load->view('component/header');
            $this->load->view('upload_view', array(
                "parameters" => $parameters,
                "chmod_messages" => $chmod_messages,
                'readme' => $readme,
                "license" => $license
            ));
            $this->load->view('component/footer');
	}
        
        private function _check_directories_mode() {
            
            $messages = array();
            
            //$config['convert_files']['uploaded']
            $convert_files = $this->config->item('convert_files');
            
            //php-file-converter\convert-files\completed
            //php-file-converter\convert-files\uploaded
            //php-file-converter\application\db
            //php-file-converter\application\db\php-file-converter.sqlite.db

            $dir_ary = array(
                get_root_path($convert_files['uploaded']),
                get_root_path($convert_files['completed']),
                get_root_path("application/db"),
                get_root_path("application/db/php-file-converter.sqlite.orig.db"),
                get_root_path("application/db/php-file-converter.sqlite.db"),
            );
            
            foreach ($dir_ary AS $dir) {
                $dir_mode = is_writable($dir);
                if ($dir_mode === FALSE) {
                    $result = @chmod($dir, 755);
                    if ($result === FALSE) {
                        array_push($messages, $dir);
                    }
                }
            }
            
            return $messages;
        }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */