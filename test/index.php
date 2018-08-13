<? require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');

$APPLICATION->IncludeComponent(
    'lenvendo:draw',
    '',
    [
        'IBLOCK_ID'		=> 12,
        'TMP_FOLDER'	=> '/upl/',
        "SEF_URL_TEMPLATES" => array(
            "list" => "test/",
            "editor" => "test/#ACTION#/#ELEMENT_ID#/",
        )
    ]
);

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');
?>