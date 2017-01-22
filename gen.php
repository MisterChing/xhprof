<?php
// init configure
$_xhprof = array();
$_xhprof['getParam'] = '__profile';  //是否开启profile参数
$_xhprof['doProfile'] = false;

$ignoredUrls = array();
$ignoredDomains = array();

// ignore builtin functions and call_user_func* during profiling
//$ignoredFunctions = array('call_user_func', 'call_user_func_array', 'socket_select');

function getExtensionName() {
    return 'xhprof';
    if (extension_loaded('tideways')) {
        return 'tideways';
    } elseif (extension_loaded('xhprof')) {
        return 'xhprof';
    }
    return false;
}

$_xhprof['extName'] = getExtensionName();
if ($_xhprof['extName']) {
    $flagsCpu = constant(strtoupper($_xhprof['extName']).'_FLAGS_CPU');
    $flagsMemory = constant(strtoupper($_xhprof['extName']).'_FLAGS_MEMORY');
    $envVarName = strtoupper($_xhprof['extName']).'_PROFILE';
}

if (isset($_GET[$_xhprof['getParam']]) && $_GET[$_xhprof['getParam']]) {
    $_xhprof['doProfile'] = true;
}

if ($_xhprof['extName'] && $_xhprof['doProfile'] === true) {
    include_once dirname(__FILE__) . '/xhprof_lib/utils/xhprof_lib.php';
    include_once dirname(__FILE__) . '/xhprof_lib/utils/xhprof_runs.php';
    include_once dirname(__FILE__) . '/xhprof_lib/utils/callgraph_utils.php';
    ob_start();
    if (isset($ignoredFunctions) && is_array($ignoredFunctions) && !empty($ignoredFunctions)) {
        call_user_func($_xhprof['extName'].'_enable', $flagsCpu + $flagsMemory, array('ignored_functions'=>$ignoredFunctions));
    } else {
        call_user_func($_xhprof['extName'].'_enable', $flagsCpu + $flagsMemory);
    }
    unset($flagsCpu);
    unset($flagsMemory);
}

function xhprof_shutdown_function() {
    global $_xhprof;
    $__content = ob_get_clean(); 
    if ($_xhprof['extName'] && $_xhprof['doProfile'] === true) {
        $xhprof_data = call_user_func($_xhprof['extName'] . '_disable');
        $xhprof_runs = new XHProfRuns_Default();
        $run_id = $xhprof_runs->save_run($xhprof_data, 'profile', null, $_xhprof);
        xhprof_render_image($xhprof_runs, $run_id, 'png', $threshold, '', 'profile', true);
    }
    echo $__content;
}

register_shutdown_function('xhprof_shutdown_function');

