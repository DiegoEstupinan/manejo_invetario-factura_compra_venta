<?php

namespace App\Models;

use JetBrains\PhpStorm\Internal\TentativeType;
use Carbon\Carbon;
use Exception;
use JsonSerializable;

require_once ("AbstractDBConnection.php");
require_once (__DIR__."\..\Interfaces\Model.php");
require_once (__DIR__.'/../../vendor/autoload.php');

class Clasificaciones extends AbstractDBConnection implements \App\Interfaces\Model
{
    private ?int $id;
    private string $nombre;


//relacion//

    private ?array  $ProductoClasificacion; //producto


    //constructor//
    /**
     * Categorias constructor. Recibe un array asociativo
     * @param array $clasificacion
     */
    public function __construct(array $clasificacion = [])
    {
        parent::__construct();
        $this->setId($clasificacion['id'] ?? NULL);
        $this->setNombre($clasificacion['nombre'] ?? '');
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
        return ucwords($this->nombre) ;
    }

    /**
     * @param string $nombre
     */
    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }
    /**
     * @return array|null
     */
    public function getProductoClasificacion(): ?array
    {

        return $this->ProductoClasificacion;
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
        $query = "INSERT INTO ferreteria.clasificacion
         VALUES(:id,:nombre)";
        return $this->save($query);
    }

    function update(): ?bool
    {
        $query = "UPDATE ferreteria.clasificacion SET 
                 nombre = :nombre WHERE id = :id";
        return $this->save($query);
    }

    function deleted(): ?bool
    {
        return null;
    }

    static function search($query): ?array
    {
        try {
            $arrClasificacion = array();
            $tmp = new Clasificaciones();
            $tmp->Connect();
            $getrows = $tmp->getRows($query);
            $tmp->Disconnect();
 if (!empty($getrows)) {
            foreach ($getrows as $valor) {
                $Clasificacion = new Clasificaciones($valor);
                array_push($arrClasificacion,  $Clasificacion);
                unset( $Clasificacion);
            }
            return $arrClasificacion;
         }
            return null;
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
        return null;
    }

    static function searchForId(int $id): ?object
    {
        try {
            if ($id > 0) {
                $tmpClasificacion = new Clasificaciones();
                $tmpClasificacion->Connect();
                $getrow = $tmpClasificacion->getRow("SELECT * FROM ferreteria.clasificacion WHERE id =?", array($id));
                $tmpClasificacion->Disconnect();
                return ($getrow) ? new Clasificaciones($getrow) : null;
            }else{
                throw new Exception('Id de clasificacion Invalida');
            }
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
        return null;
    }

    static function getAll(): ?array
    {
        return Clasificaciones::search("SELECT * FROM ferreteria.clasificacion ");
    }
    public static function clasificacionRegistrada($nombre): bool
    {
        $nombre = trim(strtolower($nombre));
        $result = Clasificaciones::search("SELECT id FROM ferreteria.clasificacion  where nombre = '" . $nombre. "'");

        if ( !empty($result) && count ($result) > 0 ) {
            return true;
        } else {
            return false;
        }
    }

    public function __toString() : string
    {
        return "nombre: $this->nombre";

    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return [
            'nombre' => $this->getNombre(),

        ];
    }
}