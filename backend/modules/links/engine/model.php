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
		  WHERE i.language = ? ORDER BY i.sequence ASC';

	const QRY_DATAGRID_LINKS =
		 'SELECT i.*
		  FROM links AS i
		  WHERE i.language = ?';

	const QRY_DATAGRID_BROWSE =
		 'SELECT i.*
		  FROM links AS i
		  WHERE i.language = ? AND i.category_id = ?
		  ORDER BY i.id DESC';

	/**
	 * Is the deletion of a category allowed?
	 *
	 * @param int $id
	 * @return bool
	 */
	public static function deleteCategoryAllowed($id)
	{
		return (bool) (BackendModel::getContainer()->get('database')->getVar(
			'SELECT COUNT(id)
			 FROM links AS i
			 WHERE i.category_id = ? AND i.language = ?',
			array((int) $id, BL::getWorkingLanguage())) == 0
		);
	}

	/**
	 * Delete a category.
	 *
	 * @param int $id The id of the category to delete.
	 */
	public static function deleteCategoryById($id)
	{
		$id = (int) $id;
		$db = BackendModel::getContainer()->get('database');

		// get item
		$item = self::getCategoryFromId($id);

		// build extra
		$extra = array(
			'id' => $item['extra_id'], 
			'module' => 'links', 
			'type' => 'widget', 
			'action' => 'widget'
		);

		// delete extra
		$db->delete('modules_extras', 'id = ? AND module = ? AND type = ? AND action = ?', 
			array(
				$extra['id'], 
				$extra['module'], 
				$extra['type'], 
				$extra['action']
		));

		// update blocks with this item linked
		$db->update('pages_blocks',
		array(
			'extra_id' => null,
			'html' => ''),
			'extra_id = ?',
			array(
				$item['extra_id']
			)
		);

		// delete all records
		$db->delete('links_categories', 'id = ? AND language = ?', array($id, BL::getWorkingLanguage()));
	}

	/**
	 * Is the deletion of a link allowed?
	 *
	 * @param int $id
	 * @return bool
	 */
	public static function deleteLinkAllowed($id)
	{
		return (bool) (BackendModel::getContainer()->get('database')->getVar(
			'SELECT COUNT(id)
			 FROM links AS i
			 WHERE i.category_id = ? AND i.language = ?', 
			array((int) $id, BL::getWorkingLanguage())) == 0
		);
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
		return (bool) BackendModel::getContainer()->get('database')->delete('links', 'id = ?', array((int) $id));
	}

	/**
	 * Does the category exist?
	 *
	 * @param int $id
	 * @return bool
	 */
	public static function existsCategory($id)
	{
		return (bool) BackendModel::getContainer()->get('database')->getVar(
			'SELECT COUNT(i.id)
			 FROM links_categories AS i
			 WHERE i.id = ? AND i.language = ?', 
			array((int) $id, BL::getWorkingLanguage())
		);
	}

	/**
	 * Does the link exist?
	 *
	 * @param int $id
	 * @return bool
	 */
	public static function existsLink($id)
	{
		return (bool) BackendModel::getContainer()->get('database')->getVar(
			'SELECT COUNT(i.id)
			 FROM links AS i
			 WHERE i.id = ? AND i.language = ?', 
			array((int) $id, BL::getWorkingLanguage())
		);
	}

	/**
	 * Get all categories
	 *
	 * @return array
	 */
	public static function getCategories()
	{
		return (array) BackendModel::getContainer()->get('database')->getRecords(
			'SELECT i.*
			 FROM links_categories AS i
			 WHERE i.language = ? AND i.hidden = ? ORDER BY i.sequence', 
			array(BL::getWorkingLanguage(), 'N')
		);
	}

	/**
	 * Get all category names for dropdown
	 *
	 * @return array
	 */
	public static function getCategoriesForDropdown()
	{
		return (array) BackendModel::getContainer()->get('database')->getPairs(
			'SELECT i.id, i.title
			 FROM links_categories AS i
			 WHERE i.language = ?
			 ORDER BY i.id ASC', 
			array(BL::getWorkingLanguage())
		);
	}

	/**
	 * Get category by id
	 *
	 * @param int $id
	 * @return array
	 */
	public static function getCategoryFromId($id)
	{
		return (array) BackendModel::getContainer()->get('database')->getRecord(
			'SELECT i.*
			 FROM links_categories AS i
			 WHERE i.language = ? AND i.id = ?', 
			array(BL::getWorkingLanguage(), (int) $id)
		);
	}

	/**
	 * Get categoryname by id
	 *
	 * @param int $id
	 * @return string
	 */
	public static function getCatNameFromId($id)
	{
		return (string) BackendModel::getContainer()->get('database')->getRecord(
			'SELECT i.title
			 FROM links_categories AS i
			 WHERE i.language = ? AND i.id = ?', 
			array(BL::getWorkingLanguage(), (int) $id)
		);
	}

	/**
	 * Get invalid links
	 *
	 * @return array
	 */
	public static function getInvalidLinks()
	{
		return (array) BackendModel::getContainer()->get('database')->getRecords(
			'SELECT i.*
			 FROM links AS i
			 WHERE i.language = ? AND i.alive = ?', 
			array(BL::getWorkingLanguage(), 'N')
		);
	}

	/**
	 * Fetch a link
	 *
	 * @param int $id
	 * @return array
	 */
	public static function getLinkById($id)
	{
		return (array) BackendModel::getContainer()->get('database')->getRecord(
			'SELECT i.*
			 FROM links AS i
			 WHERE i.id = ? AND i.language = ?', 
			array((int) $id, BL::getWorkingLanguage())
		);
	}

	/**
	 * Get all links
	 *
	 * @return array
	 */
	public static function getLinks()
	{
		return (array) BackendModel::getContainer()->get('database')->getRecords(
			'SELECT i.*
			 FROM links AS i
			 WHERE i.language = ? AND hidden = ?', 
			array(BL::getWorkingLanguage(), 'N')
		);
	}

	/**
	 * Get the maximum sequence for a category
	 *
	 * @return int
	 */
	public static function getMaximumCategorySequence()
	{
		return (int) BackendModel::getContainer()->get('database')->getVar(
			'SELECT MAX(i.sequence)
			 FROM links_categories AS i
			 WHERE i.language = ? AND hidden = ?', 
			array(BL::getWorkingLanguage(), 'N')
		);
	}

	/**
	 * Get the maximum id
	 *
	 * @return int
	 */
	public static function getMaximumId()
	{
		return (int) BackendModel::getContainer()->get('database')->getVar('SELECT MAX(id) FROM links LIMIT 1');
	}

	/**
	 * Add a new item.
	 *
	 * @param array $item The data to insert.
	 * @return int
	 */
	public static function insertCategory(array $item)
	{
		$db = BackendModel::getContainer()->get('database');

		// build extra
		$extra = array(
			'module' => 'links', 
			'type' => 'widget', 
			'label' => 'links', 
			'action' => 'widget', 
			'data' => NULL, 
			'hidden' => 'N', 
			'sequence' => $db->getVar(
				'SELECT MAX(i.sequence) + 1
				 FROM modules_extras AS i
				 WHERE i.module = ?', 
				array('links')
		));

		if(is_null($extra['sequence'])) $extra['sequence'] = $db->getVar(
			'SELECT CEILING(MAX(i.sequence) / 1000) * 1000
			 FROM modules_extras AS i'
		);

		// insert extra
		$item['extra_id'] = $db->insert('modules_extras', $extra);
		$extra['id'] = $item['extra_id'];

		// insert and return the id
		$item['id'] = $db->insert('links_categories', $item);

		// update extra (item id is now known)
		$extra['data'] = serialize(array(
			'id' => $item['id'], 
			'extra_label' => $item['title'], 
			'language' => $item['language'], 
			'edit_url' => BackendModel::createURLForAction('edit') . '&id=' . $item['id']
		));
		$db->update(
			'modules_extras', $extra, 'id = ? AND module = ? AND type = ? AND action = ?', 
			array(
				$extra['id'], 
				$extra['module'], 
				$extra['type'], 
				$extra['action']
			)
		);

		return $item['id'];
	}

	/**
	 * Add a new link.
	 *
	 * @param array $item
	 * @return int $id
	 */
	public static function insertLink(array $item)
	{
		$id = BackendModel::getContainer()->get('database')->insert('links', $item);
		return (int) $id;
	}

	/**
	 * Update an existing item.
	 *
	 * @param array $item The new data.
	 * @return int
	 */
	public static function updateCategory(array $item)
	{
		$db = BackendModel::getContainer()->get('database');

		// build extra
		$extra = array(
			'id' => $item['extra_id'], 
			'module' => 'links', 
			'type' => 'widget', 
			'label' => 'links', 
			'action' => 'widget', 
			'data' => serialize(array(
									'id' => $item['id'], 
									'extra_label' => $item['title'], 
									'language' => $item['language'], 
									'edit_url' => BackendModel::createURLForAction('edit') . '&id=' . $item['id'])), 
									'hidden' => 'N'
		);

		// update extra
		$db->update('modules_extras', $extra, 'id = ? ', array($item['extra_id']));

		// update the category
		$update = $db->update('links_categories', $item, 'id = ?', array($item['id']));

		// return the id
		return $update;
	}

	/**
	 * Update category sequence
	 *
	 * @param array $item
	 * @return bool
	 */
	public static function updateCategorySequence(array $item)
	{
		BackendModel::getContainer()->get('database')->update('links_categories', (array) $item, 'id = ?', array($item['id']));
	}

	/**
	 * Update a certain link
	 *
	 * @param array $item
	 * @return bool
	 */
	public static function updateLink(array $item)
	{
		$update = BackendModel::getContainer()->get('database')->update('links', (array) $item, 'id = ?', array($item['id']));
		return $update;
	}

	/**
	 * Check if given url is valid
	 * 
	 * @param type $url
	 * @return boolean
	 */
	public static function urlExists($url)
	{
		$parts = parse_url($url);
		if(!$parts) return false;

		// if url given, check for validity
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_TIMEOUT, 20);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_NOBODY, true);
		curl_setopt($ch, CURLOPT_HEADER, true);
		
		if($parts['scheme'] == 'https')
		{
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		}
		
		$response = curl_exec($ch);
		$info = curl_getinfo($ch);
		curl_close($ch);
		
		if(preg_match('/HTTP\/1\.\d+\s+(\d+)/', $response, $matches))
		{
			$httpcode = intval($matches[1]);
		}

		else
		{
			return false;
		};

		// result
		if($httpcode >= 200 && $httpcode < 307)
		{
			return true;
		}
		
		else
		{
			return false;
		}
	}
}