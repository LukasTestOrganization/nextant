<?php
/**
 * FullNextSearch - Full Text Search your Nextcloud.
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Maxence Lange <maxence@artificial-owl.com>
 * @copyright 2017
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *
 */

namespace OCA\FullNextSearch\Model;

class IndexDocument implements \JsonSerializable {

	const NOT_ENCODED = 0;
	const ENCODED_BASE64 = 1;

	/** @var string|int */
	private $id;

	/** @var string */
	private $providerId;

	/** @var DocumentAccess */
	private $access;

	/** @var Index */
	private $index;

	/** @var int */
	private $modifiedTime = 0;

	/** @var string */
	private $title;

	/** @var string */
	private $content;

	/** @var string */
	private $link = '';

	/** @var array */
	private $more = [];

	/** @var array */
	private $excerpts = [];

	/** @var string */
	private $score;

	/** @var array */
	private $info;

	/** @var int */
	private $contentEncoded;

	public function __construct($providerId, $id) {
		$this->providerId = $providerId;
		$this->id = $id;
	}


	/**
	 * @param string $id
	 *
	 * @return $this
	 */
	public function setId($id) {
		$this->id = $id;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getId() {
		return $this->id;
	}


	/**
	 * @param string $providerId
	 *
	 * @return $this
	 */
	public function setProviderId($providerId) {
		$this->providerId = $providerId;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getProviderId() {
		return $this->providerId;
	}


	/**
	 * @param Index $index
	 */
	public function setIndex(Index $index) {
		$this->index = $index;
	}

	/**
	 * @return Index
	 */
	public function getIndex() {
		return $this->index;
	}


	/**
	 * @param int $modifiedTime
	 *
	 * @return $this
	 */
	public function setModifiedTime($modifiedTime) {
		$this->modifiedTime = $modifiedTime;

		return $this;
	}

	/**
	 * @return int
	 */
	public function getModifiedTime() {
		return $this->modifiedTime;
	}

	/**
	 * @param int $time
	 *
	 * @return bool
	 */
	public function isOlderThan($time) {
		return ($this->modifiedTime < $time);
	}


	/**
	 * @param DocumentAccess $access
	 *
	 * @return $this
	 */
	public function setAccess(DocumentAccess $access) {
		$this->access = $access;

		return $this;
	}

	/**
	 * @return DocumentAccess
	 */
	public function getAccess() {
		return $this->access;
	}


	/**
	 * @param string $title
	 *
	 * @return $this
	 */
	public function setTitle($title) {
		$this->title = $title;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}


	/**
	 * @param string $content
	 * @param int $encoded
	 *
	 * @return $this
	 */
	public function setContent($content, $encoded = 0) {
		$this->content = $content;
		$this->contentEncoded = $encoded;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getContent() {
		return $this->content;
	}


	/**
	 * @return int
	 */
	public function isContentEncoded() {
		return $this->contentEncoded;
	}


	/**
	 * @param string $link
	 *
	 * @return $this
	 */
	public function setLink($link) {
		$this->link = $link;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getLink() {
		return $this->link;
	}


	/**
	 * @param array $more
	 *
	 * @return $this
	 */
	public function setMore($more) {
		$this->more = $more;

		return $this;
	}

	/**
	 * @return array
	 */
	public function getMore() {
		return $this->more;
	}


	/**
	 * @param array $excerpts
	 *
	 * @return $this
	 */
	public function setExcerpts($excerpts) {
		$this->excerpts = $excerpts;

		return $this;
	}

	/**
	 * @return array
	 */
	public function getExcerpts() {
		return $this->excerpts;
	}

	/**
	 * @param string $excerpt
	 */
	public function addExcerpt($excerpt) {
		$this->excerpts[] = $excerpt;
	}


	/**
	 * @param string $score
	 *
	 * @return $this
	 */
	public function setScore($score) {
		$this->score = $score;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getScore() {
		return $this->score;
	}


	/**
	 * @param string $info
	 * @param mixed $value
	 *
	 * @return $this
	 */
	public function setInfo($info, $value) {
		$this->info[$info] = $value;

		return $this;
	}

	/**
	 * @param string $info
	 * @param mixed $default
	 *
	 * @return mixed
	 */
	public function getInfo($info, $default = '') {
		if (!key_exists($info, $this->info)) {
			return $default;
		}

		return $this->info[$info];
	}


	/**
	 * @return array
	 */
	public function getInfoAll() {

		$info = [];
		foreach ($this->info as $k => $v) {
			if (substr($k, 0, 1) === '_') {
				continue;
			}

			$info[$k] = $v;
		}

		return $info;
	}


	public function __destruct() {
		unset($this->id);
		unset($this->providerId);
		unset($this->access);
		unset($this->modifiedTime);
		unset($this->title);
		unset($this->content);
		unset($this->link);
		unset($this->more);
		unset($this->excerpts);
		unset($this->score);
		unset($this->info);
		unset($this->contentEncoded);
	}

	/**
	 * @return array
	 */
	public function jsonSerialize() {
		return [
			'id'       => $this->getId(),
			'title'    => $this->getTitle(),
			'link'     => $this->getLink(),
			'more'     => $this->getMore(),
			'excerpts' => $this->getExcerpts(),
			'score'    => $this->getScore()
		];
	}

}