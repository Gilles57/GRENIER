<?php

namespace App\Command;

use App\Entity\Allocine;
use League\Csv\Reader;
use League\Csv\Statement;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\HttpClientInterface;

define('KEY', 'c7924bfc3e4208e9e6eafb5beaee9940');
#[AsCommand(
    name: 'import-csv',
    description: 'Importer un fichier csb dans la base de donnée',
    aliases: ['app:csv'],
    hidden: false,
)]
class ImportCsvCommand extends Command
{
    protected function configure(): void
    {
//        $this
//            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
//            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
//        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('En attente de l\'importation du fichier...');
        $csv = Reader::createFromPath( 'public/data/Films.csv');

//      $header = $csv->getHeader();
//      $records = $csv->getRecords();
        $stmt = Statement::create()
//            ->offset(10)
            ->limit(25)
;

        $records = $stmt->process($csv);
        foreach ($records as $record) {


//            $film = (new Film())
//                ->setTitreFR($record['TitreFR'])
//                ->setAnnee((int)($record['Annee']))
//                ->setExtension($record['Extension'])
//                ->setVu(false)
//                ->setEtat($etat);
//
//            $this->em->persist($film);
        }

        $this->em->flush();
        $io->success('Fichier importé correctement !');

        return Command::SUCCESS;
    }
}
