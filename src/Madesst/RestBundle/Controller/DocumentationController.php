<?php
/**
 * Created by JetBrains PhpStorm.
 * User: madesst
 * Date: 06.01.13
 * Time: 17:32
 * To change this template use File | Settings | File Templates.
 */
namespace Madesst\RestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DocumentationController extends Controller
{
	public function __construct($container = null){
		$this->container = $container;
	}

	public function indexAction($name = 'all methods')
	{
		return $this->render('MadesstRestBundle:Default:index.html.twig', array('name' => $name));
	}

	public function methodAction($method)
	{
		return $this->render('MadesstRestBundle:Default:index.html.twig', array('name' => $method.' method'));
	}
}
