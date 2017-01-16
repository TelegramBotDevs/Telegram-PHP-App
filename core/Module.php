<?php

namespace TelegramApp;

class Module {
	protected $telegram;
	protected $db;

	public function __construct($tg = NULL, $db = NULL){
		if(!empty($tg)){ $this->setTelegram($tg); }
		if(!empty($db)){ $this->setDB($db); }
	}

	protected function hooks(){

	}

	public function run(){
		if(!empty($this->telegram) && $this->telegram->text_command()){
			$cmd = $this->telegram->text_command();
			$cmd = substr($cmd, 1);
			if(strpos($cmd, "@") !== FALSE){
				$cmd = substr($cmd, 0, strpos($cmd, "@"));
			}
			if(in_array($cmd, ["run", "hooks", "end"])){ return $this->hooks(); }
			if(method_exists($this, $cmd)){
				$parms = array();
				if($this->telegram->words() > 1){
					$parms = $this->telegram->words(TRUE);
					array_shift($parms);
				}
				call_user_func_array([$this, $cmd], $parms);
			}
		}else{
			$this->hooks();
		}
	}

	protected function end(){
		die();
	}

	public function setTelegram($tg){
		$this->telegram = $tg;
	}

	public function setDB($db){
		$this->db = $db;
	}
}

?>
