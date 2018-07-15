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

namespace Werules\Chatbot\Model;

use Magento\Framework\Exception\NoSuchEntityException;
use Werules\Chatbot\Api\ChatbotUserRepositoryInterface;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Api\SortOrder;
use Werules\Chatbot\Api\Data\ChatbotUserSearchResultsInterfaceFactory;
use Werules\Chatbot\Model\ResourceModel\ChatbotUser as ResourceChatbotUser;
use Werules\Chatbot\Api\Data\ChatbotUserInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;
use Werules\Chatbot\Model\ResourceModel\ChatbotUser\CollectionFactory as ChatbotUserCollectionFactory;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Exception\CouldNotDeleteException;

class ChatbotUserRepository implements chatbotUserRepositoryInterface
{

    protected $resource;

    protected $dataObjectProcessor;

    protected $chatbotUserCollectionFactory;

    private $storeManager;

    protected $chatbotUserFactory;

    protected $dataChatbotUserFactory;

    protected $searchResultsFactory;

    protected $dataObjectHelper;


    /**
     * @param ResourceChatbotUser $resource
     * @param ChatbotUserFactory $chatbotUserFactory
     * @param ChatbotUserInterfaceFactory $dataChatbotUserFactory
     * @param ChatbotUserCollectionFactory $chatbotUserCollectionFactory
     * @param ChatbotUserSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourceChatbotUser $resource,
        ChatbotUserFactory $chatbotUserFactory,
        ChatbotUserInterfaceFactory $dataChatbotUserFactory,
        ChatbotUserCollectionFactory $chatbotUserCollectionFactory,
        ChatbotUserSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->chatbotUserFactory = $chatbotUserFactory;
        $this->chatbotUserCollectionFactory = $chatbotUserCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataChatbotUserFactory = $dataChatbotUserFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \Werules\Chatbot\Api\Data\ChatbotUserInterface $chatbotUser
    ) {
        /* if (empty($chatbotUser->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $chatbotUser->setStoreId($storeId);
        } */
        try {
            $chatbotUser->getResource()->save($chatbotUser);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the chatbotUser: %1',
                $exception->getMessage()
            ));
        }
        return $chatbotUser;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($chatbotUserId)
    {
        $chatbotUser = $this->chatbotUserFactory->create();
        $chatbotUser->getResource()->load($chatbotUser, $chatbotUserId);
        if (!$chatbotUser->getId()) {
            throw new NoSuchEntityException(__('ChatbotUser with id "%1" does not exist.', $chatbotUserId));
        }
        return $chatbotUser;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->chatbotUserCollectionFactory->create();
        foreach ($criteria->getFilterGroups() as $filterGroup) {
            foreach ($filterGroup->getFilters() as $filter) {
                if ($filter->getField() === 'store_id') {
                    $collection->addStoreFilter($filter->getValue(), false);
                    continue;
                }
                $condition = $filter->getConditionType() ?: 'eq';
                $collection->addFieldToFilter($filter->getField(), [$condition => $filter->getValue()]);
            }
        }
        
        $sortOrders = $criteria->getSortOrders();
        if ($sortOrders) {
            /** @var SortOrder $sortOrder */
            foreach ($sortOrders as $sortOrder) {
                $collection->addOrder(
                    $sortOrder->getField(),
                    ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
                );
            }
        }
        $collection->setCurPage($criteria->getCurrentPage());
        $collection->setPageSize($criteria->getPageSize());
        
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setTotalCount($collection->getSize());
        $searchResults->setItems($collection->getItems());
        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(
        \Werules\Chatbot\Api\Data\ChatbotUserInterface $chatbotUser
    ) {
        try {
            $chatbotUser->getResource()->delete($chatbotUser);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the ChatbotUser: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($chatbotUserId)
    {
        return $this->delete($this->getById($chatbotUserId));
    }
}
