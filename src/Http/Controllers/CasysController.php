<?php

namespace App\Http\Controllers;

use App\Traits\Casys;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;


class CasysController extends Controller
{
    use Casys;

    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('casys.loader');
    }

    /**
     * @param $client
     * @param $amount
     * @return Application|Factory|View
     */
    protected function getCasys($client, $amount)
    {
        return view('casys::index')->with(
            [
                'casys' => $this->getCasysData($client, $amount)
            ]
        );
    }

    /** paymentOKURL
     * Store a newly created resource in storage.
     *
     * @return Application|Factory|View
     */
    public function success()
    {
        return view('casys::okurl')->with('success', 'You transaction was successful');
    }

    /** paymentFailURL
     * Remove the specified resource from storage.
     * @return Application|Factory|View
     */
    public function fail()
    {
        return view('casys::failurl')->with('success', 'You transaction was successful');
    }

}
