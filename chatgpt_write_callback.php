<?php
!defined('EMLOG_ROOT') && exit('access deined!');

// 插件激活时调用
function callback_init() {
     $plugin_storage = Storage::getInstance('chatgpt_write');
     $plugin_storage->setValue('accessKey', '','string');
     $plugin_storage->setValue('self_interface', '1','number');
    // $plugin_storage = Storage::getInstance('anti_spam_comment');
    // $r = $plugin_storage->getValue('temp');
    // if (empty($r)) {
    //     $default_data = [
    //         'ip'      => [],
    //         'time'    => [],
    //         'attempt' => [],
    //     ];
    //     $plugin_storage->setValue('temp', json_encode($default_data), 'string');
    // }
}

// 插件关闭时调用
function callback_rm() {
    // ....
}