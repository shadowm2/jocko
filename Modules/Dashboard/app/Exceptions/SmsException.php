<?php

namespace Modules\Dashboard\Exceptions;

use RuntimeException;

/**
 * The SMS could not be sent: no API key, Kavenegar rejected the request
 * (getCode() is their status code), or the network failed.
 */
class SmsException extends RuntimeException {}
