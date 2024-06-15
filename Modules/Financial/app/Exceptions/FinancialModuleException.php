<?php

namespace Modules\Financial\app\Exceptions;

use App\Exceptions\BaseException;
use Illuminate\Support\Facades\Log;
use Modules\AccessControl\app\Exceptions\Exception;

class FinancialModuleException extends BaseException
{
    public function __construct($message = '', $code = 0, Exception $previous = NULL)
    {
        parent::__construct($message, $code, $previous);
    }
    
    // Construtor personalizado para permissão negada
    public static function permissionDenied($detail = '')
    {
        $message = 'Permission denied.' . ($detail ? ' ' . $detail : '');
        return new static($message, 403);
    }
    
    // Construtor personalizado para não autenticado
    public static function notAuthenticated()
    {
        return new static('User not authenticated.', 401);
    }
    
    // Sobrescrever o método report para customizar o log
    public function report()
    {
        Log::error($this->message, ['exception' => $this]);
    }
}
