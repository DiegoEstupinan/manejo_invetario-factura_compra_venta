<?php

namespace App\Models;

use App\Enums\EstadoGeneral;
use App\Interfaces\Model; //
use Carbon\Carbon;
use Exception;
use JetBrains\PhpStorm\Internal\TentativeType;
use JsonSerializable;


class Materiales extends AbstractDBConnection implements \App\Interfaces\Model
{

    private ?int $id;
    private string $nombre;


    /*Relaciones*/


    private ?array $ProductoMaterial;

    /**
     * @param int|null $id
     * @param string $nombre
     */
    public function __construct(array $material = [])
    {
        parent::__construct(); // Llamado al contructor padre
        $this->setId($material ['id'] ?? null);
        $this->setNombre($material ['nombre'] ?? '');
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
     * @return array|null
     */
    public function getProductoMaterial(): ?array
    {
        $this->productomaterial = Materiales::search('SELECT * FROM ferreteria.material WHERE id =' .$this->id);
        return $this->ProductoMaterial;
    }

    /**
     * @param array|null $ProductoMaterial
     */
    public function setProductoMaterial(?array $ProductoMaterial): void
    {
        $this->ProductoMaterial = $ProductoMaterial;
    }



    protected function save(string $query): ?bool
    {
        $arrData = [
            ':id' =>    $this->getId(),
            ':nombre' =>  $this->getNombre(),
        ];

        $this->Connect();
        $result = $this->insertRow($query, $arrData);
        $this->Disconnect();
        return $result;
    }

    function insert(): ?bool
    {
        $query = "INSERT INTO ferreteria.material VALUES ( :id,:nombre)";
        return $this->save($query);
    }

    function update(): ?bool
    {
        $query = "UPDATE ferreteria.material SET 
             nombre = :nombre
            WHERE id = :id";
        return $this->save($query);
    }

    function deleted(): ?bool
    {
        return null;
    }

    static function search($query): ?array
    {
        try {
            $arrMateriales = array();
            $tmp = new Materiales();
            $tmp->Connect();
            $getrows = $tmp->getRows($query);
            $tmp->Disconnect();


                foreach ($getrows as $valor) {
                    $Materiales = new Materiales($valor);
                    array_push($arrMateriales, $Materiales);
                    unset($Materiales);
                }
                return $arrMateriales;

        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception', $e, 'error');
        }
        return null;
    }

   public static function searchForId(int $id): ?object
    {
        try {
            if ($id > 0) {
                $tmpMateriales = new Materiales();
                $tmpMateriales->Connect();
                $getrow = $tmpMateriales->getRow("SELECT * FROM material WHERE id =?", array($id));
                $tmpMateriales->Disconnect();
                return ($getrow) ? new Materiales($getrow) : null;
            } else {
                throw new Exception('Id de Material Invalido');
            }
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception', $e);
        }
        return null;
    }

    static function getAll(): ?array
    {

        return Materiales::search("SELECT * FROM ferreteria.material");
    }

    /**
     * @param $nombre
     * @return bool
     * @throws Exception
     */
    public static function MaterialRegistrado($nombre): bool
    {
        $nombre = trim(strtolower($nombre));
        $result = Materiales::search("SELECT * FROM ferreteria.material where nombre = '" . $nombre."'");
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

        return "nombre: $this->nombre,";
    }


    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this -> getId(),
            'nombre' => $this -> getNombre(),
        ];
    }
}