<?php
/**
 * Created by JetBrains PhpStorm.
 * User: madesst
 * Date: 23.12.12
 * Time: 16:59
 * To change this template use File | Settings | File Templates.
 */
namespace Madesst\RestBundle\Factory;

use Hateoas\Factory\RouteAwareFactory;
use Hateoas\Factory\Definition\RouteLinkDefinition;
use Hateoas\Link;

class ProductLinkFactory
{
	public static function retrieveFactory()
	{
		return new RouteAwareFactory(
		// Entities in collections
			array(
				'Madesst\RestBundle\Propel\Product' => array(
					new RouteLinkDefinition('madesst_rest_product_get', array('id'), Link::REL_SELF),
//				new RouteLinkDefinition('madesst_rest_product_get', array('id'), Link::REL_SELF),
//				array(
//					'route'      => 'madesst_rest_product_get',
//					'parameters' => array('id'),
////					'rel'        => 'same_category_or_same_author',
//				),
				),
			),
			// Collections
			array(
				'Madesst\RestBundle\Propel\Product' => array(
					'links' => array(
						array(
							'route'      => 'madesst_rest_product_all',
							'parameters' => array('page'),
							'rel'        => Link::REL_SELF,
						),
						array(
							'route'      => 'madesst_rest_product_all',
							'parameters' => array('page' => 'firstPage'),
							'rel'        => Link::REL_FIRST,
						),
						array(
							'route'      => 'madesst_rest_product_all',
							'parameters' => array('page' => 'lastPage'),
							'rel'        => Link::REL_LAST,
						),
						array(
							'route'      => 'madesst_rest_product_all',
							'parameters' => array('page' => 'nextPage'),
							'rel'        => Link::REL_NEXT,
						),
						array(
							'route'      => 'madesst_rest_product_all',
							'parameters' => array('page' => 'previousPage'),
							'rel'        => Link::REL_PREVIOUS,
						),
					),
					'attributes' => array(
						'page'  => 'page',
						'limit' => 'maxPerPage',
						'total' => 'nbResults',
					)
				),
			)
		);
	}
}