 <?php

 class Shopware_Plugins_Frontend_pjamSwBestSellers_Bootstrap extends Shopware_Components_Plugin_Bootstrap
 {
    public function getVersion()
    {
        return '0.1.0';
    }

    public function getLabel()
    {
        return 'Best Seller Articles';
    }
    
    public function getInfo()
    {
        return array(
            'label' => $this->getLabel(),
            'version' => $this->getVersion(),
            'link' => 'https://github.com/pjam/pjamSwBestSellers'
        );
    }

    public function install()
    {
        $this->subscribeEvent(
            'Enlight_Controller_Action_PostDispatchSecure_Frontend',
            'onFrontendPostDispatch'
        );

        $this->createConfig();

        return true;
    }
    
    public function onFrontendPostDispatch(Enlight_Event_EventArgs $args)
    {
        /** @var \Enlight_Controller_Action $controller */
        $controller = $args->get('subject');
        $view = $controller->View();

        $view->addTemplateDir(
            __DIR__ . '/Views'
        );
        
        $bestSellers = $this->getBestSellers($this->Config()->get('timePeriod'), $this->Config()->get('listCount'));
        
        $articles = array();
        
        foreach( $bestSellers as $b )
        {
          $articleData['name'] = $b['name'];
          $articleImages = Shopware()->Modules()->Articles()->sGetArticlePictures($b['id'], true);
          $articleData['image'] = $articleImages['src'][0];
          $articles[] = $articleData;
        }
        
        $view->assign('articles', $articles);
    }
    
    private function createConfig()
    {
        $this->Form()->setElement(
            'select',
            'timePeriod',
            array(
                'label' => 'Time Period',
                'store' => array(
                    array('day', 'Last 24 hours'),
                    array('week', 'Last 7 days'),
                    array('month', 'Last 30 days')
                ),
                'value' => 'week',
                'description' => 'Time frame from which the sales count is evaluated'
            )
        );

        $this->Form()->setElement(
            'number', 
            'listCount', 
            array(
                'value' => 5,
                'label' => 'Article Count',
                'required' => true,
                'description' => 'Number of articles to be shown in the list'
            )
        );
    }
    
    private function getBestSellers($period, $articleCount)
    {
        $sql = "SELECT a.id, a.name, SUM(od.quantity) as sellCount "
                . "FROM s_articles a "
                . "LEFT JOIN s_order_details od ON a.id = od.articleID "
                . "LEFT JOIN s_order o ON o.id = od.orderID "
                . "WHERE a.active = 1 AND (o.status = 2 OR o.status = 7) AND o.ordertime >= :minimumDate "
                . "GROUP BY a.id "
                . "ORDER BY sellCount DESC";

        if ($articleCount !== null) {
            $sql = Shopware()->Db()->limit($sql, $articleCount);
        }
        echo "|" . $period . "|" . $this->getMinimumDate($period) . "|";
        $articles = Shopware()->Db()->executeQuery($sql, array(
            'minimumDate' => $this->getMinimumDate($period)
        ));
        
        return $articles;
    }
    
    private function getMinimumDate($period) 
    {
      $minimumDate = new DateTime();
      if( 'day' == $period )
        $interval = new DateInterval ('PT24H');
      else if( 'week' == $period )
        $interval = new DateInterval ('P1W');
      else if( 'month' == $period )
        $interval = new DateInterval ('P1M');
      
      return $minimumDate->sub($interval)->format('Y-m-d H:i:s');
    }
 }