<?php

use App\Models\Role;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Equipment;
use App\Models\Inventory;
use App\Models\Subcategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

if (! function_exists('isAdmin')) {
    /**
     * Check if user is admin
     *
     * @param  User $user
     * @return boolean
     */
    function isAdmin() {
        return backpack_user() != null && backpack_user()->hasAnyRole(['Admin', 'Superadmin']);
    }
}

if (! function_exists('isManager')) {
    function isManager() {
        return backpack_user() != null ? backpack_user()->hasAnyRole(['Superadmin', 'Customer admin', 'Company manager']) : false;
    }
}

if (! function_exists('canManageCompany')) {
    function canManageCompany() {
        return backpack_user() != null ? backpack_user()->hasAnyRole(['Superadmin', 'Customer admin', 'Company manager', 'WF Partner'])  : false;
    }
}

if (! function_exists('canManageEntity')) {
    function canManageEntity() {
        return backpack_user() != null ? canManageCompany() || backpack_user()->hasAnyRole(['Entity Manager']) : false;
    }
}
if (! function_exists('userRoles')) {
    function userRoles() {
        return backpack_user() != null ? backpack_user()->roles()->get() : false;
    }
}

if (! function_exists('isCarbonOperator')) {
    function isCarbonOperator() {
        return backpack_user() != null ?  backpack_user()->hasRole('Carbon operator') : false;
    }
}

if (! function_exists('userCanSeeDb')) {
    function userCanSeeDb() {
        return backpack_user() != null ?  backpack_user()->hasAnyRole(['Superadmin', 'Database admin', 'Carbon operator']) : false;
    }
}

if (! function_exists('isDbAdmin')) {
    function isDbAdmin() {
        return backpack_user() != null ?  backpack_user()->hasAnyRole(['Database admin']) : false;
    }
}
if (! function_exists('can_impersonate')) {
    function can_impersonate() {
        return backpack_user()->canImpersonate();
    }
}

if (! function_exists('isPartner')) {
    function isPartner() {
        return backpack_user() != null ?  backpack_user()->hasRole('WF Partner') : false;
    }
}

if (! function_exists('isMedialab')) {
    function isMedialab() {
        return backpack_user() != null ?  backpack_user()->is_medialab : false;
    }
}

if (! function_exists('parse')) {
    function parse($val) {
        $to_repl = array('&' => '-',' ' => '');
        return strtolower(strtr($val, $to_repl));
    }
}

if (! function_exists('getCustomUnits')) {
    function getCustomUnits() {
        $user = backpack_user();
        return [
            'surface_suffix' => $user->metric_system == "metric" ? 'm2' : 'sq ft',
            'currency_symbol' => $user->currency->symbol,
            'volume_suffix' => $user->metric_system == "metric" ? 'm3' : 'cu ft',
            'length_suffix' => $user->metric_system == "metric" ? 'm' : 'ft',
            'distance_suffix' => $user->metric_system == "metric" ? 'km' : 'mi',
            'temperature_suffix' => $user->metric_system == "metric" ? '°C' : '°F',
            'weight_suffix' => $user->metric_system == "metric" ? 'Kg' : 'Lbs',
        ];
    }
}

/**
 * Computes the equipments footprint
 *
 * @param $model
 * @param Country $country
 * @param int $open_days
 * @param string $type
 * @return array $res Array footprints
 */

function calculateEquipmentsFootprint($model, $country, $open_days, $type='activity') {
    $footprint = 0;
    $conf = Config::get('capi_data');

    // TODO : if reuse => lifespan x 2
    $ademe_weight_emission_factor = $conf['ademe_weight_emission_factor'];
    $res = [];
    $res['daily_energy'] = 0;
    $res['manufacturing_daily_carbon_impact'] = 0;
    $equipments = $type == 'activity' ? $model->bundle->equipments : $model->equipments;
    foreach($equipments as $eq) {
        $values = $eq->pivot;
        $res['daily_energy'] += $values['quantity'] * (($values['idle_time'] * $eq->nominalPC_idle + $values['operating_time'] * $eq->nominalPC_operating) * $eq->software_factor / 100);
        try {
            if ($eq->weight == 0) {
                $eq->weight = 1; //!! TODO: remove
            }
            $res['manufacturing_daily_carbon_impact'] += ($values['quantity'] * $eq->weight * $ademe_weight_emission_factor) / $values['lifespan'] / $open_days;
        } catch (Exception $e) {
            dd($eq->lifespan);
            dd($open_days);
        }
    }
    return $res;
}

/**
 * Computes the custom items footprint
 *
 * @param $model
 * @param Country $country
 * @param int $open_days
 * @param string $type
 * @return array $res Array footprints
 */

function calculateCustomItemsFootprint($model, $country, $open_days, $type='activity') {
    $footprint = 0;
    $conf = Config::get('capi_data');

    // TODO : if reuse => lifespan x 2
    $ademe_weight_emission_factor = $conf['ademe_weight_emission_factor'];
    $res = [];
    $res['daily_energy'] = 0;
    $res['manufacturing_daily_carbon_impact'] = 0;
    $equipments = $type == 'activity' ? $model->bundle->customitems : $model->customitems;
    // dd($equipments);
    foreach($equipments as $eq) {

        $values = $eq->pivot;
        $res['daily_energy'] += $values['quantity'] * (($values['idle_time'] * $eq->nominalPC_idle + $values['operating_time'] * $eq->nominalPC_operating));
        try {
            if ($eq->weight == 0) {
                $eq->weight = 1; //!! TODO: remove
            }
            $res['manufacturing_daily_carbon_impact'] += ($values['quantity'] * $eq->weight * $ademe_weight_emission_factor) / $values['lifespan'] / $open_days;
        } catch (Exception $e) {
            dd($eq->lifespan);
            dd($open_days);
        }
    }
    return $res;
}

/**
 * Computes the heatings + coolings footprint
 *
 * @param $model
 * @return array $res Array footprints
 */
function calculateHeatingCoolingFootprint($model) {
    $heating_footprint = 0;
    $cooling_footprint = 0;

    $volume = $model->surface * $model->avg_height;
    $GV = $volume * $model->G;
    $address = $model->address == null ? $model->building->address : $model->address;
    $department = substr(json_decode($address)->postcode, 0, 2);
    $country = \App\Models\Country::where('country_name', 'like', "%" . json_decode($address)->country . "%")->first();
    $working_days = $country != null ? $country->working_days : 251;
    $dpt_infos = DB::table('weather_data')->where('department', $department)->first(); //!! TODO : Worldwide
    $dju = $dpt_infos->dju;

    $avg_temperature = $dpt_infos->avg_temperature;

    $heatings = [];
    $coolings = [];
    foreach ($model->heatcoolings as $hcSystem) {
        if ($hcSystem->heatcoolingCategory_id == 1) {
            $heatings[] = ["system" => $hcSystem, "nominalPC" => $hcSystem->pivot->nominalPC, "default_temp" => $hcSystem->pivot->default_temp];
        } else {
            $coolings[] = ["system" => $hcSystem, "nominalPC" => $hcSystem->pivot->nominalPC, "default_temp" => $hcSystem->pivot->default_temp];
        }
    }

    foreach ($heatings as $heating) {
        if (backpack_user()->temperature == 'F') {
            $heating["default_temp"] = ($heating["default_temp"] - 32) * 5 / 9;
        }
        $dj = (($heating["default_temp"] - 2) * 365) - ($avg_temperature * 365);
        $kwh_PCI = $GV * $dj * 0.024; //!! TODO always 24/7 ?
        $heating_footprint += number_format(($kwh_PCI * $heating['system']->footprint) / $working_days, 2);
    }

    foreach ($coolings as $cooling) { //!! TODO leakage + coolings specs
        if (backpack_user()->temperature == 'F') {
            $cooling["default_temp"] = ($cooling["default_temp"] - 32) * 5 / 9;
        }
        $dj = (($cooling["default_temp"] - 2) * 365) - ($avg_temperature * 365);
        $kwh_PCI = $GV * $dj * 0.024; //!! TODO always 24/7 ?
        $cooling_footprint += number_format(($kwh_PCI * $cooling['system']->footprint) / $working_days, 2);
    }
    return ['heating_footprint' => $heating_footprint, 'cooling_footprint' => $cooling_footprint];
}
/**
 * Manages multi tenant display by setting global scope
 *
 * @param $model
 * @param $entityClass
 * @return null
 */
function manageModelDisplay($model, $entityClass) {
    if (Auth::check() && !isAdmin() && strpos(url()->current(), '/template') != false) {
        if (isPartner()) {
            $ids = Auth::user()->companies->pluck('id')->toArray();
            $entities = $entityClass::whereIn('company_id', $ids)->pluck('id')->toArray();
            $model->addGlobalScope('entity_id', function ($builder) use ($entities) {
                $builder->whereIn('entity_id', $entities);
            });
        } else {
            if (Auth::user()->entity_id != null) { //user is an entity manager
                $id = Auth::user()->entity_id;
                $model->addGlobalScope('entity_id', function ($builder) use ($id) {
                    $builder->where('entity_id', $id);//WhereNull('entity_id');
                });
            } else {
                $id = Auth::user()->company_id;
                $model->addGlobalScope('company_id', function ($builder) use ($id) {
                    $builder->where('company_id', $id);//WhereNull('entity_id');
                });
            }
        }
    }
    if (Auth::check() && !isAdmin() && strpos(url()->current(), '/template') == false) {
        if (isPartner()) {
            $ids = Auth::user()->companies->pluck('id')->toArray();
            $entities = $entityClass::whereIn('company_id', $ids)->pluck('id')->toArray();
            $model->addGlobalScope('entity_id', function ($builder) use ($entities) {
                $builder->whereIn('entity_id', $entities);
            });
        } else {
            if (Auth::user()->entity_id != null) { //user is an entity manager
                $id = Auth::user()->entity_id;
                $model->addGlobalScope('entity_id', function ($builder) use ($id) {
                    $builder->where('entity_id', $id);//WhereNull('entity_id');
                });
            } else {
                $id = Auth::user()->company_id;
                $model->addGlobalScope('company_id', function ($builder) use ($id) {
                    $builder->where('company_id', $id);//WhereNull('entity_id');
                });
            }
        }
    }
}
/**
 * Manages the upload and storage of user's inventory or bundle
 *
 * @param File $spreadsheets
 * @param $model
 * @return array $equipments
 */
function uploadEquipmentList($spreadsheets, $model, $type='inventory') {
    $used_equipments = DB::table($type.'_equipment')->where($type.'_id', $model->id)->pluck('equipment_id')->toArray();
    // dump($used_equipments);
    $equipments = [];
    for ($i = 0; $i < $spreadsheets->getSheetCount(); $i++) {
        if ($type == 'bundle') {
            $cat_name = strtolower(str_replace(' & ', '-', $spreadsheets->getSheet($i)->getTitle()));
        }
        $spreadsheet = $spreadsheets->getSheet($i)->toArray();
        unset($spreadsheet[0]);
        $corr = [];
        $time_start = microtime(true);
        foreach($spreadsheet as $key => $row) {
            if(!empty($row[0])) {
                $tmp = new \stdClass();
                $tmp->entry = $row;
                $equipment = Equipment::where('validated', 1)->where( 'name', 'rlike', str_replace(' ', '|',$row[0]));
                $matches = [];
                foreach($equipment->pluck('name') as $k => $eq) {
                    $match = similar_text($eq, $row[0], $percent);
                    $time_end = microtime(true);
                    $matches[$k] = [
                        'id' => $k,
                        'percentage' => round($percent, 2)
                        ];
                }
                $rsort = usort($matches, function($a, $b) {
                    return $b['percentage'] <=> $a['percentage'];
                });
                if (count($matches) == 0) {
                    $tmp->closest = "No matches found";
                    $tmp->percentage = 0;
                }
                else {
                    $tmp->closest = $equipment->get()[$matches[0]['id']];
                    $tmp->percentage = $matches[0]['percentage'];
                }
                $corr[$key] = $tmp;
            }
        }
        foreach($corr as $item) {
            $cat = $type == 'bundle' ? Category::where('slug', 'like', '%'.$cat_name.'%')->first() : Category::where('description', 'like', '%'.$item->entry[2].'%')->first();
            $subcat = Subcategory::where('description', 'like', '%'.$item->entry[3].'%')->first();
            $brand = Brand::where('name', 'like', '%'.$item->entry[8].'%')->first();

            if ($brand == null) { $brand = Brand::find(101);}
            if ($item->percentage != 100) {
                $to_add = $type == 'inventory' ? Equipment::where('name', 'like', '%'.$item->entry[0].'%')->where('category_id', $cat->id)->where('subcategory_id', $subcat->id)->first() : Equipment::where('name', 'like', '%'.$item->entry[0].'%')->where('category_id', $cat->id)->first();
                if ($to_add == null) {
                    $eq = new Equipment([
                        'name' => $item->entry[0],
                        'url' => $type == 'bundle' ? "" : $item->entry[1],
                        'category_id' => $cat->id,
                        'weight' => $type == 'bundle' ? 0 : $item->entry[5],
                        'nominalPC_operating' => $type == 'bundle' ? 0 : $item->entry[6],
                        'nominalPC_idle' => $type == 'bundle' ? 0 : $item->entry[7],
                        'brand_id' => $brand->id,
                        'closest' => (is_string($item->closest) || $item->closest == null) ? null : $item->closest->id
                    ]);
                    if ($type != 'bundle') {
                        $eq->subcategory_id = $subcat->id;
                    } else {
                        $eq->subcategory_id = null; //Subcategory::where('description', 'like', '%N/A%')->where('category_id', $cat->id)->first()->id;
                    }
                    $eq->save();
                    if ($type != 'bundle') {
                        $model->equipments()->attach($eq->id, [
                            'percentage' => $item->percentage,
                            'closest' => is_string($item->closest) ? $item->closest : $item->closest->id
                        ]);
                    } else {
                        $model->equipments()->attach($eq->id, [
                            'percentage' => $item->percentage,
                            'closest' => is_string($item->closest) ? $item->closest : $item->closest->id,
                            'quantity' => $item->entry[1],
                            'lifespan' => $item->entry[2],
                            'operating_time' => $item->entry[3],
                            'idle_time' => $item->entry[4],
                        ]);
                    }
                }
                else {
                    if ($type == 'bundle') {
                        $equipments[$cat->slug][] = [
                            'equipments-'.$cat->slug => $to_add->id,
                            'quantity' => $item->entry[1],
                            'lifespan' => $item->entry[2],
                            'operating_time' => $item->entry[3],
                            'idle_time' => $item->entry[4]
                        ];
                    }
                }
            }
            else {
                if(!in_array($item->closest->id, $used_equipments)) {
                    $eq = Equipment::find($item->closest->id);
                    $pivot = $type != 'bundle' ? ['is_validated' => 1] : [
                        'is_validated' => 1,
                        'quantity' => $item->entry[1],
                        'lifespan' => $item->entry[2],
                        'operating_time' => $item->entry[3],
                        'idle_time' => $item->entry[4]
                        ];
                    $model->equipments()->attach($eq->id, $pivot);

                }
                $equipments[$cat->slug][] = [
                    'equipments-'.$cat->slug => $item->closest->id,
                    'quantity' => $item->entry[1],
                    'lifespan' => $item->entry[2],
                    'operating_time' => $item->entry[3],
                    'idle_time' => $item->entry[4]
                ];
            }
        }
    }
    return $type == 'bundle' ? $equipments : null;
}
?>
