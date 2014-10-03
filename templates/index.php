<?php if (!defined('IS_GIUEM')) exit();?>
<h1 data-am-scrollspy="{animation:'fade',repeat: false}">回帖记录</h1>
<div class="am-scrollable-vertical" data-am-scrollspy="{animation:'slide-top', delay: 300,repeat: false}">
<table class="am-table am-table-bd am-table-striped am-table-hover">
    <thead>
        <tr>
            <th>时间</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $log = get_log();
        if ($log){
			foreach ($log as $k){
				echo '<tr><td>'.date('Y-m-d H:i:s',$k[0]).'</td><td>'.$k[1].'</td></tr>';
			}
		}      
        ?>
    </tbody>
</table>
</div>
<br />
<button type="button" class="am-btn am-btn-primary" id="del-log" data-am-scrollspy="{animation:'fade',repeat: false}">清除日志</button>
<hr />
<div class="am-modal am-modal-confirm" tabindex="-1" id="my-confirm">
  <div class="am-modal-dialog">
    <div class="am-modal-hd">提示</div>
    <div class="am-modal-bd">真的要删除吗？</div>
    <div class="am-modal-footer">
      <span class="am-modal-btn" data-am-modal-confirm>确定</span>
      <span class="am-modal-btn" data-am-modal-cancel>取消</span>
    </div>
  </div>
</div>