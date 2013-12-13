<?php

/**
 * This file is part of the NasExt extensions of Nette Framework
 *
 * Copyright (c) 2013 Dusan Hudak (http://dusan-hudak.com)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace NasExt\Controls;

use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use Nette\Http\Request;
use Nette\Http\Response;

/**
 * @author Dusan Hudak <admin@dusan-hudak.com>
 */
class ItemsPerPage extends Control
{
	const MASK_PREFIX = 'ipp-';

	/** @persistent */
	public $value;

	/** @var  bool */
	public $ajaxRequest;

	/** @var  bool */
	public $useSubmit;

	/** @var string */
	public $inputLabel;

	/** @var string */
	public $submitLabel;

	/** @var array */
	public $onChange;

	/** @ array*/
	private $perPageData;

	/** @ int*/
	private $defaultValue;

	/** @var string */
	private $cookieMask;

	/** @var  string */
	private $templateFile;

	/** @var  Request */
	private $httpRequest;

	/** @var  Response */
	private $httpResponse;


	/**
	 * @param Request $httpRequest
	 * @param Response $httpResponse
	 */
	public function __construct(Request $httpRequest, Response $httpResponse)
	{
		parent::__construct();
		$this->httpRequest = $httpRequest;
		$this->httpResponse = $httpResponse;
	}


	/**
	 * This method will be called when the component (or component's parent)
	 * becomes attached to a monitored object. Do not call this method yourself.
	 * @param  Nette\ComponentModel\IComponent
	 * @return void
	 */
	protected function attached($presenter)
	{
		if ($presenter instanceof Presenter) {
			$this->cookieMask = self::MASK_PREFIX . $this->presenter->name . ":" . $this->name;
		}
		parent::attached($presenter);
	}


	/**
	 * @param bool $value
	 * @return ItemsPerPage provides fluent interface
	 */
	public function setAjaxRequest($value = TRUE)
	{
		$this->ajaxRequest = $value;
		return $this;
	}


	/**
	 * @param string $file
	 * @return ItemsPerPage provides fluent interface
	 */
	public function setTemplateFile($file)
	{
		$this->templateFile = $file;
		return $this;
	}


	/**
	 * @param array $perPageData
	 * @return ItemsPerPage provides fluent interface
	 */
	public function setPerPageData($perPageData = NULL)
	{
		if ($perPageData != NULL) {
			$this->perPageData = $perPageData;
		}
		return $this;
	}


	/**
	 * @param int $defaultValue
	 * @return ItemsPerPage provides fluent interface
	 */
	public function setDefaultValue($defaultValue)
	{
		$this->defaultValue = $defaultValue;
		return $this;
	}


	/**
	 * This method return array list of items per page
	 * @return array
	 */
	public function getPerPageData()
	{
		$perPageData = array();
		foreach ($this->perPageData as $value) {
			$perPageData[$value] = $value;
		}
		$perPageData[$this->defaultValue] = $this->defaultValue;
		ksort($perPageData);

		return $perPageData;
	}


	/**
	 * @return int
	 */
	public function getValue()
	{
		if (in_array($this->value, $this->getPerPageData())) {
			return $this->value;
		}

		$value = (int)$this->httpRequest->getCookie($this->cookieMask);
		if (in_array($value, $this->getPerPageData())) {
			return $value;
		} else {
			return $this->defaultValue;
		}
	}


	/**
	 * @return Form
	 */
	protected function createComponentForm()
	{
		$form = new Form();
		$elementPrototype = $form->getElementPrototype();

		$elementPrototype->class[] = lcfirst($this->reflection->getShortName());
		!$this->ajaxRequest ? : $elementPrototype->class[] = 'ajax';

		$form->addSelect('itemsPerPage', $this->inputLabel, $this->getPerPageData())
			->setDefaultValue($this->getValue());

		if ($this->useSubmit) {
			$form->addSubmit('change', $this->submitLabel);
		} else {
			$form['itemsPerPage']->setAttribute('data-items-per-page');
		}

		$form->onSuccess[] = callback($this, 'processSubmit');

		return $form;
	}


	/**
	 * PROCESS-SUBMIT-FORM - save item pre page to cookie storage
	 * @param Form $form
	 */
	public function processSubmit(Form $form)
	{
		$values = $form->getValues();

		if ($values->itemsPerPage) {
			$value = $values->itemsPerPage;
		} else {
			$value = $this->defaultValue;
		}
		$this->value= $value;

		$this->httpResponse->setCookie($this->cookieMask, $value, 0);
		$this->onChange($this, $this->getValue());

		if (!$this->presenter->isAjax()) {
			$this->presenter->redirect('this');
		}
	}


	/**
	 * @return string
	 */
	public function getTemplateFile()
	{
		if ($this->templateFile) {
			return $this->templateFile;
		}

		$reflection = $this->getReflection();
		$dir = dirname($reflection->getFileName());
		$name = $reflection->getShortName();
		return $dir . DIRECTORY_SEPARATOR . $name . '.latte';
	}


	/**
	 * RENDER
	 */
	public function render()
	{
		$template = $this->template;
		$template->_form = $template->form = $this->getComponent('form');
		$template->setFile($this->getTemplateFile());
		$template->render();
	}
}

