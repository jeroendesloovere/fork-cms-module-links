<?php

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

/**
 * In this file we store all generic functions that we will be using in the links module
 *
 * @author John Poelman <john.poelman@bloobz.be>
 */

class BackendLinksModel
{
	
 /**
 * Define constants
 */	

	const QRY_DATAGRID_CAT =
		 'SELECT i.*
		  FROM links_categories AS i
		  WHERE i.language = ?';
			
	const QRY_DATAGRID_LINKS =
	 	 'SELECT i.*
		  FROM links_links AS i
		  WHERE i.language = ?';
			
	const QRY_DATAGRID_BROWSE =
		 'SELECT i.*
		  FROM links_links AS i
		  WHERE i.language = ? AND i.category_id = ?
		  ORDER BY i.id DESC';

	 /**
	 * Add a new link.
	 *
	 * @param array $item
	 * @return int $id
	 */
	public static function addLink(array $item)
	{
		$id = BackendModel::getDB(true)->insert('links_links', (array) $item);
		return (int) $id;
	}

	/**
	 * Convert the title to a widgetlabel
	 * 
	 * @param string $catname
	 * @return string $label
	 */
	public static function createWidgetLabel(string $catname)
	{
		// convert the item to camelcase
		$label 	= preg_replace('/\s+/', '_', $catname);
		$label	= SpoonFilter::toCamelCase($label);
		return (string) $label;
	}

	/**
	 * Is the deletion of a category allowed?
	 *
	 * @param int $id
	 * @return bool
	 */
	public static function deleteCategoryAllowed($id)
	{
		return (bool) (BackendModel::getDB()->getVar(
		'SELECT COUNT(id)
		 FROM links_links AS i
		 WHERE i.category_id = ? AND i.language = ?',
		 array((int) $id, BL::getWorkingLanguage())) == 0);
	}

	 /**
	 * Delete a category
	 *
	 * @param int $id		The id of the category to be deleted.
	 * @return bool
	 */
	public static function deleteCategoryById($id)
	{
		// delete the record
		return (bool) BackendModel::getDB(true)->delete('links_categories', 'id = ?', array((int) $id));
	}

	 /**
	 * Delete id's
	 *
	 * @param int $id The id of the link to be deleted.
	 * @return bool
	 */

	public static function deleteIdsByCatId($id)
	{
		// delete the record
		return (bool) BackendModel::getDB(true)->delete('links_extra_ids', 'category_id = ?', array((int) $id));
	}

	/**
	 * Is the deletion of a link allowed?
	 *
	 * @param int $id
	 * @return bool
	 */
	public static function deleteLinkAllowed($id)
	{
		return (bool) (BackendModel::getDB()->getVar(
		'SELECT COUNT(id)
		 FROM links_links AS i
		 WHERE i.category_id = ? AND i.language = ?',
		 array((int) $id, BL::getWorkingLanguage())) == 0);
	}

	 /**
	 * Delete a link
	 *
	 * @param int $id The id of the link to be deleted.
	 * @return bool
	 */
	public static function deleteLinkById($id)
	{
		// delete the link
		return (bool) BackendModel::getDB(true)->delete('links_links', 'id = ?', array((int) $id));
	}

	 /**
	 * Delete a widget
	 *
	 * @param int $id The id of the widget to be deleted.
	 * @return bool
	 */
	public static function deleteWidgetById($id)
	{
		// delete the widget
		return (bool) BackendModel::getDB(true)->delete('modules_extras', 'id = ?', array((int) $id));
	}

	 /**
	 * Does the category exist?
	 *
	 * @param int $id
	 * @return bool
	 */
	public static function existsCategory($id)
	{
		return (bool) BackendModel::getDB()->getVar(
		'SELECT COUNT(i.id)
		 FROM links_categories AS i
		 WHERE i.id = ? AND i.language = ?',
		 array((int) $id, BL::getWorkingLanguage()));
	}

	 /**
	 * Does the link exist?
	 *
	 * @param int $id
	 * @return bool
	 */
	public static function existsLink($id)
	{
		return (bool) BackendModel::getDB()->getVar(
		'SELECT COUNT(i.id)
		 FROM links_links AS i
		 WHERE i.id = ? AND i.language = ?',
		 array((int) $id, BL::getWorkingLanguage()));
	}

	 /**
	 * Get all categories
	 *
	 * @return array
	 */		
	public static function getCategories()
	{
		return (array) BackendModel::getDB()->getRecords(
		'SELECT i.*
		 FROM links_categories AS i
		 WHERE i.language = ? AND i.hidden = ?',
		 array(BL::getWorkingLanguage(), 'N'));
	}

	 /**
	 * Get all category names for dropdown
	 *
	 * @return array
	 */
	public static function getCategoriesForDropdown()
	{
		return (array) BackendModel::getDB()->getPairs(
		'SELECT i.id, i.title
		FROM links_categories AS i
		WHERE i.language = ?
		ORDER BY i.id ASC',
		array(BL::getWorkingLanguage()));
	}

	 /**
	 * Get category by id
	 *
	 * @param int $id
	 * @return array
	 */
	public static function getCategoryFromId($id)
	{
		return (array) BackendModel::getDB()->getRecord(
		'SELECT i.*
		 FROM links_categories AS i
		 WHERE i.language = ? AND i.id = ?',
		 array(BL::getWorkingLanguage(),(int) $id));
	}

	 /**
	 * Get categoryname by id
	 *
	 * @param int $id
	 * @return string
	 */
	public static function getCatNameFromId($id)
	{
		return (string) BackendModel::getDB()->getRecord(
		'SELECT i.title
		 FROM links_categories AS i
		 WHERE i.language = ? AND i.id = ?',
		 array(BL::getWorkingLanguage(),(int) $id));
	}

	 /**
	 * Get extra ids for this category
	 * 
	 * @param int $id
	 * @return array
	 */
	public static function getExtraIdsForCategory($id)
	{
		return (array) BackendModel::getDB()->getRecord(
		'SELECT i.* 
		FROM links_extra_ids AS i
		WHERE i.category_id = ?',
		array((int) $id));
	}

	 /**
	 * Fetch a link
	 *
	 * @param int $id
	 * @return array
	 */
	public static function getLinkById($id)
	{
		return (array) BackendModel::getDB()->getRecord(
		'SELECT i.*
		 FROM links_links AS i
		 WHERE i.id = ? AND i.language = ?',
		 array((int) $id, BL::getWorkingLanguage()));
	}

	 /**
	 * Get all links
	 *
	 * @return array
	 */
	public static function getLinks()
	{
		return (array) BackendModel::getDB()->getRecords(
		'SELECT i.*
		 FROM links_links AS i
		 WHERE i.language = ? AND hidden = ?',
		 array(BL::getWorkingLanguage(), 'N'));
	}

	 /**
	 * Add a new category.
	 *
	 * @param array $item The data to insert.
	 * @return int
	 */
	public static function insertCategory(array $item)
	{
		// insert the category
		return (int) $item['id'] = BackendModel::getDB(true)->insert('links_categories', $item);
	}

	 /**
	 * Save the widget
	 *
	 * @param array $widget
	 * @return int The id
	 */
	public static function insertWidget(array $widget)
	{
		$db = BackendModel::getDB(true);
		
		// get widget sequence
		$widget['sequence'] =  $db->getVar('SELECT MAX(i.sequence) + 1 FROM modules_extras AS i WHERE i.module = ?',
		array($widget['module']));
		
		if(is_null($widget['sequence']))
		{
			$widget['sequence'] = $db->getVar('SELECT CEILING(MAX(i.sequence) 
			/ 1000) * 1000 FROM modules_extras AS i');
		}
		
		// Save widget
		return (int) $db->insert('modules_extras', $widget);
	}

	 /**
	 * Store all ids
	 * 
	 * @param array $ids
	 * @return bool 
	 */
	public static function storeAllIds(array $ids)
	{
		return (bool) $stored = BackendModel::getDB(true)->insert('links_extra_ids',(array) $ids);
	}

	 /**
	 * Update a certain category
	 *
	 * @param array $item
	 * @return bool
	 */
	public static function updateCategory(array $item)
	{
		return (bool) BackendModel::getDB(true)->update('links_categories',(array) $item, 'id = ?', array($item['id']));
		BackendModel::invalidateFrontendCache('links', BL::getWorkingLanguage());
	}

	 /**
	 * Update a certain link
	 *
	 * @param array $item
	 * @return bool
	 */
	public static function updateLink(array $item)
	{
		return (bool) BackendModel::getDB(true)->update('links_links',(array) $item, 'id = ?', array($item['id']));
		BackendModel::invalidateFrontendCache('links', BL::getWorkingLanguage());
	}

	 /**
	 * update widget by id
	 *
	 * @param array $widget
	 * @return bool
	 */
	public static function updateWidget(array $widget)
	{
		return (bool) BackendModel::getDB(true)->update('modules_extras',(array) $widget, 'id = ?', array($widget['id']));
	}
}