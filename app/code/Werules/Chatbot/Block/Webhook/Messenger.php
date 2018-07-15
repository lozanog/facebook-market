<?php
/**
 * Magento Chatbot Integration
 * Copyright (C) 2018
 * 
 * This file is part of Werules/Chatbot.
 * 
 * Werules/Chatbot is free software: you can redistribute it and/or modify
 * it under the terms of the MIT License.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * MIT License for more details.
 * 
 * You should have received a copy of the MIT License
 * along with this program. If not, see <https://opensource.org/licenses/MIT>.
 */

namespace Werules\Chatbot\Block\Webhook;

class Messenger extends \Werules\Chatbot\Block\Webhook\Index
{
//    protected $_messenger;

//    public function __construct(
//        \Magento\Framework\View\Element\Template\Context $context,
//        \Magento\Framework\ObjectManagerInterface $objectManager,
//        \Werules\Chatbot\Helper\Data $helperData,
//        \Werules\Chatbot\Model\ChatbotAPI $chatbotAPI,
//        \Magento\Framework\App\Request\Http $request,
//        \Werules\Chatbot\Model\MessageFactory $message
////        \Werules\Chatbot\Cron\Worker $cronWorker
//    )
//    {
//        $this->_helper = $helperData;
//        $this->_chatbotAPI = $chatbotAPI;
//        $this->_request = $request;
//        $this->_messageModel = $message;
//        $this->_objectManager = $objectManager;
//        $this->_define = new \Werules\Chatbot\Helper\Define;
////        $this->_cronWorker = $cronWorker;
////        parent::__construct($context, $objectManager, $helperData, $chatbotAPI, $request, $message);
//    }

    public function getVerificationHub($hub_token)
    {
        $api_token = $this->_helper->getConfigValue('werules_chatbot_messenger/general/api_key');
        $messenger = $this->_chatbotAPI->initMessengerAPI($api_token);
        $result = $messenger->verifyWebhook($hub_token);

        if ($result)
            return $result;
        else
            return $this->_helper->getJsonErrorResponse();
    }

    protected function getMessengerPayload($messenger)
    {
        $payload = $messenger->getPostbackPayload();
        if (!$payload)
            $payload = $messenger->getQuickReplyPayload();

//        return json_encode($payload);
        return $payload;
    }

    protected function processRequest()
    {
//        $enabled = $this->getConfigValue('werules_chatbot_messenger/general/enable');
//        if ($enabled == $this->_define::ENABLED)
//        {
            $challenge_hub = $this->getConfigValue('werules_chatbot_messenger/general/enable_hub_challenge');
            if ($challenge_hub == $this->_define::ENABLED)
            {
                $hub_token = $this->getConfigValue('werules_chatbot_general/general/custom_key');
                $verification_hub = $this->getVerificationHub($hub_token);
                if ($verification_hub)
                    $result = $verification_hub;
                else
                    $result = __("Please check your Hub Verify Token.");
            }
            else // process message
            {
                $messengerInstance = $this->getMessengerInstance();
                if (!$messengerInstance->getPostData())
                    return $this->getJsonErrorResponse();
                $this->logPostData($messengerInstance->getPostData(), 'werules_chatbot_messenger.log');

                $messageObject = $this->createMessageObject($messengerInstance);
                if (isset($messageObject->content))
                    $result = $this->messageHandler($messageObject);
                else
                    $result = $this->getJsonSuccessResponse(); // return success to avoid receiving the same message again
            }
//        }
//        else
//            $result = $this->getJsonErrorResponse();

        return $result;
    }

    protected function getMessengerInstance()
    {
        $api_token = $this->_helper->getConfigValue('werules_chatbot_messenger/general/api_key');
        $messenger = $this->_chatbotAPI->initMessengerAPI($api_token);
        return $messenger;
    }

    protected function createMessageObject($messenger)
    {
        $messageObject = new \stdClass();
        if ($messenger->Text())
            $content = $messenger->Text();
        else
            $content = $messenger->getPostbackTitle();
        if (!$content)
            return $messageObject;

        $messageObject->senderId = $messenger->ChatID();
        $messageObject->content = $content;
        $messageObject->status = $this->_define::PROCESSING;
        $messageObject->direction = $this->_define::INCOMING;
        $messageObject->chatType = $this->_define::MESSENGER_INT; // TODO
        $messageObject->contentType = $this->_define::CONTENT_TEXT; // TODO
        $messageObject->currentCommandDetails = $this->_define::CURRENT_COMMAND_DETAILS_DEFAULT; // TODO
        $messageObject->messagePayload = $this->getMessengerPayload($messenger); // TODO
        $messageObject->chatMessageId = $messenger->MessageID();
        if ($messenger->getMessageTimestamp())
            $messageObject->sentAt = substr($messenger->getMessageTimestamp(), 0, 10);
        else
            $messageObject->sentAt = time();
        $datetime = date('Y-m-d H:i:s');
        $messageObject->createdAt = $datetime;
        $messageObject->updatedAt = $datetime;

        return $messageObject;

//        $result = $this->messageHandler($messageObject);
//        $messageModel = $this->_messageModel->create();
//        $messageModel->setSenderId($messenger->ChatID());
//        $messageModel->setContent($messenger->Text());
//        $messageModel->setStatus(1); // 0 -> not processed / 1 -> processing / 2 -> processed
//        $messageModel->setDirection(0); // 0 -> incoming / 1 -> outgoing
//        $messageModel->setChatMessageId($messenger->MessageID());
//        $datetime = date('Y-m-d H:i:s');
//        $messageModel->setCreatedAt($datetime);
//        $messageModel->setUpdatedAt($datetime);

//        try {
//            $messageModel->save();
//        } catch (\Magento\Framework\Exception\LocalizedException $e) {
//            return $this->_helper->getJsonErrorResponse();
//        }
//        $this->_helper->processMessage($messageModel->getMessageId());
//
//        return $this->_helper->getJsonSuccessResponse();
//        return $result;
    }
}
