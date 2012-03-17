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
 * Add a new category.
 *
 * @return	int
 * @param	array $item		The data to insert.
 */
public static function insertCategory(array $item)
	{
		// get db
		$db = BackendModel::getDB(true);

		// insert and return the new id
		$item['id'] = $db->insert('links_categories', $item);

		// return the new id
		return $item['id'];
	}

/**
 * Get all categories
 *
 * @return	array
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
 * Is the deletion of a category allowed?
 *
 * @param int $id
 * @return bool
 */
public static function deleteCategoryAllowed($id)
	{
		return (BackendModel::getDB()->getVar(
		'SELECT COUNT(id)
		 FROM links_links AS i
		 WHERE i.category_id = ? AND i.language = ?',
		 array((int) $id, BL::getWorkingLanguage())) == 0);
	}

/**
 * Get categoryname by id
 *
 * @param int $id
 * @return	string
 */

public static function getCatNameFromId($id)
	{
		BackendModel::getDB()->getRecord(
		'SELECT i.title
		 FROM links_categories AS i
		 WHERE i.language = ? AND i.id = ?',
		 array(BL::getWorkingLanguage(), $id));
	}
	
/**
 * Get category by id
 *
 * @param int $id
 * @return	string
 */

public static function getCategoryFromId($id)
	{
		return (array) BackendModel::getDB()->getRecord(
		'SELECT i.*
		 FROM links_categories AS i
		 WHERE i.language = ? AND i.id = ?',
		 array(BL::getWorkingLanguage(), $id));
	}

/**
 * Get all links
 *
 * @return	array
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
 * Is the deletion of a link allowed?
 *
 * @param int $id
 * @return bool
 */
public static function deleteLinkAllowed($id)
	{
		return (BackendModel::getDB()->getVar(
		'SELECT COUNT(id)
		 FROM links_links AS i
		 WHERE i.category_id = ? AND i.language = ?',
		 array((int) $id, BL::getWorkingLanguage())) == 0);
	}

/**
 * Update a certain link
 *
 * @param array $item
 */
public static function updateLink(array $item)
	{
		BackendModel::getDB(true)->update('links_links', $item, 'id = ?', array($item['id']));
		BackendModel::invalidateFrontendCache('links', BL::getWorkingLanguage());
	}

/**
 * Update a certain category
 *
 * @param array $item
 */
public static function updateCategory(array $item)
	{
		BackendModel::getDB(true)->update('links_categories', $item, 'id = ?', array($item['id']));
		BackendModel::invalidateFrontendCache('links', BL::getWorkingLanguage());
	}

/**
 * Get all category names for dropdown
 *
 * @return	array
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
 * Add a new link.
 *
 * @return	int
 * @param	array $item		The data to insert.
 */

public static function addLink(array $item)
	{
		return BackendModel::getDB(true)->insert('links_links', $item);
	}

	
/**
 * Delete a link
 *
 * @return	void
 * @param	int $id		The id of the link to be deleted.
 */

public static function deleteLinkById($id)
	{
		// get db
		$db = BackendModel::getDB(true);

		// delete the record
		$db->delete('links_links', 'id = ?', array((int) $id));
	}

/**
 * Delete a category
 *
 * @return	void
 * @param	int $id		The id of the category to be deleted.
 */

public static function deleteCategoryById($id)
	{
		// get db
		$db = BackendModel::getDB(true);

		// delete the record
		$db->delete('links_categories', 'id = ?', array((int) $id));
	}


}
?>