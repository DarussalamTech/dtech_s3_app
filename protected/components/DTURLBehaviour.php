<?php

class DTURLBehaviour extends CBehavior
{

    public function attach($owner)
    {
        // set the event callback
        $owner->attachEventHandler('onBeginRequest', array($this, 'beginRequest'));
    }

    /**
     * This method is attached to the 'onBeginRequest' event above.
     * */
    public function beginRequest(CEvent $event)
    {
        $route = Yii::app()->getUrlManager()->parseUrl(Yii::app()->getRequest());
        $controllers = array("product");
        return true;
        
        /*
         * will be used later
          $urlManager = Yii::app()->getUrlManager();


          $routes = explode("/", $route);
          CVarDumper::dump($routes,10,true);
          //die;

          $rules = array(
          '<country:[\w-\.]+>/<city:[\w-\.]+>/<city_id:[\w-\.]+>' => '/site/storehome',
          '<country:[\w-\.]+>/<city:[\w-\.]+>/<city_id:[\w-\.]+>/featuredProducts' => '/web/product/featuredProducts',
          '<country:[\w-\.]+>/<city:[\w-\.]+>/<city_id:[\w-\.]+>/bestSellings' => '/web/product/bestSellings',
          '<country:[\w-\.]+>/<city:[\w-\.]+>/<city_id:[\w-\.]+>/<product_id:[\w-\.]+>/productDetail' => '/web/product/productDetail',
          '<country:[\w-\.]+>/<city:[\w-\.]+>/<city_id:[\w-\.]+>/allProducts' => '/web/product/allproducts',
          '<country:[\w-\.]+>/<city:[\w-\.]+>/<city_id:[\w-\.]+>/viewCart' => '/web/product/viewcart',
          );

          if (strstr($route, "web") && in_array($routes[1], $controllers))
          {

          $urlManager->rules = $rules;
          }
          else
          {
          $urlManager->rules = array();
          }

          $urlManager->init();
         * */
    }

}

?>
