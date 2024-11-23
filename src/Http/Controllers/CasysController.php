<?php

namespace Kalimero\Casys\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controller;
use Kalimero\Casys\Http\Service\Casys;


class CasysController extends Controller
{

    private Casys $casys;

    public function __construct()
    {
        $this->casys = new Casys();
    }

    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('casys::loader');
    }

    /**
     * @param $client
     * @param $amount
     * @return Application|Factory|View
     */
    protected function getCasys($client, $amount)
    {
        $casys = $this->getCasysData($client, $amount);
        return view('casys::index', compact('casys'));
    }

    /** paymentOKURL
     *
     * @return Application|Factory|View
     */
    public function success()
    {
        return view('casys::okurl')->with('success', 'You transaction was successful');
    }

    /** paymentFailURL
     * @return Application|Factory|View
     */
    public function fail()
    {
        return view('casys::failurl')->with('success', 'You transaction was successful');
    }

    protected function getCasysData($client, $amount): array
    {
        return $this->casys->getCasysData($client, $amount);
    }

}
