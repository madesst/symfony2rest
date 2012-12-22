<?php

namespace Madesst\RestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Madesst\RestBundle\MadesstRestBundle;

use Madesst\RestBundle\Propel\Product;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
		$product = new Product();
		$product->setDescription('Testing timestampable');
		$product->setName('Sample product');
		$product->setPrice(0.1);
		$product->save();
//		$em = $this->getDoctrine()->getEntityManager();
//
//		$repository = $em->getRepository('MadesstRestBundle:Product');
//		// create some posts in case if there aren't any
//		if (!$repository->findAll()) {
//			$product = new \Madesst\RestBundle\Entity\Product();
//			$product->setDescription('Testing timestampable');
//			$product->setName('Sample product');
//			$product->setPrice(0.1);
//			$em->persist($product);
//			$em->flush();
//		}
//		$posts = $em
//				->createQuery('SELECT p FROM MadesstRestBundle:Product p')
//				->getArrayResult()
//		;
//		die(var_dump($posts));
        return $this->render('MadesstRestBundle:Default:index.html.twig', array('name' => $name));
    }
}
