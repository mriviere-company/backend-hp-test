<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;

readonly class HomeController
{
    public function index(): View|Factory|Application
    {
        $routes = collect(Route::getRoutes())->map(function ($route) {
            return [
                'method' => implode(', ', $route->methods()), // GET, POST, etc.
                'uri' => $route->uri(),
                'name' => $route->getName(), // Nom de la route
                'action' => $route->getActionName(), // Contrôleur@méthode ou callable
            ];
        });

        return view('home', compact('routes'));
    }
}
