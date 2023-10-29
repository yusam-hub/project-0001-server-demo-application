<?php

namespace App\ApiClients;

use Aws\S3\S3Client;

class ClientS3Sdk
{
    protected string $bucketName;
    protected S3Client $s3Client;

    public function __construct()
    {
       //todo: https://min.io/docs/minio/linux/reference/minio-mc-admin.html
       $this->s3Client = new S3Client([
           'version' => 'latest',
           'region' => 'us-east-1',
           'use_path_style_endpoint' => true,
           'endpoint' => 'http://192.168.0.200:9300/',
           'credentials' => [
               'key' => 'admin-access-key',
               'secret' => 'K0tBHplDpkgog57EcVh6s9Mcg3kxa90UKsCLUe8N',
           ],
       ]);
    }

    /**
     * @return string
     */
    public function getBucketName(): string
    {
        return $this->bucketName;
    }

    /**
     * @param string $bucketName
     */
    public function setBucketName(string $bucketName): void
    {
        $this->bucketName = $bucketName;
    }

    /**
     * @param string $path
     * @param $stringOrResourceBody
     * @return bool
     */
    public function putObject(string $path, $stringOrResourceBody): bool
    {
        $awsResult = $this->s3Client->putObject([
            'Bucket'   => $this->getBucketName(),
            'Key'      => $path,
            'Body'     => $stringOrResourceBody,
        ]);
        if (isset($awsResult["@metadata"]["statusCode"]) && ($awsResult["@metadata"]["statusCode"] == 200)) {
            return true;
        }
        return false;
    }

    /**
     * @param string $path
     * @return bool
     */
    public function isObjectExist(string $path): bool
    {
        return $this->s3Client->doesObjectExist($this->getBucketName(), $path);
    }

    /**
     * @param string $path
     * @return bool
     */
    public function deleteObject(string $path): bool
    {
        $awsResult = $this->s3Client->deleteObject([
            'Bucket'   => $this->getBucketName(),
            'Key'      => $path,
        ]);
        if (isset($awsResult["@metadata"]["statusCode"]) && ($awsResult["@metadata"]["statusCode"] == 204)) {
            return true;
        }
        return false;
    }

    public function check(): bool
    {
        $this->setBucketName('demo-dev');
        $key = 'check.txt';
        if ($this->putObject($key, 'checking')) {
            if ($this->isObjectExist($key)) {
                if ($this->deleteObject($key)) {
                    return true;
                }
            }
        }
        return false;
    }
}