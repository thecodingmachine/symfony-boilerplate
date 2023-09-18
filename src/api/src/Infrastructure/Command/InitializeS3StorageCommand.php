<?php

declare(strict_types=1);

namespace App\Infrastructure\Command;

use App\Infrastructure\S3\CreateBucket;
use App\Infrastructure\S3\CreatePublicBucket;
use Aws\S3\S3MultiRegionClient;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function Safe\sprintf;

final class InitializeS3StorageCommand extends Command
{
    public function __construct(
        private S3MultiRegionClient $client,
        private string $publicBucket,
        private string $privateBucket,
        private string $publicSource,
        private string $privateSource
    ) {
        parent::__construct('app:init-storage:s3');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (! $this->areStorageSourcesValid($output)) {
            return Command::FAILURE;
        }

        $createPublicBucket = new CreatePublicBucket($this->client);
        if (! $createPublicBucket->create($this->publicBucket)) {
            $output->writeln(
                'Bucket "' . $this->publicBucket . '" already exists'
            );
        } else {
            $output->writeln(
                'Bucket "' . $this->publicBucket . '" created'
            );
        }

        $createBucket = new CreateBucket($this->client);
        if (! $createBucket->create($this->privateBucket)) {
            $output->writeln(
                'Bucket "' .
                $this->privateBucket .
                '" already exists'
            );
        } else {
            $output->writeln(
                'Bucket "' .
                $this->privateBucket .
                '" created'
            );
        }

        return Command::SUCCESS;
    }

    private function areStorageSourcesValid(OutputInterface $output): bool
    {
        $template = '%s storage source is not "%s" but "%s"';
        if ($this->publicSource !== 'public.storage.s3') {
            $message = sprintf(
                $template,
                'Public',
                'public.storage.s3',
                $this->publicSource
            );

            $output->writeln($message);

            return false;
        }

        if ($this->privateSource !== 'private.storage.s3') {
            $message = sprintf(
                $template,
                'Private',
                'private.storage.s3',
                $this->privateSource
            );

            $output->writeln($message);

            return false;
        }

        return true;
    }
}
