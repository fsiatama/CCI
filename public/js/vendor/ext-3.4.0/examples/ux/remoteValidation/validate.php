<?

$post = $_POST;

$field = @$post["field"];
$value = @$post["value"];

$o = new stdClass;
$o->success = true;
$o->valid = false;
$o->reason = "Example of server reason";

if("ExtJS" == $value) {
	$o->valid = true;
	unset($o->reason);
}

header("Content-Type: application/json");
echo json_encode($o);

?>
