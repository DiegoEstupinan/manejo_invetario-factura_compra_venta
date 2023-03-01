<?php

namespace App\Models;
require_once "AbstractDBConnection.php";
require_once (__DIR__."\..\Interfaces\Model.php");
require_once (__DIR__.'/../../vendor/autoload.php');
use App\Enums\EstadoGeneral;
use App\Interfaces\Model;
use Carbon\Carbon;
use Exception;
use JetBrains\PhpStorm\pure;
use JsonSerializable;


class Marcas extends AbstractDBConnection implements Model
{
    private ?int $id;
    private string $nombre;
    private EstadoGeneral $estado;

    /* RELACIONES */
    private ?array $productomarca;

    /**
     * @param int|null $id
     * @param string $nombre
     * @param EstadoGeneral $estado
     */
    public function __construct(array $marcas = [])
    {
        parent::__construct();
        $this->setId($marcas ['id'] ?? null);
        $this->setNombre($marcas ['nombre'] ?? '');
        $this->setEstado($marcas['estado'] ?? EstadoGeneral::INACTIVO);
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
    public function getNombre(): string
    {
        return $this->nombre;
    }
    /**
     * @param string $nombre
     */
    public function setNombre(string $nombre): void
    {
        $this->nombre = strtoupper($nombre);

    }

    /**
     * @return EstadoGeneral
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
    public function getProductomarca(): ?array
    {
        $this->productomarca = Marcas::search('SELECT * FROM ferreteria.marca WHERE id =' .$this->id);
        return $this->productomarca;
    }

    /**
     * @param array|null $productomarca
     */
    public function setProductomarca(?array $productomarca): void
    {
        $this->productomarca = $productomarca;
    }

        protected function save(string $query): ?bool
    {
        $arrData = [
            ':id' => $this->getId(),
            ':nombre' => $this->getNombre(),
             ':estado' => $this->getEstado()
        ];

        $this->Connect();
        $result = $this->insertRow($query, $arrData);
        $this->Disconnect();
        return $result;


    }

  public function insert(): ?bool
    {
        $query = "INSERT INTO ferreteria.marca VALUES (
            :id,:nombre,:estado)";
        return $this->save($query);

    }

    public function update(): ?bool
    {
        $query = "UPDATE ferreteria.marca SET 
            nombre = :nombre,
            estado = :estado WHERE id = :id";
        return $this->save($query);

    }

    public function deleted(): ?bool
    {
        $this->setEstado(EstadoGeneral::INACTIVO); //Cambia el estado de la marca
        return $this->update();
    }

     public static function search($query): ?array
    {
        try {
            $arrMarcas = array();
            $tmp = new Marcas();
            $tmp->Connect();
            $getrows = $tmp->getRows($query);
            $tmp->Disconnect();

            if (!empty($getrows)) {
                foreach ($getrows as $valor) {
                    $Marcas = new Marcas($valor);
                    array_push($arrMarcas, $Marcas);
                    unset($Marcas);
                }
                return $arrMarcas;
            }
            return null;
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception', $e, 'error');
        }
        return null;

    }

    public static function searchForId(int $id): ?object
    {
        try {
            if ($id > 0) {
                $Marcas = new Marcas();
                $Marcas->Connect();
                $getrow = $Marcas->getRow("SELECT * FROM marca WHERE id =?", array($id));
                $Marcas->Disconnect();
                return ($getrow) ? new Marcas($getrow) : null;
            } else {
                throw new Exception('Id de marca Invalido');
            }
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception', $e);
        }
        return null;

    }

    static function getAll(): ?array
    {
        return Marcas::search("SELECT * FROM ferreteria.marca");
    }

    /**
     * @param $nombre
     * @return bool
     * @throws Exception
     */
    public static function marcasRegistrada($nombre): bool
    {
        $nombre = trim(strtolower($nombre));
        $result = Marcas::search("SELECT id FROM ferreteria.marca where nombre = '" . $nombre. "'");
        if ( !empty($result) && count ($result) > 0 ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return "Nombre: $this->nombre,
          Estado: ".$this->estado->toString();
    }

    /**
     * @inheritDoc
     */


    public function jsonSerialize():array
    {
        return [

            'nombre' => $this->getNombre()->jsonSerialize(),
            'estado' => $this->getEstado()->jsonSerialize(),

        ];

    }
}