<?php

namespace Core;

abstract class Middleware
{
	/**
	 * Handle the request and either pass to the next middleware or stop execution.
	 *
	 * @param callable $next The next middleware or handler to call.
	 * @return mixed
	 */
	abstract public function handle(callable $next);

	/**
	 * Helper method to stop execution and send an unauthorized error response.
	 */
	protected function error()
	{
		(new \Core\Response())
			->setStatusCode(401)
			->json(['error' => 'Unauthorized'])
			->send();
		exit;
	}
}
