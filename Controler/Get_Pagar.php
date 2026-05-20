<?php

include '../ConexionDB/conexion.php';
include '../DAO/PagoDAO.php';

$data = json_decode(file_get_contents("php://input"), true);

$token   = $data['token'];
$id_cita = $data['id_cita'];
$monto   = $data['monto'];

$secret_key = "sk_test_OxVkTEaOco4nB4jl";

$payload = [

    "amount" => $monto * 100,

    "currency_code" => "PEN",

    "email" => "test@culqi.com",

    "source_id" => $token

];

$curl = curl_init();

curl_setopt_array($curl, [

    CURLOPT_URL => "https://api.culqi.com/v2/charges",

    CURLOPT_RETURNTRANSFER => true,

    CURLOPT_POST => true,

    CURLOPT_POSTFIELDS => json_encode($payload),

    CURLOPT_HTTPHEADER => [

        "Content-Type: application/json",

        "Authorization: Bearer $secret_key"

    ]

]);

$response = curl_exec($curl);

curl_close($curl);

$respuesta = json_decode($response, true);


// SI EL PAGO FUE EXITOSO
if (isset($respuesta['outcome']['type']) &&
    $respuesta['outcome']['type'] == 'venta_exitosa') {

    $pagoDAO = new Pago($conexion);

    // AQUI LLAMAS addPago()
    $guardar = $pagoDAO->agregarPago(

        $id_cita,

        $monto,

        'Pagado',

        $respuesta['id'],

        $respuesta['source']['iin']['card_brand']

    );

    // ACTUALIZAR CITA
    $conexion->query("
        UPDATE citas
        SET estado_pago='Pagado'
        WHERE id='$id_cita'
    ");

    echo json_encode([
        "success" => true
    ]);

} else {

    echo json_encode([

        "success" => false,

        "mensaje" => "Pago rechazado"

    ]);

}