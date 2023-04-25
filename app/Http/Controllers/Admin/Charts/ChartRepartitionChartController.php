<?php

namespace App\Http\Controllers\Admin\Charts;

use Backpack\CRUD\app\Http\Controllers\ChartController;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;

/**
 * Class ChartRepartitionChartController
 * @package App\Http\Controllers\Admin\Charts
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ChartRepartitionChartController extends ChartController
{
    public function setup()
    {
        $this->chart = new Chart();

        // MANDATORY. Set the labels for the dataset points
        $this->chart->labels([
            '1 enfant',
            '2 enfants',
            '3 enfants',
            '4 enfants',
            '5 enfants et +',
        ]);

        // RECOMMENDED. Set URL that the ChartJS library should call, to get its data using AJAX.
        $this->chart->load(backpack_url('charts/chart-repartition'));

        // OPTIONAL
        // $this->chart->minimalist(false);
        // $this->chart->displayLegend(true);
    }

    /**
     * Respond to AJAX calls with all the chart data points.
     *
     * @return json
     */
    public function data()
    {

        $this->chart->dataset('Répartition enfants', 'pie', [150, 200, 120, 50, 20, 10])
            ->color('rgba(205, 32, 31, 1)')
            ->backgroundColor([
                'rgb(70, 127, 208)',
                'rgb(77, 189, 116)',
                'rgb(96, 92, 168)',
                'rgb(255, 193, 7)',
                'rgb(205, 32, 31)'
            ]);
    }
}