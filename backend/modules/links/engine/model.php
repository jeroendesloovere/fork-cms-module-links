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
	 * Add a new link.
	 *
	 * @param array $item
	 * @return int $id
	 */
	public static function addLink(array $item)
	{
		$id = BackendModel::getDB(true)->insert('links', $item);
		return (int) $id;
	}

	/**
	 * Convert the title to a widgetlabel
	 *
	 * @param string $catname
	 * @return string $label
	 */
	public static function createWidgetLabel($catname)
	{
		// convert the item to camelcase
		$label = preg_replace('/\s+/', '_', $catname);
		$label = SpoonFilter::toCamelCase((string) $label);
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
                     FROM links AS i
                     WHERE i.category_id = ? AND i.language = ?',
                    array((int) $id, BL::getWorkingLanguage())) == 0);
	}

	/**
	 * Delete a category.
	 *
	 * @param int $id The id of the category to delete.
	 */
	public static function deleteCategoryById($id)
	{
		$id = (int) $id;
		$db = BackendModel::getDB(true);

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
		$db->delete('modules_extras',
			    'id = ? AND module = ? AND type = ? AND action = ?',
			    array(
				$extra['id'],
                                $extra['module'],
                                $extra['type'],
				$extra['action']
		));

		// update blocks with this item linked
		$db->update('pages_blocks', array(
                                            'extra_id' => null,
                                            'html' => ''),
                                            'extra_id = ?', array(
                                                            $item['extra_id']
                                            ));

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
		return (bool) (BackendModel::getDB()->getVar(
                    'SELECT COUNT(id)
                     FROM links AS i
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
		return (bool) BackendModel::getDB(true)->delete('links', 'id = ?', array((int) $id));
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
                     FROM links AS i
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
                     WHERE i.language = ? AND i.hidden = ? ORDER BY i.sequence',
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
	 * Fetch a link
	 *
	 * @param int $id
	 * @return array
	 */
	public static function getLinkById($id)
	{
		return (array) BackendModel::getDB()->getRecord(
                    'SELECT i.*
                     FROM links AS i
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
                     FROM links AS i
                     WHERE i.language = ? AND hidden = ?',
                    array(BL::getWorkingLanguage(), 'N'));
	}

	/**
	 * Get the maximum sequence for a category
	 *
	 * @return int
	 */
	public static function getMaximumCategorySequence()
	{
		return (int) BackendModel::getDB()->getVar(
                    'SELECT MAX(i.sequence)
                     FROM links_categories AS i
                     WHERE i.language = ? AND hidden = ?',
                    array(BL::getWorkingLanguage(), 'N'));
	}

        /**
	 * Add a new item.
	 *
	 * @param array $item The data to insert.
	 * @return int
	 */
	public static function insertCategory(array $item)
	{
		$db = BackendModel::getDB(true);

		// build extra
		$extra = array(
			'module' => 'links',
			'type' => 'widget',
			'label' => BackendLinksModel::createWidgetLabel($item['title']),
			'action' => 'widget',
			'data' => NULL,
			'hidden' => 'N',
			'sequence' => $db->getVar(
				'SELECT MAX(i.sequence) + 1
				 FROM modules_extras AS i
				 WHERE i.module = ?',
				array('links')
			)
		);

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
                                            'edit_url' => BackendModel::createURLForAction('edit') . '&id=' . $item['id'])
		);
		$db->update(
			'modules_extras',
                        $extra,
			'id = ? AND module = ? AND type = ? AND action = ?',
			array(
                        $extra['id'],
                        $extra['module'],
                        $extra['type'],
                        $extra['action'])
		);

		return $item['id'];
	}

        /**
	 * Update an existing item.
	 *
	 * @param array $item The new data.
	 * @return int
	 */
	public static function updateCategory(array $item)
	{
		$db = BackendModel::getDB(true);

		// build extra
		$extra = array(
			'id' => $item['extra_id'],
			'module' => 'links',
			'type' => 'widget',
			'label' => BackendLinksModel::createWidgetLabel($item['title']),
			'action' => 'widget',
			'data' => serialize(array(
                                            'id' => $item['id'],
                                            'extra_label' => $item['title'],
                                            'language' => $item['language'],
                                            'edit_url' => BackendModel::createURLForAction('edit') . '&id=' . $item['id'])),
                                            'hidden' => 'N');

		// update extra
		$db->update('modules_extras', $extra, 'id = ? ', array($item['extra_id']));

		// update the category
		$update = $db->update('links_categories', $item, 'id = ?', array($item['id']));

		// return the id
		return $update;
	}

	/**
	 * Update a certain link
	 *
	 * @param array $item
	 * @return bool
	 */
	public static function updateLink(array $item)
	{
		$update = BackendModel::getDB(true)->update('links',(array) $item, 'id = ?', array($item['id']));
		return $update;
	}
}