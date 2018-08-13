<?php

$result = [];

//ресйзим и кладем в кеш
foreach ($arResult["ELEMENTS"] as $element) {

	$file = CFile::ResizeImageGet(
							$element['DETAIL_PICTURE'],
							[
								'width' =>  150,
								'height' => 150
							],
							BX_RESIZE_IMAGE_PROPORTIONAL,
							true
	);

	$element['DETAIL_PICTURE_SRC'] = $file['src'];

	$result[] = $element;
}

$arResult["ELEMENTS"] = $result;