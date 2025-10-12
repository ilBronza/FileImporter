<?php

namespace IlBronza\FileImporter;

use IlBronza\CRUD\Providers\RouterProvider\RoutedObjectInterface;
use IlBronza\CRUD\Traits\IlBronzaPackages\IlBronzaPackagesTrait;
use IlBronza\FileImporter\Models\Fileimportationrow;

class FileImporter implements RoutedObjectInterface
{
	use IlBronzaPackagesTrait;

	static $packageConfigPrefix = 'fileimporter';

    public function manageMenuButtons()
    {
        if(! $menu = app('menu'))
            return;

        // $button = $menu->provideButton([
        //         'text' => 'menu::menu.settings',
        //         'name' => 'settings',
        //         'icon' => 'gear',
        //         'roles' => ['administrator']
        //     ]);

        // $clientsManagerButton = $menu->createButton([
        //     'name' => 'clientsManager',
        //     'icon' => 'building',
        //     'text' => 'clients::clients.list'
        // ]);

        // $clientsButton = $menu->createButton([
        //     'name' => 'clients.index',
        //     'icon' => 'list',
        //     'text' => 'clients::clients.list',
        //     'href' => IbRouter::route($this, 'clients.index')
        // ]);

        // $destinationsButton = $menu->createButton([
        //     'name' => 'destinations.index',
        //     'icon' => 'globe',
        //     'text' => 'clients::destinations.list',
        //     'href' => IbRouter::route($this, 'destinations.index')
        // ]);

        // $referentsButton = $menu->createButton([
        //     'name' => 'referents.index',
        //     'icon' => 'user-doctor',
        //     'text' => 'clients::referents.list',
        //     'href' => IbRouter::route($this, 'referents.index')
        // ]);

        // $button->addChild($clientsManagerButton);

        // $clientsManagerButton->addChild($clientsButton);
        // $clientsManagerButton->addChild($destinationsButton);
        // $clientsManagerButton->addChild($referentsButton);

    }

	static function getRowsToAnalizeCount()
	{
		return cache()->remember(
			'fileimportationrowsToAnalize',
			180,
			function()
			{
				return Fileimportationrow::notParsed()->count();
			}
		);
	}
}