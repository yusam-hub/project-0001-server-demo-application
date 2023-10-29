<?php

namespace App\ApiClients;

use Aws\S3\S3Client;

//todo: https://min.io/docs/minio/linux/reference/minio-mc-admin.html
class ClientS3Sdk
{
    protected string $bucketName;
    protected S3Client $s3Client;

    public function __construct()
    {
        $config = app_ext_config('api-clients.' . get_class($this));
        $this->bucketName = $config['bucketName'] ?? '';
        $this->s3Client = new S3Client($config['args']??[]);
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
     * @return string|null
     */
    public function getObject(string $path): ?string
    {
        $awsResult = $this->s3Client->getObject([
            'Bucket'   => $this->getBucketName(),
            'Key'      => $path,
        ]);
        if (isset($awsResult["@metadata"]["statusCode"]) && ($awsResult["@metadata"]["statusCode"] == 200)) {
            $body = $awsResult->get('Body');
            if ($body instanceof \GuzzleHttp\Psr7\Stream) {
                return $body->getContents();
            }
            return null;
        }
        return null;
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
        $date = date("Y-m-d H:i:s");
        if ($this->putObject($key, $date)) {
            if ($this->isObjectExist($key)) {
                $content = $this->getObject($key);
                if ($content === $date && $this->deleteObject($key)) {
                    return true;
                }
            }
        }
        return false;
    }
}