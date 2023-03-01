<?php

namespace App\Models;

use Exception;
use JetBrains\PhpStorm\pure;
use JsonSerializable;

class DetalleCompras extends AbstractDBConnection implements \App\Interfaces\Model
{
    private ?int $id;
    private float $cantidad;
    private float $valor;
    private int  $compra_id;
    private int $producto_id;

    /* ------------Relaciones ------------*/
    private Productos $ProductoCompra;
    private FacturaCompras $facturaCompras;

    /**
     * @param array $DetalleCompra
     */
    public function __construct(array $DetalleCompra = [])
    {

        parent::__construct();
        $this->setId($DetalleCompra ['id'] ?? null);
        $this->setCantidad($DetalleCompra ['cantidad']?? 0);
        $this->setValor($DetalleCompra ['valor']?? 0.0);
        $this->setCompraId($DetalleCompra['compra_id'] ?? 0);
        $this->setProductoId($DetalleCompra['producto_id'] ?? 0);

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
    public function getValor(): float
    {
        return $this->valor;
    }

    /**
     * @param float $valor
     */
    public function setValor(float $valor): void
    {
        $this->valor = $valor;
    }


    public function getTotalProducto() : float
    {
        return $this->getValor() * $this->getCantidad();

    }

    /**
     * @return int
     */
    public function getCompraId(): ?FacturaCompras
    {
        if(!empty($this->compra_id)){
            $this->compra = FacturaCompras::searchForId($this->compra_id) ?? new FacturaCompras();
            return $this->compra;
        }
        return NULL;
    }

    /**
     * @param int $compra_id
     */
    public function setCompraId(int $compra_id): void
    {
        $this->compra_id = $compra_id;
    }


    /**
     * @return Productos
     */
    public function getProductoCompra(): ?Productos
    {
        if(!empty($this->producto_id)){
            $this->ProductoCompra = Productos::searchForId($this->producto_id) ?? new Productos();
            return $this->ProductoCompra;
        }
        return NULL;
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


    public function getFacturacompras(): ?FacturaCompras
    {
        if(!empty($this->compra_id)){
            $this->facturaCompras = FacturaCompras::searchForId($this->compra_id) ?? new FacturaCompras();
            return $this->facturaCompras;
        }
        return NULL;
    }




    protected function save(string $query, string $type = 'insert'): ?bool
    {
        if($type == 'deleted'){
            $arrData = [ ':id' =>   $this->getId() ];
        }else{
            $arrData = [
                ':id' =>   $this->getId(),
                ':cantidad' =>   $this->getCantidad(),
                ':valor' =>   $this->getValor(),
                ':compra_id' =>   $this->getCompraId()->getId(),
                ':producto_id' =>  $this->getProductoId()

            ];
        }

        $this->Connect();
        $result = $this->insertRow($query, $arrData);
        $this->Disconnect();
        return $result;
    }

    function insert(): ?bool
    {

        $query = "INSERT INTO ferreteria.detallecompra VALUES (:id,:cantidad,:valor,:compra_id,:producto_id)";
       if($this->save($query)){
           return $this->getProductoCompra()->addStock($this->getCantidad());
       }
        return false;

    }

    function update(): ?bool
    {
        $query = "UPDATE ferreteria.detallecompra SET 
            cantidad = :cantidad,
            valor = :valor,
            compra_id = :compra_id,
            producto_id = :producto_id                        
            WHERE id = :id";
        return $this->save($query);
    }

    function deleted(): ?bool
    {
        $query = "DELETE FROM detallecompra WHERE id = :id";
        return $this->save($query, 'deleted');
    }

    static function search($query): ?array
    {
        try {
            $arrDetalleCompras = array();
            $tmp = new DetalleCompras();
            $tmp->Connect();
            $getrows = $tmp->getRows($query);
            $tmp->Disconnect();

            foreach ($getrows as $valor) {
                $Compra = new DetalleCompras($valor);
                array_push($arrDetalleCompras, $Compra);
                unset($Compra);
            }
            return $arrDetalleCompras;
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
        return NULL;
    }

    static function searchForId(int $id): ?object
    {
        try {
            if ($id > 0) {
                $Compra = new DetalleCompras();
                $Compra->Connect();
                $getrow = $Compra->getRow("SELECT * FROM ferreteria.detallecompra WHERE id =?", array($id));
                $Compra->Disconnect();
                return ($getrow) ? new DetalleCompras($getrow) : null;
            }else{
                throw new Exception('Id de compra Invalido');
            }
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
        return NULL;
    }

    static function getAll(): ?array
    {
        return DetalleCompras::search("SELECT * FROM ferreteria.detallecompra");

    }
    /**
     * @param $nombre
     * @return bool
     * @throws Exception
     */
    public static function detallecompraregistrada($id): bool
    {
        $result = DetalleCompras::search("SELECT * FROM ferreteria.detallecompra  where id = " . $id);
        if (!empty($result) && count($result)>0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $compra_id
     * @param $producto_id
     * @return bool
     */
    public static function productoEnFactura($compra_id,$producto_id): bool
    {
        $result = DetalleCompras::search("SELECT id FROM ferreteria.detallecompra where compra_id = '" . $compra_id. "' and producto_id = '" . $producto_id. "'");
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
                valor: $this->valor, 
                compra_id: ".$this->getFacturacompras().",
                producto_id: ".$this->getProductoCompra();
    }


    /**
     * @inheritDoc
     */


    public function jsonSerialize(): array
    {
        return [
            'cantidad' =>  $this->getCantidad(),
            'valor'=> $this ->getValor(),
            'compra_id'=> $this->getFacturacompras()->jsonSerialize(),
            'producto_id'=>$this->getProductoCompra()
        ];
    }
}