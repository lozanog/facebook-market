<?php
/**
* Magento Chatbot Integration
* Copyright (C) 2018
*
* This file is part of Werules/Chatbot.
*
* Werules/Chatbot is free software: you can redistribute it and/or modify* it under the terms of the MIT License as published by
** (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* MIT License for more details.
*
* You should have received a copy of the MIT License
* along with this program. If not, see <https://opensource.org/licenses/MIT>.
*/

namespace Werules\Chatbot\Model\Api;

class witAI extends \Magento\Framework\Model\AbstractModel {

    protected $_token;
    protected $_version = '11/11/2017';
    //protected $_data;

    /// Class constructor
    public function __construct($token) {
        $this->_token = $token;
    }

    function getTextResponse($query) {
        $options = array(
            'http' => array(
                'method' => 'GET',
                'header' => "Authorization: Bearer " . $this->_token . "\r\n"
            )
        );
        $content = "&q=" . urlencode($query);
        return $this->getWitAIResponse("message", $content, $options);
    }

    function getAudioResponse($audioFile) {
        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => "Authorization: Bearer " . $this->_token . "\n" .
                    "Content-Type: audio/mpeg3" . "\r\n",
                'content' => file_get_contents($audioFile)
            )
        );
        return $this->getWitAIResponse("speech", "", $options);
    }

    function getWitAIResponse($endPoint, $content, $options) {
        $context = stream_context_create($options);
        $url = 'https://api.wit.ai/' . $endPoint . '?v=' . $this->_version . $content;
        $result = file_get_contents($url, false, $context);
        $result = json_decode($result, true);

        if ($result)
            return $result;

        return null;
    }
}
