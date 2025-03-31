<?php
namespace MonoPay;
class Payment
{
    private $client;
    public function __construct(\MonoPay\Client $client)
    {
        $this->client = $client;
    }
    /**
     * Створення рахунку
     * Створення рахунку для оплати
     * @param int $amount Сума оплати у мінімальних одиницях (копійки для гривні)
     * @param array $options Додаткові параметри (Див. посилання)
     * @return array
     * @link https://api.monobank.ua/docs/acquiring.html#/paths/~1api~1merchant~1invoice~1create/post
     */
    public function create(int $amount, array $options = []): array
    {
        if ($amount < 1) {
            throw new \Exception('Amount must be a natural number', 500);
        }
        $options['amount'] = $amount;
        return $this->client->request('POST', 'api/merchant/invoice/create', $options);
    }
    /**
     * Статус рахунку
     * Метод перевірки статусу рахунку при розсинхронізації з боку продавця або відсутності webHookUrl при створенні рахунку.
     * @param string $invoiceId ID рахунку
     * @link https://api.monobank.ua/docs/acquiring.html#/paths/~1api~1merchant~1invoice~1status?invoiceId=%7BinvoiceId%7D/get
     */
    public function info(string $invoiceId): array
    {
        return $this->client->request('GET', 'api/merchant/invoice/status', [
            'invoiceId' => $invoiceId
        ]);
    }
    /**
     * Скасування оплати
     * Скасування успішної оплати рахунку
     * @param string $invoiceId ID рахунку
     * @param array $options Додаткові параметри (Див. посилання)
     * @link https://api.monobank.ua/docs/acquiring.html#/paths/~1api~1merchant~1invoice~1cancel/post
     */
    public function refund(string $invoiceId, array $options = []): array
    {
        $options['invoiceId'] = $invoiceId;
        return $this->client->request('POST', 'api/merchant/invoice/cancel', $options);
    }
    /**
     * Інвалідація рахунку
     * Інвалідація рахунку, якщо за ним ще не було здіснено оплати
     * @param string $invoiceId ID рахунку
     */
    public function cancel(string $invoiceId): array
    {
        return $this->client->request('POST', 'api/merchant/invoice/remove', [
            'invoiceId' => $invoiceId
        ]);
    }
    /**
     * Розширена інформація про успішну оплату
     * Дані про успішну оплату, якщо вона була здійснена
     * @param string $invoiceId Ідентифікатор рахунку
     *@link https://api.monobank.ua/docs/acquiring.html#/paths/~1api~1merchant~1invoice~1payment-info?invoiceId=%7BinvoiceId%7D/get
     */
    public function successDetails(string $invoiceId): array
    {
        return $this->client->request(
            'GET',
            'api/merchant/invoice/payment-info',
            [
                'invoiceId' => $invoiceId
            ]
        );
    }
    /**
     * Фіналізація суми холду
     * Фінальна сумма списання має бути нижчою або дорівнювати суммі холду
     * @param string $invoiceId Ідентифікатор рахунку
     * @param int|null $amount Сума у мінімальних одиницях, якщо бажаєте змінити сумму списання
     * @return array
     * @link https://api.monobank.ua/docs/acquiring.html#/paths/~1api~1merchant~1invoice~1finalize/post
     */
    public function captureHold(string $invoiceId, int $amount = null): array
    {
        $options = [
            'invoiceId' => $invoiceId
        ];
        if (isset($amount)) {
            $options['amount'] = $amount;
        }
        return $this->client->request(
            'POST',
            'api/merchant/invoice/finalize',
            $options
        );
    }
    /**
     * Виписка за період
     * Список платежів за вказаний період
     * @param int $fromTimestamp UTC Unix timestamp
     * @param int|null $toTimestamp UTC Unix timestamp
     * @return array
     * @link https://api.monobank.ua/docs/acquiring.html#/paths/~1api~1merchant~1statement/get
     */
    public function items(int $fromTimestamp, int $toTimestamp = null): array
    {
        $query = [
            'from' => $fromTimestamp
        ];
        if (isset($toTimestamp)) {
            $query['to'] = $toTimestamp;
        }
        $data = $this->client->request('GET', 'api/merchant/statement', [
            $query
        ]);
        return $data['list'] ?? [];
    }
}