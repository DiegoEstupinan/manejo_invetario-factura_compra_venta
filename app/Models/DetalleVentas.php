<?php

namespace App\Models;

use JetBrains\PhpStorm\Internal\TentativeType;
use Exception;

class DetalleVentas extends AbstractDBConnection implements \App\Interfaces\Model
{
    private ?int $id;
    private float $cantidad;
    private float $precio_venta;
    private int $facturaventa_id;
    private int $producto_id;


    /*Relaciones*/

    private FacturaVentas $facturaVentas;
    private Productos $productoVentas;

    /**
     * @param array $detalle_venta
     */

    public function __construct(array $detalle_venta = [])
    {
        parent::__construct();
        $this->setId($detalle_venta ['id'] ?? null);
        $this->setCantidad($detalle_venta['cantidad'] ?? 0);
        $this->setPrecioventa($detalle_venta['precio_venta'] ?? 0.0);
        $this->setFacturaventaId($detalle_venta['facturaventa_id'] ?? 0);
        $this->setProductoId($detalle_venta['producto_id'] ?? 0);
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
     * @return float
     */
    public function getCantidad(): float
    {
        return $this->cantidad;
    }

    /**
     * @param float $cantidad
     */
    public function setCantidad(float $cantidad): void
    {
        $this->cantidad = $cantidad;
    }

    /**
     * @return float
     */
    public function getPrecioventa(): float
    {
        return $this->precio_venta ;
    }

    /**
     * @param float $precio_venta
     */
    public function setPrecioventa(float $precio_venta): void
    {
        $this->precio_venta = $precio_venta;
    }

    public function getTotalProducto() : float
    {
        return $this->getPrecioventa() * $this->getCantidad();
    }


    /**
     * @return int
     */
    public function getFacturaventaId(): int
    {
        return $this->facturaventa_id;
    }

    /**
     * @param int $facturaventa_id
     */
    public function setFacturaventaId(int $facturaventa_id): void
    {
        $this->facturaventa_id = $facturaventa_id;
    }

    /**
     * @return int
     */
    public function getProductoId(): int
    {
        return $this->producto_id;
    }

    /**
     * @param int $producto_id
     */
    public function setProductoId(int $producto_id): void
    {
        $this->producto_id = $producto_id;
    }

    /**
     * @return FacturaVentas
     */
    public function getFacturaVentas(): ?FacturaVentas
    {
        if(!empty($this->facturaventa_id)){
            $this->facturaVentas = FacturaVentas::searchForId($this->facturaventa_id) ?? new FacturaVentas();
            return $this->facturaVentas;
        }
        return NULL;
    }


    /**
     * @return Productos
     */
    public function getProductoVentas(): ?Productos
    {
        if(!empty($this->producto_id)){
            $this->productoVentas = Productos::searchForId($this->producto_id) ?? new Productos();
            return $this->productoVentas;
        }
        return NULL;
    }



    protected function save(string $query,string $type = 'insert'): ?bool
    {
        if($type == 'deleted'){
            $arrData = [ ':id' =>   $this->getId() ];
        }else{
            $arrData = [
                ':id' =>   $this->getId(),
                ':cantidad' =>   $this->getCantidad(),
                ':precio_venta' =>  $this->getPrecioventa(),
                ':facturaventa_id' =>   $this->getFacturaventaId(),
                ':producto_id' =>  $this->getProductoId(),
            ];
        }

        $this->Connect();
        $result = $this->insertRow($query, $arrData);
        $this->Disconnect();
        return $result;
    }

    function insert(): ?bool
    {
        $query = "INSERT INTO ferreteria.detalleventa VALUES (:id,:cantidad,:precio_venta,:facturaventa_id,:producto_id)";
        if($this->save($query)){
            return $this->getProductoVentas()->substractStock($this->getCantidad());
        }
        return false;
    }

    function update(): ?bool
    {
        $query = "UPDATE ferreteria.detalleventa SET 
            cantidad = :cantidad, precio_venta= :precio_venta, facturaventa_id = :facturaventa_id, producto_id = :producto_id  WHERE id = :id";
        return $this->save($query);
    }

    function deleted(): ?bool
    {
        $query = "DELETE FROM detalleventa WHERE id = :id";
        return $this->save($query, 'deleted');

    }

    static function search($query): ?array
    {
        try {
            $arrDetalleVenta = array();
            $tmp = new DetalleVentas();
            $tmp->Connect();
            $getrows = $tmp->getRows($query);
            $tmp->Disconnect();

            foreach ($getrows as $precio_venta) {
                $DetalleVenta = new DetalleVentas($precio_venta);
                array_push($arrDetalleVenta, $DetalleVenta);
                unset($DetalleVenta);
            }
            return $arrDetalleVenta;
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
        return NULL;
    }

    static function searchForId(int $id): ?DetalleVentas
    {
        try {
            if ($id > 0) {
                $DetalleVenta = new DetalleVentas();
                $DetalleVenta->Connect();
                $getrow = $DetalleVenta->getRow("SELECT * FROM ferreteria.detalleventa WHERE id = ?", array($id));
                $DetalleVenta->Disconnect();
                return ($getrow) ? new DetalleVentas($getrow) : null;
            }else{
                throw new Exception('Id de detalle venta Invalido');
            }
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
        return NULL;
    }

    static function getAll(): ?array
    {
        return DetalleVentas::search("SELECT * FROM ferreteria.detalleventa");
    }

    /**
     * @param $documento
     * @return bool
     * @throws Exception
     */

    public static function detalleventaRegistrada($id): bool|null
    {
        $result = DetalleVentas::search("SELECT * FROM ferreteria.detalleventa where id = " . $id);
        if (!empty($result) && count($result)>0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $venta_id
     * @param $producto_id
     * @return bool
     */
    public static function productoEnFactura($facturaventa_id,$producto_id): bool
    {
        $result = DetalleVentas::search("SELECT id FROM ferreteria.detalleventa where facturaventa_id = '" . $facturaventa_id. "' and producto_id = '" . $producto_id. "'");
        if (count($result) > 0) {
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
        return "cantidad: $this->cantidad, 
                 precio_venta: $this->precio_venta, 
                facturaventa_id: ".$this->getFacturaVentas().", 
                producto_id: ".$this->getProductoVentas();
    }


    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return [
            'cantidad' => $this->getCantidad(),
            'precio_venta' => $this->getPrecioventa(),
            'facturaventa_id' => $this->getFacturaVentas()->jsonSerialize(),
            'producto_id' => $this->getProductoVentas(),
        ];
    }
}