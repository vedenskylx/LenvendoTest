<?php
/**
 * Created by PhpStorm.
 * User: lexgorbachev
 * Date: 10.08.2018
 * Time: 16:49
 *
 * Компонент редактора
 */

use Bitrix\Main;
use Bitrix\Main\Localization\Loc;


class DrawEdit extends \CBitrixComponent
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
		$request = Main\Application::getInstance()->getContext()->getRequest();

		$isAjaxRequest = $request->getRequestMethod() == 'POST';

		if ($isAjaxRequest) {
            header('Content-type: application/json');
			$this->processAJAX($request);
            die();
		} elseif(isset($this->arParams['ELEMENT_ID']) && $this->arParams['ELEMENT_ID'] > 0) {

			$this->arResult = $this->getElement(['ID' => $this->arParams['ELEMENT_ID']]);
		}

        $this->includeComponentTemplate();
	}

    /**
     * Обработка POST-запросов
     *
     * @param $request
     * @return bool
     */
	protected function processAJAX($request)
	{
        $elementID = (int)$this->arParams['ELEMENT_ID'];
		if($elementID > 0)
		{
            $element = $this->getElement(['ID' => $elementID]);
			$passw = $request->get('passw');

			if(!$this->checkPassword($element['CODE'], $passw))
			{
				$this->ajaxResponse(['MESSAGE' => Loc::getMessage('BAD_PASSW')]);
				return false;
			}

			$fileID = $this->storeFile();
			$this->update($request, $fileID, $elementID);
			$this->ajaxResponse(['MESSAGE' => Loc::getMessage('SUCCESS')]);
        } else {

			$fileID = $this->storeFile();
			$elementID = $this->save($request, $fileID);
			$this->ajaxResponse(['MESSAGE' => Loc::getMessage('SUCCESS'), 'ELEMENT_ID' => $elementID]);
		}
	}

    /**
     * Получение элемента для вывода/редактирования
     *
     * @param $filter
     * @return mixed
     */
	protected function getElement($filter)
	{
		return CIBlockElement::GetList(["SORT" => "DESC"], $filter, false, ["nTopCount" => 1],[])->Fetch();
	}

	protected function save($request, $fileID)
	{
		if (!$this->arParams['IBLOCK_ID']) 
		{
			showError(Loc::getMessage('NOT_SET_IBLOCK_ID'));
			return false;
		}

		$oCIBlockElement = new CIBlockElement();

		$result = $oCIBlockElement->add([

			'IBLOCK_ID' 	 => $this->arParams['IBLOCK_ID'],
			'NAME'      	 => md5($request->get('passw')),
			'CODE'			 => md5($request->get('passw')),
			"DETAIL_PICTURE" =>  $fileID

		]);

		if (!$result) 
		{
		    return $oCIBlockElement->LAST_ERROR;
			showError($oCIBlockElement->LAST_ERROR);
			return false;
		}

		return $result;
	}

    /**
     * Обновление данных существующего элемента
     *
     * @param $request
     * @param $fileID
     * @param $elementID
     * @return bool
     */
	protected function update($request, $fileID, $elementID)
	{
		if (!$this->arParams['IBLOCK_ID']) 
		{
			showError(Loc::getMessage('NOT_SET_IBLOCK_ID'));
			return false;
		}

		$oCIBlockElement = new CIBlockElement();

		$result = $oCIBlockElement->update(

			$elementID,
			[
				'IBLOCK_ID' 	 => $this->arParams['IBLOCK_ID'],
				"DETAIL_PICTURE" =>  $fileID
			]);

		if (!$result) 
		{
			showError($oCIBlockElement->LAST_ERROR);
			return false;
		}

		return $result;
	}

    /**
     * Сохранение файла рисунка
     *
     * @return bool
     */
	protected function storeFile()
	{
		if (!$this->arParams['TMP_FOLDER'])
		{
			showError(Loc::getMessage('NOT_SET_TMP_FOLDER'));
			return false;
		}

		$request = Main\Application::getInstance()->getContext()->getRequest();
		
		$img = $request->get('canvasFile');
		$img = str_replace('data:image/png;base64,', '', $img);
		$img = str_replace(' ', '+', $img);
		
		$fileData = base64_decode($img);
		$fileName = md5("file") . '.png';
		$tmpFilePath = $_SERVER["DOCUMENT_ROOT"] . $this->arParams['TMP_FOLDER'] . $fileName;

		if(!file_put_contents($tmpFilePath, $fileData))
		{
			showError(Loc::getMessage('ERROR_STORE_FILE'));
			return false;
		}

		return \CFile::MakeFileArray($tmpFilePath);
	}

    /**
     * Возвращаем Json-ответ
     * @param $result
     */
	protected function ajaxResponse($result)
	{
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        echo \Bitrix\Main\Web\Json::encode($result);
	}

    /**
     * Проверяем пароль при редактировании
     *
     * @param $storedHash
     * @param $passw
     * @return bool
     */
	private function checkPassword($storedHash, $passw)
	{
		if(!$storedHash || !$passw) return false;

		$passwHash = md5($passw);

		return $storedHash === $passwHash;
	}

}
