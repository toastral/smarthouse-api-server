<?php
ini_set("display_errors",1);
error_reporting(E_ALL); 
set_time_limit(0);
//include('auth.php');
include('head.php');
include('../lib/pagination.class.php'); // https://github.com/onassar/PHP-Pagination

// determine page (based on <_GET>)
$page = isset($_GET['page']) ? ((int) $_GET['page']) : 1;

// instantiate; set current page; set number of records
$pagination = (new Pagination());
$pagination->setCurrent($page);
$pagination->setTotal(200);

// grab rendered/parsed pagination markup
$markup = $pagination->parse();

echo $markup;

exit;

$path_to_script = __DIR__;
$path_to_xenforo = realpath(__DIR__.'/..');

$prex = $path_to_script.'/php/';

include $prex.'db.class.php';
$scanned_directory = array_diff(scandir($prex.'orm'), array('..', '.'));
while($file = array_pop($scanned_directory)) include $prex.'orm/'.$file;

$scanned_directory = array_diff(scandir($prex.'lib'), array('..', '.'));
while($file = array_pop($scanned_directory)) include $prex.'lib/'.$file;

?>
<script>
jQuery(function($){
	$('#button_load_messages').on('click', function(){
		load_messages();
		return true;
	});
	
	$('#file_load_1').on('click', function(){
		$('#formloadblock').show();
		$('#serverloadblock').hide();
		return true;
	});
	
	$('#file_load_2').on('click', function(){
		$('#formloadblock').hide();
		$('#serverloadblock').show();
		return true;
	});
	
});


jQuery(function($){
	$('#button_load_users').on('click', function(){
		load_users();           
		return true;
	});
});


function load_messages(){
	// some checks may be here
}

function load_users(){
	// some checks may be here
}

</script>  
<body>
<section>
<h1>XenForo Importer</h1>
<div>
  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Импорт сообщений</a></li>
    <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Импорт пользователей</a></li>
	<li role="presentation"><a href="#help" aria-controls="help" role="tab" data-toggle="tab">Помощь</a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="home">
<form enctype="multipart/form-data" action="load_messages.php" method="POST">
  <div class="form-group">
    <label for="selectNodeId">Форумы</label>
<?php $Forum = new Forum();
$a_list = $Forum->getNodeIdAndTitle(); 
?>	
    <select name="node_id" id="selectNodeId" class="form-control">	
<?php foreach($a_list as $row):?>	
        <option value="<?=$row['node_id']?>"><?=$row['title']?></option>
<?php endforeach;?>			
    </select>
  </div>
  
<div class="radio">
  <label>
    <input type="radio" name="radio_type_file_load" id="file_load_1" value="form" checked>
    Взять файл из формы<small> - Используйте для файлов небольшого обьёма(до 2 Мб)</small>
  </label>
</div>
<div class="radio">
  <label>
    <input type="radio" name="radio_type_file_load" id="file_load_2" value="server">
	Взять файл с сервера<small> - Используйте для файлов большого объема (свыше 2 Мб)</small>
  </label>
</div>	
  
  <div class="form-group" id="formloadblock">
    <label for="postImportFile">Загрузка файла</label>
    <input type="file" id="postImportFile" name="file_messages">
    <p class="help-block">Загрузите txt файл с сообщениями в формате:
    <pre>
заголовок темы форума|1 сообщение темы|...|N сообщение темы 
    </pre>    
    </p>
  </div>
  
  <div id="serverloadblock" style="display:none;">
    <pre>
загрузите по ftp файл с сообщениями в папку <strong>messages</strong>
    </pre>
  </div>
  
  <input type="submit" class="btn btn-default" id="button_load_messages">
</form>
    </div>
    <div role="tabpanel" class="tab-pane" id="profile">
<form enctype="multipart/form-data" action="load_users.php" method="POST">
  <div class="form-group">
    <label for="countUsers">Число пользователй для импорта</label>
    <input type="number" class="form-control" id="countUsers" style="width:100px" value="0" name="count_users">
    <p class="help-block">Количество, которое будет рандомно взято из <em>файла с никами</em>.<br>
    Укажите 0, если нужно загрузить весь <em>файл с никами</em>.
    </p>    
  </div>
  <div class="form-group">
    <label for="fileNicks">Файл с никами</label>
    <input type="file" id="fileNicks" name="file_nicks">
    <p class="help-block">Загрузите txt файл с никами
    </p>
  </div>
  <div class="form-group">
    <label for="inputCity">Файл с городами</label>
    <input type="file" id="inputCity" name="file_city">
    <p class="help-block">Загрузите txt файл с городами<br>
    <small>(рандомно назначается 50% пользователям)</small>
    </p>
  </div>
  <div class="form-group">
    <label for="inputSig">Файл с сигнатурами</label>
    <input type="file" id="inputSig" name="file_sig">
    <p class="help-block">Загрузите txt файл с сигнатурами<br>
    <small>(рандомно назначается 50% пользователям)</small>
    </p>
  </div>  
  <hr>
  <div>
  Загрузите с помощью ftp изображения в папку avatars<br>
  <small>(рандомно назначается 50% пользователям)</small>
  </div>
  <hr>
  <input type="submit" class="btn btn-default" id="button_load_users">
</form>
    </div>
	<div role="tabpanel" class="tab-pane" id="help">
		<div>
		<br>
		Установите права 777 на папки messages и upload.<br>
        А также на файл закачиваемый в messages.
		</div>
	</div>	
	
	
  </div>
</div>
</section>
<footer style="padding-top:100px">
<a href="?exit=1">выход</a>
</footer>
</body>
</html>