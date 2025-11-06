<?php
namespace Game\Model\Entity;

class Message
{
    protected $id_mensaje;
    protected $remitente;
    protected $destinatario;
    protected $asunto;
    protected $mensaje;
    protected $fecha_enviado;
    protected $leido;
    protected $borrado_rem;
    protected $borrado_dest;
    protected $id_carpeta;

    public function __construct(array $data = [])
    {
        $this->id_mensaje = $data['id_mensaje'] ?? null;
        $this->remitente = $data['remitente'] ?? 0;
        $this->destinatario = $data['destinatario'] ?? 0;
        $this->asunto = $data['asunto'] ?? '';
        $this->mensaje = $data['mensaje'] ?? '';
        $this->fecha_enviado = $data['fecha_enviado'] ?? date('Y-m-d H:i:s');
        $this->leido = $data['leido'] ?? 0;
        $this->borrado_rem = $data['borrado_rem'] ?? 0;
        $this->borrado_dest = $data['borrado_dest'] ?? 0;
        $this->id_carpeta = $data['id_carpeta'] ?? 0;
    }

    public function getArrayCopy()
    {
        return [
            'id_mensaje' => $this->id_mensaje,
            'remitente' => $this->remitente,
            'destinatario' => $this->destinatario,
            'asunto' => $this->asunto,
            'mensaje' => $this->mensaje,
            'fecha_enviado' => $this->fecha_enviado,
            'leido' => $this->leido,
            'borrado_rem' => $this->borrado_rem,
            'borrado_dest' => $this->borrado_dest,
            'id_carpeta' => $this->id_carpeta,
        ];
    }

    public function __get($name)
    {
        return $this->$name;
    }
}
