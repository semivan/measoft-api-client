<?php

namespace Measoft;

class Response
{
	/**
	 * @var bool $success
	 */
	private $success;

	/**
	 * @var \SimpleXMLElement $xml Результат
	 */
	private $xml;

	/**
	 * @var string $error Сообщение об ошибке
	 */
	private $error;

	public function __construct(bool $success, \SimpleXMLElement $xml = null, string $error = null)
	{
		$this->success = $success;
		$this->xml     = $xml;
		$this->error   = $error;
	}

	/**
	 * @return boolean Успешен ли запрос
	 */
	public function isSuccess(): bool
	{
		return $this->success;
	}

	/**
	 * @return \SimpleXMLElement Результат
	 */
	public function getXml(): \SimpleXMLElement
	{
		return $this->xml;
	}

	/**
	 * @return string|null Сообщение об ошибке
	 */
	public function getError(): ?string
	{
		return $this->error;
	}
}