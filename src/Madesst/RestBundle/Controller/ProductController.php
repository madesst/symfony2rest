<?php
/**
 * Created by JetBrains PhpStorm.
 * User: madesst
 * Date: 22.12.12
 * Time: 17:20
 * To change this template use File | Settings | File Templates.
 */
namespace Madesst\RestBundle\Controller;

use Madesst\RestBundle\Propel\Product;
use Madesst\RestBundle\Propel\ProductQuery;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductController
{
	/**
	 * @Rest\View
	 */
	public function AllAction()
	{
		$products = ProductQuery::create()->find()->getData();

		return array('products' => $products);
	}

	/**
	 * @Rest\View
	 */
	public function getAction($id)
	{
		$product = ProductQuery::create()->findPk($id);

		if(!$product instanceof Product) {
			throw new NotFoundHttpException('Product not found');
		}

		return array('product' => $product);
	}
}