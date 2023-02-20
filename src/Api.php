<?php

namespace Measoft;

use GuzzleHttp\Client as HttpClient;
use SimpleXMLElement;

class Api
{
    const API_URL = 'https://home.courierexe.ru/api/';

    /** @var string $login Логин */
    private $login;

    /** @var string $password Пароль */
    private $password;

    /** @var string $extracode Код службы */
    private $extracode;

    /** @var HttpClient $httpClient */
    private $httpClient;

    /**
     * @param string $login     Логин
     * @param string $password  Пароль
     * @param string $extracode Код службы
     */
    public function __construct(string $login, string $password, string $extracode)
    {
        $this->login      = $login;
        $this->password   = $password;
        $this->extracode  = $extracode;
        $this->httpClient = new HttpClient([
            'base_uri' => self::API_URL,
        ]);
    }

    /**
     * Отправка запроса к АПИ
     *
     * @param SimpleXMLElement $xml XML с запросом
     * @param boolean $withAuth Добавлять ли к запросу параметры авторизации?
     * @return Response
     */
    public function request(SimpleXMLElement $xml, bool $withAuth = true): Response
    {
        //header('Content-type: text/xml'); print($xml->asXML()); exit;

        if ($withAuth) {
            $auth = $xml->addChild('auth');
            $auth->addAttribute('login', $this->login);
            $auth->addAttribute('pass', $this->password);
            $auth->addAttribute('extra', $this->extracode);
        }

        $sendData = [
            'body'    => $xml->asXML(),
            'headers' => [
                'Content-Type' => 'text/xml; charset=UTF8',
            ],
        ];

        try {
            $response   = $this->httpClient->request('POST', '', $sendData);
            $statusCode = $response->getStatusCode();
            $message    = $response->getReasonPhrase();
        } catch (\Exception $e) {
            $statusCode = $e->getCode();
            $message    = $e->getMessage();
        }

        if ($statusCode !== 200) {
            return new Response(false, null, "$message ($statusCode )");
        }

        $content = $response->getBody()->getContents();
        
        $xml = simplexml_load_string($content);

        //header('Content-type: text/xml'); print($xml->asXML()); exit;

        if (!$xml) {
            return new Response(false, null, 'Неверный формат полученных данных');
        }
        
        if ($error = intval($xml->attributes()['error'] ?? 0)) {
            return new Response(false, $xml, $this->getErrorMessage($error, (string) $xml));
        }
        
        return new Response(true, $xml);
    }

    /**
     * Получить сообщение об ошибке по коду
     *
     * @param integer $code Код ошибки
     * @return string
     */
    private function getErrorMessage(int $code, string $alternative): string
    {
        $errors = [
            0  => 'OK',
            1  => 'Неверный xml',
            2  => 'Широта не указана',
            3  => 'Долгота не указана',
            4  => 'Дата и время запроса не указаны',
            5  => 'Точность не указана',
            6  => 'Идентификатор телефона не указан',
            7  => 'Идентификатор телефона не найден',
            8  => 'Неверная широта',
            9  => 'Неверная долгота',
            10 => 'Неверная точность',
            11 => 'Заказы не найдены',
            12 => 'Неверные дата и время запроса',
            13 => 'Ошибка mysql',
            14 => 'Неизвестная функция',
            15 => 'Тариф не найден',
            18 => 'Город отправления не указан',
            19 => 'Город назначения не указан',
            20 => 'Неверная масса',
            21 => 'Город отправления не найден',
            22 => 'Город назначения не найден',
            23 => 'Масса не указана',
            24 => 'Логин не указан',
            25 => 'Ошибка авторизации',
            26 => 'Логин уже существует',
            27 => 'Клиент уже существует',
            28 => 'Адрес не указан',
            29 => 'Более не поддерживается',
            30 => 'Настройка sip не выполнена',
            31 => 'Телефон не указан',
            32 => 'Телефон курьера не указан',
            33 => 'Ошибка соединения',
            34 => 'Неверный номер',
            35 => 'Неверный номер',
            36 => 'Ошибка определения тарифа',
            37 => 'Ошибка определения тарифа',
            38 => 'Тариф не найден',
            39 => 'Тариф не найден',
        ];

        return $errors[$code] ?? sprintf('Неизвестная ошибка (%d: %s)', $code, $alternative);
    }
}
