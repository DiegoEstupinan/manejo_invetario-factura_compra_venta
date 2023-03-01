<?php

namespace App\Models;

use App\Enums\EstadoGeneral;
use App\Enums\Rol;
use App\Enums\TipoDocumento;
use JetBrains\PhpStorm\Internal\TentativeType;
use Carbon\Carbon;
use Exception;
use JsonSerializable;



class Personas extends AbstractDBConnection implements \App\Interfaces\Model
{
    private ?int $id;
    private TipoDocumento $tipodocumento;
    private string $documento;
    private string $correo;
    private string $nombre;
    private string $apellido;
    private string $telefono;
    private ?string $contrasena;
    private Rol $rol;
    private EstadoGeneral $estado;

    /* Seguridad de Contraseña */
    const HASH = PASSWORD_DEFAULT;
    const COST = 10;

    //Relaciones
    private ?array $FacturaComprasPersonas;
    private ?array $FacturaVentasPersonas;

    /**
     * @param array $persona
     */
    public function __construct(array $persona = [])
    {
        parent::__construct();
        $this->setId($persona ['id'] ?? null);
        $this->setTipodocumento($persona ['tipodocumento'] ?? TipoDocumento::CEDULA);
        $this->setDocumento($persona ['documento'] ?? '');
        $this->setCorreo($persona ['correo'] ?? '');
        $this->setNombre($persona ['nombre'] ?? '');
        $this->setApellido($persona ['apellido'] ?? '');
        $this->setTelefono($persona ['telefono'] ?? '');
        $this->setContrasena($persona ['contrasena'] ?? NULL);
        $this->setRol($persona ['rol'] ?? Rol::ADMINISTRADOR);
        $this->setEstado($persona ['estado'] ?? EstadoGeneral::INACTIVO);
    }
    public function __destruct()
    {
        parent::__destruct();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getTipodocumento(): string
    {
        return $this->tipodocumento->toString();
    }

    /**
     * @param string $tipodocumento
     */
    public function setTipodocumento(null|string|TipoDocumento $tipodocumento): void
    {
        if (is_string($tipodocumento)){
            $this->tipodocumento = TipoDocumento::from($tipodocumento);
        }else{
            $this->tipodocumento = $tipodocumento;
        }
    }

    /**
     * @return string
     */
    public function getDocumento(): string
    {
        return $this->documento;
    }

    /**
     * @param string $documento
     */
    public function setDocumento(string $documento): void
    {
        $this->documento = $documento;
    }

    /**
     * @return string
     */
    public function getCorreo(): string
    {
        return $this->correo;
    }

    /**
     * @param string $correo
     */
    public function setCorreo(string $correo): void
    {
        $this->correo = $correo;
    }

    /**
     * @return string
     */
    public function getNombre(): string
    {
        return ucwords($this->nombre) ;
    }

    /**
     * @param string $nombre
     */
    public function setNombre(string $nombre): void
    {
        $this->nombre = strtolower($nombre) ;
    }

    /**
     * @return string
     */
    public function getApellido(): string
    {
        return ucwords($this->apellido) ;
    }

    /**
     * @param string $apellido
     */
    public function setApellido(string $apellido): void
    {
        $this->apellido = strtolower($apellido);
    }

    /**
     * @return string
     */
    public function getTelefono(): string
    {
        return $this->telefono;
    }

    /**
     * @param string $telefono
     */
    public function setTelefono(string $telefono): void
    {
        $this->telefono = $telefono;
    }

    /**
     * @return string
     */
    public function getContrasena(): ?string
    {
        return $this->contrasena;
    }

    /**
     * @param string $contrasena
     */
    public function setContrasena(?string $contrasena): void
    {
        $this->contrasena = $contrasena;
    }

    /**
     * @return string
     */
    public function getRol(): string
    {
        return $this->rol->toString();
    }

    /**
     * @param string $rol
     */
    public function setRol(null|string|Rol $rol): void
    {
        if (is_string($rol)){
            $this->rol = Rol::from($rol) ;
        }else{
            $this->rol = $rol;
        }

    }

    /**
     * @return string
     */
    public function getEstado(): string
    {
        return $this->estado->toString();
    }

    /**
     * @param string $estado
     */
    public function setEstado(null|string|EstadoGeneral $estado): void
    {
        if (is_string($estado)){
            $this->estado = EstadoGeneral::from($estado) ;
        }else{
            $this->estado = $estado;
        }

    }

    /**
     * @return array|null
     */
    public function getFacturaComprasPersonas(): ?array
    {
        return $this->FacturaComprasPersonas;
    }

    /**
     * @param array|null $FacturaComprasPersonas
     */
    public function setFacturaComprasPersonas(?array $FacturaComprasPersonas): void
    {
        $this->FacturaComprasPersonas = $FacturaComprasPersonas;
    }

    /**
     * @return array|null
     */
    public function getFacturaVentasPersonas(): ?array
    {
        return $this->FacturaVentasPersonas;
    }

    /**
     * @param array|null $FacturaVentasPersonas
     */
    public function setFacturaVentasPersonas(?array $FacturaVentasPersonas): void
    {
        $this->FacturaVentasPersonas = $FacturaVentasPersonas;
    }

    protected function save(string $query): ?bool
    {
        $hashPassword = null;
        if($this->contrasena != null){
            $hashPassword = password_hash($this->contrasena, self::HASH, ['cost' => self::COST]);
        }
        $arrData = [
            ':id' =>    $this->getId(),
            ':tipodocumento' =>   $this->getTipodocumento(),
            ':documento' =>   $this->getDocumento(),
            ':nombre' =>  $this->getNombre(),
            ':apellido' =>   $this->getApellido(),
            ':telefono' =>   $this->getTelefono(),
            ':correo' =>   $this->getCorreo(),
            ':contrasena' =>  $hashPassword,
            ':rol' =>  $this->getRol(),
            ':estado' =>   $this->getEstado()
        ];
        $this->Connect();
        $result = $this->insertRow($query, $arrData);
        $this->Disconnect();
        return $result;
    }

    function insert(): ?bool
    {
        $query = "INSERT INTO ferreteria.persona VALUES (
            :id,:tipodocumento,:documento,:nombre,:apellido,
            :telefono,:correo,:contrasena,:rol,:estado
        )";
        return $this->save($query);
    }

    function update(): ?bool
    {
        $query = "UPDATE ferreteria.persona SET 
            tipodocumento = :tipodocumento, documento = :documento, nombre = :nombre, 
            apellido = :apellido, telefono = :telefono, correo = :correo, contrasena = :contrasena, 
            rol = :rol, estado = :estado WHERE id = :id";
        return $this->save($query);
    }

    function deleted(): ?bool
    {
        $this->setEstado(EstadoGeneral::INACTIVO); //Cambia el estado del Usuario
        return $this->update();
    }

    static function search($query): ?array
    {
        try {
            $arrPersona = array();
            $tmp = new Personas();
            $tmp->Connect();
            $getrows = $tmp->getRows($query);
            $tmp->Disconnect();

            if (!empty($getrows)) {
                foreach ($getrows as $valor) {
                    $Persona = new Personas ($valor);
                    array_push($arrPersona, $Persona);
                    unset($Persona);
                }
                return $arrPersona;
            }
            return null;
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception', $e);
        }
        return null;
    }

    static function searchForId(int $id): ?object
    {
        try {
            if ($id > 0) {
                $tmpPersona = new Personas();
                $tmpPersona->Connect();
                $getrow = $tmpPersona->getRow("SELECT * FROM ferreteria.persona WHERE id =?", array($id));
                $tmpPersona->Disconnect();
                return ($getrow) ? new Personas($getrow) : null;
            } else {
                throw new Exception('Id de Persona Invalido');
            }
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception', $e);
        }
        return null;
    }

    static function getAll(): ?array
    {
        return Personas::search("SELECT * FROM ferreteria.persona");
    }

    /**
     * @param $documento
     * @return bool
     * @throws Exception
     */
    public static function PersonaRegistrada($documento): bool
    {
        $result = Personas::search("SELECT * FROM ferreteria.persona where documento = " . $documento);
        if (!empty($result) && count($result)>0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return "Nombres: $this->nombre, 
                Apellidos: $this->apellido, 
                TipoDocumento: ".$this->tipodocumento->toString().", 
                Documento: $this->documento, 
                Telefono: $this->telefono,
                Estado: ".$this->estado->toString();
    }

    public function login($documento, $password): Personas|String|null
    {
        try {
            $resultPersona = Personas::search("SELECT * FROM persona WHERE documento = '$documento'");
            /* @var $resultPersona Personas[] */
            if (!empty($resultPersona) && count($resultPersona) >= 1) {
                if (password_verify($password, $resultPersona[0]->getContrasena())) {
                    if ($resultPersona[0]->getEstado() == 'activo') {
                        return $resultPersona[0];
                    } else {
                        return "Usuario Inactivo";
                    }
                } else {
                    return "Contraseña Incorrecta";
                }
            } else {
                return "Usuario Incorrecto";
            }
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception', $e);
            return "Error en Servidor";
        }
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize() :array
    {
        return [
            'id' => $this -> getId(),
            'tipodocumento' => $this -> getTipodocumento(),
            'documento' => $this -> getDocumento(),
            'correo' => $this -> getCorreo(),
            'nombre' => $this -> getNombre(),
            'apellido' => $this -> getApellido(),
            'telefono' => $this -> getTelefono(),
            'contrasena' => $this -> getContrasena(),
            'rol' => $this -> getRol(),
            'estado' => $this -> getEstado(),
        ];
    }
}