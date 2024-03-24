<?php

namespace App\Service;

use CoopTilleuls\UrlSignerBundle\UrlSigner\UrlSignerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UrlSignerService
{
    private UrlSignerInterface $urlSigner;
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(UrlSignerInterface $urlSigner, UrlGeneratorInterface $urlGenerator)
    {
        $this->urlSigner = $urlSigner;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param string $urlName
     * @param array $parameters
     * @param string $expireTime
     * @return string
     * @throws \Exception
     */
    public function generateSignedUrl(string $urlName, array $parameters = [], string $expireTime = 'PT5M'): string
    {
        $url = $this->urlGenerator->generate($urlName, $parameters);
        $expiration = (new \DateTime('now'))->add(new \DateInterval($expireTime));
        return $this->urlSigner->sign($url, $expiration);
    }
}