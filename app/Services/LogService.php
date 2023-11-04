<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class LogService
{
    protected $causer;
    protected string $module;

    public function __construct($causer, $module = null)
    {
        $this->causer = $causer;
        $this->module = $module;
    }

    public function write(string $level, string $message, array $context = [], array $stack = ['daily'])
    {
        Log::stack($stack)->log($level, $message, $context);
    }

    public function activity(string $level, string $message, array $context = [], array $stack = ['daily'])
    {
        $context['module'] = $this->module;
        $context['timestamp'] = now()->toDateTimeString();
        self::write($level, $message, $context, $stack);


        // do not throw an error when the log fails
        try {
            activity()
                ->causedBy($this->causer)
                ->withProperties([$context])
                ->log($message);
        } catch(\Exception $ex) {
            Log::error('[FAILED TO LOG] ' . $message);
        }
    }
}
