<?php

namespace Measoft\Object;

use JsonSerializable;
use Measoft\MeasoftException;
use ReflectionClass;
use ReflectionProperty;
use SimpleXMLElement;

abstract class AbstractObject implements JsonSerializable
{
    abstract public static function getFromXml(SimpleXMLElement $xml, bool $fromNode = true);

    public function __construct(array $properties = [])
    {
        foreach ($properties as $property => $value) {
            if (property_exists($this, $property) AND !is_null($value)) {
                $this->$property = $value;
            }
        }
    }

    /**
     * @param SimpleXMLElement|null $xml
     * @param $key
     * @param bool $fromNode
     * @param string $type
     * @return bool|float|int|string|null
     * @throws MeasoftException
     */
    protected static function extractXmlValue(?SimpleXMLElement $xml, $key, bool $fromNode = true, string $type = 'string')
    {
        if (!$xml) return null;

        $key   = is_array($key) ? $key[$fromNode ? 'node' : 'attr'] : $key;
        $value = $fromNode ? ($xml->$key ?? null) : ($xml[$key] ?? null);

        if (is_null($value)) {
            return null;
        }

        switch ($type) {
            case 'string':
                $value = (string) $value;
                break;

            case 'int':
                $value = (int) $value;
                break;

            case 'float':
                $value = (float) $value;
                break;

            case 'bool':
                $value = (bool) $value;
                break;

            default:
                throw new MeasoftException('Неверно указан тип переменной');
        }

        return $value;
    }

    public function jsonSerialize(): array
    {
        $results = [];

        foreach ($this->getJsonPropertiesList() as $name => $getter) {
            $results[$name] = $this->$getter();
        }

        return $results;
    }

    private function getJsonPropertiesList(): array
    {
        static $cache;

        if (!isset($cache[static::class])) {
            $cache[static::class] = [];
            $reflectionClass = new ReflectionClass(static::class);
            $properties = $reflectionClass->getProperties(ReflectionProperty::IS_PROTECTED);

            foreach($properties as $property) {
                $propertyName = $property->getName();
                $getter = 'get' . strtoupper($propertyName[0]) . substr($propertyName, 1);

                if (!($reflectionClass->hasMethod($getter) and $reflectionClass->getMethod($getter)->isPublic())) {
                    continue;
                }

                $cache[static::class][$propertyName] = $getter;
            }
        }

        return $cache[static::class];
    }
}
