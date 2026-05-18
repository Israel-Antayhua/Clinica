<?php    

require_once(__DIR__ . '/../../Vista/core.php');

$orderId = $_POST['orderId'];

$sql = "SELECT order_date, client_name, client_contact, sub_total, vat, total_amount, discount, grand_total, paid, due, payment_place, gstn 
        FROM orders WHERE order_id = $orderId";

$orderResult = $connect->query($sql);
$orderData = $orderResult->fetch_array();

$orderDate = $orderData[0];
$clientName = $orderData[1];
$clientContact = $orderData[2]; 
$subTotal = $orderData[3];
$vat = $orderData[4];
$totalAmount = $orderData[5]; 
$discount = $orderData[6];
$grandTotal = $orderData[7];
$paid = $orderData[8];
$due = $orderData[9];
$payment_place = $orderData[10];
$gstn = $orderData[11];

$orderItemSql = "SELECT order_item.product_id, order_item.rate, order_item.quantity, order_item.total,
product.product_name FROM order_item
INNER JOIN product ON order_item.product_id = product.product_id 
WHERE order_item.order_id = $orderId";

$orderItemResult = $connect->query($orderItemSql);

$table = '
<style>
table {
    width: 100%;
    border-collapse: collapse;
    font-family: Arial, sans-serif;
    font-size: 14px;
}
.invoice-title {
    text-align: center;
    color: #b30000;
    font-size: 26px;
    text-decoration: underline;
    font-weight: bold;
    padding: 10px 0;
}
td {
    padding: 6px;
}
td, th {
    border: 1px solid #000;
}
.table-header {
    background: #000;
    color: #fff;
    font-weight: bold;
    text-align: center;
    -webkit-print-color-adjust: exact;
}
.client-box td {
    padding: 4px 6px;
    font-size: 14px;
}
.total-title {
    background: #000;
    color: #fff;
    text-align: right;
    padding-right: 10px;
    -webkit-print-color-adjust: exact;
    font-weight: bold;
}
.total-value {
    text-align: right;
    font-weight: bold;
}
.signature {
    text-align: center;
    font-weight: bold;
    color: #b30000;
}
.logo {
    padding: 10px;
    border: none !important;
}
</style>

<table align="center" cellpadding="0" cellspacing="0" style="width: 100%;border:1px solid black;margin-bottom: 10px;">
<tbody>

<tr>
<td colspan="5" class="invoice-title">FACTURA</td>
</tr>

<tr>
<td rowspan="8" colspan="2" style="border-left:1px solid black;">
<img src="/logo.png" alt="logo" width="250px;">
</td>
<td colspan="3" style="text-align: right;">ORIGINAL</td>
</tr>

<tr><td colspan="3" style="text-align:right;">COPIA</td></tr>

<tr>
<td colspan="3" style="text-align:right;color: red;font-style: italic;font-weight: 600;text-decoration: underline;font-size: 25px;">
IMS
</td>
</tr>

<tr><td colspan="3" style="text-align:right;">Dirección de tu Empresa</td></tr>
<tr><td colspan="3" style="text-align:right;">Ciudad - Código Postal</td></tr>
<tr><td colspan="3" style="text-align:right;">Tel: 123456789</td></tr>
<tr><td colspan="3" style="text-align:right;">Email: correo@empresa.com</td></tr>
<tr><td colspan="3" style="text-align:right;color: blue;text-decoration: underline;">correo@empresa.com</td></tr>

<tr>
<td colspan="2" style="padding: 0px;vertical-align: top;border-right:1px solid black;">
<table align="left" style="border: 1px solid black; width: 100%">
<tr>
<td style="width: 74px;vertical-align: top;color: red;" rowspan="3">PARA: </td>
<td style="border-bottom:1px solid red;">&nbsp;'.$clientName.'</td>
</tr>
<tr><td style="border-bottom:1px solid #000;">&nbsp;</td></tr>
<tr><td style="border-bottom:1px solid #000;">&nbsp;</td></tr>
</table>

<table style="width: 100%; border: 1px solid black;">
<tr>
<td style="border-bottom:1px solid red;color: red;">G.S.T.IN : '.$gstn.'</td>
<td style="border-left:1px solid black;border-bottom:1px solid red;color:red;">Teléfono: '.$clientContact.'</td>
</tr>
</table>
</td>

<td colspan="3" style="padding: 0px;vertical-align: top;">
<table style="width: 100%;">
<tr>
<td style="border-bottom:1px solid black;border-top:1px solid black;border-right:1px solid black;color:red;">Factura Nº :</td>
</tr>
<tr>
<td style="border-bottom:1px solid black;border-right:1px solid black;color:red;">Fecha: '.$orderDate.'</td>
</tr>
<tr>
<td style="border-bottom:1px solid black;border-right:1px solid black;color:red;">G.S.T.IN: Empresa</td>
</tr>
</table>
</td>
</tr>

<tr>
<td class="table-header">N°</td>
<td class="table-header">Descripción</td>
<td class="table-header">Cantidad</td>
<td class="table-header">Precio</td>
<td class="table-header">Total</td>
</tr>';

$x = 1;
$cgst = 0;
$igst = 0;

if($payment_place == 2) {
    $igst = $subTotal * 18 / 100;
} else {
    $cgst = $subTotal * 9 / 100;
}

$total = $subTotal + 2*$cgst + $igst;

while($row = $orderItemResult->fetch_array()) {
$table .= '
<tr>
<td>'.$x.'</td>
<td>'.$row[4].'</td>
<td>'.$row[2].'</td>
<td>'.$row[1].'</td>
<td>'.$row[3].'</td>
</tr>';

$x++;
}

$table .= '
<tr>
<td></td><td></td><td></td>
<td class="total-title">Subtotal</td>
<td>'.$subTotal.'</td>
</tr>

<tr>
<td colspan="3" style="padding:5px;">Banco: Nombre del Banco</td>
<td class="total-title">S.G.S.T. 9%</td>
<td>'.$cgst.'</td>
</tr>

<tr>
<td colspan="3" style="padding:5px;">Sucursal: Dirección</td>
</tr>

<tr>
<td colspan="3" style="padding:5px;">IFSC: 78945612301</td>
<td class="total-title">C.G.S.T. 9%</td>
<td>'.$cgst.'</td>
</tr>

<tr>
<td colspan="3" style="padding:5px;">Titular: Nombre Empresa</td>
</tr>

<tr>
<td colspan="3" style="padding:5px;">N° Cuenta: 0000000000</td>
<td class="total-title">I.G.S.T. 18%</td>
<td>'.$igst.'</td>
</tr>

<tr>
<td colspan="3" style="color:red;padding:5px;">Monto en palabras</td>
<td class="total-title">Total</td>
<td>'.$total.'</td>
</tr>

<tr>
<td colspan="3" style="padding:5px;">* Sujeto a términos y condiciones <span style="float:right;">E.&O.E.</span></td>
<td colspan="2" style="text-align:center;color:red;">Por: Nombre de Empresa</td>
</tr>

</tbody>
</table>';

$connect->close();

echo $table;
?>
