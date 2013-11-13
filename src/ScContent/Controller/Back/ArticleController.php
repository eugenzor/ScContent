<?php
/**
 * ScContent (https://github.com/dphn/ScContent)
 *
 * @author    Dolphin <work.dolphin@gmail.com>
 * @copyright Copyright (c) 2013 ScContent
 * @link      https://github.com/dphn/ScContent
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace ScContent\Controller\Back;

use ScContent\Controller\AbstractBack,
    ScContent\Service\Back\ArticleService,
    ScContent\Form\Back\Article as ArticleForm,
    ScContent\Exception\RuntimeException,
    //
    Zend\View\Model\ViewModel;

/**
 * @author Dolphin <work.dolphin@gmail.com>
 */
class ArticleController extends AbstractBack
{
    /**
     * @var ScContent\Service\Back\ArticleService
     */
    protected $articleService;

    /**
     * @var ScContent\Form\Back\Article
     */
    protected $articleForm;

    /**
     * Add File.
     *
     * @return Zend\Stdlib\ResponseInterface
     */
    public function addAction()
    {
        $parent = $this->params()->fromRoute('parent');
        if (! is_numeric($parent)) {
            $this->flashMessenger()->addMessage(
                $this->translate('The article location was not specified.')
            );
            return $this->redirect()
                ->toRoute('sc-admin/content-manager')
                ->setStatusCode(303);
        }
        try {
            $categoryId = $this->getArticleService()->makeArticle($parent);
        } catch (RuntimeException $e) {
            $this->flashMessenger()->addMessage($e->getMessage());
            return $this->redirect()
                ->toRoute('sc-admin/content-manager')
                ->setStatusCode(303);
        }
        return $this->redirect()->toRoute(
            'sc-admin/article/edit',
            array('id' => $categoryId)
        );
    }

    /**
     * Edit File.
     *
     * @return Zend\Stdlib\ResponseInterface | Zend\View\Model\ViewModel
     */
    public function editAction()
    {
        $id = $this->params()->fromRoute('id');
        if (! is_numeric($id)) {
            $this->flashMessenger()->addMessage(
                $this->translate('The article ID was not specified.')
            );
            return $this->redirect()
                ->toRoute('sc-admin/content-manager')
                ->setStatusCode(303);
        }

        try {
            $article = $this->getArticleService()->getArticle($id);
        } catch (RuntimeException $e) {
            $this->flashMessenger()->addMessage($e->getMessage());
            return $this->redirect()
                ->toRoute('sc-admin/content-manager')
                ->setStatusCode(303);
        }
        $form = $this->getArticleForm();
        $form->setAttribute(
            'action',
            $this->url()->fromRoute('sc-admin/article/edit', array('id' => $id))
        );
        $form->bind($article);

        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());
            if ($form->isValid()) {
                $this->getArticleService()->saveContent($form->getData());
            }
        }

        return new ViewModel(array(
            'content' => $article,
            'form' => $form,
        ));
    }

    /**
     * @param ScContent\Service\Back\ArticleService $service
     * @return void
     */
    public function setArticleService(ArticleService $service)
    {
        $this->articleService = $service;
    }

    /**
     * @return ScContent\Service\Back\ArticleService
     */
    public function getArticleService()
    {
        if (! $this->articleService instanceof ArticleService) {
            $serviceLocator = $this->getServiceLocator();
            $this->articleService = $serviceLocator->get(
                'sc-service.back.article'
            );
        }
        return $this->articleService;
    }

    /**
     * @param ScContent\Form\Back\Article $form
     * @return void
     */
    public function setArticleForm(ArticleForm $form)
    {
        $this->articleForm = $form;
    }

    /**
     * @return ScContent\Form\Back\Article
     */
    public function getArticleForm()
    {
        if (! $this->articleForm instanceof ArticleForm) {
            $formElementManager = $this->getServiceLocator()->get(
                'FormElementManager'
            );
            $this->articleForm = $formElementManager->get(
                'sc-form.back.article'
            );
        }
        return $this->articleForm;
    }
}
