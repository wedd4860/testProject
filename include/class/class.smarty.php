<?php
if (!defined('_KIMILJUNG_')) exit;

//디렉토리 권한:755, 파일 권한:644 
require _CLASS_ROOT.'smarty/libs/Smarty.class.php';

class MySmarty extends Smarty {

  public function __construct() {
    parent::__construct();
    $this->template_dir = _TEMPLATES_ROOT.'design';
    $this->compile_dir  = _TEMPLATES_ROOT.'compile';
    $this->config_dir   = _TEMPLATES_ROOT.'configs';
    $this->cache_dir    = _TEMPLATES_ROOT.'cache';
    $this->default_modifiers = array('escape:"htmlall"');
    $this->assign('app_title', 'Smarty 예제');
  }

  public function d() {
    parent::display(basename($_SERVER['PHP_SELF'], '.php').'.tpl');
  }
}