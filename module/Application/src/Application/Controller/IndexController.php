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
use Application\Model\State;
use Application\Model\StateTable;

class IndexController extends AbstractActionController
{
    protected $adapter;
    protected $SendqueryTable;
    
    public function getAdapter() {
        if (!$this->adapter) {
            $sm = $this->getServiceLocator();
            $this->adapter = $sm->get('Zend\Db\Adapter\Adapter');
        }
        return $this->adapter;
    }

    public function getSendqueryTable() {
        if (!$this->SendqueryTable) {
            $sm = $this->getServiceLocator();
            $this->SendqueryTable = $sm->get('Application\Model\SendqueryTable');
        }
        return $this->SendqueryTable;
    }
    public function indexAction(){
        $renderer = $this->serviceLocator->get('Zend\View\Renderer\RendererInterface');
        $js_path = $renderer->basePath('js/koolguru/application');
        $headScript = $this->getServiceLocator()->get('viewhelpermanager')
                ->get('headScript');

        $headScript->appendFile($js_path . '/demovideos.js');
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
        $renderer = $this->serviceLocator->get('Zend\View\Renderer\RendererInterface');
        $js_path = $renderer->basePath('js/koolguru/application');
        $headScript = $this->getServiceLocator()->get('viewhelpermanager')
                ->get('headScript');

        $headScript->appendFile($js_path . '/contact_us.js');
        $errorMsg = '';
        $successMsg = '';
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $this->getSendqueryTable()->saveQuery($data);
            $errorMsg = $this->flashMessenger()->getCurrentMessagesFromNamespace('error');
            $successMsg = $this->flashMessenger()->getCurrentMessagesFromNamespace('success');
            //$successMsg = 'Thanks you for the submission, We will get back to you soon!';
            $this->flashMessenger()->setNamespace('success')->addMessage('Thanks you for the submission, We will get back to you soon!');
        }
        return new ViewModel(array(
            'errorMsg' => $errorMsg,
            'successMsg' => $successMsg
        ));
        
    }
    public function demoquizAction(){
        $renderer = $this->serviceLocator->get('Zend\View\Renderer\RendererInterface');
        $js_path = $renderer->basePath('js/koolguru/application');
        $headScript = $this->getServiceLocator()->get('viewhelpermanager')
                ->get('headScript');

        $headScript->appendFile($js_path . '/demoquiz.js');
        return array();
    }
}
