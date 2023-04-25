<?php

namespace App\Http\Controllers\Admin\Charts;

use Backpack\CRUD\app\Http\Controllers\ChartController;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;

/**
 * Class ChartUsersChartController
 * @package App\Http\Controllers\Admin\Charts
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ChartUsersChartController extends ChartController
{
    public function setup()
    {
        $this->chart = new Chart();

        // MANDATORY. Set the labels for the dataset points
        $this->chart->labels([
            '2021',
            '2022',
            '2023',
        ]);

        // RECOMMENDED. Set URL that the ChartJS library should call, to get its data using AJAX.
        $this->chart->load(backpack_url('charts/chart-users'));

        // OPTIONAL
        // $this->chart->minimalist(false);
        $this->chart->displayLegend(true);
    }

    /**
     * Respond to AJAX calls with all the chart data points.
     *
     * @return json
     */
    public function data()
    {
        // $users_created_today = \App\User::whereDate('created_at', today())->count();

        $this->chart->dataset('Familles logées', 'bar', [
                    50, 300, 250,
                ])
            ->backgroundColor([
                'rgb(70, 127, 208)',
                'rgb(77, 189, 116)',
                'rgb(96, 92, 168)',
                'rgb(255, 193, 7)',
            ]);
    }
}