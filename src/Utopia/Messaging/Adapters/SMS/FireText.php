<?php

namespace Utopia\Messaging\Adapters\SMS;

// Reference Material
// https://www.firetext.co.uk/docs#sendingsms
// https://github.com/FireText/FireText-PHP-SDK/blob/master/docs/en/01.send.sms.md

use Utopia\Messaging\Adapters\SMS as SMSAdapter;
use Utopia\Messaging\Messages\SMS;

class FireText extends SMSAdapter
{
    /**
     * @param  string  $apiKey FireText API Key
     */
    public function __construct(
        private string $apiKey,
    ) {
    }

    public function getName(): string
    {
        return 'FireText';
    }

    public function getMaxMessagesPerRequest(): int
    {
        return 50;  // https://www.firetext.co.uk/docs#sendingsms:~:text=Comma%20separated%20list%20of%20up%20to%2050%20mobile%20numbers
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    protected function process(SMS $message): string
    {
        return $this->request(
            method: 'POST',
            url: 'https://www.firetext.co.uk/api/sendsms',
            headers: [
                'Authorization: Bearer '.$this->apiKey,
                'Content-Type: application/json',
            ],
            body: \json_encode([
                'text' => $message->getContent(),
                'from' => $message->getFrom(),
                'to' => $message->getTo(),
            ]),
        );
    }
}