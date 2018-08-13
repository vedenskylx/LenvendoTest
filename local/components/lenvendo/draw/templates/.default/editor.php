<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?php

$params = [
    'AJAX_MODE' 	=> 'Y',
    'IBLOCK_ID'		=> $arParams["IBLOCK_ID"],
    'FILD_NAME'		=> $arParams["FILD_NAME"],
    'TMP_FOLDER'	=> $arParams["TMP_FOLDER"],
    'FOLDER'		=> $arParams["FOLDER"],
];

if((int)$arResult["VARIABLES"]["ELEMENT_ID"]>0){
    $params["ELEMENT_ID"] = $arResult["VARIABLES"]["ELEMENT_ID"];
}

$APPLICATION->IncludeComponent(
	'lenvendo:draw.editor',
	'',
    $params
);

?>