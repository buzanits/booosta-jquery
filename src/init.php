<?php
namespace booosta\jquery;

\booosta\Framework::add_module_trait('webapp', 'jquery\webapp');
\booosta\Framework::add_module_trait('base', 'jquery\base');

trait webapp
{
  protected function preparse_jquery()
  {
    #\booosta\debug('in preparse_jquery');

    if($this->moduleinfo['jquery']['document_ready_function'] || is_readable('incl/jquery_ready.js')):
      if(is_readable('incl/jquery_ready.js')) $this->add_jquery_ready(file_get_contents('incl/jquery_ready.js')); 
      $this->add_includes("<script type='text/javascript'>\$(document).ready(function(){ {$this->moduleinfo['jquery']['document_ready_function']} });</script>");
    endif;

    if($this->config('jquery_loaded')) return;  // if jquery is already statically loaded in the template do not include it here

    if($this->moduleinfo['jquery']['use'] === true || $this->config('always_load_jquery') === true)
      $this->add_preincludes("<script type='text/javascript' src='vendor/components/jquery/jquery.min.js'></script>\n");
    elseif($this->moduleinfo['jquery']['use'] != '')
      $this->add_preincludes("<script type='text/javascript' src='vendor/components/jquery/jquery-{$this->moduleinfo['jquery']['use']}.min.js'></script>\n");
    elseif(($jquery = $this->config('always_load_jquery')) != '')
      $this->add_preincludes("<script type='text/javascript' src='vendor/components/jquery/jquery-$jquery.min.js'></script>\n");

    $theme = $this->config('jquery-ui-theme') ?? 'base';

    if($this->moduleinfo['jquery']['use_ui'] === true)
      $this->add_preincludes("<script type='text/javascript' src='vendor/components/jqueryui/jquery-ui.min.js'></script>
                              <link rel='stylesheet' href='vendor/components/jqueryui/themes/$theme/jquery-ui.css'>\n");
    elseif($this->moduleinfo['jquery']['use_ui'] != '')
      $this->add_preincludes("<script type='text/javascript' src='vendor/components/jquery/themes/$theme/jquery-ui-{$this->moduleinfo['jquery']['use_ui']}.js'></script>
                              <link rel='stylesheet' href='vendor/components/jquery/themes/$theme/jquery-ui-{$this->moduleinfo['jquery']['use_ui']}.css'>\n");
  }

  public function add_jquery_ready($code) { $this->moduleinfo['jquery']['document_ready_function'] .= $code; }
}

trait base 
{
  protected function make_jquery_ready($code)
  {
    return "<script type='text/javascript'>\$(document).ready(function(){ $code });</script>";
  }
}
