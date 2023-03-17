<?php

namespace Measoft\Traits;

use InvalidArgumentException;
use SimpleXMLElement;

trait StreamId
{
    /** @var int $streamId - Идентификатор потока */
    private $streamId;

    /**
     * Задаёт идентификатор потока. Если у вас несколько интеграций и каждая нуждается в получении статусов,
     * вы можете передавать данный параметр и тем самым разделять получение и отметку об успешном получении статусов по заказам.
     * Значение должно входить в промежуток от 100 до 10000 включительно.
     *
     * @param int $streamId
     *
     * @return $this
     */
    public function setStreamId(int $streamId): self
    {
        if ($streamId < 100 or $streamId > 10000) {
            throw new InvalidArgumentException(sprintf(
                'Недопустимое значение streamId: %d (допустимый диапазон 100…10000)', $streamId
            ));
        }

        $this->streamId = $streamId;

        return $this;
    }

    private function buildStreamIdXML(SimpleXMLElement $xml): void
    {
        $xml->addChild('streamid', $this->streamId);
    }
}
