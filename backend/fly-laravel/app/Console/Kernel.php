<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Enregistrement des commandes Artisan pour votre application.
     */
    protected $commands = [
        // Enregistrez ici la commande personnalisée
        \App\Console\Commands\UpdateFlightAndPlaneStates::class,
    ];

    /**
     * Définition de la planification des tâches.
     */
    protected function schedule(Schedule $schedule)
    {
        // Planifier l'exécution de la commande toutes les minutes
        $schedule->command('update:flight-and-plane-states')->everyMinute();

        // Vous pouvez ajouter d'autres commandes planifiées ici
    }

    /**
     * Enregistrement des tâches artisan à exécuter via la console.
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
