<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
//   $file = \Illuminate\Support\Facades\Storage::disk('public')->get('diseases.json');
//   $formatted_array = [];
//   $array = json_decode($file, true);
//   foreach ($array as $value) {
//       if (array_key_exists('l', $value) && array_key_exists('n1', $value) && array_key_exists('n2', $value) && array_key_exists('label', $value)) {
//           $name = $value['l'] . $value['n1'] . $value['n2'];
//           $formatted_array[$name] = $value['label'];
//       }
//   }
//
//   $json = json_encode($formatted_array, true);
//   \App\Models\CallSetting::updateOrCreate(['slug' => \App\Models\CallSetting::DIAGNOSES_SLUG, 'name' => 'Діагнози(МКХ)'], ['value' => $json]);
//   dd(1);
//    $objective_data = [
//        'загальний стан' => null,
//        'температура (в градусах Цельсія)' => null,
//        'свідомість (ясна/приглушена/сопор/кома)' => null,
//        'шкіра' => null,
//        'артеріальний тиск (мм.рт.ст.)' => null,
//        'пульс' => null,
//        'тони серця (ритмічні або аритмічні)' => null,
//        'частота дихання (уд/хв)' => null,
//        'задишка (експіраторна/інспіраторна/змішана)' => null,
//        'периферичний набряк (наявність/відсутність)' => null,
//        'дихання' => null,
//        'зіниці (норма/міоз/мідріаз/анізокорія)' => null,
//        'реакція на світло (наявність/відсутність)' => null,
//        'ністагм (наявність/відсутність)' => null,
//        'обличчя (симетричне/асиметричне)' => null,
//        'прикушення язика (наявність/відсутність)' => null,
//        'тонус м’язів (D = S)' => null,
//        'менінгеальні ознаки (наявність/відсутність/сумнівні)' => null,
//        'плегії, параліч (наявність/відсутність)' => null,
//        'місце ушкодження при травмі (1-15)' => null,
//        'додаткова інформація про кардіологічні випадки' => null
//
//    ];

    $medicaid = [
        'артеріальний тиск (мм.рт.ст.)' => null,
        'пульс (уд/хв)' => null,
        'ЧД' => null,
        'за ШГ (балів)' => null,
        'за ТШ (балів)' => null,
        'транспортування пацієнта (пішки/на ношах/на руках)' => null
    ];

    $objective_data = json_encode($medicaid, true);
    \App\Models\CallSetting::updateOrCreate(['slug' => \App\Models\CallSetting::STATE_AFTER_RELIEF, 'name' => 'стан після надання допомоги'], ['value' => $objective_data]);
    dd(1);

});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});
