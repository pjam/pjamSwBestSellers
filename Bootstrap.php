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
//        $controller = $args->get('subject');
//        $view = $controller->View();
//
//        $view->addTemplateDir(
//            __DIR__ . '/Views'
//        );
//
//        $view->assign('slogan', $this->getSlogan());
//        $view->assign('sloganSize', $this->Config()->get('font-size'));
//        $view->assign('italic', $this->Config()->get('italic'));

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

        $this->Form()->setElement('number', 'listCount', array(
            'value' => 5,
            'label' => 'Article Count',
            'required' => true,
            'description' => 'Number of articles to be shown in the list'
        ));
    }
 }