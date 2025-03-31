<?php

class AdminUpdateProductController extends AdminController
{
    public function __construct()
    {
        // Увімкнути bootstrap-стилі в адмінці
        $this->bootstrap = true;
        parent::__construct();
    }

    public function initContent()
    {
        parent::initContent();

        // HTML‑форма: поле для артикула і кнопка
        $html = '
        <div class="panel">
            <h3>'.$this->l('Оновлення товару').'</h3>
            <form method="post">
                <label>'.$this->l('Артикул товару').'</label>
                <input type="text" name="update_product_reference" required />
                <button type="submit" name="submitUpdateProductDate" class="btn btn-primary">
                    '.$this->l('Оновити дату створення')
                .'</button>
            </form>
        </div>';

        // Прив’язуємо HTML до змінної 'content' і відобразимо
        $this->context->smarty->assign('content', $html);
    }

    public function postProcess()
    {
        // Спочатку викликаємо базову логіку Prestashop
        parent::postProcess();
    
        // Перевіряємо, чи була натиснута наша кнопка
        if (Tools::isSubmit('submitUpdateProductDate')) {
            // Отримуємо введений артикул
            $reference = Tools::getValue('update_product_reference');
            if ($reference) {
                // Знаходимо товар у таблиці 'product'
                $idProduct = Db::getInstance()->getValue('
                    SELECT id_product 
                    FROM '._DB_PREFIX_.'product
                    WHERE reference = "'.pSQL($reference).'"
                ');
    
                if ($idProduct) {
                    // Оновлюємо поля date_add і date_upd на поточну дату
                    $now = date('Y-m-d H:i:s');
                    Db::getInstance()->update(
                        'product',
                        [
                            'date_add' => pSQL($now),
                            'date_upd' => pSQL($now),
                        ],
                        'id_product='.(int)$idProduct
                    );
    
                    // Виводимо повідомлення про успіх
                    $this->confirmations[] = $this->l('Дата створення та дата оновлення змінені на сьогоднішню!');
                } else {
                    // Якщо товар не знайдено за таким артикулом
                    $this->errors[] = $this->l('Товар із таким артикулом не знайдено!');
                }
            }
        }
    }
}