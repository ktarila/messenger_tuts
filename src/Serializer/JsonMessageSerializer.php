<?php

namespace App\Serializer;

use App\Message\ComputeAreaMessage;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

class JsonMessageSerializer implements SerializerInterface
{
    public function decode(array $encodedEnvelope): Envelope
    {
        return new Envelope(new ComputeAreaMessage(0));
    }

    public function encode(Envelope $envelope): array
    {
        $message = $envelope->getMessage();

        return [
            'body' => json_encode($message),
        ];
    }
}
