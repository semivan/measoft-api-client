<?php

namespace Measoft\Operation;

use Measoft\MeasoftException;
use Measoft\Traits\Client;
use Measoft\Traits\StreamId;

class CommitLastStatusOperation extends AbstractOperation
{
    use Client;
    use StreamId;

    public function commit(): void
    {
        $xml = $this->getResults();

        $errorCode = (int) $xml['error'];

        if ($errorCode) {
            throw new MeasoftException($item['errormsgru'] ?? $item['errormsg'] ?? sprintf('Код ошибки: %d', $errorCode));
        }
    }

    protected function buildXml(): \SimpleXMLElement
    {
        $xml = $this->createXml('commitlaststatus');
        $xml->addChild('streamid', $this->streamId);

        return $xml;
    }
}
