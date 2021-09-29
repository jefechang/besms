<?php

namespace Velostazione\BeSMS\Exceptions;

class SendError extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct("Error while sending the message.", 502);
    }
}
