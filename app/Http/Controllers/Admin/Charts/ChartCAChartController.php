<?php

namespace App\Http\Controllers\Admin\Charts;

use Backpack\CRUD\app\Http\Controllers\ChartController;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;

/**
 * Class ChartCAChartController
 * @package App\Http\Controllers\Admin\Charts
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ChartCAChartController extends ChartController
{
    public function setup()
    {
        $this->chart = new Chart();

        // MANDATORY. Set the labels for the dataset points
        $this->chart->labels([
            '01-2022',
            '02-2022',
            '03-2022',
            '04-2022',
            '05-2022',
            '06-2022',
            '07-2022',
            '08-2022',
            '09-2022',
            '10-2022',
            '11-2022',
            '12-2022',
            '01-2023',
            '02-2023',
            '03-2023',
            '04-2023'

        ]);

        // RECOMMENDED. Set URL that the ChartJS library should call, to get its data using AJAX.
        $this->chart->load(backpack_url('charts/chart-c-a'));

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
    //     $users_created_today = \App\User::whereDate('created_at', today())->count();
        // $data = ;
    $this->chart->dataset('CA', 'line',
    [120050, 126216, 129931, 331312, 530468, 627421, 572271, 695118, 906061, 995198, 1082651, 1169000, 1257322, 1457841]  )
            ->backgroundColor('rgba(70, 127, 208, 0.4)');
    }
}