NasExt/ItemsPerPage
===========================

ItemsPerPage for Nette Framework.

Requirements
------------

NasExt/ItemsPerPage requires PHP 5.3.2 or higher.

- [Nette Framework 2.0.x](https://github.com/nette/nette)

Installation
------------

The best way to install NasExt/ItemsPerPage is using  [Composer](http://getcomposer.org/):

```sh
$ composer require nasext/items-per-page:@dev
```

Enable the extension using your neon config.

```yml
extensions:
	nasext.itemsPerPage: NasExt\Controls\DI\ItemsPerPageExtension
```

Configuration
```yml

nasext.itemsPerPage:
	perPageData: [2, 5, 10, 20, 30, 50, 100],
	defaultValue: 10,
	ajaxRequest: FALSE,
	useSubmit: TRUE,
	inputLabel: 'Items per page',
	submitLabel: 'Ok'
```

- perPageData (default data for choose)
- defaultValue: (If the default value is not in the perPageData list to be added, to the perPageData list.)
- ajaxRequest: (use with ajax)
- useSubmit: (use submit button for send data process)
- inputLabel: (label text for select input)
- submitLabel: (label text for submit button)

Include from client-side:
- itemsPerPage.css
- itemsPerPage.js


## Usage
Inject NasExt\Controls\IItemsPerPageFactory in to presenter

````php
FooPresenter extends Presenter{

	/** @var  NasExt\Controls\IItemsPerPageFactory */
	private $itemsPerPageFactory;

	/**
	 * INJECT ItemsPerPageFactory
	 * @param NasExt\Controls\IItemsPerPageFactory $itemsPerPageFactory
	 */
	public function injectItemsPerPageFactory(NasExt\Controls\IItemsPerPageFactory $itemsPerPageFactory)
	{
		$this->itemsPerPageFactory = $itemsPerPageFactory;
	}

	/**
	 * RENDER - Default
	 */
	public function renderDefault()
	{
		// Get control ItemsPerPage
		/** @var ItemsPerPage $itemsPerPage */
		$itemsPerPage = $this['itemsPerPage'];

		// Use with Pagination
		/** @var VisualPaginator $vp */
		$vp = $this['vp'];
		$paginator = $vp->getPaginator();
		$paginator->itemsPerPage = $itemsPerPage->getIpp();
		...
	}

	/**
	 * CONTROL - ItemsPerPage
	 * @return NasExt\Controls\ItemsPerPage
	 */
	protected function createComponentItemsPerPage()
	{
		$control = $this->itemsPerPageFactory->create();
		return $control;
	}

}
```
It is very important in obtaining Ipp values ​​used getIp () method, because only this ensures get valid data.


###ItemsPerPage with ajax
For use ItemsPerPage with ajax use setAjaxRequest() and event onChange[] for invalidateControl
```php
	/**
	 * CONTROL - ItemsPerPage
	 * @return NasExt\Controls\ItemsPerPage
	 */
	protected function createComponentItemsPerPage()
	{
		$control = $this->itemsPerPageFactory->create();

		$control->setAjaxRequest(TRUE);

		$that = $this;
		$control->onChange[] = function ($component, $ipp) use ($that) {
			if($that->isAjax()){
				$that->invalidateControl();
			}
		};

		return $control;
	}
```
###Set templateFile for ItemsPerPage
For set templateFile use setTemplateFile()
```php
	/**
	 * CONTROL - ItemsPerPage
	 * @return NasExt\Controls\ItemsPerPage
	 */
	protected function createComponentItemsPerPage()
	{
		$control = $this->itemsPerPageFactory->create();
		$control->setTemplateFile('myTemplate.latte')
		return $control;
	}
```


###Custom options
```php
	/**
	 * CONTROL - ItemsPerPage
	 * @return NasExt\Controls\ItemsPerPage
	 */
	protected function createComponentItemsPerPage()
	{
		$control = $this->itemsPerPageFactory->create();

		$control->useSubmit = FALSE;
		$control->inputLabel = 'Items per page';
		$control->submitLabel = 'Ok';
		$control->setPerPageData(array(10, 20, 30, 40));
		$control->setDefaultValue(8);

		return $control;
	}
```














-----

Repository [http://github.com/nasext/itemsperpage](http://github.com/nasext/itemsperpage).
