<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Validation\ValidationInterface;

class BaseModel extends Model {
	public function __construct(ValidationInterface $validation = null)
	{
		parent::__construct($validation);
	}
}
