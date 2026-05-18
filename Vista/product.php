<?php require_once '../php_action/db_connect.php'?>
<?php require_once '../includes/header.php'; ?>

<div class="row">
    <div class="col-md-12">

        <ol class="breadcrumb">
          <li><a href="dashboard.php">Inicio</a></li>
          <li class="active">Producto</li>
        </ol>

        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="page-heading"> <i class="glyphicon glyphicon-edit"></i> Gestionar Productos</div>
            </div> <div class="panel-body">

                <div class="remove-messages"></div>

                <div class="div-action pull pull-right" style="padding-bottom:20px;">
                    <button class="btn btn-default button1" data-toggle="modal" id="addProductModalBtn" data-target="#addProductModal"> <i class="glyphicon glyphicon-plus-sign"></i> Agregar Producto </button>
                </div> <table class="table table-hover table-striped" id="manageProductTable"> <thead>
                        <tr>
                            <th style="width:10%;">Foto</th>
                            <th>Nombre Producto</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Marca</th>
                            <th>Categoría</th>
                            <th>Estado</th>
                            <th style="width:15%;">Opciones</th>
                        </tr>
                    </thead>
                </table>
                </div> </div> </div> </div> <div class="modal fade" id="addProductModal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">

        <form class="form-horizontal" id="submitProductForm" action="../php_action/DAO/createProduct.php" method="POST" enctype="multipart/form-data">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"><i class="fa fa-plus"></i> Agregar Producto</h4>
          </div>

          <div class="modal-body" style="max-height:450px; overflow:auto;">

            <div id="add-product-messages"></div>

            <div class="form-group">
                <label for="productImage" class="col-sm-3 control-label">Imagen Producto: </label>
                    <div class="col-sm-8">
                            <div id="kv-avatar-errors-1" class="center-block" style="display:none;"></div>
                        <div class="kv-avatar center-block">
                            <input type="file" class="form-control" id="productImage" name="productImage" class="file-loading" style="width:auto;"/>
                        </div>
                    </div>
            </div> <div class="form-group">
                <label for="productName" class="col-sm-3 control-label">Nombre Producto: </label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="productName" placeholder="Nombre del Producto" name="productName" autocomplete="off" required>
                    </div>
            </div> <div class="form-group">
                <label for="quantity" class="col-sm-3 control-label">Cantidad: </label>
                    <div class="col-sm-8">
                      <input type="number" class="form-control" id="quantity" placeholder="Cantidad" name="quantity" autocomplete="off" required min="0">
                    </div>
            </div> <div class="form-group">
                <label for="rate" class="col-sm-3 control-label">Precio (S/): </label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="rate" placeholder="Precio" name="rate" autocomplete="off" required pattern="^\d+(\.\d{1,2})?$">
                    </div>
            </div> <div class="form-group">
                <label for="brandName" class="col-sm-3 control-label">Marca: </label>
                    <div class="col-sm-8">
                      <select class="form-control" id="brandName" name="brandName" required>
                        <option value="">-- SELECCIONAR --</option>
                        <?php
                        $sql = "SELECT brand_id, brand_name FROM brands WHERE brand_status = 1 AND brand_active = 1 ORDER BY brand_name ASC";
                                $result = $connect->query($sql);
                                while($row = $result->fetch_array()) {
                                    echo "<option value='".$row[0]."'>".$row[1]."</option>";
                                } // while
                        ?>
                      </select>
                    </div>
            </div> <div class="form-group">
                <label for="categoryName" class="col-sm-3 control-label">Categoría: </label>
                    <div class="col-sm-8">
                      <select type="text" class="form-control" id="categoryName" name="categoryName" required>
                        <option value="">-- SELECCIONAR --</option>
                        <?php
                        $sql = "SELECT categories_id, categories_name FROM categories WHERE categories_status = 1 AND categories_active = 1 ORDER BY categories_name ASC";
                                $result = $connect->query($sql);
                                while($row = $result->fetch_array()) {
                                    echo "<option value='".$row[0]."'>".$row[1]."</option>";
                                } // while
                        ?>
                      </select>
                    </div>
            </div> <div class="form-group">
                <label for="productStatus" class="col-sm-3 control-label">Estado: </label>
                    <div class="col-sm-8">
                      <select class="form-control" id="productStatus" name="productStatus" required>
                        <option value="">-- SELECCIONAR --</option>
                        <option value="1">Disponible</option>
                        <option value="2">No Disponible</option>
                      </select>
                    </div>
            </div> </div> <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> Cerrar</button>

            <button type="submit" class="btn btn-primary" id="createProductBtn" data-loading-text="Guardando..." autocomplete="off"> <i class="glyphicon glyphicon-ok-sign"></i> Guardar Cambios</button>
          </div> </form> </div> </div> </div>
<div class="modal fade" id="editProductModal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">

          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"><i class="fa fa-edit"></i> Editar Producto</h4>
          </div>
          <div class="modal-body" style="max-height:450px; overflow:auto;">

            <div class="div-loading text-center">
                <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
                <span class="sr-only">Cargando...</span>
            </div>

            <div class="div-result">

              <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#photo" aria-controls="photo" role="tab" data-toggle="tab">Foto</a></li>
                <li role="presentation"><a href="#productInfo" aria-controls="productInfo" role="tab" data-toggle="tab">Info Producto</a></li>
              </ul>

              <div class="tab-content">

                <div role="tabpanel" class="tab-pane active" id="photo">
                    <form action="../php_action/DAO/editProductImage.php" method="POST" id="updateProductImageForm" class="form-horizontal" enctype="multipart/form-data">

                    <br />
                    <div id="edit-productPhoto-messages"></div>

                    <div class="form-group">
                    <label for="editProductImage" class="col-sm-3 control-label">Imagen Actual: </label>
                        <div class="col-sm-8 text-center">
                            <img src="" id="getProductImage" class="thumbnail" style="width:150px; height:auto; margin: 0 auto;" />
                        </div>
                    </div> <div class="form-group">
                        <label for="editProductImage" class="col-sm-3 control-label">Seleccionar Nueva Foto: </label>
                            <div class="col-sm-8">
                                <div id="kv-avatar-errors-1" class="center-block" style="display:none;"></div>
                                <div class="kv-avatar center-block">
                                    <input type="file" class="form-control" id="editProductImage" name="editProductImage" class="file-loading" style="width:auto;"/>
                                </div>
                            </div>
                    </div>
                    
                    <input type="hidden" name="editProductImageId" id="editProductImageId" />

                    <div class="modal-footer editProductPhotoFooter">
                        <button type="button" class="btn btn-default" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> Cerrar</button>
                        <button type="submit" class="btn btn-success" id="editProductImageBtn" data-loading-text="Guardando..."> <i class="glyphicon glyphicon-ok-sign"></i> Guardar Cambios</button>
                    </div>
                      </form>
                      </div>
                <div role="tabpanel" class="tab-pane" id="productInfo">
                    <form class="form-horizontal" id="editProductForm" action="../php_action/DAO/editProduct.php" method="POST">
                    <br />

                    <div id="edit-product-messages"></div>

                    <div class="form-group">
                    <label for="editProductName" class="col-sm-3 control-label">Nombre Producto: </label>
                        <div class="col-sm-8">
                          <input type="text" class="form-control" id="editProductName" placeholder="Nombre del Producto" name="editProductName" autocomplete="off" required>
                        </div>
                    </div> <div class="form-group">
                        <label for="editQuantity" class="col-sm-3 control-label">Cantidad: </label>
                            <div class="col-sm-8">
                              <input type="number" class="form-control" id="editQuantity" placeholder="Cantidad" name="editQuantity" autocomplete="off" required min="0">
                            </div>
                    </div> <div class="form-group">
                        <label for="editRate" class="col-sm-3 control-label">Precio (S/): </label>
                            <div class="col-sm-8">
                              <input type="text" class="form-control" id="editRate" placeholder="Precio" name="editRate" autocomplete="off" required pattern="^\d+(\.\d{1,2})?$">
                            </div>
                    </div> <div class="form-group">
                        <label for="editBrandName" class="col-sm-3 control-label">Marca: </label>
                            <div class="col-sm-8">
                              <select class="form-control" id="editBrandName" name="editBrandName" required>
                                <option value="">-- SELECCIONAR --</option>
                                <?php
                                $sql = "SELECT brand_id, brand_name FROM brands WHERE brand_status = 1 AND brand_active = 1 ORDER BY brand_name ASC";
                                        $result = $connect->query($sql);
                                        while($row = $result->fetch_array()) {
                                            echo "<option value='".$row[0]."'>".$row[1]."</option>";
                                        } // while
                                ?>
                              </select>
                            </div>
                    </div> <div class="form-group">
                        <label for="editCategoryName" class="col-sm-3 control-label">Categoría: </label>
                            <div class="col-sm-8">
                              <select type="text" class="form-control" id="editCategoryName" name="editCategoryName" required>
                                <option value="">-- SELECCIONAR --</option>
                                <?php
                                $sql = "SELECT categories_id, categories_name FROM categories WHERE categories_status = 1 AND categories_active = 1 ORDER BY categories_name ASC";
                                        $result = $connect->query($sql);
                                        while($row = $result->fetch_array()) {
                                            echo "<option value='".$row[0]."'>".$row[1]."</option>";
                                        } // while
                                ?>
                              </select>
                            </div>
                    </div> <div class="form-group">
                        <label for="editProductStatus" class="col-sm-3 control-label">Estado: </label>
                            <div class="col-sm-8">
                              <select class="form-control" id="editProductStatus" name="editProductStatus" required>
                                <option value="">-- SELECCIONAR --</option>
                                <option value="1">Disponible</option>
                                <option value="2">No Disponible</option>
                              </select>
                            </div>
                    </div> <input type="hidden" name="editProductId" id="editProductId" />

                    <div class="modal-footer editProductFooter">
                        <button type="button" class="btn btn-default" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> Cerrar</button>
                        <button type="submit" class="btn btn-success" id="editProductBtn" data-loading-text="Guardando..."> <i class="glyphicon glyphicon-ok-sign"></i> Guardar Cambios</button>
                      </div> </form> </div>
                </div> </div> </div> </div>
    </div>
  </div>
<div class="modal fade" tabindex="-1" role="dialog" id="removeProductModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><i class="glyphicon glyphicon-trash"></i> Eliminar Producto</h4>
      </div>
      <div class="modal-body">
        <div class="removeProductMessages"></div>
        <p>¿Realmente deseas eliminar este producto?</p>
        <p><strong>Advertencia:</strong> Esta acción no se puede deshacer.</p>
      </div>
      <div class="modal-footer removeProductFooter">
         <input type="hidden" name="removeProductId" id="removeProductId" />
        <button type="button" class="btn btn-default" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> Cerrar</button>
        <button type="button" class="btn btn-danger" id="removeProductBtn" data-loading-text="Eliminando..."> <i class="glyphicon glyphicon-ok-sign"></i> Confirmar Eliminación</button>
      </div>
    </div></div></div><script src="../custom/js/product.js"></script>

<?php require_once '../includes/footer.php'; ?>