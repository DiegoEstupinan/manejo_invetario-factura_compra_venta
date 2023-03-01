<?php

namespace App\Controllers;
require (__DIR__.'/../../vendor/autoload.php');
use App\Models\GeneralFunctions;
use App\Models\Medidas;

class MedidasController
{
    private array $dataMedida;

    public function __construct(array $_FORM)
    {
        $this->dataMedida = array();
        $this->dataMedida['id'] = $_FORM['id'] ?? NULL;
        $this->dataMedida['nombre'] = $_FORM['nombre'] ?? NULL;
    }
    public function create($withFiles = null) {
        try {
            if (!empty($this->dataMedida['nombre']) && !Medidas::medidaRegistrada($this->dataMedida['nombre'])) {
                $Medida = new Medidas($this->dataMedida);
                if ($Medida->insert()) {
                    unset($_SESSION['frmMedidas']);
                    header("Location: ../../views/modules/medidas/index.php?respuesta=success&mensaje=Medida Registrada");
                }
            } else {
                header("Location: ../../views/modules/medidas/create.php?respuesta=error&mensaje=Medida ya registrada");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }
    public function edit($withFiles = null)
    {
        try {
            $medida = new Medidas($this->dataMedida);
            if($medida->update()){
                unset($_SESSION['frmMedidas']);
            }
            header("Location: ../../views/modules/medidas/show.php?id=" . $medida->getId() . "&respuesta=success&mensaje=Medida Actualizado");
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }
    static public function searchForID(array $data)
    {
        try {
            $result = Medidas::searchForId($data['id']);
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
            $result = Medidas::getAll();
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
            $ObjMedida = Medidas::searchForId($id);
            if ($ObjMedida->update()) {
                header("Location: ../../views/modules/medidas/index.php?respuesta=success&mensaje=Registro actualizado");
            } else {
                header("Location: ../../views/modules/medidas/index.php?respuesta=error&mensaje=Error al guardar");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }
    static public function inactivate(int $id)
    {
        try {
            $ObjMedida = Medidas::searchForId($id);
            if ($ObjMedida->update()) {
                header("Location: ../../views/modules/medidas/index.php?respuesta=success&mensaje=Registro actualizado");
            } else {
                header("Location: ../../views/modules/medidas/index.php?respuesta=error&mensaje=Error al guardar");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }
    static public function selectMedida(array $params = []) {

        $params['isMultiple'] = $params['isMultiple'] ?? false;
        $params['isRequired'] = $params['isRequired'] ?? true;
        $params['id'] = $params['id'] ?? "medida_id";
        $params['name'] = $params['name'] ?? "medida_id";
        $params['defaultValue'] = $params['defaultValue'] ?? "";
        $params['class'] = $params['class'] ?? "form-control";
        $params['where'] = $params['where'] ?? "";
        $params['arrExcluir'] = $params['arrExcluir'] ?? array();
        $params['request'] = $params['request'] ?? 'html';

        $arrMedidas = array();
        if ($params['where'] != "") { //Si hay filtro
            $base = "SELECT * FROM medida WHERE ";
            $arrMedidas = Medidas::search($base . ' ' . $params['where']);
        } else {
            $arrMedidas = Medidas::getAll();
        }
        $htmlSelect = "<select " . (($params['isMultiple']) ? "multiple" : "") . " " . (($params['isRequired']) ? "required" : "") . " id= '" . $params['id'] . "' name='" . $params['name'] . "' class='" . $params['class'] . "' style='width: 100%;'>";
        $htmlSelect .= "<option value='' >Seleccione</option>";
        if (is_array($arrMedidas) && count($arrMedidas) > 0) {
            /* @var $arrMedidas Medidas[] */
            foreach ($arrMedidas as $medida)
                if (!MedidasController::medidaIsInArray($medida->getId(), $params['arrExcluir']))
                    $htmlSelect .= "<option " . (($medida != "") ? (($params['defaultValue'] == $medida->getId()) ? "selected" : "") : "") . " value='" . $medida->getId() . "'>" . $medida->getNombre() . "</option>";
        }
        $htmlSelect .= "</select>";
        return $htmlSelect;
    }
    private static function medidaIsInArray($idMedida, $ArrMedidas)
    {
        if (count($ArrMedidas) > 0) {
            foreach ($ArrMedidas as $Medida) {
                if ($Medida->getId() == $idMedida) {
                    return true;
                }
            }
        }
        return false;
    }
}