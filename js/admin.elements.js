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
/** global: admin_settings */



var admin_elements = {
	fns_div: null,
	fns_platforms: null,
	fns_chunkSize: null,
	fns_providers: null,

	init: function () {
		admin_elements.fns_div = $('#fns');
		admin_elements.fns_platforms = $('#fns_platforms');
		admin_elements.fns_chunkSize = $('#fns_chunk_size');

		admin_elements.fns_platforms.on('change', function () {
			admin_settings.tagSettingsAsNotSaved($(this));
			admin_settings.saveSettings();
		});
		admin_elements.fns_chunkSize.on('input', function () {
			admin_settings.tagSettingsAsNotSaved($(this));
		}).blur(function () {
			admin_settings.saveSettings();
		});
	}
};


