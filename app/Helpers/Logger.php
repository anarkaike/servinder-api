<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class Logger
{
    private bool $verbose;
    
    public function __construct(bool $verbose = FALSE)
    {
        $this->verbose = $verbose;
    }
    
    public function log(string $message): void
    {
        if ($this->verbose) {
            echo $message . PHP_EOL;  // Use echo para garantir que a mensagem seja exibida no console
        }
        Log::info($message);
    }
    
    public function error(string $message): void
    {
        if ($this->verbose) {
            echo 'Error: ' . $message . PHP_EOL;
        }
        Log::error($message);
    }
}
