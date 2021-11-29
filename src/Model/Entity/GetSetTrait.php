<?php

namespace Shim\Model\Entity;

use Cake\Utility\Inflector;
use RuntimeException;

/**
 * Trait to write entity properties in a way that the passed value is ensured.
 *
 * - set{PropertyName}OrFail() must have a non-null value or throws exception otherwise
 */
trait GetSetTrait {

	use GetTrait;
	use SetTrait;

	/**
	 * @param string $name
	 * @param array $arguments
	 * @throws \RuntimeException
	 * @return mixed
	 */
	public function __call(string $name, array $arguments) {
		if (!preg_match('/^(set|get)([A-Z][A-Za-z0-9]+)OrFail$/', $name, $matches)) {
			throw new RuntimeException('Method ' . $name . ' cannot be found; set{PropertyName}OrFail() expected.');
		}
		if ($matches[1] === 'set' && !$arguments) {
			throw new RuntimeException('Method ' . $name . ' param for value not found, but expected.');
		}

		$property = Inflector::underscore($matches[2]);

		if ($matches[1] === 'get') {
			return $this->getOrFail($property);
		}

		$this->setOrFail($property, $arguments[1]);
	}

}
