<?php

declare(strict_types=1);

namespace Kalimero\Casys\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controller;
use Kalimero\Casys\Http\Requests\HandlePaymentRequest;
use Kalimero\Casys\Service\Casys;
use stdClass;

class CasysController extends Controller
{
    protected Casys $casys;

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
     * Route entry point: validate the posted buyer details and render the payment form.
     */
    public function pay(HandlePaymentRequest $request): View|Factory|Application
    {
        /** @var array{name: string, last_name: string, country: string, email: string, amount: numeric-string|int|float} $validated */
        $validated = $request->validated();

        $client = new stdClass();
        $client->name = (string) $validated['name'];
        $client->last_name = (string) $validated['last_name'];
        $client->country = (string) $validated['country'];
        $client->email = (string) $validated['email'];

        return $this->getCasys($client, (float) $validated['amount']);
    }

    /**
     * Build the Casys payload and render the payment form.
     *
     * Kept callable from application code; the route uses {@see CasysController::pay()}.
     *
     * @param stdClass $client An object containing client data (name, last_name, country, email).
     * @param float $amount The amount to be paid.
     */
    public function getCasys(stdClass $client, float $amount): View|Factory|Application
    {
        $casysData = $this->casys->getCasysData($client, $amount);
        /** @var view-string $viewName */
        $viewName = 'casys::index';

        return view($viewName, ['casys' => $casysData]);
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
