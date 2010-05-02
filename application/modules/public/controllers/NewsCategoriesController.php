<?php

/**
 * @todo add messages to flashmessenger all over the place
 */

class NewsCategoriesController extends Zend_Controller_Action
{

    public function init()
    {
        $this->model = new Planet_Model_News();
        $this->loggedInUser = $this->_helper->loggedInUser();
        $this->redirector = $this->getHelper('redirector');
        $this->urlHelper = $this->getHelper('url');
        $this->fm = $this->getHelper('flashMessenger');

        $this->view->headScript()->appendScript("
            $(function(){
                $('.delete').click(function(){
                    if(!confirm('Obrisi kategoriju? Sve vesti iz ove kategorije ce takodje biti obrisane!')) {
                        return false;
                    }
                });
            });
        ");
    }

    public function indexAction()
    {
    }

    public function adminListAction()
    {
        if(!$this->loggedInUser) {
            $this->fm->addMessage(array('fm-bad' => 'Nemate pravo pristupa!'));
            return $this->redirector->gotoRoute(null, 'login');
        }

        $page = $this->_getParam('page', 1);

        $this->view->categories = $this->model->getAllNewsCategories($page);

        $this->view->pageTitle = 'Administracija kategorija vesti';
    }
    
    public function addAction()
    {
        if(!$this->loggedInUser) {
            $this->fm->addMessage(array('fm-bad' => 'Nemate pravo pristupa!'));
            return $this->redirector->gotoRoute(null, 'login');
        }

        $addForm = $this->model->getForm('News_Categories_Add');
        $addForm->setAction($this->urlHelper->url(array(
                                                    'action' => 'add',
                                                    'controller' => 'news-categories'
                                                ),
                                                'admin', true
                                            ));
        $addForm->setSlugValidator();

        if($this->_request->isPost()) {
            if($addForm->isValid($this->_request->getPost())) {
                try {
                   $this->model->saveNewsCategory($addForm->getValues());

                   $this->fm->addMessage(array('fm-good' => 'Kategorija uspešno dodata!'));

                   return $this->redirector->gotoRoute(
                           array('action' => 'admin-list', 'controller' => 'news-categories'),
                           'admin', true
                           );
                } catch (Exception $e) {
                    $this->fm->addMessage(array('fm-bad' => $e->getMessage()));
                }
            }
        }

        $this->view->addForm = $addForm;

        $this->view->pageTitle = 'Dodavanje kategorije vesti';
    }

    public function editAction()
    {
        if(!$this->loggedInUser) {
            $this->fm->addMessage(array('fm-bad' => 'Nemate pravo pristupa!'));
            return $this->redirector->gotoRoute(null, 'login');
        }

        $id = $this->_request->getParam('id', null);

        if($id === null) {
            return $this->redirector->gotoRoute(
                           array('action' => 'admin-list', 'controller' => 'news-categories'),
                           'admin', true
                           );
        }

        $editForm = $this->model->getForm('News_Categories_Edit');
        $editForm->setAction($this->urlHelper->url(array(
                                                    'action' => 'edit',
                                                    'controller' => 'news-categories'
                                                ),
                                                'admin', true
                                            ));
        $editForm->populate($this->model->getOneNewsCategoryById($id)->toArray());
        $editForm->setSlugValidator();

        if($this->_request->isPost()) {
            if($editForm->isValid($this->_request->getPost())) {
                try {
                   $this->model->saveNewsCategory($editForm->getValues());

                   $this->fm->addMessage(array('fm-good' => 'Kategorija vesti uspešno promenjena!'));

                   return $this->redirector->gotoRoute(
                           array('action' => 'admin-list', 'controller' => 'news-categories'),
                           'admin', true
                           );
                } catch (Exception $e) {
                    $this->fm->addMessage(array('fm-bad' => $e->getMessage()));
                }
            }
        }

        $this->view->editForm = $editForm;

        $this->view->pageTitle = 'Izmena kategorije vesti';
    }

    public function deleteAction()
    {
        if(!$this->loggedInUser) {
            $this->fm->addMessage(array('fm-bad' => 'Nemate pravo pristupa!'));
            return $this->redirector->gotoRoute(null, 'login');
        }
        
        $id = $this->_request->getParam('id', null);

        if($id === null) {
            return $this->redirector->gotoRoute(
                           array('action' => 'admin-list', 'controller' => 'news-categories'),
                           'admin', true
                           );
        }

        try {
            $this->model->deleteNewsCategory($id);

            $this->fm->addMessage(array('fm-good' => 'Kategorija vesti uspešno obrisana!'));
        } catch (Exception $e) {
            $this->fm->addMessage(array('fm-bad' => $e->getMessage()));
        }
        return $this->redirector->gotoRoute(
                           array('action' => 'admin-list', 'controller' => 'news-categories'),
                           'admin', true
                           );
    }

}