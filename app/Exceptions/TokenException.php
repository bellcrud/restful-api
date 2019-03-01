<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TokenException extends HttpException
{
	public function __construct()
	{
		$message = "Token is nothing or is expired";
		parent::__construct(Response::HTTP_UNAUTHORIZED,$message);
	}
}
