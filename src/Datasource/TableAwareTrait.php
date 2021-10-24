<?php

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         3.0.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace Shim\Datasource;

use Cake\Datasource\Exception\MissingModelException;
use Cake\Datasource\FactoryLocator;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Locator\LocatorInterface;
use Cake\Datasource\RepositoryInterface;
use UnexpectedValueException;

/**
 * Provides functionality for loading table classes
 * and other repositories onto properties of the host object.
 *
 * Example users of this trait are Cake\Controller\Controller and
 * Cake\Console\Shell.
 *
 * Shim to continue allowing this to be used within 4.x and possibly 5.x.
 */
trait TableAwareTrait
{
	use LocatorAwareTrait;

    /**
     * Loads and constructs repository objects required by this object
     *
     * Typically, used to load ORM Table objects as required. Can
     * also be used to load other types of repository objects your application uses.
     *
     * If a repository provider does not return an object a MissingModelException will
     * be thrown.
     *
     * @param string|null $tableClass Name of model class to load. Defaults to $this->defaultTable / $this->modelClass.
     *  The name can be an alias like `'Post'` or FQCN like `App\Model\Table\PostsTable::class`.
     * @return \Cake\Datasource\RepositoryInterface The model instance created.
     * @throws \Cake\Datasource\Exception\MissingModelException If the model class cannot be found.
     * @throws \UnexpectedValueException If $modelClass argument is not provided
     *   and ModelAwareTrait::$modelClass property value is empty.
     */
    public function loadTable(?string $tableClass = null): RepositoryInterface
    {
        $tableClass = $tableClass ?? ($this->defaultTable ?? $this->modelClass);
        if (empty($tableClass)) {
            throw new UnexpectedValueException('defaultTable/modelClass is empty');
        }

		$options = [];
        if (strpos($tableClass, '\\') === false) {
            [, $alias] = pluginSplit($tableClass, true);
        } else {
            $options['className'] = $tableClass;
            /** @psalm-suppress PossiblyFalseOperand */
            $alias = substr(
                $tableClass,
                strrpos($tableClass, '\\') + 1,
                -strlen($this->_modelType)
            );
            $tableClass = $alias;
        }

        if (isset($this->{$alias})) {
            return $this->{$alias};
        }

        $factory = FactoryLocator::get($this->_modelType);
        if ($factory instanceof LocatorInterface) {
            $this->{$alias} = $factory->get($tableClass, $options);
        } else {
            $this->{$alias} = $factory($tableClass, $options);
        }

        if (!$this->{$alias}) {
            throw new MissingModelException([$tableClass, $this->_modelType]);
        }

        return $this->{$alias};
    }

}
