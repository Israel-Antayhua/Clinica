<?php require_once '../includes/header.php'; ?>


<div class="row">
    <div class="col-md-12">

        <ol class="breadcrumb">
          <li><a href="dashboard.php">Inicio</a></li>
          <li class="active">Categoría</li>
        </ol>

        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="page-heading"> <i class="glyphicon glyphicon-edit"></i> Gestionar Categorías</div>
            </div> <div class="panel-body">

                <div class="remove-messages"></div>

                <div class="div-action pull pull-right" style="padding-bottom:20px;">
                    <button class="btn btn-default button1" data-toggle="modal" id="addCategoriesModalBtn" data-target="#addCategoriesModal"> <i class="glyphicon glyphicon-plus-sign"></i> Agregar Categoría </button>
                </div> <table class="table" id="manageCategoriesTable">
                    <thead>
                        <tr>
                            <th>Nombre Categoría</th>
                            <th>Estado</th>
                            <th style="width:15%;">Opciones</th>
                        </tr>
                    </thead>
                </table>
                </div> </div> </div> </div> <div class="modal fade" id="addCategoriesModal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">

        <form class="form-horizontal" id="submitCategoriesForm" action="../php_action/DAO/createCategories.php" method="POST">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button> <h4 class="modal-title"><i class="fa fa-plus"></i> Agregar Categoría</h4>
          </div>
          <div class="modal-body">

            <div id="add-categories-messages"></div>

            <div class="form-group">
                <label for="categoriesName" class="col-sm-4 control-label">Nombre Categoría: </label>
                <div class="col-sm-7">
                      <input type="text" class="form-control" id="categoriesName" placeholder="Nombre de la Categoría" name="categoriesName" autocomplete="off" required> </div>
            </div> <div class="form-group">
                <label for="categoriesStatus" class="col-sm-4 control-label">Estado: </label>
                <div class="col-sm-7">
                      <select class="form-control" id="categoriesStatus" name="categoriesStatus" required> <option value="">-- SELECCIONAR --</option>
                        <option value="1">Disponible</option>
                        <option value="2">No Disponible</option>
                      </select>
                    </div>
            </div> </div> <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> Cerrar</button>

            <button type="submit" class="btn btn-primary" id="createCategoriesBtn" data-loading-text="Guardando..." autocomplete="off"> <i class="glyphicon glyphicon-ok-sign"></i> Guardar Cambios</button>
          </div> </form> </div> </div> </div>
<div class="modal fade" id="editCategoriesModal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">

        <form class="form-horizontal" id="editCategoriesForm" action="../php_action/DAO/editCategories.php" method="POST">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button> <h4 class="modal-title"><i class="fa fa-edit"></i> Editar Categoría</h4>
          </div>
          <div class="modal-body">

            <div id="edit-categories-messages"></div>

            <div class="modal-loading div-hide" style="width:50px; margin:auto;padding-top:50px; padding-bottom:50px;">
                        <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
                        <span class="sr-only">Cargando...</span>
                    </div>

              <div class="edit-categories-result">
                <div class="form-group">
                    <label for="editCategoriesName" class="col-sm-4 control-label">Nombre Categoría: </label>
                    <div class="col-sm-7">
                          <input type="text" class="form-control" id="editCategoriesName" placeholder="Nombre de la Categoría" name="editCategoriesName" autocomplete="off" required> </div>
                </div> <div class="form-group">
                    <label for="editCategoriesStatus" class="col-sm-4 control-label">Estado: </label>
                    <div class="col-sm-7">
                          <select class="form-control" id="editCategoriesStatus" name="editCategoriesStatus" required> <option value="">-- SELECCIONAR --</option>
                            <option value="1">Disponible</option>
                            <option value="2">No Disponible</option>
                          </select>
                        </div>
                </div> </div>
              </div> <div class="modal-footer editCategoriesFooter">
             <input type="hidden" name="editCategoriesId" id="editCategoriesId" />
            <button type="button" class="btn btn-default" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> Cerrar</button>

            <button type="submit" class="btn btn-success" id="editCategoriesBtn" data-loading-text="Guardando..." autocomplete="off"> <i class="glyphicon glyphicon-ok-sign"></i> Guardar Cambios</button>
          </div>
          </form>
         </div>
    </div>
  </div>
<div class="modal fade" tabindex="-1" role="dialog" id="removeCategoriesModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button> <h4 class="modal-title"><i class="glyphicon glyphicon-trash"></i> Eliminar Categoría</h4>
      </div>
      <div class="modal-body">
        <p>¿Realmente deseas eliminar esta categoría?</p>
        <p><strong>Advertencia:</strong> Esto podría afectar a los productos asociados.</p> </div>
      <div class="modal-footer removeCategoriesFooter">
         <input type="hidden" name="removeCategoriesId" id="removeCategoriesId" />
        <button type="button" class="btn btn-default" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> Cerrar</button>
        <button type="button" class="btn btn-danger" id="removeCategoriesBtn" data-loading-text="Eliminando..."> <i class="glyphicon glyphicon-ok-sign"></i> Confirmar Eliminación</button> </div>
    </div></div></div><script src="../custom/js/categories.js"></script>

<?php require_once '../includes/footer.php'; ?>