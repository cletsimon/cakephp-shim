<?php

namespace TestApp\Controller;

use Shim\Controller\Controller;
use Shim\Datasource\TableAwareTrait;

class TableAwareController extends Controller {

	use TableAwareTrait;

	/**
	 * @var string|null
	 */
	protected $defaultTable = 'Cars';

	/**
	 * @return \Cake\Http\Response|null
	 */
	public function index() {
		$entity = $this->Cars->newEmptyEntity();
	}

}
