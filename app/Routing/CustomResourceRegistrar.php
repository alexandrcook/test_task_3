<?php

namespace App\Routing;

use Illuminate\Routing\ResourceRegistrar as OriginalRegistrar;

class CustomResourceRegistrar extends OriginalRegistrar
{
    // add data to the array
    /**
     * The default actions for a resourceful controller.
     *
     * @var array
     */
    protected $resourceDefaults = ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy', 'delete', 'restore'];


    /**
     * Add the data method for a resourceful route.
     *
     * @param  string  $name
     * @param  string  $base
     * @param  string  $controller
     * @param  array   $options
     * @return \Illuminate\Routing\Route
     */
    protected function addResourceData($name, $base, $controller, $options)
    {
        $uri = $this->getResourceUri($name).'/data.json';

        $action = $this->getResourceAction($name, $controller, 'data', $options);

        return $this->router->post($uri, $action);
    }

    protected function addResourceDelete($name, $base, $controller, $options)
    {
        $uri = $this->getResourceUri($name).'/{'.substr($name, 0, -1).'}/delete';

        return $this->router->post($uri, $this->getResourceAction($name, $controller, 'delete', $options));
    }

    protected function addResourceRestore($name, $base, $controller, $options)
    {
        $uri = $this->getResourceUri($name).'/{'.substr($name, 0, -1).'}/restore';

        return $this->router->post($uri, $this->getResourceAction($name, $controller, 'restore', $options));
    }
}
