<?php

namespace Kalimero\Casys\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controller;
use Kalimero\Casys\Service\Casys;
use stdClass;

class CasysController extends Controller
{
    protected Casys $casys;

    /**
     * Inject the Casys service.
     *
     * @param Casys $casys
     */
    public function __construct(Casys $casys)
    {
        $this->casys = $casys;
    }

    /**
     * Display the payment loader view.
     *
     * @return Application|Factory|View
     */
    public function index(): View|Factory|Application
    {
        return view('casys::loader');
    }

    /**
     * Generate and display the payment data.
     *
     * @param stdClass $client An object containing client data (name, last_name, country, email).
     * @param float $amount The amount to be paid.
     * @return Application|Factory|View
     */
    public function getCasys(stdClass $client, float $amount): View|Factory|Application
    {
        $casysData = $this->casys->getCasysData($client, $amount);
        return view('casys::index', compact('casysData'));
    }

    /**
     * Handle successful payment.
     *
     * @return Application|Factory|View
     */
    public function success(): View|Factory|Application
    {
        return view('casys::okurl')->with('success', 'Your transaction was successful');
    }

    /**
     * Handle failed payment.
     *
     * @return Application|Factory|View
     */
    public function fail(): View|Factory|Application
    {
        return view('casys::failurl')->with('error', 'Your transaction failed');
    }
}
