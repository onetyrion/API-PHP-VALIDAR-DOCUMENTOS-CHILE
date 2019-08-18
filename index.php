<?php
include "simple_html_dom.php";

$arrContextOptions=array(
    "ssl"=>array(
        "verify_peer"=>false,
        "verify_peer_name"=>false,
    ),
);  

$response = new stdClass();
$cedula = new stdClass;
$url = $_SERVER["REQUEST_URI"];
$url = str_replace("/","",$url);


// Se obtiene el RUT, tipo de documento, numero de serie a validar
// URL DE EJEMPLO /?RUN=19767283-8&type=CEDULA&serial=110017253

if (isset($_GET['RUN']) && isset($_GET['type']) && isset($_GET['serial'])) {
 
    // obtengo la pagina y busco la clase .setWidthOfSecondColumn que es el resultado de la consulta para luego obtener su texto.
    $response = file_get_html("https://portal.sidiv.registrocivil.cl/usuarios-portal/pages/DocumentRequestStatus.xhtml$url", false, stream_context_create($arrContextOptions));
    $result = $response -> find('.setWidthOfSecondColumn',0) -> plaintext;

    $cedula-> estado = (!$result ? "La informacion ingresada no corresponde en nuestros registros" : strval($result));
    echo json_encode($cedula);
} else {
    $cedula -> mensaje  = "Error de escritura, verifique que los parametros esten bien escritos";
    echo json_encode($cedula);
}

?>