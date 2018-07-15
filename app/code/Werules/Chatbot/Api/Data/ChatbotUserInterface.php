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

namespace Werules\Chatbot\Api\Data;

interface ChatbotUserInterface
{

    const UPDATED_AT = 'updated_at';
    const CUSTOMER_ID = 'customer_id';
    const QUOTE_ID = 'quote_id';
    const ADMIN = 'admin';
    const ENABLE_SUPPORT = 'enable_support';
    const CREATED_AT = 'created_at';
    const ENABLE_PROMOTIONAL_MESSAGES = 'enable_promotional_messages';
    const SESSION_ID = 'session_id';
    const CHATBOTUSER_ID = 'chatbotuser_id';


    /**
     * Get chatbotuser_id
     * @return string|null
     */
    public function getChatbotuserId();

    /**
     * Set chatbotuser_id
     * @param string $chatbotuser_id
     * @return \Werules\Chatbot\Api\Data\ChatbotUserInterface
     */
    public function setChatbotuserId($chatbotuserId);

    /**
     * Get customer_id
     * @return string|null
     */
    public function getCustomerId();

    /**
     * Set customer_id
     * @param string $customer_id
     * @return \Werules\Chatbot\Api\Data\ChatbotUserInterface
     */
    public function setCustomerId($customer_id);

    /**
     * Get quote_id
     * @return string|null
     */
    public function getQuoteId();

    /**
     * Set quote_id
     * @param string $quote_id
     * @return \Werules\Chatbot\Api\Data\ChatbotUserInterface
     */
    public function setQuoteId($quote_id);

    /**
     * Get session_id
     * @return string|null
     */
    public function getSessionId();

    /**
     * Set session_id
     * @param string $session_id
     * @return \Werules\Chatbot\Api\Data\ChatbotUserInterface
     */
    public function setSessionId($session_id);

    /**
     * Get enable_promotional_messages
     * @return string|null
     */
    public function getEnablePromotionalMessages();

    /**
     * Set enable_promotional_messages
     * @param string $enable_promotional_messages
     * @return \Werules\Chatbot\Api\Data\ChatbotUserInterface
     */
    public function setEnablePromotionalMessages($enable_promotional_messages);

    /**
     * Get enable_support
     * @return string|null
     */
    public function getEnableSupport();

    /**
     * Set enable_support
     * @param string $enable_support
     * @return \Werules\Chatbot\Api\Data\ChatbotUserInterface
     */
    public function setEnableSupport($enable_support);

    /**
     * Get admin
     * @return string|null
     */
    public function getAdmin();

    /**
     * Set admin
     * @param string $admin
     * @return \Werules\Chatbot\Api\Data\ChatbotUserInterface
     */
    public function setAdmin($admin);

    /**
     * Get created_at
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * Set created_at
     * @param string $created_at
     * @return \Werules\Chatbot\Api\Data\ChatbotUserInterface
     */
    public function setCreatedAt($created_at);

    /**
     * Get updated_at
     * @return string|null
     */
    public function getUpdatedAt();

    /**
     * Set updated_at
     * @param string $updated_at
     * @return \Werules\Chatbot\Api\Data\ChatbotUserInterface
     */
    public function setUpdatedAt($updated_at);
}
