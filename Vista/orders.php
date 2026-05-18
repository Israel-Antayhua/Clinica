<?php
require_once '../php_action/db_connect.php'; // Asegúrate que la ruta sea correcta
require_once '../includes/header.php';

// Determinar acción (add, manage, edit)
$action = isset($_GET['o']) ? $_GET['o'] : 'manord'; // Por defecto, gestionar pedidos

// Código oculto para identificar la acción en JS (se mantiene igual)
if($action == 'add') {
    echo "<div class='div-request div-hide'>add</div>";
} else if($action == 'manord') {
    echo "<div class='div-request div-hide'>manord</div>";
} else if($action == 'editOrd') {
    echo "<div class='div-request div-hide'>editOrd</div>";
}
?>

<ol class="breadcrumb">
  <li><a href="dashboard.php">Inicio</a></li>
  <li>Pedido</li>
  <li class="active">
    <?php if($action == 'add') { ?>
        Agregar Pedido
      <?php } else if($action == 'manord') { ?>
        Gestionar Pedidos
      <?php } else if($action == 'editOrd') { ?>
        Editar Pedido
      <?php } ?>
  </li>
</ol>


<h4>
    <i class='glyphicon glyphicon-circle-arrow-right'></i>
    <?php if($action == 'add') {
        echo "Agregar Nuevo Pedido"; // Traducido
    } else if($action == 'manord') {
        echo "Gestionar Pedidos"; // Traducido
    } else if($action == 'editOrd') {
        echo "Editar Pedido"; // Traducido
    }
    ?>
</h4>



<div class="panel panel-default">
    <div class="panel-heading">

        <?php if($action == 'add') { ?>
        <i class="glyphicon glyphicon-plus-sign"></i> Agregar Pedido
        <?php } else if($action == 'manord') { ?>
            <i class="glyphicon glyphicon-edit"></i> Gestionar Pedidos
        <?php } else if($action == 'editOrd') { ?>
            <i class="glyphicon glyphicon-edit"></i> Editar Pedido
        <?php } ?>

    </div> <div class="panel-body">

        <?php if($action == 'add') {
            // FORMULARIO PARA AGREGAR PEDIDO
            ?>

            <div class="success-messages"></div> <form class="form-horizontal" method="POST" action="../php_action/DAO/createOrder.php" id="createOrderForm">

    <div class="form-group">
        <label for="orderDate" class="col-sm-2 control-label">Fecha Pedido</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="orderDate" name="orderDate" autocomplete="off" />
        </div>
    </div>
    <div class="form-group">
        <label for="clientName" class="col-sm-2 control-label">Nombre Cliente</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="clientName" name="clientName" placeholder="Nombre del Cliente" autocomplete="off" required />
        </div>
    </div>
    <div class="form-group">
        <label for="clientContact" class="col-sm-2 control-label">Contacto Cliente</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="clientContact" name="clientContact" placeholder="Número de Contacto" autocomplete="off" />
        </div>
    </div>

    <table class="table" id="productTable">
        <thead>
            <tr>
                <th style="width:40%;">Producto</th>
                <th style="width:20%;">Precio (S/)</th>
                <th style="width:10%;">Cant. Disponible</th>
                <th style="width:15%;">Cantidad</th>
                <th style="width:15%;">Total (S/)</th>
                <th style="width:10%;"></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $arrayNumber = 0;
            // Iniciar con una fila vacía
            for($x = 1; $x < 2; $x++) { ?>
                <tr id="row<?php echo $x; ?>" class="<?php echo $arrayNumber; ?>">
                    <td style="padding-left:20px;">
                        <div class="form-group">
                            <select class="form-control productName" name="productName[]" id="productName<?php echo $x; ?>" onchange="getProductData(<?php echo $x; ?>)" required>
                                <option value="">-- SELECCIONAR --</option>
                                <?php
                                $productSql = "SELECT * FROM product WHERE active = 1 AND status = 1 ORDER BY product_name ASC";
                                $productData = $connect->query($productSql);
                                while($row = $productData->fetch_array()) {
                                    echo "<option value='".$row['product_id']."' id='changeProduct".$row['product_id']."'>".$row['product_name']."</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </td>
                    <td style="padding-left:20px;">
                        <input type="text" name="rate[]" id="rate<?php echo $x; ?>" autocomplete="off" class="form-control" readonly />
                        <input type="hidden" name="rateValue[]" id="rateValue<?php echo $x; ?>" autocomplete="off" class="form-control" />
                    </td>
                    <td style="padding-left:20px;">
                        <div class="form-group">
                            <p id="available_quantity<?php echo $x; ?>" style="line-height: 30px; margin:0;">-</p>
                        </div>
                    </td>
                    <td style="padding-left:20px;">
                        <div class="form-group">
                            <input type="number" name="quantity[]" id="quantity<?php echo $x; ?>" onkeyup="getTotal(<?php echo $x ?>)" autocomplete="off" class="form-control" min="1" required />
                        </div>
                    </td>
                    <td style="padding-left:20px;">
                        <input type="text" name="total[]" id="total<?php echo $x; ?>" autocomplete="off" class="form-control" disabled="true" />
                        <input type="hidden" name="totalValue[]" id="totalValue<?php echo $x; ?>" autocomplete="off" class="form-control" />
                    </td>
                    <td>
                        <button class="btn btn-default removeProductRowBtn" type="button" id="removeProductRowBtn" onclick="removeProductRow(<?php echo $x; ?>)"><i class="glyphicon glyphicon-trash"></i></button>
                    </td>
                </tr>
            <?php
            $arrayNumber++;
            } // /for
            ?>
        </tbody>
    </table>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="subTotal" class="col-sm-4 control-label">Sub Total (S/)</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="subTotal" name="subTotal" disabled="true" />
                    <input type="hidden" class="form-control" id="subTotalValue" name="subTotalValue" />
                </div>
            </div>
            <div class="form-group">
                <label for="vat" class="col-sm-4 control-label">IGV 18% (S/)</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="vat" readonly="true" /> 
                    <input type="hidden" class="form-control" id="vatValue" name="vatValue" />
                </div>
            </div>
            <div class="form-group">
                <label for="totalAmount" class="col-sm-4 control-label">Monto Total (S/)</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="totalAmount" name="totalAmount" disabled="true"/>
                    <input type="hidden" class="form-control" id="totalAmountValue" name="totalAmountValue" />
                </div>
            </div>
            <div class="form-group">
                <label for="discount" class="col-sm-4 control-label">Descuento (S/)</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="discount" name="discount" onkeyup="discountFunc()" autocomplete="off" value="0" />
                </div>
            </div>
            <div class="form-group">
                <label for="grandTotal" class="col-sm-4 control-label">Total Neto (S/)</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="grandTotal" name="grandTotal" disabled="true" />
                    <input type="hidden" class="form-control" id="grandTotalValue" name="grandTotalValue" />
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="paid" class="col-sm-4 control-label">Monto Pagado (S/)</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="paid" name="paid" autocomplete="off" onkeyup="paidAmount()" value="0" />
                </div>
            </div>
            <div class="form-group">
                <label for="due" class="col-sm-4 control-label">Saldo Pendiente (S/)</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="due" name="due" disabled="true" />
                    <input type="hidden" class="form-control" id="dueValue" name="dueValue" />
                </div>
            </div>
            <div class="form-group">
                <label for="paymentType" class="col-sm-4 control-label">Tipo Pago</label>
                <div class="col-sm-8">
                    <select class="form-control" name="paymentType" id="paymentType" required>
                        <option value="">-- SELECCIONAR --</option>
                        <option value="1">Cheque</option>
                        <option value="2">Efectivo</option>
                        <option value="3">Tarjeta Crédito</option>
                        <option value="4">Transferencia</option>
                        <option value="5">Crédito</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="paymentStatus" class="col-sm-4 control-label">Estado Pago</label>
                <div class="col-sm-8">
                    <select class="form-control" name="paymentStatus" id="paymentStatus" required>
                        <option value="">-- SELECCIONAR --</option>
                        <option value="1">Pago Completo</option>
                        <option value="2">Pago Parcial</option>
                        <option value="3">Pendiente</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label for="paymentPlace" class="col-sm-4 control-label">Lugar de Pago</label>
                <div class="col-sm-8">
                    <select class="form-control" name="paymentPlace" id="paymentPlace" required>
                        <option value="">-- SELECCIONAR --</option>
                        <option value="1">Tienda</option>
                        <option value="2">Online</option>
                        </select>
                </div>
            </div>
            <div class="form-group">
                <label for="gstn" class="col-sm-4 control-label">RUC/DNI/Otro</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="gstn" name="gstn" placeholder="Nro. Identificación Fiscal" />
                </div>
            </div>
        </div>
    </div>
    
    <div class="form-group submitButtonFooter">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="button" class="btn btn-default" onclick="addRow()" id="addRowBtn" data-loading-text="Cargando..."> <i class="glyphicon glyphicon-plus-sign"></i> Agregar Fila </button>
            <button type="submit" id="createOrderBtn" data-loading-text="Guardando..." class="btn btn-success"><i class="glyphicon glyphicon-ok-sign"></i> Guardar Cambios</button>
            <button type="reset" class="btn btn-default" onclick="resetOrderForm()"><i class="glyphicon glyphicon-erase"></i> Limpiar</button>
        </div>
    </div>
</form>
        <?php } else if($action == 'manord') {
            // TABLA PARA GESTIONAR PEDIDOS
            ?>
            <div id="success-messages"></div>
            
            <table class="table table-hover table-striped" id="manageOrderTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Fecha Pedido</th>
                        <th>Nombre Cliente</th>
                        <th>Contacto</th>
                        <th>Total Items</th>
                        <th>Estado Pago</th>
                        <th>Ticket Asociado</th> <th style="min-width: 90px;">Opción</th>
                    </tr>
                </thead>
            </table>

        <?php
        } else if($action == 'editOrd') {
            // FORMULARIO PARA EDITAR PEDIDO
        ?>
        <div class="success-messages"></div> <form class="form-horizontal" method="POST" action="../php_action/DAO/editOrder.php" id="editOrderForm">

            <?php $orderId = $_GET['i'];

            // Consulta para obtener datos del pedido y cliente
            $sql = "SELECT order_id, order_date, client_name, client_contact, sub_total, vat, total_amount, discount, grand_total, paid, due, payment_type, payment_status, payment_place, gstn
                    FROM orders
                    WHERE order_id = {$orderId}";

                $result = $connect->query($sql);
                // Usar fetch_assoc para nombres de columna
                $data = $result->fetch_assoc();
            ?>

              <div class="form-group">
                <label for="orderDate" class="col-sm-2 control-label">Fecha Pedido</label> <div class="col-sm-10">
                  <input type="text" class="form-control" id="orderDate" name="orderDate" autocomplete="off" value="<?php echo $data['order_date'] ?>" />
                </div>
              </div> <div class="form-group">
                <label for="clientName" class="col-sm-2 control-label">Nombre Cliente</label> <div class="col-sm-10">
                  <input type="text" class="form-control" id="clientName" name="clientName" placeholder="Nombre del Cliente" autocomplete="off" value="<?php echo $data['client_name'] ?>" /> </div>
              </div> <div class="form-group">
                <label for="clientContact" class="col-sm-2 control-label">Contacto Cliente</label> <div class="col-sm-10">
                  <input type="text" class="form-control" id="clientContact" name="clientContact" placeholder="Número de Contacto" autocomplete="off" value="<?php echo $data['client_contact'] ?>" /> </div>
              </div> <table class="table" id="productTable">
                <thead>
                    <tr>
                        <th style="width:40%;">Producto</th> <th style="width:20%;">Precio (S/)</th> <th style="width:15%;">Cant. Disponible</th> <th style="width:15%;">Cantidad</th> <th style="width:15%;">Total (S/)</th> <th style="width:10%;"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    $orderItemSql = "SELECT order_item.order_item_id, order_item.order_id, order_item.product_id, order_item.quantity, order_item.rate, order_item.total FROM order_item WHERE order_item.order_id = {$orderId}";
                        $orderItemResult = $connect->query($orderItemSql);

                    $arrayNumber = 0;
                    $x = 1;
                    while($orderItemData = $orderItemResult->fetch_assoc()) { // Usar fetch_assoc ?>
                        <tr id="row<?php echo $x; ?>" class="<?php echo $arrayNumber; ?>">
                            <td style="padding-left:20px;">
                                <div class="form-group">
                                <select class="form-control productName" name="productName[]" id="productName<?php echo $x; ?>" onchange="getProductData(<?php echo $x; ?>)" required>
                                    <option value="">-- SELECCIONAR --</option> <?php
                                        // Obtener TODOS los productos activos
                                        $productSql = "SELECT product_id, product_name FROM product WHERE active = 1 AND status = 1 ORDER BY product_name ASC";
                                        $productData = $connect->query($productSql);
                                        while($row = $productData->fetch_array()) {
                                            $selected = ($row['product_id'] == $orderItemData['product_id']) ? "selected" : "";
                                            echo "<option value='".$row['product_id']."' id='changeProduct".$row['product_id']."' ".$selected." >".$row['product_name']."</option>";
                                         } // /while
                                    ?>
                                </select>
                                </div>
                            </td>
                            <td style="padding-left:20px;">
                                <input type="text" name="rate[]" id="rate<?php echo $x; ?>" autocomplete="off" class="form-control" readonly value="<?php echo $orderItemData['rate']; ?>" />
                                <input type="hidden" name="rateValue[]" id="rateValue<?php echo $x; ?>" autocomplete="off" class="form-control" value="<?php echo $orderItemData['rate']; ?>" />
                            </td>
                             <td style="padding-left:20px;">
                                <div class="form-group">
                                    <?php
                                        // Mostrar la cantidad disponible del producto seleccionado
                                        $productQuantitySql = "SELECT quantity FROM product WHERE product_id = ".$orderItemData['product_id'];
                                        $productQuantityData = $connect->query($productQuantitySql);
                                        $availableQuantity = ($productQuantityData && $productQuantityData->num_rows > 0) ? $productQuantityData->fetch_row()[0] : '0';
                                        echo '<p id="available_quantity'.$x.'" style="line-height: 30px; margin:0;">'.$availableQuantity.'</p>';
                                    ?>
                                </div>
                            </td>
                            <td style="padding-left:20px;">
                                <div class="form-group">
                                <input type="number" name="quantity[]" id="quantity<?php echo $x; ?>" onkeyup="getTotal(<?php echo $x ?>)" autocomplete="off" class="form-control" min="1" required value="<?php echo $orderItemData['quantity']; ?>" />
                                </div>
                            </td>
                            <td style="padding-left:20px;">
                                <input type="text" name="total[]" id="total<?php echo $x; ?>" autocomplete="off" class="form-control" disabled="true" value="<?php echo $orderItemData['total']; ?>"/>
                                <input type="hidden" name="totalValue[]" id="totalValue<?php echo $x; ?>" autocomplete="off" class="form-control" value="<?php echo $orderItemData['total']; ?>"/>
                            </td>
                            <td>
                                <button class="btn btn-default removeProductRowBtn" type="button" onclick="removeProductRow(<?php echo $x; ?>)"><i class="glyphicon glyphicon-trash"></i></button>
                            </td>
                        </tr>
                    <?php
                    $arrayNumber++;
                    $x++;
                    } // /while
                    ?>
                </tbody>
              </table>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="subTotal" class="col-sm-4 control-label">Sub Total (S/)</label> <div class="col-sm-8">
                      <input type="text" class="form-control" id="subTotal" name="subTotal" disabled="true" value="<?php echo $data['sub_total'] ?>" />
                      <input type="hidden" class="form-control" id="subTotalValue" name="subTotalValue" value="<?php echo $data['sub_total'] ?>" />
                    </div>
                  </div> <div class="form-group">
                    <label for="vat" class="col-sm-4 control-label">IGV 18% (S/)</label> <div class="col-sm-8">
                      <input type="text" class="form-control" id="vat" name="vat" disabled="true" value="<?php echo $data['vat'] ?>" />
                      <input type="hidden" class="form-control" id="vatValue" name="vatValue" value="<?php echo $data['vat'] ?>" />
                    </div>
                  </div> <div class="form-group">
                    <label for="totalAmount" class="col-sm-4 control-label">Monto Total (S/)</label> <div class="col-sm-8">
                      <input type="text" class="form-control" id="totalAmount" name="totalAmount" disabled="true" value="<?php echo $data['total_amount'] ?>" />
                      <input type="hidden" class="form-control" id="totalAmountValue" name="totalAmountValue" value="<?php echo $data['total_amount'] ?>" />
                    </div>
                  </div> <div class="form-group">
                    <label for="discount" class="col-sm-4 control-label">Descuento (S/)</label> <div class="col-sm-8">
                      <input type="text" class="form-control" id="discount" name="discount" onkeyup="discountFunc()" autocomplete="off" value="<?php echo $data['discount'] ?>" />
                    </div>
                  </div> <div class="form-group">
                    <label for="grandTotal" class="col-sm-4 control-label">Total Neto (S/)</label> <div class="col-sm-8">
                      <input type="text" class="form-control" id="grandTotal" name="grandTotal" disabled="true" value="<?php echo $data['grand_total'] ?>" />
                      <input type="hidden" class="form-control" id="grandTotalValue" name="grandTotalValue" value="<?php echo $data['grand_total'] ?>" />
                    </div>
                  </div> <div class="form-group">
                        <label for="gstn" class="col-sm-4 control-label">RUC/DNI/Otro</label> <div class="col-sm-8">
                          <input type="text" class="form-control" id="gstn" name="gstn" value="<?php echo $data['gstn'] ?>" />
                        </div>
                    </div></div> <div class="col-md-6">
                  <div class="form-group">
                    <label for="paid" class="col-sm-4 control-label">Monto Pagado (S/)</label> <div class="col-sm-8">
                      <input type="text" class="form-control" id="paid" name="paid" autocomplete="off" onkeyup="paidAmount()" value="<?php echo $data['paid'] ?>" />
                    </div>
                  </div> <div class="form-group">
                    <label for="due" class="col-sm-4 control-label">Saldo Pendiente (S/)</label> <div class="col-sm-8">
                      <input type="text" class="form-control" id="due" name="due" disabled="true" value="<?php echo $data['due'] ?>" />
                      <input type="hidden" class="form-control" id="dueValue" name="dueValue" value="<?php echo $data['due'] ?>" />
                    </div>
                  </div> <div class="form-group">
                    <label for="paymentType" class="col-sm-4 control-label">Tipo Pago</label> <div class="col-sm-8">
                      <select class="form-control" name="paymentType" id="paymentType" required> <option value="">-- SELECCIONAR --</option> <option value="1" <?php if($data['payment_type'] == 1) echo "selected"; ?> >Cheque</option> <option value="2" <?php if($data['payment_type'] == 2) echo "selected"; ?> >Efectivo</option> <option value="3" <?php if($data['payment_type'] == 3) echo "selected"; ?> >Tarjeta Crédito</option> <option value="4" <?php if($data['payment_type'] == 4) echo "selected"; ?> >Transferencia</option> <option value="5" <?php if($data['payment_type'] == 5) echo "selected"; ?> >Crédito</option> </select>
                    </div>
                  </div> <div class="form-group">
                    <label for="paymentStatus" class="col-sm-4 control-label">Estado Pago</label> <div class="col-sm-8">
                      <select class="form-control" name="paymentStatus" id="paymentStatus" required> <option value="">-- SELECCIONAR --</option> <option value="1" <?php if($data['payment_status'] == 1) echo "selected"; ?> >Pago Completo</option> <option value="2" <?php if($data['payment_status'] == 2) echo "selected"; ?> >Pago Parcial</option> <option value="3" <?php if($data['payment_status'] == 3) echo "selected"; ?> >Pendiente</option> </select>
                    </div>
                  </div> </div> </div><div class="form-group editButtonFooter">
                <div class="col-sm-offset-2 col-sm-10">
                <button type="button" class="btn btn-default" onclick="addRow()" id="addRowBtn" data-loading-text="Cargando..."> <i class="glyphicon glyphicon-plus-sign"></i> Agregar Fila </button> <input type="hidden" name="orderId" id="orderId" value="<?php echo $orderId; ?>" />

                <button type="submit" id="editOrderBtn" data-loading-text="Guardando..." class="btn btn-success"><i class="glyphicon glyphicon-ok-sign"></i> Guardar Cambios</button> </div>
              </div>
            </form>

            <?php
        } // /get order else ?>


    </div> </div> <div class="modal fade" tabindex="-1" role="dialog" id="paymentOrderModal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><i class="glyphicon glyphicon-edit"></i> Editar Pago</h4>
      </div>
      <div class="modal-body form-horizontal" style="max-height:500px; overflow:auto;" >
        <div class="paymentOrderMessages"></div>
              <div class="form-group">
                <label for="due" class="col-sm-3 control-label">Monto Pendiente (S/)</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" id="due" name="due" disabled="true" />
                </div>
              </div>
              <div class="form-group">
                <label for="payAmount" class="col-sm-3 control-label">Monto a Pagar (S/)</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" id="payAmount" name="payAmount"/>
                </div>
              </div>
              <div class="form-group">
                <label for="paymentTypeModal" class="col-sm-3 control-label">Tipo Pago</label>
                <div class="col-sm-9">
                  <select class="form-control" name="paymentTypeModal" id="paymentTypeModal" required>
                    <option value="">-- SELECCIONAR --</option>
                    <option value="1">Cheque</option>
                    <option value="2">Efectivo</option>
                    <option value="3">Tarjeta Crédito</option>
                    <option value="4">Transferencia</option>
                    <option value="5">Crédito</option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label for="paymentStatusModal" class="col-sm-3 control-label">Estado Pago</label>
                <div class="col-sm-9">
                  <select class="form-control" name="paymentStatusModal" id="paymentStatusModal" required>
                    <option value="">-- SELECCIONAR --</option>
                    <option value="1">Pago Completo</option>
                    <option value="2">Pago Parcial</option>
                    <option value="3">Pendiente</option>
                  </select>
                </div>
              </div>
      </div> 
	  <div class="modal-footer">
         <input type="hidden" name="modalOrderId" id="modalOrderId" />
        <button type="button" class="btn btn-default" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> Cerrar</button>
        <button type="button" class="btn btn-primary" id="updatePaymentOrderBtn" data-loading-text="Guardando..."> <i class="glyphicon glyphicon-ok-sign"></i> Guardar Cambios</button>
      </div>
    </div></div></div><div class="modal fade" tabindex="-1" role="dialog" id="removeOrderModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><i class="glyphicon glyphicon-trash"></i> Eliminar Pedido</h4>
      </div>
      <div class="modal-body">
        <div class="removeOrderMessages"></div>
        <p>¿Realmente deseas eliminar este pedido?</p>
        <p><strong>Advertencia:</strong> Esta acción no se puede deshacer.</p>
      </div>
      <div class="modal-footer removeProductFooter">
         <input type="hidden" name="removeOrderId" id="removeOrderId" />
        <button type="button" class="btn btn-default" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> Cerrar</button>
        <button type="button" class="btn btn-danger" id="removeOrderBtn" data-loading-text="Eliminando..."> <i class="glyphicon glyphicon-ok-sign"></i> Confirmar Eliminación</button>
      </div>
    </div></div></div><script src="../custom/js/order.js"></script>

<?php require_once '../includes/footer.php'; ?>