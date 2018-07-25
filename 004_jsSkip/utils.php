<?php
$content = '';
$jspath = '';
$status = '';

if(isset($_POST["jspath"]) && ! empty($_POST["jspath"]))
{
    $jspath = $_POST["jspath"];
    $content = vts_load_js_content($jspath);
}

if(isset($_POST["jscontent"]) && ! empty($_POST["jscontent"]))
{
    vts_save_js($jspath, $_POST["jscontent"]);
    $status = 'Save ok';
    touch($jspath, mktime(14, 23, 00, 12, 30, 2015));
}

function vts_load_js_content($jspath)
{
    $myfile = fopen($jspath, "r") or die("Unable to open file!");
    $content = fread($myfile, filesize($jspath));
    fclose($myfile);
    return $content;
}

function vts_save_js($jspath, $jscontent)
{
    $myfile = fopen($jspath, "w") or die("Unable to open file!");
    fwrite($myfile, $jscontent);
    fclose($myfile);
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>open and write</title>
<style>
* {
	margin: 0;
	padding: 0;
}

#container {
	width: 960px;
	padding: 10px;
	margin: 40px auto;
	border: 1px solid red;
}
</style>
<script>
        function loadjs()
        {
            var form = document.getElementById("form1");
            form.submit();
        }
    </script>
</head>
<body>
    <?php echo __FILE__; ?>
    
	<div id="container">
		<form id="form1" name="form1" method="post"
			action="<?php echo $_SERVER['PHP_SELF']; ?>">
			<table cellpadding="0" cellspacing="0" style="width: 100%">
				<tr>
					<td style="width: 50px;">Path:</td>
					<td><input type="text" name="jspath" id="jspath"
						style="width: 95%;" value="<?php echo $jspath;?>" /> <input
						type="button" name="btnloadjs" id="btnloadjs" onclick="loadjs();"
						value="Load" /></td>
				</tr>
				<tr>
					<td colspan="2"><textarea name="jscontent" id="jscontent"
							style="width: 100%; height: 520px"><?php echo $content; ?></textarea>
					</td>
				</tr>
				<tr>
					<td colspan="2"><input type="submit" value="Save" /></td>
				</tr>
				<tr>
					<td colspan="2"><?php echo $status;?></td>
				</tr>
			</table>
		</form>
	</div>
</body>
</html>