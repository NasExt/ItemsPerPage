<?php

/**
 * This file is part of the NasExt extensions of Nette Framework
 *
 * Copyright (c) 2013 Dusan Hudak (http://dusan-hudak.com)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace NasExt\Controls\DI;

use Nette\DI\Compiler;
use Nette\DI\CompilerExtension;

/**
 * ItemsPerPageExtension
 *
 * @author Dusan Hudak
 */
class ItemsPerPageExtension extends CompilerExtension
{
	/** @var array */
	private $perPageData = array(2, 5, 10, 20, 30, 50, 100);


	/** @var array */
	public $defaults = array(
		'perPageData' => FALSE,
		'defaultValue' => 10,
		'ajaxRequest' => FALSE,
		'useSubmit' => TRUE,
		'inputLabel' => 'Items per page',
		'submitLabel' => 'Ok',
	);


	public function loadConfiguration()
	{
		$config = $this->getConfig($this->defaults);
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('itemsPerPage'))
			->setImplement('\NasExt\Controls\IItemsPerPageFactory')
			->addSetup('setAjaxRequest', array($config['ajaxRequest']))
			->addSetup('setPerPageData', array($config['perPageData'] ? $config['perPageData'] : $this->perPageData))
			->addSetup('$useSubmit', array($config['useSubmit']))
			->addSetup('$inputLabel', array($config['inputLabel']))
			->addSetup('$submitLabel', array($config['submitLabel']))
			->addSetup('setDefaultValue', array($config['defaultValue']));
	}
}
