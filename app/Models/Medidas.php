<?php

namespace App\Models;

use JetBrains\PhpStorm\Internal\TentativeType;
use App\Interfaces\Model;
use Exception;
use JsonSerializable;

class Medidas extends AbstractDBConnection implements \App\Interfaces\Model
{
    private ?int $id;
    private string $nombre;

    /*Relaciones*/

    private ?array $productoMedida;

    /**
     * @param array $medida
     */
    public function __construct( array $medida = [])
    {
        parent::__construct();
        $this->setId($medida['id'] ?? NULL);
        $this->setNombre($medida['nombre'] ?? '');
    }
    function __destruct()
    {
        if($this->isConnected()){
            $this->Disconnect();
        }
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
        return ucwords($this->nombre);
    }

    /**
     * @param string $nombre
     */
    public function setNombre(string $nombre): void
    {
        $this->nombre =strtolower($nombre);
    }

    /*Relaciones*/

    /**
     * @return array|null
     */
    public function getProductoMedida(): ?array
    {
        $this->productoMedida = Productos::search('SELECT * FROM ferreteria.medida WHERE id =' .$this->id);
        return $this->productoMedida;
    }

    /**
     * @param array|null $productoMedida
     */
    public function setProductoMedida(?array $productoMedida): void
    {
        $this->productoMedida = $productoMedida;
    }




    protected function save(string $query): ?bool
    {
        $arrData = [
            ':id' => $this->getId(),
            ':nombre' => $this->getNombre()
        ];

        $this->Connect();
        $result = $this->insertRow($query, $arrData);

        $this->Disconnect();
        return $result;
    }

    function insert(): ?bool
    {
        $query = "INSERT INTO ferreteria.medida VALUES (:id,:nombre)";
        return $this->save($query);
    }

    function update(): ?bool
    {
        $query = "UPDATE ferreteria.medida SET 
            nombre = :nombre 
            WHERE  id = :id";
        return $this->save($query);
    }

    function deleted(): ?bool
    {
        return null;
    }

    static function search($query): ?array
    {
        try {
            $arrMedidas = array();
            $tmp = new Medidas();
            $tmp->Connect();
            $getrows = $tmp->getRows($query);
            $tmp->Disconnect();

            foreach ($getrows as $valor) {
                $Medida = new Medidas($valor);
                array_push($arrMedidas, $Medida);
                unset($Medida);
            }
            return $arrMedidas;
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
        return null;
    }

    static function searchForId(int $id): ?object
    {
        try {
            if ($id > 0) {
                $Medida = new Medidas();
                $Medida->Connect();
                $getrow = $Medida->getRow("SELECT * FROM ferreteria.medida WHERE id =?", array($id));
                $Medida->Disconnect();
                return ($getrow) ? new Medidas($getrow) : null;
            }else{
                throw new Exception('Id de medida Invalido');
            }
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
        return null;
    }

    static function getAll(): ?array
    {
        return Medidas::search("SELECT * FROM ferreteria.medida");
    }

    /**
     * @param $nombre
     * @return bool
     * @throws Exception
     */
    public static function medidaRegistrada($nombre): bool
    {
        $nombre = trim(strtolower($nombre));
        $result = Medidas::search("SELECT id FROM ferreteria.medida where nombre = '" . $nombre. "'");
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
        return "nombre: $this->nombre";
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): string
    {
        return [
            'nombre' => $this->getNombre(),
        ];
    }
}