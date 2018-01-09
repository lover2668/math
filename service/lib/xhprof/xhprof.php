<?php
namespace service\lib\xhprof;


class xhprof {
    public static function s(){
        // start profiling
        error_reporting(E_ALL ^ E_NOTICE);
        @xhprof_enable();
    }

    public static function e(){

        // stop profiler
        $xhprof_data = xhprof_disable();
        //
        // Saving the XHProf run
        // using the default implementation of iXHProfRuns.
        //
        include_once  "xhprof_lib.php";
        include_once  "xhprof_runs.php";
        $xhprof_runs = new \XHProfRuns_Default();

        // Save the run under a namespace "xhprof_foo".
        //
        // **NOTE**:
        // By default save_run() will automatically generate a unique
        // run id for you. [You can override that behavior by passing
        // a run id (optional arg) to the save_run() method instead.]
        //
//        $url =  'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
//        $arr =  parse_url($url);
//        $path  = $arr['path'];
//        $path = str_replace("/index.php","",$path);
//        $path = str_replace("/","_",$path);
//        $path = str_replace(".html","",$path);
//        $path = str_replace("__","_",$path);
//        $path = "ct".$path;
//        $path .= "_".time();
      $url =  'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];       
        $arr =  parse_url($url);     
        $path  = $arr['host'];
        $path = str_replace(".","-",$path); 
        $path  .= "-----";
        $path  .= $arr['path'];
        $path = str_replace("/index.php","",$path);
        $path = str_replace("/teacher.php","",$path);
        $path = str_replace("/","_",$path);
        $path = str_replace(".html","",$path);
        $path = str_replace("__","_",$path);
        $path = str_replace(".","_",$path);
       if(strlen($path)>200){
            $path= substr($path, 0,200);
        }
        $run_id = $xhprof_runs->save_run($xhprof_data, $path);
//        echo "---------------\n".
//            "Assuming you have set up the http based UI for \n".
//            "XHProf at some address, you can view run at \n".
//            "<a href='http://".$_SERVER['SERVER_NAME']."/xhprof_html/index.php?run=$run_id&source=xhprof_foo' target='_blank'>http://".$_SERVER['SERVER_NAME']."/xhprof_html/index.php?run=$run_id&source=xhprof_foo</a>\n".
//            "---------------\n";
    }
}