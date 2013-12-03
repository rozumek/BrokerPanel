<?php

class StockOrdersController extends Cms_Controller_Action {

    public function init() {
        $this->_actionModel = new Application_Model_StockOrders();
        $this->_actionForm = new Application_Form_StockOrder();

        $this->_setModuleForRoutes('default');
        $this->_setControllereForRoutes('stock-orders');
        $this->_setMessagesPrefix('STOCK_ORDER');

        $this->_setTitle('STOCK_ORDERS');

        $this->view->headScript()->appendFile('/js/stock-orders.js');

        $this->_setDefaultFilters();
    }

    public function addAction() {
        if (!Core_Acl::isUserAllowed('default:stock-orders', 'select-broker')) {
            if ($this->getRequest()->isPost()) {
                $brokerId = $this->_getIdentity()->getId();
                $requestBrokerId = $this->getRequest()->getPost('broker', 0);

                if ($brokerId !== $requestBrokerId) {
                    $this->getRequest()->setPost('broker', $brokerId);
                }
            }
        }

        parent::addAction();
        $this->_setTitle('ADD_STOCK_ORDER');
    }

    public function editAction() {
        $id = (int) $this->_getParam('id', 0);

        if ($id > 0) {
            $stockOrder = $this->_actionModel->get($id);
            $brokerId = $stockOrder->getBroker()->getId();

            if ($stockOrder instanceof Application_Model_DbTable_Row_StockOrder) {
                if (!(Core_Acl::canUserEdit('default:stock-orders') || Core_Acl::canUserEditOwn('default:stock-orders', $brokerId))) {
                    $this->_addMessagetoQueue(_T('ACCESS_DENIED'), Core_Log::ERR);
                    $this->_gotoIndex();
                }
            }
        }

        parent::editAction();
        $this->_setTitle('EDIT_STOCK_ORDER');
    }

    public function viewAction() {
        $id = (int) $this->_getParam('id', 0);

        if ($id > 0) {
            $stockOrder = $this->_actionModel->get($id);
            $brokerId = $stockOrder->getBroker()->getId();

            if ($stockOrder instanceof Application_Model_DbTable_Row_StockOrder) {
                if (!((Core_Acl::canUserView('default:stock-orders') || Core_Acl::canUserViewOwn('default:stock-orders', $brokerId)) && !(Core_Acl::canUserEdit('default:stock-orders') || Core_Acl::canUserEditOwn('default:stock-orders', $brokerId)))) {
                    $this->_addMessagetoQueue(_T('ACCESS_DENIED'), Core_Log::ERR);
                    $this->_gotoIndex();
                }
            }
        }

        parent::viewAction();
        $this->_setTitle('VIEW_STOCK_ORDER');
    }

    public function deleteAction() {
        $id = (int) $this->_getParam('id', 0);

        if ($id > 0) {
            $stockOrder = $this->_actionModel->get($id);
            $brokerId = $stockOrder->getBroker()->getId();

            if ($stockOrder instanceof Application_Model_DbTable_Row_StockOrder) {
                if (!(Core_Acl::canUserDelete('default:stock-orders') || Core_Acl::canUserDelete('default:stock-orders', $brokerId))) {
                    $this->_addMessagetoQueue(_T('ACCESS_DENIED'), Core_Log::ERR);
                    $this->_gotoIndex();
                }
            }
        }
        parent::deleteAction();
    }

    public function rankAction() {
        $type = $this->_getParam('type', 'year');
        $page = (int) $this->_getParam('page', self::DEFAULT_PAGE);
        $limit = (int) $this->_getParam('limit', self::DEFAULT_LIMIT);

        $this->view->paginator = $this->_actionModel->getBrokerRanks(array(), $type, true, $limit, $page);
        $this->view->type = $type;

        $this->_setTitle(sprintf(_T('BROKER_RANK'), _T(strtoupper($type))));
    }

    public function exportAction($customStatistics=false, $dateFrom=null, $dateTo=null) {
        set_time_limit(0);

        $rankType = $this->_getParam('type', null);
        $export = null;
        $data = array();

        if($customStatistics === true && !is_null($dateFrom) && !is_null($dateTo)) {
            if(Core_Acl::isUserAllowed('default:stock-orders', 'privilege')) {
                $data = $this->_actionModel->getCustomStatistics($dateFrom, $dateTo);
                $export = new Aktiv_StockOrders_Statistics_Export_Csv("stock-orders-custom-statistics-" . date("YmdHis") . "-{$dateFrom}-{$dateTo}" . ".csv");
                $export->setMode('custom')
                        ->setDateFrom($dateFrom)
                        ->setDateTo($dateTo);
            }
        } else if ($rankType == 'week') {
            $data = $this->_actionModel->getBrokerWeekRanks();
            $export = new Aktiv_StockOrders_Statistics_Export_Csv("stock-orders-week-statistics-" . date("YmdHis") . ".csv");
            $export->setMode('week');
        } else if ($rankType == 'month') {
            $data = $this->_actionModel->getBrokerMonthRanks();
            $export = new Aktiv_StockOrders_Statistics_Export_Csv("stock-orders-month-statistics-" . date("YmdHis") . ".csv");
            $export->setMode('month');
        } else {
            $filters = $this->_getParam('filters', array());
            $data = $this->_actionModel->getRowset($filters)->toArray();
            $export = new Aktiv_StockOrders_Export_Csv("stok-orders-" . date("YmdHis") . ".csv");
        }

        if ($export instanceof Core_Export_Csv) {
            $export->setData($data)->export();
            $this->_helper->csv($export->getFullFilename());
        } else {
            $this->_goto404Page();
        }
    }

    public function statisticsAction() {
        $form = new Application_Form_Statistics();

        $firstDay = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
        $currDate = date('Y-m-d');

        if($this->getRequest()->isPost()) {
            $formParams = $this->getRequest()->getPost();

            if($form->isValid($formParams)) {
                $data = $this->getRequest()->getPost();
                $this->exportAction(true, $data['date_from'], $data['date_to']);
            }
        }

        $form->getElement('date_from')->setValue($firstDay);
        $form->getElement('date_to')->setValue($currDate);

        $this->view->form = $form;
        $this->_setTitle('CUSTOM_STATISTICS');
    }

    protected function _setDefaultFilters() {
        $dateFrom = date('Y-m-d');
        $dateTo = date('Y-m-d');

        $this->_defaultFilters = array(
            'date_from' => $dateFrom,
            'date_to' => $dateTo
        );

        // setting up filters for brokers
        if ($this->_getIdentity()->getRole() == $this->_getAppConfig('acl')->get('defaultRole', 2)) {
            $this->_defaultFilters['broker'] = $this->_getIdentity()->getId();
        }


        $this->_getFilterableForm()->setValues($this->_defaultFilters);
    }

}
