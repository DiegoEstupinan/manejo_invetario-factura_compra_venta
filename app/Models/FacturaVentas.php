<?php

namespace App\Models;

use App\Enums\EstadoFactura;
use App\Enums\EstadoGeneral;
use Carbon\Carbon;
use Exception;
use JsonSerializable;

use JetBrains\PhpStorm\Internal\TentativeType;

class FacturaVentas extends AbstractDBConnection implements \App\Interfaces\Model
{
private ?int $id;
private Carbon $fecha;
private string $monto;
private int $cliente_id;
private EstadoFactura $estado;

/*Relacion*/

    private  ?Personas $PersonasFacturaVentas;
    private array $DetalleVentasFacturaVentas;

    /**
     * facturaventa constructor. Recibe un array asociativo
     * @param array $facturaventas
     */
    public function __construct(array $facturaventas = [])
    {
        parent::__construct();
        $this->setId($facturaventas['id'] ?? NULL);
        $this->setFecha(!empty($facturaventas['fecha']) ? Carbon::parse($facturaventas['fecha']) : Carbon::now());
        $this->setMonto();
        $this->setClienteId($facturaventas['cliente_id'] ?? 0);
        $this->setEstado($facturaventas['estado'] ?? EstadoFactura::PROCESO);

    }
    function __destruct()
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
            $arrDetallesVenta = $this->getDetalleVentasFacturaVentas();
            if(!empty($arrDetallesVenta)){
                /* @var $arrDetallesVenta DetalleVentas[] */
                foreach ($arrDetallesVenta as $DetalleVenta){
                    $total += $DetalleVenta->getTotalProducto();
                }
            }
        }
        $this->monto = $total;
    }


    /**
     * @return int
     */
    public function getClienteId(): int
    {
        return $this->cliente_id;
    }

    /**
     * @param int $cliente_id
     */
    public function setClienteId(int $cliente_id): void
    {
        $this->cliente_id = $cliente_id;
    }

    /**
     * @return string
     */
    public function getEstado(): string
    {
        return $this->estado->toString();
    }

    /**
     * @param string|EstadoFactura|null $estado
     */
    public function setEstado(null|string|EstadoFactura $estado): void
    {
        if(is_string($estado)){
            $this->estado = EstadoFactura::from($estado);
        }else{
            $this->estado = $estado;
        }
    }

    /* Relaciones */
    /**
     * /**
     * Retorna el objeto persona del cliente correspondiente a la venta
     * @return Personas|null
     */

    public function getPersonasFacturaVentas(): ?Personas
    {
        if(!empty($this->cliente_id))
            $this->PersonasFacturaVentas = Personas::searchForId($this->cliente_id) ?? new Personas();

        return $this->PersonasFacturaVentas;
    }

/**
* retorna un array de detalleventa que perteneces a una facturaventa
* @return array
*/

    public function getDetalleVentasFacturaVentas(): array
    {
        $this->DetalleVentasFacturaVentas = DetalleVentas::search('SELECT * FROM ferreteria.detalleventa where facturaventa_id = '.$this->id);
        return $this->DetalleVentasFacturaVentas;
    }

    /**
     * @param string $query
     * @return bool|null
     */

    protected function save(string $query): ?bool
    {
        $arrData = [
            ':id' =>    $this->getId(),
            ':fecha' =>  $this->getFecha()->toDateString(), //YYYY-MM-DD HH:MM:SS
            ':monto' =>   $this->getMonto(),
            ':cliente_id' =>   $this->getClienteId(),
            ':estado' =>   $this->getEstado(),

        ];
        $this->Connect();
        $result = $this->insertRow($query, $arrData);
        $this->Disconnect();

        return $result;
    }


    function insert(): ?bool
    {
        $query = "INSERT INTO ferreteria.facturaventa VALUES (:id,:fecha,:monto,:cliente_id,:estado)";
        return $this->save($query);
    }

    function update(): ?bool
    {
        $query = "UPDATE ferreteria.facturaventa SET 
            fecha = :fecha,
            monto = :monto,
            cliente_id = :cliente_id,
            estado = :estado
            WHERE id = :id";

        return $this->save($query);
    }

    function deleted(): ?bool
    {
        $this->setEstado("Anulada"); //Cambia el estado de la factyra venta
        return $this->update();                      //Guarda los cambios..
    }
    function cancelFactura(){
        $result = $this->getDetalleVentasFacturaVentas();
        /* @var $result DetalleVentas[] */
        if( is_array($result) && count($result) > 0){
            foreach ($result as $detalle){
                $cantidad = $detalle->getCantidad();
                $detalle->getProductoVentas()->addStock($cantidad);
            }
        }
    }


    static function search($query): ?array
    {
        try {
            $arrfacturaventa = array();
            $tmp = new FacturaVentas();
            $tmp->Connect();
            $getrows = $tmp->getRows($query);
            $tmp->Disconnect();

            foreach ($getrows as $precio_venta) {
                $Venta = new FacturaVentas($precio_venta);
                $arrfacturaventa[] = $Venta;
                unset($Venta);
            }
            return $arrfacturaventa;
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
        return NULL;
    }

    static function searchForId(int $id): ?object
    {
        try {
            if ($id > 0) {
                $facturaventa = new FacturaVentas();
                $facturaventa->Connect();
                $getrow = $facturaventa->getRow("SELECT * FROM ferreteria.facturaventa WHERE id =?", array($id));
                $facturaventa->Disconnect();
                return ($getrow) ? new FacturaVentas($getrow) : null;
            }else{
                throw new Exception('Id de venta Invalido');
            }
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
        return NULL;
    }

    static function getAll(): ?array
    {
        return FacturaVentas::search("SELECT * FROM ferreteria.facturaventa");
    }
    /**
     * @param $nombre
     * @return bool
     * @throws Exception
     */

    public static function facturaventasRegistrada(Carbon $fecha): bool
    {

        $result = FacturaVentas::search("SELECT id FROM ferreteria.facturaventa where fecha = '" . $fecha->toDateString(). "'");
        if ( !empty($result) && count ($result) > 0 ) {
            return true;
        } else {
            return false;
        }
    }


    public function __toString(): string
    {
        return "Fecha: ".$this->fecha->toDateString().",  
                Monto: $this->monto, 
                Cliente:".$this->getPersonasFacturaVentas().",
                Estado: ".$this->estado->toString();
    }


    /**
     * @inheritDoc
     */

    public function jsonSerialize(): array
    {
        return [
            'id'=> $this->getId(),
            'fecha' => $this->getFecha()->toDateString(),
            'monto' => $this->getMonto(),
            'cliente' => $this->getPersonasFacturaVentas()->jsonSerialize(),
            'estado' => $this->getEstado(),

        ];
    }
}