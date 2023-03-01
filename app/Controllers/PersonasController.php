<?php

namespace App\Controllers;

require (__DIR__.'/../../vendor/autoload.php');
use App\Models\GeneralFunctions;
use App\Models\Personas;

class PersonasController
{

    private array $dataPersona;

    public function __construct(array $_FORM)
    {
        $this->dataPersona = array();
        $this->dataPersona['id'] = $_FORM['id'] ?? NULL;
        $this->dataPersona['tipodocumento'] = $_FORM['tipodocumento'] ?? NULL;
        $this->dataPersona['documento'] = $_FORM['documento'] ?? NULL;
        $this->dataPersona['nombre'] = $_FORM['nombre'] ?? NULL;
        $this->dataPersona['apellido'] = $_FORM['apellido'] ?? null;
        $this->dataPersona['telefono'] = $_FORM['telefono'] ?? NULL;
        $this->dataPersona['correo'] = $_FORM['correo'] ?? NULL;
        $this->dataPersona['contrasena'] = $_FORM['contrasena'] ?? NULL;
        $this->dataPersona['rol'] = $_FORM['rol'] ?? 'Cliente';
        $this->dataPersona['estado'] = $_FORM['estado'] ?? 'Activo';
    }

    public function create($withFiles = null) {
        try {
            if (!empty($this->dataPersona['documento']) && !Personas::PersonaRegistrada($this->dataPersona['documento'])) {

                $Persona = new Personas($this->dataPersona);
                if ($Persona->insert()) {
                    unset($_SESSION['frmPersonas']);
                    header("Location: ../../views/modules/personas/index.php?respuesta=success&mensaje=Persona Registrada");
                }
            } else {
                header("Location: ../../views/modules/personas/create.php?respuesta=error&mensaje=Persona ya registrada");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }

    public function edit($withFiles = null)
    {
        try {
            $persona = new Personas($this->dataPersona);
            if($persona->update()){
                unset($_SESSION['frmPersonas']);
            }
            header("Location: ../../views/modules/personas/show.php?id=" . $persona->getId() . "&respuesta=success&mensaje=Persona Actualizada");
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }

    static public function searchForID(array $data)
    {
        try {
            $result = Personas::searchForId($data['id']);
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
            $result = Personas::getAll();
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
            $ObjPersona = Personas::searchForId($id);
            $ObjPersona->setEstado("activo");
            if ($ObjPersona->update()) {
                header("Location: ../../views/modules/personas/index.php");
            } else {
                header("Location: ../../views/modules/personas/index.php?respuesta=error&mensaje=Error al guardar");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }

    static public function inactivate(int $id)
    {
        try {
            $ObjPersona = Personas::searchForId($id);
            $ObjPersona->setEstado("inactivo");
            if ($ObjPersona->update()) {
                header("Location: ../../views/modules/personas/index.php");
            } else {
                header("Location: ../../views/modules/personas/index.php?respuesta=error&mensaje=Error al guardar");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }


    static public function selectPersona(array $params = []) {

        $params['isMultiple'] = $params['isMultiple'] ?? false;
        $params['isRequired'] = $params['isRequired'] ?? true;
        $params['id'] = $params['id'] ?? "persona_id";
        $params['name'] = $params['name'] ?? "persona_id";
        $params['defaultValue'] = $params['defaultValue'] ?? "";
        $params['class'] = $params['class'] ?? "form-control";
        $params['where'] = $params['where'] ?? "";
        $params['arrExcluir'] = $params['arrExcluir'] ?? array();
        $params['request'] = $params['request'] ?? 'html';

        $arrPersonas = array();
        if ($params['where'] != "") { //Si hay filtro
            $base = "SELECT * FROM persona WHERE ";
            $arrPersonas = Personas::search($base . ' ' . $params['where']);
        } else {
            $arrPersonas = Personas::getAll();
        }
        $htmlSelect = "<select " . (($params['isMultiple']) ? "multiple" : "") . " " . (($params['isRequired']) ? "required" : "") . " id= '" . $params['id'] . "' name='" . $params['name'] . "' class='" . $params['class'] . "' style='width: 100%;'>";
        $htmlSelect .= "<option value='' >Seleccione</option>";
        if (is_array($arrPersonas) && count($arrPersonas) > 0) {
            /* @var $arrPersonas Personas[] */
            foreach ($arrPersonas as $persona)
                if (!PersonasController::personaIsInArray($persona->getId(), $params['arrExcluir']))
                    $htmlSelect .= "<option " . (($persona != "") ? (($params['defaultValue'] == $persona->getId()) ? "selected" : "") : "") . " value='" . $persona->getId() . "'>" . $persona->getDocumento() . " - " . $persona->getNombre() . " " . $persona->getApellido() . "</option>";
        }
        $htmlSelect .= "</select>";
        return $htmlSelect;
    }

    private static function personaIsInArray($idPersona, $ArrUsuarios)
    {
        if (count($ArrUsuarios) > 0) {
            foreach ($ArrUsuarios as $Persona) {
                if ($Persona->getId() == $idPersona) {
                    return true;
                }
            }
        }
        return false;
    }

    public static function login (){
        try {
            if(!empty($_POST['documento']) && !empty($_POST['contrasena'])){
                $tmpUser = new Personas();
                /* @var $respuesta Personas[] */
                $respuesta = $tmpUser->login($_POST['documento'], $_POST['contrasena']);
                if (is_a($respuesta,"App\Models\Personas")) {
                    if($respuesta->getRol() != "cliente" AND $respuesta->getRol() != "proveedor"){
                        $_SESSION['UserInSession'] = $respuesta->jsonSerialize();
                        header("Location: ../../views/index.php");
                    }else{
                        header("Location: ../../views/modules/site/login.php?respuesta=error&mensaje=Rol Incorrecto");
                    }
                }else{
                    header("Location: ../../views/modules/site/login.php?respuesta=error&mensaje=".$respuesta);
                }
            }else{
                header("Location: ../../views/modules/site/login.php?respuesta=error&mensaje=Datos Vacíos");
            }
        } catch (\Exception $e) {
            header("Location: ../../views/modules/site/login.php?respuesta=error".$e->getMessage());
        }
    }


    public static function cerrarSession (){
        session_unset();
        session_destroy();
        header("Location: ../../views/modules/site/login.php");
    }



}