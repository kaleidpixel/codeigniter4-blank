<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class TrimFilter implements FilterInterface
{
	public function before(RequestInterface $request, $arguments = null)
	{
		$trimmed_post = [];

		foreach ($request->getPost() as $key => $val) {
			if (is_string($val)) {
				$trimmed_post[$key] = trim($val);
			} else {
				array_walk_recursive($val, function (&$v) {
					$v = trim($v);
				});

				$trimmed_post[$key] = $val;
			}
		}

		$request->setGlobal('post', $trimmed_post);
	}

	public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
	{
		//
	}
}
