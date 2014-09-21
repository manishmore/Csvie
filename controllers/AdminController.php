<?php

class Csvie_AdminController extends Helper_Controller_Admin
{

    public static function config()
    {

        return array(
            'name' => JO_Translate::getInstance()->translate('CSVIE'),
            'has_permision' => true,
            'menu' => JO_Translate::getInstance()->translate('CSVIE'),
            'in_menu' => true,
            'permision_key' => 'csvie',
            'sort_order' => 80504
        );
    }

    private $session;

    public function init()
    {
        $this->session = JO_Session::getInstance();
    }

    /* Set global header and footer
     * @see JO_Action::preDispatch()
     */

    public function preDispatch()
    {
        $this->view->children = array(
            'header_part' => 'admin/layout/header_part',
            'footer_part' => 'admin/layout/footer_part'
        );
    }

    /////////////////// end config

    private function positions($key = null)
    {

        $modules = array(
            'home' => 'Home',
            'all' => 'All',
            'category' => 'Category',
            'item' => 'Item view',
            'shop' => 'Shop view'
        );


        if ($key) {
            return isset($modules[$key]) ? $modules[$key] : null;
        } else {
            return $modules;
        }
    }

    public function indexAction()
    {
        if ($this->session->get('successfu_edite')) {
            $this->view->successfu_edite = true;
            $this->session->clear('successfu_edite');
        }
        if ($this->session->get('error_permision')) {
            $this->view->error_permision = $this->session->get('error_permision');
            $this->session->clear('error_permision');
        }

        $request = $this->getRequest();

        $this->view->new_record_url = WM_Router::create($request->getBaseUrl() . '?module=csvie&controller=admin&action=create');


        $page = $request->getRequest('page', 1);

        $data = array(
            'start' => ($page * Helper_Config::get('config_admin_limit')) - Helper_Config::get('config_admin_limit'),
            'limit' => Helper_Config::get('config_admin_limit')
        );

        $this->view->words = array();
        $words = Banners_Model_Banners::getBannersAdmin($data);


        if ($words) {
            foreach ($words AS $word) {
                $word['text_controller'] = $this->positions($word['controller']);
                $word['edit'] = WM_Router::link('module=csvie&controller=admin&action=edit&id=' . $word['id']);
                $this->view->words[] = $word;
            }
        }


        $total_records = Banners_Model_Banners::getTotalBannersAdmin($data);

        $this->view->total_pages = ceil($total_records / Helper_Config::get('config_admin_limit'));
        $this->view->total_rows = $total_records;

        $pagination = new Admin_Model_Pagination;
        $pagination->setLimit(Helper_Config::get('config_admin_limit'));
        $pagination->setPage($page);
        $pagination->setTotal($total_records);
        $pagination->setUrl($request->getBaseUrl() . '?module=banners&controller=admin&page={page}');
        $this->view->pagination = $pagination->render();
    }

    public function installAction()
    {
        $ext = Extensions_Model_Admin::getExtension('banners');

        if (!WM_Users::allow($this->getRequest()->getModule(), $this->getRequest()->getController(), $this->getRequest()->getAction())) {
            JO_Session::set('error', $this->translate('You do not have permission to this action'));
            $this->redirect(WM_Router::create($this->getRequest()->getBaseUrl() . '?module=extensions&controller=admin&group_id=' . $ext['group_id']));
        }

        Extensions_Model_Admin::installExtensions('banners');
        $db = JO_Db::getDefaultAdapter();
        $db->query("UPDATE `admin_menu` SET `status` = 1 WHERE `title` = 'Banners' LIMIT 1");
        $this->redirect(WM_Router::create($this->getRequest()->getBaseUrl() . '?module=banners&controller=admin'));
    }

    public function uninstallAction()
    {
        $ext = Extensions_Model_Admin::getExtension('banners');

        if (!WM_Users::allow($this->getRequest()->getModule(), $this->getRequest()->getController(), $this->getRequest()->getAction())) {
            JO_Session::set('error', $this->translate('You do not have permission to this action'));
        } else {
            Extensions_Model_Admin::uninstallExtensions('banners');
            $db = JO_Db::getDefaultAdapter();
            $db->query("UPDATE `admin_menu` SET `status` = 0 WHERE `title` = 'Banners' LIMIT 1");
            JO_Session::set('success', $this->translate('The extension was uninstalled successfuly'));
        }
        $this->redirect(WM_Router::create($this->getRequest()->getBaseUrl() . '?module=extensions&controller=admin&group_id=' . $ext['group_id']));
    }

    public function createAction()
    {
        if (!WM_Users::allow('create', $this->getRequest()->getController())) {
            $this->session->set('error_permision', $this->translate('You do not have permission to this action'));
            $this->redirect(WM_Router::create($this->getRequest()->getBaseUrl() . '?module=banners&controller=admin'));
        }
        $this->setViewChange('form');

        if ($this->getRequest()->isPost()) {
            Banners_Model_Banners::create($this->getRequest()->getParams());
            $this->session->set('successfu_edite', true);
            $this->redirect(WM_Router::create($this->getRequest()->getBaseUrl() . '?module=banners&controller=admin'));
        }

        $this->getForm();
    }

    public function editAction()
    {
        if (!WM_Users::allow('edit', $this->getRequest()->getController())) {
            $this->session->set('error_permision', $this->translate('You do not have permission to this action'));
            $this->redirect(WM_Router::create($this->getRequest()->getBaseUrl() . '?module=banners&controller=admin'));
        }
        $this->setViewChange('form');

        if ($this->getRequest()->isPost()) {
            Banners_Model_Banners::edit($this->getRequest()->getQuery('id'), $this->getRequest()->getParams());
            $this->session->set('successfu_edite', true);
            $this->redirect(WM_Router::create($this->getRequest()->getBaseUrl() . '?module=banners&controller=admin'));
        }

        $this->getForm();
    }

    public function deleteAction()
    {
        $this->setInvokeArg('noViewRenderer', true);
        if (!WM_Users::allow('delete', $this->getRequest()->getController())) {
            echo $this->translate('You do not have permission to this action');
        } else {
            Banners_Model_Banners::delete($this->getRequest()->getPost('id'));
        }
    }

    public function deleteMultiAction()
    {
        $this->setInvokeArg('noViewRenderer', true);
        if (!WM_Users::allow('delete', $this->getRequest()->getController())) {
            echo $this->translate('You do not have permission to this action');
        } else {
            $action_check = $this->getRequest()->getPost('action_check');
            if ($action_check && is_array($action_check)) {
                foreach ($action_check AS $record_id) {
                    Banners_Model_Banners::delete($record_id);
                }
            }
        }
    }

    /*     * *************************************** HELP FUNCTIONS ******************************************* */

    private function getForm()
    {
        $request = $this->getRequest();

        $dic_id = $request->getRequest('id');

        if ($dic_id) {
            $banner_info = Banners_Model_Banners::getBanner($dic_id);
        }

        $this->view->cancel_url = WM_Router::create($this->getRequest()->getBaseUrl() . '?module=csvie&controller=admin');


        if ($request->getPost('status')) {
            $this->view->status = $request->getPost('status');
        } elseif (isset($banner_info['status'])) {
            $this->view->status = $banner_info['status'];
        } else {
            $this->view->status = 1;
        }

        if ($request->getPost('status_in_js_mode')) {
            $this->view->status_in_js_mode = $request->getPost('status_in_js_mode');
        } elseif (isset($banner_info['status_in_js_mode'])) {
            $this->view->status_in_js_mode = $banner_info['status_in_js_mode'];
        } else {
            $this->view->status_in_js_mode = 0;
        }

        if ($request->getPost('category_id')) {
            $this->view->category_id = $request->getPost('category_id');
        } elseif (isset($banner_info['category_id'])) {
            $this->view->category_id = $banner_info['category_id'];
        } else {
            $this->view->category_id = '';
        }

        if ($request->getPost('name')) {
            $this->view->name = $request->getPost('name');
        } elseif (isset($banner_info['name'])) {
            $this->view->name = $banner_info['name'];
        } else {
            $this->view->name = '';
        }

        if ($request->getPost('html')) {
            $this->view->html = $request->getPost('html');
        } elseif (isset($banner_info['html'])) {
            $this->view->html = $banner_info['html'];
        } else {
            $this->view->html = '';
        }

        if ($request->getPost('height')) {
            $this->view->height = $request->getPost('height');
        } elseif (isset($banner_info['height'])) {
            if ($banner_info['height']) {
                $this->view->height = $banner_info['height'];
            } else {
                $this->view->height = 300;
            }
        } else {
            $this->view->height = 300;
        }

        if ($request->getPost('width')) {
            $this->view->width = $request->getPost('width');
        } elseif (isset($banner_info['width'])) {
            if ($banner_info['width']) {
                $this->view->width = $banner_info['width'];
            } else {
                $this->view->width = 220;
            }
        } else {
            $this->view->width = 220;
        }

        if ($request->getPost('position')) {
            $this->view->position = $request->getPost('position');
        } elseif (isset($banner_info['position'])) {
            $this->view->position = $banner_info['position'];
        } else {
            $this->view->position = 0;
        }

        if ($request->getPost('controller_set')) {
            $this->view->controller_set = $request->getPost('controller_set');
        } elseif (isset($banner_info['controller'])) {
            $this->view->controller_set = $banner_info['controller'];
        } else {
            $this->view->controller_set = '';
        }

        //$this->view->categories = Model_Categories::getCategories();

        $this->view->categories = $this->view->callChildrenView('banners/admin/getCategory')->categories;

        $this->view->controllers = $this->positions();
    }

    public function getCategoryAction()
    {
        $this->noViewRenderer(true);

        $this->view->categories = $this->categories();
    }

    private function categories($category_id = null)
    {
        $filter = array();
        if ($category_id) {
            $filter['where'] = new JO_Db_Expr('category.parent_id = ' . Helper_Db::quote($category_id));
        }
        $cats = Category_Model_Categories::getCategories($filter);

        $result = array();
        foreach ($cats AS $cat) {
            $childs = $this->categories($cat['category_id']);
            if ($childs) {
                $result[] = array(
                    'category_id' => $cat['category_id'],
                    'title' => $cat['title'],
                    'disabled' => true
                );
                foreach ($childs AS $child) {
                    $result[] = array(
                        'category_id' => $child['category_id'],
                        'title' => $cat['title'] . ' > ' . $child['title'],
                        'disabled' => $child['disabled']
                    );
                }
            } else {
                $result[] = array(
                    'category_id' => $cat['category_id'],
                    'title' => $cat['title'],
                    'disabled' => false
                );
            }
        }
        return $result;
    }

}
?>
