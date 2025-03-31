<?php


namespace MonoPay;

class Client
{
    private $merchantId;
    private $merchantName;
    public $apiEndpoint = 'https://api.monobank.ua/';
    public $token;
    private $httpClient;

    /**
     * Створює клієнт з ключем для запитів до серверу Mono і отримує дані про мерчант
     * @param string $token Токен з особистого кабінету https://fop.monobank.ua/ або тестовий токен з https://api.monobank.ua/
     * @link https://api.monobank.ua/docs/acquiring.html#/paths/~1api~1merchant~1details/get Так отримуються деталі мерчанту
     */


    public function __construct($token)
    {
        $this->token = $token;

        $response = $this->request('GET', 'api/merchant/details');

        if ($response) {
            if ($response && isset($response['merchantId']) && isset($response['merchantName'])) {
                $this->merchantId = $response['merchantId'];
                $this->merchantName = $response['merchantName'];
            } else {
                throw new \Exception('Cannot decode json response from Mono', 200);
            }
        }


    }
    public function request($type, string $method, array $data = []): array
    {
        
        $headers = [
            'Content-Type: application/json',
            'X-Token: ' . $this->token,
            'X-Cms: Prestashop',
            'X-Cms-Version: ' . (string)_PS_VERSION_
        ];

        $options = [
            CURLOPT_URL => $this->apiEndpoint . $method,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
        ];
        if ($type == 'POST') {
            if (is_array($data) && !empty($data)) {
                $data = json_encode($data, JSON_UNESCAPED_UNICODE);
            }
            $options[CURLOPT_POSTFIELDS] = $data;
        } else {
            $options[CURLOPT_URL] .= '?' . http_build_query($data);
        }

        $ch = curl_init();
        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true) ?? [];
    }
    public function getMerchantId(): string
    {
        return $this->merchantId;
    }

    public function getMerchantName(): string
    {
        return $this->merchantName;
    }

    /**
     * Відкритий ключ для верифікації підписів
     * Отримання відкритого ключа для перевірки підпису, який включено у вебхуки. Ключ можна кешувати і робити запит на отримання нового, коли верифікація підпису з поточним ключем перестане працювати. Кожного разу робити запит на отримання ключа не треба
     * @link https://api.monobank.ua/docs/acquiring.html#/paths/~1api~1merchant~1pubkey/get
     */
    public function getPublicKey(): string
    {
        $response = $this->request('GET', 'api/merchant/pubkey');
        if (!isset($response['key'])) {
            throw new \Exception('Invalid response from Mono API', 500);
        }
        return $response['key'];
    }

    /**
     * Дані мерчанта
     * @link https://api.monobank.ua/docs/acquiring.html#/paths/~1api~1merchant~1details/get
     * @return array Масив з ключами merchantId та merchantName
     */
    public function getMerchant(): array
    {
        return $this->request('GET', '/api/merchant/details');
    }

}