<?php

class AdminUpdateProductController extends AdminController
{
    public function __construct()
    {
        $this->bootstrap = true; // Вмикаємо Bootstrap-стиль у формі
        parent::__construct();
    }

    public function initContent()
    {
        parent::initContent();

        // HTML-форма для введення артикула і кнопки
        $html = '
        <div class="panel">
            <h3>'.$this->l('Оновлення товару').'</h3>
            <form method="post">
                <label>'.$this->l('Артикул товару').'</label>
                <input type="text" name="update_product_reference" required />
                <button type="submit" name="submitUpdateProductDate" class="btn btn-primary">
                    '.$this->l('Оновити дату створення').'
                </button>
            </form>
        </div>';

        // Передаємо наш HTML у шаблон
        $this->context->smarty->assign('content', $html);
    }

    public function postProcess()
    {
        parent::postProcess();

        // Якщо натиснута кнопка
        if (Tools::isSubmit('submitUpdateProductDate')) {
            $reference = Tools::getValue('update_product_reference');
            if ($reference) {
                // Знаходимо товар за артикулом
                $idProduct = Db::getInstance()->getValue('
                    SELECT id_product
                    FROM '._DB_PREFIX_.'product
                    WHERE reference = "'.pSQL($reference).'"
                ');

                if ($idProduct) {
                    // Оновлюємо date_add на поточну дату
                    $now = date('Y-m-d H:i:s');
                    Db::getInstance()->update('product', [
                        'date_add' => pSQL($now)
                    ], 'id_product='.(int)$idProduct);

                    $this->confirmations[] = $this->l('Дата створення товару успішно оновлена!');
                } else {
                    $this->errors[] = $this->l('Товар із таким артикулом не знайдено!');
                }
            }
        }
    }
}