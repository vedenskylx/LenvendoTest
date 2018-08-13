<?
    $this->addExternalJS("/local/components/lenvendo/draw.editor/templates/.default/jquery.jqscribble.js");
?>
<? if(isset($arResult['ID']) && $arResult['ID'] > 0){?>
    <script type="text/javascript">
        src = "<?=CFile::GetPath($arResult['DETAIL_PICTURE'])?>";
    </script>
    <input id="elementid" type="hidden" name="ELEMENT_ID" value="<?=$arResult['ID']?>"/>
<?}?>

<p>
    <label for="passw">Пароль:</label>
</p>
<input id="passw" type="password" name="passw" value=""/>
<button id="saveimg">Сохранить</button>
<div class="palet_wrapper">
    <canvas id="tablet" width = "800" height = "800"></canvas>
</div>

<div class="clear"></div>


