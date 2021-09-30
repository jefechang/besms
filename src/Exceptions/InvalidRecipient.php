<?php

namespace Velostazione\BeSMS\Exceptions;

class InvalidRecipient extends \RuntimeException
{
    public function __construct(mixed $recipient = '')
    {
        parent::__construct("The provided number '$recipient' is invalid.", 422);
    }
}
