<?php
/*
Plugin Name: ChatgptWrite
Version: 1.0
Plugin URL:https://www.fish9.cn/archives/387/
Description: ChatgptWrite 是一个方便你在typecho使用chatgpt进行文章创作的插件！
Author: 吃猫的鱼
Author URL: https://www.fish9.cn
*/

!defined('EMLOG_ROOT') && exit('access deined!');
function chatgptwrite() {
	$plugin_storage = Storage::getInstance('chatgpt_write');
    if($plugin_storage->getValue('self_interface') == 1){
    $url_api ='../content/plugins/chatgpt_write/chatgptApi.php';
    }else{
    $url_api = 'https://chatgpt.fish9.net/chat.php';
    }

?>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>                    
<script type="text/jscript">
    var loading = 0;
         function chatgpt() {
            if(loading!=0){
                alert("上一个请求正在处理，请等待！");
                return
            }
            loading = 1;
            var str = document.getElementById("chatgptQuestion").value;
            console.log(str)
                axios.get('<?php echo $url_api; ?>', {
                    params: {
                        id: '1',
                        apikey: '<?php echo $plugin_storage->getValue('accessKey'); ?>',
                        text: str
                    }
                })
                    .then(function (response) {
                        if(response["data"]["data"]["html"]==null){
                            alert("出现错误，请检查您的AccessToken");
                        }else{
                            document.getElementById("respone-div").style.display="block"; 
                            document.getElementById("respone").value = response["data"]["data"]["html"];
                        }
                    })
                    .catch(function (error) {
                        alert(error);
                    })
                    .then(function () {
                        // 总是会执行
                        loading = 0;
                    });
        }

        function showChatgptAsk(){
            if(document.getElementById("ChatgptAsk").style.display=="none"){
                document.getElementById("ChatgptAsk").style.display="block"; 
                document.getElementById("respone-div").style.display="block"; 
                document.getElementById("chatgpt-button").innerHTML="收起chatgpt"; 
            }else{
                document.getElementById("ChatgptAsk").style.display="none"; 
                document.getElementById("respone-div").style.display="none"; 
                document.getElementById("chatgpt-button").innerHTML="展开chatgpt"; 
            }
         } 


        function copy() {
    const range = document.createRange();
    range.selectNode(document.getElementById('respone'));
    const selection = window.getSelection();
    if(selection.rangeCount > 0) selection.removeAllRanges();
    selection.addRange(range);
    document.execCommand('copy');
    }
    document.getElementById('copy').addEventListener('click', copyArticle, false);
		
	

        </script>   

        <div style="margin:5px 0px 5px 0px;text-align:left;">
       <a class="btn btn-sm btn-primary" id="chatgpt-button" style = "text-decoration:none; color:white; padding:7px; margin:5px 0px 5px 0px"onclick="showChatgptAsk()">展开Chatgpt</a>
        </div>
       <div id ="ChatgptAsk" style="display:none;">
          <div >
             <input type="text" placeholder="请输入询问内容..." class="w-70 text title" name="name" id="chatgptQuestion" value=""/><label onclick="chatgpt()" class="btn btn-sm btn-primary" style = "text-decoration:none; color:white; padding:7px 15px 7px 15px; margin:5px 5px 5px 5px">发送</label><label onclick="copy()" class="btn btn-sm btn-primary" style = "text-decoration:none; color:white; padding:7px 15px 7px 15px; margin:5px 5px 5px 5px">复制</label>
          </div>   
        </div>
        
         <div id="respone-div" style="display:none;">
        <textarea  autocomplete="off" id="respone" name="respone" class="w-100 mono" rows="5" value="">
        </textarea>
        </div>

<?php }
addAction('adm_writelog_head', 'chatgptwrite');