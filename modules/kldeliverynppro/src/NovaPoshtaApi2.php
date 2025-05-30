<?php
/**
* @author    Antikov Evgeniy
* @copyright 2017-2022 kLooKva
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*/

namespace NovaPoshta\Pro;

class NovaPoshtaApi2
{
    /**
     * Key for API NovaPoshta
     *
     * @var string $key
     * @see https://my.novaposhta.ua/settings/index#apikeys
     */
    protected $key;
    
    /**
     * @var bool $throwErrors Throw exceptions when in response is error
     */
    protected $throwErrors = false;
    
    /**
     * @var string $format Format of returned data - array, json, xml
     */
    protected $format = 'array';
    
    /**
     * @var string $language Language of response
     */
    protected $language = 'ru';
    
    /**
     * @var string $connectionType Connection type (curl | file_get_contents)
     */
    protected $connectionType = 'curl';
    
    /**
     * @var string $areas Areas (loaded from file, because there is no so function in NovaPoshta API 2.0)
     */
    protected $areas;
    
    /**
     * @var string $model Set current model for methods save(), update(), delete()
     */
    protected $model = 'Common';
    
    /**
     * @var string $method Set method of current model
     */
    protected $method;
    
    /**
     * @var array $params Set params of current method of current model
     */
    protected $params;

    protected $email;

    /**
     * Default constructor
     *
     * @param string $key NovaPoshta API key
     * @param string $language Default Language
     * @param bool $throwErrors Throw request errors as Exceptions
     * @param bool $connectionType Connection type (curl | file_get_contents)
     * @return NovaPoshtaApi2
     */
    public function __construct($key, $language = 'ru', $throwErrors = false, $connectionType = 'curl', $email = false)
    {
        $this->throwErrors = $throwErrors;
        $this->email = $email;
        return $this
            ->setKey($key)
            ->setLanguage($language)
            ->setConnectionType($connectionType)
            ->model('Common');
    }
    
    /**
     * Setter for key property
     *
     * @param string $key NovaPoshta API key
     * @return NovaPoshtaApi2
     */
    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }
    
    /**
     * Getter for key property
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }
    
    /**
     * Setter for $connectionType property
     *
     * @param string $connectionType Connection type (curl | file_get_contents)
     * @return this
     */
    public function setConnectionType($connectionType)
    {
        $this->connectionType = $connectionType;
        return $this;
    }
    
    /**
     * Getter for $connectionType property
     *
     * @return string
     */
    public function getConnectionType()
    {
        return $this->connectionType;
    }
    
    /**
     * Setter for language property
     *
     * @param string $language
     * @return NovaPoshtaApi2
     */
    public function setLanguage($language)
    {
        $this->language = $language;
        return $this;
    }
    
    /**
     * Getter for language property
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }
    
    /**
     * Setter for format property
     *
     * @param string $format Format of returned data by methods (json, xml, array)
     * @return NovaPoshtaApi2
     */
    public function setFormat($format)
    {
        $this->format = $format;
        return $this;
    }
    
    /**
     * Getter for format property
     *
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }
    
    /**
     * Prepare data before return it
     *
     * @param json $data
     * @return mixed
     */
    private function prepare($data)
    {
        //Returns array
        if ($this->format == 'array') {
            $result = is_array($data)
                ? $data
                : json_decode($data, 1);
            // If error exists, throw Exception
            if ($this->throwErrors and $result['errors']) {
                throw new \Exception(is_array($result['errors']) ? implode("\n", $result['errors']) :
                    $result['errors']);
            } elseif ($result['errors'] and $this->email) {
                $text = is_array($result['errors']) ? implode("\n", $result['errors']) : $result['errors'];
                if (trim($text) !== '') {
                    $this->sendErrorMail($text);
                }
            }
            return $result;
        }
        // Returns json or xml document
        return $data;
    }

    private function sendErrorMail($text)
    {
        return $text;
    }
    
    /**
     * Converts array to xml
     *
     * @param array
     */
    private function array2xml(array $array, $xml = false)
    {
        ($xml === false) and $xml = new \SimpleXMLElement('<root/>');
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $this->array2xml($value, $xml->addChild($key));
            } else {
                $xml->addChild($key, $value);
            }
        }
        return $xml->asXML();
    }
    
    /**
     * Make request to NovaPoshta API
     *
     * @param string $model Model name
     * @param string $method Method name
     * @param array $params Required params
     */
    private function request($model, $method, $params = null)
    {
        // Get required URL
        $url = $this->format == 'xml'
            ? 'https://api.novaposhta.ua/v2.0/xml/'
            : 'https://api.novaposhta.ua/v2.0/json/';
        
        $data = array(
            'apiKey' => $this->key,
            'modelName' => $model,
            'calledMethod' => $method,
            //'language' => $this->language,
			//'language' => 'ru',
            'methodProperties' => $params
        );
        // Convert data to neccessary format
        $post = $this->format == 'xml'
            ? $this->array2xml($data)
            : $post = json_encode($data);

        if ($this->getConnectionType() == 'curl') {
            //$ch = curl_init($url);

            // @todo: 29-01-2025 виправлення помилки запиту curl "Maximum response size reached"
            $ch = curl_init();
            curl_setopt_array($ch, array(
                CURLOPT_HTTPHEADER => array('Content-Type: '.($this->format == 'xml' ? 'text/xml' : 'application/json')),
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_POSTFIELDS => $post,
            ));
            /*
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            //curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: '.($this->format == 'xml' ? 'text/xml' : 'application/json')));
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
            */
            $result = curl_exec($ch);

			if($_SERVER['REMOTE_ADDR'] == '134.249.84.233') {
                echo '<pre>';
                    var_dump($this->format, $post, curl_error($ch), $result);
                echo '</pre>';
                exit;
            }
            curl_close($ch);
        } 
		else 
		{
            $result = \Tools::file_get_contents($url, null, stream_context_create(array(
                'http' => array(
                    'method' => 'POST',
                    'header' => "Content-type: application/x-www-form-urlencoded;\r\n",
                    'content' => $post,
                ),
            )));
        }
        //if($_SERVER['REMOTE_ADDR'] == '134.249.84.233') { echo '<pre>'; var_dump($result); echo '</pre>';  exit;}
        return $this->prepare($result);
    }

    /**
     * Set current model and empties method and params properties
     *
     * @param string $model
     * @return mixed
     */
    public function model($model = '')
    {
        if (! $model) {
            return $this->model;
        }

        $this->model = $model;
        $this->method = null;
        $this->params = null;
        return $this;
    }
    /**
     * Set method of current model property and empties params properties
     *
     * @param string $method
     * @return mixed
     */
    public function method($method = '')
    {
        if (! $method) {
            return $this->method;
        }

        $this->method = $method;
        $this->params = null;
        return $this;
    }
    
    /**
     * Set params of current method/property property
     *
     * @param array $params
     * @return mixed
     */
    public function params($params)
    {
        $this->params = $params;
        return $this;
    }
    
    /**
     * Execute request to NovaPoshta API
     *
     * @return mixed
     */
    public function execute()
    {
        return $this->request($this->model, $this->method, $this->params);
    }
    
    /**
     * Get tracking information by track number
     *
     * @param string $track Track number
     * @return mixed
     */
    public function documentsTracking($track)
    {
        return $this->request('TrackingDocument', 'getStatusDocuments', array(
            'Documents' => array(array('DocumentNumber' => $track,'Phone' => ''))));
    }
    
    /**
     * Get cities of company NovaPoshta
     *
     * @param int $page Num of page
     * @param string $findByString Find city by russian or ukrainian word
     * @param string $ref ID of city
     * @return mixed
     */
    public function getCities($page = 0, $findByString = '', $ref = '')
    {
        return $this->request('Address', 'getCities', array(
            'Page' => $page,
            'FindByString' => $findByString,
            'Ref' => $ref,
        ));
    }
    
    /**
     * Get warehouses by city
     *
     * @param string $cityRef ID of city
     * @param int $page
     * @return mixed
     */
    public function getWarehouses($cityRef, $page = 0)
    {
        return $this->request('Address', 'getWarehouses', array(
            'CityRef' => $cityRef,
            'Page' => $page,
        ));
    }

    /**
     * Get all warehouses
     *
     * @param int $page
     * @return mixed
     */
    public function getAllWarehouses($page = 0)
    {
        return $this->request('Address', 'getWarehouses', array(
            'Page' => $page,
        ));
    }
    
    /**
     * Get 5 nearest warehouses by array of strings
     *
     * @param array $searchStringArray
     * @return mixed
     */
    public function findNearestWarehouse($searchStringArray)
    {
        $searchStringArray = (array) $searchStringArray;
        return $this->request('Address', 'findNearestWarehouse', array(
            'SearchStringArray' => $searchStringArray,
        ));
    }
    
    /**
     * Get warehouse by city name and warehouse's description
     *
     * @param string $cityRef ID of city
     * @param string $description Description like in getted by getWarehouses()
     * @return mixed
     */
    public function getWarehouse($cityRef, $description = '')
    {
        $warehouses = $this->getWarehouses($cityRef);
        $data = null;
        if (is_array($warehouses['data']) && !empty($warehouses['data'])) {
            if (count($warehouses['data']) == 1) {
                $data = $warehouses['data'][0];
            } elseif (count($warehouses['data']) > 1 and $description) {
                foreach ($warehouses['data'] as $warehouse) {
                    if (mb_stripos($warehouse['Description'], $description) !== false
                    or mb_stripos($warehouse['DescriptionRu'], $description) !== false) {
                        $data = $warehouse;
                        break;
                    }
                }
            }
        }
        $error = '';
        // Error
        (! $data) and $error = 'Warehouse was not found';
        // Return data in same format like NovaPoshta API
        return $this->prepare(
            array(
                'success' => empty($error),
                'data' => array($data),
                'errors' => (array) $error,
                'warnings' => array(),
                'info' => array(),
            )
        );
    }
    
    /**
     * Get streets list by city and/or search string
     *
     * @param string $cityRef ID of city
     * @param string $findByString
     * @param int $page
     * @return mixed
     */
    public function getStreet($cityRef, $findByString = '', $page = 0)
    {
        return $this->request('Address', 'getStreet', array(
            'FindByString' => $findByString,
            'CityRef' => $cityRef,
            'Page' => $page,
        ));
    }
    
    /**
     * Find current area in list of areas
     *
     * @param array $areas List of arias, getted from file
     * @param string $findByString Area name
     * @param string $ref Area Ref ID
     * @return array
     */
    protected function findArea(array $areas, $findByString = '', $ref = '')
    {
        $data = array();
        if (! $findByString and ! $ref) {
            return $data;
        }
        // Try to find current region
        foreach ($areas as $key => $area) {
            // Is current area found by string or by key
            $found = $findByString
                ? ((mb_stripos($area['Description'], $findByString) !== false)
                    or (mb_stripos($area['DescriptionRu'], $findByString) !== false)
                    or (mb_stripos($area['Area'], $findByString) !== false)
                    or (mb_stripos($area['AreaRu'], $findByString) !== false))
                : ($key == $ref);
            if ($found) {
                $area['Ref'] = $key;
                $data[] = $area;
                break;
            }
        }
        return $data;
    }
    
    /**
     * Get area by name or by ID
     *
     * @param string $findByString Find area by russian or ukrainian word
     * @param string $ref Get area by ID
     * @return array
     */
    public function getArea($findByString = '', $ref = '')
    {
        // Load areas list from file
        empty($this->areas) and $this->areas = include dirname(__FILE__).'/NovaPoshtaApi2Areas.php';
        $data = $this->findArea($this->areas, $findByString, $ref);
        $error = '';
        // Error
        empty($data) and $error = 'Area was not found';
        // Return data in same format like NovaPoshta API
        return $this->prepare(
            array(
                'success' => empty($error),
                'data' => $data,
                'errors' => (array) $error,
                'warnings' => array(),
                'info' => array(),
            )
        );
    }
    
    /**
     * Get areas list by city and/or search string
     *
     * @param string $ref ID of area
     * @param int $page
     * @return mixed
     */
    public function getAreas($ref = '', $page = 0)
    {
        return $this->request('Address', 'getAreas', array(
            'Ref' => $ref,
            'Page' => $page,
        ));
    }
    
    /**
     * Find city from list by name of region
     *
     * @param array $cities Array from query getCities to NovaPoshta
     * @param string $areaName
     * @return array
     */
    protected function findCityByRegion($cities, $areaName, $clarifyingWord = '')
    {
        $data = array();
        $areaRef = '';
        // Get region id
        $area = $this->getArea($areaName);
        $area['success'] and $areaRef = $area['data'][0]['Ref'];
        if ($areaRef and is_array($cities['data'])) {
            foreach ($cities['data'] as $city) {
                if ($city['Area'] == $areaRef &&
                    ($clarifyingWord === '' ||
                        $clarifyingWord === $city['Description'] ||
                        $clarifyingWord === $city['DescriptionRu']
                    )
                ) {
                    $data = $city;
                }
            }
        }
        return $data;
    }
    
    /**
     * Get city by name and region (if it needs)
     *
     * @param string $cityName City's name
     * @param string $areaName Region's name
     * @return array City's data
     */
    public function getCity($cityName, $areaName = '')
    {
        // Get cities by name
        $cities = $this->getCities(0, $cityName);
        $data = null;
        if (is_array($cities['data']) && !empty($cities['data'])) {
            // If cities more then one, calculate current by area name
            if (count($cities['data']) > 1) {
                if (!empty($areaName)) {
                    $data = $this->findCityByRegion($cities, $areaName, $cityName);
                }
                if (empty($data)) {
                    foreach ($cities['data'] as $city) {
                        if ($cityName === $city['Description'] || $cityName === $city['DescriptionRu']) {
                            $data = $city;
                            break;
                        }
                    }
                }
            } else {
                $data = $cities['data'][0];
            }
        }
        $error = '';
        // Error
        (! $data) and $error = 'City was not found';
        // Return data in same format like NovaPoshta API
        return $this->prepare(
            array(
                'success' => empty($error),
                'data' => array($data),
                'errors' => (array) $error,
                'warnings' => array(),
                'info' => array(),
            )
        );
    }
    
    /**
     * Magic method of calling functions (uses for calling Common Model of NovaPoshta API)
     *
     * @param string $method Called method of Common Model
     * @param array $arguments Array of params
     */
    public function __call($method, $arguments)
    {
        $common_model_method = array(
            'getTypesOfCounterparties',
            'getBackwardDeliveryCargoTypes',
            'getCargoDescriptionList',
            'getCargoTypes',
            'getDocumentStatuses',
            'getOwnershipFormsList',
            'getPalletsList',
            'getPaymentForms',
            'getTimeIntervals',
            'getServiceTypes',
            'getTiresWheelsList',
            'getTraysList',
            'getTypesOfAlternativePayers',
            'getTypesOfPayers',
            'getTypesOfPayersForRedelivery',
        );
        $new_method = $method;
        if (isset($arguments['metadot'])) {
            $new_method = $arguments['metadot'];
        }
        // Call method of Common model
        if (in_array($new_method, $common_model_method)) {
            return $this
                ->model('Common')
                ->method($new_method)
                ->params(null)
                ->execute();
        }
    }
    
    /**
     * Delete method of current model
     *
     * @param array $params
     * @return mixed
     */
    public function delete($params)
    {
        return $this->request($this->model, 'delete', $params);
    }
    
    /**
     * Update method of current model
     * Required params:
     * For ContactPerson model: Ref, CounterpartyRef, FirstName (ukr), MiddleName, LastName, Phone (format 0xxxxxxxxx)
     * For Counterparty model: Ref, CounterpartyProperty (Recipient|Sender), CityRef,
     * CounterpartyType (Organization, PrivatePerson),
     * FirstName (or name of organization), MiddleName, LastName, Phone (0xxxxxxxxx), OwnershipForm (if Organization)
     *
     * @param array $params
     * @return mixed
     */
    public function update($params)
    {
        return $this->request($this->model, 'update', $params);
    }
    
    /**
     * Save method of current model
     * Required params:
     * For ContactPerson model (only for Organization API key, for PrivatePerson error will be returned):
     *   CounterpartyRef, FirstName (ukr), MiddleName, LastName, Phone (format 0xxxxxxxxx)
     * For Counterparty model:
     *   CounterpartyProperty (Recipient|Sender), CityRef, CounterpartyType (Organization, PrivatePerson),
     *   FirstName (or name of organization), MiddleName, LastName, Phone (0xxxxxxxxx), OwnershipForm (if Organization)
     *
     * @param array $params
     * @return mixed
     */
    public function save($params)
    {
        return $this->request($this->model, 'save', $params);
    }
    
    /**
     * getCounterparties() function of model Counterparty
     *
     * @param string $counterpartyProperty Type of Counterparty (Sender|Recipient)
     * @param int $page Page number
     * @param string $findByString String to search
     * @param string $cityRef City ID
     * @return mixed
     */
    public function getCounterparties(
        $counterpartyProperty = 'Recipient',
        $page = null,
        $findByString = null,
        $cityRef = null
    ) {
        // Any param can be skipped
        $params = array();
        $params['CounterpartyProperty'] = $counterpartyProperty ? $counterpartyProperty : 'Recipient';
        $page and $params['Page'] = $page;
        $findByString and $params['FindByString'] = $findByString;
        $cityRef and $params['CityRef'] = $cityRef;
        return $this->request('Counterparty', 'getCounterparties', $params);
    }
    
    /**
     * cloneLoyaltyCounterpartySender() function of model Counterparty
     * The counterparty will be not created immediately, you can wait a long time
     *
     * @param string $cityRef City ID
     * @return mixed
     */
    public function cloneLoyaltyCounterpartySender($cityRef)
    {
        return $this->request('Counterparty', 'cloneLoyaltyCounterpartySender', array('CityRef' => $cityRef));
    }
    
    /**
     * getCounterpartyContactPersons() function of model Counterparty
     *
     * @param string $ref Counterparty ref
     * @return mixed
     */
    public function getCounterpartyContactPersons($ref)
    {
        return $this->request('Counterparty', 'getCounterpartyContactPersons', array('Ref' => $ref));
    }
    
    /**
     * getCounterpartyAddresses() function of model Counterparty
     *
     * @param string $ref Counterparty ref
     * @param int $page
     * @return mixed
     */
    public function getCounterpartyAddresses($ref, $page = 0)
    {
        return $this->request('Counterparty', 'getCounterpartyAddresses', array('Ref' => $ref, 'Page' => $page));
    }
    
    /**
     * getCounterpartyOptions() function of model Counterparty
     *
     * @param string $ref Counterparty ref
     * @return mixed
     */
    public function getCounterpartyOptions($ref)
    {
        return $this->request('Counterparty', 'getCounterpartyOptions', array('Ref' => $ref));
    }
    
    /**
     * getCounterpartyByEDRPOU() function of model Counterparty
     *
     * @param string $edrpou EDRPOU code
     * @param string $cityRef City ID
     * @return mixed
     */
    public function getCounterpartyByEDRPOU($edrpou, $cityRef)
    {
        return $this->request('Counterparty', 'getCounterpartyByEDRPOU', array('EDRPOU' => $edrpou,
            'cityRef' => $cityRef));
    }

    /**
     * Get price of delivery between two cities
     *
     * @param string $citySender City ID
     * @param string $cityRecipient City ID
     * @param string $serviceType (DoorsDoors|DoorsWarehouse|WarehouseWarehouse|WarehouseDoors)
     * @param float $weight
     * @param float $cost
     * @param bool $redelivery
     * @return mixed
     */
    public function getDocumentPrice($citySender, $cityRecipient, $serviceType, $weight, $cost, $redelivery = false)
    {
        $options = array(
            'CitySender' => $citySender,
            'CityRecipient' => $cityRecipient,
            'ServiceType' => $serviceType,
            'Weight' => $weight,
            'Cost' => $cost,
            'RedeliveryCalculate' => $redelivery
        );

        if ($redelivery) {
            $options['RedeliveryCalculate'] = array(
                'CargoType' => 'Money',
                'Amount' => $cost,
            );
        }

        return $this->request('InternetDocument', 'getDocumentPrice', $options);
    }

    /**
     * Get approximately date of delivery between two cities
     *
     * @param string $citySender City ID
     * @param string $cityRecipient City ID
     * @param string $serviceType (DoorsDoors|DoorsWarehouse|WarehouseWarehouse|WarehouseDoors)
     * @param string $dateTime Date of shipping
     * @return mixed
     */
    public function getDocumentDeliveryDate($citySender, $cityRecipient, $serviceType, $dateTime)
    {
        return $this->request('InternetDocument', 'getDocumentDeliveryDate', array(
            'CitySender' => $citySender,
            'CityRecipient' => $cityRecipient,
            'ServiceType' => $serviceType,
            'DateTime' => $dateTime,
        ));
    }
    
    /**
     * Get documents list
     *
     * @param array $params List of params
     * Not required keys:
     * 'Ref', 'IntDocNumber', 'InfoRegClientBarcodes', 'DeliveryDateTime', 'RecipientDateTime',
     * 'CreateTime', 'SenderRef', 'RecipientRef', 'WeightFrom', 'WeightTo',
     * 'CostFrom', 'CostTo', 'SeatsAmountFrom', 'SeatsAmountTo', 'CostOnSiteFrom',
     * 'CostOnSiteTo', 'StateIds', 'ScanSheetRef', 'DateTime', 'DateTimeFrom',
     * 'RecipientDateTime', 'isAfterpayment', 'Page', 'OrderField =>
     *   [
     *    IntDocNumber, DateTime, Weight, Cost, SeatsAmount, CostOnSite,
     *    CreateTime, EstimatedDeliveryDate, StateId, InfoRegClientBarcodes, RecipientDateTime
     *   ],
     * 'OrderDirection' => [DESC, ASC], 'ScanSheetRef'
     * @return mixed
     */
    public function getDocumentList($params = null)
    {
        return $this->request('InternetDocument', 'getDocumentList', $params ? $params : null);
    }
    
    /**
     * Get document info by ID
     *
     * @param string $ref Document ID
     * @return mixed
     */
    public function getDocument($ref)
    {
        return $this->request('InternetDocument', 'getDocument', array(
            'Ref' => $ref,
        ));
    }
    
    /**
     * Generetes report by Document refs
     *
     * @param array $params Params like getDocumentList with requiered keys
     *  'Type' => [xls, csv], 'DocumentRefs' => []
     * @return mixed
     */
    public function generateReport($params)
    {
        return $this->request('InternetDocument', 'generateReport', $params);
    }

    /**
     * Check required fields for new InternetDocument and set defaults
     *
     * @param array & $counterparty Recipient info array
     * @return void
     */
    protected function checkInternetDocumentRecipient(array &$counterparty)
    {
        // Check required fields
        if (! $counterparty['FirstName']) {
            throw new \Exception('FirstName is required filed for recipient');
        }
        // MiddleName realy is not required field, but manual says otherwise
        // if ( ! $counterparty['MiddleName'])
        // throw new \Exception('MiddleName is required filed for sender and recipient');
        if (! $counterparty['LastName']) {
            throw new \Exception('LastName is required filed for recipient');
        }
        if (! $counterparty['Phone']) {
            throw new \Exception('Phone is required filed for recipient');
        }
        if (! ($counterparty['City'] or $counterparty['CityRef'])) {
            throw new \Exception('City is required filed for recipient');
        }
        if (! ($counterparty['Region'] or $counterparty['CityRef'])) {
            throw new \Exception('Region is required filed for recipient');
        }
    
        // Set defaults
        if (!array_key_exists('CounterpartyType', $counterparty)) {
            $counterparty['CounterpartyType'] = 'PrivatePerson';
        }
    }
    
    /**
     * Check required params for new InternetDocument and set defaults
     *
     * @param array & $params
     * @return void
     */
    protected function checkInternetDocumentParams(array &$params)
    {
        if (! $params['Description']) {
            throw new \Exception('Description is required filed for new Internet document');
        }
        if (! $params['Weight']) {
            throw new \Exception('Weight is required filed for new Internet document');
        }
        if (! $params['Cost']) {
            throw new \Exception('Cost is required filed for new Internet document');
        }
        (! array_key_exists('DateTime', $params)) and $params['DateTime'] = date('d.m.Y');
        (! array_key_exists('ServiceType', $params)) and $params['ServiceType'] = 'WarehouseWarehouse';
        (! array_key_exists('PaymentMethod', $params)) and $params['PaymentMethod'] = 'Cash';
        (! array_key_exists('PayerType', $params)) and $params['PayerType'] = 'Recipient';
        (! array_key_exists('SeatsAmount', $params)) and $params['SeatsAmount'] = '1';
        (! array_key_exists('CargoType', $params)) and $params['CargoType'] = 'Cargo';
        (! array_key_exists('VolumeGeneral', $params)) and $params['VolumeGeneral'] = '0.0004';
    }
    
    /**
     * Create Internet Document by
     *
     * @param array $sender Sender info.
     *     Required:
     *  For existing sender:
     *      'Description' => String (Full name i.e.), 'City' => String (City name)
     *  For creating:
     *         'FirstName' => String, 'MiddleName' => String,
     *         'LastName' => String, 'Phone' => '000xxxxxxx', 'City' => String (City name),
     *         'Region' => String (Region name),
     *         'Warehouse' => String (Description from getWarehouses))
     * @param array $recipient Recipient info, same like $sender param
     * @param array $params Additional params of Internet Document
     *  Required:
     *         'Description' => String, 'Weight' => Float, 'Cost' => Float
     *  Recommended:
     *         'VolumeGeneral' => Float (default = 0.004), 'SeatsAmount' => Int (default = 1),
     *         'PayerType' => (Sender|Recipient - default), 'PaymentMethod' => (NonCash|Cash - default)
     *         'ServiceType' => (DoorsDoors|DoorsWarehouse|WarehouseDoors|WarehouseWarehouse - default)
     *         'CargoType' => String
     * @param mixed
     */
    public function allcheckInternetDocument($sender, $recipient, $params, $recipientAddress = false, $afterpaymentOnGoodsCost = false)
    {
        if (!$recipientAddress) {
            $this->checkInternetDocumentRecipient($recipient);
        }
        $this->checkInternetDocumentParams($params);
        if (! array_key_exists('CitySender', $sender)) {
            $senderCity = $this->getCity($sender['City'], $sender['Region']);
            $sender['CitySender'] = $senderCity['data'][0]['Ref'];
        }
        $sender['CityRef'] = $sender['CitySender'];
        if (! array_key_exists('SenderAddress', $sender) and $sender['CitySender'] and $sender['Warehouse']) {
            $senderWarehouse = $this->getWarehouse($sender['CitySender'], $sender['Warehouse']);
            $sender['SenderAddress'] = $senderWarehouse['data'][0]['Ref'];
        }
        if (! array_key_exists('Sender', $sender)) {
            $sender['CounterpartyProperty'] = 'Sender';
            // Set full name to Description if is not set
            if (! array_key_exists('Description', $sender)) {
                $sender['Description'] = $sender['LastName'].' '.$sender['FirstName'].' '.$sender['MiddleName'];
            }
            // Check for existing sender
            $senderCounterpartyExisting = $this->getCounterparties(
                'Sender',
                1,
                $sender['Description'],
                $sender['CityRef']
            );
            // Copy user to the selected city if user doesn't exists there
            if (empty($senderCounterpartyExisting['data']) || !$senderCounterpartyExisting['data'][0]['Ref']) {
                $senderCounterpartyExisting = $this->getCounterparties(
                    'Sender',
                    1
                );
            }
            $sender['Sender'] = $senderCounterpartyExisting['data'][0]['Ref'];
            $contactSender = $this->getCounterpartyContactPersons($sender['Sender']);
            $needContactSender = $contactSender['data'][0];
            foreach ($contactSender['data'] as $data) {
                if ($data['LastName'] == $sender['LastName'] &&
                    $data['FirstName'] == $sender['FirstName'] &&
                    $data['MiddleName'] == $sender['MiddleName']
                ) {
                    $needContactSender = $data;
                }
            }
            $sender['ContactSender'] = $needContactSender['Ref'];
            $sender['SendersPhone'] = $sender['Phone'] ? $sender['Phone'] : $needContactSender['Phones'];
            if ($afterpaymentOnGoodsCost){
                $sender['AfterpaymentOnGoodsCost'] = $afterpaymentOnGoodsCost;
            }
        }
        
        // Prepare recipient data
        if (!$recipientAddress) {
            $recipient['CounterpartyProperty'] = 'Recipient';
            $recipient['RecipientsPhone'] = $recipient['Phone'];
            if (! array_key_exists('CityRecipient', $recipient)) {
                $recipientCity = $this->getCity($recipient['City'], $recipient['Region']);
                $recipient['CityRecipient'] = $recipientCity['data'][0]['Ref'];
            }
            $recipient['CityRef'] = $recipient['CityRecipient'];
            if (! array_key_exists('RecipientAddress', $recipient)) {
                $recipientWarehouse = $this->getWarehouse($recipient['CityRecipient'], $recipient['Warehouse']);
                $recipient['RecipientAddress'] = $recipientWarehouse['data'][0]['Ref'];
            }
            if (! array_key_exists('Recipient', $recipient)) {
                $recipientCounterparty = $this->model('Counterparty')->save($recipient);
                $ref = '';
                $con_ref = '';
                if (!empty($recipientCounterparty['data'])) {
                    $ref = $recipientCounterparty['data'][0]['Ref'];
                    $con_ref = $recipientCounterparty['data'][0]['ContactPerson']['data'][0]['Ref'];
                }
                $recipient['Recipient'] = $ref;
                $recipient['ContactRecipient'] = $con_ref;
            }
        }
        

        return array_merge($sender, $recipient, $params);
    }

    public function newInternetDocument($sender, $recipient, $params, $recipientAddress = false, $afterpaymentOnGoodsCost = false)
    {
        $paramsInternetDocument = $this->allcheckInternetDocument($sender, $recipient, $params, $recipientAddress, $afterpaymentOnGoodsCost);
        return $this->model('InternetDocument')->save($paramsInternetDocument);
    }

    public function updateInternetDocument($ref, $sender, $recipient, $params, $recipientAddress = false,$afterpaymentOnGoodsCost = false)
    {
        $paramsInternetDocument = $this->allcheckInternetDocument($sender, $recipient, $params, $recipientAddress, $afterpaymentOnGoodsCost);
        $paramsInternetDocument['Ref'] = $ref;
        return $this->model('InternetDocument')->update($paramsInternetDocument);
    }

    public function deleteInternetDocument($ref)
    {
        $paramsInternetDocument = array();
        $paramsInternetDocument['DocumentRefs'] = $ref;
        return $this->model('InternetDocument')->delete($paramsInternetDocument);
    }

    /**
     * Get only link on internet document for printing
     *
     * @param string $method Called method of NovaPoshta API
     * @param array|string $documentRefs Array of Documents IDs
     * @param string $type (html_link|pdf_link)
     * @return mixed
     */
    protected function printGetLink($method, $documentRefs, $type)
    {
        $data = 'https://my.novaposhta.ua/orders/' . $method . '/orders[]/'.implode(',', $documentRefs)
                .'/type/'.str_replace('_link', '', $type)
                .'/apiKey/'.$this->key;
        // Return data in same format like NovaPoshta API
        return $this->prepare(
            array(
                'success' => true,
                'data' => array($data),
                'errors' => array(),
                'warnings' => array(),
                'info' => array(),
            )
        );
    }

    /**
     * printDocument method of InternetDocument model
     *
     * @param array|string $documentRefs Array of Documents IDs
     * @param string $type (pdf|html|html_link|pdf_link)
     * @return mixed
     */
    public function printDocument($documentRefs, $type = 'html')
    {
        $documentRefs = (array) $documentRefs;
        // If needs link
        if ($type == 'html_link' or $type == 'pdf_link') {
            return $this->printGetLink('printDocument', $documentRefs, $type);
        }
        // If needs data
        return $this->request('InternetDocument', 'printDocument', array('DocumentRefs' => $documentRefs,
            'Type' => $type));
    }

    /**
     * printMarkings method of InternetDocument model
     *
     * @param array|string $documentRefs Array of Documents IDs
     * @param string $type (pdf|new_pdf|new_html|old_html|html_link|pdf_link)
     * @return mixed
     */
    public function printMarkings($documentRefs, $type = 'new_html')
    {
        $documentRefs = (array) $documentRefs;
        // If needs link
        if ($type == 'html_link' or $type == 'pdf_link') {
            return $this->printGetLink('printMarkings', $documentRefs, $type);
        }
        // If needs data
        return $this->request('InternetDocument', 'printMarkings', array('DocumentRefs' => $documentRefs,
            'Type' => $type));
    }
}
