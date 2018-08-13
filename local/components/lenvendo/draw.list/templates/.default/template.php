<div class="col-md-12">
	<a href="/test/editor/create/">Создать новый рисунок</a>

	<ul>
		<?php foreach ($arResult['ELEMENTS'] as $element):?>
			<li>
					<a href="/test/editor/<?=$element['ID']?>/">
						<img src="<?=$element['DETAIL_PICTURE_SRC']?>" alt="<?=$element['NAME']?>" class="img-responsive">
						<p><?=$element['NAME']?></p>
					</a>
			</li>
		<?php endforeach;?>
	</ul>
</div>
