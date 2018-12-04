<?php
set_time_limit(0);
function addFileToZip($path, $zip) 
{
	$handler = opendir($path);
	while (($filename = readdir($handler)) !== false) {
		if ($filename != "." && $filename != "..") {
			if (is_dir($path . "/" . $filename)) {
				addFileToZip($path . "/" . $filename, $zip);
			} else {
				$zip->addFile($path . "/" . $filename);
			}
		}
	}
	@closedir($path);
}
$zip = new ZipArchive();
if ($zip->open("pano.zip", ZipArchive::OVERWRITE) === TRUE)
{
	addFileToZip("pano/", $zip);
	$zip->close();
}
unlink("./do.php");
echo "downzip();";
?>