<?php
namespace Ometria\Api\Helper\Service\Filterable\Service;

class Product extends \Ometria\Api\Helper\Service\Filterable\Service
{
    protected $urlModel;
    public function __construct(
		\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria,
		\Ometria\Api\Helper\Filter\V1\Service $helperOmetriaApiFilter,
		\Magento\Framework\Reflection\DataObjectProcessor $dataObjectProcessor ,
		\Magento\Catalog\Model\Product\Url $urlModel   
    )
    {
        $this->urlModel = $urlModel;
        return parent::__construct($searchCriteria, $helperOmetriaApiFilter, $dataObjectProcessor);
    }
    
    public function createResponse($repository, $serialize_as)
    {
        $searchCriteria = $this->helperOmetriaApiFilter
            ->applyFilertsToSearchCriteria($this->searchCriteria);
            
        $list = $repository->getList($searchCriteria);

        $items = [];
        foreach($list->getItems() as $item)
        {
            $new;
            if($serialize_as)
            {
                $new = $this->dataObjectProcessor->buildOutputDataArray(
                    $item,
                    $serialize_as                
                );        
            }
            else
            {
                $new = $item->getData();
            }
            
            $new['url'] = $item->getProductUrl();
            $new['category_ids'] = $item->getCategoryIds();
            
            $items[] = $new;
        }
        
        return $items;    
    }
}