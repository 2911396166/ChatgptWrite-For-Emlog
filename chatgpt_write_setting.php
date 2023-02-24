<?php
if (!defined('EMLOG_ROOT')) {
	die('err');
}
function plugin_setting_view() {
	$plugin_storage = Storage::getInstance('chatgpt_write');
	$self_interface = $plugin_storage->getValue('self_interface');
	$accessKey = $plugin_storage->getValue('accessKey');
	?>
	<?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">修改成功</div>
	<?php endif; ?>

<div class="card shadow mb-4 mt-2">
        <div class="card-body">
            <form method="post">
                <div class="form-group">
					<p>请输入您在openAI的AccessKey（没有可以<a href="http://wpa.qq.com/msgrd?v=3&uin=2911396166&site=qq&menu=yes">点我购买</a>）</p>
					<input name="accessKey"  class="form-control" value="<?php echo $accessKey; ?>">
					<hr/>
					
					<p>是否使用自己服务器接口（如果自己服务器不行就用作者接口）</p>
					<input type="radio" name="self_interface" value="1" <?php if($self_interface==1) echo "checked";  ?> >内置接口
					<input type="radio" name="self_interface" value="0" <?php if($self_interface!=1){ echo "checked"; }  ?> >作者接口
					<hr/>

					<input type="submit" class="btn btn-success btn-sm" value="提交更改"/>
				</div>
			</form>
        </div>
</div>





<?php
}
if (!empty($_POST)) {
	$plugin_storages = Storage::getInstance('chatgpt_write');
	$plugin_storages->setValue('accessKey', addslashes($_POST["accessKey"]));
	$plugin_storages->setValue('self_interface', intval($_POST["self_interface"]));
	header('Location:./plugin.php?plugin=chatgpt_write&success=1');
}

