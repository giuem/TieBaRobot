<?php if (!defined('IS_GIUEM')) exit();?>
<?php 
if ($_POST){
	setting($_POST['api'], $_POST['apikey'], $_POST['blacklist'],$_POST['kwblacklist'], $_POST['islike'], $_POST['weiba']);
}
$setting=get_setting();
?>
<form action="./index.php?action=setting" method="post" class="am-form" data-am-scrollspy="{animation:'slide-top', delay: 300,repeat: false}">
  <fieldset>
    <legend>设置</legend>
    <div class="am-form-group">
      <label>API</label>
      <select name="api">
        <option value="xiaoji" <?php if($setting[1]=='xiaoji') echo 'selected="selected"';?>>小鸡</option>
        <option value="tuling" <?php if($setting[1]=='tuling') echo 'selected="selected"';?>>图灵</option>
        <option value="simsimi" <?php if($setting[1]=='simsimi') echo 'selected="selected"';?>>simsimi</option>
        <option value="simsimi3" <?php if($setting[1]=='simsimi3') echo 'selected="selected"';?>>第三方的simsimi</option>
      </select>
      <span class="am-form-caret"></span>
    </div>

    <div class="am-form-group">
      <label>APIKey</label>
      <input type="text" name="apikey" placeholder="图灵专用" value="<?php echo $setting[2];?>">
    </div>

    <div class="am-form-group">
      <label>用户黑名单</label>
      <input type="text" name="blacklist" placeholder='使用"|"分隔每个帐号' value="<?php echo $setting[3];?>">
    </div>
    
    <div class="am-form-group">
      <label>贴吧黑名单</label>
      <input type="text" name="kwblacklist" placeholder='使用"|"分隔每个贴吧，注意别带吧字' value="<?php echo $setting[4];?>">
    </div>
    
    <div class="am-form-group">
      <label>小尾巴</label>
      <input type="text" name="weiba" value="<?php echo $setting[6];?>">
    </div>
    
    <div class="am-checkbox">
      <label>
        <input type="checkbox" name="islike" value="1"<?php if ($setting[5]==1) echo 'checked=\"checked\"'; ?>>自动关注贴吧（3级免码，推荐配合自动刷新贴吧的云签到）
      </label>
    </div>
    
    <p><button type="submit" class="am-btn am-btn-default">保存</button></p>
  </fieldset>
</form>
<hr />