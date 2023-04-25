@extends(backpack_view('blank'))

@php

if (backpack_user() != null) {

        $widgets['before_content'][] = [
            'type' => 'div',
            'class' => 'row',
            'content' => [
                // widgets
                [
                'type'          => 'progress_white',
                'class'         => 'card mb-2',
                'value'         => '110',
                'description'   => 'Familles logées',
                'progress'      => 550, // integer
                'progressClass' => 'progress-bar bg-success',
                'hint'          => '9 avant le prochain objectif.',
                ],
                [
                'type'          => 'progress_white',
                'class'         => 'card mb-2',
                'value'         => '1337',
                'description'   => 'Baux générés',
                'progress'      => 100, // integer
                'progressClass' => 'progress-bar bg-light',
                'hint'          => '&nbsp;',
                ],
                [
                'type'          => 'progress_white',
                'class'         => 'card mb-2',
                'value'         => '1 457 841',
                'description'   => 'Loyer généré (€)',
                'progress'      => 100, // integer
                'progressClass' => 'progress-bar bg-primary',
                'hint'          => '&nbsp;',
                ],
                [
                'type'          => 'progress_white',
                'class'         => 'card mb-2',
                'value'         => '1',
                'description'   => 'Loyer(s) en retard',
                'progress'      => 100, // integer
                'progressClass' => 'progress-bar bg-danger',
                'hint'          => '&nbsp;',
                ],
                [
                    'type' => 'chart',
                    // 'class' => 'col-md-6',
                    'controller' => \App\Http\Controllers\Admin\Charts\ChartUsersChartController::class,
                    'content' => [
                        'header' => 'Familles logées', // optional
                    ],
                ],
                [
                    'type' => 'chart',
                    // 'class' => 'col-md-6',
                    'controller' => \App\Http\Controllers\Admin\Charts\ChartCAChartController::class,
                    'content' => [
                        'header' => 'Evolution CA', // optional
                    ],
                ],
                [
                    'type' => 'chart',
                    // 'class' => 'col-md-6',
                    'controller' => \App\Http\Controllers\Admin\Charts\ChartRepartitionChartController::class,
                    'content' => [
                        'header' => 'Répartition des familles', // optional
                    ],
                ],
            ],

        ];

}
@endphp
@section('content')
@endsection
