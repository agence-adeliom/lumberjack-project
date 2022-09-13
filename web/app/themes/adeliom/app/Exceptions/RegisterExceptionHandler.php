<?php

namespace App\Exceptions;

use Rareloop\Lumberjack\Bootstrappers\RegisterExceptionHandler as BaseRegisterExceptionHandler;

class RegisterExceptionHandler extends BaseRegisterExceptionHandler
{
    /**
     * Convert PHP errors to ErrorException instances.
     *
     * @param  int  $level
     * @param  string  $message
     * @param  string  $file
     * @param  int  $line
     * @param  array  $context
     * @return void
     *
     * @throws \ErrorException
     */
    public function handleError($level, $message, $file = '', $line = 0, $context = [])
    {
        $exception = new \ErrorException($message, 0, $level, $file, $line);

        if ($level === E_USER_NOTICE || $level === E_NOTICE || $level === E_USER_DEPRECATED || $level === E_DEPRECATED) {
            $this->getExceptionHandler()->report($exception);
            return;
        }

        if (error_reporting() & $level) {
            throw $exception;
        }
    }
}
