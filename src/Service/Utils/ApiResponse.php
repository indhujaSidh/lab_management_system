<?php


namespace App\Service\Utils;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class ApiResponse
{
    private $serializer;
    private $em;

    /**
     * ApiResponse constructor.
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(SerializerInterface $serializer,EntityManagerInterface $entityManager)
    {
        $this->serializer = $serializer;
        $this->em = $entityManager;
    }


    /**
     *
     * @param $object
     * @param int $code
     * @return Response
     */
    public function apiSendResponse($object, $code = 200)
    {
        if (!is_string($object)) {
            $object = json_encode($object);
        }
        $response = new Response($object, $code);
        $responseHeaders = $response->headers;
        $responseHeaders->set('Access-Control-Allow-Headers', 'origin, content-type, accept');
        $responseHeaders->set('Access-Control-Allow-Origin', '*');
        $responseHeaders->set('Access-Control-Allow-Methods', 'POST, GET, PUT, DELETE, PATCH, OPTIONS');
        $responseHeaders->set('content-type', 'application/json');
        return $response;
    }

    /**
     * @param bool $status
     * @param string $message
     * @param array $payload
     * @param int $error_code
     * @return \stdClass
     */
    public function getResponseObj($status = true, $message = "_message", $payload = array(), $error_code = 0)
    {
        $responseObj = new \stdClass();
        $responseObj->status = $status;
        $responseObj->message = $message;
        if (!$status) {
            $responseObj->error_code = $error_code;
        }
        $responseObj->payload = $payload;
        return $responseObj;
    }

    /**
     * @param $object
     * @return string
     */
    protected function objectSerialize($object)
    {
        //some encoding here
        return json_encode($object);
    }

    /**
     * @param $text
     * @return mixed
     */
    protected function objectDeserialize($text)
    {
        // some decoding here
        return json_decode($text);
    }

    public function objectNormalize($obj,$ignoredAttrs = array()){

        $ignoredAttrs = array_merge($ignoredAttrs,array('__initializer__','__cloner__','__isInitialized__'));
        $encoder = new JsonEncoder();
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                return $object->getId();
            },
        ];
        $normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);
        $dateTimeNormalizer = new DateTimeNormalizer();
        $serializer = new Serializer([$dateTimeNormalizer,$normalizer], [$encoder]);
        return $serializer->normalize($obj,'json', [AbstractNormalizer::IGNORED_ATTRIBUTES => $ignoredAttrs,AbstractObjectNormalizer::ENABLE_MAX_DEPTH => true]);
//        return $this->serializer->normalize($obj,'json', [AbstractNormalizer::IGNORED_ATTRIBUTES => ['__initializer__','__cloner__','__isInitialized__']]);
    }

    public function objectJsonResponse($obj,$ignoredAttrs = array()){

        $res = array('status'=>true,'message'=>null,'payload'=>array());

        $paperFrequency = $this->objectNormalize($obj,$ignoredAttrs);
        $res['payload'] = $paperFrequency;

        $resObj = $this->getResponseObj($res['status'],$res['message'],$res['payload']);
        return $this->apiSendResponse($resObj);
    }

}
