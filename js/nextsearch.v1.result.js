/*
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

/** global: OCA */
/** global: next_settings */
/** global: curr */
/** global: nav */

var result = {

	displayResult: function (res) {
		if (next_settings.resultContainer === null) {
			return;
		}

		nav.onResultDisplayed();

		var searchResult = res.result;
		if (searchResult.length === 0) {
			result.displayNoResult();
			return;
		}

		for (var i = 0; i < searchResult.length; i++) {
			result.displayProviderResult(searchResult[i]);
		}
	},


	displayNoResult: function () {
		next_settings.divNoResult.fadeTo(next_settings.delay_result, 1);
		next_settings.resultContainer.find('.provider_header').each(function () {
			$(this).fadeTo(next_settings.delay_result, 0);
		});
	},


	/** @namespace result.provider */
	/** @namespace result.documents */
	displayProviderResult: function (result) {

		next_settings.divNoResult.fadeTo(next_settings.delay_result, 0);

		var current = curr.getProviderResult(result.provider.id);
		var divProvider = nav.getDivProvider(result.provider.id, result.provider.name);

		nav.managerDivProviderResult(divProvider.children('.provider_result'), result.documents,
			current.documents);

		divProvider.slideDown(next_settings.delay_provider, function () {
			$(this).fadeTo(next_settings.delay_provider, 1);
		});

		curr.setProviderResult(result.provider.id, result);
	},


	recalibrateResult: function (oldResult, newResult) {
		var tmpResult = [];
		for (var i = 0; i < oldResult.length; i++) {
			if (result.getResultIndex(oldResult[i].id, newResult) > -1) {
				tmpResult.push(oldResult[i]);
			}
		}

		return tmpResult;
	},


	getResultIndex: function (id, result) {
		if (!result) {
			return -1;
		}

		for (var i = 0; i < result.length; i++) {
			if (result[i].id === id) {
				return i;
			}
		}

		return -1;
	}


};
