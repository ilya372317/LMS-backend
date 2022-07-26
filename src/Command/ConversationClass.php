<?php

namespace App\Command;

class ConversationClass
{
    public function conversation(): string
    {
        stream_set_blocking(fopen('php://stdin', 'r'), false);

        while (true) {
            $readstreams = [STDIN];
            $timeout = 2;
            $writestreams = [];
            $except = [];

            $numberofstreamswithdata = stream_select(
                $readstreams,
                $writestreams,
                $except,
                $timeout
            );

            if ($numberofstreamswithdata > 0) {
                return fgets(STDIN);
            }

        }
    }
}