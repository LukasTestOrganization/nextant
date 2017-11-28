<?php

/**
 * Nextcloud - nextant
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Maxence Lange <maxence@pontapreta.net>
 * @copyright Maxence Lange 2016
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
 */
namespace OCA\Nextant\Command;

use OC\Core\Command\Base;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class Optimize extends Base
{

    private $configService;

    private $solrService;

    private $solrTools;

    public function __construct($configService, $solrService, $solrTools)
    {
        parent::__construct();
        $this->configService = $configService;
        $this->solrService = $solrService;
        $this->solrTools = $solrTools;
    }

    protected function configure()
    {
        parent::configure();
        $this->setName('nextant:optimize')
            ->setDescription('optimize your Solr core')
            ->addOption('commit', 'm', InputOption::VALUE_NONE, 'Commit only ; Do not optimize.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (! $this->solrService->configured(true)) {
            $output->writeln('Nextant is not yet configured');
            return;
        }
        
        $this->solrService->setClient(array(
            'timeout' => 36000
        ));
        
        $infos = $this->solrTools->getInfoCore();
        $output->writeln('Your index contains ' . $infos->index->segmentCount . ' segments.');
        
        if (! $input->getOption("no-interaction")) {
            $helper = $this->getHelper('question');
            if (! $input->getOption('commit')) {
                $question = new ConfirmationQuestion('<question>Your core will not be accessible while optimize is running. Continue with this action? (y/N) </question> ', false);
                
                if (! $helper->ask($input, $output, $question)) {
                    return;
                }
            }
        }
        
        if (! $result = $this->solrTools->commit(! $input->getOption('commit')))
            $output->writeln('Operation failed');
        else
            $output->writeln('Operation success (' . gmdate("H:i:s", floor($result->getQueryTime() / 1000)) . ')');
    }
}



