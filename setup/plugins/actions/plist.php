<p>Data-Action</p>

<datalist id="dav">
<?php 
foreach(glob("../../../resources/js/action/*.js") as $filename) {
    $pname= str_replace(".js","",basename($filename));
    echo "<option value=\"$pname\">$pname</option>";
}
?>
</datalist>

<input id="dataActionValue" value="<?php echo $_GET['da'] ?>" list="dav">
<button id="setDataAction">Save</button>