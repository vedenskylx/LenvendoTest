<?php
/**
 * Created by PhpStorm.
 * User: lexgorbachev
 * Date: 10.08.2018
 * Time: 19:21
 *
 * Компонент для вывода списка рисунков.
 *
 */

use Bitrix\Main;
use Bitrix\Main\Localization\Loc;

class GetElements extends \CBitrixComponent
{
    /**
     *  Подключаем языковые файлы
     */

    public function onIncludeComponentLang()
    {
        $this -> includeComponentLang(basename(__FILE__));
        Loc::loadMessages(__FILE__);
    }

    public function executeComponent()
    {
        \Bitrix\Main\Loader::includeModule('iblock');

        $this->arResult["ELEMENTS"] = $this->getList($this->arParams["SORT"], ["IBLOCK_ID" => $this->arParams["IBLOCK_ID"]], $this->arParams["COUNT"]);

        $this->includeComponentTemplate();

        return $this->arResult;
    }


    /**
     * Получить список существующих рисунков.
     *
     * @param array $sort
     * @param $filter
     * @param bool $count
     * @return array
     */

    private function getList($sort = ["SORT" => "ASC"], $filter, $count = false)
    {
        $NavStartParams = false;

        if($count) $NavStartParams = ["nTopCount" => $count];
        $CDBResult = CIBlockElement::GetList($sort, $filter, false, $NavStartParams, []);

        while ($temp = $CDBResult->GetNextElement()) {
        
            $result[] = array_merge($temp->GetFields(), ['PROPERTIES' => $temp->GetProperties()]); 
        }

        return $result;
    }
}
