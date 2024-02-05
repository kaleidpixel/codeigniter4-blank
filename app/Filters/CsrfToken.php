<?php
/**
 * @see https://stackoverflow.com/questions/76603376/codeigniter4-code-to-add-employee-redirects-to-itself-on-submit-when-regenerate
 */
namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class CsrfToken implements FilterInterface
{
	public function before(RequestInterface $request, $arguments = null)
	{
	}

	public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
	{
		$method           = strtoupper($request->getMethod());
		$methodsProtected = ["POST", "PUT", "DELETE", "PATCH"];

		if (in_array($method, $methodsProtected, true)) {
			$response->setHeader(csrf_header(), csrf_hash());
		}
	}
}
