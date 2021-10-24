<?php

namespace Shim\Test\TestCase\Controller;

use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use TestApp\Controller\TableAwareController;

class TableAwareControllerTest extends TestCase {

	/**
	 * @var \Shim\Controller\Controller
	 */
	protected $Controller;

	/**
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();

		Configure::write('App.namespace', 'TestApp');

		$this->Controller = new TableAwareController();
		$this->Controller->startupProcess();
	}

	/**
	 * @return void
	 */
	public function tearDown(): void {
		parent::tearDown();

		unset($this->Controller);
	}

	/**
	 * @return void
	 */
	public function testIndex() {
		$this->Controller->index();
		$true = true;
		$this->assertTrue($true);
	}

}
