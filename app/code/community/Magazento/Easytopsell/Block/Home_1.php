<?php
/*
 *  Created on Mar 16, 2011
 *  Author Ivan Proskuryakov - volgodark@gmail.com - Magazento.com
 *  Copyright Proskuryakov Ivan. Magazento.com © 2011. All Rights Reserved.
 *  Single Use, Limited Licence and Single Use No Resale Licence ["Single Use"]
 */
?>
<?php

class Magazento_Easytopsell_Block_Home extends Mage_Catalog_Block_Product_Abstract {


//	protected function _construct() {
//		parent::_construct();
//		$this->addData(array(
//			'cache_lifetime' => 86400,
//			'cache_tags' => array('magazentoeasytopsell_home'),
//		));
//
//	}

        protected function _beforeToHtml() {

		$storeId = Mage::app()->getStore()->getId();
                $sellDate=$this->getModel()->getSellDate($this->getModel()->getHomepageDaysLimit());
		$collection = Mage::getResourceModel('reports/product_sold_collection')
                                ->addOrderedQty()
                                ->setStoreId($storeId)
                                ->addStoreFilter($storeId)
				->setDateRange($sellDate['startdate'], $sellDate['todaydate']) //
                                ->addUrlRewrite()
                                ->setOrder('ordered_qty', 'desc')
                                ->setPageSize($this->getModel()->getHomepageProductsLimit())
                                ->setCurPage(1)
				->setOrder('ordered_qty', 'desc');
                
                if ($collection->isEnabledFlat())
                {
                    $collection->getSelect()
                                ->joinLeft(
                                    array('cpl' => $collection->getResource()->getFlatTableName()),
                                        "e.entity_id = cpl.entity_id"
                                )
                                ->where("cpl.visibility IN (?)", 
                                    array(
                                        Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_CATALOG, 
                                        Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH
                                    )
                                );      
                } else {
                    $collection->addAttributeToFilter('cpl.visibility', 
                                array(
                                    'in' => array(
                                        Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_CATALOG, 
                                        Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH
                                    )
                                )
                    );
                }                

                $catId=$this->getModel()->getHomepageCatID();
                if ($catId>0) {
                    $category = $this->getModel()->getCategory($catId);
                    $collection->addCategoryFilter($category); 
                }


		$this->setProductCollection($collection);
		return parent::_beforeToHtml();
	}

    public function getModel() {
        return Mage::getModel('easytopsell/Data');
    }

}














//				->setDateRange($sellDate['startdate'], $sellDate['todaydate'])
//				->addAttributeToFilter('is_salable')
//				->addAttributeToFilter('visibility', array('in' => array(Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_CATALOG, Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)))
//				->addAttributeToFilter('status', Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
//                                ->addSaleableFilterToCollection()
//                                ->addInStockFilterToCollection()
//                                ->addUrlRewrite()
////                                ->addCategoryFilter($currentCategory)
//                                ->setPageSize($this->getModel()->getHomepageProductsLimit())
//                                ->setCurPage(1)
//				->addOrderedQty()
//				->setOrder('ordered_qty', 'desc');
//

//			->addAttributeToSelect(array('entity_id', 'name', 'price', 'small_image', 'short_description', 'description', 'type_id', 'status'))
//			->addOrderedQty()
//			->setStoreId($storeId)
//			->addStoreFilter($storeId)
////			->addCategoryFilter($currentCategory)
//			->setOrder('ordered_qty', 'desc')
//                        ->setPageSize($this->getModel()->getHomepageProductsLimit())
//                        ->setCurPage(1);
//
//                $collection= array();
//                foreach ($rawcollection as $product) {
////                    $addproduct = $product->getData('is_salable');
//                    $collection[]=$product->getData();
//                }
//                var_dump($rawcollection);
////
//
//                exit();