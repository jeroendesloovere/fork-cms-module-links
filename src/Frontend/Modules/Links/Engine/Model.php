<?php

namespace Frontend\Modules\Links\Engine;

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

/**
 * This is the frontend model
 *
 * @author John Poelman <john.poelman@bloobz.be>
 */
class FrontendLinksModel
{
	/**
	 * Get all categories
	 *
	 * @return array
	 */
	public static function getCategories()
	{
		$items = (array) FrontendModel::getContainer()->get('database')->getRecords(
				'SELECT i.*
				 FROM links_categories AS i
				 WHERE i.language = ? AND i.hidden = ? ORDER BY i.sequence ASC', 
				array(FRONTEND_LANGUAGE, 'N')
		);
		return $items;
	}

	/**
	 * Get a category
	 *
	 * @param int $id
	 * @return array
	 */
 	public static function getCategory($id)
	{
		$item = (array) FrontendModel::getContainer()->get('database')->getRecord(
				'SELECT i.*
				 FROM links_categories AS i
				 WHERE i.language = ? AND i.hidden = ? AND i.id = ?', 
				array(FRONTEND_LANGUAGE, 'N', $id)
		);
		return $item;
	}

	/**
	 * Get all links for a category
	 *
	 * @param int $id
	 * @return array
	 */
	public static function getLinksForCategory($id)
	{
		$items = (array) FrontendModel::getContainer()->get('database')->getRecords(
				'SELECT l.*
				 FROM links AS l
				 WHERE l.category_id = ? AND l.language = ? AND l.hidden = ? AND l.alive = ?', 
				array($id, FRONTEND_LANGUAGE, 'N', 'Y')
		);
		return $items;
	}
}