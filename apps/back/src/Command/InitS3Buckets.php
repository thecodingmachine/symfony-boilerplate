<?php

declare(strict_types=1);

namespace App\Command;

use Aws\S3\S3MultiRegionClient;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class InitS3Buckets extends Command
{
    public function __construct(
        private readonly S3MultiRegionClient $client,
        private string $publicBucketName,
        private string $privateBucketName,
    ) {
        parent::__construct('app:storage:init-buckets');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $style = new SymfonyStyle($input, $output);
        $style->info('Initializing buckets...');
        $this->createBucket($this->privateBucketName, $style);

        if ($this->createBucket($this->publicBucketName, $style)) {
            //Add DL rights for public bucket
            $policyReadOnly = '{
              "Version": "2012-10-17",
              "Statement": [
                {
                  "Action": [
                    "s3:GetBucketLocation",
                    "s3:ListBucket"
                  ],
                  "Effect": "Allow",
                  "Principal": {
                    "AWS": [
                      "*"
                    ]
                  },
                  "Resource": [
                    "arn:aws:s3:::%s"
                  ],
                  "Sid": ""
                },
                {
                  "Action": [
                    "s3:GetObject"
                  ],
                  "Effect": "Allow",
                  "Principal": {
                    "AWS": [
                      "*"
                    ]
                  },
                  "Resource": [
                    "arn:aws:s3:::%s/*"
                  ],
                  "Sid": ""
                }
              ]
            }
            ';

            $this->client->putBucketPolicy([
                'Bucket' => $this->publicBucketName,
                'Policy' => sprintf(
                    $policyReadOnly,
                    $this->publicBucketName,
                    $this->publicBucketName,
                ),
            ]);
        }

        $style->success('Buckets init done !');

        return Command::SUCCESS;
    }

    public function createBucket(string $bucketName, SymfonyStyle $style): bool
    {
        $buckets = $this->client->listBuckets();
        if (is_iterable($buckets->get('Buckets'))) {
            foreach ($buckets->get('Buckets') as $bucket) {
                if ($bucket['Name'] === $bucketName) {
                    $style->info(sprintf("Bucket '%s' exists, skipping...", $bucketName));

                    return false;
                }
            }
        }

        $this->client->createBucket(['Bucket' => $bucketName]);
        $style->success(sprintf("Bucket '%s' created !", $bucketName));

        return true;
    }
}
