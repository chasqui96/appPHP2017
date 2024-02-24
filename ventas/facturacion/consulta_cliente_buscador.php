<?php
require_once '../../clases/conexion.php';

// Realizar la búsqueda en la tabla obtener_ruc_set
$numero_documento = $_POST["numero_documento"];
$sql = "SELECT * FROM obtener_ruc_set WHERE ruc_numero='$numero_documento'";
$result = consultas::get_datos($sql);

if ($result) {
    foreach ($result as $r) {?>
        <div class="row">
            <div class="col-md-3">
                <label for="inputCity" class="form-label">RUC/CI</label>
                <input type="text" class="form-control" id="ruc_numero" name="ruc_numero" value="<?php echo $r['ruc_numero']; ?>" >
            </div>
            <div class="col-md-1">
                <label for="inputState" class="form-label">DV</label>
                <input type="text" class="form-control" id="dv" name="dv" value="<?php echo $r['dv']; ?>" >
            </div>
            <div class="col-md-8">
                <label for="inputZip" class="form-label">Razon Social:</label>
                <input type="text" class="form-control" id="razon_social" name="razon_social" value="<?php echo $r['razon_social']; ?>" >
            </div>
        </div>
      
    <?php } ?>
<?php }else{?>
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                Cliente no encontrado
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
      <div class="row">
            <div class="col-md-3">
                <label for="inputCity" class="form-label">RUC/CI</label>
                <input type="text" class="form-control" name="ruc_numero" id="ruc_numero" value="<?php echo $numero_documento; ?>">
            </div>
            <div class="col-md-1">
                <label for="inputState" class="form-label">DV</label>
                <input type="text" class="form-control" name="dv" id="dv" >
            </div>
            <div class="col-md-8">
                <label for="inputZip" class="form-label">Razon Social:</label>
                <input type="text" class="form-control" name="razon_social" id="razon_social" >
            </div>
        </div>
        
<?php } ?>
