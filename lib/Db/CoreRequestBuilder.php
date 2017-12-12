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

namespace OCA\FullNextSearch\Db;


use Doctrine\DBAL\Query\QueryBuilder;
use OCA\FullNextSearch\Model\Index;
use OCA\FullNextSearch\Service\ConfigService;
use OCA\FullNextSearch\Service\MiscService;
use OCP\DB\QueryBuilder\IQueryBuilder;
use OCP\IDBConnection;
use OCP\IL10N;

class CoreRequestBuilder {

	const TABLE_INDEXES = 'fullnextsearch_indexes';
	const TABLE_TICKS = 'fullnextsearch_ticks';

	/** @var IDBConnection */
	protected $dbConnection;

	/** @var IL10N */
	protected $l10n;

	/** @var ConfigService */
	protected $configService;

	/** @var MiscService */
	protected $miscService;

	/** @var string */
	protected $defaultSelectAlias;


	/**
	 * CoreRequestBuilder constructor.
	 *
	 * @param IL10N $l10n
	 * @param IDBConnection $connection
	 * @param ConfigService $configService
	 * @param MiscService $miscService
	 */
	public function __construct(
		IL10N $l10n, IDBConnection $connection, ConfigService $configService, MiscService $miscService
	) {
		$this->l10n = $l10n;
		$this->dbConnection = $connection;
		$this->configService = $configService;
		$this->miscService = $miscService;
	}


	/**
	 * Limit the request to the Id
	 *
	 * @param IQueryBuilder $qb
	 * @param int $id
	 */
	protected function limitToId(IQueryBuilder &$qb, $id) {
		$this->limitToDBField($qb, 'id', $id);
	}


	/**
	 * Limit the request to the OwnerId
	 *
	 * @param IQueryBuilder $qb
	 * @param string $userId
	 */
	protected function limitToOwnerId(IQueryBuilder &$qb, $userId) {
		$this->limitToDBField($qb, 'owner_id', $userId);
	}


	/**
	 * Limit to the type
	 *
	 * @param IQueryBuilder $qb
	 * @param string $providerId
	 */
	protected function limitToProviderId(IQueryBuilder &$qb, $providerId) {
		$this->limitToDBField($qb, 'provider_id', $providerId);
	}


	/**
	 * Limit to the documentId
	 *
	 * @param IQueryBuilder $qb
	 * @param string $documentId
	 */
	protected function limitToDocumentId(IQueryBuilder &$qb, $documentId) {
		$this->limitToDBField($qb, 'document_id', $documentId);
	}


	/**
	 * Limit the request to the Source
	 *
	 * @param IQueryBuilder $qb
	 * @param string $source
	 */
	protected function limitToSource(IQueryBuilder &$qb, $source) {
		$this->limitToDBField($qb, 'id', $source);
	}


	/**
	 * Limit the request to the Source
	 *
	 * @param IQueryBuilder $qb
	 * @param string $status
	 */
	protected function limitToStatus(IQueryBuilder &$qb, $status) {
		$this->limitToDBField($qb, 'status', $status);
	}


	/**
	 * @param IQueryBuilder $qb
	 * @param string $field
	 * @param string|integer $value
	 */
	private function limitToDBField(IQueryBuilder &$qb, $field, $value) {
		$expr = $qb->expr();
		$pf = ($qb->getType() === QueryBuilder::SELECT) ? $this->defaultSelectAlias . '.' : '';
		$qb->andWhere($expr->eq($pf . $field, $qb->createNamedParameter($value)));
	}


	/**
	 * @param IQueryBuilder $qb
	 */
	protected function limitToQueuedIndexes(IQueryBuilder &$qb) {
		$expr = $qb->expr();
		$pf = ($qb->getType() === QueryBuilder::SELECT) ? $this->defaultSelectAlias . '.' : '';
		$qb->andWhere($expr->neq($pf . 'status', $qb->createNamedParameter(Index::STATUS_INDEX_DONE)));
	}

}



