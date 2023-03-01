<?php

namespace App\Models;

use App\Enums\EstadoFactura;
use App\Enums\EstadoGeneral;
use JetBrains\PhpStorm\Internal\TentativeType;
use PhpParser\Node\Scalar\String_;
use Carbon\Carbon;
use Exception;
use JsonSerializable;

class FacturaCompras extends AbstractDBConnection implements \App\Interfaces\Model
{
    private ?int $id;
    private carbon $fecha;
    private string $monto;
    private int $proveedor_id;
    private EstadoFactura $estado;


    //relaciones
    private array $DetalleComprasFacturaCompras;
    private ?Personas $proveedor;


    /**
     * @param array $FacturaCompra
     */
    public function __construct(array $FacturaCompra=[])
    {
        parent::__construct();
        $this->setId ($FacturaCompra['id'] ?? NULL);
        $this->setFecha(!empty($FacturaCompra['fecha'])? carbon::parse($FacturaCompra['fecha']) : Carbon::now());
        $this->setMonto ();
        $this ->setProveedorId($FacturaCompra ['proveedor_id'] ?? 0);
        $this ->setEstado($FacturaCompra ['estado'] ?? EstadoFactura::PROCESO);
    }

    public function __destruct()
    {

        if ($this->isConnected()) {
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
     * @return Carbon
     */
    public function getFecha(): Carbon
    {
        return $this->fecha->locale('es');
    }

    /**
     * @param Carbon $fecha
     */
    public function setFecha(Carbon $fecha): void
    {
        $this->fecha = $fecha;
    }

    /**
     * @return string
     */
    public function getMonto(): string
    {
        return $this->monto;
    }

    /**
     * @param string $monto
     */
    public function setMonto(): void
    {
        $total = 0;
        if($this->getId() != null){
            $arrFacturaCompra = $this->getDetalleComprasFacturaCompras();
            if(!empty($arrFacturaCompra)){
                /* @var $arrFacturaCompra DetalleCompras[]
                 */
                foreach ($arrFacturaCompra as $DetalleCompra){
                    $total += $DetalleCompra->getTotalProducto();
                }
            }
        }
        $this->monto = $total;
    }

    /**
     * @return int
     */
    public function getProveedorId(): int
    {
        return $this->proveedor_id;
    }

    /**
     * @param int $proveedor_id
     */
    public function setProveedorId(int $proveedor_id): void
    {
        $this->proveedor_id = $proveedor_id;
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
    public function setEstado(null|string|EstadoFactura $estado): void
    {
        if(is_string($estado)){
            $this->estado = EstadoFactura::from($estado);
        }else{
            $this->estado = $estado;
        }
    }

    /**
     * @return array|null
     */
    public function getDetalleComprasFacturaCompras(): ?array
    {
        $this->DetalleComprasFacturaCompras = DetalleCompras::search('SELECT * FROM ferreteria.detallecompra where compra_id = '.$this->id);
        return $this->DetalleComprasFacturaCompras;
    }


    /**

     * @return Personas
     */
    public function getProveedor(): ?Personas
    {
        if(!empty($this->proveedor_id))
            $this->proveedor = Personas::searchForId($this->proveedor_id) ??new Personas();

            return $this->proveedor;

    }



    protected function save(string $query): ?bool
    {
        $arrData = [
            ':id' =>    $this->getId(),
            ':fecha' =>   $this->getFecha() ->ToDateString(),
            ':monto' =>   $this->getMonto(),
            ':proveedor_id' =>   $this->getProveedorId(),
            ':estado' =>  $this->getEstado()
        ];
        $this->Connect();
        $result = $this->insertRow($query, $arrData);
        $this->Disconnect();
        return $result;
    }

    function insert(): ?bool
    {
        $query = "INSERT INTO ferreteria.facturacompra VALUES (:id,:fecha,:monto,:proveedor_id,:estado)";
        return $this->save($query);
    }

    function update(): ?bool
    {
        $query = "UPDATE ferreteria.facturacompra SET 
            fecha = :fecha, 
            monto = :monto, 
            proveedor_id = :proveedor_id, 
            estado = :estado 
            WHERE id = :id";
        return $this->save($query);
    }

    function deleted(): ?bool
    {
        $this->setEstado(EstadoFactura::ANULADA); //Cambia el estado de la factura compra
        return $this->update();
    }

    static function search($query): ?array
    {
        try {
            $arrFacturaCompra = array();
            $tmp = new FacturaCompras();
            $tmp->Connect();
            $getrows = $tmp->getRows($query);
            $tmp->Disconnect();

            foreach ($getrows as $valor) {
                $Compra = new FacturaCompras($valor);
                //array_push($arrFacturaCompra, $Compra);
                $arrFacturaCompra[] = $Compra;
                unset($Compra);
            }
            return $arrFacturaCompra;
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
        return NULL;
    }

    static function searchForId(int $id): ?object
    {
        try {
            if ($id > 0) {
                $Compra = new FacturaCompras();
                $Compra->Connect();
                $getrow = $Compra->getRow("SELECT * FROM ferreteria.facturacompra WHERE id =?", array($id));
                $Compra->Disconnect();
                return ($getrow) ? new FacturaCompras($getrow) : null;
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
        return FacturaCompras::search("SELECT * FROM ferreteria.facturacompra");
    }

    public function __toString(): string
    {
        return "Fecha: ".$this->fecha->toDateString().",  
               Monto: $this->monto, 
               Proveedor:".$this->getProveedor().",
               Estado: ".$this->estado->toString();
    }


    public static function facturacomprasRegistrada(Carbon $fecha): bool
    {

        $result = FacturaCompras::search("SELECT id FROM ferreteria.facturacompra where fecha = '" . $fecha->toDateString(). "'");
        if ( !empty($result) && count ($result) > 0 ) {
            return true;
        } else {
            return false;
        }
    }

    function cancelFactura(){
        $result = $this->getDetalleComprasFacturaCompras();
        /* @var $result DetalleCompras[] */
        if( is_array($result) && count($result) > 0){
            foreach ($result as $detalle){
                $cantidad = $detalle->getCantidad();
                $detalle->getProductoCompra()->substractStock($cantidad);
            }
        }
    }


    /**
     * @inheritDoc
     */
    public function jsonSerialize():array
    {
        return [
            'id'=> $this->getId(),
            'fecha' =>  $this->getFecha()->toDateString(),
            'monto'=> $this ->getMonto(),
            'proveedor'=> $this->getProveedor()->jsonSerialize(),
            'estado'=>$this->getEstado(),
        ];


    }
}