<?php

namespace Measoft\Traits;

use SimpleXMLElement;

trait Client
{
    /** @var string $client Признак клиента или агента */
    private $client;

    /**
     * Признак клиента или агента
     *
     * @param bool $client true - клиент, false - агент
     * @return self
     */
    public function client(bool $client = true): self
    {
        $this->client = $client ? 'CLIENT' : 'AGENT';

        return $this;
    }

    private function buildClientXML(SimpleXMLElement $xml): void
    {
        $xml->addChild('client', $this->client);
    }
}
