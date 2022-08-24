<?global $USER;
$uid=$USER->GetID();
$rsUser = CUser::GetByID($uid);
$arUser = $rsUser->Fetch();

$discount_code=$arUser["UF_DISCOUNT"]; //Код скидки
$discount_time=$arUser["UF_DISCOUNT_TIME"]; //Время получения кода скидки
$discount_vol=$arUser["UF_DISCOUNT_VOL"]; //Значение скидки

$hours = floor((strtotime(date("d.m.Y H:i:s"))-strtotime($discount_time))/3600); // Расчитываем разницу в часах

if(isset($_GET["new"]) && $hours>0) {
		$discount_code=bin2hex(random_bytes(10)); //Код скидки
		$discount_vol=rand(1,50); //Новое значение скидки
		$discount_time=ConvertTimeStamp(time(), "FULL"); //Время получения кода скидки
		$user = new CUser;
		$fields = Array(
			"UF_DISCOUNT" => $discount_code,
			"UF_DISCOUNT_TIME" => $discount_time,
			"UF_DISCOUNT_VOL" => $discount_vol,
  		);
		$user->Update($uid, $fields);
}
?>
<style>
	.get_discount {padding:10px;border-radius:15px;}
	.discount_info {display:block;text-align:center;font-size:30px;}
	.discount_code {display:block;text-align:center;font-size:20px;color:#63aa28;}
	.discount_check label {display:block;margin-top:20px;font-weight:700;}
	.discount_check input {padding:10px;color:#555;border-radius:15px;text-align:center;}
	.discount_check button {padding:10px;border-radius:15px;}
	.discount_check_result {font-size: 30px;}
</style>

<button class="get_discount" onclick="document.location='?new'">Получить скидку</button>
<?if(isset($_GET["new"])){?>
	<span class="discount_info">Ваша скидка <?=$discount_vol?>%</span>
	<span class="discount_code">Уникальный код для получения данной скидки: <?=$discount_code?></span>
<?}?>
<form action="" method="POST" class="discount_check">
	<label for="discount_check_input">Проверка кода скидки</label>
	<input id="discount_check_input" name="discount_code" value="<?=$_POST["discount_code"]?>">
	<button onClick="$('.discount_check').submit();">Проверить скидку</button>
</form>
<?if(isset($_POST["discount_code"])){?>
	<span class="discount_check_result">
		<?
			if(($hours>2) || ($discount_code!=$_POST["discount_code"])) echo "Скидка недоступна";
			else echo "Ваша скидка ".$discount_vol."%";
		?>
	</span>
<?}?>