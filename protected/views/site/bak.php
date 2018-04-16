<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>上传酒吧资料</title>
<form enctype="multipart/form-data" action="/jiubaServer/wineBarInfo/regist.action" method="post">
	名称:
	<input name='name' /> &nbsp; 
	简介:
	<input name='intro' /> &nbsp; 
	电话号码:
	<input name='tel' /> &nbsp; 
	城市:
	<input name='city' /> &nbsp; 
	地址:
	<input name='address' /> &nbsp; 
	经度（浮点数）:
	<input name='lon' /> &nbsp; 
	纬度（浮点数）:
	<input name='lat' /> &nbsp;
	封面文件_320*480比例:
	<input name='coverFile_320_480' type="file"/> &nbsp;
	封面文件_640*640比例:
	<input name='coverFile_640_640' type="file"/> &nbsp; 
	封面文件_640*1136比例:
	<input name='coverFile_640_1136' type="file"/> &nbsp; 
	<input type="submit" value="Submit" />
</form>
</head>
<body>
</body>
</html>
