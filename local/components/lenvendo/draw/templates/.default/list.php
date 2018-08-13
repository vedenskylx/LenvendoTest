<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?php $APPLICATION->IncludeComponent(
	'lenvendo:draw.list',
	'',
	[
		'IBLOCK_ID'		=> $arParams["IBLOCK_ID"],		
		'SORT'			=> ["created" => "DESC"],		
	]
);?>