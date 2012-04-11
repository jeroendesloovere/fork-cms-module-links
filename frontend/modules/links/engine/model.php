<?php

/**
 * This is the frontend model
 *
 * @package		frontend
 * @subpackage	links
 *
 * @author		John Poelman <john.poelman@bloobz.be
 * @since		1.0.0
 */
class FrontendLinksModel
{
	/**
	 * Get all categories
	 *
	 * @return	array
	 */

	public static function getCategories()
		{
			return (array) FrontendModel::getDB()->getRecords(
			'SELECT i.*
			 FROM links_categories AS i
			 WHERE i.language = ? AND i.hidden = ?',
			 array(FRONTEND_LANGUAGE, 'N'));
		}
	
		/**
		 * Get category
		 *
		 * @return	array
		 */

		public static function getCategory($id)
			{
				return (array) FrontendModel::getDB()->getRecord(
				'SELECT i.*
				 FROM links_categories AS i
				 WHERE i.language = ? AND i.hidden = ? AND i.id = ?',
				 array(FRONTEND_LANGUAGE, 'N', $id));
			}
	
		
	/**
	 * Get all links for a category
	 *
	 * @param int
	 * @return	array
	 */

	public static function getLinksForCategory($id)
		{
			return (array) FrontendModel::getDB()->getRecords(
				'SELECT l.*
				FROM links_links AS l
				WHERE l.category_id = ? AND l.language = ? AND l.hidden = ?',
				array($id, FRONTEND_LANGUAGE, 'N'));
		}
	
	/**
	 * Get categorie by widget_id
	 *
	 * @return	array
	 */

	public static function getCategoryForWidget($id)
		{
			return (array) FrontendModel::getDB()->getRecords(
			'SELECT l.*
			FROM links_categories AS l
			WHERE l.widget_id = ? AND l.language = ? AND l.hidden = ?',
			array($id, FRONTEND_LANGUAGE, 'N'));
		}	
}