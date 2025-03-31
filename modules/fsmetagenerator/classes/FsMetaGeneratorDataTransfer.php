<?php
/**
 * Copyright 2023 ModuleFactory
 *
 * @author    ModuleFactory
 * @copyright ModuleFactory all rights reserved.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * https://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
class FsMetaGeneratorDataTransfer
{
    private static $data;
    private static $readed_from_file = false;
    private static $module_name = 'fsmetagenerator';
    private static $data_file = 'data.json';

    public static function setData($var)
    {
        $data_file = _PS_MODULE_DIR_ . self::$module_name . '/' . self::$data_file;
        $file = fopen($data_file, 'w');
        fwrite($file, json_encode($var));
        fclose($file);
    }

    public static function getData()
    {
        $data_file = _PS_MODULE_DIR_ . self::$module_name . '/' . self::$data_file;
        if (!self::$readed_from_file) {
            if (file_exists($data_file)) {
                self::$data = json_decode(Tools::file_get_contents($data_file), true);
                unlink($data_file);
            }

            self::$readed_from_file = true;
        }

        return self::$data;
    }
}
