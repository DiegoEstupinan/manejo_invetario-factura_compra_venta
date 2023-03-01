<?php

namespace App\Models;

use App\Enums\EstadoGeneral;
use App\Interfaces\Model;
use JetBrains\PhpStorm\Internal\TentativeType;
use Carbon\Carbon;
use Exception;
use JsonSerializable;

require_once ("AbstractDBConnection.php");
require_once (__DIR__."\..\Interfaces\Model.php");
require_once (__DIR__.'/../../vendor/autoload.php');

class Productos extends AbstractDBConnection implements Model
{
    private ?int $id;
    private string $nombre;
    private  int $stock;
    private int $precio;
    private float $porcentaje_ganancia; //0.5
    private int $clasificacion_id;
    private EstadoGeneral $estado;
    private ?int $medida_id;
    private int $marca_id;
    private ?int $material_id;
    private ?Clasificaciones $clasificacion;
    private ?Materiales $materiales;
    private ?Marcas $marcas;
    private ?Medidas $medidas;

    private array $ProductoDetalleventa;
    private array $ProductoDetallecompra;


    /**
     * Categorias constructor. Recibe un array asociativo
     * @param array $producto
     * @param EstadoGeneral $estado
     */
    public function __construct(array $producto = [])
    {
        parent::__construct();
        $this->setId($producto['id'] ?? NULL);
        $this->setNombre($producto['nombre'] ?? '');
        $this->setStock($producto['stock'] ?? 0);
        $this->setPrecio($producto['precio'] ?? 0.0);
        $this->setPorcentajeGanancia($producto['porcentaje_ganancia'] ?? 0.0);
        $this->setClasificacionId($producto['clasificacion_id'] ?? 0);
        $this->setEstado($producto['estado'] ?? EstadoGeneral::INACTIVO);
        $this->setMedidaId($producto['medida_id'] ?? null );
        $this->setMarcaId($producto['marca_id'] ?? 0);
        $this->setMaterialId($producto['material_id'] ?? null);


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
        return ucwords($this->nombre);
    }

    /**
     * @param string $nombre
     */
    public function setNombre(string $nombre): void
    {
        $this->nombre = strtolower($nombre);
    }

    /**
     * @return int
     */
    public function getStock(): int
    {
        return $this->stock;
    }

    /**
     * @param int $stock
     */
    public function setStock(int $stock)
    {
        $this->stock = $stock;
    }

    /**
     * @return int
     */
    public function getPrecio(): int
    {
        return $this->precio;
    }

    /**
     * @param int $precio
     */
    public function setPrecio(int $precio): void
    {
        $this->precio = $precio;
    }
    /**
     * @return float|mixed
     */
    public function getPorcentajeGanancia() : float
    {
        return $this->porcentaje_ganancia;
    }

    /**
     * @param float|mixed $porcentaje_ganancia
     */
    public function setPorcentajeGanancia(float $porcentaje_ganancia): void
    {
        $this->porcentaje_ganancia = $porcentaje_ganancia;
    }



    /**
     * @return int
     */
    public function getClasificacionId(): int
    {
        return $this->clasificacion_id;
    }

    /**
     * @param int $clasificacion_id
     */
    public function setClasificacionId(int $clasificacion_id): void
    {
        $this->clasificacion_id = $clasificacion_id;
    }

    /**
     * @return EstadoGeneral
     */
    public function getEstado(): string
    {
        return $this->estado->toString();
    }

    /**
     * @param EstadoGeneral|null $estado
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
     * @return int
     */
    public function getMedidaId(): ?int
    {
        return $this->medida_id;
    }

    /**
     * @param int $medida_id
     */
    public function setMedidaId(?int $medida_id ): void
    {
        $this->medida_id = $medida_id;

    }


    /**
     * @return int
     */
    public function getMarcaId(): int
    {
        return $this->marca_id;
    }

    /**
     * @param int $marca_id
     */
    public function setMarcaId(int $marca_id): void
    {
        $this->marca_id = $marca_id;
    }

    /**
     * @return int
     */

    public function getMaterialId(): ?int
    {
        return $this->material_id;
    }

    /**
     * @param int $material_id
     */
    public function setMaterialId(?int $material_id): void
    {
        $this->material_id = $material_id;
    }



    /**
     * @return Clasificaciones|null
     */

    public function getClasificacion(): ?Clasificaciones
    {
        if (!empty($this->clasificacion_id)) {
            $this->clasificacion = Clasificaciones::searchForId($this->clasificacion_id) ?? new Clasificaciones();
            return $this->clasificacion;
        }
        return null;
    }

    /**
     * @return Materiales|null
     */
    public function getMateriales(): ?Materiales
    {
        if (!empty($this->material_id)) {
            $this->materiales = Materiales::searchForId($this->material_id) ?? new Materiales();
            return $this->materiales;
        }
        return null;
    }

    /**
     * @return Marcas|null
     */
    public function getMarcas(): ?Marcas
    {
        if (!empty($this->marca_id)) {
            $this->marcas = Marcas::searchForId($this->marca_id) ?? new Marcas();
            return $this->marcas;
        }
        return null;
    }

    /**
     * @return Medidas|null
     */
    public function getMedidas(): ?Medidas
    {
        if (!empty($this->medida_id)) {
            $this->medidas = Medidas::searchForId($this->medida_id) ?? new Medidas();
            return $this->medidas;
        }
        return null;
    }

    /**
     * @param Medidas|null $medidas
     */
    public function setMedidas(?Medidas $medidas): void
    {
        $this->medidas = $medidas;
    }


    /**
     * @return array
     */
    public function getProductoDetalleventa(): array
    {
        return $this->ProductoDetalleventa;
    }

    /**
     * @return array
     */
    public function getProductoDetallecompra(): array
    {
        return $this->ProductoDetallecompra;
    }

    protected function save(string $query): ?bool
    {
        $arrData = [
            ':id' =>    $this->getId(),
            ':nombre' =>   $this->getNombre(),
            ':stock' =>   $this->getStock(),
            ':precio' =>   $this->getPrecio(),
            ':porcentaje_ganancia' =>  $this->getPorcentajeGanancia(),
            ':clasificacion_id' =>   $this->getClasificacionId(),
            ':estado'=>   $this->getEstado(),
            ':medida_id' =>   $this->getMedidaId(),
            ':marca_id' =>   $this->getMarcaId(),
            ':material_id'=>   $this->getMaterialId(),

        ];
        $this->Connect();
        $result = $this->insertRow($query, $arrData);
        $this->Disconnect();
        return $result;
    }

    function insert(): ?bool
    {
        $query = "INSERT INTO ferreteria.producto VALUES (:id,:nombre,:stock,:precio,:porcentaje_ganancia,:clasificacion_id,:estado,:medida_id,:marca_id,:material_id)";
        return $this->save($query);
    }

    function update(): ?bool
    {
        $query = "UPDATE ferreteria.producto SET 
            nombre = :nombre, precio = :precio,porcentaje_ganancia = :porcentaje_ganancia,   
            stock = :stock, estado = :estado, clasificacion_id = :clasificacion_id, medida_id = :medida_id, marca_id = :marca_id, material_id = :material_id
            WHERE id = :id";
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
            $arrProducto = array();
            $tmp = new Productos();
            $tmp->Connect();
            $getrows = $tmp->getRows($query);
            $tmp->Disconnect();

            foreach ($getrows as $valor) {
                $Producto = new Productos($valor);
                array_push($arrProducto, $Producto);
                unset($Producto);
            }
            return $arrProducto;
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
        return null;
    }

    static function searchForId(int $id): ?object
    {
        try {
            if ($id > 0) {
                $tmpProducto = new Productos();
                $tmpProducto->Connect();
                $getrow = $tmpProducto->getRow("SELECT * FROM ferreteria.Producto WHERE id =?", array($id));
                $tmpProducto->Disconnect();
                return ($getrow) ? new Productos($getrow) : null;
            }else{
                throw new Exception('Id de clasificacion Invalida');
            }
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
        return null;
    }
    public static function productoRegistrado($nombre): bool
    {
        $result = Productos::search("SELECT id FROM ferreteria.producto where nombre = '" . $nombre. "'");
        if ( !empty($result) && count ($result) > 0 ) {
            return true;
        } else {
            return false;
        }
    }

    static function getAll(): ?array
    {
        return Productos::search("SELECT * FROM ferreteria.producto");
    }
    public function getPrecioVenta() : float
    {
        return $this->precio + ($this->precio * ($this->porcentaje_ganancia / 100));
    }
    public function __toString() : string
    {
        return "Nombre: $this->nombre, 
        Precio: $this->precio, 
         Porcentaje: $this->porcentaje_ganancia,
        Stock: $this->stock,
        estado: ".$this->estado->toString();
    }
    public function substractStock(int $quantity)
    {
        $this->setStock( $this->getStock() - $quantity);
        $result = $this->update();
        if($result == false){
            GeneralFunctions::console('Stock no actualizado!');
        }
        return $result;
    }
    public function addStock(int $quantity)
    {
        $this->setStock( $this->getStock() + $quantity);
        $result = $this->update();
        if($result == false){
            GeneralFunctions::console('Stock no actualizado!');
        }
        return $result;
    }
/*
    public function registerMarca(int $idMarca, int $idProducto)
    {
        $isRegister = Productos::search("SELECT * FROM 
                    ferreteria.producto_marca
                    WHERE marca_id = $idMarca and producto_id = $idProducto");
        if(count($isRegister)> 0){
            GeneralFunctions::console('Marca ya asociada al producto!');
            return false;
        }else{
            return $this->saveMarcaProducto($idMarca, $idProducto);
        }
    }

    public function saveMarcaProducto (int $idMarca, int $idProducto){
        $query = "INSERT INTO ferreteria.producto_marca VALUES (:marca_id,:producto_id)";
        $arrData = [
            ':marca_id' => $idMarca,
            ':producto_id' => $idProducto,
        ];
        $this->Connect();
        $result = $this->insertRow($query, $arrData);
        $this->Disconnect();
        return $result;
    }
*/
    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return [
            'nombre' => $this->getNombre(),
            'precio' => $this->getPrecio(),
            'porcentaje_ganancias' => $this->getPorcentajeGanancia(),
            'precio_venta' => $this->getPrecioVenta(),
            'stock' => $this->getStock(),


        ];
    }
}