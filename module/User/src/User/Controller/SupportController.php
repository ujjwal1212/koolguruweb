<?php

/*
 * Controller Class for Support module
 */

namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class SupportController extends AbstractActionController {

    /**
     * Default Action of SupportController
     */
    public function indexAction() { 
        return new ViewModel();
    }

}
