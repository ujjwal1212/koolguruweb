<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;

class IndexController extends AbstractActionController
{
    public function indexAction(){
        $session = new Container('User');
        if($session->offsetExists('email') && $session->offsetGet('roleCode') == 'st'){
            $this->redirect()->toRoute('student');
        }
    }
    
    public function locateusAction(){
        
    }
    
    public function aboutusAction(){
        
    }
    
    public function visionAction(){
        
    }
    
    public function missionAction(){
        
    }
    
    public function contactusAction(){
        
    }
    public function demoquizAction(){
        return array();
    }
}
