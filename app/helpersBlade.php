<?php

use App\Models\Cards;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;


if (! function_exists('getMoney')) {

	function getMoney($value,$moeda = null){
		if($value === null){
			return '0,00';
		}
		if($moeda !== null){
			return $moeda . " " . number_format($value,2,',','.');
		}else{
			return @number_format($value,2,',','.');
		}
		
	}
}
if (! function_exists('saveMoney')) {
	function saveMoney($value){
		
		if($value === null){
			return 0.00;
		}
		$money = str_replace(".", "", $value);
		$money = str_replace(",", ".", $money);
		return $money;
	}
}
if (! function_exists('progressColor')) {
	function progressColor($value){
		if($value < 15){
			return 'bg-danger';
		}
		if($value >= 15 && $value <= 30){
			return 'bg-warning';
		}

		if($value > 30 && $value <= 70){
			return 'bg-primary';
		}

		if($value > 70 ){
			return 'bg-success';
		}
		
	}
}

if (! function_exists('slug')) {
	function slug($value){
		 $slug = Str::slug($value, '-');
        return $slug;
		
	}
}

if (! function_exists('status')) {
	function status($value){
		if($value){
		$array = [
			'active'=>
				[
					'title'=>'Ativo',
					'color'=> 'badge-success',
				],
			'inactive'=>
				[
					'title'=>'Inativo',
					'color'=> "badge-warning",
				],
			'removed'=>
				[
					'title'=>'Removido',
					'color'=> "badge-danger"
				]
			];
		return '<span class="badge '.$array[$value]['color'].'">'.$array[$value]['title'].'</span>';
		;
		}
	}
}
?>