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
     */
    public function __construct(Casys $casys)
    {
        $this->casys = $casys;
    }

    /**
     * Display the payment loader view.
     */
    public function index(): View|Factory|Application
    {
        /** @var view-string $viewName */
        $viewName = 'casys::loader';
        return view($viewName);
    }

    /**
     * Generate and display the payment data.
     *
     * @param stdClass $client An object containing client data (name, last_name, country, email).
     * @param float $amount The amount to be paid.
     */
    public function getCasys(stdClass $client, float $amount): View|Factory|Application
    {
        $casysData = $this->casys->getCasysData($client, $amount);
        /** @var view-string $viewName */
        $viewName = 'casys::index';
        return view($viewName, ['casysData' => $casysData]);
    }

    /**
     * Handle successful payment.
     */
    public function success(): View|Factory|Application
    {
        /** @var view-string $viewName */
        $viewName = 'casys::okurl';
        return view($viewName)->with('success', 'Your transaction was successful');
    }

    /**
     * Handle failed payment.
     */
    public function fail(): View|Factory|Application
    {
        /** @var view-string $viewName */
        $viewName = 'casys::failurl';
        return view($viewName)->with('error', 'Your transaction failed');
    }
}
