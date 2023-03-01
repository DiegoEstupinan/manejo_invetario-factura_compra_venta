<?php

namespace App\Controllers;
require (__DIR__.'/../../vendor/autoload.php');
use App\Models\GeneralFunctions;
use App\Models\Materiales;

class MaterialesController
{
    private array $dataMaterial;

    public function __construct(array $_FORM)
    {
        $this->dataMaterial = array();
        $this->dataMaterial['id'] = $_FORM['id'] ?? NULL;
        $this->dataMaterial['nombre'] = $_FORM['nombre'] ?? NULL;
    }

    public function create($withFiles = null)
    {
        try {
            if (!empty($this->dataMaterial['nombre']) && !Materiales::MaterialRegistrado($this->dataMaterial['nombre'])) {
                $Material = new Materiales($this->dataMaterial);
                if ($Material->insert()) {
                    unset($_SESSION['frmMateriales']);
                    header("Location: ../../views/modules/materiales/index.php?respuesta=success&mensaje=Material Registrado");
                }
            } else {
                header("Location: ../../views/modules/materiales/create.php?respuesta=error&mensaje=Material ya registrado");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception', $e, 'error');
        }
    }

    public function edit($withFiles = null)
    {
        try {
          
            $material = new Materiales($this->dataMaterial);
            if ($material->update()) {
                unset($_SESSION['frmMateriales']);
            }
            header("Location: ../../views/modules/materiales/show.php?id=" . $material->getId() . "&respuesta=success&mensaje=Material Actualizado");
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception', $e, 'error');
        }
    }
    static public function searchForID(array $data)
    {
        try {
            $result = Materiales::searchForId($data['id']);
            if (!empty($data['request']) and $data['request'] === 'ajax' and !empty($result)) {
                header('Content-type: application/json; charset=utf-8');
                $result = json_encode($result->jsonSerialize());
            }
            return $result;
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
        return null;
}
    static public function getAll(array $data = null)
    {
        try {
            $result = Materiales::getAll();
            if (!empty($data['request']) and $data['request'] === 'ajax') {
                header('Content-type: application/json; charset=utf-8');
                $result = json_encode($result);
            }
            return $result;
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
        return null;
    }
    static public function activate(int $id)
    {
        try {
            $ObjMaterial = Materiales::searchForId($id);
            if ($ObjMaterial->update()) {
                header("Location: ../../views/modules/materiales/index.php?respuesta=success&mensaje=Registro actualizado");
            } else {
                header("Location: ../../views/modules/materiales/index.php?respuesta=error&mensaje=Error al guardar");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }
    static public function inactivate(int $id)
    {
        try {
            $ObjMaterial = Materiales::searchForId($id);
            if ($ObjMaterial->update()) {
                header("Location: ../../views/modules/materiales/index.php?respuesta=success&mensaje=Registro actualizado");
            } else {
                header("Location: ../../views/modules/materiales/index.php?respuesta=error&mensaje=Error al guardar");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }
    static public function selectMaterial(array $params = []) {

        $params['isMultiple'] = $params['isMultiple'] ?? false;
        $params['isRequired'] = $params['isRequired'] ?? true;
        $params['id'] = $params['id'] ?? "material_id";
        $params['name'] = $params['name'] ?? "material_id";
        $params['defaultValue'] = $params['defaultValue'] ?? "";
        $params['class'] = $params['class'] ?? "form-control";
        $params['where'] = $params['where'] ?? "";
        $params['arrExcluir'] = $params['arrExcluir'] ?? array();
        $params['request'] = $params['request'] ?? 'html';

        $arrMateriales = array();
        if ($params['where'] != "") { //Si hay filtro
            $base = "SELECT * FROM material WHERE ";
            $arrMateriales = Materiales::search($base . ' ' . $params['where']);
        } else {
            $arrMateriales = Materiales::getAll();
        }
        $htmlSelect = "<select " . (($params['isMultiple']) ? "multiple" : "") . " " . (($params['isRequired']) ? "required" : "") . " id= '" . $params['id'] . "' name='" . $params['name'] . "' class='" . $params['class'] . "' style='width: 100%;'>";
        $htmlSelect .= "<option value='' >Seleccione</option>";
        if (is_array($arrMateriales) && count($arrMateriales) > 0) {
            /* @var $arrMateriales Materiales[] */
            foreach ($arrMateriales as $material)
                if (!MaterialesController::materialIsInArray($material->getId(), $params['arrExcluir']))
                    $htmlSelect .= "<option " . (($material != "") ? (($params['defaultValue'] == $material->getId()) ? "selected" : "") : "") . " value='" . $material->getId() . "'>" . $material->getNombre() . "</option>";
        }
        $htmlSelect .= "</select>";
        return $htmlSelect;
    }
    private static function materialIsInArray($idMaterial, $ArrMateriales)
    {
        if (count($ArrMateriales) > 0) {
            foreach ($ArrMateriales as $Material) {
                if ($Material->getId() == $idMaterial) {
                    return true;
                }
            }
        }
        return false;
    }
}